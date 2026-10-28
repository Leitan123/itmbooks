'use client';

import Link from 'next/link';
import { usePathname, useParams } from 'next/navigation';
import { useSession, signOut } from 'next-auth/react';

export default function Sidebar() {
  const pathname = usePathname();
  const params = useParams();
  const { data: session } = useSession();
  const companySlug = (params?.companySlug as string) || 'dashboard';
  const accountType = (session?.user as any)?.accountType || 'Invoice';

  const allLinks = [
    { name: 'Dashboard', href: `/${companySlug}`, group: 'Overview', allowed: ['Invoice', 'Accounts', 'Inventory'] },
    { name: 'Reports', href: `/${companySlug}/reports`, group: 'Overview', allowed: ['Invoice', 'Accounts', 'Inventory'] },
    
    { name: 'Customers', href: `/${companySlug}/customers`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounts'] },
    { name: 'Items & Services', href: `/${companySlug}/items`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounts'] },
    { name: 'Invoices', href: `/${companySlug}/invoices`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounts'] },
    { name: 'Payments Received', href: `/${companySlug}/payments-received`, group: 'Sales', allowed: ['Invoice', 'Inventory', 'Accounts'] },
    
    { name: 'Vendors', href: `/${companySlug}/vendors`, group: 'Purchases', allowed: ['Inventory', 'Accounts'] },
    { name: 'Expenses', href: `/${companySlug}/expenses`, group: 'Purchases', allowed: ['Invoice', 'Inventory', 'Accounts'] },
    { name: 'Bills', href: `/${companySlug}/bills`, group: 'Purchases', allowed: ['Inventory', 'Accounts'] },
    
    { name: 'Chart of Accounts', href: `/${companySlug}/chart-of-accounts`, group: 'Accounting', allowed: ['Accounts'] },
    { name: 'Manual Journals', href: `/${companySlug}/manual-journals`, group: 'Accounting', allowed: ['Accounts'] },
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
        <Link href={`/${companySlug}`} className="sidebar-logo">
          <span className="logo-dot" aria-hidden="true" />
          <span className="logo-itm">ITM</span> <span className="logo-books">BOOKS</span>
        </Link>
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
