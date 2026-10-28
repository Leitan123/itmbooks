import Link from 'next/link';

export const metadata = {
  title: 'Guides | ITM Books',
};

export default function GuidesPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>Accounting Guides</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto' }}>
          Step-by-step guides to master ITM Books and scale your business.
        </p>
        <Link href="/" className="btn btn-primary">Return Home</Link>
      </div>
    </div>
  );
}
