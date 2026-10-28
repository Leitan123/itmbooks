import Link from 'next/link';
import '../feature.css';

export const metadata = {
  title: 'Invoicing Software | ITM Books',
  description: 'Create professional invoices in seconds. Send them online and track payments with ease.',
};

export default function InvoicingPage() {
  return (
    <div className="feature-page">
      <section className="feature-hero">
        <div className="container text-center">
          <h1>Create professional invoices in seconds</h1>
          <p className="feature-subtitle">
            Impress your clients with beautiful invoices, automate reminders, and get paid faster with integrated online payments.
          </p>
          <div className="feature-ctas">
            <Link href="/signup" className="btn btn-primary btn-large">Create Your First Invoice</Link>
          </div>
        </div>
      </section>

      <section className="feature-details">
        <div className="container">
          <div className="feature-grid">
            <div className="feature-card">
              <h3>Professional Templates</h3>
              <p>Choose from a variety of clean, customizable invoice templates that reflect your brand identity.</p>
            </div>
            <div className="feature-card">
              <h3>Recurring Invoices</h3>
              <p>Set up automatic billing for regular customers and subscription services without manual intervention.</p>
            </div>
            <div className="feature-card">
              <h3>Payment Tracking</h3>
              <p>Know exactly when your clients view their invoices and track partial or full payments instantly.</p>
            </div>
            <div className="feature-card">
              <h3>Automated Reminders</h3>
              <p>Stop chasing payments manually. Configure polite, automated email reminders for overdue invoices.</p>
            </div>
            <div className="feature-card">
              <h3>Online Payments</h3>
              <p>Include secure payment links directly in your invoices so clients can pay you via credit card or bank transfer.</p>
            </div>
            <div className="feature-card">
              <h3>Credit Notes</h3>
              <p>Easily issue and manage credit notes for returned goods or billing adjustments.</p>
            </div>
          </div>
        </div>
      </section>

      <section className="pricing-plans-section" style={{ padding: '4rem 0', background: '#f8fafc' }}>
        <div className="container">
          <div style={{ textAlign: 'center', marginBottom: '3rem' }}>
            <h2>Start Invoicing Today</h2>
            <p className="feature-subtitle">Choose the plan that fits your business needs.</p>
          </div>
          <div className="pricing-grid" style={{ maxWidth: '800px', margin: '0 auto', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '2rem' }}>
            <div className="pricing-card" style={{ background: '#fff', padding: '2rem', borderRadius: '8px', border: '1px solid #e2e8f0', textAlign: 'center' }}>
              <h3>14-Day Free Trial</h3>
              <p className="plan-desc">Try all features before you commit.</p>
              <div className="price" style={{ fontSize: '2rem', fontWeight: 'bold', margin: '1rem 0' }}><span>LKR 0</span></div>
              <ul className="plan-features" style={{ listStyle: 'none', padding: 0, margin: '0 0 2rem 0', textAlign: 'left' }}>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ Full access for 14 days</li>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ Unlimited Invoices</li>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ Email Support</li>
              </ul>
              <Link href="/signup" className="btn btn-secondary btn-block" style={{ width: '100%', display: 'block' }}>Start Free Trial</Link>
            </div>
            
            <div className="pricing-card popular" style={{ background: '#fff', padding: '2rem', borderRadius: '8px', border: '2px solid #0b3c5d', textAlign: 'center', position: 'relative' }}>
              <div className="popular-badge" style={{ position: 'absolute', top: '-12px', left: '50%', transform: 'translateX(-50%)', background: '#0b3c5d', color: '#fff', padding: '4px 12px', borderRadius: '12px', fontSize: '0.8rem', fontWeight: 'bold' }}>Premium Plan</div>
              <h3>Paid Subscription</h3>
              <p className="plan-desc">For growing businesses.</p>
              <div className="price" style={{ fontSize: '2rem', fontWeight: 'bold', margin: '1rem 0', color: '#0b3c5d' }}><span>LKR 4,500</span>/mo</div>
              <ul className="plan-features" style={{ listStyle: 'none', padding: 0, margin: '0 0 2rem 0', textAlign: 'left' }}>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ All trial features</li>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ Priority 24/7 Support</li>
                <li style={{ padding: '0.5rem 0', borderBottom: '1px solid #e2e8f0' }}>✓ Custom Integrations</li>
              </ul>
              {/* Redirecting directly to the checkout payment gateway page */}
              <Link href="/hnb-checkout" className="btn btn-primary btn-block" style={{ width: '100%', display: 'block' }}>Add to Cart & Pay</Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
