import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import { ensureTenantAccounts } from "../../../../lib/accounting";
import BankingClient from "./BankingClient";

export default async function BankingPage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const companySlug = resolvedParams.companySlug || 'dashboard';
  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;

  let banks: any[] = [];
  let cashAccounts: any[] = [];

  try {
    await ensureTenantAccounts(tenantId);

    const [bRows] = await db.query(
      `SELECT b.BankID as id, b.BankName as name, b.AccountName as accountName, b.AccountNumber as accountNumber, b.Branch as branch, b.OpeningBalance as openingBalance, IFNULL(coa.CurrentBalance, b.OpeningBalance) as currentBalance, b.AccountID as accountId, coa.AccountCode as accountCode
       FROM banks b
       LEFT JOIN chart_of_accounts coa ON b.AccountID = coa.AccountID
       WHERE (b.tenant_id = ? OR (b.tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM banks WHERE tenant_id = ?)))
         AND (b.IsDeleted = 0 OR b.IsDeleted IS NULL)
       ORDER BY b.BankName ASC`,
      [tenantId, tenantId]
    ) as any;
    banks = bRows || [];

    const [cRows] = await db.query(
      `SELECT AccountID as id, AccountCode as code, AccountName as name, CurrentBalance as balance 
       FROM chart_of_accounts 
       WHERE AccountTypeID = 1 
         AND (AccountName LIKE '%Cash%' OR AccountCode IN ('1001', '1002'))
         AND (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM chart_of_accounts WHERE tenant_id = ?)))`,
      [tenantId, tenantId]
    ) as any;
    cashAccounts = cRows || [];
  } catch (error) {
    console.error("Failed to load banking data:", error);
  }

  return (
    <BankingClient 
      initialBanks={banks} 
      initialCash={cashAccounts} 
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
    />
  );
}
