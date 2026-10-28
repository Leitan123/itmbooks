import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import { getProfitAndLossData } from "../../../../lib/accounting";
import ProfitLossClient from "./ProfitLossClient";

export default async function ProfitLossPage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
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

  let initialData: any = {
    incomeAccounts: [],
    totalIncome: 0,
    expenseAccounts: [],
    totalExpenses: 0,
    netProfit: 0
  };

  try {
    initialData = await getProfitAndLossData(tenantId);
  } catch (error) {
    console.error("Failed to load P&L statement:", error);
  }

  return (
    <ProfitLossClient 
      initialData={initialData} 
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
    />
  );
}
