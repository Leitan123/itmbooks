'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { getSession } from 'next-auth/react';

export default function HnbCheckoutPage() {
  const router = useRouter();
  const [isProcessing, setIsProcessing] = useState(false);

  const handlePayment = (e: React.FormEvent) => {
    e.preventDefault();
    setIsProcessing(true);

    // Simulate the 2.5 second IPG verification time from the legacy app
    setTimeout(async () => {
      // Redirect to the new dashboard on success
      const session = await getSession();
      if (session?.user && (session.user as any).tenantSlug) {
        router.push(`/${(session.user as any).tenantSlug}?payment=success`);
      } else {
        router.push('/dashboard?payment=success');
      }
    }, 2500);
  };

  const cancelTransaction = () => {
    if (confirm("Are you sure you want to cancel the transaction?")) {
      router.push('/pricing');
    }
  };

  return (
    <>
      {/* Header matching legacy HNB view */}
      <header style={{ backgroundColor: '#0b3c5d', color: '#fff', padding: '15px 0', borderBottom: '4px solid #f5a623' }}>
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div style={{ fontSize: '24px', fontWeight: 800, letterSpacing: '0.05em' }}>
            HNB <span style={{ color: '#f5a623' }}>IPG</span>
          </div>
          <div style={{ fontSize: '0.85rem', opacity: 0.8 }}>
            🔒 Hatton National Bank Secure Portal
          </div>
        </div>
      </header>

      <div style={{ backgroundColor: '#f1f5f9', minHeight: 'calc(100vh - 75px)', display: 'flex', alignItems: 'flex-start', justifyContent: 'center', paddingTop: '40px' }}>
        <div style={{ background: '#fff', border: '1px solid #e2e8f0', borderRadius: '12px', boxShadow: '0 10px 25px rgba(0,0,0,0.05)', padding: '35px', width: '100%', maxWidth: '550px' }}>
          
          <h4 style={{ textAlign: 'center', marginBottom: '1.5rem', fontWeight: 700, color: '#0b3c5d', fontSize: '1.25rem' }}>
            Internet Payment Gateway
          </h4>

          {/* Merchant Info */}
          <div style={{ backgroundColor: '#f8fafc', border: '1px solid #cbd5e1', borderRadius: '8px', padding: '20px', marginBottom: '25px', fontSize: '14px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
              <span style={{ color: '#64748b' }}>Merchant:</span>
              <span style={{ fontWeight: 500 }}>ITM Books (Pvt) Ltd</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
              <span style={{ color: '#64748b' }}>Package:</span>
              <span style={{ fontWeight: 500 }}>Starter Plan</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', paddingTop: '8px', marginTop: '8px', borderTop: '1px dashed #cbd5e1', fontWeight: 700 }}>
              <span>Total Amount:</span>
              <span>LKR 4,500.00</span>
            </div>
          </div>

          <form onSubmit={handlePayment}>
            <div style={{ marginBottom: '1rem' }}>
              <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '5px' }}>Cardholder Name</label>
              <input type="text" required placeholder="J. D. SILVA" style={{ width: '100%', height: '48px', padding: '0 15px', border: '1px solid #cbd5e1', borderRadius: '6px', fontSize: '15px' }} />
            </div>

            <div style={{ marginBottom: '1rem' }}>
              <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '5px' }}>Card Number</label>
              <input type="text" required placeholder="4111 2222 3333 4444" pattern="[0-9\s]{13,19}" style={{ width: '100%', height: '48px', padding: '0 15px', border: '1px solid #cbd5e1', borderRadius: '6px', fontSize: '15px' }} />
            </div>

            <div style={{ display: 'flex', gap: '15px', marginBottom: '1.5rem' }}>
              <div style={{ flex: 1 }}>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '5px' }}>Expiry Date</label>
                <input type="text" required placeholder="MM / YY" style={{ width: '100%', height: '48px', padding: '0 15px', border: '1px solid #cbd5e1', borderRadius: '6px', fontSize: '15px' }} />
              </div>
              <div style={{ flex: 1 }}>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', marginBottom: '5px' }}>CVV / CVC</label>
                <input type="password" required placeholder="123" style={{ width: '100%', height: '48px', padding: '0 15px', border: '1px solid #cbd5e1', borderRadius: '6px', fontSize: '15px' }} />
              </div>
            </div>

            <button type="submit" style={{ width: '100%', backgroundColor: '#0b3c5d', color: '#fff', border: 'none', padding: '14px', borderRadius: '8px', fontWeight: 700, fontSize: '16px', cursor: 'pointer', marginBottom: '15px' }}>
              Pay LKR 4,500.00
            </button>

            <div style={{ textAlign: 'center' }}>
              <button type="button" onClick={cancelTransaction} style={{ background: 'none', border: 'none', color: '#ef4444', textDecoration: 'underline', fontWeight: 600, fontSize: '14px', cursor: 'pointer' }}>
                Cancel Transaction
              </button>
            </div>
          </form>
          
          <div style={{ textAlign: 'center', marginTop: '25px', fontSize: '12px', color: '#64748b' }}>
            <span style={{ color: '#10b981' }}>✓</span> 3D Secure Verification Enabled. Your transaction details are fully encrypted.
          </div>
        </div>
      </div>

      {isProcessing && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(11, 60, 93, 0.9)', zIndex: 9999, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', color: '#fff' }}>
          <div style={{ border: '4px solid rgba(255,255,255,0.3)', borderTop: '4px solid #fff', borderRadius: '50%', width: '50px', height: '50px', animation: 'spin 1s linear infinite', marginBottom: '20px' }} />
          <style>{`@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }`}</style>
          <h3 style={{ margin: '0 0 10px', fontSize: '1.25rem' }}>Contacting Hatton National Bank secure servers...</h3>
          <p style={{ opacity: 0.8, fontSize: '0.9rem' }}>Please do not refresh the page or click back.</p>
        </div>
      )}
    </>
  );
}
