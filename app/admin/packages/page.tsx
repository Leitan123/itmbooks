import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";

export default async function PackagesDashboard() {
  const session = await getServerSession(authOptions);

  if (!session || (session.user as any).role !== "admin") {
    redirect("/");
  }

  let packages = [];
  try {
    const { db } = await import("../../../lib/db");
    const [rows] = await db.query("SELECT * FROM packages ORDER BY price ASC") as any;
    packages = rows || [];
  } catch (error) {
    console.error("Failed to load packages", error);
    // Fallback mockup data
    packages = [
      { id: 1, name: "Free Tier", price: "0.00", billing_cycle: "monthly", features: '["1 user", "Basic accounting"]' },
      { id: 2, name: "Pro", price: "29.99", billing_cycle: "monthly", features: '["5 users", "Advanced reports", "Invoicing"]' },
      { id: 3, name: "Enterprise", price: "99.99", billing_cycle: "monthly", features: '["Unlimited users", "API Access", "Custom branding"]' },
    ];
  }

  return (
    <div className="py-8 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <div className="mb-8 md:flex md:items-center md:justify-between">
          <div className="flex-1 min-w-0">
            <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              Packages & Pricing
            </h2>
            <p className="mt-1 text-sm text-gray-500">
              Manage the subscription tiers available to your customers.
            </p>
          </div>
          <div className="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
              Create New Package
            </button>
          </div>
        </div>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {packages.map((pkg: any) => (
            <div key={pkg.id} className="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
              <div className="px-4 py-5 sm:p-6">
                <h3 className="text-lg leading-6 font-medium text-gray-900">{pkg.name}</h3>
                <div className="mt-2 text-3xl font-extrabold text-gray-900">
                  ${pkg.price}
                  <span className="text-sm font-medium text-gray-500">/{pkg.billing_cycle}</span>
                </div>
                <ul className="mt-6 space-y-4">
                  {JSON.parse(pkg.features || '[]').map((feature: string, idx: number) => (
                    <li key={idx} className="flex items-start">
                      <div className="flex-shrink-0">
                        <svg className="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                        </svg>
                      </div>
                      <p className="ml-3 text-sm text-gray-700">{feature}</p>
                    </li>
                  ))}
                </ul>
              </div>
              <div className="bg-gray-50 px-4 py-4 sm:px-6">
                <div className="text-sm">
                  <a href="#" className="font-medium text-indigo-600 hover:text-indigo-500">
                    Edit Package &rarr;
                  </a>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
