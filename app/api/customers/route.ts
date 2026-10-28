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
  
  // Fallback: check query parameter
  const url = new URL(request.url);
  const qTenantId = url.searchParams.get('tenant_id');
  if (qTenantId) return parseInt(qTenantId, 10);

  // Fallback: check referer URL slug
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

    const [customers] = await db.query(
      'SELECT * FROM saas_customers WHERE tenant_id = ? ORDER BY created_at DESC',
      [tenantId]
    );

    return NextResponse.json({ customers });
  } catch (error) {
    console.error('Error fetching customers:', error);
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

    const { name, email, phone, address } = await request.json();

    if (!name) {
      return NextResponse.json({ message: 'Name is required' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_customers (tenant_id, name, email, phone, address, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
      [tenantId, name, email, phone, address]
    ) as any;

    return NextResponse.json({ 
      message: 'Customer added successfully', 
      customer: { id: result.insertId, name, email, phone, address } 
    });
  } catch (error) {
    console.error('Error adding customer:', error);
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

    const { id, name, email, phone, address } = await request.json();

    if (!id || !name) {
      return NextResponse.json({ message: 'ID and Name are required' }, { status: 400 });
    }

    await db.query(
      'UPDATE saas_customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ? AND tenant_id = ?',
      [name, email, phone, address, id, tenantId]
    );

    return NextResponse.json({ message: 'Customer updated successfully' });
  } catch (error) {
    console.error('Error updating customer:', error);
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
      return NextResponse.json({ message: 'Customer ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_customers WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Customer deleted successfully' });
  } catch (error) {
    console.error('Error deleting customer:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
