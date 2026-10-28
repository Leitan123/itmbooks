import { getServerSession } from "next-auth";
import { authOptions } from "../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import Link from "next/link";
import "../(marketing)/form.css";

export default async function AdminDashboard() {
  const session = await getServerSession(authOptions);

  if (!session || (session.user as any).role !== "admin") {
    redirect("/");
  }

  let tenants = [];
  try {
    const { db } = await import("../../lib/db");
    const [rows] = await db.query(
      "SELECT id, name, slug, subscription_type, account_type, created_at FROM tenants ORDER BY created_at DESC"
    ) as any;
    tenants = rows || [];
  } catch (error) {
    console.error("Failed to load tenants for admin dashboard", error);
  }

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">Parent Control Panel</h1>
        <p style={{ color: '#64748b', marginTop: '4px' }}>
          Manage all clients and companies on the system.
        </p>
      </div>

      <div className="card">
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Company Name</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>URL Slug</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Subscription Type</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Account Type</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Joined Date</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', textAlign: 'right' }}>Dashboard</th>
            </tr>
          </thead>
          <tbody>
            {tenants.map((tenant: any) => (
              <tr key={tenant.id} style={{ borderBottom: '1px solid #e2e8f0' }}>
                <td style={{ padding: '12px 16px', fontWeight: '500' }}>{tenant.name}</td>
                <td style={{ padding: '12px 16px', color: '#64748b' }}>/{tenant.slug}</td>
                <td style={{ padding: '12px 16px' }}>
                  <span style={{ 
                    padding: '4px 8px', borderRadius: '4px', fontSize: '0.8rem', fontWeight: '600',
                    backgroundColor: tenant.subscription_type === 'paid' ? '#dcfce7' : '#f1f5f9',
                    color: tenant.subscription_type === 'paid' ? '#166534' : '#475569'
                  }}>
                    {tenant.subscription_type ? tenant.subscription_type.toUpperCase() : 'FREE'}
                  </span>
                </td>
                <td style={{ padding: '12px 16px' }}>
                  <span style={{ 
                    padding: '4px 8px', borderRadius: '4px', fontSize: '0.8rem', fontWeight: '600',
                    backgroundColor: tenant.account_type === 'Invoice' ? '#dbeafe' : 
                                     tenant.account_type === 'Accounts' ? '#f3e8ff' : '#fef9c3',
                    color: tenant.account_type === 'Invoice' ? '#1e40af' : 
                           tenant.account_type === 'Accounts' ? '#6b21a8' : '#854d0e'
                  }}>
                    {tenant.account_type || 'Invoice'}
                  </span>
                </td>
                <td style={{ padding: '12px 16px', color: '#64748b' }}>
                  {new Date(tenant.created_at).toLocaleDateString()}
                </td>
                <td style={{ padding: '12px 16px', textAlign: 'right' }}>
                  <Link href={`/${tenant.slug}`} className="btn btn-outline" style={{ padding: '4px 12px', fontSize: '0.85rem' }}>
                    View
                  </Link>
                </td>
              </tr>
            ))}
            {tenants.length === 0 && (
              <tr>
                <td colSpan={6} style={{ padding: '24px', textAlign: 'center', color: '#64748b' }}>
                  No companies registered yet.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
