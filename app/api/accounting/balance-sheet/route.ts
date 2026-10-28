import { NextResponse } from 'next/server';
import { getServerSession } from 'next-auth';
import { authOptions } from '../../auth/[...nextauth]/route';
import { getBalanceSheetData } from '../../../../lib/accounting';

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions) as any;
    if (!session || !session.user) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const tenantId = session.user.tenantId ? parseInt(session.user.tenantId) : 1000;
    const url = new URL(request.url);
    const asOfDate = url.searchParams.get('as_of') || undefined;

    const data = await getBalanceSheetData(tenantId, asOfDate);
    return NextResponse.json(data);
  } catch (error: any) {
    console.error('Error fetching balance sheet:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}
