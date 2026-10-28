'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { signIn, getSession } from 'next-auth/react';
import Link from 'next/link';
import '../form.css';

export default function DemoPage() {
  const router = useRouter();
  const [formData, setFormData] = useState({
    name: '',
    company: '',
    email: '',
    phone: '',
    country: 'Sri Lanka',
    currency: 'LKR',
    password: ''
  });
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');

  const handleDemoSignup = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    try {
      // 1. Register the user
      const res = await fetch('/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const data = await res.json();

      if (res.ok) {
        // 2. Automatically sign them in
        const result = await signIn('credentials', {
          redirect: false,
          email: formData.email,
          password: formData.password,
        });

        if (result?.error) {
          setError('Account created, but automatic login failed. Please sign in manually.');
          setIsLoading(false);
          router.push('/login');
        } else {
          // 3. Redirect directly to the dashboard (bypassing payment gateway for the free demo)
          const session = await getSession();
          if (session?.user && (session.user as any).tenantSlug) {
            router.push(`/${(session.user as any).tenantSlug}`);
          } else {
            router.push('/dashboard');
          }
        }
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
        <h1 className="auth-title">Get Instant Free Demo Access</h1>
        <p className="auth-subtitle">Fill out the details below to instantly access the ITM Books dashboard. No credit card required.</p>

        {error && <div style={{ color: '#ef4444', backgroundColor: '#fef2f2', padding: '10px', borderRadius: '6px', marginBottom: '1rem', fontSize: '14px', border: '1px solid #fee2e2' }}>{error}</div>}

        <form className="auth-form" onSubmit={handleDemoSignup}>
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
              <label className="form-label">Software Type</label>
              <select name="currency" className="form-input" required value={formData.currency} onChange={handleChange}>
                <option>Accounting SaaS</option>
                <option>Inventory SaaS</option>
              </select>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Set Password for Demo Access</label>
            <input type="password" name="password" className="form-input" required value={formData.password} onChange={handleChange} />
          </div>

          <button type="submit" className="btn btn-primary btn-block" disabled={isLoading}>
            {isLoading ? 'Creating Demo Account...' : 'Get Instant Demo Access'}
          </button>
        </form>

        <div className="auth-footer">
          Already have an account? <Link href="/login">Sign In</Link>
        </div>
      </div>
    </div>
  );
}
