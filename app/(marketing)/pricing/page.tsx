import Link from 'next/link';
import './pricing.css';

export const metadata = {
  title: 'Pricing | ITM Books',
  description: 'Simple, transparent pricing for growing businesses.',
};

export default function PricingPage() {
  return (
    <div className="pricing-page">
      <section className="pricing-hero">
        <div className="container text-center">
          <h1>Simple, Transparent Pricing</h1>
          <p className="pricing-subtitle">
            Choose the plan that fits your business needs. Upgrade, downgrade, or cancel at any time.
          </p>
        </div>
      </section>

      <section className="pricing-plans-section">
        <div className="container">
          <div className="pricing-grid">
            <div className="pricing-card">
              <h3>Free</h3>
              <p className="plan-desc">Basic accounting and invoicing.</p>
              <div className="price"><span>$0</span>/mo</div>
              <ul className="plan-features">
                <li>1 User</li>
                <li>Up to 10 Invoices/mo</li>
                <li>Basic Reports</li>
                <li>Community Support</li>
              </ul>
              <Link href="/signup" className="btn btn-secondary btn-block">Start Free</Link>
            </div>
            
            <div className="pricing-card popular">
              <div className="popular-badge">Most Popular</div>
              <h3>Starter</h3>
              <p className="plan-desc">For small businesses.</p>
              <div className="price"><span>$15</span>/mo</div>
              <ul className="plan-features">
                <li>3 Users</li>
                <li>Unlimited Invoices</li>
                <li>Expense Tracking</li>
                <li>Standard Reports</li>
                <li>Email Support</li>
              </ul>
              <Link href="/signup" className="btn btn-primary btn-block">Start Free Trial</Link>
            </div>

            <div className="pricing-card">
              <h3>Professional</h3>
              <p className="plan-desc">Accounting + inventory + advanced features.</p>
              <div className="price"><span>$30</span>/mo</div>
              <ul className="plan-features">
                <li>5 Users</li>
                <li>Advanced Inventory</li>
                <li>Multi-currency</li>
                <li>Project Management</li>
                <li>Priority Support</li>
              </ul>
              <Link href="/signup" className="btn btn-secondary btn-block">Start Free Trial</Link>
            </div>

            <div className="pricing-card">
              <h3>Enterprise</h3>
              <p className="plan-desc">Advanced controls, integrations and support.</p>
              <div className="price"><span>Custom</span></div>
              <ul className="plan-features">
                <li>Unlimited Users</li>
                <li>Multiple Organizations</li>
                <li>Custom Integrations</li>
                <li>Dedicated Account Manager</li>
                <li>24/7 Phone Support</li>
              </ul>
              <Link href="/contact" className="btn btn-secondary btn-block">Contact Sales</Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
