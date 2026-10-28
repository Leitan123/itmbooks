import Link from 'next/link';

export const metadata = {
  title: 'Automation | ITM Books',
};

export default function AutomationPage() {
  return (
    <div style={{ padding: '6rem 0', flex: 1, backgroundColor: 'var(--bg-color)' }}>
      <div className="container text-center">
        <h1 style={{ fontSize: '3rem', marginBottom: '1rem' }}>Workflow Automation</h1>
        <p style={{ color: 'var(--text-secondary)', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto' }}>
          Set up rules to automate repetitive tasks like recurring invoices and payment reminders.
        </p>
        <Link href="/" className="btn btn-primary">Return Home</Link>
      </div>
    </div>
  );
}
