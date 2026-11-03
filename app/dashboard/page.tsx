import { getServerSession } from 'next-auth';
import { redirect } from 'next/navigation';
import { authOptions } from '../api/auth/[...nextauth]/route';

export default async function DashboardRedirect() {
  const session = await getServerSession(authOptions);

  if (session?.user && (session.user as any).tenantSlug) {
    redirect(`/${(session.user as any).tenantSlug}`);
  }

  redirect('/login');
}
