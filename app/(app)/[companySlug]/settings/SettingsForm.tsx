'use client';

import { useState, useRef } from 'react';
import { useRouter } from 'next/navigation';
import '../../../(marketing)/form.css';

type Settings = {
  name: string;
  address: string;
  tel: string;
  mobile: string;
  whatsapp: string;
  logo_url: string;
  theme_color: string;
};

export default function SettingsForm({ initialData, basePath }: { initialData: Settings; basePath: string }) {
  const router = useRouter();
  const [formData, setFormData] = useState<Settings>(initialData);
  const [isSaving, setIsSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleLogoUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files || e.target.files.length === 0) return;
    
    const file = e.target.files[0];
    const uploadData = new FormData();
    uploadData.append('file', file);

    try {
      setMessage({ text: 'Uploading logo...', type: 'info' });
      const res = await fetch(`${basePath}/api/upload`, {
        method: 'POST',
        body: uploadData,
      });
      
      const data = await res.json();
      if (res.ok) {
        setFormData({ ...formData, logo_url: data.url });
        setMessage({ text: 'Logo uploaded. Remember to save changes.', type: 'success' });
      } else {
        setMessage({ text: data.error || 'Failed to upload logo', type: 'error' });
      }
    } catch (error) {
      setMessage({ text: 'An error occurred during upload', type: 'error' });
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    setMessage({ text: '', type: '' });

    try {
      const res = await fetch(`${basePath}/api/settings`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (res.ok) {
        setMessage({ text: 'Settings saved successfully!', type: 'success' });
        router.refresh();
      } else {
        setMessage({ text: data.error || 'Failed to save settings', type: 'error' });
      }
    } catch (error) {
      setMessage({ text: 'An error occurred while saving', type: 'error' });
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <div className="card" style={{ maxWidth: '800px' }}>
      <form onSubmit={handleSubmit}>
        
        {message.text && (
          <div style={{
            padding: '12px',
            marginBottom: '20px',
            borderRadius: '6px',
            backgroundColor: message.type === 'error' ? '#fef2f2' : (message.type === 'info' ? '#eff6ff' : '#f0fdf4'),
            color: message.type === 'error' ? '#ef4444' : (message.type === 'info' ? '#3b82f6' : '#16a34a'),
            border: `1px solid ${message.type === 'error' ? '#fee2e2' : (message.type === 'info' ? '#bfdbfe' : '#bbf7d0')}`
          }}>
            {message.text}
          </div>
        )}

        <div style={{ marginBottom: '20px' }}>
          <label style={{ display: 'block', marginBottom: '8px', fontWeight: 'bold' }}>Company Logo</label>
          <div style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
            {formData.logo_url ? (
              <img src={formData.logo_url} alt="Company Logo" style={{ width: '100px', height: '100px', objectFit: 'contain', border: '1px solid #e2e8f0', borderRadius: '8px', padding: '5px' }} />
            ) : (
              <div style={{ width: '100px', height: '100px', backgroundColor: '#f1f5f9', display: 'flex', alignItems: 'center', justifyContent: 'center', border: '1px dashed #cbd5e1', borderRadius: '8px', color: '#94a3b8' }}>
                No Logo
              </div>
            )}
            <div>
              <input 
                type="file" 
                accept="image/*"
                ref={fileInputRef}
                style={{ display: 'none' }}
                onChange={handleLogoUpload}
              />
              <button 
                type="button" 
                className="btn btn-outline"
                onClick={() => fileInputRef.current?.click()}
              >
                Upload New Logo
              </button>
            </div>
          </div>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '20px' }}>
          <div className="form-group">
            <label className="form-label">Theme Color</label>
            <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
              <input 
                type="color" 
                name="theme_color" 
                value={formData.theme_color || '#168FCE'} 
                onChange={handleChange} 
                style={{ width: '50px', height: '40px', padding: '0', cursor: 'pointer' }}
              />
              <span style={{ fontSize: '0.9rem', color: '#64748b' }}>Select your brand color</span>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Company Name *</label>
            <input 
              type="text" 
              name="name" 
              className="form-input" 
              required 
              value={formData.name || ''} 
              onChange={handleChange} 
            />
          </div>

          <div className="form-group">
            <label className="form-label">Address</label>
            <textarea 
              name="address" 
              className="form-input" 
              rows={3} 
              value={formData.address || ''} 
              onChange={handleChange} 
            />
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '20px' }}>
            <div className="form-group">
              <label className="form-label">Telephone</label>
              <input 
                type="tel" 
                name="tel" 
                className="form-input" 
                value={formData.tel || ''} 
                onChange={handleChange} 
              />
            </div>
            
            <div className="form-group">
              <label className="form-label">Mobile</label>
              <input 
                type="tel" 
                name="mobile" 
                className="form-input" 
                value={formData.mobile || ''} 
                onChange={handleChange} 
              />
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">WhatsApp Number</label>
            <input 
              type="tel" 
              name="whatsapp" 
              className="form-input" 
              value={formData.whatsapp || ''} 
              onChange={handleChange} 
            />
          </div>
        </div>

        <div style={{ marginTop: '30px', borderTop: '1px solid #e2e8f0', paddingTop: '20px', display: 'flex', justifyContent: 'flex-end' }}>
          <button type="submit" className="btn btn-primary" disabled={isSaving}>
            {isSaving ? 'Saving...' : 'Save Settings'}
          </button>
        </div>
      </form>
    </div>
  );
}
