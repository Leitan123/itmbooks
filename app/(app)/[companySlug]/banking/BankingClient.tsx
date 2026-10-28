'use client';

import { useState } from 'react';
import Link from 'next/link';
import '../../../(marketing)/form.css';

interface BankAccount {
  id: number;
  name: string;
  accountName: string;
  accountNumber: string;
  branch: string;
  openingBalance: number;
  currentBalance: number;
  accountId: number;
  accountCode: string;
}

interface CashAccount {
  id: number;
  code: string;
  name: string;
  balance: number;
}

export default function BankingClient({
  initialBanks,
  initialCash,
  basePath,
  companySlug
}: {
  initialBanks: BankAccount[],
  initialCash: CashAccount[],
  basePath: string,
  companySlug: string
}) {
  const [banks, setBanks] = useState<BankAccount[]>(initialBanks);
  const [cashAccounts] = useState<CashAccount[]>(initialCash);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    bankName: '',
    accountName: '',
    accountNumber: '',
    branch: '',
    openingBalance: '0.00'
  });
  const [isSaving, setIsSaving] = useState(false);
  const [error, setError] = useState('');

  const totalBankBalance = banks.reduce((s, b) => s + Number(b.currentBalance || 0), 0);
  const totalCashBalance = cashAccounts.reduce((s, c) => s + Number(c.balance || 0), 0);
  const totalLiquidFunds = totalBankBalance + totalCashBalance;

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    setError('');

    try {
      const res = await fetch(`${basePath}/api/accounting/banking`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const json = await res.json();
      if (res.ok) {
        setBanks([
          ...banks,
          {
            id: json.bankId,
            name: formData.bankName,
            accountName: formData.accountName || formData.bankName,
            accountNumber: formData.accountNumber,
            branch: formData.branch,
            openingBalance: parseFloat(formData.openingBalance) || 0,
            currentBalance: parseFloat(formData.openingBalance) || 0,
            accountId: json.accountId,
            accountCode: '1030'
          }
        ]);
        setIsModalOpen(false);
        setFormData({ bankName: '', accountName: '', accountNumber: '', branch: '', openingBalance: '0.00' });
      } else {
        setError(json.error || 'Failed to save bank account');
      }
    } catch (err) {
      setError('An error occurred');
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <div className="page-container">
      {/* Header */}
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Bank & Cash Accounts</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Manage liquid operating accounts, banking reconciliations, and cash balances.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-outline">
            Bank Transfer / Journal
          </Link>
          <button onClick={() => setIsModalOpen(true)} className="btn btn-primary">
            + Connect Bank Account
          </button>
        </div>
      </div>

      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '20px', borderLeft: '4px solid #10b981' }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Liquid Capital</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>
            ${totalLiquidFunds.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
          <div style={{ fontSize: '0.8rem', color: '#16a34a', marginTop: '4px' }}>Combined cash and bank deposits</div>
        </div>
        <div className="card" style={{ padding: '20px', borderLeft: '4px solid #2563eb' }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Bank Accounts</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>
            ${totalBankBalance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
          <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>{banks.length} registered accounts</div>
        </div>
        <div className="card" style={{ padding: '20px', borderLeft: '4px solid #8b5cf6' }}>
          <div style={{ fontSize: '0.8rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Cash in Hand</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '6px' }}>
            ${totalCashBalance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
          <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '4px' }}>Physical cash drawers & petty cash</div>
        </div>
      </div>

      {/* Bank Accounts Table */}
      <div className="card" style={{ marginBottom: '28px', overflow: 'hidden' }}>
        <div style={{ padding: '16px 20px', borderBottom: '1px solid #e2e8f0', backgroundColor: '#f8fafc', fontWeight: 700, color: '#0f172a', fontSize: '1rem' }}>
          Commercial Bank Accounts
        </div>
        <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
          <thead>
            <tr style={{ borderBottom: '1px solid #e2e8f0', textAlign: 'left', color: '#64748b', backgroundColor: '#ffffff' }}>
              <th style={{ padding: '12px 16px' }}>Bank Name</th>
              <th style={{ padding: '12px 16px' }}>Account Title</th>
              <th style={{ padding: '12px 16px' }}>Account Number</th>
              <th style={{ padding: '12px 16px' }}>Branch</th>
              <th style={{ padding: '12px 16px', textAlign: 'right' }}>Current Balance</th>
              <th style={{ padding: '12px 16px', textAlign: 'center' }}>Action</th>
            </tr>
          </thead>
          <tbody>
            {banks.length === 0 ? (
              <tr>
                <td colSpan={6} style={{ padding: '32px', textAlign: 'center', color: '#94a3b8' }}>
                  No commercial bank accounts found. Click "+ Connect Bank Account" to add one.
                </td>
              </tr>
            ) : (
              banks.map(b => (
                <tr key={b.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td style={{ padding: '14px 16px', fontWeight: 600, color: '#0f172a' }}>{b.name}</td>
                  <td style={{ padding: '14px 16px', color: '#334155' }}>{b.accountName || b.name}</td>
                  <td style={{ padding: '14px 16px', fontFamily: 'monospace', fontWeight: 600, color: '#2563eb' }}>{b.accountNumber}</td>
                  <td style={{ padding: '14px 16px', color: '#64748b' }}>{b.branch || '-'}</td>
                  <td style={{ padding: '14px 16px', textAlign: 'right', fontWeight: 700, color: Number(b.currentBalance || 0) < 0 ? '#dc2626' : '#166534' }}>
                    ${Number(b.currentBalance || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                  </td>
                  <td style={{ padding: '14px 16px', textAlign: 'center' }}>
                    <Link 
                      href={`/${companySlug}/general-ledger?accountId=${b.accountId}`}
                      style={{
                        padding: '4px 10px',
                        borderRadius: '4px',
                        backgroundColor: '#eff6ff',
                        color: '#2563eb',
                        fontSize: '0.8rem',
                        fontWeight: 600,
                        textDecoration: 'none'
                      }}
                    >
                      View Ledger
                    </Link>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Cash Accounts Table */}
      <div className="card" style={{ overflow: 'hidden' }}>
        <div style={{ padding: '16px 20px', borderBottom: '1px solid #e2e8f0', backgroundColor: '#f8fafc', fontWeight: 700, color: '#0f172a', fontSize: '1rem' }}>
          Cash on Hand & Petty Cash Registers
        </div>
        <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.9rem' }}>
          <thead>
            <tr style={{ borderBottom: '1px solid #e2e8f0', textAlign: 'left', color: '#64748b', backgroundColor: '#ffffff' }}>
              <th style={{ padding: '12px 16px', width: '20%' }}>Account Code</th>
              <th style={{ padding: '12px 16px', width: '45%' }}>Register / Drawer Name</th>
              <th style={{ padding: '12px 16px', textAlign: 'right', width: '20%' }}>Current Balance</th>
              <th style={{ padding: '12px 16px', textAlign: 'center', width: '15%' }}>Action</th>
            </tr>
          </thead>
          <tbody>
            {cashAccounts.map(c => (
              <tr key={c.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                <td style={{ padding: '14px 16px', fontWeight: 600, color: '#1e293b' }}>{c.code}</td>
                <td style={{ padding: '14px 16px', fontWeight: 500, color: '#0f172a' }}>{c.name}</td>
                <td style={{ padding: '14px 16px', textAlign: 'right', fontWeight: 700, color: Number(c.balance || 0) < 0 ? '#dc2626' : '#166534' }}>
                  ${Number(c.balance || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
                <td style={{ padding: '14px 16px', textAlign: 'center' }}>
                  <Link 
                    href={`/${companySlug}/general-ledger?accountId=${c.id}`}
                    style={{
                      padding: '4px 10px',
                      borderRadius: '4px',
                      backgroundColor: '#eff6ff',
                      color: '#2563eb',
                      fontSize: '0.8rem',
                      fontWeight: 600,
                      textDecoration: 'none'
                    }}
                  >
                    View Ledger
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Add Bank Modal */}
      {isModalOpen && (
        <div style={{
          position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
          backgroundColor: 'rgba(15, 23, 42, 0.6)', zIndex: 1000,
          display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(3px)'
        }}>
          <div className="card" style={{ width: '100%', maxWidth: '520px', padding: '28px', margin: '20px', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h2 style={{ margin: 0, fontSize: '1.25rem', fontWeight: 700 }}>Connect Bank Account</h2>
              <button onClick={() => setIsModalOpen(false)} style={{ background: 'none', border: 'none', fontSize: '1.5rem', cursor: 'pointer', color: '#94a3b8' }}>×</button>
            </div>

            {error && (
              <div style={{ padding: '12px', backgroundColor: '#fee2e2', color: '#ef4444', borderRadius: '6px', marginBottom: '16px', fontSize: '0.9rem' }}>
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Bank Institution Name *</label>
                <input type="text" name="bankName" className="form-input" required value={formData.bankName} onChange={handleChange} placeholder="e.g. Commercial Bank of Ceylon" />
              </div>

              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Account Holder / Title Name</label>
                <input type="text" name="accountName" className="form-input" value={formData.accountName} onChange={handleChange} placeholder="e.g. Main Operating Account" />
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px', marginBottom: '16px' }}>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Account Number *</label>
                  <input type="text" name="accountNumber" className="form-input" required value={formData.accountNumber} onChange={handleChange} placeholder="e.g. 1000492819" />
                </div>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Branch Name</label>
                  <input type="text" name="branch" className="form-input" value={formData.branch} onChange={handleChange} placeholder="e.g. Colombo City" />
                </div>
              </div>

              <div className="form-group" style={{ marginBottom: '24px' }}>
                <label className="form-label">Opening Balance ($)</label>
                <input type="number" step="0.01" name="openingBalance" className="form-input" value={formData.openingBalance} onChange={handleChange} />
                <span style={{ fontSize: '0.75rem', color: '#64748b', marginTop: '4px', display: 'block' }}>Initial funds deposited as of opening date.</span>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', borderTop: '1px solid #f1f5f9', paddingTop: '16px' }}>
                <button type="button" className="btn btn-outline" onClick={() => setIsModalOpen(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary" disabled={isSaving}>
                  {isSaving ? 'Connecting...' : 'Connect Bank'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
