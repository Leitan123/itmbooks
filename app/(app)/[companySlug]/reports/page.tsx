import Link from "next/link";
import { FaFileInvoiceDollar, FaBalanceScale, FaBook, FaMoneyBillWave, FaChartLine, FaUsers } from "react-icons/fa";

export default function ReportsPage({ params }: { params: { companySlug: string } }) {
  const reports = [
    {
      title: "Profit and Loss",
      description: "Income, expenses, and net profit for a selected period.",
      icon: <FaChartLine size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/profit-and-loss`
    },
    {
      title: "Balance Sheet",
      description: "What your business owns and owes at a specific date.",
      icon: <FaBalanceScale size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/balance-sheet`
    },
    {
      title: "Trial Balance",
      description: "A summary of all your account balances.",
      icon: <FaBook size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/trial-balance`
    },
    {
      title: "General Ledger",
      description: "Detailed transactions for all your accounts.",
      icon: <FaFileInvoiceDollar size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/general-ledger`
    },
    {
      title: "A/R Aging",
      description: "Unpaid invoices and how long they've been overdue.",
      icon: <FaUsers size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/ar-aging`
    },
    {
      title: "Cash Flow Statement",
      description: "The flow of cash in and out of your business.",
      icon: <FaMoneyBillWave size={24} color="var(--primary-color)" />,
      link: `/${params.companySlug}/reports/cash-flow`
    }
  ];

  return (
    <>
      <div className="page-header">
        <h1 className="page-title">Reports</h1>
      </div>
      
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))', gap: '20px', marginTop: '20px' }}>
        {reports.map((report, idx) => (
          <Link href={report.link} key={idx} style={{ textDecoration: 'none', color: 'inherit' }}>
            <div className="card" style={{ display: 'flex', gap: '20px', alignItems: 'center', transition: 'transform 0.2s, box-shadow 0.2s', cursor: 'pointer', height: '100%' }}>
              <div style={{ padding: '15px', backgroundColor: 'var(--bg-alt)', borderRadius: '12px' }}>
                {report.icon}
              </div>
              <div>
                <h3 style={{ fontSize: '1.1rem', marginBottom: '8px', fontWeight: 600 }}>{report.title}</h3>
                <p style={{ color: 'var(--text-secondary)', fontSize: '0.9rem', lineHeight: '1.4' }}>{report.description}</p>
              </div>
            </div>
          </Link>
        ))}
      </div>
    </>
  );
}