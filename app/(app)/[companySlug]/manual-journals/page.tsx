import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import ManualJournalsClient from "./ManualJournalsClient";
import { ensureTenantAccounts } from "../../../../lib/accounting";

export default async function ManualJournalsPage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
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

  let journals = [];
  try {
    await ensureTenantAccounts(tenantId);
    const [rows] = await db.query(
      `SELECT JournalID as id, JournalDate as date, ReferenceNo as reference, Description as notes 
       FROM journal_entries 
       WHERE (tenant_id = ? OR (tenant_id IS NULL AND NOT EXISTS (SELECT 1 FROM journal_entries WHERE tenant_id = ?)))
       ORDER BY JournalDate DESC, JournalID DESC`,
      [tenantId, tenantId]
    ) as any;
    
    if (rows && rows.length > 0) {
      const journalIds = rows.map((j: any) => j.id);
      const [lines] = await db.query(
        `SELECT jl.JournalID as journal_id, jl.AccountID as account_id, a.AccountName as account_name, a.AccountCode as account_code, jl.Description as description, jl.DebitAmount as debit, jl.CreditAmount as credit 
         FROM journal_entry_details jl 
         JOIN chart_of_accounts a ON jl.AccountID = a.AccountID 
         WHERE jl.JournalID IN (?)
         ORDER BY jl.JournalDetailID ASC`,
        [journalIds]
      ) as any;
      
      journals = rows.map((journal: any) => {
        const jLines = lines.filter((l: any) => l.journal_id === journal.id);
        const total = jLines.reduce((sum: number, l: any) => sum + Number(l.debit || 0), 0);
        return { ...journal, total, lines: jLines };
      });
    }
  } catch (error) {
    console.error("Failed to load journals:", error);
  }

  return <ManualJournalsClient initialJournals={journals} companySlug={companySlug} />;
}