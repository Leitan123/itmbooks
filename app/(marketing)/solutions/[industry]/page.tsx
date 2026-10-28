import Link from 'next/link';
import { notFound } from 'next/navigation';

export async function generateMetadata(props: { params: Promise<{ industry: string }> }) {
  const params = await props.params;
  const titles: Record<string, string> = {
    'small-business': 'Small Businesses',
    'sme': 'SMEs',
    'retail': 'Retail',
    'wholesale': 'Wholesale',
    'manufacturing': 'Manufacturing',
    'services': 'Service Businesses',
    'freelancers': 'Freelancers',
    'accountants': 'Accountants',
    'professional-services': 'Professional Services'
  };

  const title = titles[params.industry];
  if (!title) return { title: 'Solutions | ITM Books' };

  return {
    title: `Accounting for ${title} | ITM Books`,
  };
}

export default async function IndustrySolutionPage(props: { params: Promise<{ industry: string }> }) {
  const params = await props.params;
  const details: Record<string, { title: string, subtitle: string, features: string[] }> = {
    'small-business': {
      title: 'Small Businesses',
      subtitle: 'Everything you need to keep your small business finances in check.',
      features: ['Easy Expense Tracking', 'Professional Invoicing', 'Cash Flow Monitoring']
    },
    'sme': {
      title: 'SMEs',
      subtitle: 'Robust tools to scale your operations.',
      features: ['Multi-currency Support', 'Advanced Inventory Management', 'Detailed Financial Reports']
    },
    'retail': {
      title: 'Retail',
      subtitle: 'Manage sales and inventory across multiple channels.',
      features: ['POS Integration', 'Real-time Stock Levels', 'Sales Tax Automation']
    },
    'wholesale': {
      title: 'Wholesale',
      subtitle: 'B2B invoicing and bulk order management made easy.',
      features: ['Custom Price Lists', 'Bulk Invoicing', 'Warehouse Management']
    },
    'manufacturing': {
      title: 'Manufacturing',
      subtitle: 'Track production costs from raw materials to finished goods.',
      features: ['Bill of Materials (BOM)', 'Cost of Goods Sold (COGS)', 'Work in Progress (WIP)']
    },
    'services': {
      title: 'Service Businesses',
      subtitle: 'Streamline your billing and project profitability.',
      features: ['Time Tracking', 'Project Accounting', 'Recurring Invoices']
    },
    'freelancers': {
      title: 'Freelancers',
      subtitle: 'Simple, fast, and professional accounting for solo workers.',
      features: ['Quick Invoicing', 'Receipt Scanning', 'Tax Preparation']
    },
    'accountants': {
      title: 'Accountants',
      subtitle: 'Manage all your clients in one powerful dashboard.',
      features: ['Client Portal', 'Bulk Reconciliations', 'Custom Reporting']
    },
    'professional-services': {
      title: 'Professional Services',
      subtitle: 'Advanced reporting and billing for consulting and law firms.',
      features: ['Retainer Management', 'Expense Rebilling', 'Partner Profitability']
    }
  };

  const content = details[params.industry];

  if (!content) {
    notFound();
  }

  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', margin: '0 auto 1rem', maxWidth: '800px' }}>Accounting software built for {content.title}</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto 3rem', fontSize: '1.2rem' }}>
          {content.subtitle}
        </p>
        
        <div style={{ display: 'flex', justifyContent: 'center', gap: '1rem', marginBottom: '4rem' }}>
          <Link href="/signup" className="btn btn-primary btn-large">Start Free Trial</Link>
          <Link href="/demo" className="btn btn-secondary btn-large" style={{ padding: '0.8rem 1.5rem', borderRadius: '4px', border: '1px solid var(--border-color)', backgroundColor: 'transparent', textDecoration: 'none', color: 'inherit' }}>Request Demo</Link>
        </div>

        <h2 style={{ fontSize: '2rem', marginBottom: '2rem' }}>Key Features for {content.title}</h2>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '2rem', textAlign: 'left', maxWidth: '900px', margin: '0 auto' }}>
          {content.features.map((feature, index) => (
            <div key={index} style={{ background: 'var(--bg-alt)', padding: '2rem', borderRadius: '12px', border: '1px solid var(--border-color)' }}>
              <div style={{ color: 'var(--primary-color)', fontSize: '2rem', marginBottom: '1rem' }}>✓</div>
              <h3 style={{ margin: '0 0 0.5rem 0' }}>{feature}</h3>
              <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem', margin: 0 }}>Discover how our {feature.toLowerCase()} tools can transform your day-to-day workflow.</p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
