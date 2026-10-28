import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';
import { postJournalEntry } from '../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;

    const [expenses] = await db.query(
      'SELECT * FROM saas_expenses WHERE tenant_id = ? ORDER BY date DESC, created_at DESC',
      [tenantId]
    );

    return NextResponse.json({ expenses });
  } catch (error) {
    console.error('Error fetching expenses:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = parseInt(session.user.tenantId);
    const userId = session.user.id ? parseInt(session.user.id) : null;
    const { category, amount, date, reference_number, notes, account_id, paid_through_account_id } = await request.json();

    if (!category || !amount || !date) {
      return NextResponse.json({ message: 'Category, Amount, and Date are required' }, { status: 400 });
    }

    const parsedAmount = parseFloat(amount);
    if (isNaN(parsedAmount) || parsedAmount <= 0) {
      return NextResponse.json({ message: 'Amount must be greater than zero' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_expenses (tenant_id, category, amount, date, reference_number, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, category, parsedAmount, date, reference_number || null, notes || null]
    ) as any;

    const expenseId = result.insertId;

    // If account_id (expense account) and paid_through_account_id (cash/bank) are provided, post journal entry automatically!
    try {
      let expenseAccId = account_id ? parseInt(account_id) : null;
      let paymentAccId = paid_through_account_id ? parseInt(paid_through_account_id) : null;

      // Auto-resolve accounts if not explicitly chosen
      if (!expenseAccId) {
        // Find matching expense account in chart_of_accounts
        const [expRows] = await db.query(
          'SELECT AccountID FROM chart_of_accounts WHERE AccountTypeID = 5 AND (tenant_id = ? OR tenant_id IS NULL) AND (AccountName LIKE ? OR AccountName LIKE ?) LIMIT 1',
          [tenantId, `%${category}%`, '%Miscellaneous%']
        ) as any;
        if (expRows && expRows.length > 0) expenseAccId = expRows[0].AccountID;
      }

      if (!paymentAccId) {
        // Default to cash on hand (1001) or primary bank (1010)
        const [payRows] = await db.query(
          'SELECT AccountID FROM chart_of_accounts WHERE AccountTypeID = 1 AND (tenant_id = ? OR tenant_id IS NULL) AND (AccountCode = "1001" OR AccountName LIKE "%Cash%") LIMIT 1',
          [tenantId]
        ) as any;
        if (payRows && payRows.length > 0) paymentAccId = payRows[0].AccountID;
      }

      if (expenseAccId && paymentAccId) {
        await postJournalEntry({
          tenantId,
          date,
          reference: reference_number || `EXP-${expenseId}`,
          notes: `Expense: ${category}${notes ? ' - ' + notes : ''}`,
          userId: userId || undefined,
          lines: [
            {
              account_id: expenseAccId,
              description: `Expense debit: ${category}`,
              debit: parsedAmount,
              credit: 0
            },
            {
              account_id: paymentAccId,
              description: `Paid via cash/bank`,
              debit: 0,
              credit: parsedAmount
            }
          ]
        });
      }
    } catch (journalErr) {
      console.error('Failed to post automatic journal for expense:', journalErr);
      // Non-fatal for expense recording
    }

    return NextResponse.json({ 
      message: 'Expense added successfully', 
      expense: { id: expenseId, category, amount: parsedAmount, date } 
    });
  } catch (error) {
    console.error('Error adding expense:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
    const { id, category, amount, date, reference_number, notes } = await request.json();

    if (!id || !category || !amount || !date) {
      return NextResponse.json({ message: 'ID, Category, Amount, and Date are required' }, { status: 400 });
    }

    await db.query(
      'UPDATE saas_expenses SET category = ?, amount = ?, date = ?, reference_number = ?, notes = ? WHERE id = ? AND tenant_id = ?',
      [category, amount, date, reference_number, notes, id, tenantId]
    );

    return NextResponse.json({ message: 'Expense updated successfully' });
  } catch (error) {
    console.error('Error updating expense:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
    const url = new URL(request.url);
    const id = url.searchParams.get('id');

    if (!id) {
      return NextResponse.json({ message: 'Expense ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_expenses WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Expense deleted successfully' });
  } catch (error) {
    console.error('Error deleting expense:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
