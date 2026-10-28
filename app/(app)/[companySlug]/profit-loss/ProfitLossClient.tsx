'use client';

import { useState } from 'react';
import Link from 'next/link';

interface AccountLine {
  id: number;
  code: string;
  name: string;
  amount: number;
}

interface ProfitLossData {
  incomeAccounts: AccountLine[];
  totalIncome: number;
  expenseAccounts: AccountLine[];
  totalExpenses: number;
  netProfit: number;
}

export default function ProfitLossClient({
  initialData,
  basePath,
  companySlug
}: {
  initialData: ProfitLossData,
  basePath: string,
  companySlug: string
}) {
  const [data, setData] = useState<ProfitLossData>(initialData);
  const [fromDate, setFromDate] = useState('');
  const [toDate, setToDate] = useState(new Date().toISOString().split('T')[0]);
  const [isLoading, setIsLoading] = useState(false);

  const handleFilter = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      const params = new URLSearchParams();
      if (fromDate) params.set('from_date', fromDate);
      if (toDate) params.set('to_date', toDate);

      const res = await fetch(`${basePath}/api/accounting/profit-loss?${params.toString()}`);
      if (res.ok) {
        const json = await res.json();
        setData(json);
      }
    } catch (err) {
      console.error('Failed to load P&L statement:', err);
    } finally {
      setIsLoading(false);
    }
  };

  const handlePrint = () => {
    window.print();
  };

  const netMargin = data.totalIncome > 0 ? (data.netProfit / data.totalIncome) * 100 : 0;

  return (
    <div className="page-container">
      {/* Header */}
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Profit & Loss Statement</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Statement of financial performance comparing revenue against operational expenditures.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <button onClick={handlePrint} className="btn btn-outline">
            🖨️ Print Statement
          </button>
          <Link href={`/${companySlug}/balance-sheet`} className="btn btn-primary">
            Balance Sheet
          </Link>
        </div>
      </div>

      {/* Date Filter Bar */}
      <div className="card" style={{ padding: '16px 20px', marginBottom: '24px' }}>
        <form onSubmit={handleFilter} style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>From Date:</label>
            <input 
              type="date" 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={fromDate} 
              onChange={(e) => setFromDate(e.target.value)} 
            />
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>To Date:</label>
            <input 
              type="date" 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={toDate} 
              onChange={(e) => setToDate(e.target.value)} 
            />
          </div>
          <button type="submit" className="btn btn-primary" style={{ padding: '7px 18px', fontSize: '0.85rem' }} disabled={isLoading}>
            {isLoading ? 'Updating...' : 'Generate P&L'}
          </button>
        </form>
      </div>

      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '20px', borderLeft: '4px solid #3b82f6' }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Revenue</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>
            ${data.totalIncome.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '20px', borderLeft: '4px solid #ef4444' }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Expenses</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>
            ${data.totalExpenses.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '20px', borderLeft: `4px solid ${data.netProfit >= 0 ? '#10b981' : '#dc2626'}` }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Net Profit / (Loss)</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: data.netProfit >= 0 ? '#166534' : '#dc2626', marginTop: '6px' }}>
            ${data.netProfit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
          <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>
            Net Margin: {netMargin.toFixed(1)}%
          </div>
        </div>
      </div>

      {/* Statement Sheet */}
      <div className="card" style={{ padding: '32px', maxWidth: '900px', margin: '0 auto' }}>
        <div style={{ textAlign: 'center', borderBottom: '2px solid #0f172a', paddingBottom: '16px', marginBottom: '24px' }}>
          <h2 style={{ margin: '0 0 4px 0', fontSize: '1.5rem', fontWeight: 800 }}>INCOME STATEMENT</h2>
          <div style={{ fontSize: '0.9rem', color: '#64748b' }}>
            {fromDate ? `For period ${fromDate} to ${toDate}` : `Cumulative to date: ${toDate}`}
          </div>
        </div>

        {/* 1. Operating Income */}
        <div style={{ marginBottom: '28px' }}>
          <div style={{ 
            fontSize: '1rem', 
            fontWeight: 700, 
            color: '#1e3a8a', 
            backgroundColor: '#eff6ff', 
            padding: '8px 12px', 
            borderRadius: '4px',
            marginBottom: '12px'
          }}>
            1. OPERATING REVENUE & INCOME
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
            <tbody>
              {data.incomeAccounts.length === 0 ? (
                <tr>
                  <td colSpan={2} style={{ padding: '8px 12px', color: '#94a3b8', fontStyle: 'italic' }}>No income accounts active</td>
                </tr>
              ) : (
                data.incomeAccounts.map(acc => (
                  <tr key={acc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '10px 12px' }}>
                      <Link href={`/${companySlug}/general-ledger?accountId=${acc.id}`} style={{ color: '#0f172a', textDecoration: 'none' }}>
                        {acc.code} - {acc.name}
                      </Link>
                    </td>
                    <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600 }}>
                      ${acc.amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                  </tr>
                ))
              )}
              <tr style={{ borderTop: '2px solid #cbd5e1', fontWeight: 700 }}>
                <td style={{ padding: '12px', color: '#1e3a8a' }}>TOTAL OPERATING REVENUE:</td>
                <td style={{ padding: '12px', textAlign: 'right', color: '#1e3a8a', fontSize: '1rem' }}>
                  ${data.totalIncome.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* 2. Operating Expenses */}
        <div style={{ marginBottom: '28px' }}>
          <div style={{ 
            fontSize: '1rem', 
            fontWeight: 700, 
            color: '#991b1b', 
            backgroundColor: '#fef2f2', 
            padding: '8px 12px', 
            borderRadius: '4px',
            marginBottom: '12px'
          }}>
            2. OPERATING & ADMINISTRATIVE EXPENSES
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
            <tbody>
              {data.expenseAccounts.length === 0 ? (
                <tr>
                  <td colSpan={2} style={{ padding: '8px 12px', color: '#94a3b8', fontStyle: 'italic' }}>No expense accounts recorded</td>
                </tr>
              ) : (
                data.expenseAccounts.map(acc => (
                  <tr key={acc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '10px 12px' }}>
                      <Link href={`/${companySlug}/general-ledger?accountId=${acc.id}`} style={{ color: '#0f172a', textDecoration: 'none' }}>
                        {acc.code} - {acc.name}
                      </Link>
                    </td>
                    <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600 }}>
                      ${acc.amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                  </tr>
                ))
              )}
              <tr style={{ borderTop: '2px solid #cbd5e1', fontWeight: 700 }}>
                <td style={{ padding: '12px', color: '#991b1b' }}>TOTAL OPERATING EXPENSES:</td>
                <td style={{ padding: '12px', textAlign: 'right', color: '#991b1b', fontSize: '1rem' }}>
                  ${data.totalExpenses.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* 3. Summary Bottom Line */}
        <div style={{ 
          backgroundColor: data.netProfit >= 0 ? '#f0fdf4' : '#fef2f2',
          border: `2px solid ${data.netProfit >= 0 ? '#16a34a' : '#dc2626'}`,
          borderRadius: '8px',
          padding: '16px 20px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginTop: '20px'
        }}>
          <div>
            <div style={{ fontSize: '1.15rem', fontWeight: 800, color: data.netProfit >= 0 ? '#166534' : '#991b1b' }}>
              NET PROFIT / (LOSS) FOR PERIOD:
            </div>
            <div style={{ fontSize: '0.8rem', color: '#64748b' }}>
              Calculated automatically from general ledger double-entry postings
            </div>
          </div>
          <div style={{ 
            fontSize: '1.6rem', 
            fontWeight: 800, 
            color: data.netProfit >= 0 ? '#166534' : '#dc2626',
            borderBottom: `3px double ${data.netProfit >= 0 ? '#166534' : '#dc2626'}`
          }}>
            ${data.netProfit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
      </div>
    </div>
  );
}
