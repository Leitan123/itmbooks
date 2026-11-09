import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../auth/[...nextauth]/route';
import { getTenantCurrency, setTenantCurrency } from '../../../../lib/tenant-currency';
import { isValidCurrency } from '../../../../lib/currencies';

export async function GET() {
  try {
    const session = await getServerSession(authOptions);
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }
    const tenantId = (session.user as any).tenantId;
    const currency = await getTenantCurrency(tenantId);
    return NextResponse.json({ currency });
  } catch (error) {
    console.error('Error fetching currency:', error);
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
    if (!tenantId) {
      return NextResponse.json({ error: 'Missing tenant' }, { status: 400 });
    }

    const { currency } = await request.json();
    if (!isValidCurrency(currency)) {
      return NextResponse.json({ error: 'Unsupported currency' }, { status: 400 });
    }

    await setTenantCurrency(tenantId, currency);
    return NextResponse.json({ success: true, currency });
  } catch (error) {
    console.error('Error updating currency:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
