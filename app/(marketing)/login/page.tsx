'use client';

import { useState, Suspense } from 'react';
import { signIn, getSession } from 'next-auth/react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { FcGoogle } from 'react-icons/fc';
import '../form.css';

function LoginForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const urlError = searchParams.get('error');
  const isRegistered = searchParams.get('registered') === 'true';

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isGoogleLoading, setIsGoogleLoading] = useState(false);

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
      await signIn('google', { callbackUrl: '/dashboard' });
    } catch (err) {
      setIsGoogleLoading(false);
      setError('Could not initiate Google authentication.');
    }
  };

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    const res = await signIn('credentials', {
      redirect: false,
      email,
      password,
    });

    if (res?.error) {
      setError('Invalid email or password');
      setIsLoading(false);
    } else {
      const session = await getSession();
      if (session?.user && (session.user as any).tenantSlug) {
        router.push(`/${(session.user as any).tenantSlug}`);
      } else {
        router.push('/dashboard'); // fallback
      }
    }
  };

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

        <button type="submit" className="btn btn-primary btn-block" disabled={isLoading || isGoogleLoading}>
          {isLoading ? 'Signing In...' : 'Sign In'}
        </button>
      </form>

      <div className="auth-footer">
        Don't have an account? <Link href="/signup">Start your free trial</Link>
      </div>
    </div>
  );
}

export default function LoginPage() {
  return (
    <div className="auth-container">
      <Suspense fallback={<div className="auth-card" style={{ textAlign: 'center', padding: '3rem' }}>Loading sign in...</div>}>
        <LoginForm />
      </Suspense>
    </div>
  );
}
