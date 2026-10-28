-- ==========================================================
-- ITM Books SaaS Tables and Relations
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------
-- 1. Tenants (Companies / Workspaces)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL UNIQUE,
  `account_type` VARCHAR(50) NOT NULL DEFAULT 'Invoice',
  `subscription_type` VARCHAR(50) NOT NULL DEFAULT 'trialing',
  `address` TEXT DEFAULT NULL,
  `tel` VARCHAR(50) DEFAULT NULL,
  `mobile` VARCHAR(50) DEFAULT NULL,
  `whatsapp` VARCHAR(50) DEFAULT NULL,
  `logo_url` VARCHAR(500) DEFAULT NULL,
  `theme_color` VARCHAR(50) DEFAULT '#4f46e5',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_tenant_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Packages (Subscription Plans)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `packages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` VARCHAR(50) NOT NULL DEFAULT 'monthly',
  `features` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. Users (Authentication & Tenant membership)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'user',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_tenant` (`tenant_id`),
  INDEX `idx_user_email` (`email`),
  CONSTRAINT `fk_users_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. Subscriptions (Tenant plan assignments)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `package_id` INT DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'trialing',
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_subscriptions_tenant` (`tenant_id`),
  INDEX `idx_subscriptions_package` (`package_id`),
  CONSTRAINT `fk_subscriptions_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_subscriptions_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. SaaS Customers
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_customers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_customers_tenant` (`tenant_id`),
  CONSTRAINT `fk_saas_customers_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. SaaS Vendors
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_vendors` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_vendors_tenant` (`tenant_id`),
  CONSTRAINT `fk_saas_vendors_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. SaaS Items (Products & Services)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_items_tenant` (`tenant_id`),
  CONSTRAINT `fk_saas_items_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. SaaS Invoices
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_invoices` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `invoice_number` VARCHAR(100) NOT NULL,
  `date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Draft',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_invoices_tenant` (`tenant_id`),
  INDEX `idx_saas_invoices_customer` (`customer_id`),
  CONSTRAINT `fk_saas_invoices_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `saas_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 9. SaaS Invoice Items
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_invoice_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_id` INT NOT NULL,
  `item_id` INT DEFAULT NULL,
  `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_inv_items_invoice` (`invoice_id`),
  INDEX `idx_saas_inv_items_item` (`item_id`),
  CONSTRAINT `fk_saas_inv_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `saas_invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_inv_items_item` FOREIGN KEY (`item_id`) REFERENCES `saas_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. SaaS Bills
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_bills` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `vendor_id` INT NOT NULL,
  `bill_number` VARCHAR(100) NOT NULL,
  `date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Unpaid',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_bills_tenant` (`tenant_id`),
  INDEX `idx_saas_bills_vendor` (`vendor_id`),
  CONSTRAINT `fk_saas_bills_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_bills_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `saas_vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 11. SaaS Bill Items
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_bill_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bill_id` INT NOT NULL,
  `item_id` INT DEFAULT NULL,
  `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_bill_items_bill` (`bill_id`),
  INDEX `idx_saas_bill_items_item` (`item_id`),
  CONSTRAINT `fk_saas_bill_items_bill` FOREIGN KEY (`bill_id`) REFERENCES `saas_bills` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_bill_items_item` FOREIGN KEY (`item_id`) REFERENCES `saas_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 12. SaaS Expenses
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_expenses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `date` DATE NOT NULL,
  `reference_number` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_expenses_tenant` (`tenant_id`),
  CONSTRAINT `fk_saas_expenses_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 13. SaaS Payments Received
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saas_payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `invoice_id` INT DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_date` DATE NOT NULL,
  `payment_mode` VARCHAR(50) NOT NULL,
  `reference_number` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_saas_payments_tenant` (`tenant_id`),
  INDEX `idx_saas_payments_customer` (`customer_id`),
  INDEX `idx_saas_payments_invoice` (`invoice_id`),
  CONSTRAINT `fk_saas_payments_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_payments_customer` FOREIGN KEY (`customer_id`) REFERENCES `saas_customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saas_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `saas_invoices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 14. Accounting Engine: Account Types
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `account_types` (
  `AccountTypeID` INT NOT NULL AUTO_INCREMENT,
  `AccountTypeName` VARCHAR(100) NOT NULL,
  `CreatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AccountTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 15. Accounting Engine: Chart of Accounts
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chart_of_accounts` (
  `AccountID` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT DEFAULT NULL,
  `AccountCode` VARCHAR(20) NOT NULL,
  `AccountName` VARCHAR(255) NOT NULL,
  `AccountTypeID` INT NOT NULL,
  `OpeningBalance` DECIMAL(18,2) DEFAULT 0.00,
  `CurrentBalance` DECIMAL(18,2) DEFAULT 0.00,
  `Status` TINYINT(1) DEFAULT 1,
  `CreatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AccountID`),
  INDEX `idx_coa_tenant` (`tenant_id`),
  INDEX `idx_coa_type` (`AccountTypeID`),
  CONSTRAINT `fk_coa_type` FOREIGN KEY (`AccountTypeID`) REFERENCES `account_types` (`AccountTypeID`),
  CONSTRAINT `fk_coa_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 16. Accounting Engine: Journal Entries (Master)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `journal_entries` (
  `JournalID` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT DEFAULT NULL,
  `JournalDate` DATE NOT NULL,
  `ReferenceNo` VARCHAR(100) DEFAULT NULL,
  `Description` TEXT DEFAULT NULL,
  `CreatedBy` INT DEFAULT NULL,
  `CreatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`JournalID`),
  INDEX `idx_je_tenant` (`tenant_id`),
  CONSTRAINT `fk_je_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 17. Accounting Engine: Journal Entry Details (Lines)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `journal_entry_details` (
  `JournalDetailID` INT NOT NULL AUTO_INCREMENT,
  `JournalID` INT NOT NULL,
  `AccountID` INT NOT NULL,
  `DebitAmount` DECIMAL(18,2) DEFAULT 0.00,
  `CreditAmount` DECIMAL(18,2) DEFAULT 0.00,
  `Description` TEXT DEFAULT NULL,
  PRIMARY KEY (`JournalDetailID`),
  INDEX `idx_jed_journal` (`JournalID`),
  INDEX `idx_jed_account` (`AccountID`),
  CONSTRAINT `fk_jed_journal` FOREIGN KEY (`JournalID`) REFERENCES `journal_entries` (`JournalID`) ON DELETE CASCADE,
  CONSTRAINT `fk_jed_account` FOREIGN KEY (`AccountID`) REFERENCES `chart_of_accounts` (`AccountID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 18. Accounting Engine: Banks
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banks` (
  `BankID` INT NOT NULL AUTO_INCREMENT,
  `tenant_id` INT DEFAULT NULL,
  `BankCode` VARCHAR(20) DEFAULT NULL,
  `BankName` VARCHAR(150) NOT NULL,
  `AccountName` VARCHAR(150) DEFAULT NULL,
  `AccountNumber` VARCHAR(100) DEFAULT NULL,
  `Branch` VARCHAR(100) DEFAULT NULL,
  `OpeningBalance` DECIMAL(18,2) DEFAULT 0.00,
  `IsDeleted` TINYINT(1) DEFAULT 0,
  `AccountID` INT DEFAULT NULL,
  `CreatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`BankID`),
  INDEX `idx_banks_tenant` (`tenant_id`),
  INDEX `idx_banks_account` (`AccountID`),
  CONSTRAINT `fk_banks_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 19. Seed Data
-- ----------------------------------------------------------

-- Default account types
INSERT INTO `account_types` (`AccountTypeID`, `AccountTypeName`) VALUES
(1, 'Assets'),
(2, 'Liabilities'),
(3, 'Equity'),
(4, 'Income'),
(5, 'Expenses')
ON DUPLICATE KEY UPDATE `AccountTypeName`=VALUES(`AccountTypeName`);


-- Default packages
INSERT INTO `packages` (`id`, `name`, `price`, `billing_cycle`, `features`) VALUES
(1, 'Free Tier', 0.00, 'monthly', '[\"1 user\", \"Basic accounting\", \"14-Day Trial\"]'),
(2, 'Pro', 29.99, 'monthly', '[\"5 users\", \"Advanced reports\", \"Invoicing\", \"Bank Integration\"]'),
(3, 'Enterprise', 99.99, 'monthly', '[\"Unlimited users\", \"API Access\", \"Custom branding\", \"Dedicated Support\"]')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price`=VALUES(`price`);

-- Default tenants
INSERT INTO `tenants` (`id`, `name`, `slug`, `account_type`, `subscription_type`, `address`, `tel`, `mobile`, `whatsapp`, `theme_color`) VALUES
(1, 'Acme Corp', 'acme-corp', 'Invoice', 'active', '123 Business Way, Colombo', '+94 11 2345678', '+94 77 1234567', '+94 77 1234567', '#4f46e5'),
(999, 'Demo Company', 'demo-company', 'Invoice', 'trialing', '45 Market Street, Colombo', '+94 11 7654321', '+94 71 9876543', '+94 71 9876543', '#2563eb'),
(1000, 'Super Admin Org', 'admin', 'Accounting', 'active', 'ITM Books HQ', '+94 11 0000000', '+94 77 0000000', '+94 77 0000000', '#0f172a')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Default users
INSERT INTO `users` (`id`, `tenant_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `role`) VALUES
(999, 999, 'Demo', 'User', 'demo@itmbooks.com', 'demo123', '0771234567', 'admin'),
(1000, 1000, 'Super', 'Admin', 'superadmin@itmbooks.com', 'admin123', '0770000000', 'admin')
ON DUPLICATE KEY UPDATE `email`=VALUES(`email`);

-- Default subscriptions
INSERT INTO `subscriptions` (`id`, `tenant_id`, `package_id`, `status`, `start_date`, `end_date`) VALUES
(1, 1, 2, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR)),
(2, 999, 1, 'trialing', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY)),
(3, 1000, 3, 'active', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 5 YEAR))
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

SET FOREIGN_KEY_CHECKS = 1;
