<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <!--<div class="sidebar-inner  ">-->
    <div class="sidebar-inner slimscrollleft ">

        <!--- Sidemenu -->
        <li id="sidebar-menu">
            <ul>

                <li class="has_sub">
                    <a href="<?= base_url('admin') ?>" class="waves-effect">
                        <i class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span>
                    </a>
                </li>

                <!-- ================= ACCOUNTING ================= -->

        <li class="has_sub">

            <a href="#" class="waves-effect">

                <i class="ion-calculator"></i>

                <span>Accounting</span>

                <span class="menu-arrow"></span>

            </a>

        <ul>
                <li>
                    <a href="<?= base_url('admin/company') ?>">
                        <i class="fa fa-building"></i>
                        <span>Company Settings</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/financial_year') ?>">
                        <i class="ion-calendar"></i>
                        <span>Financial Year</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/banks') ?>">
                        <i class="fa fa-bank"></i>
                        <span>Bank Master</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/opening_balance') ?>">
                        <i class="fa fa-balance-scale"></i>
                        <span>Opening Balance</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('admin/accounting_defaults') ?>">
                        <i class="fa fa-cog"></i>
                        <span>Accounting Defaults</span>
                    </a>
                </li>

                

        <!-- Chart Of Accounts -->
        <li>

            <a href="<?= base_url('admin/chart_of_accounts/') ?>">

                <span>Chart Of Accounts</span>

            </a>

        </li>

        <!-- Income -->
        <li>

            <a href="<?= base_url('admin/incomes/') ?>">

                <span>Income</span>

            </a>

        </li>

        <!-- Expenses -->
        <li>

            <a href="<?= base_url('admin/expenses/') ?>">

                <span>Expenses</span>

            </a>

        </li>

        <!-- Drawings / Personal Withdrawals -->
        <li>

            <a href="<?= base_url('admin/drawings/') ?>">

                <span>Drawings / Personal Withdrawals</span>

            </a>

        </li>

        <!-- Journal Entries -->
        <li>

            <a href="<?= base_url('admin/journal_entries/') ?>">

                <span>Journal Entries</span>

            </a>

        </li>

        <!-- General Ledger -->
        <li>

            <a href="<?= base_url('admin/general_ledger/') ?>">

                <span>General Ledger</span>

            </a>

        </li>

        <!-- Trial Balance -->
        <li>

            <a href="<?= base_url('admin/trial_balance/') ?>">

                <span>Trial Balance</span>

            </a>

        </li>

        <!-- Profit & Loss -->
        <li>

            <a href="<?= base_url('admin/profit_loss/') ?>">

                <span>Profit & Loss</span>

            </a>

        </li>

        <!-- Project Profit & Loss -->
        <li>

            <a href="<?= base_url('admin/project_profit_loss/') ?>">

                <span>Project Profit & Loss</span>

            </a>

        </li>

        <!-- Mobile App Preview -->
        <li>

            <a href="<?= base_url('admin/home/mobile_preview') ?>">

                <span>Mobile App Preview</span>

            </a>

        </li>

        <!-- Balance Sheet -->
        <li>

            <a href="<?= base_url('admin/balance_sheet/') ?>">

                <span>Balance Sheet</span>

            </a>

        </li>

        <!-- Cash Flow -->
        <li>

            <a href="<?= base_url('admin/cash_flow/') ?>">

                <span>Cash Flow</span>

            </a>

        </li>

        <li>
            <a href="<?= base_url('admin/bank_accounts') ?>">
                Bank Accounts
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/received_cheques') ?>">
                <i class="fa fa-download"></i>
                <span> Received Cheques </span>
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/issued_cheques') ?>">
                <i class="fa fa-upload"></i>
                <span> Issued Cheques </span>
            </a>
        </li>

        <li>
            <a href="<?= base_url('admin/bank_rec') ?>">
                <i class="fa fa-university"></i>
                <span> Bank Reconciliation </span>
            </a>
        </li>

    </ul>

</li>

            <?php if (hasPermissions('category_list') || (hasPermissions('category_add'))): ?> 
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="ion-android-stair-drawer"></i>
                        <span>Categories</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('category_add')): ?>
                        <li><a href="<?= base_url('admin/categories/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('category_list')): ?>
                        <li><a href="<?= base_url('admin/categories/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>
                </li>
            <?php endif ?>
            <?php if (hasPermissions('brand_list') || (hasPermissions('brand_add'))): ?> 
                <li class="has_sub" style="display:">
                    <a href="#" class="waves-effect">
                        <i class="ion-pricetag"></i>
                        <span>Brand</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('brand_add')): ?>
                        <li><a href="<?= base_url('admin/brand/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('brand_list')): ?>
                        <li><a href="<?= base_url('admin/brand/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>
                </li>
            <?php endif ?>
            <?php if (hasPermissions('item_list') || (hasPermissions('item_add'))): ?> 
                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-android-storage"></i>
                        <span>Items</span> <span class="menu-arrow"></span>
                    </a> 
                        <ul>
                        <?php if (hasPermissions('item_add')): ?>
                        <li><a href="<?= base_url('admin/items/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('item_list')): ?>
                        <li><a href="<?= base_url('admin/items/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>
                        
                </li>
            <?php endif ?>               
            <?php if (hasPermissions('customer_list') || (hasPermissions('customer_add'))): ?> 
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="ion-person-stalker"></i>
                        <span>Customer</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('customer_add')): ?>
                        <li><a href="<?= base_url('admin/customer/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('customer_list')): ?>
                        <li><a href="<?= base_url('admin/customer/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul> 
                </li>
            <?php endif ?>   

            <li class="has_sub">

    <a href="#" class="waves-effect">

        <i class="ion-document-text"></i>

        <span>Quotation</span>

        <span class="menu-arrow"></span>

    </a>

    <ul>

        <li>

            <a href="<?= base_url('admin/quotation/create') ?>">

                <span>Add</span>

            </a>

        </li>

        <li>

            <a href="<?= base_url('admin/quotation/') ?>">

                <span>Manage</span>

            </a>

        </li>

    </ul>

</li>



<li class="has_sub">

    <a href="#" class="waves-effect">

        <i class="ion-clipboard"></i>

        <span>Job Card</span>

        <span class="menu-arrow"></span>

    </a>

    <ul>

        <li>

            <a href="<?= base_url('admin/job_card/create') ?>">

                <span>Add</span>

            </a>

        </li>

        <li>

            <a href="<?= base_url('admin/job_card/') ?>">

                <span>Manage</span>

            </a>

        </li>

    </ul>

</li>    
            <?php if (hasPermissions('vendor_list') || (hasPermissions('vendor_add'))): ?> 

                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-man"></i>
                        <span>Vendor</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('vendor_add')): ?>
                        <li><a href="<?= base_url('admin/Vendor/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('vendor_list')): ?>
                        <li><a href="<?= base_url('admin/Vendor/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>  
                </li>
                <?php endif ?>
                <?php if (hasPermissions('purchases_list') || (hasPermissions('purchases_add'))): ?> 
                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-arrow-shrink"></i>
                        <span>Purchases</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('purchases_add')): ?>
                        <li><a href="<?= base_url('admin/purchases/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('purchases_list')): ?>
                        <li><a href="<?= base_url('admin/purchases/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>  
                </li>
                <?php endif ?>
                <?php if (hasPermissions('vendor_payment_list') || (hasPermissions('vendor_payment_add'))): ?> 
                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-cash"></i>
                        <span>Purchase Payment</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('vendor_payment_add')): ?>
                        <li><a href="<?= base_url('admin/vendor_payments/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('vendor_payment_list')): ?>
                        <li><a href="<?= base_url('admin/vendor_payments/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul>   
                </li>
                <?php endif ?>
                <?php if (hasPermissions('sales_list') || (hasPermissions('sales_add'))): ?> 
                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-arrow-expand"></i>
                        <span>Sales</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('sales_add')): ?>
                        <li><a href="<?= base_url('admin/sales/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('sales_list')): ?>
                        <li><a href="<?= base_url('admin/sales/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul> 
                    
                </li>
                <?php endif ?>
                <?php if (hasPermissions('customer_payment_list') || (hasPermissions('customer_payment_add'))): ?> 
                <li class="has_sub" >
                    <a href="#" class="waves-effect">
                        <i class="ion-card"></i>
                        <span>Sales Payment</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <?php if (hasPermissions('customer_payment_add')): ?>
                        <li><a href="<?= base_url('admin/customer_payments/create') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('customer_payment_list')): ?>
                        <li><a href="<?= base_url('admin/customer_payments/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul> 
                   
                </li>
                <?php endif ?>
                <?php if (hasPermissions('stock_report') || hasPermissions('customer_balance_sheet_report') || hasPermissions('vendor_balance_sheet_report')): ?> 
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="ion-ios7-browsers"></i>
                        <span>Reports</span> <span class="menu-arrow"></span>
                    </a>
                    
                    <ul>
                        <?php if (hasPermissions('stock_report')): ?>
                        <li><a href="<?= base_url('admin/sales/stock') ?>"><span> Stock</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('customer_balance_sheet_report')): ?>
                        <li><a href="<?= base_url('admin/customer_payments/balance_sheet') ?>"><span>Customer Balance Sheet</span></a></li>
                        <?php endif ?>
                        <li><a href="<?= base_url('admin/customer_payments/payment_history') ?>"><span>Customer Payment History</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/ledger') ?>"><span>Customer Ledger</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/statement') ?>"><span>Customer Statement of Accounts</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/invoice_aging') ?>" target="_blank"><span>Invoice Aging</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/receivables_summary') ?>"><span>Receivables Summary</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/daily_cash_collection') ?>"><span>Daily Cash Collection</span></a></li>
                        <li><a href="<?= base_url('admin/customer_payments/cheque_register') ?>"><span>Cheque Register</span></a></li>

                        <!--
                        <?php if (hasPermissions('vendor_balance_sheet_report')): ?>
                        <li><a href="<?= base_url('admin/vendor_payments/balance_sheet') ?>"><span>Vendor Balance Sheet</span></a></li> 
                        <?php endif ?> -->
                        <li><a href="<?= base_url('admin/vendor_payments/summary') ?>"><span>Vendor Payment Summary</span></a></li>
                    </ul>  
                </li>

                 <?php endif ?>

                 <li class="has_sub">

    <a href="#" class="waves-effect">

        <i class="ion-clipboard"></i>

        <span>Employee</span>

        <span class="menu-arrow"></span>

    </a>

    <ul>

        <li>

            <a href="<?= base_url('admin/employee/create') ?>">

                <span>Add</span>

            </a>

        </li>

        <li>

            <a href="<?= base_url('admin/employee/') ?>">

                <span>Manage</span>

            </a>

        </li>

    </ul>

</li>    
                 
                 <?php if (hasPermissions('users_list') || (hasPermissions('users_add'))): ?> 

                    <li class="has_sub" >
                        <a href="#" class="waves-effect">
                            <i class="ion-person"></i>
                            <span>Users</span> <span class="menu-arrow"></span>
                        </a>
                         <ul>
                        <?php if (hasPermissions('users_add')): ?>
                        <li><a href="<?= base_url('admin/users/add') ?>"><span> Add</span></a></li>
                        <?php endif ?>
                        <?php if (hasPermissions('users_list')): ?>
                        <li><a href="<?= base_url('admin/users/') ?>"><span>Manage</span></a></li>
                        <?php endif ?>
                    </ul> 
                    </li>
                <?php endif ?>
                
                 <?php if (hasPermissions('roles_list') || (hasPermissions('permissions_list'))): ?> 

                    <li class="has_sub" >
                        <a href="#" class="waves-effect">
                            <i class="ion-card"></i>
                            <span>User Privileges</span> <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="<?php echo  base_url('admin/roles') ?>" class="nav-link  "><span> Roles</span></a></li>
                            <li><a href="<?php echo base_url('admin/permissions')  ?>" class="nav-link  "><span> Permissions</span></a></li>

                        </ul>
                    </li>
                <?php endif ?>


                                
                                

            </ul>
            <div class="clearfix"></div>
        </div>
        <!--- Sidemenu -->
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
