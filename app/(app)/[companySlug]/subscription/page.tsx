import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";
import "../../../(marketing)/form.css";
import CybersourceCheckout from './CybersourceCheckout';

export default async function SubscriptionPage({ params }: { params: { companySlug: string } }) {
  const session = await getServerSession(authOptions);

  if (!session || !session.user) {
    redirect("/login");
  }

  const tenantId = (session.user as any).tenantId;

  let expiryDate = 'Not available';
  let status = "active";
  let planName = 'No Active Plan';
  let accountType = 'Invoice';
  let billingCycle = 'Monthly';
  let price = '0.00';
  
  try {
    const [rows] = await db.query(
      `SELECT 
         t.account_type,
         t.created_at,
         s.end_date, 
         s.status,
         p.name as package_name,
         p.billing_cycle,
         p.price
       FROM tenants t
       LEFT JOIN subscriptions s ON t.id = s.tenant_id
       LEFT JOIN packages p ON s.package_id = p.id
       WHERE t.id = ?
       ORDER BY s.id DESC LIMIT 1`,
      [tenantId]
    ) as any;
    
    if (rows && rows.length > 0) {
      const data = rows[0];
      
      let calculatedExpiry = data.end_date;
      if (!calculatedExpiry && data.created_at) {
        // Calculate 14 days from tenant creation if no subscription end_date exists
        const creationDate = new Date(data.created_at);
        creationDate.setDate(creationDate.getDate() + 14);
        calculatedExpiry = creationDate;
      }

      expiryDate = calculatedExpiry ? new Date(calculatedExpiry).toLocaleDateString() : 'Unknown';
      status = data.status || 'trialing';
      planName = data.package_name || 'Free Tier (14-Day Trial)';
      accountType = data.account_type || 'Invoice';
      billingCycle = data.billing_cycle ? data.billing_cycle.charAt(0).toUpperCase() + data.billing_cycle.slice(1) : 'Monthly';
      price = data.price || '0.00';
    }
  } catch (error) {
    console.error("Failed to load subscription details:", error);
    expiryDate = 'Unknown';
  }

  return (
    <>
      <div className="page-header">
        <h1 className="page-title">Subscription Management</h1>
      </div>

      <div className="card" style={{ maxWidth: '600px', margin: '0 auto', marginTop: '20px', padding: '30px' }}>
        <h2 style={{ fontSize: '1.25rem', fontWeight: 600, color: '#1e293b', marginBottom: '20px' }}>
          Current Plan Details
        </h2>
        
        <div style={{ marginBottom: '20px', padding: '15px', backgroundColor: '#f8fafc', borderRadius: '8px', border: '1px solid #e2e8f0' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Account Type:</span>
            <span style={{ fontWeight: 600, color: '#0f172a' }}>{accountType}</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Plan:</span>
            <span style={{ fontWeight: 600, color: '#0f172a' }}>{planName}</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Billing Cycle:</span>
            <span style={{ fontWeight: 600, color: '#0f172a' }}>{billingCycle} Subscription</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Amount:</span>
            <span style={{ fontWeight: 600, color: '#0f172a' }}>LKR {price}</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Status:</span>
            <span style={{ 
              fontWeight: 600, 
              color: status === 'active' ? '#166534' : '#991b1b',
              backgroundColor: status === 'active' ? '#dcfce7' : '#fee2e2',
              padding: '2px 8px',
              borderRadius: '12px',
              textTransform: 'capitalize'
            }}>
              {status}
            </span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '1.1rem', marginTop: '10px', paddingTop: '10px', borderTop: '1px dashed #cbd5e1' }}>
            <span style={{ color: '#64748b', fontWeight: 500 }}>Expiry Date:</span>
            <span style={{ fontWeight: 700, color: '#0f172a' }}>{expiryDate}</span>
          </div>
        </div>

        <CybersourceCheckout amount={price} />
      </div>
    </>
  );
}
