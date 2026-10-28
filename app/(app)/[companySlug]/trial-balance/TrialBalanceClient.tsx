'use client';

import { useState } from 'react';
import Link from 'next/link';

interface TrialBalanceRecord {
  id: number;
  code: string;
  name: string;
  typeId: number;
  typeName: string;
  openingBalance: number;
  periodDebit: number;
  periodCredit: number;
  netDebit: number;
  netCredit: number;
}

interface TrialBalanceData {
  records: TrialBalanceRecord[];
  totalDebit: number;
  totalCredit: number;
  difference: number;
  isBalanced: boolean;
}

export default function TrialBalanceClient({ 
  initialData, 
  basePath, 
  companySlug 
}: { 
  initialData: TrialBalanceData, 
  basePath: string, 
  companySlug: string 
}) {
  const [data, setData] = useState<TrialBalanceData>(initialData);
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

      const res = await fetch(`${basePath}/api/accounting/trial-balance?${params.toString()}`);
      if (res.ok) {
        const json = await res.json();
        setData(json);
      }
    } catch (err) {
      console.error('Failed to fetch trial balance:', err);
    } finally {
      setIsLoading(false);
    }
  };

  const handlePrint = () => {
    window.print();
  };

  return (
    <div className="page-container">
      {/* Header */}
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Trial Balance</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Summary of all general ledger account debit and credit balances proving double-entry equilibrium.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <button onClick={handlePrint} className="btn btn-outline">
            🖨️ Print Report
          </button>
          <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-primary">
            + New Journal
          </Link>
        </div>
      </div>

      {/* Date Filter Bar */}
      <div className="card" style={{ padding: '16px 20px', marginBottom: '24px' }}>
        <form onSubmit={handleFilter} style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>From:</label>
            <input 
              type="date" 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={fromDate} 
              onChange={(e) => setFromDate(e.target.value)} 
            />
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>To:</label>
            <input 
              type="date" 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={toDate} 
              onChange={(e) => setToDate(e.target.value)} 
            />
          </div>
          <button type="submit" className="btn btn-primary" style={{ padding: '7px 18px', fontSize: '0.85rem' }} disabled={isLoading}>
            {isLoading ? 'Updating...' : 'Filter Records'}
          </button>
          {fromDate && (
            <button 
              type="button" 
              className="btn btn-outline" 
              style={{ padding: '7px 14px', fontSize: '0.85rem' }}
              onClick={() => { setFromDate(''); setToDate(new Date().toISOString().split('T')[0]); }}
            >
              Reset
            </button>
          )}
        </form>
      </div>

      {/* Equilibrium Status Badge Banner */}
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
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          <span style={{ fontSize: '1.5rem' }}>{data.isBalanced ? '✅' : '⚠️'}</span>
          <div>
            <div style={{ fontWeight: 700, color: data.isBalanced ? '#166534' : '#991b1b', fontSize: '1rem' }}>
              {data.isBalanced ? 'Trial Balance is in Exact Equilibrium' : 'Trial Balance Out of Equilibrium'}
            </div>
            <div style={{ fontSize: '0.85rem', color: data.isBalanced ? '#15803d' : '#b91c1c' }}>
              {data.isBalanced 
                ? 'All general ledger debits and credits match perfectly with zero discrepancy.' 
                : `Difference of $${data.difference.toFixed(2)} detected. Review recent journal entries.`}
            </div>
          </div>
        </div>
        <div style={{ textAlign: 'right' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Discrepancy</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: data.isBalanced ? '#166534' : '#dc2626' }}>
            ${data.difference.toFixed(2)}
          </div>
        </div>
      </div>

      {/* Trial Balance Table */}
      <div className="card" style={{ overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', backgroundColor: '#f8fafc', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '12%' }}>Code</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '40%' }}>Account Title</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '18%' }}>Account Type</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'right', width: '15%' }}>Debit ($)</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'right', width: '15%' }}>Credit ($)</th>
            </tr>
          </thead>
          <tbody>
            {data.records.length === 0 ? (
              <tr>
                <td colSpan={5} style={{ padding: '32px', textAlign: 'center', color: '#64748b' }}>
                  No transactions recorded for the selected period.
                </td>
              </tr>
            ) : (
              data.records.map((r) => (
                <tr key={r.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td style={{ padding: '12px 16px', fontWeight: 600, color: '#1e293b' }}>
                    <Link href={`/${companySlug}/general-ledger?accountId=${r.id}`} style={{ color: '#2563eb', textDecoration: 'none' }}>
                      {r.code}
                    </Link>
                  </td>
                  <td style={{ padding: '12px 16px', fontWeight: 500, color: '#0f172a' }}>
                    <Link href={`/${companySlug}/general-ledger?accountId=${r.id}`} style={{ color: 'inherit', textDecoration: 'none' }}>
                      {r.name}
                    </Link>
                  </td>
                  <td style={{ padding: '12px 16px' }}>
                    <span style={{
                      padding: '3px 8px',
                      borderRadius: '4px',
                      fontSize: '0.75rem',
                      fontWeight: 600,
                      backgroundColor: r.typeId === 1 ? '#ecfdf5' : r.typeId === 2 ? '#fff7ed' : r.typeId === 3 ? '#faf5ff' : r.typeId === 4 ? '#eff6ff' : '#fdf2f8',
                      color: r.typeId === 1 ? '#065f46' : r.typeId === 2 ? '#9a3412' : r.typeId === 3 ? '#6b21a8' : r.typeId === 4 ? '#1e40af' : '#9d174d'
                    }}>
                      {r.typeName}
                    </span>
                  </td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 600, color: r.netDebit > 0 ? '#0f172a' : '#94a3b8' }}>
                    {r.netDebit > 0 ? r.netDebit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-'}
                  </td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 600, color: r.netCredit > 0 ? '#0f172a' : '#94a3b8' }}>
                    {r.netCredit > 0 ? r.netCredit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-'}
                  </td>
                </tr>
              ))
            )}
          </tbody>
          <tfoot>
            <tr style={{ backgroundColor: '#f8fafc', borderTop: '2px solid #cbd5e1', fontWeight: 700, fontSize: '1rem' }}>
              <td colSpan={3} style={{ padding: '16px', textAlign: 'right', color: '#0f172a' }}>
                TOTAL EQUILIBRIUM:
              </td>
              <td style={{ padding: '16px', textAlign: 'right', color: '#166534', borderBottom: '3px double #166534' }}>
                ${data.totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
              </td>
              <td style={{ padding: '16px', textAlign: 'right', color: '#166534', borderBottom: '3px double #166534' }}>
                ${data.totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  );
}
