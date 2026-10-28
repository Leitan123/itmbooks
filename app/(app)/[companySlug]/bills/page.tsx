'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function BillsPage() {
  const [bills, setBills] = useState([]);
  const [vendors, setVendors] = useState([]);
  const [availableItems, setAvailableItems] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  
  const [formData, setFormData] = useState({ 
    id: '',
    vendor_id: '', 
    bill_number: `BILL-${Math.floor(1000 + Math.random() * 9000)}`, 
    date: new Date().toISOString().split('T')[0], 
    due_date: '', 
    status: 'Unpaid' 
  });

  const [billItems, setBillItems] = useState([{ item_id: '', quantity: 1, unit_price: 0 }]);

  useEffect(() => {
    fetchBills();
    fetchVendors();
    fetchAvailableItems();
  }, []);

  const fetchBills = async () => {
    try {
      const res = await fetch('/api/bills');
      const data = await res.json();
      if (res.ok) setBills(data.bills);
    } catch (error) {
      console.error('Failed to fetch bills', error);
    } finally {
      setIsLoading(false);
    }
  };

  const fetchVendors = async () => {
    try {
      const res = await fetch('/api/vendors');
      const data = await res.json();
      if (res.ok) setVendors(data.vendors);
    } catch (error) {
      console.error('Failed to fetch vendors', error);
    }
  };

  const fetchAvailableItems = async () => {
    try {
      const res = await fetch('/api/items');
      const data = await res.json();
      if (res.ok) setAvailableItems(data.items);
    } catch (error) {
      console.error('Failed to fetch items', error);
    }
  };

  const calculateTotal = () => {
    return billItems.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
  };

  const handleItemChange = (index: number, field: string, value: string | number) => {
    const newItems = [...billItems];
    if (field === 'item_id') {
      const selectedItem: any = availableItems.find((i: any) => i.id.toString() === value);
      newItems[index] = { ...newItems[index], item_id: value as string, unit_price: selectedItem ? Number(selectedItem.price) : 0 };
    } else {
      newItems[index] = { ...newItems[index], [field]: Number(value) };
    }
    setBillItems(newItems);
  };

  const addLineItem = () => {
    setBillItems([...billItems, { item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const removeLineItem = (index: number) => {
    const newItems = billItems.filter((_, i) => i !== index);
    setBillItems(newItems.length ? newItems : [{ item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const handleOpenModal = (bill?: any) => {
    if (bill) {
      setFormData({ 
        id: bill.id,
        vendor_id: bill.vendor_id, 
        bill_number: bill.bill_number, 
        date: new Date(bill.date).toISOString().split('T')[0], 
        due_date: bill.due_date ? new Date(bill.due_date).toISOString().split('T')[0] : '', 
        status: bill.status 
      });
      if (bill.items && bill.items.length > 0) {
        setBillItems(bill.items.map((i: any) => ({
          item_id: i.item_id.toString(),
          quantity: Number(i.quantity),
          unit_price: Number(i.unit_price)
        })));
      } else {
        setBillItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      }
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '',
        vendor_id: '', 
        bill_number: `BILL-${Math.floor(1000 + Math.random() * 9000)}`, 
        date: new Date().toISOString().split('T')[0], 
        due_date: '', 
        status: 'Unpaid' 
      });
      setBillItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveBill = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const payload = {
        ...formData,
        total_amount: calculateTotal(),
        items: billItems.filter(i => i.item_id !== '')
      };

      const url = '/api/bills';
      const method = isEditing ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchBills();
      } else {
        alert('Error saving bill: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save bill', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this bill?')) return;
    try {
      const res = await fetch(`/api/bills?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchBills();
      } else {
        const data = await res.json();
        alert('Error deleting bill: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete bill', error);
      alert('Network error occurred.');
    }
  };


  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Bills</h1>
          <p className="page-subtitle">Track and manage invoices received from your vendors.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ Create Bill</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading bills...</div>
        ) : bills.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No bills found. Click "+ Create Bill" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Bill #</th>
                  <th>Vendor</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {bills.map((bill: any) => (
                  <tr key={bill.id}>
                    <td><div className="font-medium text-blue-600">{bill.bill_number}</div></td>
                    <td>{bill.vendor_name}</td>
                    <td>{new Date(bill.date).toLocaleDateString()}</td>
                    <td>
                      <span style={{
                        padding: '0.25rem 0.75rem',
                        borderRadius: '9999px',
                        fontSize: '0.75rem',
                        fontWeight: 500,
                        backgroundColor: bill.status === 'Paid' ? '#d1fae5' : '#fee2e2',
                        color: bill.status === 'Paid' ? '#065f46' : '#991b1b'
                      }}>
                        {bill.status}
                      </span>
                    </td>
                    <td className="font-medium">${Number(bill.total_amount).toFixed(2)}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(bill)} 
                        style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(bill.id)} 
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
            <h2 className="modal-title">{isEditing ? 'Edit Bill' : 'Create Bill'}</h2>
            <form onSubmit={handleSaveBill}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Vendor</label>
                  <select className="form-control" required value={formData.vendor_id} onChange={e => setFormData({...formData, vendor_id: e.target.value})}>
                    <option value="">Select a vendor...</option>
                    {vendors.map((v: any) => (
                      <option key={v.id} value={v.id}>{v.name}</option>
                    ))}
                  </select>
                </div>
                <div className="form-group">
                  <label>Bill Number</label>
                  <input type="text" className="form-control" required value={formData.bill_number} onChange={e => setFormData({...formData, bill_number: e.target.value})} />
                </div>
              </div>
              
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '1rem' }}>
                <div className="form-group">
                  <label>Bill Date</label>
                  <input type="date" className="form-control" required value={formData.date} onChange={e => setFormData({...formData, date: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Due Date</label>
                  <input type="date" className="form-control" value={formData.due_date} onChange={e => setFormData({...formData, due_date: e.target.value})} />
                </div>
                <div className="form-group">
                  <label>Status</label>
                  <select className="form-control" value={formData.status} onChange={e => setFormData({...formData, status: e.target.value})}>
                    <option>Unpaid</option>
                    <option>Paid</option>
                  </select>
                </div>
              </div>

              <div style={{ marginTop: '1.5rem', marginBottom: '1.5rem', padding: '1rem', backgroundColor: '#f9fafb', borderRadius: '0.5rem', border: '1px solid #e5e7eb' }}>
                <h3 style={{ fontSize: '1rem', fontWeight: 600, marginBottom: '1rem' }}>Line Items</h3>
                {billItems.map((item, index) => (
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
                      <label style={{ fontSize: '0.75rem', color: '#6b7280', display: 'block', marginBottom: '0.25rem' }}>Unit Price</label>
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
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Create Bill'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}