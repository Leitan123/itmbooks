import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions);
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    // Fetch journals with their lines
    const [journals] = await db.query(
      'SELECT JournalID as id, JournalDate as date, ReferenceNo as reference, Description as notes, CreatedDate as created_at FROM journal_entries ORDER BY JournalDate DESC'
    ) as any;

    if (journals.length === 0) {
      return NextResponse.json([]);
    }

    const journalIds = journals.map((j: any) => j.id);
    const [lines] = await db.query(
      `SELECT jl.JournalID as journal_id, jl.AccountID as account_id, a.AccountName as account_name, a.AccountCode as account_code, jl.Description as description, jl.DebitAmount as debit, jl.CreditAmount as credit 
       FROM journal_entry_details jl 
       JOIN chart_of_accounts a ON jl.AccountID = a.AccountID 
       WHERE jl.JournalID IN (?)`,
      [journalIds]
    ) as any;

    // Attach lines to journals
    const journalsWithLines = journals.map((journal: any) => {
      return {
        ...journal,
        lines: lines.filter((line: any) => line.journal_id === journal.id)
      };
    });

    return NextResponse.json(journalsWithLines);
  } catch (error) {
    console.error('Error fetching journals:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const session = await getServerSession(authOptions);
  if (!session || !session.user) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  const body = await request.json();
  const { date, reference, notes, lines } = body;

  if (!date || !lines || lines.length < 2) {
    return NextResponse.json({ error: 'Invalid journal entry' }, { status: 400 });
  }

  // Validate double entry (Debits == Credits)
  let totalDebit = 0;
  let totalCredit = 0;
  for (const line of lines) {
    if (!line.account_id) {
      return NextResponse.json({ error: 'Account is required for all lines' }, { status: 400 });
    }
    totalDebit += Number(line.debit) || 0;
    totalCredit += Number(line.credit) || 0;
  }

  // Use a small epsilon to handle JS floating point inaccuracies
  if (Math.abs(totalDebit - totalCredit) > 0.001) {
    return NextResponse.json({ error: 'Debits must equal Credits' }, { status: 400 });
  }

  const connection = await (db as any).getConnection();
  try {
    await connection.beginTransaction();

    // Insert journal
    const [journalResult] = await connection.query(
      'INSERT INTO journal_entries (JournalDate, ReferenceNo, Description) VALUES (?, ?, ?)',
      [date, reference || null, notes || null]
    ) as any;
    
    const journalId = journalResult.insertId;

    // Insert lines
    for (const line of lines) {
      await connection.query(
        'INSERT INTO journal_entry_details (JournalID, AccountID, Description, DebitAmount, CreditAmount) VALUES (?, ?, ?, ?, ?)',
        [journalId, line.account_id, line.description || null, line.debit || 0, line.credit || 0]
      );
      
      // Update account current balance (we should probably do this)
      // Debit to Asset/Expense increases balance, Credit decreases.
      // Debit to Liability/Equity/Income decreases balance, Credit increases.
      // For simplicity, we just insert the manual journal lines here. Let's not make assumptions about balance updating logic unless necessary.
    }

    await connection.commit();
    connection.release();

    return NextResponse.json({ success: true, id: journalId });
  } catch (error) {
    await connection.rollback();
    connection.release();
    console.error('Error creating journal:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
