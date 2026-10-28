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

    const [rows] = await db.query(
      `SELECT c.AccountID as id, c.AccountCode as code, c.AccountName as name, t.AccountTypeName as type, t.AccountTypeID as typeId, c.CurrentBalance as balance, c.OpeningBalance as openingBalance
       FROM chart_of_accounts c 
       JOIN account_types t ON c.AccountTypeID = t.AccountTypeID 
       ORDER BY c.AccountCode ASC`
    );

    return NextResponse.json(rows);
  } catch (error) {
    console.error('Error fetching accounts:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions);
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const body = await request.json();
    const { code, name, typeId, openingBalance } = body;

    if (!code || !name || !typeId) {
      return NextResponse.json({ error: 'Code, Name, and Type are required' }, { status: 400 });
    }

    // Check if code already exists
    const [existing] = await db.query(
      'SELECT AccountID FROM chart_of_accounts WHERE AccountCode = ?',
      [code]
    ) as any;

    if (existing && existing.length > 0) {
      return NextResponse.json({ error: 'Account code already exists' }, { status: 400 });
    }

    const initialBalance = openingBalance ? parseFloat(openingBalance) : 0.00;

    const [result] = await db.query(
      'INSERT INTO chart_of_accounts (AccountCode, AccountName, AccountTypeID, OpeningBalance, CurrentBalance) VALUES (?, ?, ?, ?, ?)',
      [code, name, parseInt(typeId), initialBalance, initialBalance]
    ) as any;

    return NextResponse.json({ success: true, id: result.insertId });
  } catch (error) {
    console.error('Error creating account:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
