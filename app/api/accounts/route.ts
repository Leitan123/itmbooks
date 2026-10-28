import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';
import { ensureTenantAccounts } from '../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
    await ensureTenantAccounts(tenantId);

    const [rows] = await db.query(
      `SELECT c.AccountID as id, c.AccountCode as code, c.AccountName as name, t.AccountTypeName as type, t.AccountTypeID as typeId, c.CurrentBalance as balance, c.OpeningBalance as openingBalance
       FROM chart_of_accounts c 
       JOIN account_types t ON c.AccountTypeID = t.AccountTypeID 
       WHERE (c.tenant_id = ? OR (c.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
       ORDER BY c.AccountCode ASC`,
      [tenantId, tenantId]
    ) as any;

    return NextResponse.json(rows);
  } catch (error) {
    console.error('Error fetching accounts:', error);
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
    const body = await request.json();
    const { code, name, typeId, openingBalance } = body;

    if (!code || !name || !typeId) {
      return NextResponse.json({ error: 'Code, Name, and Type are required' }, { status: 400 });
    }

    // Check if code already exists for this tenant
    const [existing] = await db.query(
      'SELECT AccountID FROM chart_of_accounts WHERE AccountCode = ? AND (tenant_id = ? OR tenant_id IS NULL)',
      [code, tenantId]
    ) as any;

    if (existing && existing.length > 0) {
      return NextResponse.json({ error: 'Account code already exists' }, { status: 400 });
    }

    const initialBalance = openingBalance ? parseFloat(openingBalance) : 0.00;

    const [result] = await db.query(
      'INSERT INTO chart_of_accounts (tenant_id, AccountCode, AccountName, AccountTypeID, OpeningBalance, CurrentBalance) VALUES (?, ?, ?, ?, ?, ?)',
      [tenantId, code, name, parseInt(typeId), initialBalance, initialBalance]
    ) as any;

    return NextResponse.json({ success: true, id: result.insertId });
  } catch (error) {
    console.error('Error creating account:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
