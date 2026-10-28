import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import { getGeneralLedgerData, ensureTenantAccounts } from "../../../../lib/accounting";
import GeneralLedgerClient from "./GeneralLedgerClient";

export default async function GeneralLedgerPage({ 
  params,
  searchParams
}: { 
  params: Promise<{ companySlug: string }> | { companySlug: string },
  searchParams: Promise<{ accountId?: string }> | { accountId?: string }
}) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const resolvedSearchParams = await searchParams;
  const companySlug = resolvedParams.companySlug || 'dashboard';
  const rawAccountType = session.user.accountType || 'Invoice';
  const isAccounting = rawAccountType === 'Accounting' || rawAccountType === 'Accounts';

  if (!isAccounting) {
    redirect(`/${companySlug}`);
  }

  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
  const initialAccountId = resolvedSearchParams.accountId ? parseInt(resolvedSearchParams.accountId) : undefined;

  let initialLedger: any[] = [];
  let accounts: any[] = [];

  try {
    await ensureTenantAccounts(tenantId);
    
    // Fetch account list for dropdown
    const [accRows] = await db.query(
      `SELECT AccountID as id, AccountCode as code, AccountName as name 
       FROM chart_of_accounts 
       WHERE (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
       ORDER BY AccountCode ASC`,
      [tenantId, tenantId]
    ) as any;
    accounts = accRows || [];

    // Fetch initial ledger (single account if specified, else all accounts)
    initialLedger = await getGeneralLedgerData(tenantId, initialAccountId);
  } catch (error) {
    console.error("Failed to load general ledger:", error);
  }

  return (
    <GeneralLedgerClient 
      initialLedger={initialLedger} 
      accounts={accounts}
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
      initialAccountId={initialAccountId}
    />
  );
}
