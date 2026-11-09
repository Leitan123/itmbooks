'use client';

import { signOut } from 'next-auth/react';

export default function ExitAdminButton() {
  const handleExit = () => {
    signOut({ callbackUrl: '/itmbooks.com/login' });
  };

  return (
    <button
      type="button"
      onClick={handleExit}
      className="sidebar-link"
      style={{
        width: '100%',
        textAlign: 'left',
        background: 'none',
        border: 'none',
        color: '#cbd5e1',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        gap: '0.5rem',
        padding: '0.75rem 1rem',
        fontSize: '0.9rem',
        fontFamily: 'inherit',
      }}
    >
      &larr; Exit Admin
    </button>
  );
}

