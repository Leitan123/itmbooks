<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Premium Settings Header -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 25px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: #f0fdf4; width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #1abc9c; border: 1px solid #d1fae5;">
                        <i class="fa fa-sliders"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 2px 0;">All Settings</h4>
                        <p style="font-size: 12px; color: #64748b; margin: 0; font-weight: 500;"><?= title ?></p>
                    </div>
                </div>

                <!-- Instant Search Bar -->
                <div style="position: relative; width: 350px;">
                    <i class="fa fa-search" style="position: absolute; left: 14px; top: 12px; color: #94a3b8; font-size: 14px;"></i>
                    <input type="text" id="settingSearch" placeholder="Search settings ( / )" style="width: 100%; height: 38px; padding-left: 38px; padding-right: 15px; border-radius: 20px; border: 1px solid #cbd5e1; font-size: 13px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1abc9c'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                <div>
                    <a href="<?= base_url('admin') ?>" class="btn btn-default" style="border-radius: 6px; font-weight: 600; padding: 8px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #cbd5e1; background: #fff;">
                        Close Settings <i class="fa fa-times" style="color: #ef4444;"></i>
                    </a>
                </div>
            </div>

            <!-- Custom Grid Styles -->
            <style>
                .zoho-settings-group-title {
                    font-size: 16px;
                    font-weight: 700;
                    color: #1e293b;
                    margin-bottom: 20px;
                    margin-top: 30px;
                    padding-bottom: 6px;
                    border-bottom: 2px solid #cbd5e1;
                    display: inline-block;
                }
                .zoho-settings-card {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 20px;
                    min-height: 290px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
                    transition: transform 0.15s, box-shadow 0.15s;
                }
                .zoho-settings-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.05);
                    border-color: #cbd5e1;
                }
                .zoho-settings-card-header {
                    font-size: 14px;
                    font-weight: 700;
                    margin-bottom: 15px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #f1f5f9;
                }
                .zoho-settings-card-header.c1 { color: #0284c7; }
                .zoho-settings-card-header.c2 { color: #ea580c; }
                .zoho-settings-card-header.c3 { color: #059669; }
                .zoho-settings-card-header.c4 { color: #7c3aed; }
                .zoho-settings-card-header.c5 { color: #e11d48; }
                
                .zoho-settings-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }
                .zoho-settings-list li {
                    margin-bottom: 10px;
                    font-size: 13px;
                }
                .zoho-settings-list li a {
                    color: #475569;
                    text-decoration: none;
                    display: block;
                    padding: 4px 8px;
                    border-radius: 4px;
                    transition: all 0.15s;
                }
                .zoho-settings-list li a:hover {
                    background: #f8fafc;
                    color: #0f172a;
                    padding-left: 12px;
                }
                .zoho-settings-sub-header {
                    font-size: 11px;
                    font-weight: 700;
                    text-transform: uppercase;
                    color: #94a3b8;
                    margin-top: 15px;
                    margin-bottom: 8px;
                    letter-spacing: 0.5px;
                }
                .no-settings-found {
                    text-align: center;
                    padding: 40px;
                    color: #94a3b8;
                    font-size: 15px;
                }
            </style>

            <div id="settingsContainer">
                
                <!-- SECTION 1: ORGANIZATION SETTINGS -->
                <div>
                    <h5 class="zoho-settings-group-title">Organization Settings</h5>
                    <div class="row">
                        
                        <!-- Organization -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c1">
                                    <i class="fa fa-building-o"></i> Organization
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/company') ?>"><i class="fa fa-angle-right style-icon"></i> Profile</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Branding</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Custom Domain</a></li>
                                    <li><a href="<?= base_url('admin/location') ?>"><i class="fa fa-angle-right style-icon"></i> Locations</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> AI Integration</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Manage Subscription</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Users & Roles -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c2">
                                    <i class="fa fa-users"></i> Users & Roles
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/users') ?>"><i class="fa fa-angle-right style-icon"></i> Users</a></li>
                                    <li><a href="<?= base_url('admin/roles') ?>"><i class="fa fa-angle-right style-icon"></i> Roles</a></li>
                                    <li><a href="<?= base_url('admin/setting/profile') ?>"><i class="fa fa-angle-right style-icon"></i> User Profile / Password</a></li>
                                </ul>
                                <div class="zoho-settings-sub-header">Taxes & Compliance</div>
                                <ul class="zoho-settings-list">
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Taxes</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Setup & Configurations -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c3">
                                    <i class="fa fa-sliders"></i> Setup & Config
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> General</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Currencies</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Payment Terms</a></li>
                                    <li><a href="<?= base_url('admin/opening_balance') ?>"><i class="fa fa-angle-right style-icon"></i> Opening Balances</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Reminders</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Customer Portal</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Vendor Portal</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Customization -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c4">
                                    <i class="fa fa-wrench"></i> Customization
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Number Series</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> PDF Templates</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Email Notifications</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Reporting Tags</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Web Tabs</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Automation -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c5">
                                    <i class="fa fa-bolt"></i> Automation
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Workflow Rules</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Workflow Actions</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Workflow Logs</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Schedules</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SECTION 2: MODULE SETTINGS -->
                <div>
                    <h5 class="zoho-settings-group-title">Module Settings</h5>
                    <div class="row">
                        
                        <!-- General -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c1">
                                    <i class="fa fa-cog"></i> General Modules
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/financial_year') ?>"><i class="fa fa-angle-right style-icon"></i> Financial Year</a></li>
                                    <li><a href="<?= base_url('admin/banks') ?>"><i class="fa fa-angle-right style-icon"></i> Bank Accounts</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Inventory -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c2">
                                    <i class="fa fa-archive"></i> Inventory
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/items') ?>"><i class="fa fa-angle-right style-icon"></i> Items & Services</a></li>
                                    <li><a href="<?= base_url('admin/categories') ?>"><i class="fa fa-angle-right style-icon"></i> Categories</a></li>
                                    <li><a href="<?= base_url('admin/brand') ?>"><i class="fa fa-angle-right style-icon"></i> Brands</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Sales -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c3">
                                    <i class="fa fa-shopping-cart"></i> Sales
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/customer') ?>"><i class="fa fa-angle-right style-icon"></i> Customers</a></li>
                                    <li><a href="<?= base_url('admin/quotation') ?>"><i class="fa fa-angle-right style-icon"></i> Quotations</a></li>
                                    <li><a href="<?= base_url('admin/job_card') ?>"><i class="fa fa-angle-right style-icon"></i> Job Cards</a></li>
                                    <li><a href="<?= base_url('admin/sales/pos') ?>" style="color: #1abc9c; font-weight: 700;"><i class="fa fa-angle-right style-icon"></i> POS Terminal</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Purchases -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c4">
                                    <i class="fa fa-truck"></i> Purchases
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="<?= base_url('admin/vendor') ?>"><i class="fa fa-angle-right style-icon"></i> Vendors</a></li>
                                    <li><a href="<?= base_url('admin/purchase_orders') ?>"><i class="fa fa-angle-right style-icon"></i> Purchase Orders</a></li>
                                    <li><a href="<?= base_url('admin/grn') ?>"><i class="fa fa-angle-right style-icon"></i> GRNs</a></li>
                                    <li><a href="<?= base_url('admin/purchases') ?>"><i class="fa fa-angle-right style-icon"></i> Purchase Vouchers</a></li>
                                    <li><a href="<?= base_url('admin/vendor_payments') ?>"><i class="fa fa-angle-right style-icon"></i> Vendor Payments</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Custom Modules -->
                        <div class="col-lg-2-4 col-md-4 col-sm-6 setting-card-wrapper">
                            <div class="zoho-settings-card">
                                <div class="zoho-settings-card-header c5">
                                    <i class="fa fa-plus-square"></i> Custom Modules
                                </div>
                                <ul class="zoho-settings-list">
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Custom fields</a></li>
                                    <li><a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fa fa-angle-right style-icon"></i> Webhooks</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Empty Search State -->
            <div id="noResultsState" class="no-settings-found" style="display: none;">
                <i class="fa fa-search fa-3x" style="color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                No settings found matching your search.
            </div>

        </div>
    </div>
</div>

<!-- Grid System Custom layout for 5 cards in a row -->
<style>
@media (min-width: 1200px) {
    .col-lg-2-4 {
        width: 20%;
        float: left;
    }
}
</style>

<script>
$(document).ready(function() {
    // Keyboard shortcut to focus search: '/'
    $(document).keydown(function(e) {
        if (e.key === '/' && !$(e.target).is('input, textarea')) {
            e.preventDefault();
            $('#settingSearch').focus();
        }
    });

    // Real-time incremental search filter
    $('#settingSearch').on('input', function() {
        var query = $(this).val().toLowerCase().trim();
        var matchCount = 0;

        if (query === '') {
            $('.setting-card-wrapper').show();
            $('.zoho-settings-list li').show();
            $('.zoho-settings-group-title').show();
            $('#noResultsState').hide();
            return;
        }

        // Loop through cards
        $('.zoho-settings-card').each(function() {
            var card = $(this);
            var cardWrapper = card.closest('.setting-card-wrapper');
            var cardMatches = false;

            // Loop through list items
            card.find('.zoho-settings-list li').each(function() {
                var li = $(this);
                var text = li.text().toLowerCase();

                if (text.indexOf(query) !== -1) {
                    li.show();
                    cardMatches = true;
                    matchCount++;
                } else {
                    li.hide();
                }
            });

            if (cardMatches) {
                cardWrapper.show();
            } else {
                cardWrapper.hide();
            }
        });

        // Hide headers if no children cards are visible
        $('.zoho-settings-group-title').each(function() {
            var title = $(this);
            var nextRow = title.next('.row');
            var visibleCards = nextRow.find('.setting-card-wrapper:visible').length;
            if (visibleCards === 0) {
                title.hide();
            } else {
                title.show();
            }
        });

        if (matchCount === 0) {
            $('#noResultsState').show();
        } else {
            $('#noResultsState').hide();
        }
    });
});
</script>

<?php $this->view('includes/footer_start.php'); ?>
<?php $this->view('includes/footer_end.php'); ?>
