import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';
import { postJournalEntry } from '../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;

    // Fetch journals with their lines for this tenant
    const [journals] = await db.query(
      `SELECT JournalID as id, JournalDate as date, ReferenceNo as reference, Description as notes, CreatedDate as created_at 
       FROM journal_entries 
       WHERE (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM journal_entries WHERE tenant_id = ?)))
       ORDER BY JournalDate DESC, JournalID DESC`,
      [tenantId, tenantId]
    ) as any;

    if (!journals || journals.length === 0) {
      return NextResponse.json([]);
    }

    const journalIds = journals.map((j: any) => j.id);
    const [lines] = await db.query(
      `SELECT jl.JournalID as journal_id, jl.AccountID as account_id, a.AccountName as account_name, a.AccountCode as account_code, jl.Description as description, jl.DebitAmount as debit, jl.CreditAmount as credit 
       FROM journal_entry_details jl 
       JOIN chart_of_accounts a ON jl.AccountID = a.AccountID 
       WHERE jl.JournalID IN (?)
       ORDER BY jl.JournalDetailID ASC`,
      [journalIds]
    ) as any;

    // Attach lines to journals
    const journalsWithLines = journals.map((journal: any) => {
      const jLines = lines.filter((line: any) => line.journal_id === journal.id);
      const totalDebit = jLines.reduce((sum: number, l: any) => sum + Number(l.debit || 0), 0);
      const totalCredit = jLines.reduce((sum: number, l: any) => sum + Number(l.credit || 0), 0);
      return {
        ...journal,
        total: totalDebit,
        totalDebit,
        totalCredit,
        lines: jLines
      };
    });

    return NextResponse.json(journalsWithLines);
  } catch (error) {
    console.error('Error fetching journals:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
    const userId = session.user.id ? parseInt(session.user.id) : null;
    const body = await request.json();
    const { date, reference, notes, lines } = body;

    const result = await postJournalEntry({
      tenantId,
      date,
      reference,
      notes,
      lines,
      userId: userId || undefined
    });

    return NextResponse.json(result);
  } catch (error: any) {
    console.error('Error posting journal:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 400 });
  }
}
