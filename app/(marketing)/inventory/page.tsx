import Link from 'next/link';
import '../feature.css';

export const metadata = {
  title: 'Inventory Management Software | ITM Books',
  description: 'Know exactly what you have in stock. Track stock, warehouses, purchases, and sales in real-time.',
};

export default function InventoryPage() {
  return (
    <div className="feature-page">
      <section className="feature-hero">
        <div className="container text-center">
          <h1>Know exactly what you have in stock</h1>
          <p className="feature-subtitle">
            Track stock levels, manage multiple warehouses, and automate low-stock alerts so you never run out of your best-sellers.
          </p>
          <div className="feature-ctas">
            <Link href="/signup" className="btn btn-primary btn-large">Explore Inventory</Link>
          </div>
        </div>
      </section>

      <section className="feature-details">
        <div className="container">
          <div className="feature-grid">
            <div className="feature-card">
              <h3>Real-time Stock Tracking</h3>
              <p>Your inventory levels update automatically the moment a sale is made or a purchase order is received.</p>
            </div>
            <div className="feature-card">
              <h3>Multiple Warehouses</h3>
              <p>Manage and track stock across different locations, stores, or warehouses from a single dashboard.</p>
            </div>
            <div className="feature-card">
              <h3>Low-Stock Alerts</h3>
              <p>Set minimum reorder points and get notified instantly when it is time to replenish your inventory.</p>
            </div>
            <div className="feature-card">
              <h3>Barcode Support</h3>
              <p>Speed up checkout and stock-taking processes with integrated barcode scanning functionality.</p>
            </div>
            <div className="feature-card">
              <h3>Batch & Expiry Tracking</h3>
              <p>Keep track of batch numbers and expiration dates to ensure product quality and compliance.</p>
            </div>
            <div className="feature-card">
              <h3>Stock Transfers & Adjustments</h3>
              <p>Easily transfer goods between locations and make manual adjustments for damages or discrepancies.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
