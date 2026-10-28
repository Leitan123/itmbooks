'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import '../../../(marketing)/form.css';

type Account = {
  id: number;
  code: string;
  name: string;
  type: string;
  typeId: number;
  openingBalance: string;
  balance: string;
};

export default function AccountsClient({ initialAccounts, basePath, companySlug }: { initialAccounts: Account[], basePath: string, companySlug: string }) {
  const router = useRouter();
  const [accounts, setAccounts] = useState<Account[]>(initialAccounts);
  const [selectedType, setSelectedType] = useState<number | 'all'>('all');
  const [searchTerm, setSearchTerm] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({ code: '', name: '', typeId: '1', openingBalance: '0.00' });
  const [isSaving, setIsSaving] = useState(false);
  const [error, setError] = useState('');

  const accountTypes = [
    { id: 1, name: 'Assets', badgeBg: '#ecfdf5', badgeColor: '#065f46' },
    { id: 2, name: 'Liabilities', badgeBg: '#fff7ed', badgeColor: '#9a3412' },
    { id: 3, name: 'Equity', badgeBg: '#faf5ff', badgeColor: '#6b21a8' },
    { id: 4, name: 'Income', badgeBg: '#eff6ff', badgeColor: '#1e40af' },
    { id: 5, name: 'Expenses', badgeBg: '#fdf2f8', badgeColor: '#9d174d' }
  ];

  const filteredAccounts = accounts.filter(acc => {
    const matchesType = selectedType === 'all' || acc.typeId === selectedType;
    const matchesSearch = acc.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
                          acc.code.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesType && matchesSearch;
  });

  // Calculate totals by classification
  const totalAssets = accounts.filter(a => a.typeId === 1).reduce((s, a) => s + Number(a.balance || 0), 0);
  const totalLiabilities = accounts.filter(a => a.typeId === 2).reduce((s, a) => s + Number(a.balance || 0), 0);
  const totalEquity = accounts.filter(a => a.typeId === 3).reduce((s, a) => s + Number(a.balance || 0), 0);
  const totalIncome = accounts.filter(a => a.typeId === 4).reduce((s, a) => s + Number(a.balance || 0), 0);
  const totalExpenses = accounts.filter(a => a.typeId === 5).reduce((s, a) => s + Number(a.balance || 0), 0);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    setError('');

    try {
      const res = await fetch(`${basePath}/api/accounts`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (res.ok) {
        const typeName = accountTypes.find(t => t.id.toString() === formData.typeId)?.name || '';
        setAccounts([...accounts, { 
          id: data.id, 
          code: formData.code, 
          name: formData.name, 
          type: typeName, 
          typeId: parseInt(formData.typeId), 
          openingBalance: formData.openingBalance,
          balance: formData.openingBalance
        }]);
        setIsModalOpen(false);
        setFormData({ code: '', name: '', typeId: '1', openingBalance: '0.00' });
        router.refresh();
      } else {
        setError(data.error || 'Failed to create account');
      }
    } catch (err) {
      setError('An unexpected network error occurred');
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <div className="page-container">
      {/* Header */}
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Chart of Accounts</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Manage the general ledger account hierarchy, opening balances, and classifications.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-outline">
            + New Journal
          </Link>
          <button className="btn btn-primary" onClick={() => setIsModalOpen(true)}>
            + New Account
          </button>
        </div>
      </div>

      {/* Summary KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #10b981' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Assets</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalAssets.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #f97316' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Liabilities</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalLiabilities.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #8b5cf6' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Equity</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalEquity.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #3b82f6' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Revenue</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalIncome.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #ec4899' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Expenses</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalExpenses.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
      </div>

      {/* Filter and Search Bar */}
      <div className="card" style={{ padding: '16px', marginBottom: '20px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '16px' }}>
          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
            <button 
              onClick={() => setSelectedType('all')}
              style={{
                padding: '6px 14px', borderRadius: '6px', fontSize: '0.85rem', fontWeight: 600, cursor: 'pointer', border: '1px solid #e2e8f0',
                backgroundColor: selectedType === 'all' ? '#0f172a' : '#ffffff',
                color: selectedType === 'all' ? '#ffffff' : '#475569'
              }}
            >
              All ({accounts.length})
            </button>
            {accountTypes.map(t => {
              const count = accounts.filter(a => a.typeId === t.id).length;
              const isActive = selectedType === t.id;
              return (
                <button
                  key={t.id}
                  onClick={() => setSelectedType(t.id)}
                  style={{
                    padding: '6px 14px', borderRadius: '6px', fontSize: '0.85rem', fontWeight: 600, cursor: 'pointer', border: '1px solid #e2e8f0',
                    backgroundColor: isActive ? '#0f172a' : '#ffffff',
                    color: isActive ? '#ffffff' : '#475569'
                  }}
                >
                  {t.name} ({count})
                </button>
              );
            })}
          </div>

          <div style={{ minWidth: '260px' }}>
            <input 
              type="text" 
              className="form-input" 
              placeholder="Search code or account name..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              style={{ margin: 0 }}
            />
          </div>
        </div>
      </div>

      {/* Table Card */}
      <div className="card" style={{ overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', backgroundColor: '#f8fafc', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '12%' }}>Code</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '38%' }}>Account Name</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '18%' }}>Classification</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'right', width: '18%' }}>Current Balance</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'center', width: '14%' }}>Ledger</th>
            </tr>
          </thead>
          <tbody>
            {filteredAccounts.length === 0 ? (
              <tr>
                <td colSpan={5} style={{ padding: '32px', textAlign: 'center', color: '#64748b' }}>
                  No accounts found matching your filters.
                </td>
              </tr>
            ) : (
              filteredAccounts.map((acc) => {
                const badgeInfo = accountTypes.find(t => t.id === acc.typeId) || { badgeBg: '#f1f5f9', badgeColor: '#475569' };
                const bal = Number(acc.balance || 0);
                return (
                  <tr key={acc.id} style={{ borderBottom: '1px solid #e2e8f0' }}>
                    <td style={{ padding: '14px 16px', fontWeight: '600', color: '#1e293b' }}>{acc.code}</td>
                    <td style={{ padding: '14px 16px', fontWeight: '500', color: '#0f172a' }}>{acc.name}</td>
                    <td style={{ padding: '14px 16px' }}>
                      <span style={{ 
                        padding: '4px 10px', 
                        borderRadius: '20px', 
                        fontSize: '0.75rem', 
                        fontWeight: '600',
                        backgroundColor: badgeInfo.badgeBg,
                        color: badgeInfo.badgeColor
                      }}>
                        {acc.type}
                      </span>
                    </td>
                    <td style={{ padding: '14px 16px', textAlign: 'right', fontWeight: '600', color: bal < 0 ? '#dc2626' : '#0f172a' }}>
                      ${bal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                    <td style={{ padding: '14px 16px', textAlign: 'center' }}>
                      <Link 
                        href={`/${companySlug}/general-ledger?accountId=${acc.id}`}
                        style={{
                          fontSize: '0.8rem',
                          color: '#2563eb',
                          fontWeight: 600,
                          textDecoration: 'none',
                          padding: '4px 10px',
                          borderRadius: '4px',
                          backgroundColor: '#eff6ff'
                        }}
                      >
                        View Ledger
                      </Link>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>

      {/* New Account Modal */}
      {isModalOpen && (
        <div style={{
          position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
          backgroundColor: 'rgba(15, 23, 42, 0.6)', zIndex: 1000,
          display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(3px)'
        }}>
          <div className="card" style={{ width: '100%', maxWidth: '520px', padding: '28px', margin: '20px', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h2 style={{ margin: 0, fontSize: '1.25rem', fontWeight: 700 }}>Add New Account</h2>
              <button onClick={() => setIsModalOpen(false)} style={{ background: 'none', border: 'none', fontSize: '1.5rem', cursor: 'pointer', color: '#94a3b8' }}>×</button>
            </div>
            
            {error && (
              <div style={{ padding: '12px', backgroundColor: '#fee2e2', color: '#ef4444', borderRadius: '6px', marginBottom: '16px', fontSize: '0.9rem' }}>
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Classification / Type *</label>
                <select name="typeId" className="form-input" value={formData.typeId} onChange={handleChange} required>
                  {accountTypes.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                </select>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 2fr', gap: '16px', marginBottom: '16px' }}>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Code *</label>
                  <input type="text" name="code" className="form-input" required value={formData.code} onChange={handleChange} placeholder="e.g. 1040" />
                </div>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Account Name *</label>
                  <input type="text" name="name" className="form-input" required value={formData.name} onChange={handleChange} placeholder="e.g. PayPal Clearing" />
                </div>
              </div>

              <div className="form-group" style={{ marginBottom: '24px' }}>
                <label className="form-label">Opening Balance</label>
                <input type="number" step="0.01" name="openingBalance" className="form-input" value={formData.openingBalance} onChange={handleChange} />
                <span style={{ fontSize: '0.75rem', color: '#64748b', marginTop: '4px', display: 'block' }}>Initial ledger balance at the beginning of the fiscal period.</span>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', borderTop: '1px solid #f1f5f9', paddingTop: '16px' }}>
                <button type="button" className="btn btn-outline" onClick={() => setIsModalOpen(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary" disabled={isSaving}>
                  {isSaving ? 'Saving Account...' : 'Save Account'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
