'use client';

import { useState, useEffect, useRef } from 'react';
import { useRouter } from 'next/navigation';

export default function CybersourceCheckout({ amount }: { amount: string }) {
  const router = useRouter();
  const [isProcessing, setIsProcessing] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [captureContext, setCaptureContext] = useState<string | null>(null);
  const [showCheckout, setShowCheckout] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  // Load Capture Context
  const initiateCheckout = async () => {
    setIsProcessing(true);
    setError(null);
    try {
      const res = await fetch('/api/checkout/session', { method: 'POST' });
      if (!res.ok) {
        throw new Error('Failed to initialize secure checkout session. Please check API credentials.');
      }
      const data = await res.json();
      setCaptureContext(data.captureContext);
      setShowCheckout(true);
    } catch (err: any) {
      setError(err.message || 'An error occurred while initializing checkout.');
    } finally {
      setIsProcessing(false);
    }
  };

  // Mount Cybersource Unified Checkout
  useEffect(() => {
    if (showCheckout && captureContext && containerRef.current) {
      // Create and load the script if not exists
      if (!document.getElementById('cybersource-microform')) {
        const script = document.createElement('script');
        script.id = 'cybersource-microform';
        script.src = 'https://flex.cybersource.com/cybersource/assets/microform/0.11/flex-microform.min.js';
        script.async = true;
        
        script.onload = () => {
          setupMicroform();
        };
        document.body.appendChild(script);
      } else {
        setupMicroform();
      }
    }
    
    function setupMicroform() {
      try {
        // Wait briefly for Flex to attach to window
        setTimeout(() => {
          const flex = (window as any).FLEX;
          if (flex) {
            const microform = flex(captureContext);
            const myDropIn = microform.createDropin({
              container: containerRef.current
            });
            
            myDropIn.on('tokenCreated', async (error: any, response: any) => {
              if (error) {
                console.error('Token generation error:', error);
                setError('Failed to tokenize payment method.');
                return;
              }
              
              // We got the token, process it on our server
              processPayment(response.token);
            });
          }
        }, 500);
      } catch (err) {
        console.error("Microform setup error:", err);
      }
    }
  }, [showCheckout, captureContext]);

  const processPayment = async (transientToken: string) => {
    setIsProcessing(true);
    try {
      const res = await fetch('/api/checkout/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ transientToken, amount })
      });
      
      const data = await res.json();
      
      if (res.ok && data.success) {
        // Success - navigate to dashboard or show success message
        window.location.reload();
      } else {
        setError(data.error || 'Payment declined.');
      }
    } catch (err: any) {
      setError('An error occurred during payment processing.');
    } finally {
      setIsProcessing(false);
    }
  };

  if (showCheckout) {
    return (
      <div style={{ marginTop: '30px', textAlign: 'left', background: '#f8fafc', padding: '20px', borderRadius: '8px', border: '1px solid #e2e8f0' }}>
        <h3 style={{ fontSize: '1.1rem', marginBottom: '15px' }}>Secure Checkout</h3>
        
        {error && <div style={{ padding: '10px', backgroundColor: '#fee2e2', color: '#991b1b', borderRadius: '4px', marginBottom: '15px' }}>{error}</div>}
        
        <div ref={containerRef} style={{ minHeight: '300px' }}>
           <p style={{ color: '#64748b' }}>Loading secure payment fields...</p>
        </div>
        
        {isProcessing && <div style={{ marginTop: '10px', color: '#0b3c5d', fontWeight: 'bold' }}>Processing payment...</div>}
        
        <button 
          onClick={() => setShowCheckout(false)} 
          style={{ background: 'none', border: 'none', color: '#64748b', textDecoration: 'underline', marginTop: '15px', cursor: 'pointer' }}
          disabled={isProcessing}
        >
          Cancel
        </button>
      </div>
    );
  }

  return (
    <div style={{ marginTop: '30px', textAlign: 'center' }}>
      <p style={{ color: '#64748b', marginBottom: '15px', fontSize: '0.95rem' }}>
        Renew or upgrade your subscription securely via CyberSource Unified Checkout (Cards, Google Pay, Apple Pay).
      </p>
      
      {error && <div style={{ padding: '10px', backgroundColor: '#fee2e2', color: '#991b1b', borderRadius: '4px', marginBottom: '15px' }}>{error}</div>}
      
      <button 
        onClick={initiateCheckout} 
        className="btn btn-primary"
        style={{ width: '100%', padding: '12px', fontSize: '1.1rem', backgroundColor: '#0b3c5d', border: 'none', cursor: 'pointer' }}
        disabled={isProcessing}
      >
        {isProcessing ? 'Connecting...' : 'Pay Now Securely'}
      </button>
    </div>
  );
}
