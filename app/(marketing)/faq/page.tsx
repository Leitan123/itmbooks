import Link from 'next/link';

export const metadata = {
  title: 'FAQs | ITM Books',
};

export default function FAQPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>Frequently Asked Questions</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto' }}>
          Find answers to common questions about our platform and services.
        </p>
        <Link href="/" className="btn btn-primary">Return Home</Link>
      </div>
    </div>
  );
}
