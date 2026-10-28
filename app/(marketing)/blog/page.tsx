import Link from 'next/link';

export const metadata = {
  title: 'Blog | ITM Books',
};

export default function BlogPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-alt)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>ITM Books Blog</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem' }}>
          Latest news, accounting tips, and business guides.
        </p>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))', gap: '2rem', textAlign: 'left' }}>
          <div style={{ background: 'var(--bg-color)', padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)' }}>
            <span style={{ color: 'var(--primary-color)', fontSize: '0.85rem', fontWeight: 600 }}>Accounting</span>
            <h3 style={{ margin: '0.5rem 0' }}>The Ultimate Guide to Cloud Accounting in 2026</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem', marginBottom: '1rem' }}>Learn why transitioning to cloud accounting is essential for modern businesses.</p>
            <Link href="#" style={{ color: 'var(--primary-color)', fontWeight: 600, fontSize: '0.9rem' }}>Read Article →</Link>
          </div>
          <div style={{ background: 'var(--bg-color)', padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)' }}>
            <span style={{ color: 'var(--primary-color)', fontSize: '0.85rem', fontWeight: 600 }}>SME</span>
            <h3 style={{ margin: '0.5rem 0' }}>5 Ways to Improve Cash Flow</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem', marginBottom: '1rem' }}>Actionable strategies to maintain a healthy cash flow for your small business.</p>
            <Link href="#" style={{ color: 'var(--primary-color)', fontWeight: 600, fontSize: '0.9rem' }}>Read Article →</Link>
          </div>
          <div style={{ background: 'var(--bg-color)', padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)' }}>
            <span style={{ color: 'var(--primary-color)', fontSize: '0.85rem', fontWeight: 600 }}>Product Updates</span>
            <h3 style={{ margin: '0.5rem 0' }}>Introducing the ITM Books AI Assistant</h3>
            <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem', marginBottom: '1rem' }}>Ask questions and get instant insights from your financial data.</p>
            <Link href="#" style={{ color: 'var(--primary-color)', fontWeight: 600, fontSize: '0.9rem' }}>Read Article →</Link>
          </div>
        </div>
      </div>
    </div>
  );
}
