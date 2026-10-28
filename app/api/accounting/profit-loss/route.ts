import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../auth/[...nextauth]/route';
import { getProfitAndLossData } from '../../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
    const url = new URL(request.url);
    const fromDate = url.searchParams.get('from_date') || undefined;
    const toDate = url.searchParams.get('to_date') || undefined;

    const data = await getProfitAndLossData(tenantId, fromDate, toDate);
    return NextResponse.json(data);
  } catch (error: any) {
    console.error('Error fetching P&L statement:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}
