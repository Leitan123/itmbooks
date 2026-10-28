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

    const [bills] = await db.query(`
      SELECT b.*, v.name as vendor_name 
      FROM saas_bills b
      JOIN saas_vendors v ON b.vendor_id = v.id
      WHERE b.tenant_id = ? 
      ORDER BY b.created_at DESC
    `, [tenantId]) as any;

    // Fetch line items for each bill
    for (let i = 0; i < bills.length; i++) {
      const [items] = await db.query(
        'SELECT * FROM saas_bill_items WHERE bill_id = ?',
        [bills[i].id]
      ) as any;
      bills[i].items = items;
    }

    return NextResponse.json({ bills });
  } catch (error) {
    console.error('Error fetching bills:', error);
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

    const { vendor_id, bill_number, date, due_date, total_amount, status, items } = await request.json();

    if (!vendor_id || !bill_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    // Insert Bill
    const [result] = await db.query(
      'INSERT INTO saas_bills (tenant_id, vendor_id, bill_number, date, due_date, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
      [tenantId, vendor_id, bill_number, date, due_date, total_amount, status || 'Unpaid']
    ) as any;

    const billId = result.insertId;

    // Insert Line Items
    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_bill_items (bill_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [billId, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ 
      message: 'Bill created successfully', 
      bill: { id: billId, bill_number, status } 
    });
  } catch (error) {
    console.error('Error adding bill:', error);
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

    const { id, vendor_id, bill_number, date, due_date, total_amount, status, items } = await request.json();

    if (!id || !vendor_id || !bill_number) {
      return NextResponse.json({ message: 'Missing required fields' }, { status: 400 });
    }

    // Verify bill belongs to tenant
    const [existing] = await db.query('SELECT id FROM saas_bills WHERE id = ? AND tenant_id = ?', [id, tenantId]) as any;
    if (existing.length === 0) {
      return NextResponse.json({ message: 'Bill not found or unauthorized' }, { status: 403 });
    }

    // Update Bill
    await db.query(
      'UPDATE saas_bills SET vendor_id = ?, bill_number = ?, date = ?, due_date = ?, total_amount = ?, status = ? WHERE id = ? AND tenant_id = ?',
      [vendor_id, bill_number, date, due_date, total_amount, status, id, tenantId]
    );

    // Delete existing items
    await db.query('DELETE FROM saas_bill_items WHERE bill_id = ?', [id]);

    // Insert Line Items again
    if (items && Array.isArray(items) && items.length > 0) {
      for (const item of items) {
        await db.query(
          'INSERT INTO saas_bill_items (bill_id, item_id, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)',
          [id, item.item_id, item.quantity, item.unit_price, item.quantity * item.unit_price]
        );
      }
    }

    return NextResponse.json({ message: 'Bill updated successfully' });
  } catch (error) {
    console.error('Error updating bill:', error);
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
      return NextResponse.json({ message: 'Bill ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_bills WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Bill deleted successfully' });
  } catch (error) {
    console.error('Error deleting bill:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
