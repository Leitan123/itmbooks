'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function CustomersPage() {
  const [customers, setCustomers] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({ id: '', name: '', email: '', phone: '', address: '' });

  useEffect(() => {
    fetchCustomers();
  }, []);

  const fetchCustomers = async () => {
    try {
      const res = await fetch('/api/customers');
      const data = await res.json();
      if (res.ok) {
        setCustomers(data.customers);
      }
    } catch (error) {
      console.error('Failed to fetch customers', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleOpenModal = (customer?: any) => {
    if (customer) {
      setFormData({ id: customer.id, name: customer.name, email: customer.email || '', phone: customer.phone || '', address: customer.address || '' });
      setIsEditing(true);
    } else {
      setFormData({ id: '', name: '', email: '', phone: '', address: '' });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveCustomer = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = '/api/customers';
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchCustomers();
      } else {
        alert('Error saving customer: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save customer', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this customer?')) return;
    try {
      const res = await fetch(`/api/customers?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchCustomers();
      } else {
        const data = await res.json();
        alert('Error deleting customer: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete customer', error);
      alert('Network error occurred.');
    }
  };

  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Customers</h1>
          <p className="page-subtitle">Manage your clients and their contact details.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ New Customer</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading customers...</div>
        ) : customers.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No customers found. Click "+ New Customer" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {customers.map((c: any) => (
                  <tr key={c.id}>
                    <td><div className="font-medium text-gray-900">{c.name}</div></td>
                    <td>{c.email || '-'}</td>
                    <td>{c.phone || '-'}</td>
                    <td>{c.address || '-'}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(c)} 
                        style={{ background: 'none', border: 'none', color: 'var(--primary-color)', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(c.id)} 
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
            <h2 className="modal-title">{isEditing ? 'Edit Customer' : 'Add New Customer'}</h2>
            <form onSubmit={handleSaveCustomer}>
              <div className="form-group">
                <label>Name</label>
                <input type="text" className="form-control" required value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} />
              </div>
              <div className="form-group">
                <label>Email</label>
                <input type="email" className="form-control" value={formData.email} onChange={e => setFormData({...formData, email: e.target.value})} />
              </div>
              <div className="form-group">
                <label>Phone</label>
                <input type="tel" className="form-control" value={formData.phone} onChange={e => setFormData({...formData, phone: e.target.value})} />
              </div>
              <div className="form-group">
                <label>Address</label>
                <textarea className="form-control" value={formData.address} onChange={e => setFormData({...formData, address: e.target.value})}></textarea>
              </div>
              <div className="modal-actions">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Save Customer'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}