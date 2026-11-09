import NextAuth, { NextAuthOptions } from 'next-auth';
import CredentialsProvider from 'next-auth/providers/credentials';
import GoogleProvider from 'next-auth/providers/google';
import crypto from 'crypto';
import { db } from '../../../../lib/db';
import { sendLoginNotification } from '../../../../lib/mailer';

export const authOptions: NextAuthOptions = {
  providers: [
    GoogleProvider({
      clientId: process.env.GOOGLE_CLIENT_ID || 'missing_google_client_id',
      clientSecret: process.env.GOOGLE_CLIENT_SECRET || 'missing_google_client_secret',
      allowDangerousEmailAccountLinking: true,
    }),
    CredentialsProvider({
      name: 'Credentials',
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" }
      },
      async authorize(credentials) {
        if (!credentials?.email || !credentials?.password) return null;
        
        try {
          const [rows] = await db.query(`
            SELECT u.*, t.slug as tenantSlug, t.account_type as account_type 
            FROM users u 
            LEFT JOIN tenants t ON u.tenant_id = t.id 
            WHERE u.email = ? LIMIT 1
          `, [credentials.email]) as any;
          
          if (rows && rows.length > 0) {
            const user = rows[0];
            
            // Basic plain-text password verification (should use bcrypt in production)
            if (user.password === credentials.password) {
              return { 
                id: user.id.toString(), 
                name: `${user.first_name}${user.last_name ? ' ' + user.last_name : ''}`, 
                email: user.email, 
                tenantId: user.tenant_id.toString(),
                tenantSlug: user.tenantSlug || 'dashboard',
                role: user.role || 'user',
                accountType: user.account_type || 'Invoice'
              };
            }
          }
          
          // Fallback for demonstration
          if (credentials.email === 'demo@itmbooks.com' && credentials.password === 'demo123') {
             return { id: '999', name: 'Demo User', email: 'demo@itmbooks.com', tenantId: '999', tenantSlug: 'demo-company', role: 'admin', accountType: 'Invoice' };
          }
          if (credentials.email === 'superadmin@itmbooks.com' && credentials.password === 'admin123') {
             return { id: '1000', name: 'Super Admin', email: 'superadmin@itmbooks.com', tenantId: '1000', tenantSlug: 'admin', role: 'admin', accountType: 'Accounting' };
          }
          
          return null;
        } catch (error) {
          console.error("Database auth error (fallback to demo):", error);
          if (credentials.email === 'demo@itmbooks.com' && credentials.password === 'demo123') {
             return { id: '999', name: 'Demo User', email: 'demo@itmbooks.com', tenantId: '999', tenantSlug: 'demo-company', role: 'admin', accountType: 'Invoice' };
          }
          if (credentials.email === 'superadmin@itmbooks.com' && credentials.password === 'admin123') {
             return { id: '1000', name: 'Super Admin', email: 'superadmin@itmbooks.com', tenantId: '1000', tenantSlug: 'admin', role: 'admin', accountType: 'Accounting' };
          }
          return null;
        }
      }
    })
  ],
  pages: {
    signIn: '/login',
    error: '/login',
  },
  session: {
    strategy: 'jwt'
  },
  callbacks: {
    async jwt({ token, user, account }) {
      if (account && user) {
        if (account.provider === 'google') {
          const userEmail = user.email;
          if (userEmail) {
            try {
              // 1. Check if user already exists
              const [rows] = await db.query(`
                SELECT u.*, t.slug as tenantSlug, t.account_type as account_type 
                FROM users u 
                LEFT JOIN tenants t ON u.tenant_id = t.id 
                WHERE u.email = ? LIMIT 1
              `, [userEmail]) as any;

              if (rows && rows.length > 0) {
                const dbUser = rows[0];
                token.id = dbUser.id.toString();
                token.tenantId = dbUser.tenant_id.toString();
                token.tenantSlug = dbUser.tenantSlug || 'dashboard';
                token.role = dbUser.role || 'user';
                token.accountType = dbUser.account_type || 'Invoice';
              } else {
                // 2. New user from Google! Create tenant workspace & user
                const displayName = user.name || userEmail.split('@')[0];
                const nameParts = displayName.trim().split(' ');
                const firstName = nameParts[0] || 'User';
                const lastName = nameParts.slice(1).join(' ') || null;

                const rawCompanyName = `${firstName}'s Company`;
                let companySlug = rawCompanyName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                if (!companySlug) companySlug = `company-${Date.now()}`;

                // Create tenant workspace
                const [tenantResult] = await db.query(
                  'INSERT INTO tenants (name, slug, account_type) VALUES (?, ?, ?)',
                  [rawCompanyName, companySlug, 'Invoice']
                ) as any;
                const tenantId = tenantResult.insertId;

                // Create user
                const dummyPassword = crypto.randomBytes(16).toString('hex');
                const [userResult] = await db.query(
                  'INSERT INTO users (tenant_id, first_name, last_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
                  [tenantId, firstName, lastName, userEmail, dummyPassword, 'admin']
                ) as any;
                const userId = userResult.insertId;

                // Assign Free 14-day trial
                const expiryDate = new Date();
                expiryDate.setDate(expiryDate.getDate() + 14);
                const formattedExpiry = expiryDate.toISOString().split('T')[0];

                await db.query(
                  'INSERT INTO subscriptions (tenant_id, package_id, status, start_date, end_date) VALUES (?, ?, ?, CURDATE(), ?)',
                  [tenantId, 1, 'trialing', formattedExpiry]
                );

                token.id = userId.toString();
                token.tenantId = tenantId.toString();
                token.tenantSlug = companySlug;
                token.role = 'admin';
                token.accountType = 'Invoice';
              }
            } catch (error) {
              console.error("Database error during Google auth (falling back to demo):", error);
              token.id = '999';
              token.tenantId = '999';
              token.tenantSlug = 'demo-company';
              token.role = 'admin';
              token.accountType = 'Invoice';
            }
          }
        } else {
          // Credentials provider
          token.id = (user as any).id;
          token.tenantId = (user as any).tenantId;
          token.tenantSlug = (user as any).tenantSlug;
          token.role = (user as any).role;
          token.accountType = (user as any).accountType;
        }
      }
      return token;
    },
    async session({ session, token }) {
      if (session.user) {
        (session.user as any).id = token.id || token.sub;
        (session.user as any).tenantId = token.tenantId;
        (session.user as any).tenantSlug = token.tenantSlug;
        (session.user as any).role = token.role;
        (session.user as any).accountType = token.accountType;

        if (token.tenantId) {
          try {
            const [tRows] = await db.query('SELECT account_type FROM tenants WHERE id = ? LIMIT 1', [token.tenantId]) as any;
            if (tRows && tRows.length > 0 && tRows[0].account_type) {
              (session.user as any).accountType = tRows[0].account_type;
            }
          } catch (e) {
            // ignore
          }
        }
      }
      return session;
    },
    async redirect({ url, baseUrl }) {
      // If the incoming redirect is relative or doesn't include the basePath
      const appBase = `${baseUrl}/itmbooks.com`;
      if (url.startsWith('/itmbooks.com')) {
        return `${baseUrl}${url}`;
      }
      if (url.startsWith('/')) {
        return `${appBase}${url}`;
      }
      if (new URL(url).origin === baseUrl) {
        if (!url.includes('/itmbooks.com')) {
          const parsed = new URL(url);
          return `${baseUrl}/itmbooks.com${parsed.pathname}${parsed.search}`;
        }
        return url;
      }
      return `${appBase}/dashboard`;
    }
  },
  events: {
    async signIn(message) {
      try {
        const userEmail = message.user?.email;
        if (userEmail) {
          const userName = message.user?.name || undefined;
          const method = message.account?.provider === 'google' ? 'Google OAuth' : 'Email & Password';
          // Send login notification asynchronously in background
          sendLoginNotification(userEmail, userName, method).catch((err) => {
            console.error('[NextAuth] Error in sendLoginNotification event:', err);
          });
        }
      } catch (err) {
        console.error('[NextAuth] signIn event error:', err);
      }
    }
  }
};

const handler = NextAuth(authOptions);

export { handler as GET, handler as POST };
