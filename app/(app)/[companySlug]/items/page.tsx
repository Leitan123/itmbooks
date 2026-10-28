'use client';

import { useState, useEffect } from 'react';
import '../../table.css';

export default function ItemsPage() {
  const [items, setItems] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [formData, setFormData] = useState({ id: '', name: '', description: '', price: '', stock_quantity: '' });

  useEffect(() => {
    fetchItems();
  }, []);

  const fetchItems = async () => {
    try {
      const res = await fetch('/api/items');
      const data = await res.json();
      if (res.ok) {
        setItems(data.items);
      }
    } catch (error) {
      console.error('Failed to fetch items', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleOpenModal = (item?: any) => {
    if (item) {
      setFormData({ 
        id: item.id, 
        name: item.name, 
        description: item.description || '', 
        price: item.price, 
        stock_quantity: item.stock_quantity 
      });
      setIsEditing(true);
    } else {
      setFormData({ id: '', name: '', description: '', price: '', stock_quantity: '' });
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleSaveItem = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const url = '/api/items';
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (res.ok) {
        setShowModal(false);
        fetchItems();
      } else {
        alert('Error saving item: ' + (data.message || 'Unknown error'));
        if (res.status === 401) {
          alert('Your session is missing the tenant ID. Please log out and log back in.');
        }
      }
    } catch (error) {
      console.error('Failed to save item', error);
      alert('Network error occurred.');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Are you sure you want to delete this item?')) return;
    try {
      const res = await fetch(`/api/items?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchItems();
      } else {
        const data = await res.json();
        alert('Error deleting item: ' + (data.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Failed to delete item', error);
      alert('Network error occurred.');
    }
  };

  return (
    <div className="page-container">
      <div className="page-header">
        <div>
          <h1 className="page-title">Items & Services</h1>
          <p className="page-subtitle">Manage your inventory, products, and services.</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>+ New Item</button>
      </div>

      <div className="card">
        {isLoading ? (
          <div style={{ padding: '2rem', textAlign: 'center' }}>Loading items...</div>
        ) : items.length === 0 ? (
          <div style={{ padding: '3rem', textAlign: 'center', color: '#6b7280' }}>
            No items found. Click "+ New Item" to add one.
          </div>
        ) : (
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>Item Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {items.map((item: any) => (
                  <tr key={item.id}>
                    <td><div className="font-medium text-gray-900">{item.name}</div></td>
                    <td>{item.description || '-'}</td>
                    <td>${Number(item.price).toFixed(2)}</td>
                    <td>{item.stock_quantity}</td>
                    <td style={{ textAlign: 'right' }}>
                      <button 
                        onClick={() => handleOpenModal(item)} 
                        style={{ background: 'none', border: 'none', color: '#2f54eb', cursor: 'pointer', marginRight: '1rem', fontWeight: 500 }}
                      >
                        Edit
                      </button>
                      <button 
                        onClick={() => handleDelete(item.id)} 
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
            <h2 className="modal-title">{isEditing ? 'Edit Item' : 'Add New Item'}</h2>
            <form onSubmit={handleSaveItem}>
              <div className="form-group">
                <label>Item Name</label>
                <input type="text" className="form-control" required value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} />
              </div>
              <div className="form-group">
                <label>Description</label>
                <textarea className="form-control" value={formData.description} onChange={e => setFormData({...formData, description: e.target.value})}></textarea>
              </div>
              <div className="form-group">
                <label>Price</label>
                <input type="number" step="0.01" className="form-control" required value={formData.price} onChange={e => setFormData({...formData, price: e.target.value})} />
              </div>
              <div className="form-group">
                <label>Stock Quantity</label>
                <input type="number" className="form-control" value={formData.stock_quantity} onChange={e => setFormData({...formData, stock_quantity: e.target.value})} />
              </div>
              <div className="modal-actions">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Save Item'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
