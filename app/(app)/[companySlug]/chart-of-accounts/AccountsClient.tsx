'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
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

export default function AccountsClient({ initialAccounts, basePath }: { initialAccounts: Account[], basePath: string }) {
  const router = useRouter();
  const [accounts, setAccounts] = useState<Account[]>(initialAccounts);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({ code: '', name: '', typeId: '1', openingBalance: '0.00' });
  const [isSaving, setIsSaving] = useState(false);
  const [error, setError] = useState('');

  const accountTypes = [
    { id: 1, name: 'Assets' },
    { id: 2, name: 'Liabilities' },
    { id: 3, name: 'Equity' },
    { id: 4, name: 'Income' },
    { id: 5, name: 'Expenses' }
  ];

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
      setError('An error occurred');
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <div>
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <h1 className="page-title">Chart of Accounts</h1>
        <button className="btn btn-primary" onClick={() => setIsModalOpen(true)}>+ New Account</button>
      </div>

      <div className="card">
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Code</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Name</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Type</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', textAlign: 'right' }}>Balance</th>
            </tr>
          </thead>
          <tbody>
            {accounts.length === 0 ? (
              <tr>
                <td colSpan={4} style={{ padding: '24px', textAlign: 'center', color: '#64748b' }}>
                  No accounts found. Create one to get started.
                </td>
              </tr>
            ) : (
              accounts.map((acc) => (
                <tr key={acc.id} style={{ borderBottom: '1px solid #e2e8f0' }}>
                  <td style={{ padding: '12px 16px', fontWeight: '500' }}>{acc.code}</td>
                  <td style={{ padding: '12px 16px' }}>{acc.name}</td>
                  <td style={{ padding: '12px 16px' }}>
                    <span style={{ 
                      padding: '4px 8px', 
                      borderRadius: '4px', 
                      fontSize: '0.8rem', 
                      fontWeight: '600',
                      backgroundColor: acc.typeId === 1 ? '#dcfce7' : 
                                       acc.typeId === 2 ? '#fee2e2' : 
                                       acc.typeId === 3 ? '#f3e8ff' : 
                                       acc.typeId === 4 ? '#dbeafe' : '#fef9c3',
                      color: acc.typeId === 1 ? '#166534' : 
                             acc.typeId === 2 ? '#991b1b' : 
                             acc.typeId === 3 ? '#6b21a8' : 
                             acc.typeId === 4 ? '#1e40af' : '#854d0e'
                    }}>
                      {acc.type}
                    </span>
                  </td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: '500' }}>${Number(acc.balance).toFixed(2)}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {isModalOpen && (
        <div style={{
          position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
          backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1000,
          display: 'flex', alignItems: 'center', justifyContent: 'center'
        }}>
          <div className="card" style={{ width: '100%', maxWidth: '500px', padding: '24px', margin: '20px' }}>
            <h2 style={{ margin: '0 0 20px 0', fontSize: '1.25rem' }}>New Account</h2>
            
            {error && (
              <div style={{ padding: '10px', backgroundColor: '#fee2e2', color: '#ef4444', borderRadius: '6px', marginBottom: '16px', fontSize: '0.9rem' }}>
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="form-group">
                <label className="form-label">Account Type *</label>
                <select name="typeId" className="form-input" value={formData.typeId} onChange={handleChange} required>
                  {accountTypes.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                </select>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 2fr', gap: '16px' }}>
                <div className="form-group">
                  <label className="form-label">Code *</label>
                  <input type="text" name="code" className="form-input" required value={formData.code} onChange={handleChange} placeholder="e.g. 1000" />
                </div>
                <div className="form-group">
                  <label className="form-label">Name *</label>
                  <input type="text" name="name" className="form-input" required value={formData.name} onChange={handleChange} placeholder="e.g. Cash in Bank" />
                </div>
              </div>

              <div className="form-group">
                <label className="form-label">Opening Balance</label>
                <input type="number" step="0.01" name="openingBalance" className="form-input" value={formData.openingBalance} onChange={handleChange} />
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', marginTop: '24px' }}>
                <button type="button" className="btn btn-outline" onClick={() => setIsModalOpen(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary" disabled={isSaving}>
                  {isSaving ? 'Saving...' : 'Save Account'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}

