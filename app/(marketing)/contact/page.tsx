import Link from 'next/link';
import '../form.css';

export const metadata = {
  title: 'Contact Us | ITM Books',
};

export default function ContactPage() {
  return (
    <div className="contact-page">
      <div className="form-container large">
        <h1>Contact Our Team</h1>
        <p className="form-subtitle">Have questions about ITM Books? We are here to help.</p>
        
        <div style={{ textAlign: 'center', marginBottom: '2rem', padding: '1.5rem', background: 'var(--bg-color)', borderRadius: '8px', border: '1px solid var(--border-color)' }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.5rem', color: 'var(--text-secondary)' }}>
            <span style={{ fontSize: '1.2rem' }}>📱</span>
            <span style={{ fontSize: '1.1rem' }}>WhatsApp: </span>
            <a href="https://wa.me/94701449779" style={{ color: 'var(--primary-color)', textDecoration: 'none', fontWeight: 'bold', fontSize: '1.2rem' }}>0701449779</a>
          </div>
        </div>
        
        <form>
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="name">Full Name</label>
              <input type="text" id="name" className="form-control" required />
            </div>
            <div className="form-group">
              <label htmlFor="company">Company Name</label>
              <input type="text" id="company" className="form-control" />
            </div>
          </div>

          <div className="form-row">
            <div className="form-group">
              <label htmlFor="email">Email Address</label>
              <input type="email" id="email" className="form-control" required />
            </div>
            <div className="form-group">
              <label htmlFor="phone">Phone Number</label>
              <input type="tel" id="phone" className="form-control" />
            </div>
          </div>

          <div className="form-group">
            <label htmlFor="subject">Subject</label>
            <select id="subject" className="form-control">
              <option value="sales">Sales Inquiry</option>
              <option value="support">Technical Support</option>
              <option value="billing">Billing Question</option>
              <option value="partnership">Partnership</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div className="form-group">
            <label htmlFor="message">Message</label>
            <textarea id="message" className="form-control" required></textarea>
          </div>
          
          <div className="form-actions" style={{ display: 'flex', gap: '1rem' }}>
            <button type="submit" className="btn btn-primary" style={{ flex: 1 }}>Send Message</button>
            <Link href="/demo" className="btn btn-secondary" style={{ flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>Book a Demo</Link>
          </div>
        </form>
      </div>
    </div>
  );
}
