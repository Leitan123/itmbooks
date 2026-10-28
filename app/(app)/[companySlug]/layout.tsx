import type { Metadata } from 'next';
import Sidebar from '../../../components/layout/Sidebar';
import '../../dashboard.css';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../api/auth/[...nextauth]/route';
import { db } from '../../../lib/db';

export const metadata: Metadata = {
  title: 'Dashboard | ITM Books',
};

export default async function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
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
          
          {/* Topbar */}
          <header className="dashboard-topbar">
            <div className="search-bar">
              <input type="text" placeholder="Search in ITM Books..." className="search-input" />
            </div>
            
            <div className="topbar-actions">
              <div className="org-selector">
                {companyName} ▾
              </div>
              <div className="user-avatar">
                {initials}
              </div>
            </div>
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
