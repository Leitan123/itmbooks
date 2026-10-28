import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import AccountsClient from "./AccountsClient";

export default async function ChartOfAccountsPage() {
  const session = await getServerSession(authOptions);

  if (!session || !session.user) {
    redirect("/login");
  }

  let accounts = [];
  try {
    const [rows] = await db.query(
      `SELECT c.AccountID as id, c.AccountCode as code, c.AccountName as name, t.AccountTypeName as type, t.AccountTypeID as typeId, c.CurrentBalance as balance, c.OpeningBalance as openingBalance
       FROM chart_of_accounts c 
       JOIN account_types t ON c.AccountTypeID = t.AccountTypeID 
       ORDER BY c.AccountCode ASC`
    ) as any;
    accounts = rows || [];
  } catch (error) {
    console.error("Failed to load accounts:", error);
  }

  return <AccountsClient initialAccounts={accounts} basePath="/itmbooks.com" />;
}