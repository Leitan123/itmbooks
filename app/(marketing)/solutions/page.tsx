import Link from 'next/link';

export const metadata = {
  title: 'Solutions | ITM Books',
  description: 'Accounting solutions tailored for your business type.',
};

export default function SolutionsPage() {
  const industries = [
    { id: 'small-business', title: 'Small Businesses', description: 'Streamline your finances and get back to growing your business.' },
    { id: 'sme', title: 'SMEs', description: 'Scale your operations with robust accounting and inventory tools.' },
    { id: 'retail', title: 'Retail', description: 'Manage sales, inventory, and cash flow across multiple locations.' },
    { id: 'wholesale', title: 'Wholesale', description: 'Handle bulk orders, B2B invoicing, and warehouse management.' },
    { id: 'manufacturing', title: 'Manufacturing', description: 'Track raw materials, production costs, and finished goods.' },
    { id: 'services', title: 'Service Businesses', description: 'Time tracking, project profitability, and recurring invoicing.' },
    { id: 'freelancers', title: 'Freelancers', description: 'Simple expense tracking and professional invoices.' },
    { id: 'accountants', title: 'Accountants', description: 'Manage multiple clients from a single, powerful dashboard.' },
    { id: 'professional-services', title: 'Professional Services', description: 'Advanced reporting and billing for consulting and law firms.' },
  ];

  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>Solutions for Every Business</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto 3rem' }}>
          No matter what industry you're in, ITM Books has the tools you need to manage your finances efficiently and accurately.
        </p>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))', gap: '2rem', textAlign: 'left' }}>
          {industries.map((industry) => (
            <Link href={`/solutions/${industry.id}`} key={industry.id} style={{ textDecoration: 'none', color: 'inherit', display: 'block' }}>
              <div style={{ background: 'var(--bg-alt)', padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)', height: '100%', transition: 'transform 0.2s, box-shadow 0.2s', cursor: 'pointer' }} className="solution-card">
                <h3 style={{ margin: '0 0 1rem 0', color: 'var(--primary-color)' }}>{industry.title}</h3>
                <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem', margin: 0 }}>{industry.description}</p>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
