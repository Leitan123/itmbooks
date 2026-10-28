import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import { getTrialBalanceData } from "../../../../lib/accounting";
import TrialBalanceClient from "./TrialBalanceClient";

export default async function TrialBalancePage({ params }: { params: Promise<{ companySlug: string }> | { companySlug: string } }) {
  const session = await getServerSession(authOptions) as any;

  if (!session || !session.user) {
    redirect("/login");
  }

  const resolvedParams = await params;
  const companySlug = resolvedParams.companySlug || 'dashboard';
  const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;

  let initialData: any = {
    records: [],
    totalDebit: 0,
    totalCredit: 0,
    difference: 0,
    isBalanced: true
  };

  try {
    initialData = await getTrialBalanceData(tenantId);
  } catch (error) {
    console.error("Failed to load trial balance:", error);
  }

  return (
    <TrialBalanceClient 
      initialData={initialData} 
      basePath="/itmbooks.com" 
      companySlug={companySlug} 
    />
  );
}
