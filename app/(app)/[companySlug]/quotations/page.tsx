'use client';

import { useState, useEffect } from 'react';
import '../../table.css';
import { useCurrency } from '../../../../components/CurrencyProvider';

export default function QuotationsPage() {
  const { format: formatMoney } = useCurrency();
  const [quotations, setQuotations] = useState<any[]>([]);
  const [customers, setCustomers] = useState<any[]>([]);
  const [availableItems, setAvailableItems] = useState<any[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('ALL');

  const [formData, setFormData] = useState({ 
    id: '',
    customer_id: '', 
    quotation_number: `QT-${Math.floor(1000 + Math.random() * 9000)}`, 
    date: new Date().toISOString().split('T')[0], 
    expiry_date: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], 
    status: 'Pending',
    notes: ''
  });

  const [quotationItems, setQuotationItems] = useState([{ item_id: '', quantity: 1, unit_price: 0 }]);

  const basePath = '/itmbooks.com';

  useEffect(() => {
    fetchQuotations();
    fetchCustomers();
    fetchAvailableItems();
  }, []);

  const fetchQuotations = async () => {
    try {
      const res = await fetch(`${basePath}/api/quotations`);
      const data = await res.json();
      if (res.ok) setQuotations(data.quotations || []);
    } catch (error) {
      console.error('Failed to fetch quotations', error);
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
    return quotationItems.reduce((sum, item) => sum + (Number(item.quantity) * Number(item.unit_price)), 0);
  };

  const handleItemChange = (index: number, field: string, value: string | number) => {
    const newItems = [...quotationItems];
    if (field === 'item_id') {
      const selectedItem: any = availableItems.find((i: any) => i.id.toString() === value.toString());
      newItems[index] = { 
        ...newItems[index], 
        item_id: value as string, 
        unit_price: selectedItem ? Number(selectedItem.price) : 0 
      };
    } else {
      newItems[index] = { ...newItems[index], [field]: Number(value) };
    }
    setQuotationItems(newItems);
  };

  const addLineItem = () => {
    setQuotationItems([...quotationItems, { item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const removeLineItem = (index: number) => {
    const newItems = quotationItems.filter((_, i) => i !== index);
    setQuotationItems(newItems.length ? newItems : [{ item_id: '', quantity: 1, unit_price: 0 }]);
  };

  const handleOpenModal = (quotation?: any) => {
    if (quotation) {
      setFormData({ 
        id: quotation.id,
        customer_id: quotation.customer_id, 
        quotation_number: quotation.quotation_number, 
        date: new Date(quotation.date).toISOString().split('T')[0], 
        expiry_date: new Date(quotation.expiry_date).toISOString().split('T')[0], 
        status: quotation.status,
        notes: quotation.notes || ''
      });
      if (quotation.items && quotation.items.length > 0) {
        setQuotationItems(quotation.items.map((i: any) => ({
          item_id: i.item_id ? i.item_id.toString() : '',
          quantity: Number(i.quantity),
          unit_price: Number(i.unit_price)
        })));
      } else {
        setQuotationItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      }
      setIsEditing(true);
    } else {
      setFormData({ 
        id: '',
        customer_id: customers.length > 0 ? customers[0].id : '', 
        quotation_number: `QT-${Math.floor(1000 + Math.random() * 9000)}`, 
        date: new Date().toISOString().split('T')[0], 
        expiry_date: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], 
        status: 'Pending',
        notes: ''
      });
      setQuotationItems([{ item_id: '', quantity: 1, unit_price: 0 }]);
      setIsEditing(false);
    }
    setShowModal(true);
  };

  const handleCloseModal = () => {
    setShowModal(false);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const payload = {
      ...formData,
      total_amount: calculateTotal(),
      items: quotationItems.filter(item => item.item_id)
    };

    try {
      const url = `${basePath}/api/quotations`;
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      if (res.ok) {
        setShowModal(false);
        fetchQuotations();
      } else {
        const errorData = await res.json();
        alert(errorData.message || 'Operation failed');
      }
    } catch (error) {
      console.error('Error saving quotation', error);
      alert('Failed to save quotation');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this quotation?')) return;
    try {
      const res = await fetch(`${basePath}/api/quotations?id=${id}`, { method: 'DELETE' });
      if (res.ok) {
        fetchQuotations();
      } else {
        alert('Failed to delete quotation');
      }
    } catch (error) {
      console.error('Error deleting quotation', error);
    }
  };

  const formatCurrency = (val: number) => {
    return formatMoney(val);
  };

  const filteredQuotations = quotations.filter(q => {
    const matchesSearch = 
      q.quotation_number.toLowerCase().includes(searchTerm.toLowerCase()) ||
      (q.customer_name && q.customer_name.toLowerCase().includes(searchTerm.toLowerCase()));
    const matchesStatus = statusFilter === 'ALL' || q.status.toLowerCase() === statusFilter.toLowerCase();
    return matchesSearch && matchesStatus;
  });

  const totalValue = quotations.reduce((acc, q) => acc + Number(q.total_amount), 0);
  const pendingCount = quotations.filter(q => q.status === 'Pending').length;
  const approvedCount = quotations.filter(q => q.status === 'Approved').length;

  return (
    <div className="table-page-container">
      {/* Top Header */}
      <div className="table-header">
        <div>
          <h1 className="table-title">Quotations & Estimates</h1>
          <p className="table-subtitle">Create, manage, and track formal price estimates and proposals</p>
        </div>
        <button className="btn btn-primary" onClick={() => handleOpenModal()}>
          + Create Quotation
        </button>
      </div>

      {/* KPI Cards */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '18px', borderLeft: '4px solid #168FCE' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Quotations</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>{quotations.length}</div>
          <div style={{ fontSize: '0.8rem', color: '#168FCE', marginTop: '2px' }}>Total pipeline: {formatCurrency(totalValue)}</div>
        </div>
        <div className="card" style={{ padding: '18px', borderLeft: '4px solid #f59e0b' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Pending Approval</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#f59e0b', marginTop: '4px' }}>{pendingCount}</div>
          <div style={{ fontSize: '0.8rem', color: '#64748b', marginTop: '2px' }}>Awaiting customer reply</div>
        </div>
        <div className="card" style={{ padding: '18px', borderLeft: '4px solid #00539B' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Approved Quotations</div>
          <div style={{ fontSize: '1.5rem', fontWeight: 700, color: '#00539B', marginTop: '4px' }}>{approvedCount}</div>
          <div style={{ fontSize: '0.8rem', color: '#00539B', marginTop: '2px' }}>Ready for invoice conversion</div>
        </div>
      </div>

      {/* Filters */}
      <div className="filters-container">
        <div className="search-input-wrap">
          <svg
            style={{
              position: 'absolute',
              left: '0.75rem',
              top: '50%',
              transform: 'translateY(-50%)',
              color: '#94a3b8',
              pointerEvents: 'none'
            }}
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input 
            type="text" 
            placeholder="Search by quotation # or customer name..." 
            className="search-input"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        <select 
          className="filter-select"
          value={statusFilter}
          onChange={(e) => setStatusFilter(e.target.value)}
        >
          <option value="ALL">All Statuses</option>
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Rejected">Rejected</option>
          <option value="Draft">Draft</option>
        </select>
      </div>

      {/* Main Table */}
      <div className="table-card">
        {isLoading ? (
          <div className="table-loading">Loading quotations...</div>
        ) : filteredQuotations.length === 0 ? (
          <div className="empty-state">
            <div className="empty-icon">📄</div>
            <h3>No quotations found</h3>
            <p>Generate your first formal quotation to send to prospective clients.</p>
            <button className="btn btn-primary" onClick={() => handleOpenModal()} style={{ marginTop: '1rem' }}>
              + Create Quotation
            </button>
          </div>
        ) : (
          <div className="table-responsive">
            <table className="data-table">
              <thead>
                <tr>
                  <th>Quotation #</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Valid Until</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th style={{ textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredQuotations.map((q) => (
                  <tr key={q.id}>
                    <td className="font-semibold text-primary">{q.quotation_number}</td>
                    <td>{q.customer_name}</td>
                    <td>{new Date(q.date).toLocaleDateString()}</td>
                    <td>{new Date(q.expiry_date).toLocaleDateString()}</td>
                    <td className="font-semibold">{formatCurrency(Number(q.total_amount))}</td>
                    <td>
                      <span className={`status-pill ${
                        q.status === 'Approved' ? 'status-approved' :
                        q.status === 'Pending' ? 'status-pending' :
                        q.status === 'Rejected' ? 'status-rejected' : 'status-draft'
                      }`}>
                        {q.status}
                      </span>
                    </td>
                    <td style={{ textAlign: 'right' }}>
                      <button className="action-btn" onClick={() => handleOpenModal(q)}>Edit</button>
                      <button className="action-btn text-danger" onClick={() => handleDelete(q.id)}>Delete</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Modal */}
      {showModal && (
        <div className="modal-overlay">
          <div className="modal-content" style={{ maxWidth: '750px' }}>
            <div className="modal-header">
              <h2>{isEditing ? 'Edit Quotation' : 'Create New Quotation'}</h2>
              <button className="close-btn" onClick={handleCloseModal}>&times;</button>
            </div>
            
            <form onSubmit={handleSubmit}>
              <div className="modal-body">
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem', marginBottom: '1rem' }}>
                  <div className="form-group">
                    <label>Quotation Number</label>
                    <input 
                      type="text" 
                      className="form-input" 
                      required 
                      value={formData.quotation_number}
                      onChange={(e) => setFormData({ ...formData, quotation_number: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <label>Customer</label>
                    <select 
                      className="form-input" 
                      required
                      value={formData.customer_id}
                      onChange={(e) => setFormData({ ...formData, customer_id: e.target.value })}
                    >
                      <option value="">Select Customer</option>
                      {customers.map((c) => (
                        <option key={c.id} value={c.id}>{c.name}</option>
                      ))}
                    </select>
                  </div>
                </div>

                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '1rem', marginBottom: '1.5rem' }}>
                  <div className="form-group">
                    <label>Quote Date</label>
                    <input 
                      type="date" 
                      className="form-input" 
                      required 
                      value={formData.date}
                      onChange={(e) => setFormData({ ...formData, date: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <label>Valid Until</label>
                    <input 
                      type="date" 
                      className="form-input" 
                      required 
                      value={formData.expiry_date}
                      onChange={(e) => setFormData({ ...formData, expiry_date: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <label>Status</label>
                    <select 
                      className="form-input" 
                      value={formData.status}
                      onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                    >
                      <option value="Draft">Draft</option>
                      <option value="Pending">Pending</option>
                      <option value="Approved">Approved</option>
                      <option value="Rejected">Rejected</option>
                    </select>
                  </div>
                </div>

                {/* Line Items */}
                <div style={{ marginBottom: '1.5rem' }}>
                  <label style={{ display: 'block', fontWeight: 600, marginBottom: '0.5rem', color: '#334155' }}>
                    Quotation Items & Services
                  </label>
                  
                  {quotationItems.map((item, index) => (
                    <div key={index} style={{ display: 'grid', gridTemplateColumns: '3fr 1fr 1.5fr 1.5fr auto', gap: '0.75rem', alignItems: 'center', marginBottom: '0.5rem' }}>
                      <select 
                        className="form-input"
                        value={item.item_id}
                        onChange={(e) => handleItemChange(index, 'item_id', e.target.value)}
                        required
                      >
                        <option value="">Select Product or Service</option>
                        {availableItems.map((i) => (
                          <option key={i.id} value={i.id}>{i.name} ({formatCurrency(Number(i.price))})</option>
                        ))}
                      </select>
                      
                      <input 
                        type="number" 
                        className="form-input" 
                        min="1" 
                        placeholder="Qty"
                        value={item.quantity}
                        onChange={(e) => handleItemChange(index, 'quantity', e.target.value)}
                        required
                      />

                      <input 
                        type="number" 
                        className="form-input" 
                        placeholder="Price"
                        value={item.unit_price}
                        onChange={(e) => handleItemChange(index, 'unit_price', e.target.value)}
                        required
                      />

                      <div style={{ fontWeight: 600, color: '#0f172a', textAlign: 'right', paddingRight: '10px' }}>
                        {formatCurrency(Number(item.quantity) * Number(item.unit_price))}
                      </div>

                      <button 
                        type="button" 
                        onClick={() => removeLineItem(index)}
                        style={{ background: 'none', border: 'none', color: '#ef4444', fontSize: '1.25rem', cursor: 'pointer' }}
                      >
                        &times;
                      </button>
                    </div>
                  ))}

                  <button 
                    type="button" 
                    onClick={addLineItem}
                    style={{ background: '#f1f5f9', border: '1px dashed #cbd5e1', padding: '0.4rem 0.8rem', borderRadius: '4px', fontSize: '0.85rem', cursor: 'pointer', marginTop: '0.25rem', color: '#475569' }}
                  >
                    + Add Item
                  </button>
                </div>

                <div className="form-group" style={{ marginBottom: '1.5rem' }}>
                  <label>Notes / Terms</label>
                  <textarea 
                    className="form-input" 
                    rows={2} 
                    placeholder="E.g. Delivery terms, payment timeline, warranty info..." 
                    value={formData.notes}
                    onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                  />
                </div>

                <div style={{ display: 'flex', justifyContent: 'flex-end', borderTop: '1px solid #e2e8f0', paddingTop: '1rem', marginTop: '1rem' }}>
                  <div style={{ textAlign: 'right' }}>
                    <div style={{ fontSize: '0.875rem', color: '#64748b' }}>Estimated Total Amount:</div>
                    <div style={{ fontSize: '1.75rem', fontWeight: 700, color: '#168FCE' }}>
                      {formatCurrency(calculateTotal())}
                    </div>
                  </div>
                </div>
              </div>

              <div className="modal-footer">
                <button type="button" className="btn btn-secondary" onClick={handleCloseModal}>Cancel</button>
                <button type="submit" className="btn btn-primary">{isEditing ? 'Save Changes' : 'Create Quotation'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
