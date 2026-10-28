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

    const [invoices] = await db.query(`
      SELECT i.*, c.name as customer_name 
      FROM saas_invoices i
      JOIN saas_customers c ON i.customer_id = c.id
      WHERE i.tenant_id = ? 
      ORDER BY i.created_at DESC
    `, [tenantId]) as any;

    // Fetch line items for each invoice
    for (let i = 0; i < invoices.length; i++) {
      const [items] = await db.query(
        'SELECT * FROM saas_invoice_items WHERE invoice_id = ?',
        [invoices[i].id]
      ) as any;
      invoices[i].items = items;
    }

    return NextResponse.json({ invoices });
  } catch (error) {
    console.error('Error fetching invoices:', error);
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

    const { customer_id, invoice_number, date, due_date, total_amount, status, items } = await request.json();

    if (!customer_id || !invoice_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    // Insert Invoice
    const [result] = await db.query(
      'INSERT INTO saas_invoices (tenant_id, customer_id, invoice_number, date, due_date, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, customer_id, invoice_number, date, due_date, total_amount, status || 'Draft']
    ) as any;

    const invoiceId = result.insertId;

    // Insert Line Items
    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_invoice_items (invoice_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [invoiceId, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ 
      message: 'Invoice created successfully', 
      invoice: { id: invoiceId, invoice_number, status } 
    });
  } catch (error) {
    console.error('Error adding invoice:', error);
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

    const { id, customer_id, invoice_number, date, due_date, total_amount, status, items } = await request.json();

    if (!id || !customer_id || !invoice_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    // Verify invoice belongs to tenant
    const [existing] = await db.query('SELECT id FROM saas_invoices WHERE id = ? AND tenant_id = ?', [id, tenantId]) as any;
    if (existing.length === 0) {
      return NextResponse.json({ message: 'Invoice not found or unauthorized' }, { status: 403 });
    }

    // Update Invoice
    await db.query(
      'UPDATE saas_invoices SET customer_id = ?, invoice_number = ?, date = ?, due_date = ?, total_amount = ?, status = ? WHERE id = ? AND tenant_id = ?',
      [customer_id, invoice_number, date, due_date, total_amount, status, id, tenantId]
    );

    // Delete existing items
    await db.query('DELETE FROM saas_invoice_items WHERE invoice_id = ?', [id]);

    // Insert Line Items again
    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_invoice_items (invoice_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [id, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ message: 'Invoice updated successfully' });
  } catch (error) {
    console.error('Error updating invoice:', error);
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
      return NextResponse.json({ message: 'Invoice ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_invoices WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Invoice deleted successfully' });
  } catch (error) {
    console.error('Error deleting invoice:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
