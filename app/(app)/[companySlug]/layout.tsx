import type { Metadata } from 'next';
import Sidebar from '../../../components/layout/Sidebar';
import DashboardTopNav from '../../../components/layout/DashboardTopNav';
import '../../dashboard.css';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../api/auth/[...nextauth]/route';
import { db } from '../../../lib/db';

export const metadata: Metadata = {
  title: 'Dashboard | ITM Books',
};

export default async function DashboardLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ companySlug: string }> | { companySlug: string };
}) {
  const resolvedParams = await params;
  const companySlug = resolvedParams?.companySlug || 'dashboard';

  const session = await getServerSession(authOptions);
  let companyName = "Company Name";
  let themeColor = null;

  if (session && session.user) {
    const tenantId = (session.user as any).tenantId;
    try {
      const [rows] = await db.query('SELECT name, theme_color FROM tenants WHERE id = ?', [tenantId]) as any;
      if (rows && rows.length > 0) {
        companyName = rows[0].name;
        themeColor = rows[0].theme_color;
      }
    } catch (e) {
      console.error("Error fetching tenant details in layout:", e);
    }
  }

  const rawAccountType = (session?.user as any)?.accountType || 'Invoice';
  const accountType = (rawAccountType === 'Accounts' || rawAccountType === 'Accounting') ? 'Accounting' : rawAccountType;

  // Get initials for avatar
  const initials = companyName
    .split(' ')
    .map(word => word[0])
    .join('')
    .substring(0, 2)
    .toUpperCase();

  return (
    <div className="dashboard-body">
      {themeColor && (
        <style dangerouslySetInnerHTML={{__html: `
          :root {
            --primary-color: ${themeColor} !important;
          }
        `}} />
      )}
      <div className="dashboard-layout">
        
        <Sidebar />

        {/* Main Content Area */}
        <main className="dashboard-main">
          
          {/* Topbar with functioning search, popovers, and user dropdown */}
          <header className="dashboard-topbar">
            <DashboardTopNav
              companyName={companyName}
              initials={initials}
              userName={session?.user?.name || companyName}
              userEmail={session?.user?.email || ''}
              companySlug={companySlug}
              accountType={accountType}
            />
          </header>


          {/* Content Container */}
          <div className="dashboard-content">
            {children}
          </div>
          
        </main>
      </div>
    </div>
  );
}
