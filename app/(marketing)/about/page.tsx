import Link from 'next/link';

export const metadata = {
  title: 'About Us | ITM Books',
  description: 'Learn about ITM Books and our parent company IT MARTX (PVT) LTD.',
};

export default function AboutPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container">
        <div style={{ textAlign: 'center', marginBottom: '4rem' }}>
          <h1 style={{ fontSize: '3.5rem', marginBottom: '1rem' }}>About ITM Books</h1>
          <p style={{ color: 'var(--text-secondary)', fontSize: '1.2rem', maxWidth: '700px', margin: '0 auto' }}>
            Empowering businesses of all sizes with seamless, cloud-based accounting solutions.
          </p>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '4rem', alignItems: 'center', marginBottom: '4rem' }}>
          <div>
            <h2 style={{ fontSize: '2rem', marginBottom: '1.5rem', color: 'var(--primary-color)' }}>Our Mission</h2>
            <p style={{ color: 'var(--text-secondary)', fontSize: '1.1rem', lineHeight: '1.6', marginBottom: '1.5rem' }}>
              We are on a mission to simplify accounting and financial management for businesses around the world. ITM Books removes the complexity of bookkeeping, allowing you to focus on what matters most—growing your business.
            </p>
            <p style={{ color: 'var(--text-secondary)', fontSize: '1.1rem', lineHeight: '1.6' }}>
              Whether you are a freelancer, a growing startup, or an established enterprise, our intuitive tools adapt to your unique needs, providing real-time insights and unparalleled accuracy.
            </p>
          </div>
          <div style={{ background: 'var(--bg-alt)', padding: '3rem', borderRadius: '16px', border: '1px solid var(--border-color)', textAlign: 'center' }}>
            <h3 style={{ fontSize: '1.5rem', marginBottom: '1rem' }}>A Product By</h3>
            <div style={{ fontSize: '2rem', fontWeight: 'bold', color: 'var(--primary-color)', marginBottom: '1rem' }}>
              IT MARTX (PVT) LTD
            </div>
            <p style={{ color: 'var(--text-secondary)', fontSize: '1rem' }}>
              ITM Books is proudly developed and maintained by IT MARTX (PVT) LTD, a leading technology solutions provider dedicated to building innovative software that drives business success.
            </p>
          </div>
        </div>

        <div style={{ textAlign: 'center', marginTop: '6rem', padding: '4rem', background: 'var(--bg-alt)', borderRadius: '16px' }}>
          <h2 style={{ fontSize: '2rem', marginBottom: '1rem' }}>Ready to transform your accounting?</h2>
          <p style={{ color: 'var(--text-secondary)', marginBottom: '2rem' }}>
            Join thousands of businesses trusting ITM Books today.
          </p>
          <div style={{ display: 'flex', justifyContent: 'center', gap: '1rem' }}>
            <Link href="/signup" className="btn btn-primary btn-large">Start Free Trial</Link>
            <Link href="/contact" className="btn btn-secondary btn-large" style={{ padding: '0.8rem 1.5rem', borderRadius: '4px', border: '1px solid var(--border-color)', backgroundColor: 'transparent', textDecoration: 'none', color: 'inherit' }}>Contact Us</Link>
          </div>
        </div>
      </div>
    </div>
  );
}
