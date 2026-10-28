'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import '../form.css';

export default function SignupPage() {
  const router = useRouter();
  const [formData, setFormData] = useState({
    name: '',
    company: '',
    email: '',
    phone: '',
    country: 'Sri Lanka',
    currency: 'LKR',
    password: '',
    accountType: 'Invoice'
  });
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSignup = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    try {
      const res = await fetch('/itmbooks.com/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();

      if (res.ok) {
        // Successful registration, redirect to login page for the free trial
        router.push('/login?registered=true');
      } else {
        setError(data.message || 'Registration failed');
        setIsLoading(false);
      }
    } catch (err) {
      setError('An unexpected error occurred');
      setIsLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  return (
    <div className="auth-container">
      <div className="auth-card" style={{ maxWidth: '600px' }}>
        <h1 className="auth-title">Start your 14-day free trial</h1>
        <p className="auth-subtitle">Create your account to get started.</p>

        {error && <div style={{ color: '#ef4444', backgroundColor: '#fef2f2', padding: '10px', borderRadius: '6px', marginBottom: '1rem', fontSize: '14px', border: '1px solid #fee2e2' }}>{error}</div>}

        <form className="auth-form" onSubmit={handleSignup}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
            <div className="form-group">
              <label className="form-label">Full Name</label>
              <input type="text" name="name" className="form-input" required value={formData.name} onChange={handleChange} />
            </div>
            <div className="form-group">
              <label className="form-label">Company Name</label>
              <input type="text" name="company" className="form-input" required value={formData.company} onChange={handleChange} />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
            <div className="form-group">
              <label className="form-label">Email Address</label>
              <input type="email" name="email" className="form-input" required value={formData.email} onChange={handleChange} />
            </div>
            <div className="form-group">
              <label className="form-label">Phone Number</label>
              <input type="tel" name="phone" className="form-input" required value={formData.phone} onChange={handleChange} />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
            <div className="form-group">
              <label className="form-label">Business Location</label>
              <select name="country" className="form-input" required value={formData.country} onChange={handleChange}>
                <option>Sri Lanka</option>
                <option>United States</option>
                <option>United Kingdom</option>
                <option>Australia</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Base Currency</label>
              <select name="currency" className="form-input" required value={formData.currency} onChange={handleChange}>
                <option>LKR - Sri Lankan Rupee</option>
                <option>USD - US Dollar</option>
                <option>GBP - British Pound</option>
              </select>
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem', marginBottom: '1.5rem' }}>
            <div className="form-group" style={{ marginBottom: 0 }}>
              <label className="form-label">Select Product</label>
              <select name="accountType" className="form-input" required value={formData.accountType} onChange={handleChange}>
                <option value="Invoice">Invoicing (Sales & Billing)</option>
                <option value="Accounting">Accounting (Journals & Ledgers)</option>
                <option value="Inventory">Inventory (Purchases & Stock)</option>
              </select>
            </div>
            <div className="form-group" style={{ marginBottom: 0 }}>
              <label className="form-label">Password</label>
              <input type="password" name="password" className="form-input" required value={formData.password} onChange={handleChange} />
            </div>
          </div>

          <button type="submit" className="btn btn-primary btn-block" disabled={isLoading}>
            {isLoading ? 'Creating Account...' : 'Create Account & Continue to Payment'}
          </button>
        </form>

        <div className="auth-footer">
          Already have an account? <Link href="/login">Sign In</Link>
        </div>
      </div>
    </div>
  );
}
