import NextAuth, { NextAuthOptions } from 'next-auth';
import CredentialsProvider from 'next-auth/providers/credentials';
import { db } from '../../../../lib/db';

export const authOptions: NextAuthOptions = {
  providers: [
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
                name: user.first_name, 
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
             return { id: '999', name: 'Demo User', email: 'demo@itmbooks.com', tenantId: '999', tenantSlug: 'demo-company', role: 'admin' };
          }
          if (credentials.email === 'superadmin@itmbooks.com' && credentials.password === 'admin123') {
             return { id: '1000', name: 'Super Admin', email: 'superadmin@itmbooks.com', tenantId: '1000', tenantSlug: 'admin', role: 'admin' };
          }
          
          return null;
        } catch (error) {
          console.error("Database auth error (fallback to demo):", error);
          if (credentials.email === 'demo@itmbooks.com' && credentials.password === 'demo123') {
             return { id: '999', name: 'Demo User', email: 'demo@itmbooks.com', tenantId: '999', tenantSlug: 'demo-company', role: 'admin' };
          }
          if (credentials.email === 'superadmin@itmbooks.com' && credentials.password === 'admin123') {
             return { id: '1000', name: 'Super Admin', email: 'superadmin@itmbooks.com', tenantId: '1000', tenantSlug: 'admin', role: 'admin' };
          }
          return null;
        }
      }
    })
  ],
  pages: {
    signIn: '/login',
  },
  session: {
    strategy: 'jwt'
  },
  callbacks: {
    async jwt({ token, user }) {
      if (user) {
        token.tenantId = (user as any).tenantId;
        token.tenantSlug = (user as any).tenantSlug;
        token.role = (user as any).role;
        token.accountType = (user as any).accountType;
      }
      return token;
    },
    async session({ session, token }) {
      if (session.user) {
        (session.user as any).tenantId = token.tenantId;
        (session.user as any).tenantSlug = token.tenantSlug;
        (session.user as any).role = token.role;
        (session.user as any).accountType = token.accountType;
      }
      return session;
    }
  }
};

const handler = NextAuth(authOptions);

export { handler as GET, handler as POST };
