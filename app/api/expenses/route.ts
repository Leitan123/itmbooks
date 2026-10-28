import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';

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

    const tenantId = session.user.tenantId;
    const { category, amount, date, reference_number, notes } = await request.json();

    if (!category || !amount || !date) {
      return NextResponse.json({ message: 'Category, Amount, and Date are required' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_expenses (tenant_id, category, amount, date, reference_number, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, category, amount, date, reference_number, notes]
    ) as any;

    return NextResponse.json({ 
      message: 'Expense added successfully', 
      expense: { id: result.insertId, category, amount, date } 
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
