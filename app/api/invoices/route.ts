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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
    
    if (!session || !session.user || !session.user.tenantId) {
      return NextResponse.json({ message: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId;
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
