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

    const [items] = await db.query(
      'SELECT * FROM saas_items WHERE tenant_id = ? ORDER BY created_at DESC',
      [tenantId]
    );

    return NextResponse.json({ items });
  } catch (error) {
    console.error('Error fetching items:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
    const { name, description, price, stock_quantity } = await request.json();

    if (!name || price === undefined) {
      return NextResponse.json({ message: 'Name and price are required' }, { status: 400 });
    }

    const [result] = await db.query(
      'INSERT INTO saas_items (tenant_id, name, description, price, stock_quantity, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
      [tenantId, name, description, price, stock_quantity || 0]
    ) as any;

    return NextResponse.json({ 
      message: 'Item added successfully', 
      item: { id: result.insertId, name, description, price, stock_quantity } 
    });
  } catch (error) {
    console.error('Error adding item:', error);
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
    const { id, name, description, price, stock_quantity } = await request.json();

    if (!id || !name || price === undefined) {
      return NextResponse.json({ message: 'ID, Name and price are required' }, { status: 400 });
    }

    await db.query(
      'UPDATE saas_items SET name = ?, description = ?, price = ?, stock_quantity = ? WHERE id = ? AND tenant_id = ?',
      [name, description, price, stock_quantity || 0, id, tenantId]
    );

    return NextResponse.json({ message: 'Item updated successfully' });
  } catch (error) {
    console.error('Error updating item:', error);
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
      return NextResponse.json({ message: 'Item ID is required' }, { status: 400 });
    }

    await db.query(
      'DELETE FROM saas_items WHERE id = ? AND tenant_id = ?',
      [id, tenantId]
    );

    return NextResponse.json({ message: 'Item deleted successfully' });
  } catch (error) {
    console.error('Error deleting item:', error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
