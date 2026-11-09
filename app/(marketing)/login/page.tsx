import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import { Suspense } from "react";
import LoginForm from "./LoginForm";

export default async function LoginPage() {
  const session = await getServerSession(authOptions);

  if (session?.user) {
    const tenantSlug = (session.user as any).tenantSlug || 'dashboard';
    redirect(`/${tenantSlug}`);
  }

  return (
    <div className="auth-container">
      <Suspense fallback={<div className="auth-card" style={{ textAlign: 'center', padding: '3rem' }}>Loading sign in...</div>}>
        <LoginForm />
      </Suspense>
    </div>
  );
}
