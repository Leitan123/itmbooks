import Link from 'next/link';
import './Header.css';

export default function Header() {
  return (
    <header className="site-header">
      <div className="container header-inner">
        <div className="logo">
          <Link href="/">ITM BOOKS</Link>
        </div>
        <nav className="desktop-nav">
          <ul className="nav-links">
            <li className="dropdown">
              <Link href="#">Product ▾</Link>
              <ul className="dropdown-menu">
                <li><Link href="/invoicing">Invoicing</Link></li>
                <li><Link href="/accounting">Accounting</Link></li>
                <li><Link href="/inventory">Inventory</Link></li>
              </ul>
            </li>
            <li className="dropdown">
              <Link href="/solutions">Solutions ▾</Link>
              <ul className="dropdown-menu">
                <li><Link href="/solutions/small-business">Small Businesses</Link></li>
                <li><Link href="/solutions/sme">SMEs</Link></li>
                <li><Link href="/solutions/retail">Retail</Link></li>
                <li><Link href="/solutions/wholesale">Wholesale</Link></li>
                <li><Link href="/solutions/manufacturing">Manufacturing</Link></li>
                <li><Link href="/solutions/services">Service Businesses</Link></li>
                <li><Link href="/solutions/freelancers">Freelancers</Link></li>
                <li><Link href="/solutions/accountants">Accountants</Link></li>
                <li><Link href="/solutions/professional-services">Professional Services</Link></li>
              </ul>
            </li>
            <li className="dropdown">
              <Link href="/resources">Resources ▾</Link>
              <ul className="dropdown-menu">
                <li><Link href="/blog">Blog</Link></li>
                <li><Link href="/guides">Guides</Link></li>
                <li><Link href="/help">Help Center</Link></li>
                <li><Link href="/documentation">Documentation</Link></li>
                <li><Link href="/faq">FAQs</Link></li>
                <li><Link href="/accounting-resources">Accounting Resources</Link></li>
              </ul>
            </li>
            <li><Link href="/pricing">Pricing</Link></li>
            <li className="dropdown">
              <Link href="/about">Company ▾</Link>
              <ul className="dropdown-menu">
                <li><Link href="/about">About</Link></li>
                <li><Link href="/contact">Contact</Link></li>
                <li><Link href="/careers">Careers</Link></li>
                <li><Link href="/partners">Partners</Link></li>
              </ul>
            </li>
          </ul>
        </nav>
        <div className="header-actions">
          <Link href="/login" className="btn btn-secondary">Login</Link>
          <Link href="/signup" className="btn btn-primary">Start Free</Link>
        </div>
      </div>
    </header>
  );
}
