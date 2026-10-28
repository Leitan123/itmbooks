import { getServerSession } from "next-auth";
import { authOptions } from "../../../../api/auth/[...nextauth]/route";
import { db } from "../../../../../lib/db";
import { redirect } from "next/navigation";
import NewJournalClient from "./NewJournalClient";
import { ensureTenantAccounts } from "../../../../../lib/accounting";

export default async function NewJournalPage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const companySlug = resolvedParams.companySlug || 'dashboard';
  const rawAccountType = session.user.accountType || 'Invoice';
  const isAccounting = rawAccountType === 'Accounting' || rawAccountType === 'Accounts';

  if (!isAccounting) {
    redirect(`/${companySlug}`);
  }

  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;

  let accounts = [];
  try {
    await ensureTenantAccounts(tenantId);
    const [rows] = await db.query(
      `SELECT c.AccountID as id, c.AccountCode as code, c.AccountName as name, t.AccountTypeName as type 
       FROM chart_of_accounts c 
       JOIN account_types t ON c.AccountTypeID = t.AccountTypeID 
       WHERE (c.tenant_id = ? OR (c.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))
       ORDER BY c.AccountCode ASC`,
      [tenantId, tenantId]
    ) as any;
    accounts = rows || [];
  } catch (error) {
    console.error("Failed to load accounts for new journal:", error);
  }

  return (
    <NewJournalClient 
      accounts={accounts} 
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
    />
  );
}
