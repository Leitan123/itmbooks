'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import '../../../../(marketing)/form.css';

type Account = {
  id: number;
  code: string;
  name: string;
  type: string;
};

export default function NewJournalClient({ accounts, basePath, companySlug }: { accounts: Account[], basePath: string, companySlug: string }) {
  const router = useRouter();
  const [formData, setFormData] = useState({ date: new Date().toISOString().split('T')[0], reference: '', notes: '' });
  const [lines, setLines] = useState([{ id: 1, account_id: '', description: '', debit: '', credit: '' }, { id: 2, account_id: '', description: '', debit: '', credit: '' }]);
  const [isSaving, setIsSaving] = useState(false);
  const [error, setError] = useState('');

  const totalDebit = lines.reduce((sum, line) => sum + (Number(line.debit) || 0), 0);
  const totalCredit = lines.reduce((sum, line) => sum + (Number(line.credit) || 0), 0);
  const isBalanced = Math.abs(totalDebit - totalCredit) < 0.001 && totalDebit > 0;

  const handleFieldChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleLineChange = (id: number, field: string, value: string) => {
    setLines(lines.map(line => {
      if (line.id === id) {
        const newLine = { ...line, [field]: value };
        if (field === 'debit' && value) newLine.credit = '';
        if (field === 'credit' && value) newLine.debit = '';
        return newLine;
      }
      return line;
    }));
  };

  const addLine = () => {
    setLines([...lines, { id: Date.now(), account_id: '', description: '', debit: '', credit: '' }]);
  };

  const removeLine = (id: number) => {
    if (lines.length > 2) {
      setLines(lines.filter(line => line.id !== id));
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    if (!isBalanced) {
      setError('Debits and Credits must be equal and greater than 0.');
      return;
    }

    if (lines.some(l => !l.account_id)) {
      setError('Please select an account for all lines.');
      return;
    }

    setIsSaving(true);
    try {
      const res = await fetch(`${basePath}/api/journals`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...formData, lines: lines.map(l => ({ ...l, debit: Number(l.debit)||0, credit: Number(l.credit)||0 })) }),
      });

      const data = await res.json();
      if (res.ok) {
        router.push(`/${companySlug}/manual-journals`);
        router.refresh();
      } else {
        setError(data.error || 'Failed to create journal');
      }
    } catch (err) {
      setError('An error occurred');
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <div className="card" style={{ maxWidth: '1000px', margin: '0 auto' }}>
      <div className="page-header">
        <h1 className="page-title">New Manual Journal</h1>
      </div>

      {error && (
        <div style={{ padding: '12px', backgroundColor: '#fee2e2', color: '#ef4444', borderRadius: '6px', marginBottom: '20px' }}>
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '20px', marginBottom: '24px' }}>
          <div className="form-group">
            <label className="form-label">Date *</label>
            <input type="date" name="date" className="form-input" required value={formData.date} onChange={handleFieldChange} />
          </div>
          <div className="form-group">
            <label className="form-label">Reference Number</label>
            <input type="text" name="reference" className="form-input" value={formData.reference} onChange={handleFieldChange} placeholder="e.g. ADJ-001" />
          </div>
        </div>
        
        <div className="form-group" style={{ marginBottom: '30px' }}>
          <label className="form-label">Notes</label>
          <textarea name="notes" className="form-input" rows={2} value={formData.notes} onChange={handleFieldChange} placeholder="Reason for this journal entry..."></textarea>
        </div>

        <table style={{ width: '100%', borderCollapse: 'collapse', marginBottom: '16px' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', textAlign: 'left' }}>
              <th style={{ padding: '12px 8px', fontWeight: '600', color: '#475569', width: '35%' }}>Account</th>
              <th style={{ padding: '12px 8px', fontWeight: '600', color: '#475569', width: '30%' }}>Description</th>
              <th style={{ padding: '12px 8px', fontWeight: '600', color: '#475569', width: '15%' }}>Debits</th>
              <th style={{ padding: '12px 8px', fontWeight: '600', color: '#475569', width: '15%' }}>Credits</th>
              <th style={{ padding: '12px 8px', width: '5%' }}></th>
            </tr>
          </thead>
          <tbody>
            {lines.map((line, index) => (
              <tr key={line.id} style={{ borderBottom: '1px solid #e2e8f0' }}>
                <td style={{ padding: '12px 8px' }}>
                  <select className="form-input" value={line.account_id} onChange={(e) => handleLineChange(line.id, 'account_id', e.target.value)} required>
                    <option value="">Select Account</option>
                    {accounts.map(acc => (
                      <option key={acc.id} value={acc.id}>{acc.code} - {acc.name}</option>
                    ))}
                  </select>
                </td>
                <td style={{ padding: '12px 8px' }}>
                  <input type="text" className="form-input" value={line.description} onChange={(e) => handleLineChange(line.id, 'description', e.target.value)} placeholder="Line description" />
                </td>
                <td style={{ padding: '12px 8px' }}>
                  <input type="number" step="0.01" min="0" className="form-input" value={line.debit} onChange={(e) => handleLineChange(line.id, 'debit', e.target.value)} placeholder="0.00" />
                </td>
                <td style={{ padding: '12px 8px' }}>
                  <input type="number" step="0.01" min="0" className="form-input" value={line.credit} onChange={(e) => handleLineChange(line.id, 'credit', e.target.value)} placeholder="0.00" />
                </td>
                <td style={{ padding: '12px 8px', textAlign: 'center' }}>
                  <button type="button" onClick={() => removeLine(line.id)} disabled={lines.length <= 2} style={{ color: '#ef4444', background: 'none', border: 'none', cursor: lines.length <= 2 ? 'not-allowed' : 'pointer', fontSize: '1.2rem', opacity: lines.length <= 2 ? 0.3 : 1 }}>×</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '30px' }}>
          <button type="button" onClick={addLine} style={{ color: 'var(--primary-color)', background: 'none', border: 'none', fontWeight: '600', cursor: 'pointer' }}>+ Add Line</button>
          
          <div style={{ display: 'flex', gap: '40px', paddingRight: '40px' }}>
            <div style={{ textAlign: 'right' }}>
              <div style={{ color: '#64748b', fontSize: '0.85rem', fontWeight: '600', textTransform: 'uppercase' }}>Total Debits</div>
              <div style={{ fontSize: '1.5rem', fontWeight: '700', color: isBalanced ? '#16a34a' : '#0f172a' }}>${totalDebit.toFixed(2)}</div>
            </div>
            <div style={{ textAlign: 'right' }}>
              <div style={{ color: '#64748b', fontSize: '0.85rem', fontWeight: '600', textTransform: 'uppercase' }}>Total Credits</div>
              <div style={{ fontSize: '1.5rem', fontWeight: '700', color: isBalanced ? '#16a34a' : '#0f172a' }}>${totalCredit.toFixed(2)}</div>
            </div>
          </div>
        </div>

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', borderTop: '1px solid #e2e8f0', paddingTop: '20px' }}>
          <button type="button" className="btn btn-outline" onClick={() => router.push(`/${companySlug}/manual-journals`)}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={!isBalanced || isSaving}>
            {isSaving ? 'Saving...' : 'Post Journal'}
          </button>
        </div>
      </form>
    </div>
  );
}
