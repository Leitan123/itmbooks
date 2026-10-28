import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../auth/[...nextauth]/route';
import { db } from '../../../lib/db';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions);
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = (session.user as any).tenantId;

    const [rows] = await db.query(
      'SELECT id, name, address, tel, mobile, whatsapp, logo_url, theme_color FROM tenants WHERE id = ?',
      [tenantId]
    ) as any;

    if (!rows || rows.length === 0) {
      return NextResponse.json({ error: 'Tenant not found' }, { status: 404 });
    }

    return NextResponse.json(rows[0]);
  } catch (error) {
    console.error('Error fetching settings:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(request: Request) {
  try {
    const session = await getServerSession(authOptions);
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = (session.user as any).tenantId;
    const body = await request.json();
    const { name, address, tel, mobile, whatsapp, logo_url, theme_color } = body;

    if (!name) {
      return NextResponse.json({ error: 'Company name is required' }, { status: 400 });
    }

    await db.query(
      `UPDATE tenants 
       SET name = ?, address = ?, tel = ?, mobile = ?, whatsapp = ?, logo_url = ?, theme_color = ? 
       WHERE id = ?`,
      [name, address || null, tel || null, mobile || null, whatsapp || null, logo_url || null, theme_color || null, tenantId]
    );

    return NextResponse.json({ success: true, message: 'Settings updated successfully' });
  } catch (error) {
    console.error('Error updating settings:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
