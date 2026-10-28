'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function ExpensesPage() {
  const [expenses, setExpenses] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  
  const [formData, setFormData] = useState({ 
    id: '', 
    category: 'Office Supplies', 
    amount: '', 
    date: new Date().toISOString().split('T')[0], 
    reference_number: '', 
    notes: '' 
  });

  const categories = [
    'Advertising & Marketing',
    'Bank Fees',
    'Contractor/Freelancer',
    'Equipment & Machinery',
    'Insurance',
    'Meals & Entertainment',
    'Office Supplies',
    'Rent & Lease',
    'Software & Subscriptions',
    'Travel',
    'Utilities',
    'Other'
  ];

  useEffect(() => {
    fetchExpenses();
  }, []);

  const fetchExpenses = async () => {
    try {
      const res = await fetch('/api/expenses');
      const data = await res.json();
      if (res.ok) {
        setExpenses(data.expenses);
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
        notes: expense.notes || '' 
      });
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '', 
        category: 'Office Supplies', 
        amount: '', 
        date: new Date().toISOString().split('T')[0], 
        reference_number: '', 
        notes: '' 
      });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveExpense = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = '/api/expenses';
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
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save expense', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this expense?')) return;
    try {
      const res = await fetch(`/api/expenses?id=${id}`, { method: 'DELETE' });
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

  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Expenses</h1>
          <p className="page-subtitle">Track your direct business expenditures.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ Record Expense</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading expenses...</div>
        ) : expenses.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No expenses recorded. Click "+ Record Expense" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Category</th>
                  <th>Reference #</th>
                  <th>Notes</th>
                  <th>Amount</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {expenses.map((e: any) => (
                  <tr key={e.id}>
                    <td>{new Date(e.date).toLocaleDateString()}</td>
                    <td><div className="font-medium text-gray-900">{e.category}</div></td>
                    <td>{e.reference_number || '-'}</td>
                    <td style={{ maxWidth: '200px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{e.notes || '-'}</td>
                    <td className="font-medium text-red-600">${Number(e.amount).toFixed(2)}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(e)} 
                        style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(e.id)} 
                        style={{ background: 'none', border: 'none', color: '#ef4444', cursor: 'pointer', fontWeight: 500 }}
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
        <div className="modal-overlay">
          <div className="modal">
            <h2 className="modal-title">{isEditing ? 'Edit Expense' : 'Record Expense'}</h2>
            <form onSubmit={handleSaveExpense}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Date</label>
                  <input type="date" className="form-control" required value={formData.date} onChange={e => setFormData({...formData, date: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Amount</label>
                  <input type="number" step="0.01" className="form-control" required value={formData.amount} onChange={e => setFormData({...formData, amount: e.target.value})} />
                </div>
              </div>
              
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Category</label>
                  <select className="form-control" required value={formData.category} onChange={e => setFormData({...formData, category: e.target.value})}>
                    {categories.map(c => (
                      <option key={c} value={c}>{c}</option>
                    ))}
                  </select>
                </div>
                <div className="form-group">
                  <label>Reference #</label>
                  <input type="text" className="form-control" value={formData.reference_number} onChange={e => setFormData({...formData, reference_number: e.target.value})} placeholder="e.g. Receipt # or Check #" />
                </div>
              </div>
              
              <div className="form-group">
                <label>Notes</label>
                <textarea className="form-control" value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})} placeholder="Description of the expense..."></textarea>
              </div>
              
              <div className="modal-actions">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Record Expense'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}