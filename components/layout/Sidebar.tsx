'use client';

import Link from 'next/link';
import { usePathname, useParams } from 'next/navigation';
import { useSession, signOut } from 'next-auth/react';

export default function Sidebar() {
  const pathname = usePathname();
  const params = useParams();
  const { data: session } = useSession();
  const companySlug = (params?.companySlug as string) || 'dashboard';
  const rawAccountType = (session?.user as any)?.accountType || 'Invoice';
  
  // Normalize account type: treat both 'Accounts' and 'Accounting' as Accounting
  const accountType = (rawAccountType === 'Accounts' || rawAccountType === 'Accounting') ? 'Accounting' : rawAccountType;

  const allLinks = [
    { name: 'Dashboard', href: `/${companySlug}`, group: 'Overview', allowed: ['Invoice', 'Accounting', 'Inventory'] },
    { name: 'Reports', href: `/${companySlug}/reports`, group: 'Overview', allowed: ['Invoice', 'Accounting', 'Inventory'] },
    
    // Accounting Core
    { name: 'Chart of Accounts', href: `/${companySlug}/chart-of-accounts`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'Manual Journals', href: `/${companySlug}/manual-journals`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'General Ledger', href: `/${companySlug}/general-ledger`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'Trial Balance', href: `/${companySlug}/trial-balance`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'Profit & Loss', href: `/${companySlug}/profit-loss`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'Balance Sheet', href: `/${companySlug}/balance-sheet`, group: 'Accounting', allowed: ['Accounting'] },
    { name: 'Bank & Cash', href: `/${companySlug}/banking`, group: 'Accounting', allowed: ['Accounting'] },

    // Sales & Revenue
    { name: 'Customers', href: `/${companySlug}/customers`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounting'] },
    { name: 'Items & Services', href: `/${companySlug}/items`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounting'] },
    { name: 'Invoices', href: `/${companySlug}/invoices`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounting'] },
    { name: 'Payments Received', href: `/${companySlug}/payments-received`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounting'] },
    
    // Purchases & Expenses (Not available for Invoice edition)
    { name: 'Vendors', href: `/${companySlug}/vendors`, group: 'Purchases', allowed: ['Inventory', 'Accounting'] },
    { name: 'Expenses', href: `/${companySlug}/expenses`, group: 'Purchases', allowed: ['Inventory', 'Accounting'] },
    { name: 'Bills', href: `/${companySlug}/bills`, group: 'Purchases', allowed: ['Inventory', 'Accounting'] },
  ];

  const links = allLinks.filter(link => link.allowed.includes(accountType));

  const groupedLinks = links.reduce((acc, link) => {
    if (!acc[link.group]) acc[link.group] = [];
    acc[link.group].push(link);
    return acc;
  }, {} as Record<string, typeof links>);

  return (
    <aside className="dashboard-sidebar">
      <div className="sidebar-header">
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          <Link href={`/${companySlug}`} className="sidebar-logo">
            <span className="logo-dot" aria-hidden="true" />
            <span className="logo-itm">ITM</span> <span className="logo-books">BOOKS</span>
          </Link>
          <div style={{ fontSize: '11px', color: 'var(--text-light)', textTransform: 'uppercase', letterSpacing: '0.05em', marginTop: '2px' }}>
            {accountType === 'Accounting' ? 'Full Accounting Edition' : accountType === 'Inventory' ? 'Inventory Edition' : 'Invoicing Edition'}
          </div>
        </div>
      </div>
      
      <nav className="sidebar-nav">
        {Object.entries(groupedLinks).map(([group, groupLinks]) => (
          <div className="nav-group" key={group}>
            <div className="nav-group-title">{group}</div>
            {groupLinks.map((link) => {
              const isActive = pathname === link.href;
              return (
                <Link 
                  key={link.name}
                  href={link.href} 
                  className={`sidebar-link ${isActive ? 'active' : ''}`}
                >
                  {link.name}
                </Link>
              );
            })}
          </div>
        ))}
      </nav>
      
      <div className="sidebar-footer">
        <Link 
          href={`/${companySlug}/subscription`} 
          className={`sidebar-link ${pathname === `/${companySlug}/subscription` ? 'active' : ''}`}
        >
          Subscription
        </Link>
        <Link 
          href={`/${companySlug}/settings`} 
          className={`sidebar-link ${pathname === `/${companySlug}/settings` ? 'active' : ''}`}
        >
          Settings
        </Link>
        <button 
          onClick={() => signOut({ callbackUrl: '/itmbooks.com' })}
          className="sidebar-link" 
          style={{ width: '100%', textAlign: 'left', background: 'none', border: 'none', color: '#ef4444', cursor: 'pointer', marginTop: '0.5rem' }}
        >
          Sign Out
        </button>
      </div>
    </aside>
  );
}
