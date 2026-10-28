<!-- Top Bar Start -->
<?php
$CI =& get_instance();
$CI->load->model('Company_model', 'topbar_company_model');
$company = $CI->topbar_company_model->get_company();
?>
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <a href="<?= base_url() ?>" class="logo" id="logo-tour" style="display: flex; align-items: center; justify-content: center; height: 70px;">
            <?php if (!empty($company) && !empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)): ?>
                <img src="<?= base_url('media/img/' . $company->Logo) ?>" alt="Logo" style="max-height: 50px; max-width: 220px; vertical-align: middle; object-fit: contain;">
            <?php else: ?>
                <span><?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : title ?></span>
            <?php endif; ?>
        </a>
    </div>


    <nav class="navbar navbar-custom">
        <ul class="nav navbar-nav" style="display: flex; align-items: center; float: left;">
            <li class="nav-item">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="zmdi zmdi-menu"></i>
                </button>
            </li>
            
            <!-- Zoho Quick Create Plus (+) Dropdown -->
            <li class="nav-item dropdown" style="display: inline-block;">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="padding: 18px 15px; display: inline-flex; align-items: center; justify-content: center;">
                    <span style="background-color: #1abc9c; color: #fff; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 16px; font-weight: bold; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                <div class="dropdown-menu" style="width: 680px; padding: 25px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-top: 5px; border: 1px solid #cbd5e1; background: #fff; z-index: 99999; left: 10px;">
                    <div class="row">
                        <!-- GENERAL -->
                        <div class="col-xs-3">
                            <h5 style="font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px; margin-top: 0;"><i class="fa fa-th" style="margin-right: 3px;"></i> General</h5>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 2.3;">
                                <li><a href="<?= base_url('admin/items/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Item / Service</a></li>
                                <li><a href="<?= base_url('admin/brand/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Brand</a></li>
                                <li><a href="<?= base_url('admin/categories/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Category</a></li>
                            </ul>
                        </div>
                        
                        <!-- SALES -->
                        <div class="col-xs-3">
                            <h5 style="font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px; margin-top: 0;"><i class="fa fa-shopping-cart" style="margin-right: 3px;"></i> Sales</h5>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 2.3;">
                                <li><a href="<?= base_url('admin/sales/pos') ?>" style="color: #1abc9c; font-weight: bold; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #1abc9c; margin-right: 6px;"></i> POS Terminal</a></li>
                                <li><a href="<?= base_url('admin/customer/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Customer</a></li>
                                <li><a href="<?= base_url('admin/sales/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Add Sale</a></li>
                                <li><a href="<?= base_url('admin/quotation/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Add Quotation</a></li>
                                <li><a href="<?= base_url('admin/jobcard/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Add Job Card</a></li>
                            </ul>
                        </div>
                        
                        <!-- PURCHASES -->
                        <div class="col-xs-3">
                            <h5 style="font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px; margin-top: 0;"><i class="fa fa-truck" style="margin-right: 3px;"></i> Purchases</h5>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 2.3;">
                                <li><a href="<?= base_url('admin/vendor/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Vendor</a></li>
                                <li><a href="<?= base_url('admin/purchase/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Add Purchase</a></li>
                            </ul>
                        </div>
                        
                        <!-- BANKING / PAYMENTS -->
                        <div class="col-xs-3">
                            <h5 style="font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px; margin-top: 0;"><i class="fa fa-university" style="margin-right: 3px;"></i> Banking</h5>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 2.3;">
                                <li><a href="<?= base_url('admin/expense/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Expense</a></li>
                                <li><a href="<?= base_url('admin/payment/create') ?>" style="color: #334155; text-decoration: none;"><i class="fa fa-plus-circle" style="color: #cbd5e1; margin-right: 6px;"></i> Receive Payment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="nav navbar-nav pull-right" style="display: flex; align-items: center; float: right; margin: 0; padding: 0; list-style: none; height: 70px;">
            <!-- Subscribe -->
            <li class="nav-item hidden-xs" style="display: inline-block;">
                <a href="#" style="color: #3b82f6; font-weight: 700; font-size: 11px; text-transform: uppercase; border: 1px solid #3b82f6; border-radius: 20px; padding: 5px 15px; margin-right: 15px; display: inline-block; background: rgba(59, 130, 246, 0.05); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#3b82f6'; this.style.color='#fff';" onmouseout="this.style.background='rgba(59, 130, 246, 0.05)'; this.style.color='#3b82f6';">Subscribe</a>
            </li>
            
            <!-- Refer & Earn -->
            <li class="nav-item" style="display: inline-block;">
                <a href="#" title="Refer & Earn" style="color: #cbd5e1; font-size: 18px; padding: 10px; margin-right: 8px; display: inline-block; transition: color 0.2s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#cbd5e1'">
                    <i class="fa fa-gift"></i>
                </a>
            </li>
            
            <!-- Notification -->
            <li class="nav-item" style="display: inline-block;">
                <a href="#" title="Notifications" style="color: #cbd5e1; font-size: 18px; padding: 10px; margin-right: 8px; display: inline-block; transition: color 0.2s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#cbd5e1'">
                    <i class="fa fa-bell"></i>
                </a>
            </li>
            
            <!-- Setting -->
            <li class="nav-item" style="display: inline-block;">
                <a href="<?= base_url('admin/setting') ?>" title="Settings" style="color: #cbd5e1; font-size: 18px; padding: 10px; margin-right: 15px; display: inline-block; transition: color 0.2s;" onmouseover="this.style.color='#1abc9c'" onmouseout="this.style.color='#cbd5e1'">
                    <i class="fa fa-cog"></i>
                </a>
            </li>

            <!-- Profile dropdown -->
            <li class="nav-item dropdown notification-list" style="display: inline-block; margin-top: 10px;">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user"
                   data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false" style="padding: 0;">
                    <img src='<?= base_url('assets/images/user77.png') ?>'
                         style="border: 2px solid #000; background-color: #f5f5f5" width="40" alt="user"
                         class="img-circle">
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown "
                     aria-labelledby="Preview" style="margin-top: 10px;">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="text-overflow">
                            <small><?= isset($user->Name) ? $user->Name : $this->session->userdata('user')->Name ?></small>
                        </h5>
                    </div>

                    <!-- item-->
                    <a href="<?= base_url('admin/setting') ?>" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-settings"></i> <span>Settings</span>
                    </a>


                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item logout">
                        <i class="zmdi zmdi-power"></i> <span>Logout</span>
                    </a>

                </div>
            </li>

        </ul>

    </nav>

</div>
<!-- Top Bar End -->