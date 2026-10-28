import { getServerSession } from "next-auth";
import { authOptions } from "../../../../api/auth/[...nextauth]/route";
import { db } from "../../../../../lib/db";
import { redirect } from "next/navigation";
import NewJournalClient from "./NewJournalClient";

export default async function NewJournalPage({ params }: { params: { companySlug: string } }) {
  const session = await getServerSession(authOptions);

  if (!session || !session.user) {
    redirect("/login");
  }

  let accounts = [];
  try {
    const [rows] = await db.query(
      `SELECT c.AccountID as id, c.AccountCode as code, c.AccountName as name, t.AccountTypeName as type 
       FROM chart_of_accounts c 
       JOIN account_types t ON c.AccountTypeID = t.AccountTypeID 
       ORDER BY c.AccountCode ASC`
    ) as any;
    accounts = rows || [];
  } catch (error) {
    console.error("Failed to load accounts:", error);
  }

  return (
    <NewJournalClient 
      accounts={accounts} 
      basePath="/itmbooks.com" 
      companySlug={params.companySlug} 
    />
  );
}
