import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";
import SignupForm from "./SignupForm";

export default async function SignupPage() {
  const session = await getServerSession(authOptions);

  if (session?.user) {
    const tenantSlug = (session.user as any).tenantSlug || 'dashboard';
    redirect(`/${tenantSlug}`);
  }

  return (
    <div className="auth-container">
      <SignupForm />
    </div>
  );
}
