<!-- ========== Left Sidebar Start ========== -->
<style>
/* Custom styled submenu section titles for a professional, clean Zoho/QuickBooks look */
.submenu-section-title {
    padding: 14px 20px 6px 60px !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    color: #a0aec0 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.06em !important;
    pointer-events: none;
    cursor: default;
    display: block;
}

.submenu-section-prof {
    border-top: 1px solid #edf2f9 !important;
    margin-top: 6px !important;
    padding-top: 16px !important;
}

/* Premium micro-animations for submenu items */
#sidebar-menu ul ul a {
    padding: 8px 20px 8px 60px !important;
    font-size: 13px !important;
    position: relative;
    transition: all 0.2s ease-in-out !important;
}

#sidebar-menu ul ul a:hover {
    padding-left: 65px !important; /* Premium slide-right hover effect */
    color: #64b0f2 !important;
    background: rgba(100, 176, 242, 0.05) !important;
    border-radius: 4px;
}

/* Sleek bullet point indicator on hover */
#sidebar-menu ul ul a::before {
    content: '';
    position: absolute;
    left: 45px;
    top: 50%;
    transform: translateY(-50%) scale(0);
    width: 4px;
    height: 4px;
    background-color: #64b0f2;
    border-radius: 50%;
    transition: transform 0.2s ease-in-out;
}

#sidebar-menu ul ul a:hover::before {
    transform: translateY(-50%) scale(1);
}

/* Adjust layout when sidebar is collapsed (enlarged wrapper) */
#wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > ul .submenu-section-title {
    padding-left: 20px !important;
}

#wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > ul .submenu-section-prof {
    border-top: none !important;
    margin-top: 0 !important;
    padding-top: 10px !important;
}

#wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > ul a {
    padding-left: 15px !important;
}

#wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > ul a:hover {
    padding-left: 20px !important;
}

#wrapper.enlarged .left.side-menu #sidebar-menu ul > li:hover > ul a::before {
    left: 6px;
}
</style>
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>

                <!-- Dashboard -->
                <li class="has_sub">
                    <a href="<?= base_url('admin') ?>" class="waves-effect">
                        <i class="zmdi zmdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Sales -->
                <?php if (hasPermissions('customer_list') || hasPermissions('customer_add') || hasPermissions('sales_list') || hasPermissions('sales_add') || hasPermissions('customer_payment_list') || hasPermissions('customer_payment_add') || logged('role') == 1 || logged('role') == 2 || logged('role') == 4): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-social-usd"></i><span>Sales</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li class="submenu-section-title">Customers</li>
                        <?php if (hasPermissions('customer_add')): ?>
                        <li><a href="<?= base_url('admin/customer/create') ?>"><span>Add Customer</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('customer_list')): ?>
                        <li><a href="<?= base_url('admin/customer/') ?>"><span>Manage Customers</span></a></li>
                        <?php endif; ?>

                        <?php if (logged('role') == 1 || logged('role') == 2 || logged('role') == 4): ?>
                        <li class="submenu-section-title submenu-section-prof">Estimates & Orders</li>
                        <li><a href="<?= base_url('admin/quotation/create') ?>"><span>Add Quotation</span></a></li>
                        <li><a href="<?= base_url('admin/quotation/') ?>"><span>Manage Quotations</span></a></li>
                        <li><a href="<?= base_url('admin/job_card/create') ?>"><span>Add Job Card</span></a></li>
                        <li><a href="<?= base_url('admin/job_card/') ?>"><span>Manage Job Cards</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Invoices & Payments</li>
                        <?php if (hasPermissions('sales_add')): ?>
                        <li><a href="<?= base_url('admin/sales/pos') ?>" style="border-left: 3px solid #1abc9c; background: rgba(26, 188, 156, 0.05);"><span style="font-weight:bold; color:#1abc9c;"><i class="fa fa-desktop" style="margin-right:5px;"></i> POS Terminal</span></a></li>
                        <li><a href="<?= base_url('admin/sales/create') ?>"><span>Add Sale</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('sales_list')): ?>
                        <li><a href="<?= base_url('admin/sales/') ?>"><span>Manage Sales</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('customer_payment_add')): ?>
                        <li><a href="<?= base_url('admin/customer_payments/create') ?>"><span>Receive Payment</span></a></li>
                        <?php endif; ?>
                         <?php if (hasPermissions('customer_payment_list')): ?>
                        <li><a href="<?= base_url('admin/customer_payments/') ?>"><span>Manage Payments</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Returns</li>
                        <?php if (hasPermissions('sales_add') || logged('role') == 1 || logged('role') == 2 || logged('role') == 4): ?>
                        <li><a href="<?= base_url('admin/Sales_returns/create') ?>"><span>Add Sales Return</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('sales_list') || logged('role') == 1 || logged('role') == 2 || logged('role') == 4): ?>
                        <li><a href="<?= base_url('admin/Sales_returns/') ?>"><span>Manage Sales Returns</span></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Purchases -->
                <?php if (hasPermissions('vendor_list') || hasPermissions('vendor_add') || hasPermissions('purchases_list') || hasPermissions('purchases_add') || hasPermissions('vendor_payment_list') || hasPermissions('vendor_payment_add')): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-ios7-cart"></i><span>Purchases</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li class="submenu-section-title">Vendors</li>
                        <?php if (hasPermissions('vendor_add')): ?>
                        <li><a href="<?= base_url('admin/Vendor/create') ?>"><span>Add Vendor</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('vendor_list')): ?>
                        <li><a href="<?= base_url('admin/Vendor/') ?>"><span>Manage Vendors</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Purchase Workflow</li>
                        
                        <!-- Purchase Order -->
                        <li class="has_sub_sub">
                            <a href="javascript:void(0);" style="font-weight: 500; padding-left: 20px; display: block;"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Purchase Order <span class="menu-arrow" style="margin-top: 2px;"></span></a>
                            <ul style="padding-left: 15px; display: none;">
                                <li><a href="<?= base_url('admin/purchase_orders/create') ?>">Add PO</a></li>
                                <li><a href="<?= base_url('admin/purchase_orders') ?>">Manage POs</a></li>
                            </ul>
                        </li>

                        <!-- GRN -->
                        <li class="has_sub_sub" style="margin-top: 5px;">
                            <a href="javascript:void(0);" style="font-weight: 500; padding-left: 20px; display: block;"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> GRN <span class="menu-arrow" style="margin-top: 2px;"></span></a>
                            <ul style="padding-left: 15px; display: none;">
                                <li><a href="<?= base_url('admin/grn/create') ?>">Add GRN</a></li>
                                <li><a href="<?= base_url('admin/grn') ?>">Manage GRNs</a></li>
                            </ul>
                        </li>

                        <!-- Purchase Voucher -->
                        <li class="has_sub_sub" style="margin-top: 5px;">
                            <a href="javascript:void(0);" style="font-weight: 500; padding-left: 20px; display: block;"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Purchase Voucher <span class="menu-arrow" style="margin-top: 2px;"></span></a>
                            <ul style="padding-left: 15px; display: none;">
                                <li><a href="<?= base_url('admin/purchases/create') ?>">Add Voucher</a></li>
                                <li><a href="<?= base_url('admin/purchases') ?>">Manage Vouchers</a></li>
                            </ul>
                        </li>

                        <!-- Pay Invoice -->
                        <li class="has_sub_sub" style="margin-top: 5px;">
                            <a href="javascript:void(0);" style="font-weight: 500; padding-left: 20px; display: block;"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Pay Invoice <span class="menu-arrow" style="margin-top: 2px;"></span></a>
                            <ul style="padding-left: 15px; display: none;">
                                <li><a href="<?= base_url('admin/vendor_payments/create') ?>">Make Payment</a></li>
                                <li><a href="<?= base_url('admin/vendor_payments') ?>">Manage Payments</a></li>
                            </ul>
                        </li>

                        <!-- Purchase Return -->
                        <li class="has_sub_sub" style="margin-top: 5px; margin-bottom: 10px;">
                            <a href="javascript:void(0);" style="font-weight: 500; padding-left: 20px; display: block;"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Purchase Return <span class="menu-arrow" style="margin-top: 2px;"></span></a>
                            <ul style="padding-left: 15px; display: none;">
                                <li><a href="<?= base_url('admin/Purchase_returns/create') ?>">Add Return</a></li>
                                <li><a href="<?= base_url('admin/Purchase_returns') ?>">Manage Returns</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Inventory -->
                <?php if (hasPermissions('item_list') || hasPermissions('item_add') || hasPermissions('category_list') || hasPermissions('category_add') || hasPermissions('brand_list') || hasPermissions('brand_add')): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-android-storage"></i><span>Inventory</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li class="submenu-section-title">Items & Catalog</li>
                        <?php if (hasPermissions('item_add')): ?>
                        <li><a href="<?= base_url('admin/items/create') ?>"><span>Add Item</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('item_list')): ?>
                        <li><a href="<?= base_url('admin/items/') ?>"><span>Manage Items</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Categories & Brands</li>
                        <?php if (hasPermissions('category_add')): ?>
                        <li><a href="<?= base_url('admin/categories/create') ?>"><span>Add Category</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('category_list')): ?>
                        <li><a href="<?= base_url('admin/categories/') ?>"><span>Manage Categories</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('brand_add')): ?>
                        <li><a href="<?= base_url('admin/brand/create') ?>"><span>Add Brand</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('brand_list')): ?>
                        <li><a href="<?= base_url('admin/brand/') ?>"><span>Manage Brands</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Warehouses</li>
                        <li><a href="<?= base_url('admin/warehouse/create') ?>"><span>Add Warehouse</span></a></li>
                        <li><a href="<?= base_url('admin/warehouse/') ?>"><span>Manage Warehouses</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Accounting -->
                <?php if (logged('role') == 1 || logged('role') == 3): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="ion-calculator"></i>
                        <span>Accounting</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li class="submenu-section-title">Masters & Balances</li>
                        <li><a href="<?= base_url('admin/chart_of_accounts/') ?>"><span>Chart Of Accounts</span></a></li>
                        <li><a href="<?= base_url('admin/banks') ?>"><span>Bank Master</span></a></li>
                        <li><a href="<?= base_url('admin/opening_balance') ?>"><span>Opening Balance</span></a></li>

                        <li class="submenu-section-title submenu-section-prof">Daily Transactions</li>
                        <li><a href="<?= base_url('admin/incomes/') ?>"><span>Income</span></a></li>
                        <li><a href="<?= base_url('admin/expenses/') ?>"><span>Expenses</span></a></li>
                        <li><a href="<?= base_url('admin/drawings/') ?>"><span>Drawings / Withdrawals</span></a></li>
                        <li><a href="<?= base_url('admin/journal_entries/') ?>"><span>Journal Entries</span></a></li>
                        <li><a href="<?= base_url('admin/received_cheques') ?>"><span>Received Cheques</span></a></li>
                        <li><a href="<?= base_url('admin/issued_cheques') ?>"><span>Issued Cheques</span></a></li>
                        <li><a href="<?= base_url('admin/bank_rec') ?>"><span>Bank Reconciliation</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Reports -->
                <?php if (hasPermissions('stock_report') || hasPermissions('customer_balance_sheet_report') || hasPermissions('vendor_balance_sheet_report')): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-ios7-browsers"></i><span>Reports</span><span class="menu-arrow"></span></a>
                    <ul>
                        <?php if (logged('role') == 1 || logged('role') == 3): ?>
                        <li class="submenu-section-title">Financial Statements</li>
                        <li><a href="<?= base_url('admin/profit_loss/') ?>"><span>Profit & Loss (P&L)</span></a></li>
                        <li><a href="<?= base_url('admin/balance_sheet/') ?>"><span>Balance Sheet</span></a></li>
                        <li><a href="<?= base_url('admin/cash_flow/') ?>"><span>Cash Flow</span></a></li>
                        <li><a href="<?= base_url('admin/trial_balance/') ?>"><span>Trial Balance</span></a></li>
                        <li><a href="<?= base_url('admin/general_ledger/') ?>"><span>General Ledger</span></a></li>
                        <li><a href="<?= base_url('admin/project_profit_loss/') ?>"><span>Project Profit & Loss</span></a></li>
                        <li><a href="<?= base_url('admin/home/monthly_financials') ?>"><span style="font-weight:bold; color:#1abc9c;">Monthly Financials</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('stock_report') || logged('role') == 1 || logged('role') == 3): ?>
                        <?php if (hasPermissions('stock_report')): ?>
                        <li><a href="<?= base_url('admin/sales/stock') ?>"><span>Stock</span></a></li>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if (logged('role') == 1 || logged('role') == 3 || hasPermissions('customer_balance_sheet_report')): ?>
                        <li class="submenu-section-title submenu-section-prof">Customer (Receivables)</li>
                        <?php if (hasPermissions('customer_balance_sheet_report') || logged('role') == 1 || logged('role') == 3): ?>
                        <li><a href="<?= base_url('admin/customer_payments/balance_sheet') ?>"><span>Customer Balance Sheet</span></a></li>
                        <?php endif; ?>
                        <li><a href="<?= base_url('admin/customer_payments/payment_history') ?>"><span>Customer Payment History</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/ledger') ?>"><span>Customer Ledger</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/statement') ?>"><span>Customer Statement of Accounts</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/invoice_aging') ?>" target="_blank"><span>Invoice Aging</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/receivables_summary') ?>"><span>Receivables Summary</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/daily_cash_collection') ?>"><span>Daily Cash Collection</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/cheque_register') ?>"><span>Cheque Register</span></a></li>
                        <?php endif; ?>

                        <?php if (logged('role') == 1 || logged('role') == 3 || hasPermissions('vendor_balance_sheet_report')): ?>
                        <li class="submenu-section-title submenu-section-prof">Vendor (Payables)</li>
                        <li><a href="<?= base_url('admin/vendor_payments/summary') ?>"><span>Vendor Payment Summary</span></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Employee / HR -->
                <?php if (logged('role') == 1 || logged('role') == 3): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-clipboard"></i><span>Employee / HR</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li class="submenu-section-title">Personnel</li>
                        <li><a href="<?= base_url('admin/employee/create') ?>"><span>Add Employee</span></a></li>
                        <li><a href="<?= base_url('admin/employee/') ?>"><span>Manage Employees</span></a></li>
                        <li><a href="<?= base_url('admin/employee/leaves') ?>"><span>Leave Requests</span></a></li>

                        <li class="submenu-section-title submenu-section-prof">Payroll & Statutory</li>
                        <li><a href="<?= base_url('admin/employee_advances/') ?>"><span>Salary Advances</span></a></li>
                        <li><a href="<?= base_url('admin/payroll/') ?>"><span>Monthly Pay Sheets</span></a></li>
                        <li><a href="<?= base_url('admin/payroll/epf_form') ?>"><span>EPF Form (C)</span></a></li>
                        <li><a href="<?= base_url('admin/payroll/etf_form') ?>"><span>ETF Form (R4)</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- User Administration -->
                <?php if (logged('role') == 1): ?>
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ion-android-settings"></i><span>Administration</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li class="submenu-section-title">Company Settings</li>
                        <li><a href="<?= base_url('admin/company') ?>"><span>Company Details</span></a></li>
                        <li><a href="<?= base_url('admin/financial_year') ?>"><span>Financial Year</span></a></li>
                        <li><a href="<?= base_url('admin/accounting_defaults') ?>"><span>Accounting Defaults</span></a></li>

                        <li class="submenu-section-title submenu-section-prof">Users & Security</li>
                        <?php if (hasPermissions('users_add')): ?>
                        <li><a href="<?= base_url('admin/users/add') ?>"><span>Add User</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('users_list')): ?>
                        <li><a href="<?= base_url('admin/users/') ?>"><span>Manage Users</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('roles_list')): ?>
                        <li><a href="<?= base_url('admin/roles') ?>"><span>Roles</span></a></li>
                        <?php endif; ?>
                        <?php if (hasPermissions('permissions_list')): ?>
                        <li><a href="<?= base_url('admin/permissions') ?>"><span>Permissions</span></a></li>
                        <?php endif; ?>

                        <li class="submenu-section-title submenu-section-prof">Utilities</li>
                        <li><a href="<?= base_url('admin/home/mobile_preview') ?>"><span>Mobile App Preview</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- JQuery Sub-Sub Menu script -->
<script>
$(document).ready(function() {
    $('.has_sub_sub > a').click(function(e) {
        e.preventDefault();
        var currentLi = $(this).parent();
        var currentUl = currentLi.find('ul');
        
        // Slide up sibling sub-sub menus
        currentLi.siblings('.has_sub_sub').removeClass('active').find('ul').slideUp(200);
        
        // Toggle current sub-sub menu
        if(currentUl.is(':visible')) {
            currentLi.removeClass('active');
            currentUl.slideUp(200);
        } else {
            currentLi.addClass('active');
            currentUl.slideDown(200);
        }
    });
});
</script>
<!-- Left Sidebar End -->