import Link from 'next/link';

export const metadata = {
  title: 'Partners | ITM Books',
};

export default function PartnersPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>Partner Program</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto' }}>
          Become an ITM Books partner and grow your accounting practice.
        </p>
        <Link href="/" className="btn btn-primary">Return Home</Link>
      </div>
    </div>
  );
}
