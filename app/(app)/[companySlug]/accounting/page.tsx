import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";
import {
  ensureTenantAccounts,
  getProfitAndLossData,
  getBalanceSheetData,
  getTrialBalanceData,
} from "../../../../lib/accounting";
import { getTenantCurrency } from "../../../../lib/tenant-currency";
import { formatMoney } from "../../../../lib/currencies";
import "../../table.css";

export default async function AccountingDashboardPage({
  params,
}: {
  params: Promise<{ companySlug: string }> | { companySlug: string };
}) {
  const session = (await getServerSession(authOptions)) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const companySlug = resolvedParams.companySlug || "dashboard";
  const rawAccountType = session.user.accountType || "Invoice";
  const isAccounting = rawAccountType === "Accounting" || rawAccountType === "Accounts";

  if (!isAccounting) {
    redirect(`/${companySlug}`);
  }

  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;

  // Ensure Chart of Accounts is initialized for this tenant
  try {
    await ensureTenantAccounts(tenantId);
  } catch (e) {
    console.error("Account auto-seed error:", e);
  }

  // 1. Profit & Loss Data
  let totalIncome = 0;
  let totalExpenses = 0;
  let netProfit = 0;
  let incomeAccounts: any[] = [];
  let expenseAccounts: any[] = [];

  try {
    const pnl = await getProfitAndLossData(tenantId);
    totalIncome = pnl.totalIncome;
    totalExpenses = pnl.totalExpenses;
    netProfit = pnl.netProfit;
    incomeAccounts = pnl.incomeAccounts || [];
    expenseAccounts = pnl.expenseAccounts || [];
  } catch (e) {
    console.error("Error loading P&L for accounting dashboard:", e);
  }

  // 2. Balance Sheet Data
  let totalAssets = 0;
  let totalLiabilities = 0;
  let totalEquity = 0;
  let assetAccounts: any[] = [];
  let liabilityAccounts: any[] = [];

  try {
    const bs = await getBalanceSheetData(tenantId);
    totalAssets = bs.totalAssets;
    totalLiabilities = bs.totalLiabilities;
    totalEquity = bs.totalEquity;
    assetAccounts = bs.assets || [];
    liabilityAccounts = bs.liabilities || [];
  } catch (e) {
    console.error("Error loading Balance Sheet for accounting dashboard:", e);
  }

  // 3. Trial Balance & Equilibrium Check
  let tbBalanced = true;
  let tbDifference = 0;
  let totalDebits = 0;
  let totalCredits = 0;

  try {
    const tb = await getTrialBalanceData(tenantId);
    tbBalanced = tb.isBalanced;
    tbDifference = tb.difference;
    totalDebits = tb.totalDebit;
    totalCredits = tb.totalCredit;
  } catch (e) {
    console.error("Error loading Trial Balance for accounting dashboard:", e);
  }

  // 4. Liquid Bank & Cash Accounts
  let liquidFunds = 0;
  let cashAndBankAccounts: any[] = [];

  try {
    const [cRows] = (await db.query(
      `SELECT AccountID as id, AccountCode as code, AccountName as name, CurrentBalance as balance 
       FROM chart_of_accounts 
       WHERE AccountTypeID = 1 
         AND (AccountCode LIKE '10%' OR AccountName LIKE '%Cash%' OR AccountName LIKE '%Bank%')
         AND (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
       ORDER BY AccountCode ASC`,
      [tenantId, tenantId]
    )) as any;
    cashAndBankAccounts = cRows || [];
    liquidFunds = cashAndBankAccounts.reduce((sum, acc) => sum + (Number(acc.balance) || 0), 0);
  } catch (e) {
    console.error("Error loading cash and bank accounts:", e);
  }

  // 5. Invoicing Receivables & Payables
  let totalReceivables = 0;
  let totalPayables = 0;

  try {
    const [invRows] = (await db.query(
      "SELECT IFNULL(SUM(total_amount), 0) as total FROM saas_invoices WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    )) as any;
    if (invRows && invRows.length > 0) totalReceivables = Number(invRows[0].total) || 0;

    const [billRows] = (await db.query(
      "SELECT IFNULL(SUM(total_amount), 0) as total FROM saas_bills WHERE tenant_id = ? AND status != 'Paid'",
      [tenantId]
    )) as any;
    if (billRows && billRows.length > 0) totalPayables = Number(billRows[0].total) || 0;
  } catch (e) {
    console.error("Error loading receivables/payables:", e);
  }

  // 6. Recent Journal Entries Audit Trail
  let recentJournals: any[] = [];

  try {
    const [jRows] = (await db.query(
      `SELECT 
         je.JournalID, 
         je.JournalDate, 
         je.ReferenceNo, 
         je.Description,
         je.CreatedDate,
         COUNT(jed.JournalDetailID) as line_count,
         IFNULL(SUM(jed.DebitAmount), 0) as total_debit,
         IFNULL(SUM(jed.CreditAmount), 0) as total_credit
       FROM journal_entries je
       LEFT JOIN journal_entry_details jed ON je.JournalID = jed.JournalID
       WHERE (je.tenant_id = ? OR (je.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM journal_entries WHERE tenant_id = ?)))
       GROUP BY je.JournalID
       ORDER BY je.JournalDate DESC, je.JournalID DESC
       LIMIT 8`,
      [tenantId, tenantId]
    )) as any;
    recentJournals = jRows || [];
  } catch (e) {
    console.error("Error loading recent journals:", e);
  }

  // Working Capital Calculation: Current Assets (Liquid + Receivables) - Current Liabilities (Payables)
  const workingCapital = (liquidFunds + totalReceivables) - totalPayables;

  const currency = await getTenantCurrency(tenantId);
  const formatCurrency = (amount: number) => {
    return formatMoney(amount, currency);
  };

  return (
    <div className="page-container" style={{ maxWidth: "1380px", margin: "0 auto", padding: "1.75rem 2rem" }}>
      {/* Main Page Header with Quick Actions */}
      <div
        className="page-header"
        style={{
          display: "flex",
          justifyContent: "space-between",
          alignItems: "flex-start",
          marginBottom: "24px",
          flexWrap: "wrap",
          gap: "16px",
        }}
      >
        <div>
          <h1 className="page-title" style={{ fontSize: "1.85rem", fontWeight: 800, color: "#0f172a", margin: "0 0 6px 0" }}>
            Accounting & Financial Control
          </h1>
          <p className="page-subtitle" style={{ color: "#64748b", fontSize: "0.95rem", margin: 0 }}>
            Executive double-entry general ledger, balance sheet, profit & loss, and liquid cash management.
          </p>
        </div>

        <div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
          <Link
            href={`/${companySlug}/manual-journals/new`}
            className="btn btn-primary"
            style={{
              display: "inline-flex",
              alignItems: "center",
              gap: "6px",
              fontSize: "0.88rem",
              padding: "0.6rem 1.15rem",
              borderRadius: "8px",
              fontWeight: 600,
              backgroundColor: "#2563eb",
              color: "#ffffff",
              textDecoration: "none",
            }}
          >
            <span>✍️</span> + New Journal Entry
          </Link>

          <Link
            href={`/${companySlug}/expenses`}
            className="btn btn-secondary"
            style={{
              display: "inline-flex",
              alignItems: "center",
              gap: "6px",
              fontSize: "0.88rem",
              padding: "0.6rem 1.15rem",
              borderRadius: "8px",
              fontWeight: 600,
              backgroundColor: "#ffffff",
              border: "1px solid #cbd5e1",
              color: "#334155",
              textDecoration: "none",
            }}
          >
            <span>💸</span> + Record Expense
          </Link>

          <Link
            href={`/${companySlug}/banking`}
            className="btn btn-secondary"
            style={{
              display: "inline-flex",
              alignItems: "center",
              gap: "6px",
              fontSize: "0.88rem",
              padding: "0.6rem 1.15rem",
              borderRadius: "8px",
              fontWeight: 600,
              backgroundColor: "#ffffff",
              border: "1px solid #cbd5e1",
              color: "#334155",
              textDecoration: "none",
            }}
          >
            <span>🏦</span> Bank & Cash
          </Link>

          <Link
            href={`/${companySlug}/chart-of-accounts`}
            className="btn btn-secondary"
            style={{
              display: "inline-flex",
              alignItems: "center",
              gap: "6px",
              fontSize: "0.88rem",
              padding: "0.6rem 1.15rem",
              borderRadius: "8px",
              fontWeight: 600,
              backgroundColor: "#ffffff",
              border: "1px solid #cbd5e1",
              color: "#334155",
              textDecoration: "none",
            }}
          >
            <span>📑</span> Chart of Accounts
          </Link>
        </div>
      </div>

      {/* Double-Entry Equilibrium & Audit Banner */}
      <div
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          backgroundColor: tbBalanced ? "#f0fdf4" : "#fef2f2",
          border: `1px solid ${tbBalanced ? "#bbf7d0" : "#fecaca"}`,
          borderRadius: "10px",
          padding: "14px 20px",
          marginBottom: "24px",
          flexWrap: "wrap",
          gap: "12px",
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: "12px" }}>
          <div
            style={{
              width: "36px",
              height: "36px",
              borderRadius: "50%",
              backgroundColor: tbBalanced ? "#22c55e" : "#ef4444",
              color: "#ffffff",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              fontSize: "18px",
              fontWeight: "bold",
            }}
          >
            {tbBalanced ? "✓" : "!"}
          </div>
          <div>
            <div style={{ fontSize: "0.95rem", fontWeight: 700, color: tbBalanced ? "#15803d" : "#b91c1c" }}>
              {tbBalanced
                ? "Double-Entry Bookkeeping Equilibrium: 100% Balanced"
                : "Trial Balance Imbalance Detected!"}
            </div>
            <div style={{ fontSize: "0.82rem", color: "#64748b", marginTop: "2px" }}>
              Total Debits: <strong>{formatCurrency(totalDebits)}</strong> | Total Credits:{" "}
              <strong>{formatCurrency(totalCredits)}</strong>
              {tbDifference > 0 && ` (Variance: ${formatCurrency(tbDifference)})`}
            </div>
          </div>
        </div>

        <div style={{ display: "flex", gap: "10px" }}>
          <Link
            href={`/${companySlug}/trial-balance`}
            style={{
              fontSize: "0.85rem",
              fontWeight: 600,
              color: tbBalanced ? "#15803d" : "#b91c1c",
              textDecoration: "underline",
              display: "inline-flex",
              alignItems: "center",
              gap: "4px",
            }}
          >
            Inspect Trial Balance Audit Report →
          </Link>
        </div>
      </div>

      {/* Core Financial Health KPI Cards */}
      <div
        style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fit, minmax(210px, 1fr))",
          gap: "18px",
          marginBottom: "28px",
        }}
      >
        {/* Liquid Operating Capital */}
        <div className="card" style={{ padding: "20px", borderLeft: "4px solid #10b981", position: "relative" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#64748b", textTransform: "uppercase", letterSpacing: "0.5px" }}>
              Liquid Funds (Bank & Cash)
            </span>
            <span style={{ fontSize: "1.2rem" }}>🏦</span>
          </div>
          <div style={{ fontSize: "1.65rem", fontWeight: 800, color: "#0f172a", marginTop: "8px" }}>
            {formatCurrency(liquidFunds)}
          </div>
          <div style={{ fontSize: "0.8rem", color: "#10b981", marginTop: "4px", fontWeight: 500 }}>
            Active operating liquidity
          </div>
        </div>

        {/* Total Assets */}
        <div className="card" style={{ padding: "20px", borderLeft: "4px solid #3b82f6", position: "relative" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#64748b", textTransform: "uppercase", letterSpacing: "0.5px" }}>
              Total Assets
            </span>
            <span style={{ fontSize: "1.2rem" }}>📈</span>
          </div>
          <div style={{ fontSize: "1.65rem", fontWeight: 800, color: "#0f172a", marginTop: "8px" }}>
            {formatCurrency(totalAssets)}
          </div>
          <div style={{ fontSize: "0.8rem", color: "#64748b", marginTop: "4px" }}>
            Current & fixed enterprise assets
          </div>
        </div>

        {/* Total Liabilities */}
        <div className="card" style={{ padding: "20px", borderLeft: "4px solid #f59e0b", position: "relative" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#64748b", textTransform: "uppercase", letterSpacing: "0.5px" }}>
              Total Liabilities
            </span>
            <span style={{ fontSize: "1.2rem" }}>📋</span>
          </div>
          <div style={{ fontSize: "1.65rem", fontWeight: 800, color: "#0f172a", marginTop: "8px" }}>
            {formatCurrency(totalLiabilities)}
          </div>
          <div style={{ fontSize: "0.8rem", color: "#64748b", marginTop: "4px" }}>
            Payables & commercial obligations
          </div>
        </div>

        {/* Working Capital */}
        <div className="card" style={{ padding: "20px", borderLeft: `4px solid ${workingCapital >= 0 ? "#6366f1" : "#ef4444"}`, position: "relative" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#64748b", textTransform: "uppercase", letterSpacing: "0.5px" }}>
              Working Capital
            </span>
            <span style={{ fontSize: "1.2rem" }}>⚖️</span>
          </div>
          <div style={{ fontSize: "1.65rem", fontWeight: 800, color: workingCapital >= 0 ? "#4338ca" : "#dc2626", marginTop: "8px" }}>
            {formatCurrency(workingCapital)}
          </div>
          <div style={{ fontSize: "0.8rem", color: "#64748b", marginTop: "4px" }}>
            Current Liquidity minus Payables
          </div>
        </div>

        {/* Net Profit / (Loss) */}
        <div className="card" style={{ padding: "20px", borderLeft: `4px solid ${netProfit >= 0 ? "#16a34a" : "#dc2626"}`, position: "relative" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#64748b", textTransform: "uppercase", letterSpacing: "0.5px" }}>
              Net Profit / (Loss)
            </span>
            <span style={{ fontSize: "1.2rem" }}>💰</span>
          </div>
          <div style={{ fontSize: "1.65rem", fontWeight: 800, color: netProfit >= 0 ? "#16a34a" : "#dc2626", marginTop: "8px" }}>
            {formatCurrency(netProfit)}
          </div>
          <div style={{ fontSize: "0.8rem", color: "#64748b", marginTop: "4px" }}>
            Income: {formatCurrency(totalIncome)} | Exp: {formatCurrency(totalExpenses)}
          </div>
        </div>
      </div>

      {/* Accounting Command Center Launchpad Grid */}
      <div style={{ marginBottom: "32px" }}>
        <h2 style={{ fontSize: "1.15rem", fontWeight: 700, color: "#1e293b", marginBottom: "14px", display: "flex", alignItems: "center", gap: "8px" }}>
          <span>🚀</span> Accounting Command Center & Ledger Hub
        </h2>
        <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fill, minmax(280px, 1fr))", gap: "16px" }}>
          {/* Chart of Accounts */}
          <Link
            href={`/${companySlug}/chart-of-accounts`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>📑</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Chart of Accounts</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Master ledger structure encompassing Assets, Liabilities, Equity, Revenue, and Expense classes.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Open Chart of Accounts →
            </div>
          </Link>

          {/* Manual Journals */}
          <Link
            href={`/${companySlug}/manual-journals`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>✍️</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Manual Journals</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Draft, balance, and post double-entry journal vouchers with custom debit/credit lines.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              View & Post Journals →
            </div>
          </Link>

          {/* General Ledger */}
          <Link
            href={`/${companySlug}/general-ledger`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>📖</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>General Ledger</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Detailed chronological audit log of all debit and credit movements across individual accounts.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Browse General Ledger →
            </div>
          </Link>

          {/* Trial Balance */}
          <Link
            href={`/${companySlug}/trial-balance`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>⚖️</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Trial Balance</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Real-time debit vs credit balance summary verifying financial integrity before period closes.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Review Trial Balance →
            </div>
          </Link>

          {/* Profit & Loss */}
          <Link
            href={`/${companySlug}/profit-loss`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>📊</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Profit & Loss (P&L)</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Income statement providing breakdown of revenue streams, operating costs, and net margin.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Generate Income Statement →
            </div>
          </Link>

          {/* Balance Sheet */}
          <Link
            href={`/${companySlug}/balance-sheet`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>🏛️</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Balance Sheet</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Statement of financial position showing enterprise net worth: Assets = Liabilities + Equity.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              View Balance Sheet →
            </div>
          </Link>

          {/* Bank & Cash Management */}
          <Link
            href={`/${companySlug}/banking`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>🏦</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Bank & Cash Accounts</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Manage bank accounts, petty cash vaults, opening balances, and account reconciliations.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Manage Banking & Cash →
            </div>
          </Link>

          {/* Expenses Tracking */}
          <Link
            href={`/${companySlug}/expenses`}
            className="card"
            style={{
              padding: "18px",
              textDecoration: "none",
              color: "inherit",
              transition: "transform 0.15s ease, box-shadow 0.15s ease",
              display: "flex",
              flexDirection: "column",
              justifyContent: "space-between",
            }}
          >
            <div>
              <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "8px" }}>
                <span style={{ fontSize: "1.5rem" }}>🧾</span>
                <span style={{ fontSize: "1rem", fontWeight: 700, color: "#0f172a" }}>Operating Expenses</span>
              </div>
              <p style={{ fontSize: "0.84rem", color: "#64748b", margin: 0, lineHeight: 1.4 }}>
                Record day-to-day expenditures, track category distribution, and attach vendor receipts.
              </p>
            </div>
            <div style={{ marginTop: "14px", fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", display: "flex", alignItems: "center", gap: "4px" }}>
              Track Operating Expenses →
            </div>
          </Link>
        </div>
      </div>

      {/* Two Column Layout: Cash & Bank Accounts + Recent Journal Entries */}
      <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit, minmax(420px, 1fr))", gap: "24px", marginBottom: "32px" }}>
        {/* Left Column: Liquid Bank & Cash Accounts Ledger */}
        <div className="card" style={{ padding: "20px" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "16px" }}>
            <div>
              <h3 style={{ fontSize: "1.05rem", fontWeight: 700, color: "#0f172a", margin: 0 }}>
                Bank & Cash Accounts Ledger
              </h3>
              <p style={{ fontSize: "0.82rem", color: "#64748b", margin: "2px 0 0 0" }}>
                Real-time balances across liquid commercial and petty cash accounts
              </p>
            </div>
            <Link
              href={`/${companySlug}/banking`}
              style={{ fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", textDecoration: "none" }}
            >
              + Add / Reconcile
            </Link>
          </div>

          <div className="table-responsive">
            <table className="table" style={{ margin: 0 }}>
              <thead>
                <tr>
                  <th style={{ padding: "8px 12px" }}>Account Code</th>
                  <th style={{ padding: "8px 12px" }}>Account Name</th>
                  <th style={{ padding: "8px 12px", textAlign: "right" }}>Current Balance</th>
                </tr>
              </thead>
              <tbody>
                {cashAndBankAccounts.length > 0 ? (
                  cashAndBankAccounts.map((acc) => (
                    <tr key={acc.id}>
                      <td style={{ padding: "10px 12px", fontFamily: "monospace", fontWeight: 600, color: "#475569" }}>
                        {acc.code}
                      </td>
                      <td style={{ padding: "10px 12px", fontWeight: 500, color: "#1e293b" }}>
                        {acc.name}
                      </td>
                      <td
                        style={{
                          padding: "10px 12px",
                          textAlign: "right",
                          fontWeight: 700,
                          color: Number(acc.balance) >= 0 ? "#16a34a" : "#dc2626",
                        }}
                      >
                        {formatCurrency(Number(acc.balance) || 0)}
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={3} style={{ textAlign: "center", color: "#94a3b8", padding: "20px" }}>
                      No liquid accounts configured.
                    </td>
                  </tr>
                )}
              </tbody>
              <tfoot>
                <tr style={{ backgroundColor: "#f8fafc", fontWeight: 700 }}>
                  <td colSpan={2} style={{ padding: "10px 12px", color: "#334155" }}>
                    Total Liquid Cash & Bank:
                  </td>
                  <td style={{ padding: "10px 12px", textAlign: "right", color: "#0f172a" }}>
                    {formatCurrency(liquidFunds)}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        {/* Right Column: Recent Journal Entries Audit Trail */}
        <div className="card" style={{ padding: "20px" }}>
          <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "16px" }}>
            <div>
              <h3 style={{ fontSize: "1.05rem", fontWeight: 700, color: "#0f172a", margin: 0 }}>
                Recent Journal Entries
              </h3>
              <p style={{ fontSize: "0.82rem", color: "#64748b", margin: "2px 0 0 0" }}>
                Latest double-entry adjustments and transactional postings
              </p>
            </div>
            <Link
              href={`/${companySlug}/manual-journals`}
              style={{ fontSize: "0.82rem", fontWeight: 600, color: "#2563eb", textDecoration: "none" }}
            >
              View All Journals →
            </Link>
          </div>

          <div className="table-responsive">
            <table className="table" style={{ margin: 0 }}>
              <thead>
                <tr>
                  <th style={{ padding: "8px 12px" }}>Ref No</th>
                  <th style={{ padding: "8px 12px" }}>Date</th>
                  <th style={{ padding: "8px 12px" }}>Description</th>
                  <th style={{ padding: "8px 12px", textAlign: "right" }}>Total Debit</th>
                  <th style={{ padding: "8px 12px", textAlign: "center" }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {recentJournals.length > 0 ? (
                  recentJournals.map((j) => (
                    <tr key={j.JournalID}>
                      <td style={{ padding: "10px 12px", fontWeight: 600, fontFamily: "monospace", color: "#2563eb" }}>
                        <Link href={`/${companySlug}/general-ledger?ref=${j.ReferenceNo}`} style={{ color: "inherit", textDecoration: "none" }}>
                          {j.ReferenceNo}
                        </Link>
                      </td>
                      <td style={{ padding: "10px 12px", fontSize: "0.82rem", color: "#64748b" }}>
                        {new Date(j.JournalDate).toLocaleDateString()}
                      </td>
                      <td style={{ padding: "10px 12px", maxWidth: "160px", overflow: "hidden", textOverflow: "ellipsis", whiteSpace: "nowrap" }}>
                        {j.Description || "General adjustment"}
                      </td>
                      <td style={{ padding: "10px 12px", textAlign: "right", fontWeight: 600, color: "#0f172a" }}>
                        {formatCurrency(Number(j.total_debit) || 0)}
                      </td>
                      <td style={{ padding: "10px 12px", textAlign: "center" }}>
                        <span
                          style={{
                            fontSize: "11px",
                            fontWeight: 600,
                            padding: "2px 8px",
                            borderRadius: "999px",
                            backgroundColor: "#dcfce7",
                            color: "#166534",
                          }}
                        >
                          Posted
                        </span>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={5} style={{ textAlign: "center", color: "#94a3b8", padding: "24px" }}>
                      No manual journal vouchers posted yet.
                      <div style={{ marginTop: "8px" }}>
                        <Link
                          href={`/${companySlug}/manual-journals/new`}
                          className="btn btn-primary"
                          style={{ fontSize: "0.8rem", padding: "0.4rem 0.8rem", textDecoration: "none" }}
                        >
                          + Post First Journal
                        </Link>
                      </div>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Financial Structure Summary: Assets, Liabilities, and Equity Breakdown */}
      <div className="card" style={{ padding: "22px" }}>
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "16px", flexWrap: "wrap", gap: "10px" }}>
          <div>
            <h3 style={{ fontSize: "1.05rem", fontWeight: 700, color: "#0f172a", margin: 0 }}>
              Enterprise Financial Structure Overview
            </h3>
            <p style={{ fontSize: "0.82rem", color: "#64748b", margin: "2px 0 0 0" }}>
              High-level statement of balances reflecting financial stability and equity distribution
            </p>
          </div>
          <div style={{ display: "flex", gap: "12px" }}>
            <Link
              href={`/${companySlug}/balance-sheet`}
              style={{ fontSize: "0.85rem", fontWeight: 600, color: "#2563eb", textDecoration: "none" }}
            >
              Full Balance Sheet Statement →
            </Link>
            <span style={{ color: "#cbd5e1" }}>|</span>
            <Link
              href={`/${companySlug}/profit-loss`}
              style={{ fontSize: "0.85rem", fontWeight: 600, color: "#2563eb", textDecoration: "none" }}
            >
              Full P&L Statement →
            </Link>
          </div>
        </div>

        <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))", gap: "18px" }}>
          <div style={{ backgroundColor: "#f8fafc", padding: "16px", borderRadius: "8px", border: "1px solid #e2e8f0" }}>
            <div style={{ fontSize: "0.8rem", fontWeight: 600, color: "#64748b", textTransform: "uppercase" }}>
              1. Assets Portfolio
            </div>
            <div style={{ fontSize: "1.35rem", fontWeight: 700, color: "#0f172a", margin: "6px 0" }}>
              {formatCurrency(totalAssets)}
            </div>
            <div style={{ fontSize: "0.78rem", color: "#64748b" }}>
              Includes liquid cash ({formatCurrency(liquidFunds)}), receivables, and capital assets.
            </div>
          </div>

          <div style={{ backgroundColor: "#f8fafc", padding: "16px", borderRadius: "8px", border: "1px solid #e2e8f0" }}>
            <div style={{ fontSize: "0.8rem", fontWeight: 600, color: "#64748b", textTransform: "uppercase" }}>
              2. Total Liabilities
            </div>
            <div style={{ fontSize: "1.35rem", fontWeight: 700, color: "#0f172a", margin: "6px 0" }}>
              {formatCurrency(totalLiabilities)}
            </div>
            <div style={{ fontSize: "0.78rem", color: "#64748b" }}>
              Accounts payable ({formatCurrency(totalPayables)}), operating expenses payable, and tax provisions.
            </div>
          </div>

          <div style={{ backgroundColor: "#f8fafc", padding: "16px", borderRadius: "8px", border: "1px solid #e2e8f0" }}>
            <div style={{ fontSize: "0.8rem", fontWeight: 600, color: "#64748b", textTransform: "uppercase" }}>
              3. Shareholder / Owner Equity
            </div>
            <div style={{ fontSize: "1.35rem", fontWeight: 700, color: "#0f172a", margin: "6px 0" }}>
              {formatCurrency(totalEquity)}
            </div>
            <div style={{ fontSize: "0.78rem", color: "#64748b" }}>
              Retained earnings, owner capital investments, and cumulative profits.
            </div>
          </div>

          <div style={{ backgroundColor: "#f8fafc", padding: "16px", borderRadius: "8px", border: "1px solid #e2e8f0" }}>
            <div style={{ fontSize: "0.8rem", fontWeight: 600, color: "#64748b", textTransform: "uppercase" }}>
              4. Current Net Profit
            </div>
            <div
              style={{
                fontSize: "1.35rem",
                fontWeight: 700,
                color: netProfit >= 0 ? "#16a34a" : "#dc2626",
                margin: "6px 0",
              }}
            >
              {formatCurrency(netProfit)}
            </div>
            <div style={{ fontSize: "0.78rem", color: "#64748b" }}>
              Revenue ({formatCurrency(totalIncome)}) minus expenditures ({formatCurrency(totalExpenses)}).
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
