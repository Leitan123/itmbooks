import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';

async function getTenantId(request: Request, session: any): Promise<number | null> {
  if (session?.user?.tenantId) {
    return parseInt(session.user.tenantId, 10);
  }
  if (session?.user?.email) {
    const [rows] = await db.query('SELECT tenant_id FROM users WHERE email = ? LIMIT 1', [session.user.email]) as any;
    if (rows && rows.length > 0 && rows[0].tenant_id) {
      return parseInt(rows[0].tenant_id, 10);
    }
  }
  
  const url = new URL(request.url);
  const qTenantId = url.searchParams.get('tenant_id');
  if (qTenantId) return parseInt(qTenantId, 10);

  const referer = request.headers.get('referer');
  if (referer) {
    const match = referer.match(/\/itmbooks\.com\/([a-zA-Z0-9_-]+)/);
    if (match && match[1] && !['login', 'signup', 'pricing', 'demo', 'api'].includes(match[1])) {
      const slug = match[1];
      const [tRows] = await db.query('SELECT id FROM tenants WHERE slug = ? LIMIT 1', [slug]) as any;
      if (tRows && tRows.length > 0) {
        return parseInt(tRows[0].id, 10);
      }
    }
  }
  
  return null;
}

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    const tenantId = await getTenantId(request, session);
    
    if (!tenantId) {
      return NextResponse.json({ message: 'Unauthorized: missing tenant context' }, { status: 401 });
    }

    const [payments] = await db.query(`
      SELECT p.*, c.name as customer_name, i.invoice_number 
      FROM saas_payments p
      JOIN saas_customers c ON p.customer_id = c.id
      LEFT JOIN saas_invoices i ON p.invoice_id = i.id
      WHERE p.tenant_id = ? 
      ORDER BY p.payment_date DESC, p.created_at DESC
    `, [tenantId]);

    return NextResponse.json({ payments });
  } catch (error) {
    console.error('Error fetching payments:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    const tenantId = await getTenantId(request, session);
    
    if (!tenantId) {
      return NextResponse.json({ message: 'Unauthorized: missing tenant context' }, { status: 401 });
    }

    const { customer_id, invoice_id, amount, payment_date, payment_mode, reference_number, notes } = await request.json();

    if (!customer_id || !amount || !payment_date || !payment_mode) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_payments (tenant_id, customer_id, invoice_id, amount, payment_date, payment_mode, reference_number, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, customer_id, invoice_id || null, amount, payment_date, payment_mode, reference_number, notes]
    ) as any;

    // Auto-update invoice status to Paid if linked
    if (invoice_id) {
      await db.query('UPDATE saas_invoices SET status = "Paid" WHERE id = ? AND tenant_id = ?', [invoice_id, tenantId]);
    }

    return NextResponse.json({ 
      message: 'Payment recorded successfully', 
      payment: { id: result.insertId } 
    });
  } catch (error) {
    console.error('Error adding payment:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    const tenantId = await getTenantId(request, session);
    
    if (!tenantId) {
      return NextResponse.json({ message: 'Unauthorized: missing tenant context' }, { status: 401 });
    }

    const { id, customer_id, invoice_id, amount, payment_date, payment_mode, reference_number, notes } = await request.json();

    if (!id || !customer_id || !amount || !payment_date || !payment_mode) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    await db.query(
      'UPDATE saas_payments SET customer_id = ?, invoice_id = ?, amount = ?, payment_date = ?, payment_mode = ?, reference_number = ?, notes = ? WHERE id = ? AND tenant_id = ?',
      [customer_id, invoice_id || null, amount, payment_date, payment_mode, reference_number, notes, id, tenantId]
    );

    return NextResponse.json({ message: 'Payment updated successfully' });
  } catch (error) {
    console.error('Error updating payment:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    const tenantId = await getTenantId(request, session);
    
    if (!tenantId) {
      return NextResponse.json({ message: 'Unauthorized: missing tenant context' }, { status: 401 });
    }

    const url = new URL(request.url);
    const id = url.searchParams.get('id');

    if (!id) {
      return NextResponse.json({ message: 'Payment ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_payments WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Payment deleted successfully' });
  } catch (error) {
    console.error('Error deleting payment:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
