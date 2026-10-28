import Link from 'next/link';

export const metadata = {
  title: 'Help Center | ITM Books',
};

export default function HelpCenterPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>How can we help?</h1>
        <div style={{ maxWidth: '600px', margin: '0 auto 4rem' }}>
          <input type="text" placeholder="Search for articles, tutorials, and FAQs..." style={{ width: '100%', padding: '1rem', borderRadius: '8px', border: '1px solid var(--border-color)', fontSize: '1rem' }} />
        </div>
        
        <h2 style={{ fontSize: '1.5rem', marginBottom: '2rem' }}>Browse Categories</h2>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))', gap: '2rem', textAlign: 'left' }}>
          <div style={{ padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)', backgroundColor: 'var(--bg-alt)' }}>
            <h3 style={{ fontSize: '1.1rem', marginBottom: '0.5rem' }}>Getting Started</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem' }}>Learn how to set up your account and company.</p>
          </div>
          <div style={{ padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)', backgroundColor: 'var(--bg-alt)' }}>
            <h3 style={{ fontSize: '1.1rem', marginBottom: '0.5rem' }}>Invoicing</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem' }}>Everything about creating and sending invoices.</p>
          </div>
          <div style={{ padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)', backgroundColor: 'var(--bg-alt)' }}>
            <h3 style={{ fontSize: '1.1rem', marginBottom: '0.5rem' }}>Accounting</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem' }}>Journals, ledgers, and financial reports.</p>
          </div>
          <div style={{ padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)', backgroundColor: 'var(--bg-alt)' }}>
            <h3 style={{ fontSize: '1.1rem', marginBottom: '0.5rem' }}>Inventory</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem' }}>Managing stock, warehouses, and adjustments.</p>
          </div>
        </div>
      </div>
    </div>
  );
}
