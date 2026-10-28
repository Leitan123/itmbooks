import Link from 'next/link';
import './Footer.css';

export default function Footer() {
  return (
    <footer className="site-footer">
      <div className="container">
        <div className="footer-grid">
          <div className="footer-column">
            <h3>Product</h3>
            <ul>
              <li><Link href="/accounting">Accounting</Link></li>
              <li><Link href="/invoicing">Invoicing</Link></li>
              <li><Link href="/inventory">Inventory</Link></li>
              <li><Link href="/expenses">Expenses</Link></li>
              <li><Link href="/banking">Banking</Link></li>
              <li><Link href="/projects">Projects</Link></li>
              <li><Link href="/reports">Reports</Link></li>
              <li><Link href="/automation">Automation</Link></li>
              <li><Link href="/ai">AI</Link></li>
            </ul>
          </div>
          <div className="footer-column">
            <h3>Solutions</h3>
            <ul>
              <li><Link href="/solutions/small-business">Small Business</Link></li>
              <li><Link href="/solutions/retail">Retail</Link></li>
              <li><Link href="/solutions/wholesale">Wholesale</Link></li>
              <li><Link href="/solutions/manufacturing">Manufacturing</Link></li>
              <li><Link href="/solutions/services">Services</Link></li>
              <li><Link href="/solutions/accountants">Accountants</Link></li>
            </ul>
          </div>
          <div className="footer-column">
            <h3>Resources</h3>
            <ul>
              <li><Link href="/blog">Blog</Link></li>
              <li><Link href="/help">Help Center</Link></li>
              <li><Link href="/guides">Guides</Link></li>
              <li><Link href="/documentation">Documentation</Link></li>
              <li><Link href="/faq">FAQs</Link></li>
            </ul>
          </div>
          <div className="footer-column">
            <h3>Company</h3>
            <ul>
              <li><Link href="/about">About</Link></li>
              <li><Link href="/contact">Contact</Link></li>
              <li><Link href="/careers">Careers</Link></li>
              <li><Link href="/partners">Partners</Link></li>
            </ul>
          </div>
          <div className="footer-column">
            <h3>Legal</h3>
            <ul>
              <li><Link href="/privacy">Privacy Policy</Link></li>
              <li><Link href="/terms">Terms of Service</Link></li>
              <li><Link href="/cookies">Cookie Policy</Link></li>
              <li><Link href="/security">Security</Link></li>
            </ul>
          </div>
        </div>
        <div className="footer-bottom">
          <p>© 2026 ITM Books. All rights reserved.</p>
          <p>A product of IT MARTX (PVT) LTD</p>
        </div>
      </div>
    </footer>
  );
}
