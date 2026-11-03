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

    const [quotations] = await db.query(`
      SELECT q.*, c.name as customer_name 
      FROM saas_quotations q
      JOIN saas_customers c ON q.customer_id = c.id
      WHERE q.tenant_id = ? 
      ORDER BY q.created_at DESC
    `, [tenantId]) as any;

    for (let i = 0; i < quotations.length; i++) {
      const [items] = await db.query(
        'SELECT * FROM saas_quotation_items WHERE quotation_id = ?',
        [quotations[i].id]
      ) as any;
      quotations[i].items = items;
    }

    return NextResponse.json({ quotations });
  } catch (error) {
    console.error('Error fetching quotations:', error);
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

    const { customer_id, quotation_number, date, expiry_date, total_amount, status, notes, items } = await request.json();

    if (!customer_id || !quotation_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_quotations (tenant_id, customer_id, quotation_number, date, expiry_date, total_amount, status, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, customer_id, quotation_number, date, expiry_date, total_amount, status || 'Draft', notes || '']
    ) as any;

    const quotationId = result.insertId;

    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_quotation_items (quotation_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [quotationId, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ 
      message: 'Quotation created successfully', 
      quotation: { id: quotationId, quotation_number, status } 
    });
  } catch (error) {
    console.error('Error adding quotation:', error);
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

    const { id, customer_id, quotation_number, date, expiry_date, total_amount, status, notes, items } = await request.json();

    if (!id || !customer_id || !quotation_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    const [existing] = await db.query('SELECT id FROM saas_quotations WHERE id = ? AND tenant_id = ?', [id, tenantId]) as any;
    if (existing.length === 0) {
      return NextResponse.json({ message: 'Quotation not found or unauthorized' }, { status: 403 });
    }

    await db.query(
      'UPDATE saas_quotations SET customer_id = ?, quotation_number = ?, date = ?, expiry_date = ?, total_amount = ?, status = ?, notes = ? WHERE id = ? AND tenant_id = ?',
      [customer_id, quotation_number, date, expiry_date, total_amount, status, notes || '', id, tenantId]
    );

    await db.query('DELETE FROM saas_quotation_items WHERE quotation_id = ?', [id]);

    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_quotation_items (quotation_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [id, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ message: 'Quotation updated successfully' });
  } catch (error) {
    console.error('Error updating quotation:', error);
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
      return NextResponse.json({ message: 'Quotation ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_quotations WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Quotation deleted successfully' });
  } catch (error) {
    console.error('Error deleting quotation:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
