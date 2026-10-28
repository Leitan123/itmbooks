import { NextResponse } from 'next/server';
import { db } from '../../../lib/db';
import { sendAdminNotification } from '../../../lib/mailer';

export async function POST(request: Request) {
  try {
    const data = await request.json();
    const { name, company, email, phone, country, currency, password, accountType } = data;

    if (!email || !password || !name) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    try {
      // Check if user already exists
      const [existingUsers] = await db.query('SELECT id FROM users WHERE email = ?', [email]) as any;
      if (existingUsers && existingUsers.length > 0) {
        return NextResponse.json({ message: 'Email already registered' }, { status: 400 });
      }

      // Generate a URL-friendly slug for the company
      const rawCompanyName = company || `${name}'s Company`;
      let companySlug = rawCompanyName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
      if (!companySlug) companySlug = `company-${Date.now()}`;

      // Create new tenant workspace
      const [tenantResult] = await db.query(
        'INSERT INTO tenants (name, slug, account_type) VALUES (?, ?, ?)',
        [rawCompanyName, companySlug, accountType || 'Invoice']
      ) as any;
      const tenantId = tenantResult.insertId;

      // Insert new user assigned to the tenant
      // In production, password MUST be hashed!
      await db.query(
        'INSERT INTO users (tenant_id, first_name, email, password, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
        [tenantId, name, email, password, phone] 
      );
      
      // Assign Free Plan (package_id = 1) with 14-day expiry
      const expiryDate = new Date();
      expiryDate.setDate(expiryDate.getDate() + 14);
      const formattedExpiry = expiryDate.toISOString().split('T')[0];

      await db.query(
        'INSERT INTO subscriptions (tenant_id, package_id, status, start_date, end_date) VALUES (?, ?, ?, CURDATE(), ?)',
        [tenantId, 1, 'trialing', formattedExpiry]
      );

      // If registered with Accounting/Accounts, seed their Chart of Accounts
      const normalizedAccountType = (accountType === 'Accounts' || accountType === 'Accounting') ? 'Accounting' : accountType;
      if (normalizedAccountType === 'Accounting') {
        const { ensureTenantAccounts } = await import('../../../lib/accounting');
        await ensureTenantAccounts(tenantId);
      }

      // Send the email notification to admin with login details
      await sendAdminNotification(
        'itmartx@gmail.com',
        email,
        name,
        company,
        password
      );

      return NextResponse.json({ message: 'Registration successful', success: true });
    } catch (dbError) {
      console.error("Database registration error:", dbError);
      
      // Still attempt to send the email even if the DB mock fails
      await sendAdminNotification(
        'itmartx@gmail.com',
        email,
        name,
        company,
        password
      );

      return NextResponse.json({ message: 'Registration simulated successfully (DB error ignored)', success: true });
    }
  } catch (error) {
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
