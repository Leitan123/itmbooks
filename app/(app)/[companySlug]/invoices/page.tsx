'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function InvoicesPage() {
  const [invoices, setInvoices] = useState([]);
  const [customers, setCustomers] = useState([]);
  const [availableItems, setAvailableItems] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  
  const [formData, setFormData] = useState({ 
    id: '',
    customer_id: '', 
    invoice_number: `INV-${Math.floor(1000 + Math.random() * 9000)}`, 
    date: new Date().toISOString().split('T')[0], 
    due_date: '', 
    status: 'Draft' 
  });

  const [invoiceItems, setInvoiceItems] = useState([{ item_id: '', quantity: 1, unit_price: 0 }]);

  const basePath = '/itmbooks.com';

  useEffect(() => {
    fetchInvoices();
    fetchCustomers();
    fetchAvailableItems();
  }, []);

  const fetchInvoices = async () => {
    try {
      const res = await fetch(`${basePath}/api/invoices`);
      const data = await res.json();
      if (res.ok) setInvoices(data.invoices || []);
    } catch (error) {
      console.error('Failed to fetch invoices', error);
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

  const fetchAvailableItems = async () => {
    try {
      const res = await fetch(`${basePath}/api/items`);
      const data = await res.json();
      if (res.ok) setAvailableItems(data.items || []);
    } catch (error) {
      console.error('Failed to fetch items', error);
    }
  };

  const calculateTotal = () => {
    return invoiceItems.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
  };

  const handleItemChange = (index: number, field: string, value: string | number) => {
    const newItems = [...invoiceItems];
    if (field === 'item_id') {
      const selectedItem: any = availableItems.find((i: any) => i.id.toString() === value);
      newItems[index] = { ...newItems[index], item_id: value as string, unit_price: selectedItem ? Number(selectedItem.price) : 0 };
    } else {
      newItems[index] = { ...newItems[index], [field]: Number(value) };
    }
    setInvoiceItems(newItems);
  };

  const addLineItem = () => {
    setInvoiceItems([...invoiceItems, { item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const removeLineItem = (index: number) => {
    const newItems = invoiceItems.filter((_, i) => i !== index);
    setInvoiceItems(newItems.length ? newItems : [{ item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const handleOpenModal = (invoice?: any) => {
    if (invoice) {
      setFormData({ 
        id: invoice.id,
        customer_id: invoice.customer_id, 
        invoice_number: invoice.invoice_number, 
        date: new Date(invoice.date).toISOString().split('T')[0], 
        due_date: new Date(invoice.due_date).toISOString().split('T')[0], 
        status: invoice.status 
      });
      if (invoice.items && invoice.items.length > 0) {
        setInvoiceItems(invoice.items.map((i: any) => ({
          item_id: i.item_id.toString(),
          quantity: Number(i.quantity),
          unit_price: Number(i.unit_price)
        })));
      } else {
        setInvoiceItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      }
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '',
        customer_id: '', 
        invoice_number: `INV-${Math.floor(1000 + Math.random() * 9000)}`, 
        date: new Date().toISOString().split('T')[0], 
        due_date: '', 
        status: 'Draft' 
      });
      setInvoiceItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveInvoice = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const payload = {
        ...formData,
        total_amount: calculateTotal(),
        items: invoiceItems.filter(i => i.item_id !== '')
      };

      const url = `${basePath}/api/invoices`;
      const method = isEditing ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchInvoices();
      } else {
        alert('Error saving invoice: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save invoice', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this invoice?')) return;
    try {
      const res = await fetch(`${basePath}/api/invoices?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchInvoices();
      } else {
        const data = await res.json();
        alert('Error deleting invoice: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete invoice', error);
      alert('Network error occurred.');
    }
  };


  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Invoices</h1>
          <p className="page-subtitle">Create and manage your customer invoices.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ Create Invoice</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading invoices...</div>
        ) : invoices.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No invoices found. Click "+ Create Invoice" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Invoice #</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {invoices.map((inv: any) => (
                  <tr key={inv.id}>
                    <td><div className="font-medium text-blue-600">{inv.invoice_number}</div></td>
                    <td>{inv.customer_name}</td>
                    <td>{new Date(inv.date).toLocaleDateString()}</td>
                    <td>
                      <span style={{
                        padding: '0.25rem 0.75rem',
                        borderRadius: '9999px',
                        fontSize: '0.75rem',
                        fontWeight: 500,
                        backgroundColor: inv.status === 'Paid' ? '#d1fae5' : inv.status === 'Sent' ? '#dbeafe' : '#f3f4f6',
                        color: inv.status === 'Paid' ? '#065f46' : inv.status === 'Sent' ? '#1e40af' : '#374151'
                      }}>
                        {inv.status}
                      </span>
                    </td>
                    <td className="font-medium">${Number(inv.total_amount).toFixed(2)}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(inv)} 
                        style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(inv.id)} 
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
          <div className="modal" style={{ maxWidth: '800px' }}>
            <h2 className="modal-title">{isEditing ? 'Edit Invoice' : 'Create Invoice'}</h2>
            <form onSubmit={handleSaveInvoice}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Customer</label>
                  <select className="form-control" required value={formData.customer_id} onChange={e => setFormData({...formData, customer_id: e.target.value})}>
                    <option value="">Select a customer...</option>
                    {customers.map((c: any) => (
                      <option key={c.id} value={c.id}>{c.name}</option>
                    ))}
                  </select>
                </div>
                <div className="form-group">
                  <label>Invoice Number</label>
                  <input type="text" className="form-control" required value={formData.invoice_number} onChange={e => setFormData({...formData, invoice_number: e.target.value})} />
                </div>
              </div>
              
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Invoice Date</label>
                  <input type="date" className="form-control" required value={formData.date} onChange={e => setFormData({...formData, date: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Due Date</label>
                  <input type="date" className="form-control" required value={formData.due_date} onChange={e => setFormData({...formData, due_date: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Status</label>
                  <select className="form-control" value={formData.status} onChange={e => setFormData({...formData, status: e.target.value})}>
                    <option>Draft</option>
                    <option>Sent</option>
                    <option>Paid</option>
                  </select>
                </div>
              </div>

              <div style={{ marginTop: '1.5rem', marginBottom: '1.5rem', padding: '1rem', backgroundColor: '#f9fafb', borderRadius: '0.5rem', border: '1px solid #e5e7eb' }}>
                <h3 style={{ fontSize: '1rem', fontWeight: 600, marginBottom: '1rem' }}>Line Items</h3>
                {invoiceItems.map((item, index) => (
                  <div key={index} style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start', marginBottom: '1rem' }}>
                    <div style={{ flex: 2 }}>
                      <label style={{ fontSize: '0.75rem', color: '#6b7280', display: 'block', marginBottom: '0.25rem' }}>Item</label>
                      <select className="form-control" required value={item.item_id} onChange={e => handleItemChange(index, 'item_id', e.target.value)}>
                        <option value="">Select item...</option>
                        {availableItems.map((i: any) => (
                          <option key={i.id} value={i.id}>{i.name}</option>
                        ))}
                      </select>
                    </div>
                    <div style={{ flex: 1 }}>
                      <label style={{ fontSize: '0.75rem', color: '#6b7280', display: 'block', marginBottom: '0.25rem' }}>Qty</label>
                      <input type="number" min="1" className="form-control" required value={item.quantity} onChange={e => handleItemChange(index, 'quantity', e.target.value)} />
                    </div>
                    <div style={{ flex: 1 }}>
                      <label style={{ fontSize: '0.75rem', color: '#6b7280', display: 'block', marginBottom: '0.25rem' }}>Price</label>
                      <input type="number" step="0.01" className="form-control" required value={item.unit_price} onChange={e => handleItemChange(index, 'unit_price', e.target.value)} />
                    </div>
                    <div style={{ flex: 1 }}>
                      <label style={{ fontSize: '0.75rem', color: '#6b7280', display: 'block', marginBottom: '0.25rem' }}>Total</label>
                      <div style={{ padding: '0.625rem 0', fontWeight: 600 }}>
                        ${(item.quantity * item.unit_price).toFixed(2)}
                      </div>
                    </div>
                    <div style={{ padding: '1.25rem 0 0 0' }}>
                      <button type="button" onClick={() => removeLineItem(index)} style={{ background: 'none', border: 'none', color: '#ef4444', cursor: 'pointer', fontSize: '1.25rem' }}>×</button>
                    </div>
                  </div>
                ))}
                <button type="button" onClick={addLineItem} style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', fontWeight: 500, fontSize: '0.875rem' }}>
                  + Add another line
                </button>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: '1.5rem', fontSize: '1.25rem', fontWeight: 700 }}>
                Total Amount: ${calculateTotal().toFixed(2)}
              </div>

              <div className="modal-actions">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Create Invoice'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
