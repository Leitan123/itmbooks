'use client';

import { SessionProvider } from 'next-auth/react';

export function Providers({ children }: { children: React.ReactNode }) {
  return <SessionProvider basePath="/itmbooks.com/api/auth">{children}</SessionProvider>;
}
