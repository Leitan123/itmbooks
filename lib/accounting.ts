import { db } from './db';

export interface AccountRow {
  id: number;
  code: string;
  name: string;
  type: string;
  typeId: number;
  openingBalance: number;
  balance: number;
  status: number;
}

export interface JournalLineInput {
  account_id: number;
  description?: string;
  debit: number;
  credit: number;
}

export interface CreateJournalInput {
  tenantId: number;
  date: string;
  reference?: string;
  notes?: string;
  lines: JournalLineInput[];
  userId?: number;
}

/**
 * Standard default Chart of Accounts for new tenants
 */
export const DEFAULT_ACCOUNTS = [
  // 1. Assets (AccountTypeID = 1)
  { code: '1001', name: 'Cash on Hand', typeId: 1, openingBalance: 0 },
  { code: '1002', name: 'Petty Cash', typeId: 1, openingBalance: 0 },
  { code: '1010', name: 'Primary Business Bank Account', typeId: 1, openingBalance: 0 },
  { code: '1020', name: 'Savings Account', typeId: 1, openingBalance: 0 },
  { code: '1100', name: 'Accounts Receivable (Debtors)', typeId: 1, openingBalance: 0 },
  { code: '1200', name: 'Merchandise Inventory', typeId: 1, openingBalance: 0 },
  { code: '1250', name: 'Prepaid Expenses', typeId: 1, openingBalance: 0 },
  { code: '1300', name: 'Office Equipment', typeId: 1, openingBalance: 0 },
  { code: '1310', name: 'Computer & Hardware', typeId: 1, openingBalance: 0 },
  { code: '1320', name: 'Furniture & Fixtures', typeId: 1, openingBalance: 0 },
  { code: '1330', name: 'Motor Vehicles', typeId: 1, openingBalance: 0 },

  // 2. Liabilities (AccountTypeID = 2)
  { code: '2001', name: 'Accounts Payable (Creditors)', typeId: 2, openingBalance: 0 },
  { code: '2010', name: 'Accrued Operating Expenses', typeId: 2, openingBalance: 0 },
  { code: '2020', name: 'Sales Tax / VAT Payable', typeId: 2, openingBalance: 0 },
  { code: '2030', name: 'EPF / ETF Payable', typeId: 2, openingBalance: 0 },
  { code: '2040', name: 'Payroll & Salaries Payable', typeId: 2, openingBalance: 0 },
  { code: '2100', name: 'Bank Loan / Overdraft', typeId: 2, openingBalance: 0 },
  { code: '2200', name: 'Director Loan Account', typeId: 2, openingBalance: 0 },

  // 3. Equity (AccountTypeID = 3)
  { code: '3001', name: "Owner's Capital", typeId: 3, openingBalance: 0 },
  { code: '3002', name: "Owner's Drawings", typeId: 3, openingBalance: 0 },
  { code: '3003', name: 'Retained Earnings', typeId: 3, openingBalance: 0 },

  // 4. Income / Revenue (AccountTypeID = 4)
  { code: '4001', name: 'Sales Revenue', typeId: 4, openingBalance: 0 },
  { code: '4002', name: 'Service & Consulting Revenue', typeId: 4, openingBalance: 0 },
  { code: '4003', name: 'Software Development Income', typeId: 4, openingBalance: 0 },
  { code: '4004', name: 'Hosting & Domain Income', typeId: 4, openingBalance: 0 },
  { code: '4090', name: 'Other Operating Income', typeId: 4, openingBalance: 0 },

  // 5. Expenses (AccountTypeID = 5)
  { code: '5001', name: 'Cost of Goods Sold (COGS)', typeId: 5, openingBalance: 0 },
  { code: '5002', name: 'Salaries & Wages', typeId: 5, openingBalance: 0 },
  { code: '5003', name: 'EPF / ETF Employer Contribution', typeId: 5, openingBalance: 0 },
  { code: '5004', name: 'Rent & Facilities Expense', typeId: 5, openingBalance: 0 },
  { code: '5005', name: 'Electricity & Power Bill', typeId: 5, openingBalance: 0 },
  { code: '5006', name: 'Water & Utilities', typeId: 5, openingBalance: 0 },
  { code: '5007', name: 'Telephone & Internet Expense', typeId: 5, openingBalance: 0 },
  { code: '5008', name: 'Hosting, Cloud & Server Costs', typeId: 5, openingBalance: 0 },
  { code: '5009', name: 'Software Subscriptions & SaaS', typeId: 5, openingBalance: 0 },
  { code: '5010', name: 'Marketing & Advertising', typeId: 5, openingBalance: 0 },
  { code: '5011', name: 'Office Supplies & Stationery', typeId: 5, openingBalance: 0 },
  { code: '5012', name: 'Travel, Fuel & Transport', typeId: 5, openingBalance: 0 },
  { code: '5013', name: 'Vehicle Maintenance & Repairs', typeId: 5, openingBalance: 0 },
  { code: '5014', name: 'Legal & Professional Accounting Fees', typeId: 5, openingBalance: 0 },
  { code: '5015', name: 'Bank Charges & Transaction Fees', typeId: 5, openingBalance: 0 },
  { code: '5016', name: 'Insurance Expense', typeId: 5, openingBalance: 0 },
  { code: '5017', name: 'Staff Welfare & Entertainment', typeId: 5, openingBalance: 0 },
  { code: '5099', name: 'Miscellaneous Expenses', typeId: 5, openingBalance: 0 }
];

/**
 * Normal balance type:
 * Assets (1) & Expenses (5) => Normal Debit
 * Liabilities (2), Equity (3), Income (4) => Normal Credit
 */
export function isDebitNormal(typeId: number): boolean {
  return typeId === 1 || typeId === 5;
}

/**
 * Ensure tenant has accounts seeded.
 */
export async function ensureTenantAccounts(tenantId: number) {
  const [existing] = await db.query(
    'SELECT AccountID FROM chart_of_accounts WHERE tenant_id = ? LIMIT 1',
    [tenantId]
  ) as any;

  if (!existing || existing.length === 0) {
    console.log(`Seeding default Chart of Accounts for tenant ${tenantId}...`);
    for (const acc of DEFAULT_ACCOUNTS) {
      await db.query(
        'INSERT INTO chart_of_accounts (tenant_id, AccountCode, AccountName, AccountTypeID, OpeningBalance, CurrentBalance, Status, CreatedDate) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())',
        [tenantId, acc.code, acc.name, acc.typeId, acc.openingBalance, acc.openingBalance]
      );
    }
    
    // Also create default Bank record linked to primary bank account
    const [primaryBankAcc] = await db.query(
      'SELECT AccountID FROM chart_of_accounts WHERE tenant_id = ? AND AccountCode = ? LIMIT 1',
      [tenantId, '1010']
    ) as any;

    if (primaryBankAcc && primaryBankAcc.length > 0) {
      await db.query(
        'INSERT INTO banks (tenant_id, BankName, AccountName, AccountNumber, Branch, OpeningBalance, AccountID, CreatedDate) VALUES (?, ?, ?, ?, ?, 0.00, ?, NOW())',
        [tenantId, 'Primary Commercial Bank', 'Main Operating Account', '1000-4521-8890', 'Main Branch', primaryBankAcc[0].AccountID]
      );
    }
  }
}

/**
 * Recalculate account balance from all posted journal entries
 */
export async function recalculateAccountBalance(connOrPool: any, accountId: number) {
  const [accRows] = await connOrPool.query(
    'SELECT AccountID, AccountTypeID, IFNULL(OpeningBalance, 0) as OpeningBalance FROM chart_of_accounts WHERE AccountID = ?',
    [accountId]
  ) as any;

  if (!accRows || accRows.length === 0) return;
  const acc = accRows[0];
  const typeId = Number(acc.AccountTypeID);
  const opening = Number(acc.OpeningBalance);

  const [sumRows] = await connOrPool.query(
    'SELECT IFNULL(SUM(DebitAmount), 0) as TotalDebit, IFNULL(SUM(CreditAmount), 0) as TotalCredit FROM journal_entry_details WHERE AccountID = ?',
    [accountId]
  ) as any;

  const totalDebit = Number(sumRows[0]?.TotalDebit || 0);
  const totalCredit = Number(sumRows[0]?.TotalCredit || 0);

  let newBalance = 0;
  if (isDebitNormal(typeId)) {
    // Assets & Expenses: Debit increases, Credit decreases
    newBalance = opening + totalDebit - totalCredit;
  } else {
    // Liabilities, Equity, Income: Credit increases, Debit decreases
    newBalance = opening + totalCredit - totalDebit;
  }

  await connOrPool.query(
    'UPDATE chart_of_accounts SET CurrentBalance = ? WHERE AccountID = ?',
    [newBalance, accountId]
  );
}

/**
 * Post a double-entry journal entry atomically
 */
export async function postJournalEntry(input: CreateJournalInput) {
  const { tenantId, date, reference, notes, lines, userId } = input;

  if (!date || !lines || lines.length < 2) {
    throw new Error('Journal must have a date and at least two lines');
  }

  let totalDebit = 0;
  let totalCredit = 0;
  for (const line of lines) {
    if (!line.account_id) throw new Error('All lines must have an account');
    totalDebit += Number(line.debit) || 0;
    totalCredit += Number(line.credit) || 0;
  }

  if (Math.abs(totalDebit - totalCredit) > 0.001 || totalDebit <= 0) {
    throw new Error('Total Debits must equal Total Credits and be greater than 0');
  }

  const conn = await (db as any).getConnection();
  try {
    await conn.beginTransaction();

    const refNo = reference || `JN-${date.replace(/-/g, '')}-${Math.floor(100 + Math.random() * 900)}`;

    const [jResult] = await conn.query(
      'INSERT INTO journal_entries (tenant_id, JournalDate, ReferenceNo, Description, CreatedBy, CreatedDate) VALUES (?, ?, ?, ?, ?, NOW())',
      [tenantId, date, refNo, notes || '', userId || null]
    ) as any;

    const journalId = jResult.insertId;
    const affectedAccountIds = new Set<number>();

    for (const line of lines) {
      await conn.query(
        'INSERT INTO journal_entry_details (JournalID, AccountID, DebitAmount, CreditAmount, Description) VALUES (?, ?, ?, ?, ?)',
        [journalId, line.account_id, Number(line.debit) || 0, Number(line.credit) || 0, line.description || '']
      );
      affectedAccountIds.add(Number(line.account_id));
    }

    // Recalculate balances for all affected accounts
    for (const accId of affectedAccountIds) {
      await recalculateAccountBalance(conn, accId);
    }

    await conn.commit();
    return { success: true, journalId, referenceNo: refNo };
  } catch (err) {
    await conn.rollback();
    throw err;
  } finally {
    conn.release();
  }
}

/**
 * Fetch Trial Balance
 */
export async function getTrialBalanceData(tenantId: number, fromDate?: string, toDate?: string) {
  await ensureTenantAccounts(tenantId);

  let dateFilter = '';
  const params: any[] = [tenantId];
  if (fromDate) {
    dateFilter += ' AND je.JournalDate >= ?';
    params.push(fromDate);
  }
  if (toDate) {
    dateFilter += ' AND je.JournalDate <= ?';
    params.push(toDate);
  }

  const sql = `
    SELECT 
      coa.AccountID as id,
      coa.AccountCode as code,
      coa.AccountName as name,
      coa.AccountTypeID as typeId,
      at.AccountTypeName as typeName,
      coa.OpeningBalance as openingBalance,
      IFNULL(SUM(jed.DebitAmount), 0) as periodDebit,
      IFNULL(SUM(jed.CreditAmount), 0) as periodCredit
    FROM chart_of_accounts coa
    JOIN account_types at ON coa.AccountTypeID = at.AccountTypeID
    LEFT JOIN journal_entry_details jed ON jed.AccountID = coa.AccountID
    LEFT JOIN journal_entries je ON je.JournalID = jed.JournalID ${dateFilter}
    WHERE (coa.tenant_id = ? OR (coa.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
    GROUP BY coa.AccountID
    ORDER BY coa.AccountCode ASC
  `;

  // Provide tenantId twice for query params
  const [rows] = await db.query(sql, [...params.slice(1), tenantId, tenantId]) as any;

  let totalDebit = 0;
  let totalCredit = 0;

  const records = rows.map((r: any) => {
    const typeId = Number(r.typeId);
    const opening = Number(r.openingBalance || 0);
    const pDebit = Number(r.periodDebit || 0);
    const pCredit = Number(r.periodCredit || 0);

    let netDebit = 0;
    let netCredit = 0;

    if (isDebitNormal(typeId)) {
      const net = opening + pDebit - pCredit;
      if (net >= 0) {
        netDebit = net;
      } else {
        netCredit = Math.abs(net);
      }
    } else {
      const net = opening + pCredit - pDebit;
      if (net >= 0) {
        netCredit = net;
      } else {
        netDebit = Math.abs(net);
      }
    }

    totalDebit += netDebit;
    totalCredit += netCredit;

    return {
      id: r.id,
      code: r.code,
      name: r.name,
      typeId,
      typeName: r.typeName,
      openingBalance: opening,
      periodDebit: pDebit,
      periodCredit: pCredit,
      netDebit,
      netCredit
    };
  });

  return {
    records: records.filter((r: any) => r.netDebit !== 0 || r.netCredit !== 0 || r.openingBalance !== 0 || r.periodDebit !== 0 || r.periodCredit !== 0),
    totalDebit,
    totalCredit,
    difference: Math.abs(totalDebit - totalCredit),
    isBalanced: Math.abs(totalDebit - totalCredit) < 0.01
  };
}

/**
 * Fetch Profit & Loss Statement (Income Statement)
 */
export async function getProfitAndLossData(tenantId: number, fromDate?: string, toDate?: string) {
  await ensureTenantAccounts(tenantId);

  let dateFilter = '';
  const params: any[] = [];
  if (fromDate) {
    dateFilter += ' AND je.JournalDate >= ?';
    params.push(fromDate);
  }
  if (toDate) {
    dateFilter += ' AND je.JournalDate <= ?';
    params.push(toDate);
  }

  const sql = `
    SELECT 
      coa.AccountID as id,
      coa.AccountCode as code,
      coa.AccountName as name,
      coa.AccountTypeID as typeId,
      at.AccountTypeName as typeName,
      coa.OpeningBalance as openingBalance,
      IFNULL(SUM(jed.DebitAmount), 0) as totalDebit,
      IFNULL(SUM(jed.CreditAmount), 0) as totalCredit
    FROM chart_of_accounts coa
    JOIN account_types at ON coa.AccountTypeID = at.AccountTypeID
    LEFT JOIN journal_entry_details jed ON jed.AccountID = coa.AccountID
    LEFT JOIN journal_entries je ON je.JournalID = jed.JournalID ${dateFilter}
    WHERE coa.AccountTypeID IN (4, 5)
      AND (coa.tenant_id = ? OR (coa.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
    GROUP BY coa.AccountID
    ORDER BY coa.AccountCode ASC
  `;

  const [rows] = await db.query(sql, [...params, tenantId, tenantId]) as any;

  const incomeAccounts: any[] = [];
  const expenseAccounts: any[] = [];
  let totalIncome = 0;
  let totalExpenses = 0;

  for (const r of rows) {
    const typeId = Number(r.typeId);
    const opening = Number(r.openingBalance || 0);
    const debits = Number(r.totalDebit || 0);
    const credits = Number(r.totalCredit || 0);

    if (typeId === 4) {
      // Income: Credit increases, Debit decreases
      const amount = opening + credits - debits;
      if (amount !== 0) {
        incomeAccounts.push({ id: r.id, code: r.code, name: r.name, amount });
        totalIncome += amount;
      }
    } else if (typeId === 5) {
      // Expenses: Debit increases, Credit decreases
      const amount = opening + debits - credits;
      if (amount !== 0) {
        expenseAccounts.push({ id: r.id, code: r.code, name: r.name, amount });
        totalExpenses += amount;
      }
    }
  }

  const netProfit = totalIncome - totalExpenses;

  return {
    incomeAccounts,
    totalIncome,
    expenseAccounts,
    totalExpenses,
    netProfit
  };
}

/**
 * Fetch Balance Sheet
 */
export async function getBalanceSheetData(tenantId: number, asOfDate?: string) {
  await ensureTenantAccounts(tenantId);

  let dateFilter = '';
  const params: any[] = [];
  if (asOfDate) {
    dateFilter += ' AND je.JournalDate <= ?';
    params.push(asOfDate);
  }

  const sql = `
    SELECT 
      coa.AccountID as id,
      coa.AccountCode as code,
      coa.AccountName as name,
      coa.AccountTypeID as typeId,
      at.AccountTypeName as typeName,
      coa.OpeningBalance as openingBalance,
      IFNULL(SUM(jed.DebitAmount), 0) as totalDebit,
      IFNULL(SUM(jed.CreditAmount), 0) as totalCredit
    FROM chart_of_accounts coa
    JOIN account_types at ON coa.AccountTypeID = at.AccountTypeID
    LEFT JOIN journal_entry_details jed ON jed.AccountID = coa.AccountID
    LEFT JOIN journal_entries je ON je.JournalID = jed.JournalID ${dateFilter}
    WHERE (coa.tenant_id = ? OR (coa.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
    GROUP BY coa.AccountID
    ORDER BY coa.AccountCode ASC
  `;

  const [rows] = await db.query(sql, [...params, tenantId, tenantId]) as any;

  const assets: any[] = [];
  const liabilities: any[] = [];
  const equity: any[] = [];

  let totalAssets = 0;
  let totalLiabilities = 0;
  let totalEquity = 0;
  let currentPeriodIncome = 0;
  let currentPeriodExpenses = 0;

  for (const r of rows) {
    const typeId = Number(r.typeId);
    const opening = Number(r.openingBalance || 0);
    const debits = Number(r.totalDebit || 0);
    const credits = Number(r.totalCredit || 0);

    if (typeId === 1) {
      // Assets
      const balance = opening + debits - credits;
      if (balance !== 0) {
        assets.push({ id: r.id, code: r.code, name: r.name, balance });
        totalAssets += balance;
      }
    } else if (typeId === 2) {
      // Liabilities
      const balance = opening + credits - debits;
      if (balance !== 0) {
        liabilities.push({ id: r.id, code: r.code, name: r.name, balance });
        totalLiabilities += balance;
      }
    } else if (typeId === 3) {
      // Equity
      const balance = opening + credits - debits;
      if (balance !== 0) {
        equity.push({ id: r.id, code: r.code, name: r.name, balance });
        totalEquity += balance;
      }
    } else if (typeId === 4) {
      // Income
      currentPeriodIncome += (opening + credits - debits);
    } else if (typeId === 5) {
      // Expenses
      currentPeriodExpenses += (opening + debits - credits);
    }
  }

  // Current Period Net Profit rolls into Equity
  const currentPeriodProfit = currentPeriodIncome - currentPeriodExpenses;
  totalEquity += currentPeriodProfit;

  return {
    assets,
    totalAssets,
    liabilities,
    totalLiabilities,
    equity,
    currentPeriodProfit,
    totalEquity,
    totalLiabilitiesAndEquity: totalLiabilities + totalEquity,
    isBalanced: Math.abs(totalAssets - (totalLiabilities + totalEquity)) < 0.01
  };
}

/**
 * Fetch General Ledger for account or all accounts
 */
export async function getGeneralLedgerData(tenantId: number, accountId?: number, fromDate?: string, toDate?: string) {
  await ensureTenantAccounts(tenantId);

  let where = 'WHERE (coa.tenant_id = ? OR (coa.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))';
  const params: any[] = [tenantId, tenantId];

  if (accountId) {
    where += ' AND coa.AccountID = ?';
    params.push(accountId);
  }

  const [accounts] = await db.query(
    `SELECT coa.AccountID as id, coa.AccountCode as code, coa.AccountName as name, coa.AccountTypeID as typeId, at.AccountTypeName as typeName, coa.OpeningBalance as openingBalance 
     FROM chart_of_accounts coa 
     JOIN account_types at ON coa.AccountTypeID = at.AccountTypeID 
     ${where} 
     ORDER BY coa.AccountCode ASC`,
    params
  ) as any;

  const ledger: any[] = [];

  for (const acc of accounts) {
    let lineWhere = 'WHERE jed.AccountID = ?';
    const lineParams: any[] = [acc.id];
    if (fromDate) {
      lineWhere += ' AND je.JournalDate >= ?';
      lineParams.push(fromDate);
    }
    if (toDate) {
      lineWhere += ' AND je.JournalDate <= ?';
      lineParams.push(toDate);
    }

    const [txRows] = await db.query(
      `SELECT jed.JournalDetailID as id, je.JournalDate as date, je.ReferenceNo as reference, jed.Description as description, jed.DebitAmount as debit, jed.CreditAmount as credit 
       FROM journal_entry_details jed 
       JOIN journal_entries je ON je.JournalID = jed.JournalID 
       ${lineWhere} 
       ORDER BY je.JournalDate ASC, jed.JournalDetailID ASC`,
      lineParams
    ) as any;

    const typeId = Number(acc.typeId);
    let runningBalance = Number(acc.openingBalance || 0);

    const transactions = txRows.map((tx: any) => {
      const debit = Number(tx.debit || 0);
      const credit = Number(tx.credit || 0);
      if (isDebitNormal(typeId)) {
        runningBalance += (debit - credit);
      } else {
        runningBalance += (credit - debit);
      }
      return {
        ...tx,
        debit,
        credit,
        runningBalance
      };
    });

    const totalDebit = transactions.reduce((sum: number, t: any) => sum + t.debit, 0);
    const totalCredit = transactions.reduce((sum: number, t: any) => sum + t.credit, 0);

    ledger.push({
      account: {
        id: acc.id,
        code: acc.code,
        name: acc.name,
        typeId,
        typeName: acc.typeName,
        openingBalance: Number(acc.openingBalance || 0),
        closingBalance: runningBalance
      },
      transactions,
      totalDebit,
      totalCredit
    });
  }

  return ledger;
}
