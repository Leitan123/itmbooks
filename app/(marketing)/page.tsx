'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import './page.css';

export default function Home() {
  // State for interactive hero dashboard mockup
  const [mockCurrency, setMockCurrency] = useState<'USD' | 'LKR' | 'EUR' | 'GBP'>('USD');
  const [activeModuleTab, setActiveModuleTab] = useState<'invoicing' | 'accounting' | 'inventory' | 'ai'>('invoicing');

  // Currency multiplier & prefix
  const curPrefix = mockCurrency === 'USD' ? '$' : mockCurrency === 'EUR' ? '€' : mockCurrency === 'GBP' ? '£' : 'Rs. ';
  const curMultiplier = mockCurrency === 'USD' ? 1 : mockCurrency === 'EUR' ? 0.92 : mockCurrency === 'GBP' ? 0.79 : 315;

  const fmt = (baseVal: number) => {
    const val = baseVal * curMultiplier;
    return `${curPrefix}${val.toLocaleString('en-US', { maximumFractionDigits: 0 })}`;
  };

  // Module content dictionary
  const moduleData = {
    invoicing: {
      tag: 'Sales & Billing',
      title: 'Accelerate Cash Flow with Frictionless Invoicing',
      desc: 'Create, send, and track stunning, brand-aligned invoices and formal estimates. Convert accepted quotes to invoices in one click, accept payments online, and trigger automated polite payment reminders.',
      features: [
        'Quotes & Estimates with automated 1-click invoice conversion',
        'Multi-currency billing & automated regional tax calculations',
        'Automated recurring invoices & payment gateway integrations',
        'Real-time customer view receipts and settlement tracking'
      ],
      linkHref: '/invoicing',
      linkText: 'Explore Invoicing Engine →',
      previewHead: 'Invoice #INV-2026-084',
      previewCustomer: 'Acme Global Enterprises Ltd',
      previewItems: [
        { desc: 'Cloud Infrastructure & SaaS Setup', qty: '1', amount: fmt(4500) },
        { desc: 'Financial Workflow Automation', qty: '12 hrs', amount: fmt(1800) },
        { desc: 'Dedicated Server SLA Support', qty: '1', amount: fmt(950) }
      ],
      previewTotal: fmt(7250)
    },
    accounting: {
      tag: 'Ledger & Compliance',
      title: 'Certified Double-Entry Core Without the Complexity',
      desc: 'Maintain complete financial integrity with automated journal entries, a flexible chart of accounts, and live-updated balance sheets and profit & loss statements accessible at any second.',
      features: [
        'Complete double-entry general ledger with audit-trail history',
        'Instant Real-Time Balance Sheet, P&L, and Trial Balance',
        'Automatic journal entries triggered by invoices, bills, and expenses',
        'Tax-ready reporting customized for your jurisdiction'
      ],
      linkHref: '/accounting',
      linkText: 'Explore Accounting Suite →',
      previewHead: 'Profit & Loss Statement (YTD)',
      previewCustomer: 'All Consolidated Operating Entities',
      previewItems: [
        { desc: 'Operating Revenue & Sales', qty: '—', amount: fmt(128400) },
        { desc: 'Cost of Goods Sold (COGS)', qty: '—', amount: `-${fmt(42100)}` },
        { desc: 'Operating & Admin Expenses', qty: '—', amount: `-${fmt(18500)}` }
      ],
      previewTotal: fmt(67800)
    },
    inventory: {
      tag: 'Supply & Stock',
      title: 'Real-Time Inventory Tracking & Multi-Warehouse Control',
      desc: 'Always know your exact stock valuation, purchase commitments, and available units. Prevent stockouts with automated reorder thresholds and seamless invoice integration.',
      features: [
        'Real-time multi-location warehouse tracking & stock transfers',
        'Automated stock adjustments on invoice fulfillment and purchase receipt',
        'Weighted average & FIFO inventory cost valuation',
        'Low-stock alerts and purchase order generation'
      ],
      linkHref: '/inventory',
      linkText: 'Explore Inventory Management →',
      previewHead: 'Warehouse Stock Valuation',
      previewCustomer: 'Main Central Distribution Hub',
      previewItems: [
        { desc: 'Enterprise Server Node Units (In Stock: 48)', qty: '48 units', amount: fmt(34500) },
        { desc: 'Fiber Transceiver Modems (In Stock: 150)', qty: '150 units', amount: fmt(12000) },
        { desc: 'Rack Enclosures & Accessories (In Stock: 18)', qty: '18 units', amount: fmt(6400) }
      ],
      previewTotal: fmt(52900)
    },
    ai: {
      tag: 'Intelligence & Insights',
      title: 'AI-Powered Financial Copilot & Anomaly Detection',
      desc: 'Turn raw ledger data into strategic growth decisions. Ask natural language questions about your cash flow, identify revenue bottlenecks, and automate transaction categorization.',
      features: [
        'Predictive cash flow forecasting for the next 30, 60, and 90 days',
        'Instant natural-language queries: "What were my top expenses last month?"',
        'Automatic transaction classification and duplicate payment detection',
        'Smart customer risk scoring and overdue likelihood alerts'
      ],
      linkHref: '/ai',
      linkText: 'Explore AI Financial Tools →',
      previewHead: 'AI Financial Forecast & Insights',
      previewCustomer: 'Automated Cash Flow Projection',
      previewItems: [
        { desc: 'Projected Net Cash Inflow (Next 30 Days)', qty: 'Forecast', amount: fmt(48200) },
        { desc: 'Expected Collection from Top 5 Accounts', qty: '98% Conf.', amount: fmt(36000) },
        { desc: 'Identified Cost-Saving Opportunities', qty: '3 Recs', amount: fmt(4200) }
      ],
      previewTotal: fmt(88400)
    }
  };

  const currentMod = moduleData[activeModuleTab];

  return (
    <div className="home-page">
      
      {/* ===================================================================
          1. HERO SECTION
          =================================================================== */}
      <section className="hero-section">
        <div className="container hero-inner">
          
          {/* Announcement Badge */}
          <div className="hero-badge">
            <span className="hero-badge-dot" />
            <span>✨ The Modern Operating System for Business Finance</span>
          </div>

          {/* Main Hero Headline */}
          <h1 className="hero-title">
            Smart Cloud Accounting. <br />
            <span className="gradient-text">Built for Growing Businesses.</span>
          </h1>

          {/* Subtitle */}
          <p className="hero-subtitle">
            ITM Books brings together automated invoicing, double-entry general ledger, 
            inventory control, and real-time financial reporting into one ultra-fast, 
            intuitive platform.
          </p>

          {/* Action Buttons */}
          <div className="hero-ctas">
            <Link href="/signup" className="btn-hero-primary">
              <span>Start Free 14-Day Trial</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </Link>

            <Link href="/demo" className="btn-hero-secondary">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="10 8 16 12 10 16 10 8" fill="currentColor"></polygon>
              </svg>
              <span>Explore Interactive Demo</span>
            </Link>
          </div>

          {/* Trust Guarantees */}
          <div className="hero-trust-row">
            <div className="trust-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>No credit card required</span>
            </div>
            <div className="trust-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Instant setup in 60 seconds</span>
            </div>
            <div className="trust-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Bank-grade 256-bit encryption</span>
            </div>
          </div>

          {/* ===============================================================
              2. HERO PRODUCT VISUAL: Real Dribbble-Style Dashboard Showcase
              =============================================================== */}
          <div className="hero-mockup-wrapper">
            <div className="mockup-ambient-glow" />

            <div className="dashboard-showcase-card">
              {/* macOS Window Titlebar */}
              <div className="mockup-window-header">
                <div className="window-dots">
                  <span className="window-dot dot-red" />
                  <span className="window-dot dot-yellow" />
                  <span className="window-dot dot-green" />
                </div>

                <div className="mockup-window-search">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                  <span>Search invoices, quotations, customers...</span>
                  <kbd>⌘K</kbd>
                </div>

                <div className="mockup-window-status">
                  <span className="status-live-dot" />
                  <span>ITM Books Cloud • Live</span>
                </div>
              </div>

              {/* Mockup Body Content */}
              <div className="mockup-body">
                {/* Mockup Toolbar */}
                <div className="mockup-toolbar">
                  <div className="mockup-page-title">
                    <h3>Billing & Financial Operations</h3>
                    <p>Real-time enterprise telemetry & transaction flow</p>
                  </div>

                  {/* Interactive Currency Selector */}
                  <div className="mockup-currency-pills">
                    {(['USD', 'EUR', 'GBP', 'LKR'] as const).map((curr) => (
                      <button
                        key={curr}
                        type="button"
                        className={`currency-pill-btn ${mockCurrency === curr ? 'active' : ''}`}
                        onClick={() => setMockCurrency(curr)}
                      >
                        {curr}
                      </button>
                    ))}
                  </div>
                </div>

                {/* 4 KPI Cards Row */}
                <div className="mockup-kpis-grid">
                  <div className="m-kpi-card">
                    <div className="m-kpi-top">
                      <span className="m-accent-bar bar-blue" />
                      <span className="m-kpi-label">Total Invoiced</span>
                    </div>
                    <div className="m-kpi-val">{fmt(128450)}</div>
                    <div className="m-kpi-sub">
                      <span>↗ +24.6%</span> vs last month
                    </div>
                  </div>

                  <div className="m-kpi-card">
                    <div className="m-kpi-top">
                      <span className="m-accent-bar bar-amber" />
                      <span className="m-kpi-label">Active Customers</span>
                    </div>
                    <div className="m-kpi-val">3,240</div>
                    <div className="m-kpi-sub amber">
                      <span>↗ +142</span> new accounts
                    </div>
                  </div>

                  <div className="m-kpi-card">
                    <div className="m-kpi-top">
                      <span className="m-accent-bar bar-navy" />
                      <span className="m-kpi-label">Outstanding Dues</span>
                    </div>
                    <div className="m-kpi-val">{fmt(4850)}</div>
                    <div className="m-kpi-sub navy">
                      <span>✓ 98.2%</span> on-time collection
                    </div>
                  </div>

                  <div className="m-kpi-card">
                    <div className="m-kpi-top">
                      <span className="m-accent-bar bar-cyan" />
                      <span className="m-kpi-label">Quotations Pipeline</span>
                    </div>
                    <div className="m-kpi-val">{fmt(342800)}</div>
                    <div className="m-kpi-sub">
                      <span>⚡ 18</span> active proposals
                    </div>
                  </div>
                </div>

                {/* Charts Split Row */}
                <div className="mockup-charts-grid">
                  {/* Left: Monthly Recurring Revenue Curve */}
                  <div className="m-chart-card">
                    <div className="m-chart-title">Monthly Recurring Revenue</div>
                    <div className="m-chart-sub">Average Monthly Inflow: <strong>{fmt(26400)}</strong></div>

                    <div className="m-chart-svg-wrap">
                      <svg viewBox="0 0 540 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="20" y1="35" x2="520" y2="35" stroke="#f1f5f9" strokeDasharray="3 3" />
                        <line x1="20" y1="85" x2="520" y2="85" stroke="#f1f5f9" strokeDasharray="3 3" />
                        <line x1="20" y1="135" x2="520" y2="135" stroke="#f1f5f9" strokeDasharray="3 3" />
                        <line x1="20" y1="160" x2="520" y2="160" stroke="#e2e8f0" strokeWidth="1" />

                        {/* Signature Vertical Hatching Lines (ITM Brand Blue) */}
                        {[
                          40, 52, 64, 76, 88, 100, 112, 124, 136, 148, 160, 172, 184, 196,
                          208, 220, 232, 244, 256, 268, 280, 292, 304, 316, 328, 340, 352,
                          364, 376, 388, 400, 412, 424, 436, 448, 460, 472, 484, 496, 508
                        ].map((lx, i) => {
                          const rel = (lx - 40) / 468;
                          const curveY = 140 - Math.sin(rel * Math.PI) * 95 - Math.sin(rel * 4 * Math.PI) * 12;
                          return (
                            <line
                              key={i}
                              x1={lx}
                              y1={Math.max(35, curveY)}
                              x2={lx}
                              y2="160"
                              stroke="#168FCE"
                              strokeWidth="1.1"
                              strokeOpacity="0.28"
                            />
                          );
                        })}

                        {/* Smooth Spline Curve */}
                        <path
                          d="M 40 135 C 100 120, 140 60, 220 52 C 280 46, 340 90, 410 70 C 460 55, 485 38, 508 35"
                          stroke="#168FCE"
                          strokeWidth="3"
                          fill="none"
                          strokeLinecap="round"
                        />

                        {/* Focal Drop Indicator */}
                        <line x1="410" y1="70" x2="410" y2="160" stroke="#00539B" strokeWidth="2" />
                        <circle cx="410" cy="70" r="6" fill="#00539B" stroke="#ffffff" strokeWidth="2.5" />

                        {/* X-Axis Month Markers */}
                        <text x="40" y="174" fill="#94a3b8" fontSize="10" textAnchor="middle">May</text>
                        <text x="130" y="174" fill="#94a3b8" fontSize="10" textAnchor="middle">Jun</text>
                        <text x="220" y="174" fill="#94a3b8" fontSize="10" textAnchor="middle">Jul</text>
                        <text x="310" y="174" fill="#94a3b8" fontSize="10" textAnchor="middle">Aug</text>
                        <text x="410" y="174" fill="#00539B" fontWeight="700" fontSize="10" textAnchor="middle">Sep</text>
                        <text x="508" y="174" fill="#94a3b8" fontSize="10" textAnchor="middle">Oct</text>
                      </svg>
                    </div>
                  </div>

                  {/* Right: Settlement Status Arc Gauge */}
                  <div className="m-chart-card">
                    <div className="m-chart-title">Settlement Efficiency</div>
                    <div className="m-chart-sub">98.2% invoices collected on time</div>

                    <div className="m-chart-svg-wrap" style={{ textAlign: 'center' }}>
                      <svg viewBox="0 0 280 145" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ maxHeight: '125px', margin: '0 auto' }}>
                        <path d="M 35 125 A 105 105 0 0 1 245 125" stroke="#e0f2fe" strokeWidth="16" strokeLinecap="round" fill="none" />
                        <path
                          d="M 35 125 A 105 105 0 0 1 245 125"
                          stroke="#168FCE"
                          strokeWidth="16"
                          strokeLinecap="round"
                          fill="none"
                          strokeDasharray="330"
                          strokeDashoffset="24"
                        />
                        <path d="M 65 125 A 75 75 0 0 1 215 125" stroke="#fffbeb" strokeWidth="14" strokeLinecap="round" fill="none" />
                        <path
                          d="M 65 125 A 75 75 0 0 1 215 125"
                          stroke="#f59e0b"
                          strokeWidth="14"
                          strokeLinecap="round"
                          fill="none"
                          strokeDasharray="235"
                          strokeDashoffset="180"
                        />
                      </svg>

                      <div style={{ display: 'flex', justifyContent: 'center', gap: '14px', marginTop: '4px', fontSize: '0.78rem' }}>
                        <span style={{ color: '#0284c7', fontWeight: 600 }}>● Paid 92%</span>
                        <span style={{ color: '#b45309', fontWeight: 600 }}>● Pending 6%</span>
                        <span style={{ color: '#dc2626', fontWeight: 600 }}>● Overdue 2%</span>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Recent Activity Ticker */}
                <div className="mockup-ticker">
                  <div className="ticker-item">
                    <span>📄</span>
                    <strong>QT-6025</strong>
                    <span>Vinuka Akarawita — {fmt(12000)}</span>
                    <span className="ticker-badge badge-paid">Approved</span>
                  </div>
                  <div className="ticker-item">
                    <span>🧾</span>
                    <strong>INV-9042</strong>
                    <span>Horizon Digital Ltd — {fmt(4350)}</span>
                    <span className="ticker-badge badge-paid">Paid</span>
                  </div>
                  <div className="ticker-item">
                    <span>📄</span>
                    <strong>QT-6028</strong>
                    <span>Apex Engineering Corp — {fmt(18500)}</span>
                    <span className="ticker-badge badge-pending">Pending Review</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ===================================================================
          3. STATS & PERFORMANCE BAR
          =================================================================== */}
      <section className="stats-bar-section">
        <div className="container">
          <div className="stats-grid">
            <div className="stat-item">
              <span className="stat-num">$45M+</span>
              <span className="stat-label">Invoiced Annually</span>
              <span className="stat-desc">Processed reliably across enterprise tenants</span>
            </div>

            <div className="stat-item">
              <span className="stat-num">99.98%</span>
              <span className="stat-label">System Uptime</span>
              <span className="stat-desc">Zero-downtime ledger synchronization</span>
            </div>

            <div className="stat-item">
              <span className="stat-num">&lt; 30s</span>
              <span className="stat-label">Quote-to-Invoice Time</span>
              <span className="stat-desc">Frictionless 1-click conversion workflow</span>
            </div>

            <div className="stat-item">
              <span className="stat-num">15+</span>
              <span className="stat-label">Currencies & Tax Rules</span>
              <span className="stat-desc">Built for local compliance & global scale</span>
            </div>
          </div>
        </div>
      </section>

      {/* ===================================================================
          4. CORE ENGINES / INTERACTIVE MODULES SHOWCASE
          =================================================================== */}
      <section className="modules-section">
        <div className="container">
          <div className="text-center">
            <span className="section-tag">Enterprise Capabilities</span>
            <h2 className="section-heading-main">
              Everything Your Business Needs. <br />
              <span className="gradient-text">Unified in One Intelligent System.</span>
            </h2>
            <p className="section-subheading-main">
              No fragmented tools or disconnected spreadsheets. ITM Books integrates your entire commercial 
              lifecycle from initial proposal to final audited financial statement.
            </p>
          </div>

          {/* Module Switcher Tabs */}
          <div className="module-tabs-nav">
            <button
              type="button"
              className={`module-tab-btn ${activeModuleTab === 'invoicing' ? 'active' : ''}`}
              onClick={() => setActiveModuleTab('invoicing')}
            >
              <span>🧾</span> Smart Invoicing & Quotes
            </button>
            <button
              type="button"
              className={`module-tab-btn ${activeModuleTab === 'accounting' ? 'active' : ''}`}
              onClick={() => setActiveModuleTab('accounting')}
            >
              <span>📊</span> Double-Entry Accounting
            </button>
            <button
              type="button"
              className={`module-tab-btn ${activeModuleTab === 'inventory' ? 'active' : ''}`}
              onClick={() => setActiveModuleTab('inventory')}
            >
              <span>📦</span> Inventory & Warehousing
            </button>
            <button
              type="button"
              className={`module-tab-btn ${activeModuleTab === 'ai' ? 'active' : ''}`}
              onClick={() => setActiveModuleTab('ai')}
            >
              <span>✨</span> AI Copilot & Forecasting
            </button>
          </div>

          {/* Active Module Showcase Card */}
          <div className="module-display-card">
            <div className="module-text-col">
              <span className="section-tag">{currentMod.tag}</span>
              <h3>{currentMod.title}</h3>
              <p>{currentMod.desc}</p>

              <ul className="module-checklist">
                {currentMod.features.map((feat, i) => (
                  <li key={i}>
                    <span className="check-icon">✓</span>
                    <span>{feat}</span>
                  </li>
                ))}
              </ul>

              <Link href={currentMod.linkHref} className="module-action-link">
                <span>{currentMod.linkText}</span>
              </Link>
            </div>

            {/* Dynamic Interactive Sheet Mockup */}
            <div className="module-preview-col">
              <div className="preview-mock-sheet">
                <div className="sheet-head">
                  <div>
                    <strong style={{ display: 'block', fontSize: '1rem', color: '#0f172a' }}>{currentMod.previewHead}</strong>
                    <span style={{ fontSize: '0.8rem', color: '#64748b' }}>{currentMod.previewCustomer}</span>
                  </div>
                  <span className="sheet-badge">Active Record</span>
                </div>

                {currentMod.previewItems.map((item, idx) => (
                  <div key={idx} className="sheet-row">
                    <div>
                      <div style={{ fontWeight: 600, color: '#334155' }}>{item.desc}</div>
                      <div style={{ fontSize: '0.75rem', color: '#94a3b8' }}>Qty / Scope: {item.qty}</div>
                    </div>
                    <div style={{ fontWeight: 700, color: '#0f172a' }}>{item.amount}</div>
                  </div>
                ))}

                <div className="sheet-row total">
                  <span>Balance / Net Total</span>
                  <span style={{ color: '#00539B' }}>{currentMod.previewTotal}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ===================================================================
          5. COMPLETE PLATFORM FEATURES GRID (6 CARDS)
          =================================================================== */}
      <section className="features-grid-section">
        <div className="container">
          <div className="text-center">
            <span className="section-tag">Complete Feature Ecosystem</span>
            <h2 className="section-heading-main">Engineered for Accuracy, Built for Scale</h2>
            <p className="section-subheading-main">
              Explore how each layer of the ITM Books architecture powers your daily operations.
            </p>
          </div>

          <div className="features-cards-grid">
            {/* Box 1 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">📄</div>
              <h4>Formal Quotations & Estimates</h4>
              <p>
                Draft professional estimates, capture client approvals, and automatically convert 
                them into formal invoices without re-entering data or risking transcription errors.
              </p>
              <Link href="/quotations" className="feature-box-link">
                Learn about Quotations →
              </Link>
            </div>

            {/* Box 2 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">🏦</div>
              <h4>Banking & Auto-Reconciliation</h4>
              <p>
                Import bank statements, connect live transaction feeds, and let our intelligent 
                reconciliation algorithm match cash inflows with outstanding customer dues.
              </p>
              <Link href="/banking" className="feature-box-link">
                Explore Banking Features →
              </Link>
            </div>

            {/* Box 3 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">👥</div>
              <h4>Customer & Supplier Directory</h4>
              <p>
                Centralize customer accounts, track credit balances, monitor payment histories, 
                and maintain comprehensive vendor records for seamless procurement.
              </p>
              <Link href="/solutions" className="feature-box-link">
                View Contact Hub →
              </Link>
            </div>

            {/* Box 4 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">💳</div>
              <h4>Operational Expenses & Bills</h4>
              <p>
                Record bills from vendors, categorize operating overheads, attach scanned receipts, 
                and maintain tight control over accounts payable timelines.
              </p>
              <Link href="/expenses" className="feature-box-link">
                Track Expenses →
              </Link>
            </div>

            {/* Box 5 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">📊</div>
              <h4>Real-Time Financial Reports</h4>
              <p>
                Generate comprehensive Balance Sheets, Profit & Loss summaries, General Ledgers, 
                and Trial Balances instantly with one-click export to PDF or Excel.
              </p>
              <Link href="/reports" className="feature-box-link">
                View Sample Reports →
              </Link>
            </div>

            {/* Box 6 */}
            <div className="feature-box">
              <div className="feature-icon-bubble">🔒</div>
              <h4>Role-Based Access & Security</h4>
              <p>
                Grant strict permissions tailored for sales executives, billing clerks, chartered accountants, 
                and auditors with complete cryptographic session isolation.
              </p>
              <Link href="/security" className="feature-box-link">
                Explore Security Standards →
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* ===================================================================
          6. WORKFLOW ("HOW IT WORKS")
          =================================================================== */}
      <section className="workflow-section">
        <div className="container">
          <div className="text-center">
            <span className="section-tag">Streamlined Operations</span>
            <h2 className="section-heading-main">How ITM Books Accelerates Your Business</h2>
            <p className="section-subheading-main">
              Three simple steps to transform your financial administration into an automated competitive advantage.
            </p>
          </div>

          <div className="workflow-steps-grid">
            <div className="step-card">
              <div className="step-number">01</div>
              <h4>Create Proposals & Invoices</h4>
              <p>
                Generate estimates and formal tax invoices in seconds with pre-loaded product price books, 
                automatic tax calculations, and customized branding.
              </p>
            </div>

            <div className="step-card">
              <div className="step-number">02</div>
              <h4>Collect Payments Faster</h4>
              <p>
                Send invoices with direct payment links, track customer open status, and receive 
                instant confirmation when payments hit your bank.
              </p>
            </div>

            <div className="step-card">
              <div className="step-number">03</div>
              <h4>Automatic Ledger Posting</h4>
              <p>
                Every invoice, payment, and expense automatically posts to your chart of accounts. 
                Your books are closed in real-time, every single day.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* ===================================================================
          7. HIGH-CONVERSION CTA BANNER
          =================================================================== */}
      <section className="cta-banner-section">
        <div className="container">
          <div className="cta-banner-card">
            <h2 className="cta-banner-title">
              Ready to Upgrade Your Financial Architecture?
            </h2>
            <p className="cta-banner-sub">
              Join thousands of growing companies who trust ITM Books for reliable invoicing, 
              accounting, and financial clarity. Start your free trial today.
            </p>

            <div className="cta-banner-actions">
              <Link href="/signup" className="btn-cta-white">
                Start Free 14-Day Trial
              </Link>
              <Link href="/demo" className="btn-cta-outline">
                Book a Personalized Demo
              </Link>
            </div>

            <div className="cta-guarantee-note">
              ✓ No credit card required • Cancel anytime • 24/7 Priority Support
            </div>
          </div>
        </div>
      </section>

    </div>
  );
}
