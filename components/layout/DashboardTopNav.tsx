'use client';

import React, { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import { signOut } from 'next-auth/react';
import './DashboardTopNav.css';

export interface DashboardTopNavProps {
  companyName: string;
  initials: string;
  userName: string;
  userEmail: string;
  companySlug: string;
  accountType: string;
}

export default function DashboardTopNav({
  companyName,
  initials,
  userName,
  userEmail,
  companySlug,
  accountType,
}: DashboardTopNavProps) {
  // Dropdown states
  const [messagesOpen, setMessagesOpen] = useState(false);
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const [helpOpen, setHelpOpen] = useState(false);
  const [userMenuOpen, setUserMenuOpen] = useState(false);

  // Search state
  const [searchQuery, setSearchQuery] = useState('');
  const [searchFocused, setSearchFocused] = useState(false);
  const searchInputRef = useRef<HTMLInputElement>(null);

  // Notifications state
  const [unreadCount, setUnreadCount] = useState(2);

  // Refs for outside click handling
  const containerRef = useRef<HTMLDivElement>(null);

  // Close all menus
  const closeAll = () => {
    setMessagesOpen(false);
    setNotificationsOpen(false);
    setHelpOpen(false);
    setUserMenuOpen(false);
    setSearchFocused(false);
  };

  // Keyboard shortcut: Cmd+F or Ctrl+F to focus search, Esc to close menus
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if ((e.metaKey || e.ctrlKey) && (e.key === 'f' || e.key === 'k')) {
        e.preventDefault();
        searchInputRef.current?.focus();
        setSearchFocused(true);
      } else if (e.key === 'Escape') {
        closeAll();
      }
    };

    const handleOutsideClick = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        closeAll();
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    document.addEventListener('mousedown', handleOutsideClick);

    return () => {
      window.removeEventListener('keydown', handleKeyDown);
      document.removeEventListener('mousedown', handleOutsideClick);
    };
  }, []);

  // Quick navigation items
  const isAccounting = accountType === 'Accounting';
  const allNavItems = [
    { title: 'Invoices', href: `/${companySlug}/invoices`, icon: '🧾', desc: 'Billing & sent sales' },
    { title: 'Quotations', href: `/${companySlug}/quotations`, icon: '📄', desc: 'Price estimates & proposals' },
    { title: 'Customers', href: `/${companySlug}/customers`, icon: '👥', desc: 'Client profiles & directory' },
    { title: 'Payments Received', href: `/${companySlug}/payments-received`, icon: '💳', desc: 'Receipts & collections' },
    { title: 'Items & Services', href: `/${companySlug}/items`, icon: '📦', desc: 'Products, services & inventory' },
    { title: 'Reports', href: `/${companySlug}/reports`, icon: '📊', desc: 'Financial summaries & exports' },
    { title: 'Settings', href: `/${companySlug}/settings`, icon: '⚙️', desc: 'Company details & preferences' },
    { title: 'Subscription', href: `/${companySlug}/subscription`, icon: '💳', desc: 'Plan & billing tier' },
    ...(isAccounting ? [
      { title: 'Accounting Dashboard', href: `/${companySlug}/accounting`, icon: '📈', desc: 'Core accounting metrics' },
      { title: 'Chart of Accounts', href: `/${companySlug}/chart-of-accounts`, icon: '📑', desc: 'Ledger accounts hierarchy' },
      { title: 'Manual Journals', href: `/${companySlug}/manual-journals`, icon: '✍️', desc: 'Debit / credit journal entries' },
      { title: 'General Ledger', href: `/${companySlug}/general-ledger`, icon: '📖', desc: 'Audit trail of transactions' },
      { title: 'Trial Balance', href: `/${companySlug}/trial-balance`, icon: '⚖️', desc: 'Accounts equilibrium balance' },
      { title: 'Profit & Loss', href: `/${companySlug}/profit-loss`, icon: '📊', desc: 'Income statement & margins' },
      { title: 'Balance Sheet', href: `/${companySlug}/balance-sheet`, icon: '🏛️', desc: 'Assets, liabilities & equity' },
      { title: 'Bank & Cash', href: `/${companySlug}/banking`, icon: '🏦', desc: 'Commercial banks & cash drawers' },
      { title: 'Expenses', href: `/${companySlug}/expenses`, icon: '💳', desc: 'Operational disbursements' },
      { title: 'Bills', href: `/${companySlug}/bills`, icon: '🧾', desc: 'Vendor payables & bills' },
      { title: 'Vendors', href: `/${companySlug}/vendors`, icon: '🏢', desc: 'Suppliers & trade contacts' },
    ] : [])
  ];

  const filteredNavItems = searchQuery.trim() === ''
    ? allNavItems.slice(0, 6)
    : allNavItems.filter(item => 
        item.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
        item.desc.toLowerCase().includes(searchQuery.toLowerCase())
      );

  return (
    <div className="dashboard-topbar-inner" ref={containerRef}>
      
      {/* 1. Search Bar */}
      <div className="dashboard-search-container">
        <div className="dashboard-search-input-wrap">
          <span className="dashboard-search-icon">🔍</span>
          <input 
            ref={searchInputRef}
            type="text" 
            placeholder="Search in ITM Books..." 
            className="dashboard-search-input"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            onFocus={() => {
              setSearchFocused(true);
              setMessagesOpen(false);
              setNotificationsOpen(false);
              setHelpOpen(false);
              setUserMenuOpen(false);
            }}
          />
          <span className="dashboard-search-shortcut">⌘ F</span>
        </div>

        {/* Live Search Quick Results */}
        {searchFocused && (
          <div className="dashboard-search-dropdown">
            <div className="dashboard-search-group-title">
              {searchQuery.trim() === '' ? 'Quick Jump Navigation' : `Results for "${searchQuery}"`}
            </div>

            {filteredNavItems.length === 0 ? (
              <div style={{ padding: '0.75rem', fontSize: '0.82rem', color: '#64748b', textAlign: 'center' }}>
                No matching pages found
              </div>
            ) : (
              filteredNavItems.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className="dashboard-search-item"
                  onClick={() => {
                    setSearchFocused(false);
                    setSearchQuery('');
                  }}
                >
                  <span>{item.icon}</span>
                  <span>{item.title}</span>
                  <span className="dashboard-search-item-desc">{item.desc}</span>
                </Link>
              ))
            )}
          </div>
        )}
      </div>

      {/* 2. Topbar Actions & Popovers */}
      <div className="dashboard-actions-cluster">
        
        {/* Quick Icons: Messages, Notifications, Help */}
        <div className="dashboard-icons-group">
          
          {/* Messages Button (💬) */}
          <div className="dashboard-icon-btn-wrap">
            <button 
              type="button" 
              className={`dashboard-icon-btn ${messagesOpen ? 'active' : ''}`}
              title="Messages & Support"
              onClick={() => {
                setMessagesOpen(!messagesOpen);
                setNotificationsOpen(false);
                setHelpOpen(false);
                setUserMenuOpen(false);
                setSearchFocused(false);
              }}
            >
              💬
            </button>

            {messagesOpen && (
              <div className="dashboard-popover-panel">
                <div className="dashboard-popover-header">
                  <h4 className="dashboard-popover-title">Messages & Support</h4>
                  <span style={{ fontSize: '0.72rem', color: '#94a3b8' }}>ITM Books Team</span>
                </div>

                <div className="dashboard-popover-body">
                  <div className="dashboard-popover-item">
                    <span className="dashboard-popover-item-icon">👋</span>
                    <div>
                      <div className="dashboard-popover-item-title">Welcome to ITM Books!</div>
                      <div className="dashboard-popover-item-text">
                        Your workspace <strong>{companyName}</strong> is fully active. Let us know if you need assistance configuring settings or invoices.
                      </div>
                    </div>
                  </div>

                  <div className="dashboard-popover-item">
                    <span className="dashboard-popover-item-icon">✉️</span>
                    <div>
                      <div className="dashboard-popover-item-title">Dedicated Support Channel</div>
                      <div className="dashboard-popover-item-text">
                        Reach our support specialists at <strong>info@itmartx.com</strong> or WhatsApp our billing team.
                      </div>
                    </div>
                  </div>
                </div>

                <div className="dashboard-popover-footer">
                  <Link 
                    href="/itmbooks.com/contact" 
                    className="dashboard-popover-footer-link"
                    onClick={() => setMessagesOpen(false)}
                  >
                    Open Support Desk →
                  </Link>
                </div>
              </div>
            )}
          </div>

          {/* Notifications Button (🔔) */}
          <div className="dashboard-icon-btn-wrap">
            <button 
              type="button" 
              className={`dashboard-icon-btn ${notificationsOpen ? 'active' : ''}`}
              title="Notifications"
              onClick={() => {
                setNotificationsOpen(!notificationsOpen);
                setMessagesOpen(false);
                setHelpOpen(false);
                setUserMenuOpen(false);
                setSearchFocused(false);
              }}
            >
              🔔
              {unreadCount > 0 && <span className="dashboard-unread-dot" />}
            </button>

            {notificationsOpen && (
              <div className="dashboard-popover-panel">
                <div className="dashboard-popover-header">
                  <h4 className="dashboard-popover-title">Notifications</h4>
                  {unreadCount > 0 && (
                    <button 
                      type="button" 
                      className="dashboard-popover-action"
                      onClick={() => setUnreadCount(0)}
                    >
                      Mark all read
                    </button>
                  )}
                </div>

                <div className="dashboard-popover-body">
                  <Link 
                    href={`/${companySlug}/invoices`} 
                    className="dashboard-popover-item"
                    onClick={() => setNotificationsOpen(false)}
                  >
                    <span className="dashboard-popover-item-icon">🧾</span>
                    <div>
                      <div className="dashboard-popover-item-title">Customer Invoices Paid</div>
                      <div className="dashboard-popover-item-text">
                        Customer payments of $1,200.00 reconciled in full.
                      </div>
                    </div>
                  </Link>

                  <Link 
                    href={`/${companySlug}/quotations`} 
                    className="dashboard-popover-item"
                    onClick={() => setNotificationsOpen(false)}
                  >
                    <span className="dashboard-popover-item-icon">📄</span>
                    <div>
                      <div className="dashboard-popover-item-title">Quotation QT-1001 Active</div>
                      <div className="dashboard-popover-item-text">
                        Pending commercial price estimate for $800.00 is awaiting customer reply.
                      </div>
                    </div>
                  </Link>

                  <div className="dashboard-popover-item">
                    <span className="dashboard-popover-item-icon">🔐</span>
                    <div>
                      <div className="dashboard-popover-item-title">Security & Session Active</div>
                      <div className="dashboard-popover-item-text">
                        Signed in securely to workspace <strong>{companySlug}</strong>.
                      </div>
                    </div>
                  </div>
                </div>

                <div className="dashboard-popover-footer">
                  <Link 
                    href={`/${companySlug}/reports`} 
                    className="dashboard-popover-footer-link"
                    onClick={() => setNotificationsOpen(false)}
                  >
                    View All Activity Reports →
                  </Link>
                </div>
              </div>
            )}
          </div>

          {/* Help Button (❓) */}
          <div className="dashboard-icon-btn-wrap">
            <button 
              type="button" 
              className={`dashboard-icon-btn ${helpOpen ? 'active' : ''}`}
              title="Help & Guides"
              onClick={() => {
                setHelpOpen(!helpOpen);
                setMessagesOpen(false);
                setNotificationsOpen(false);
                setUserMenuOpen(false);
                setSearchFocused(false);
              }}
            >
              ❓
            </button>

            {helpOpen && (
              <div className="dashboard-popover-panel">
                <div className="dashboard-popover-header">
                  <h4 className="dashboard-popover-title">Help & Documentation</h4>
                </div>

                <div className="dashboard-popover-body">
                  <Link 
                    href="/itmbooks.com/documentation" 
                    className="dashboard-popover-item"
                    onClick={() => setHelpOpen(false)}
                  >
                    <span className="dashboard-popover-item-icon">📖</span>
                    <div>
                      <div className="dashboard-popover-item-title">Documentation & Manual</div>
                      <div className="dashboard-popover-item-text">
                        Step-by-step guides for invoicing, estimates, and tax management.
                      </div>
                    </div>
                  </Link>

                  <Link 
                    href="/itmbooks.com/faq" 
                    className="dashboard-popover-item"
                    onClick={() => setHelpOpen(false)}
                  >
                    <span className="dashboard-popover-item-icon">💡</span>
                    <div>
                      <div className="dashboard-popover-item-title">Frequently Asked Questions</div>
                      <div className="dashboard-popover-item-text">
                        Answers to common questions regarding multi-currency & payments.
                      </div>
                    </div>
                  </Link>

                  <div className="dashboard-popover-item">
                    <span className="dashboard-popover-item-icon">⌨️</span>
                    <div>
                      <div className="dashboard-popover-item-title">Keyboard Shortcuts</div>
                      <div className="dashboard-popover-item-text">
                        Press <strong>⌘ F</strong> or <strong>Ctrl + F</strong> to jump to any page or document instantly.
                      </div>
                    </div>
                  </div>
                </div>

                <div className="dashboard-popover-footer">
                  <Link 
                    href="/itmbooks.com/contact" 
                    className="dashboard-popover-footer-link"
                    onClick={() => setHelpOpen(false)}
                  >
                    Need more help? Contact Support →
                  </Link>
                </div>
              </div>
            )}
          </div>

        </div>

        {/* 3. User Profile Badge & Dropdown */}
        <div className="dashboard-user-container">
          <button 
            type="button" 
            className={`dashboard-user-badge-btn ${userMenuOpen ? 'active' : ''}`}
            onClick={() => {
              setUserMenuOpen(!userMenuOpen);
              setMessagesOpen(false);
              setNotificationsOpen(false);
              setHelpOpen(false);
              setSearchFocused(false);
            }}
          >
            <div className="dashboard-user-avatar">
              {initials}
            </div>
            
            <div className="dashboard-user-info-text">
              <span className="dashboard-user-name">{userName}</span>
              <span className="dashboard-user-email">{userEmail || `${companyName} workspace`}</span>
            </div>

            <span className={`dashboard-user-chevron ${userMenuOpen ? 'open' : ''}`}>
              ▼
            </span>
          </button>

          {/* Interactive User Menu Dropdown */}
          {userMenuOpen && (
            <div className="dashboard-user-dropdown-menu">
              
              <div className="dashboard-user-menu-header">
                <div style={{ fontWeight: 700, fontSize: '0.9rem', color: '#0f172a' }}>{userName}</div>
                <div style={{ fontSize: '0.75rem', color: '#64748b', wordBreak: 'break-all' }}>{userEmail}</div>
                <span className="dashboard-user-menu-role-badge">
                  {companyName} • {accountType}
                </span>
              </div>

              <Link 
                href={`/${companySlug}/settings`} 
                className="dashboard-user-menu-item"
                onClick={() => setUserMenuOpen(false)}
              >
                <span>⚙️</span> Account & Organization Settings
              </Link>

              <Link 
                href={`/${companySlug}/subscription`} 
                className="dashboard-user-menu-item"
                onClick={() => setUserMenuOpen(false)}
              >
                <span>💳</span> Billing & Subscription
              </Link>

              <Link 
                href={`/${companySlug}/reports`} 
                className="dashboard-user-menu-item"
                onClick={() => setUserMenuOpen(false)}
              >
                <span>📊</span> Financial Reports
              </Link>

              <div className="dashboard-user-menu-divider" />

              <button 
                type="button"
                className="dashboard-user-menu-item signout-item"
                onClick={() => {
                  setUserMenuOpen(false);
                  signOut({ callbackUrl: '/itmbooks.com' });
                }}
              >
                <span>🚪</span> Sign Out
              </button>

            </div>
          )}
        </div>

      </div>

    </div>
  );
}
