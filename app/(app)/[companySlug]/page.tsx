import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { db } from "../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";
import { ensureTenantAccounts, getProfitAndLossData } from "../../../lib/accounting";

export default async function DashboardOverview({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const companySlug = resolvedParams.companySlug || 'dashboard';
  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
  const rawAccountType = session.user.accountType || 'Invoice';
  const isAccounting = rawAccountType === 'Accounting' || rawAccountType === 'Accounts';

  // Ensure accounts exist for accounting tenants
  if (isAccounting) {
    try {
      await ensureTenantAccounts(tenantId);
    } catch (e) {
      console.error("Account auto-seed error:", e);
    }
  }

  // Liquid funds (Cash + Bank balances)
  let liquidFunds = 0;
  let totalRevenue = 0;
  let totalExpenses = 0;
  let netProfit = 0;

  try {
    const pnl = await getProfitAndLossData(tenantId);
    totalRevenue = pnl.totalIncome;
    totalExpenses = pnl.totalExpenses;
    netProfit = pnl.netProfit;

    const [bankRows] = await db.query(
      `SELECT IFNULL(SUM(CurrentBalance), 0) as total 
       FROM chart_of_accounts 
       WHERE AccountTypeID = 1 
         AND (AccountCode LIKE '10%' OR AccountName LIKE '%Cash%' OR AccountName LIKE '%Bank%')
         AND (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))`,
      [tenantId, tenantId]
    ) as any;
    if (bankRows && bankRows.length > 0) liquidFunds = Number(bankRows[0].total) || 0;
  } catch (e) {
    console.error("Error loading accounting metrics:", e);
  }

  // Receivables & Payables
  let totalReceivables = 0;
  let totalPayables = 0;
  try {
    const [invRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_invoices WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    ) as any;
    if (invRows && invRows.length > 0) totalReceivables = Number(invRows[0].total) || 0;

    const [billRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_bills WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    ) as any;
    if (billRows && billRows.length > 0) totalPayables = Number(billRows[0].total) || 0;
  } catch (e) {
    console.error(e);
  }

  // Invoice Edition Metrics
  let totalInvoiced = 0;
  let totalCollected = 0;
  let customerCount = 0;
  if (!isAccounting) {
    try {
      const [allInv] = await db.query(
        "SELECT IFNULL(SUM(total_amount), 0) as total FROM saas_invoices WHERE tenant_id = ?",
        [tenantId]
      ) as any;
      totalInvoiced = Number(allInv[0]?.total) || 0;

      const [pmtRows] = await db.query(
        "SELECT IFNULL(SUM(amount), 0) as total FROM saas_payments WHERE tenant_id = ?",
        [tenantId]
      ) as any;
      totalCollected = Number(pmtRows[0]?.total) || 0;

      const [custRows] = await db.query(
        "SELECT COUNT(*) as count FROM saas_customers WHERE tenant_id = ?",
        [tenantId]
      ) as any;
      customerCount = Number(custRows[0]?.count) || 0;
    } catch (e) {
      console.error("Error loading invoice metrics:", e);
    }
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
  };

  return (
    <div className="page-container">
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Executive Dashboard</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Welcome back, {session.user.name}! Workspace: <strong>{companySlug}</strong> ({isAccounting ? 'Full Accounting System' : 'Invoicing Edition'})
          </p>
        </div>
        <div style={{ display: 'flex', gap: '10px' }}>
          {isAccounting ? (
            <>
              <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-primary">
                + New Journal
              </Link>
              <Link href={`/${companySlug}/expenses`} className="btn btn-outline">
                + Record Expense
              </Link>
            </>
          ) : (
            <Link href={`/${companySlug}/invoices`} className="btn btn-primary">
              + Create Invoice
            </Link>
          )}
        </div>
      </div>

      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        {isAccounting ? (
          <>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #10b981' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Liquid Capital (Bank & Cash)</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>{formatCurrency(liquidFunds)}</div>
              <div style={{ fontSize: '0.8rem', color: '#10b981', marginTop: '4px' }}>Active operating accounts</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #3b82f6' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Revenue / Sales</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>{formatCurrency(totalRevenue)}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Earned from operations</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #ef4444' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Expenditures</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>{formatCurrency(totalExpenses)}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Direct & operating expenses</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: `4px solid ${netProfit >= 0 ? '#16a34a' : '#dc2626'}` }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Net Profit / (Loss)</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: netProfit >= 0 ? '#16a34a' : '#dc2626', marginTop: '6px' }}>
                {formatCurrency(netProfit)}
              </div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>P&L Bottom Line</div>
            </div>
          </>
        ) : (
          <>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #3b82f6' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Invoiced Sales</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>{formatCurrency(totalInvoiced)}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Total value of all invoices</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #f59e0b' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Outstanding Receivables</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#f59e0b', marginTop: '6px' }}>{formatCurrency(totalReceivables)}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Unpaid customer invoices</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #10b981' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Payments Collected</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#10b981', marginTop: '6px' }}>{formatCurrency(totalCollected)}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Successfully collected revenue</div>
            </div>
            <div className="card" style={{ padding: '20px', borderLeft: '4px solid #8b5cf6' }}>
              <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Active Customers</div>
              <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>{customerCount}</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Registered client directory</div>
            </div>
          </>
        )}
      </div>

      {/* Accounting Quick Actions & Modules */}
      {isAccounting && (
        <div className="card" style={{ padding: '24px', marginBottom: '24px' }}>
          <h3 style={{ margin: '0 0 16px 0', fontSize: '1.1rem', fontWeight: 700, color: '#0f172a' }}>
            Accounting Hub Quick Navigation
          </h3>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '14px' }}>
            <Link 
              href={`/${companySlug}/chart-of-accounts`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block',
                transition: 'all 0.2s'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>📑</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Chart of Accounts</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Accounts hierarchy & balances</div>
            </Link>

            <Link 
              href={`/${companySlug}/manual-journals`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>✍️</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Manual Journals</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Adjustments & compound entries</div>
            </Link>

            <Link 
              href={`/${companySlug}/general-ledger`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>📖</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>General Ledger</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Audit trail & running balances</div>
            </Link>

            <Link 
              href={`/${companySlug}/trial-balance`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>⚖️</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Trial Balance</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Debits & credits equilibrium</div>
            </Link>

            <Link 
              href={`/${companySlug}/profit-loss`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>📊</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Profit & Loss</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Income statement & margins</div>
            </Link>

            <Link 
              href={`/${companySlug}/balance-sheet`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>🏛️</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Balance Sheet</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Assets, Liabilities & Equity</div>
            </Link>

            <Link 
              href={`/${companySlug}/banking`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>🏦</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Bank & Cash</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Commercial banks & drawers</div>
            </Link>

            <Link 
              href={`/${companySlug}/expenses`} 
              style={{
                padding: '16px',
                borderRadius: '8px',
                backgroundColor: '#f8fafc',
                border: '1px solid #e2e8f0',
                textDecoration: 'none',
                display: 'block'
              }}
            >
              <div style={{ fontSize: '1.2rem', marginBottom: '6px' }}>💳</div>
              <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '0.95rem' }}>Expenses</div>
              <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Disbursements with ledger sync</div>
            </Link>
          </div>
        </div>
      )}

      {/* Receivables & Payables Summary */}
      <div style={{ display: 'grid', gridTemplateColumns: isAccounting ? '1fr 1fr' : '1fr', gap: '20px' }}>
        <div className="card" style={{ padding: '20px' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
            <h3 style={{ margin: 0, fontSize: '1rem', fontWeight: 700, color: '#0f172a' }}>Receivables Summary</h3>
            <Link href={`/${companySlug}/invoices`} style={{ fontSize: '0.85rem', color: '#2563eb', textDecoration: 'none', fontWeight: 600 }}>View Invoices →</Link>
          </div>
          <div style={{ fontSize: '1.6rem', fontWeight: 700, color: '#16a34a', marginBottom: '6px' }}>
            {formatCurrency(totalReceivables)}
          </div>
          <div style={{ fontSize: '0.85rem', color: '#64748b' }}>Pending customer collections on issued invoices</div>
        </div>

        {isAccounting && (
          <div className="card" style={{ padding: '20px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
              <h3 style={{ margin: 0, fontSize: '1rem', fontWeight: 700, color: '#0f172a' }}>Payables Summary</h3>
              <Link href={`/${companySlug}/bills`} style={{ fontSize: '0.85rem', color: '#2563eb', textDecoration: 'none', fontWeight: 600 }}>View Bills →</Link>
            </div>
            <div style={{ fontSize: '1.6rem', fontWeight: 700, color: '#dc2626', marginBottom: '6px' }}>
              {formatCurrency(totalPayables)}
            </div>
            <div style={{ fontSize: '0.85rem', color: '#64748b' }}>Outstanding vendor bills due</div>
          </div>
        )}
      </div>
    </div>
  );
}
