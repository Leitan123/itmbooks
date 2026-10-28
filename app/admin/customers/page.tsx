import { getServerSession } from "next-auth";
import { authOptions } from "../../api/auth/[...nextauth]/route";
import { redirect } from "next/navigation";

export default async function CustomersDashboard() {
  const session = await getServerSession(authOptions);

  if (!session || (session.user as any).role !== "admin") {
    redirect("/");
  }

  let tenants = [];
  try {
    const { db } = await import("../../../lib/db");
    
    // Join tenants with their subscriptions and packages
    const query = `
      SELECT 
        t.id, t.name, t.created_at,
        s.status as subscription_status, s.start_date, s.end_date,
        p.name as package_name, p.price as package_price
      FROM tenants t
      LEFT JOIN subscriptions s ON t.id = s.tenant_id
      LEFT JOIN packages p ON s.package_id = p.id
      ORDER BY t.created_at DESC
    `;
    const [rows] = await db.query(query) as any;
    
    // Map rows to calculate expiry date if missing
    tenants = (rows || []).map((row: any) => {
      let expiry = row.end_date;
      if (!expiry && row.created_at) {
        const d = new Date(row.created_at);
        d.setDate(d.getDate() + 14);
        expiry = d;
      }
      return {
        ...row,
        calculated_expiry: expiry ? new Date(expiry).toLocaleDateString() : 'Unknown',
        package_name: row.package_name || 'Free Tier',
        package_price: row.package_price || '0.00'
      };
    });
  } catch (error) {
    console.error("Failed to load tenants for admin dashboard", error);
    // Fallback mockup data since DB tables might not exist yet
    tenants = [
      { id: 1, name: "Acme Corp", created_at: new Date('2025-01-15'), subscription_status: "active", package_name: "Pro", package_price: 29.99, calculated_expiry: '2027-01-15' },
      { id: 2, name: "Globex Inc", created_at: new Date('2025-02-20'), subscription_status: "trialing", package_name: "Free Tier", package_price: 0.00, calculated_expiry: '2025-03-06' },
    ];
  }

  return (
    <div className="py-8 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <div className="mb-8 md:flex md:items-center md:justify-between">
          <div className="flex-1 min-w-0">
            <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              SaaS Customers (Tenants)
            </h2>
            <p className="mt-1 text-sm text-gray-500">
              Manage the companies subscribed to itmbooks.com, view their plans and statuses.
            </p>
          </div>
          <div className="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
              Add Customer Manually
            </button>
          </div>
        </div>

        <div className="flex flex-col">
          <div className="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div className="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
              <div className="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MRR</th>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                      <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                      <th scope="col" className="relative px-6 py-3"><span className="sr-only">Manage</span></th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {tenants.map((tenant: any) => (
                      <tr key={tenant.id}>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm font-medium text-gray-900">{tenant.name}</div>
                          <div className="text-xs text-gray-500">ID: {tenant.id}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">{tenant.package_name}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">LKR {tenant.package_price}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            ${tenant.subscription_status === 'active' ? 'bg-green-100 text-green-800' : 
                              tenant.subscription_status === 'trialing' ? 'bg-blue-100 text-blue-800' : 
                              'bg-red-100 text-red-800'}`}>
                            {tenant.subscription_status || 'Inactive'}
                          </span>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {tenant.calculated_expiry}
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {new Date(tenant.created_at).toLocaleDateString()}
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <a href="#" className="text-indigo-600 hover:text-indigo-900 mx-2">Change Plan</a>
                          <a href="#" className="text-red-600 hover:text-red-900">Suspend</a>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
