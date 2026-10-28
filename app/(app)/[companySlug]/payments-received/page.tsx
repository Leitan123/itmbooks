'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function PaymentsReceivedPage() {
  const [payments, setPayments] = useState([]);
  const [customers, setCustomers] = useState([]);
  const [invoices, setInvoices] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  
  const [formData, setFormData] = useState({ 
    id: '',
    customer_id: '', 
    invoice_id: '', 
    amount: '', 
    payment_date: new Date().toISOString().split('T')[0], 
    payment_mode: 'Bank Transfer',
    reference_number: '',
    notes: ''
  });

  const basePath = '/itmbooks.com';

  useEffect(() => {
    fetchPayments();
    fetchCustomers();
    fetchInvoices();
  }, []);

  const fetchPayments = async () => {
    try {
      const res = await fetch(`${basePath}/api/payments-received`);
      const data = await res.json();
      if (res.ok) setPayments(data.payments || []);
    } catch (error) {
      console.error('Failed to fetch payments', error);
    } finally {
      setIsLoading(false);
    }
  };

  const fetchCustomers = async () => {
    try {
      const res = await fetch(`${basePath}/api/customers`);
      const data = await res.json();
      if (res.ok) setCustomers(data.customers || []);
    } catch (error) {
      console.error('Failed to fetch customers', error);
    }
  };

  const fetchInvoices = async () => {
    try {
      const res = await fetch(`${basePath}/api/invoices`);
      const data = await res.json();
      if (res.ok) setInvoices(data.invoices || []);
    } catch (error) {
      console.error('Failed to fetch invoices', error);
    }
  };

  const handleOpenModal = (payment?: any) => {
    if (payment) {
      setFormData({ 
        id: payment.id,
        customer_id: payment.customer_id, 
        invoice_id: payment.invoice_id || '', 
        amount: payment.amount, 
        payment_date: new Date(payment.payment_date).toISOString().split('T')[0], 
        payment_mode: payment.payment_mode,
        reference_number: payment.reference_number || '',
        notes: payment.notes || ''
      });
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '',
        customer_id: '', 
        invoice_id: '', 
        amount: '', 
        payment_date: new Date().toISOString().split('T')[0], 
        payment_mode: 'Bank Transfer',
        reference_number: '',
        notes: ''
      });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSavePayment = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = `${basePath}/api/payments-received`;
      const method = isEditing ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchPayments();
      } else {
        alert('Error saving payment: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save payment', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this payment record?')) return;
    try {
      const res = await fetch(`${basePath}/api/payments-received?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchPayments();
      } else {
        const data = await res.json();
        alert('Error deleting payment: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete payment', error);
      alert('Network error occurred.');
    }
  };


  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Payments Received</h1>
          <p className="page-subtitle">Track and manage payments from your customers.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ Record Payment</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading payments...</div>
        ) : payments.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No payments recorded. Click "+ Record Payment" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Reference #</th>
                  <th>Customer</th>
                  <th>Linked Invoice</th>
                  <th>Mode</th>
                  <th>Amount</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {payments.map((payment: any) => (
                  <tr key={payment.id}>
                    <td>{new Date(payment.payment_date).toLocaleDateString()}</td>
                    <td><div className="font-medium text-gray-900">{payment.reference_number || '-'}</div></td>
                    <td>{payment.customer_name}</td>
                    <td>
                      {payment.invoice_number ? (
                        <span className="text-blue-600 font-medium">{payment.invoice_number}</span>
                      ) : (
                        <span className="text-gray-400">Unapplied</span>
                      )}
                    </td>
                    <td>{payment.payment_mode}</td>
                    <td className="font-medium text-green-600">${Number(payment.amount).toFixed(2)}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(payment)} 
                        style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(payment.id)} 
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
          <div className="modal" style={{ maxWidth: '600px' }}>
            <h2 className="modal-title">{isEditing ? 'Edit Payment' : 'Record Payment'}</h2>
            <form onSubmit={handleSavePayment}>
              <div className="form-group">
                <label>Customer</label>
                <select className="form-control" required value={formData.customer_id} onChange={e => setFormData({...formData, customer_id: e.target.value, invoice_id: ''})}>
                  <option value="">Select a customer...</option>
                  {customers.map((c: any) => (
                    <option key={c.id} value={c.id}>{c.name}</option>
                  ))}
                </select>
              </div>

              {formData.customer_id && (
                <div className="form-group">
                  <label>Apply to Invoice (Optional)</label>
                  <select className="form-control" value={formData.invoice_id} onChange={e => setFormData({...formData, invoice_id: e.target.value})}>
                    <option value="">Do not apply to an invoice</option>
                    {invoices.filter((inv: any) => inv.customer_id.toString() === formData.customer_id.toString()).map((inv: any) => (
                      <option key={inv.id} value={inv.id}>{inv.invoice_number} - ${Number(inv.total_amount).toFixed(2)} ({inv.status})</option>
                    ))}
                  </select>
                </div>
              )}
              
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Amount Received</label>
                  <input type="number" step="0.01" className="form-control" required value={formData.amount} onChange={e => setFormData({...formData, amount: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Payment Date</label>
                  <input type="date" className="form-control" required value={formData.payment_date} onChange={e => setFormData({...formData, payment_date: e.target.value})} />
                </div>
              </div>
              
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Payment Mode</label>
                  <select className="form-control" required value={formData.payment_mode} onChange={e => setFormData({...formData, payment_mode: e.target.value})}>
                    <option>Bank Transfer</option>
                    <option>Cash</option>
                    <option>Cheque</option>
                    <option>Credit Card</option>
                    <option>PayPal</option>
                  </select>
                </div>
                <div className="form-group">
                  <label>Reference #</label>
                  <input type="text" className="form-control" value={formData.reference_number} onChange={e => setFormData({...formData, reference_number: e.target.value})} placeholder="e.g. Check number" />
                </div>
              </div>

              <div className="form-group">
                <label>Notes</label>
                <textarea className="form-control" value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})}></textarea>
              </div>

              <div className="modal-actions">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Record Payment'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}