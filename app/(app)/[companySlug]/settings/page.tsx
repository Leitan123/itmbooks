import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import SettingsForm from "./SettingsForm";

export default async function SettingsPage() {
  const session = await getServerSession(authOptions);

  if (!session || !session.user) {
    redirect("/login");
  }

  const tenantId = (session.user as any).tenantId;

  const [rows] = await db.query(
    'SELECT name, address, tel, mobile, whatsapp, logo_url, theme_color FROM tenants WHERE id = ?',
    [tenantId]
  ) as any;

  const initialData = rows && rows.length > 0 ? rows[0] : {
    name: '', address: '', tel: '', mobile: '', whatsapp: '', logo_url: '', theme_color: ''
  };

  return (
    <>
      <div className="page-header">
        <h1 className="page-title">Company Settings</h1>
      </div>
      
      <SettingsForm initialData={initialData} basePath="/itmbooks.com" />
    </>
  );
}