'use client';

import { useState } from 'react';
import Link from 'next/link';

interface LedgerTransaction {
  id: number;
  date: string;
  reference: string;
  description: string;
  debit: number;
  credit: number;
  runningBalance: number;
}

interface AccountInfo {
  id: number;
  code: string;
  name: string;
  typeId: number;
  typeName: string;
  openingBalance: number;
  closingBalance: number;
}

interface LedgerAccountBlock {
  account: AccountInfo;
  transactions: LedgerTransaction[];
  totalDebit: number;
  totalCredit: number;
}

export default function GeneralLedgerClient({
  initialLedger,
  accounts,
  basePath,
  companySlug,
  initialAccountId
}: {
  initialLedger: LedgerAccountBlock[],
  accounts: { id: number; code: string; name: string }[],
  basePath: string,
  companySlug: string,
  initialAccountId?: number
}) {
  const [ledger, setLedger] = useState<LedgerAccountBlock[]>(initialLedger);
  const [selectedAccount, setSelectedAccount] = useState<string>(initialAccountId ? initialAccountId.toString() : '');
  const [fromDate, setFromDate] = useState('');
  const [toDate, setToDate] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleFilter = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      const params = new URLSearchParams();
      if (selectedAccount) params.set('account_id', selectedAccount);
      if (fromDate) params.set('from_date', fromDate);
      if (toDate) params.set('to_date', toDate);

      const res = await fetch(`${basePath}/api/accounting/general-ledger?${params.toString()}`);
      if (res.ok) {
        const json = await res.json();
        setLedger(json);
      }
    } catch (err) {
      console.error('Failed to load ledger:', err);
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
          <h1 className="page-title">General Ledger</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Chronological audit trail of all posted debits, credits, and running balances for every account.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <button onClick={handlePrint} className="btn btn-outline">
            🖨️ Print Ledger
          </button>
          <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-primary">
            + Post Journal
          </Link>
        </div>
      </div>

      {/* Filter Bar */}
      <div className="card" style={{ padding: '16px 20px', marginBottom: '24px' }}>
        <form onSubmit={handleFilter} style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px', minWidth: '280px' }}>
            <label style={{ fontSize: '0.85rem', fontWeight: 600, color: '#475569' }}>Account:</label>
            <select 
              className="form-input" 
              style={{ margin: 0, padding: '6px 12px', fontSize: '0.85rem' }}
              value={selectedAccount}
              onChange={(e) => setSelectedAccount(e.target.value)}
            >
              <option value="">All Accounts</option>
              {accounts.map(acc => (
                <option key={acc.id} value={acc.id}>{acc.code} - {acc.name}</option>
              ))}
            </select>
          </div>
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
            {isLoading ? 'Loading...' : 'Filter Ledger'}
          </button>
        </form>
      </div>

      {/* Ledger Accounts Blocks */}
      {ledger.length === 0 ? (
        <div className="card" style={{ padding: '40px', textAlign: 'center', color: '#64748b' }}>
          No ledger activity found for the selected accounts or date range.
        </div>
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          {ledger.map((block) => (
            <div key={block.account.id} className="card" style={{ overflow: 'hidden', padding: 0 }}>
              {/* Account Header */}
              <div style={{ 
                padding: '16px 20px', 
                backgroundColor: '#f8fafc', 
                borderBottom: '1px solid #e2e8f0',
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                flexWrap: 'wrap',
                gap: '12px'
              }}>
                <div>
                  <span style={{ 
                    padding: '3px 8px', 
                    borderRadius: '4px', 
                    fontSize: '0.75rem', 
                    fontWeight: 700, 
                    backgroundColor: '#e2e8f0', 
                    color: '#334155',
                    marginRight: '8px'
                  }}>
                    {block.account.code}
                  </span>
                  <span style={{ fontWeight: 700, fontSize: '1.05rem', color: '#0f172a' }}>
                    {block.account.name}
                  </span>
                  <span style={{ marginLeft: '12px', fontSize: '0.8rem', color: '#64748b' }}>
                    ({block.account.typeName})
                  </span>
                </div>
                <div style={{ display: 'flex', gap: '24px', fontSize: '0.85rem' }}>
                  <div>
                    <span style={{ color: '#64748b' }}>Opening Balance: </span>
                    <strong style={{ color: '#0f172a' }}>${block.account.openingBalance.toFixed(2)}</strong>
                  </div>
                  <div>
                    <span style={{ color: '#64748b' }}>Closing Balance: </span>
                    <strong style={{ color: block.account.closingBalance < 0 ? '#dc2626' : '#166534' }}>
                      ${block.account.closingBalance.toFixed(2)}
                    </strong>
                  </div>
                </div>
              </div>

              {/* Transactions Table */}
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.85rem' }}>
                <thead>
                  <tr style={{ borderBottom: '1px solid #e2e8f0', color: '#64748b', textAlign: 'left', backgroundColor: '#ffffff' }}>
                    <th style={{ padding: '10px 16px', width: '12%' }}>Date</th>
                    <th style={{ padding: '10px 16px', width: '15%' }}>Reference</th>
                    <th style={{ padding: '10px 16px', width: '37%' }}>Description</th>
                    <th style={{ padding: '10px 16px', textAlign: 'right', width: '12%' }}>Debit ($)</th>
                    <th style={{ padding: '10px 16px', textAlign: 'right', width: '12%' }}>Credit ($)</th>
                    <th style={{ padding: '10px 16px', textAlign: 'right', width: '12%' }}>Balance ($)</th>
                  </tr>
                </thead>
                <tbody>
                  {block.transactions.length === 0 ? (
                    <tr>
                      <td colSpan={6} style={{ padding: '20px', textAlign: 'center', color: '#94a3b8', fontStyle: 'italic' }}>
                        No transactions during this period. Opening balance carried forward.
                      </td>
                    </tr>
                  ) : (
                    block.transactions.map((tx) => (
                      <tr key={tx.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                        <td style={{ padding: '10px 16px', color: '#0f172a' }}>{new Date(tx.date).toLocaleDateString()}</td>
                        <td style={{ padding: '10px 16px', fontWeight: 600, color: '#2563eb' }}>{tx.reference || '-'}</td>
                        <td style={{ padding: '10px 16px', color: '#334155' }}>{tx.description || '-'}</td>
                        <td style={{ padding: '10px 16px', textAlign: 'right', fontWeight: 600, color: tx.debit > 0 ? '#10b981' : '#94a3b8' }}>
                          {tx.debit > 0 ? tx.debit.toFixed(2) : '-'}
                        </td>
                        <td style={{ padding: '10px 16px', textAlign: 'right', fontWeight: 600, color: tx.credit > 0 ? '#f97316' : '#94a3b8' }}>
                          {tx.credit > 0 ? tx.credit.toFixed(2) : '-'}
                        </td>
                        <td style={{ padding: '10px 16px', textAlign: 'right', fontWeight: 700, color: '#0f172a' }}>
                          ${tx.runningBalance.toFixed(2)}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
                {block.transactions.length > 0 && (
                  <tfoot>
                    <tr style={{ backgroundColor: '#f8fafc', borderTop: '1px solid #cbd5e1', fontWeight: 700 }}>
                      <td colSpan={3} style={{ padding: '10px 16px', textAlign: 'right', color: '#475569' }}>Period Total:</td>
                      <td style={{ padding: '10px 16px', textAlign: 'right', color: '#10b981' }}>${block.totalDebit.toFixed(2)}</td>
                      <td style={{ padding: '10px 16px', textAlign: 'right', color: '#f97316' }}>${block.totalCredit.toFixed(2)}</td>
                      <td style={{ padding: '10px 16px', textAlign: 'right', color: '#0f172a' }}>${block.account.closingBalance.toFixed(2)}</td>
                    </tr>
                  </tfoot>
                )}
              </table>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
