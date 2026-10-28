import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import { getBalanceSheetData } from "../../../../lib/accounting";
import BalanceSheetClient from "./BalanceSheetClient";

export default async function BalanceSheetPage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
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
    assets: [],
    totalAssets: 0,
    liabilities: [],
    totalLiabilities: 0,
    equity: [],
    currentPeriodProfit: 0,
    totalEquity: 0,
    totalLiabilitiesAndEquity: 0,
    isBalanced: true
  };

  try {
    initialData = await getBalanceSheetData(tenantId);
  } catch (error) {
    console.error("Failed to load balance sheet:", error);
  }

  return (
    <BalanceSheetClient 
      initialData={initialData} 
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
    />
  );
}
