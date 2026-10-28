import Link from 'next/link';
import '../dashboard.css';

export default function AdminLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="dashboard-body">
      <div className="dashboard-layout">
        
        {/* Sidebar */}
        <aside className="dashboard-sidebar" style={{ backgroundColor: '#0f172a' }}>
          <div className="sidebar-header" style={{ borderBottom: '1px solid #1e293b' }}>
            <div className="sidebar-logo" style={{ color: '#fff' }}>Parent Panel</div>
          </div>
          
          <nav className="sidebar-nav">
            <div className="nav-group">
              <div className="nav-group-title" style={{ color: '#94a3b8' }}>Admin Settings</div>
              <Link href="/admin" className="sidebar-link active" style={{ color: '#fff', backgroundColor: 'rgba(255,255,255,0.1)' }}>
                Company List
              </Link>
            </div>
          </nav>
          
          <div className="sidebar-footer" style={{ borderTop: '1px solid #1e293b' }}>
            <Link href="/login" className="sidebar-link" style={{ color: '#cbd5e1' }}>
              &larr; Exit Admin
            </Link>
          </div>
        </aside>

        {/* Main Content Area */}
        <main className="dashboard-main">
          
          <header className="dashboard-topbar">
            <div className="search-bar"></div>
            <div className="topbar-actions">
              <div className="org-selector">Super Admin ▾</div>
              <div className="user-avatar" style={{ backgroundColor: '#0f172a' }}>SA</div>
            </div>
          </header>

          <div className="dashboard-content">
            {children}
          </div>
          
        </main>
      </div>
    </div>
  );
}
