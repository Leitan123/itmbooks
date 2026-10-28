import Link from 'next/link';
import '../feature.css';

export const metadata = {
  title: 'Cloud Accounting Software | ITM Books',
  description: 'Complete Accounting, Without the Complexity. Manage your complete accounting system with double-entry bookkeeping, chart of accounts, and financial statements.',
};

export default function AccountingPage() {
  return (
    <div className="feature-page">
      <section className="feature-hero">
        <div className="container text-center">
          <h1>Complete Accounting, Without the Complexity</h1>
          <p className="feature-subtitle">
            Manage your complete accounting system with double-entry bookkeeping, chart of accounts, journals and financial statements.
          </p>
          <div className="feature-ctas">
            <Link href="/signup" className="btn btn-primary btn-large">Start Free</Link>
          </div>
        </div>
      </section>

      <section className="feature-details">
        <div className="container">
          <div className="feature-grid">
            <div className="feature-card">
              <h3>Chart of Accounts</h3>
              <p>Organize your finances with a standard, fully customizable chart of accounts tailored to your business structure.</p>
            </div>
            <div className="feature-card">
              <h3>Double-entry Accounting</h3>
              <p>Ensure accuracy and compliance with automated double-entry accounting operating seamlessly in the background.</p>
            </div>
            <div className="feature-card">
              <h3>Journal Entries</h3>
              <p>Easily create manual journal entries to adjust accounts, record depreciation, or handle complex transactions.</p>
            </div>
            <div className="feature-card">
              <h3>General Ledger</h3>
              <p>Keep a complete, audit-ready record of all your financial transactions organized in one secure place.</p>
            </div>
            <div className="feature-card">
              <h3>Financial Statements</h3>
              <p>Generate instant Profit & Loss, Balance Sheet, and Trial Balance reports with real-time data.</p>
            </div>
            <div className="feature-card">
              <h3>Accounts Receivable & Payable</h3>
              <p>Track exactly who owes you money and who you owe money to effortlessly, improving your cash flow.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
