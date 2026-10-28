import Link from 'next/link';
import './page.css';

export default function Home() {
  return (
    <div className="home-page">
      {/* 5. HERO SECTION */}
      <section className="hero-section">
        <div className="container hero-inner">
          <div className="hero-content">
            <h1 className="hero-title">Powerful Accounting. Simple Business.</h1>
            <p className="hero-text">
              ITM Books is a complete cloud accounting and business management platform that helps you manage invoices, expenses, inventory, banking, customers, vendors and financial reports — all in one place.
            </p>
            <div className="hero-ctas">
              <Link href="/signup" className="btn btn-primary btn-large">Start Free</Link>
              <Link href="/demo" className="btn btn-secondary btn-large">Book a Demo</Link>
            </div>
            <p className="trust-statement">Manage your business finances from anywhere, anytime.</p>
          </div>
          
          {/* 6. HERO PRODUCT VISUAL */}
          <div className="hero-visual">
            <div className="dashboard-mockup">
              <div className="mockup-header">
                <div className="dots"><span></span><span></span><span></span></div>
                <div className="mockup-title">ITM Books Dashboard</div>
              </div>
              <div className="mockup-body">
                <div className="mockup-kpis">
                  <div className="kpi-card">
                    <span className="kpi-label">Total Revenue</span>
                    <span className="kpi-value text-success">$45,230.00</span>
                  </div>
                  <div className="kpi-card">
                    <span className="kpi-label">Total Expenses</span>
                    <span className="kpi-value text-error">$12,450.00</span>
                  </div>
                  <div className="kpi-card">
                    <span className="kpi-label">Net Profit</span>
                    <span className="kpi-value text-primary">$32,780.00</span>
                  </div>
                </div>
                <div className="mockup-charts">
                  <div className="chart-placeholder sales-chart">
                    Sales & Revenue Overview
                    <div className="chart-bars">
                      <div className="bar" style={{height: '60%'}}></div>
                      <div className="bar" style={{height: '80%'}}></div>
                      <div className="bar" style={{height: '40%'}}></div>
                      <div className="bar" style={{height: '100%'}}></div>
                      <div className="bar" style={{height: '70%'}}></div>
                    </div>
                  </div>
                  <div className="chart-placeholder expenses-chart">
                    Outstanding Invoices
                    <ul className="invoice-list">
                      <li>INV-001 <span className="text-warning">Pending</span></li>
                      <li>INV-002 <span className="text-success">Paid</span></li>
                      <li>INV-003 <span className="text-error">Overdue</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 7. TRUST SECTION */}
      <section className="trust-section">
        <div className="container text-center">
          <h2>Everything you need to manage your business finances</h2>
          <div className="trust-badges">
            <span className="badge">Cloud-based</span>
            <span className="badge">Secure</span>
            <span className="badge">Multi-user</span>
            <span className="badge">Real-time reports</span>
            <span className="badge">Mobile access</span>
            <span className="badge">Automated workflows</span>
          </div>
        </div>
      </section>

      {/* 8. FEATURES OVERVIEW */}
      <section className="features-section bg-alt">
        <div className="container">
          <div className="section-header text-center">
            <h2>Everything your business needs. One powerful platform.</h2>
          </div>
          <div className="features-grid">
            <div className="feature-card">
              <h3>Accounting</h3>
              <p>Manage your complete accounting system with double-entry bookkeeping, chart of accounts, journals and financial statements.</p>
              <Link href="/accounting" className="feature-link">Explore Accounting →</Link>
            </div>
            <div className="feature-card">
              <h3>Invoicing</h3>
              <p>Create professional invoices, send them online and track payments.</p>
              <Link href="/invoicing" className="feature-link">Create Your First Invoice →</Link>
            </div>
            <div className="feature-card">
              <h3>Inventory</h3>
              <p>Track stock, warehouses, purchases, sales, adjustments and inventory valuation.</p>
              <Link href="/inventory" className="feature-link">Explore Inventory →</Link>
            </div>
            <div className="feature-card">
              <h3>Expenses</h3>
              <p>Record, approve and monitor business expenses.</p>
            </div>
            <div className="feature-card">
              <h3>Banking</h3>
              <p>Connect or import bank transactions and reconcile accounts.</p>
            </div>
            <div className="feature-card">
              <h3>Customers & Vendors</h3>
              <p>Manage customers, suppliers, balances, invoices, bills and payments.</p>
            </div>
            <div className="feature-card">
              <h3>Projects</h3>
              <p>Track projects, expenses, timesheets and profitability.</p>
            </div>
            <div className="feature-card">
              <h3>Reports</h3>
              <p>Get real-time financial and business reports.</p>
              <Link href="/reports" className="feature-link">Explore Reports →</Link>
            </div>
            <div className="feature-card">
              <h3>Automation & AI</h3>
              <p>Automate recurring workflows, and ask questions about your business to get insights.</p>
              <Link href="/ai" className="feature-link">Explore AI →</Link>
            </div>
          </div>
        </div>
      </section>

    </div>
  );
}
