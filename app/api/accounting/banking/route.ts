import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../auth/[...nextauth]/route';
import { db } from '../../../../lib/db';
import { ensureTenantAccounts } from '../../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
    await ensureTenantAccounts(tenantId);

    // Fetch banks
    const [banks] = await db.query(
      `SELECT b.BankID as id, b.BankName as name, b.AccountName as accountName, b.AccountNumber as accountNumber, b.Branch as branch, b.OpeningBalance as openingBalance, coa.CurrentBalance as currentBalance, coa.AccountID as accountId, coa.AccountCode as accountCode
       FROM banks b
       LEFT JOIN chart_of_accounts coa ON b.AccountID = coa.AccountID
       WHERE (b.tenant_id = ? OR (b.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM banks WHERE tenant_id = ?)))
         AND (b.IsDeleted = 0 OR b.IsDeleted IS NULL)
       ORDER BY b.BankName ASC`,
      [tenantId, tenantId]
    ) as any;

    // Also fetch Cash accounts from Chart of Accounts
    const [cashAccounts] = await db.query(
      `SELECT AccountID as id, AccountCode as code, AccountName as name, CurrentBalance as balance 
       FROM chart_of_accounts 
       WHERE AccountTypeID = 1 
         AND (AccountName LIKE '%Cash%' OR AccountCode IN ('1001', '1002'))
         AND (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))`,
      [tenantId, tenantId]
    ) as any;

    return NextResponse.json({ banks, cashAccounts });
  } catch (error: any) {
    console.error('Error fetching banking data:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
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
    const { bankName, accountName, accountNumber, branch, openingBalance } = body;

    if (!bankName || !accountNumber) {
      return NextResponse.json({ error: 'Bank Name and Account Number are required' }, { status: 400 });
    }

    const opBal = openingBalance ? parseFloat(openingBalance) : 0.00;

    // Generate a unique account code for COA under 1000 series
    const [maxCodeRow] = await db.query(
      'SELECT MAX(CAST(AccountCode as UNSIGNED)) as maxCode FROM chart_of_accounts WHERE AccountTypeID = 1 AND AccountCode LIKE "10%" AND (tenant_id = ? OR tenant_id IS NULL)',
      [tenantId]
    ) as any;
    const nextCode = (maxCodeRow[0]?.maxCode ? maxCodeRow[0].maxCode + 1 : 1030).toString();

    // Create linked COA account
    const [coaResult] = await db.query(
      'INSERT INTO chart_of_accounts (tenant_id, AccountCode, AccountName, AccountTypeID, OpeningBalance, CurrentBalance, Status, CreatedDate) VALUES (?, ?, ?, 1, ?, ?, 1, NOW())',
      [tenantId, nextCode, `${bankName} (${accountNumber.slice(-4)})`, opBal, opBal]
    ) as any;

    const coaId = coaResult.insertId;

    // Create bank record
    const [bankResult] = await db.query(
      'INSERT INTO banks (tenant_id, BankName, AccountName, AccountNumber, Branch, OpeningBalance, AccountID, CreatedDate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, bankName, accountName || bankName, accountNumber, branch || '', opBal, coaId]
    ) as any;

    return NextResponse.json({ success: true, bankId: bankResult.insertId, accountId: coaId });
  } catch (error: any) {
    console.error('Error creating bank account:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}
