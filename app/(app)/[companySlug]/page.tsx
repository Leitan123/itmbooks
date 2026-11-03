import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { db } from "../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";
import { ensureTenantAccounts, getProfitAndLossData } from "../../../lib/accounting";
import "../table.css";
import "../billing-dashboard.css";
import BillingDashboardView from "../../../components/dashboard/BillingDashboardView";

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

  // Quotation Metrics & Recent List
  let totalQuotations = 0;
  let totalQuotationAmount = 0;
  let pendingQuotations = 0;
  let approvedQuotations = 0;
  let recentQuotations: any[] = [];
  try {
    const [qStats] = await db.query(
      `SELECT 
        COUNT(*) as count, 
        IFNULL(SUM(total_amount), 0) as total,
        IFNULL(SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END), 0) as pending,
        IFNULL(SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END), 0) as approved
       FROM saas_quotations 
       WHERE tenant_id = ?`,
      [tenantId]
    ) as any;
    if (qStats && qStats.length > 0) {
      totalQuotations = Number(qStats[0].count) || 0;
      totalQuotationAmount = Number(qStats[0].total) || 0;
      pendingQuotations = Number(qStats[0].pending) || 0;
      approvedQuotations = Number(qStats[0].approved) || 0;
    }

    const [qRecent] = await db.query(
      `SELECT q.*, c.name as customer_name 
       FROM saas_quotations q
       JOIN saas_customers c ON q.customer_id = c.id
       WHERE q.tenant_id = ?
       ORDER BY q.created_at DESC
       LIMIT 5`,
      [tenantId]
    ) as any;
    recentQuotations = qRecent || [];
  } catch (e) {
    console.error("Error loading quotation metrics:", e);
  }


  // Real 6-Month Revenue & Activity History
  const now = new Date();
  const monthMap: Record<string, { label: string; amount: number; count: number }> = {};
  const monthsList: { key: string; label: string }[] = [];
  
  for (let i = 5; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
    const label = d.toLocaleString('en-US', { month: 'short' });
    monthsList.push({ key, label });
    monthMap[key] = { label, amount: 0, count: 0 };
  }

  try {
    const [monthlyRows] = await db.query(
      `SELECT 
        DATE_FORMAT(date, '%Y-%m') as mKey, 
        IFNULL(SUM(total_amount), 0) as total, 
        COUNT(*) as count
       FROM saas_invoices 
       WHERE tenant_id = ?
       GROUP BY mKey`,
      [tenantId]
    ) as any;

    if (monthlyRows && monthlyRows.length > 0) {
      for (const row of monthlyRows) {
        if (monthMap[row.mKey]) {
          monthMap[row.mKey].amount = Number(row.total) || 0;
          monthMap[row.mKey].count = Number(row.count) || 0;
        }
      }
    }
  } catch (e) {
    console.error("Error loading monthly invoice history:", e);
  }

  const revenueHistory = monthsList.map(m => ({
    label: m.label,
    amount: monthMap[m.key].amount,
    count: monthMap[m.key].count
  }));

  // Real Invoice Status Breakdown
  const invoiceStatusStats = {
    total: 0,
    paidCount: 0,
    paidAmount: 0,
    pendingCount: 0,
    pendingAmount: 0,
    overdueCount: 0,
    overdueAmount: 0,
    draftCount: 0,
  };

  try {
    const [stRows] = await db.query(
      `SELECT 
        status, 
        COUNT(*) as count, 
        IFNULL(SUM(total_amount), 0) as total 
       FROM saas_invoices 
       WHERE tenant_id = ? 
       GROUP BY status`,
      [tenantId]
    ) as any;

    if (stRows && stRows.length > 0) {
      for (const r of stRows) {
        const count = Number(r.count) || 0;
        const total = Number(r.total) || 0;
        invoiceStatusStats.total += count;
        if (r.status === 'Paid') {
          invoiceStatusStats.paidCount += count;
          invoiceStatusStats.paidAmount += total;
        } else if (r.status === 'Overdue') {
          invoiceStatusStats.overdueCount += count;
          invoiceStatusStats.overdueAmount += total;
        } else if (r.status === 'Draft') {
          invoiceStatusStats.draftCount += count;
        } else {
          invoiceStatusStats.pendingCount += count;
          invoiceStatusStats.pendingAmount += total;
        }
      }
    }
  } catch (e) {
    console.error("Error loading invoice status breakdown:", e);
  }

  // Recent Invoices List
  let recentInvoices: any[] = [];
  try {
    const [iRecent] = await db.query(
      `SELECT i.*, c.name as customer_name 
       FROM saas_invoices i
       LEFT JOIN saas_customers c ON i.customer_id = c.id
       WHERE i.tenant_id = ?
       ORDER BY i.created_at DESC
       LIMIT 5`,
      [tenantId]
    ) as any;
    recentInvoices = iRecent || [];
  } catch (e) {
    console.error("Error loading recent invoices:", e);
  }

  return (
    <BillingDashboardView
      companySlug={companySlug}
      userName={session.user.name || 'User'}
      isAccounting={isAccounting}
      metrics={{
        totalInvoiced,
        totalCollected,
        totalReceivables,
        customerCount,
        totalQuotations,
        totalQuotationAmount,
        pendingQuotations,
        approvedQuotations,
        liquidFunds,
        totalRevenue,
        totalExpenses,
        netProfit,
        totalPayables,
      }}
      revenueHistory={revenueHistory}
      invoiceStatusStats={invoiceStatusStats}
      recentQuotations={recentQuotations}
      recentInvoices={recentInvoices}
    />
  );
}
