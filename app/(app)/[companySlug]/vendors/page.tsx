'use client';

import { useState, useEffect } from 'react';
import { useSession } from 'next-auth/react';
import { useParams } from 'next/navigation';
import Link from 'next/link';
import '../../table.css';

export default function VendorsPage() {
  const { data: session } = useSession();
  const params = useParams();
  const companySlug = (params?.companySlug as string) || 'dashboard';
  const rawAccountType = (session?.user as any)?.accountType || 'Invoice';
  const isInvoiceUser = rawAccountType === 'Invoice';

  const [vendors, setVendors] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({ id: '', name: '', email: '', phone: '', address: '' });

  const basePath = '/itmbooks.com';

  useEffect(() => {
    if (!isInvoiceUser) {
      fetchVendors();
    } else {
      setIsLoading(false);
    }
  }, [isInvoiceUser]);

  if (session && isInvoiceUser) {
    return (
      <div className="page-container" style={{ textAlign: 'center', padding: '60px 20px' }}>
        <div style={{ fontSize: '3rem', marginBottom: '16px' }}>🔒</div>
        <h2 style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a' }}>Purchases & Vendors Not Included</h2>
        <p style={{ color: '#64748b', maxWidth: '520px', margin: '12px auto 24px auto', lineHeight: 1.6 }}>
          Vendor management and supplier purchases are exclusively available on the Full Accounting and Inventory editions. Your workspace is currently on the Invoicing Edition.
        </p>
        <Link href={`/${companySlug}`} className="btn btn-primary">
          Back to Dashboard
        </Link>
      </div>
    );
  }

  const fetchVendors = async () => {
    try {
      const res = await fetch(`${basePath}/api/vendors`);
      const data = await res.json();
      if (res.ok) {
        setVendors(data.vendors || []);
      }
    } catch (error) {
      console.error('Failed to fetch vendors', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleOpenModal = (vendor?: any) => {
    if (vendor) {
      setFormData({ id: vendor.id, name: vendor.name, email: vendor.email || '', phone: vendor.phone || '', address: vendor.address || '' });
      setIsEditing(true);
    } else {
      setFormData({ id: '', name: '', email: '', phone: '', address: '' });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveVendor = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = `${basePath}/api/vendors`;
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchVendors();
      } else {
        alert('Error saving vendor: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save vendor', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this vendor?')) return;
    try {
      const res = await fetch(`${basePath}/api/vendors?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchVendors();
      } else {
        const data = await res.json();
        alert('Error deleting vendor: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete vendor', error);
      alert('Network error occurred.');
    }
  };

  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Vendors</h1>
          <p className="page-subtitle">Manage your suppliers and purchase contacts.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ New Vendor</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading vendors...</div>
        ) : vendors.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No vendors found. Click "+ New Vendor" to add one.
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
                {vendors.map((v: any) => (
                  <tr key={v.id}>
                    <td><div className="font-medium text-gray-900">{v.name}</div></td>
                    <td>{v.email || '-'}</td>
                    <td>{v.phone || '-'}</td>
                    <td>{v.address || '-'}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(v)} 
                        style={{ background: 'none', border: 'none', color: '#2f54eb', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(v.id)} 
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
            <h2 className="modal-title">{isEditing ? 'Edit Vendor' : 'Add New Vendor'}</h2>
            <form onSubmit={handleSaveVendor}>
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
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Save Vendor'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}