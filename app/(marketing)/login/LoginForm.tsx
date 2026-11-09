'use client';

import { useState, useEffect } from 'react';
import { signIn, getSession, useSession } from 'next-auth/react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { FcGoogle } from 'react-icons/fc';
import '../form.css';

export default function LoginForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { data: session, status } = useSession();

  const urlError = searchParams.get('error');
  const isRegistered = searchParams.get('registered') === 'true';

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isGoogleLoading, setIsGoogleLoading] = useState(false);
  const [isRedirecting, setIsRedirecting] = useState(false);

  // If already authenticated on client, auto redirect
  useEffect(() => {
    if (status === 'authenticated' && session?.user) {
      const targetSlug = (session.user as any).tenantSlug || 'dashboard';
      router.replace(`/${targetSlug}`);
    }
  }, [status, session, router]);

  let alertMessage = error;
  let alertType: 'error' | 'success' | null = error ? 'error' : null;

  if (!alertMessage) {
    if (urlError === 'OAuthSignin' || urlError === 'OAuthCallback') {
      alertMessage = 'Google Sign-In failed. Please verify that GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET are configured in .env.local and authorized redirect URIs are set in Google Cloud Console.';
      alertType = 'error';
    } else if (urlError === 'OAuthAccountNotLinked') {
      alertMessage = 'An account with this email already exists. Please sign in with your email and password.';
      alertType = 'error';
    } else if (urlError) {
      alertMessage = 'Authentication failed. Please check your credentials and try again.';
      alertType = 'error';
    } else if (isRegistered) {
      alertMessage = 'Registration successful! You can now sign in.';
      alertType = 'success';
    }
  }

  const handleGoogleLogin = async () => {
    setIsGoogleLoading(true);
    setError('');
    try {
      await signIn('google', { callbackUrl: '/itmbooks.com/dashboard' });
    } catch (err) {
      setIsGoogleLoading(false);
      setError('Could not initiate Google authentication.');
    }
  };

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    try {
      const res = await signIn('credentials', {
        redirect: false,
        email,
        password,
      });

      if (res?.error) {
        setError('Invalid email or password');
        setIsLoading(false);
      } else {
        setIsRedirecting(true);
        const session = await getSession();
        const targetSlug = (session?.user as any)?.tenantSlug || 'dashboard';
        router.push(`/${targetSlug}`);
      }
    } catch (err) {
      setError('An unexpected error occurred during login');
      setIsLoading(false);
    }
  };

  if (status === 'authenticated' || isRedirecting) {
    return (
      <div className="auth-card" style={{ textAlign: 'center', padding: '3rem 2rem' }}>
        <h2 style={{ fontSize: '1.4rem', fontWeight: 700, marginBottom: '0.75rem', color: '#0f172a' }}>
          Welcome back!
        </h2>
        <p style={{ color: '#64748b', fontSize: '0.95rem' }}>
          You are already signed in. Opening your dashboard...
        </p>
      </div>
    );
  }

  return (
    <div className="auth-card">
      <h1 className="auth-title">Welcome back to ITM Books</h1>
      <p className="auth-subtitle">Sign in to manage your accounting</p>
      
      {alertMessage && (
        <div className={`auth-alert ${alertType === 'success' ? 'auth-alert-success' : 'auth-alert-error'}`}>
          {alertMessage}
        </div>
      )}

      {/* Google Authentication */}
      <div className="auth-oauth-container">
        <button
          type="button"
          className="btn-google"
          onClick={handleGoogleLogin}
          disabled={isLoading || isGoogleLoading}
        >
          <FcGoogle size={20} />
          <span>{isGoogleLoading ? 'Connecting to Google...' : 'Continue with Google'}</span>
        </button>

        <div className="auth-divider">
          <span>or continue with email</span>
        </div>
      </div>

      <form className="auth-form" onSubmit={handleLogin}>
        <div className="form-group">
          <label className="form-label">Email Address</label>
          <input 
            type="email" 
            className="form-input" 
            placeholder="you@company.com" 
            required 
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            disabled={isLoading || isGoogleLoading}
          />
        </div>

        <div className="form-group">
          <label className="form-label" style={{ display: 'flex', justifyContent: 'space-between' }}>
            Password
            <a href="#" className="forgot-password">Forgot?</a>
          </label>
          <input 
            type="password" 
            className="form-input" 
            placeholder="••••••••" 
            required 
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            disabled={isLoading || isGoogleLoading}
          />
        </div>

        <button type="submit" className="btn btn-primary btn-block" disabled={isLoading || isGoogleLoading || isRedirecting}>
          {isRedirecting ? 'Redirecting to dashboard...' : isLoading ? 'Signing In...' : 'Sign In'}
        </button>
      </form>

      <div className="auth-footer">
        Don't have an account? <Link href="/signup">Start your free trial</Link>
      </div>
    </div>
  );
}

