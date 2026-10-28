import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;

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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized', debug_session: session }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
