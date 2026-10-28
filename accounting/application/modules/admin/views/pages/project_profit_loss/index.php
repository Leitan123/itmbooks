<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<style>
    .project-header {
        background: #f7f9fa;
        border-bottom: 2px solid #e3e7eb;
        padding: 15px;
        border-radius: 6px 6px 0 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .project-header:hover {
        background: #f0f3f5;
    }
    .project-card {
        border: 1px solid #e3e7eb;
        border-radius: 6px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        background: #fff;
    }
    .project-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }
    .project-subtitle {
        font-size: 13px;
        color: #7f8c8d;
        margin-top: 4px;
    }
    .badge-profit {
        background-color: #dff0d8;
        color: #3c763d;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 4px;
        border: 1px solid #d6e9c6;
    }
    .badge-loss {
        background-color: #f2dede;
        color: #a94442;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 4px;
        border: 1px solid #ebccd1;
    }
    .table-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #34495e;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-left: 3px solid #3498db;
        padding-left: 8px;
    }
    @media print {
        .topbar, .side-menu, .breadcrumb, .filter-panel, .footer {
            display: none !important;
        }
        .content-page {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 0 !important;
        }
        .project-card {
            border: none !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }
        .collapse {
            display: block !important;
            height: auto !important;
        }
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Project-wise Profit & Loss Report</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Project P&L</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-panel" style="margin-bottom: 25px; background: #fdfdfd; border: 1px solid #e3e7eb; padding: 15px; border-radius: 8px;">
                <form method="GET" action="<?= base_url('admin/project_profit_loss') ?>" id="filterForm" class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">Project (Job Card)</label>
                        <select name="job_card_id" class="form-control select2">
                            <option value="">All Projects</option>
                            <?php foreach ($all_projects as $ap): ?>
                            <option value="<?= $ap->JobCardID ?>" <?= ($job_card_id == $ap->JobCardID) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ap->JobCardNo ?? '') ?> - <?= htmlspecialchars($ap->Title ?? '') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($from_date) ?>">
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($to_date) ?>">
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-success" style="font-weight: bold; height: 38px; width: 80px;">Filter</button>
                        <button type="button" onclick="resetFilter()" class="btn btn-default" style="font-weight: bold; height: 38px; width: 80px; margin-left: 5px;">Reset</button>
                    </div>
                </form>
                
                <div class="row" style="margin-top: 15px; border-top: 1px solid #f0f3f5; padding-top: 15px;">
                    <div class="col-xs-12">
                        <button type="button" onclick="window.print()" class="btn btn-primary btn-sm" style="font-weight: bold;"><i class="fa fa-print"></i> Print Report</button>
                        <a href="<?= base_url('admin/project_profit_loss/export_csv?from_date='.$from_date.'&to_date='.$to_date.'&job_card_id='.$job_card_id) ?>" class="btn btn-info btn-sm" style="font-weight: bold; margin-left: 5px;"><i class="fa fa-file-excel-o"></i> Export CSV</a>
                    </div>
                </div>
            </div>

            <script>
                function resetFilter() {
                    window.location.href = '<?= base_url("admin/project_profit_loss") ?>';
                }
            </script>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs m-b-20" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-pl" aria-controls="tab-pl" role="tab" data-toggle="tab" style="font-weight: bold;">
                        <i class="fa fa-money"></i> Project Profit & Loss (Details)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-overhead" aria-controls="tab-overhead" role="tab" data-toggle="tab" style="font-weight: bold;">
                        <i class="fa fa-calculator"></i> Cost Allocation & Overhead (Unpaid/Active Projects)
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- TAB 1: P&L Details -->
                <div role="tabpanel" class="tab-pane active" id="tab-pl">
                    <!-- Projects P&L Listings -->
                    <div class="row">
                        <div class="col-xs-12">
                    <?php if (empty($projects)): ?>
                        <div class="alert alert-info text-center">
                            <strong>No projects (Job Cards) found matching the selected filters.</strong>
                        </div>
                    <?php else: ?>
                        <?php foreach ($projects as $p): ?>
                            <div class="project-card">
                                <div class="project-header" data-toggle="collapse" data-target="#project-body-<?= $p->JobCardID ?>">
                                    <div>
                                        <h3 class="project-title">
                                            <i class="fa fa-folder-open text-primary" style="margin-right: 5px;"></i>
                                            <?= htmlspecialchars($p->Title ?? '') ?> (<?= htmlspecialchars($p->JobCardNo ?? '') ?>)
                                        </h3>
                                         <div class="project-subtitle">
                                             <i class="fa fa-building"></i> Company Name: <strong><?= htmlspecialchars($p->CompanyName ?? 'N/A') ?></strong>
                                             <?php if (!empty($p->CustomerName)): ?>
                                                 &nbsp;|&nbsp; <i class="fa fa-user"></i> Contact: <strong><?= htmlspecialchars($p->CustomerName) ?></strong>
                                             <?php endif; ?>
                                             <?php if (!empty($p->DomainName)): ?>
                                                 &nbsp;|&nbsp; <i class="fa fa-globe"></i> Domain: <strong><?= htmlspecialchars($p->DomainName) ?></strong>
                                             <?php endif; ?>
                                         </div>
                                    </div>
                                    <div>
                                        <span class="<?= ($p->net_profit >= 0) ? 'badge-profit' : 'badge-loss' ?>">
                                            Net Profit/Loss: LKR <?= number_format($p->net_profit, 2) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div id="project-body-<?= $p->JobCardID ?>" class="collapse" style="padding: 20px; border-top: 1px solid #f0f3f5;">
                                    <div class="row">
                                        <!-- Incomes (Sales) -->
                                        <div class="col-md-6 col-xs-12">
                                            <h4 class="table-section-title">Incomes Details (Sales)</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-condensed table-striped">
                                                    <thead>
                                                        <tr class="info">
                                                            <th>Date</th>
                                                            <th>Invoice Ref No</th>
                                                            <th style="text-align: right;">Amount (LKR)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($p->incomes)): ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">No sales income recorded.</td>
                                                            </tr>
                                                        <?php else: ?>
                                                            <?php foreach ($p->incomes as $inc): ?>
                                                                <tr>
                                                                    <td><?= date('Y-m-d', strtotime($inc->SaleDate)) ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url('admin/sales/view/' . $inc->SaleID) ?>" style="color: #337ab7; font-weight: 500;">
                                                                            <?= htmlspecialchars($inc->RefNo ?? '') ?>
                                                                        </a>
                                                                    </td>
                                                                    <td style="text-align: right;"><?= number_format($inc->TotalSaleAmount, 2) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background: #f7f9fa; font-weight: bold;">
                                                            <td colspan="2" style="text-align: right;">Total Incomes:</td>
                                                            <td style="text-align: right; color: green;"><?= number_format($p->total_income, 2) ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <!-- Expenses -->
                                        <div class="col-md-6 col-xs-12">
                                            <h4 class="table-section-title">Expenses Details</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-condensed table-striped">
                                                    <thead>
                                                        <tr class="danger">
                                                            <th>Date</th>
                                                            <th>Expense No & Account</th>
                                                            <th>Description</th>
                                                            <th style="text-align: right;">Amount (LKR)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($p->expenses)): ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted">No customer expenses recorded.</td>
                                                            </tr>
                                                        <?php else: ?>
                                                            <?php foreach ($p->expenses as $exp): ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($exp->ExpenseDate ?? '') ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url('admin/expenses/edit/' . $exp->ExpenseID) ?>" style="color: #a94442; font-weight: 500;">
                                                                            <?= htmlspecialchars($exp->ExpenseNo ?? '') ?>
                                                                        </a>
                                                                        <br>
                                                                        <small class="text-muted"><?= htmlspecialchars($exp->AccountName ?? '') ?></small>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($exp->Description ?? '-') ?></td>
                                                                    <td style="text-align: right;"><?= number_format($exp->Amount, 2) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background: #f7f9fa; font-weight: bold;">
                                                            <td colspan="3" style="text-align: right;">Total Expenses:</td>
                                                            <td style="text-align: right; color: red;"><?= number_format($p->total_expenses, 2) ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Project Net Summary Bar -->
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-xs-12">
                                            <div class="alert <?= ($p->net_profit >= 0) ? 'alert-success' : 'alert-danger' ?>" style="margin-bottom: 0; padding: 12px;">
                                                <i class="fa <?= ($p->net_profit >= 0) ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                                                This project runs at a net <strong><?= ($p->net_profit >= 0) ? 'Profit' : 'Loss' ?></strong> of <strong>LKR <?= number_format(abs($p->net_profit), 2) ?></strong> 
                                                (Income: LKR <?= number_format($p->total_income, 2) ?> | Expenses: LKR <?= number_format($p->total_expenses, 2) ?>).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Pagination links -->
                        <div class="row">
                        <!-- Pagination links -->
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <?= $links ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
                </div>

                <!-- TAB 2: Costing & Overhead Allocations -->
                <div role="tabpanel" class="tab-pane" id="tab-overhead">
                    <!-- KPI Cards Row -->
                    <div class="row m-b-20" style="margin-top: 15px;">
                        <div class="col-lg-4 col-md-6 col-xs-12">
                            <div class="card-box text-center" style="background: #f7f9fa; border: 1px solid #e3e7eb; padding: 20px; border-radius: 6px;">
                                <h4 style="color: #7f8c8d; font-size: 14px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Total Fixed Overhead (<?= htmlspecialchars($last_month_name) ?>)</h4>
                                <h2 style="color: #2c3e50; font-weight: 700; margin: 0;">LKR <?= number_format($overhead_total, 2) ?></h2>
                                <small class="text-muted">General fixed expenses not mapped to projects</small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12">
                            <div class="card-box text-center" style="background: #eef7ee; border: 1px solid #d6e9c6; padding: 20px; border-radius: 6px;">
                                <h4 style="color: #27ae60; font-size: 14px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Calculated Day Cost</h4>
                                <h2 style="color: #27ae60; font-weight: 700; margin: 0;">LKR <?= number_format($day_cost, 2) ?></h2>
                                <small class="text-muted">Overhead divided by calendar days</small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12">
                            <div class="card-box text-center" style="background: #eef3f7; border: 1px solid #bce8f1; padding: 20px; border-radius: 6px;">
                                <h4 style="color: #2980b9; font-size: 14px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Calculated Hour Cost</h4>
                                <h2 style="color: #2980b9; font-weight: 700; margin: 0;">LKR <?= number_format($hour_cost, 2) ?></h2>
                                <small class="text-muted">Day cost divided by 8 working hours</small>
                            </div>
                        </div>
                    </div>

                    <!-- Cost Allocation Table -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-20" style="font-weight: 700; color: #2c3e50;">
                                    Project Overhead Allocation Summary (Active/Unpaid Projects)
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr style="background: #34495e; color: #fff;">
                                                <th>Project Name & Job Card No.</th>
                                                <th>Company & Domain Name</th>
                                                <th style="text-align: right;">Est. Duration</th>
                                                <th style="text-align: right;">Allocated Overhead</th>
                                                <th style="text-align: right;">Direct Expenses</th>
                                                <th style="text-align: right;">Total Cost (Cost Price)</th>
                                                <th style="text-align: right;">Project Value (Sale Price)</th>
                                                <th style="text-align: right;">Customer Outstanding</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($unpaid_projects)): ?>
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted" style="padding: 20px;">No unfinished or unpaid projects found.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php 
                                                $sum_days = 0;
                                                $sum_overhead = 0;
                                                $sum_direct = 0;
                                                $sum_total_cost = 0;
                                                $sum_value = 0;
                                                $sum_outstanding = 0;
                                                foreach ($unpaid_projects as $up): 
                                                    $sum_days += $up->EstimatedDays;
                                                    $sum_overhead += $up->allocated_overhead;
                                                    $sum_direct += $up->direct_expenses;
                                                    $sum_total_cost += $up->total_cost;
                                                    $sum_value += $up->project_value;
                                                    $sum_outstanding += $up->outstanding_balance;
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?= htmlspecialchars($up->Title ?? '') ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?= htmlspecialchars($up->JobCardNo ?? '') ?></small>
                                                        </td>
                                                        <td>
                                                            <strong><?= htmlspecialchars($up->CompanyName ?? 'N/A') ?></strong>
                                                            <?php if (!empty($up->DomainName)): ?>
                                                                <br>
                                                                <small class="text-primary"><i class="fa fa-globe"></i> <?= htmlspecialchars($up->DomainName) ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="text-align: right;"><?= (int)$up->EstimatedDays ?> days / <?= (int)$up->EstimatedDays * 8 ?> hrs</td>
                                                        <td style="text-align: right; font-weight: 500;"><?= number_format($up->allocated_overhead, 2) ?></td>
                                                        <td style="text-align: right;"><?= number_format($up->direct_expenses, 2) ?></td>
                                                        <td style="text-align: right; font-weight: bold; color: #c0392b;"><?= number_format($up->total_cost, 2) ?></td>
                                                        <td style="text-align: right; font-weight: bold; color: #27ae60;"><?= number_format($up->project_value, 2) ?></td>
                                                        <td style="text-align: right; font-weight: 500; color: #e67e22;"><?= number_format($up->outstanding_balance, 2) ?></td>
                                                        <td>
                                                            <span class="label <?= ($up->Status == 'Completed' || $up->Status == 'done') ? 'label-success' : 'label-warning' ?>">
                                                                <?= htmlspecialchars($up->Status ?? 'N/A') ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <?php if (!empty($unpaid_projects)): ?>
                                            <tfoot>
                                                <tr style="background: #f7f9fa; font-weight: bold; border-top: 2px solid #bdc3c7;">
                                                    <td colspan="2" style="text-align: right;">TOTALS:</td>
                                                    <td style="text-align: right;"><?= $sum_days ?> days / <?= $sum_days * 8 ?> hrs</td>
                                                    <td style="text-align: right;"><?= number_format($sum_overhead, 2) ?></td>
                                                    <td style="text-align: right;"><?= number_format($sum_direct, 2) ?></td>
                                                    <td style="text-align: right; color: #c0392b;"><?= number_format($sum_total_cost, 2) ?></td>
                                                    <td style="text-align: right; color: #27ae60;"><?= number_format($sum_value, 2) ?></td>
                                                    <td style="text-align: right; color: #e67e22;"><?= number_format($sum_outstanding, 2) ?></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<script>
    $(document).ready(function () {
        // Toggle collapse state cleanly if header is clicked
        $('.project-header').click(function() {
            var target = $(this).attr('data-target');
            $(target).collapse('toggle');
        });
    });
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
