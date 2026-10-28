import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { db } from "../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";

export default async function DashboardOverview({ params }: { params: { companySlug: string } }) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const tenantId = session.user.tenantId;

  // Total Receivables (Unpaid Invoices)
  let totalReceivables = 0;
  try {
    const [invRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_invoices WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    ) as any;
    if (invRows && invRows.length > 0) totalReceivables = Number(invRows[0].total) || 0;
  } catch (e) {
    console.error(e);
  }

  // Total Payables (Unpaid Bills)
  let totalPayables = 0;
  try {
    const [billRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_bills WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    ) as any;
    if (billRows && billRows.length > 0) totalPayables = Number(billRows[0].total) || 0;
  } catch (e) {
    console.error(e);
  }

  // Net Profit (Total Invoices - Total Bills for simplicity on dashboard)
  let totalIncome = 0;
  let totalExpense = 0;
  try {
    const [incRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_invoices WHERE tenant_id = ?",
      [tenantId]
    ) as any;
    if (incRows && incRows.length > 0) totalIncome = Number(incRows[0].total) || 0;
    
    const [expRows] = await db.query(
      "SELECT SUM(total_amount) as total FROM saas_bills WHERE tenant_id = ?",
      [tenantId]
    ) as any;
    if (expRows && expRows.length > 0) totalExpense = Number(expRows[0].total) || 0;
  } catch (e) {
    console.error(e);
  }
  
  const netProfit = totalIncome - totalExpense;

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
  };

  return (
    <>
      <div className="page-header">
        <h1 className="page-title">Dashboard Overview</h1>
        <div className="page-actions">
          <Link href={`/${params.companySlug}/invoices/new`} className="btn btn-primary">+ New Transaction</Link>
        </div>
      </div>

      <div className="grid-kpis">
        <div className="card">
          <div className="kpi-label">Total Receivables</div>
          <div className="kpi-value" style={{ color: 'var(--success)' }}>{formatCurrency(totalReceivables)}</div>
          <div style={{ fontSize: '0.85rem', color: 'var(--text-light)', marginTop: '0.5rem' }}>Awaiting payments</div>
        </div>
        <div className="card">
          <div className="kpi-label">Total Payables</div>
          <div className="kpi-value" style={{ color: 'var(--error)' }}>{formatCurrency(totalPayables)}</div>
          <div style={{ fontSize: '0.85rem', color: 'var(--text-light)', marginTop: '0.5rem' }}>Bills to pay</div>
        </div>
        <div className="card">
          <div className="kpi-label">Net Profit (YTD)</div>
          <div className="kpi-value" style={{ color: 'var(--primary-color)' }}>{formatCurrency(netProfit)}</div>
          <div style={{ fontSize: '0.85rem', color: 'var(--text-light)', marginTop: '0.5rem' }}>Estimated</div>
        </div>
      </div>

      <div className="card">
        <h3 style={{ marginBottom: '1.5rem', fontSize: '1.1rem', fontWeight: 600 }}>Cash Flow</h3>
        <div style={{ height: '300px', display: 'flex', alignItems: 'flex-end', gap: '1rem', borderBottom: '1px solid var(--border-color)', paddingBottom: '1rem' }}>
          {/* Mock Chart visually representing data */}
          <div style={{ flex: 1, backgroundColor: 'var(--success)', height: '40%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
          <div style={{ flex: 1, backgroundColor: 'var(--error)', height: '20%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
          <div style={{ flex: 1, backgroundColor: 'var(--success)', height: '70%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
          <div style={{ flex: 1, backgroundColor: 'var(--success)', height: '50%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
          <div style={{ flex: 1, backgroundColor: 'var(--error)', height: '30%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
          <div style={{ flex: 1, backgroundColor: 'var(--success)', height: '90%', opacity: 0.8, borderRadius: '4px 4px 0 0' }}></div>
        </div>
        <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '1rem', color: 'var(--text-secondary)', fontSize: '0.85rem' }}>
          <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
        </div>
      </div>
    </>
  );
}
