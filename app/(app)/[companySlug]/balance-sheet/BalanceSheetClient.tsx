'use client';

import { useState } from 'react';
import Link from 'next/link';

interface AccountBalanceLine {
  id: number;
  code: string;
  name: string;
  balance: number;
}

interface BalanceSheetData {
  assets: AccountBalanceLine[];
  totalAssets: number;
  liabilities: AccountBalanceLine[];
  totalLiabilities: number;
  equity: AccountBalanceLine[];
  currentPeriodProfit: number;
  totalEquity: number;
  totalLiabilitiesAndEquity: number;
  isBalanced: boolean;
}

export default function BalanceSheetClient({
  initialData,
  basePath,
  companySlug
}: {
  initialData: BalanceSheetData,
  basePath: string,
  companySlug: string
}) {
  const [data, setData] = useState<BalanceSheetData>(initialData);
  const [asOfDate, setAsOfDate] = useState(new Date().toISOString().split('T')[0]);
  const [isLoading, setIsLoading] = useState(false);

  const handleFilter = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      const params = new URLSearchParams();
      if (asOfDate) params.set('as_of', asOfDate);

      const res = await fetch(`${basePath}/api/accounting/balance-sheet?${params.toString()}`);
      if (res.ok) {
        const json = await res.json();
        setData(json);
      }
    } catch (err) {
      console.error('Failed to load balance sheet:', err);
    } finally {
      setIsLoading(false);
    }
  };

  const handlePrint = () => {
    window.print();
  };

  const discrepancy = Math.abs(data.totalAssets - data.totalLiabilitiesAndEquity);

  return (
    <div className="page-container">
      {/* Header */}
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Balance Sheet</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Statement of financial position showing company Assets, Liabilities, and Shareholder Equity.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <button onClick={handlePrint} className="btn btn-outline">
            🖨️ Print Sheet
          </button>
          <Link href={`/${companySlug}/profit-loss`} className="btn btn-primary">
            Profit & Loss
          </Link>
        </div>
      </div>

      {/* Filter Bar */}
      <div className="card" style={{ padding: '16px 20px', marginBottom: '24px' }}>
        <form onSubmit={handleFilter} style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>As of Date:</label>
            <input 
              type="date" 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={asOfDate} 
              onChange={(e) => setAsOfDate(e.target.value)} 
            />
          </div>
          <button type="submit" className="btn btn-primary" style={{ padding: '7px 18px', fontSize: '0.85rem' }} disabled={isLoading}>
            {isLoading ? 'Computing...' : 'Recalculate Statement'}
          </button>
        </form>
      </div>

      {/* Accounting Equation Verification Banner */}
      <div style={{
        padding: '16px 20px',
        borderRadius: '8px',
        marginBottom: '24px',
        backgroundColor: data.isBalanced ? '#f0fdf4' : '#fef2f2',
        border: `1px solid ${data.isBalanced ? '#bbf7d0' : '#fecaca'}`,
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center'
      }}>
        <div>
          <div style={{ fontWeight: 700, color: data.isBalanced ? '#166534' : '#991b1b', fontSize: '1rem' }}>
            {data.isBalanced ? '✓ Fundamental Equation Balances (Assets = Liabilities + Equity)' : '⚠ Discrepancy in Accounting Equation'}
          </div>
          <div style={{ fontSize: '0.85rem', color: data.isBalanced ? '#15803d' : '#b91c1c', marginTop: '2px' }}>
            Assets: ${data.totalAssets.toFixed(2)} | Liabilities & Equity: ${data.totalLiabilitiesAndEquity.toFixed(2)}
          </div>
        </div>
        <div style={{ textAlign: 'right' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Discrepancy</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: data.isBalanced ? '#166534' : '#dc2626' }}>
            ${discrepancy.toFixed(2)}
          </div>
        </div>
      </div>

      {/* Balance Sheet Paper Container */}
      <div className="card" style={{ padding: '32px', maxWidth: '900px', margin: '0 auto' }}>
        <div style={{ textAlign: 'center', borderBottom: '2px solid #0f172a', paddingBottom: '16px', marginBottom: '24px' }}>
          <h2 style={{ margin: '0 0 4px 0', fontSize: '1.5rem', fontWeight: 800 }}>STATEMENT OF FINANCIAL POSITION</h2>
          <div style={{ fontSize: '0.9rem', color: '#64748b' }}>
            As of: {asOfDate}
          </div>
        </div>

        {/* ASSETS SECTION */}
        <div style={{ marginBottom: '28px' }}>
          <div style={{ 
            fontSize: '1rem', 
            fontWeight: 700, 
            color: '#065f46', 
            backgroundColor: '#ecfdf5', 
            padding: '8px 12px', 
            borderRadius: '4px',
            marginBottom: '12px'
          }}>
            1. ASSETS
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
            <tbody>
              {data.assets.length === 0 ? (
                <tr>
                  <td colSpan={2} style={{ padding: '8px 12px', color: '#94a3b8', fontStyle: 'italic' }}>No asset accounts active</td>
                </tr>
              ) : (
                data.assets.map(acc => (
                  <tr key={acc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '10px 12px' }}>
                      <Link href={`/${companySlug}/general-ledger?accountId=${acc.id}`} style={{ color: '#0f172a', textDecoration: 'none' }}>
                        {acc.code} - {acc.name}
                      </Link>
                    </td>
                    <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600 }}>
                      ${acc.balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                  </tr>
                ))
              )}
              <tr style={{ borderTop: '2px solid #cbd5e1', fontWeight: 700 }}>
                <td style={{ padding: '12px', color: '#065f46' }}>TOTAL ASSETS:</td>
                <td style={{ padding: '12px', textAlign: 'right', color: '#065f46', fontSize: '1.1rem', borderBottom: '3px double #065f46' }}>
                  ${data.totalAssets.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* LIABILITIES SECTION */}
        <div style={{ marginBottom: '28px' }}>
          <div style={{ 
            fontSize: '1rem', 
            fontWeight: 700, 
            color: '#9a3412', 
            backgroundColor: '#fff7ed', 
            padding: '8px 12px', 
            borderRadius: '4px',
            marginBottom: '12px'
          }}>
            2. LIABILITIES
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
            <tbody>
              {data.liabilities.length === 0 ? (
                <tr>
                  <td colSpan={2} style={{ padding: '8px 12px', color: '#94a3b8', fontStyle: 'italic' }}>No liability accounts recorded</td>
                </tr>
              ) : (
                data.liabilities.map(acc => (
                  <tr key={acc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '10px 12px' }}>
                      <Link href={`/${companySlug}/general-ledger?accountId=${acc.id}`} style={{ color: '#0f172a', textDecoration: 'none' }}>
                        {acc.code} - {acc.name}
                      </Link>
                    </td>
                    <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600 }}>
                      ${acc.balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                  </tr>
                ))
              )}
              <tr style={{ borderTop: '2px solid #cbd5e1', fontWeight: 700 }}>
                <td style={{ padding: '12px', color: '#9a3412' }}>TOTAL LIABILITIES:</td>
                <td style={{ padding: '12px', textAlign: 'right', color: '#9a3412', fontSize: '1.05rem' }}>
                  ${data.totalLiabilities.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* EQUITY SECTION */}
        <div style={{ marginBottom: '28px' }}>
          <div style={{ 
            fontSize: '1rem', 
            fontWeight: 700, 
            color: '#6b21a8', 
            backgroundColor: '#faf5ff', 
            padding: '8px 12px', 
            borderRadius: '4px',
            marginBottom: '12px'
          }}>
            3. EQUITY & CAPITAL
          </div>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
            <tbody>
              {data.equity.map(acc => (
                <tr key={acc.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td style={{ padding: '10px 12px' }}>
                    <Link href={`/${companySlug}/general-ledger?accountId=${acc.id}`} style={{ color: '#0f172a', textDecoration: 'none' }}>
                      {acc.code} - {acc.name}
                    </Link>
                  </td>
                  <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600 }}>
                    ${acc.balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                  </td>
                </tr>
              ))}
              {/* Retained Earnings from Current Net Profit */}
              <tr style={{ borderBottom: '1px solid #f1f5f9', fontStyle: 'italic', backgroundColor: '#fafafa' }}>
                <td style={{ padding: '10px 12px' }}>
                  Current Period Net Earnings (rolled from P&L)
                </td>
                <td style={{ padding: '10px 12px', textAlign: 'right', fontWeight: 600, color: data.currentPeriodProfit >= 0 ? '#166534' : '#dc2626' }}>
                  ${data.currentPeriodProfit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
              <tr style={{ borderTop: '2px solid #cbd5e1', fontWeight: 700 }}>
                <td style={{ padding: '12px', color: '#6b21a8' }}>TOTAL EQUITY:</td>
                <td style={{ padding: '12px', textAlign: 'right', color: '#6b21a8', fontSize: '1.05rem' }}>
                  ${data.totalEquity.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* EQUATION SUM TOTAL */}
        <div style={{ 
          backgroundColor: '#f8fafc',
          border: '2px solid #0f172a',
          borderRadius: '8px',
          padding: '16px 20px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginTop: '20px'
        }}>
          <div>
            <div style={{ fontSize: '1.15rem', fontWeight: 800, color: '#0f172a' }}>
              TOTAL LIABILITIES & EQUITY:
            </div>
            <div style={{ fontSize: '0.8rem', color: '#64748b' }}>
              Must equal Total Assets (${data.totalAssets.toFixed(2)})
            </div>
          </div>
          <div style={{ 
            fontSize: '1.6rem', 
            fontWeight: 800, 
            color: data.isBalanced ? '#166534' : '#dc2626',
            borderBottom: '3px double #0f172a'
          }}>
            ${data.totalLiabilitiesAndEquity.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
      </div>
    </div>
  );
}
