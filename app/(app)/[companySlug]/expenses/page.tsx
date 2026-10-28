'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useParams } from 'next/navigation';
import '../../table.css';
import '../../../(marketing)/form.css';

interface AccountOption {
  id: number;
  code: string;
  name: string;
  typeId: number;
}

export default function ExpensesPage() {
  const params = useParams();
  const companySlug = (params?.companySlug as string) || 'dashboard';
  const basePath = '/itmbooks.com';

  const [expenses, setExpenses] = useState([]);
  const [expenseAccounts, setExpenseAccounts] = useState<AccountOption[]>([]);
  const [paymentAccounts, setPaymentAccounts] = useState<AccountOption[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  
  const [formData, setFormData] = useState({ 
    id: '', 
    category: 'Office Supplies & Stationery', 
    amount: '', 
    date: new Date().toISOString().split('T')[0], 
    reference_number: '', 
    notes: '',
    account_id: '',
    paid_through_account_id: ''
  });

  useEffect(() => {
    fetchExpenses();
    fetchAccounts();
  }, []);

  const fetchAccounts = async () => {
    try {
      const res = await fetch(`${basePath}/api/accounts`);
      if (res.ok) {
        const data = await res.json();
        // Type 5 = Expenses
        const exp = data.filter((a: any) => a.typeId === 5);
        setExpenseAccounts(exp);

        // Type 1 = Assets (Cash, Bank, Petty Cash)
        const pay = data.filter((a: any) => a.typeId === 1 && (a.name.toLowerCase().includes('cash') || a.name.toLowerCase().includes('bank') || a.code.startsWith('10')));
        setPaymentAccounts(pay);

        if (exp.length > 0 && !formData.account_id) {
          setFormData(prev => ({ ...prev, account_id: exp[0].id.toString() }));
        }
        if (pay.length > 0 && !formData.paid_through_account_id) {
          setFormData(prev => ({ ...prev, paid_through_account_id: pay[0].id.toString() }));
        }
      }
    } catch (err) {
      console.error('Failed to load accounts for expenses:', err);
    }
  };

  const fetchExpenses = async () => {
    try {
      const res = await fetch(`${basePath}/api/expenses`);
      const data = await res.json();
      if (res.ok) {
        setExpenses(data.expenses || []);
      }
    } catch (error) {
      console.error('Failed to fetch expenses', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleOpenModal = (expense?: any) => {
    if (expense) {
      setFormData({ 
        id: expense.id, 
        category: expense.category, 
        amount: expense.amount, 
        date: new Date(expense.date).toISOString().split('T')[0], 
        reference_number: expense.reference_number || '', 
        notes: expense.notes || '',
        account_id: formData.account_id,
        paid_through_account_id: formData.paid_through_account_id
      });
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '', 
        category: expenseAccounts[0]?.name || 'Office Supplies & Stationery', 
        amount: '', 
        date: new Date().toISOString().split('T')[0], 
        reference_number: `EXP-${Date.now().toString().slice(-4)}`, 
        notes: '',
        account_id: expenseAccounts[0]?.id.toString() || '',
        paid_through_account_id: paymentAccounts[0]?.id.toString() || ''
      });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveExpense = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = `${basePath}/api/expenses`;
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchExpenses();
      } else {
        alert('Error saving expense: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to save expense', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this expense?')) return;
    try {
      const res = await fetch(`${basePath}/api/expenses?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchExpenses();
      } else {
        const data = await res.json();
        alert('Error deleting expense: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete expense', error);
      alert('Network error occurred.');
    }
  };

  const totalExpenseSum = expenses.reduce((s: number, e: any) => s + Number(e.amount || 0), 0);

  return (
    <div className="page-container">
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Expenses</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Record operational disbursements with automatic double-entry ledger posting.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <Link href={`/${companySlug}/manual-journals`} className="btn btn-outline">
            View All Journals
          </Link>
          <button className="btn btn-primary" onClick={() => handleOpenModal()}>
            + Record Expense
          </button>
        </div>
      </div>

      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #ef4444' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Expenditures</div>
          <div style={{ fontSize: '1.4rem', fontWeight: 700, color: '#991b1b', marginTop: '4px' }}>
            ${totalExpenseSum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #2563eb' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Transactions</div>
          <div style={{ fontSize: '1.4rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            {expenses.length} recorded
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #10b981' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Ledger Sync</div>
          <div style={{ fontSize: '1.4rem', fontWeight: 700, color: '#166534', marginTop: '4px' }}>
            Auto-Posted
          </div>
        </div>
      </div>

      <div className="card" style={{ overflow: 'hidden' }}>
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading expenses...</div>
        ) : expenses.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No expenses recorded. Click "+ Record Expense" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table" style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ backgroundColor: '#f8fafc', borderBottom: '2px solid #e2e8f0', textAlign: 'left' }}>
                  <th style={{ padding: '12px 16px' }}>Date</th>
                  <th style={{ padding: '12px 16px' }}>Category / Account</th>
                  <th style={{ padding: '12px 16px' }}>Reference #</th>
                  <th style={{ padding: '12px 16px' }}>Notes</th>
                  <th style={{ padding: '12px 16px', textAlign: 'right' }}>Amount</th>
                  <th style={{ padding: '12px 16px', textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {expenses.map((e: any) => (
                  <tr key={e.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td style={{ padding: '12px 16px', color: '#0f172a' }}>{new Date(e.date).toLocaleDateString()}</td>
                    <td style={{ padding: '12px 16px' }}>
                      <div style={{ fontWeight: 600, color: '#0f172a' }}>{e.category}</div>
                    </td>
                    <td style={{ padding: '12px 16px', fontWeight: 500, color: '#2563eb' }}>{e.reference_number || '-'}</td>
                    <td style={{ padding: '12px 16px', color: '#475569', maxWidth: '220px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                      {e.notes || '-'}
                    </td>
                    <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: 700, color: '#dc2626' }}>
                      ${Number(e.amount).toFixed(2)}
                    </td>
                    <td style={{ padding: '12px 16px', textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(e)} 
                        style={{ background: 'none', border: 'none', color: '#2563eb', cursor: 'pointer', marginRight: '1rem', fontWeight: 600 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(e.id)} 
                        style={{ background: 'none', border: 'none', color: '#ef4444', cursor: 'pointer', fontWeight: 600 }}
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {showModal && (
        <div style={{
          position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
          backgroundColor: 'rgba(15, 23, 42, 0.6)', zIndex: 1000,
          display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(3px)'
        }}>
          <div className="card" style={{ width: '100%', maxWidth: '540px', padding: '28px', margin: '20px', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h2 style={{ margin: 0, fontSize: '1.25rem', fontWeight: 700 }}>
                {isEditing ? 'Edit Expense' : 'Record Business Expense'}
              </h2>
              <button onClick={() => setShowModal(false)} style={{ background: 'none', border: 'none', fontSize: '1.5rem', cursor: 'pointer', color: '#94a3b8' }}>×</button>
            </div>

            <form onSubmit={handleSaveExpense}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px', marginBottom: '16px' }}>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Expense Date *</label>
                  <input type="date" className="form-input" required value={formData.date} onChange={e => setFormData({...formData, date: e.target.value})} />
                </div>
                <div className="form-group" style={{ margin: 0 }}>
                  <label className="form-label">Amount ($) *</label>
                  <input type="number" step="0.01" min="0.01" className="form-input" required value={formData.amount} onChange={e => setFormData({...formData, amount: e.target.value})} placeholder="0.00" />
                </div>
              </div>
              
              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Expense Account (Debit) *</label>
                {expenseAccounts.length > 0 ? (
                  <select 
                    className="form-input" 
                    required 
                    value={formData.account_id} 
                    onChange={e => {
                      const sel = expenseAccounts.find(a => a.id.toString() === e.target.value);
                      setFormData({
                        ...formData, 
                        account_id: e.target.value,
                        category: sel ? sel.name : formData.category
                      });
                    }}
                  >
                    {expenseAccounts.map(a => (
                      <option key={a.id} value={a.id}>{a.code} - {a.name}</option>
                    ))}
                  </select>
                ) : (
                  <input type="text" className="form-input" required value={formData.category} onChange={e => setFormData({...formData, category: e.target.value})} />
                )}
              </div>

              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Paid Via Account (Credit) *</label>
                <select 
                  className="form-input" 
                  required 
                  value={formData.paid_through_account_id} 
                  onChange={e => setFormData({...formData, paid_through_account_id: e.target.value})}
                >
                  {paymentAccounts.map(a => (
                    <option key={a.id} value={a.id}>{a.code} - {a.name}</option>
                  ))}
                </select>
                <span style={{ fontSize: '0.75rem', color: '#64748b', marginTop: '4px', display: 'block' }}>
                  Auto-posts double-entry: Debit [Expense Account], Credit [Paid Via Account].
                </span>
              </div>

              <div className="form-group" style={{ marginBottom: '16px' }}>
                <label className="form-label">Reference / Bill / Receipt #</label>
                <input type="text" className="form-input" value={formData.reference_number} onChange={e => setFormData({...formData, reference_number: e.target.value})} placeholder="e.g. Receipt # or Check #" />
              </div>
              
              <div className="form-group" style={{ marginBottom: '24px' }}>
                <label className="form-label">Description / Notes</label>
                <textarea className="form-input" rows={2} value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})} placeholder="Purpose of this disbursement..."></textarea>
              </div>
              
              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', borderTop: '1px solid #f1f5f9', paddingTop: '16px' }}>
                <button type="button" className="btn btn-outline" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Record & Post Entry'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}