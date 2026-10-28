<?php $this->view('includes/header_start.php'); ?>
<?php
if (!function_exists('get_growth_html')) {
    function get_growth_html($current, $previous, $is_expense = false) {
        if ($previous == 0) {
            if ($current > 0) {
                return '<span class="zoho-growth-pill ' . ($is_expense ? 'zoho-growth-down' : 'zoho-growth-up') . '"><i class="fa fa-arrow-up"></i> +100%</span>';
            }
            return '';
        }
        $percent = (($current - $previous) / $previous) * 100;
        if ($percent > 0) {
            $badge_class = $is_expense ? 'zoho-growth-down' : 'zoho-growth-up';
            return '<span class="zoho-growth-pill ' . $badge_class . '"><i class="fa fa-arrow-up"></i> +' . round($percent, 1) . '%</span>';
        } elseif ($percent < 0) {
            $badge_class = $is_expense ? 'zoho-growth-up' : 'zoho-growth-down';
            return '<span class="zoho-growth-pill ' . $badge_class . '"><i class="fa fa-arrow-down"></i> ' . round($percent, 1) . '%</span>';
        }
        return '<span class="zoho-growth-pill zoho-growth-neutral">0%</span>';
    }
}
?>

<link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet" type="text/css" />

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- PAGE HEADER -->
            <div class="zoho-page-header" style="background: transparent; box-shadow: none; border-bottom: none; margin-bottom: 25px; padding: 0; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <!-- Rounded Icon container like Zoho -->
                    <div style="background: #fff; width: 50px; height: 50px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #64748b; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <i class="fa fa-building-o"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 2px 0;">Hello, <?= isset($user->Name) ? $user->Name : $this->session->userdata('user')->Name ?></h4>
                        <p style="font-size: 13px; color: #64748b; margin: 0; font-weight: 500;"><?= title ?></p>
                    </div>
                </div>

                <div class="zoho-breadcrumb" style="background: #f1f5f9; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                    <a href="<?= base_url('admin') ?>" style="color: #64748b; text-decoration: none;">Dashboard</a>
                    <span style="margin: 0 4px; color: #cbd5e1;">/</span>
                    <span style="color: #0f172a; font-weight: 600;">Overview</span>
                </div>
            </div>

            <!-- TODAY'S SALES STRIP -->
            <div class="row">
                <div class="col-md-4">
                    <div class="zoho-kpi-card" style="border-left: 4px solid #0ea5e9;">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Today's Sales (Total)</div>
                                <div class="zoho-kpi-value" style="color: #0ea5e9;">
                                    LKR <?= isset($todaySalesTotal) ? number_format($todaySalesTotal, 2) : '0.00' ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="zoho-kpi-card" style="border-left: 4px solid #10b981;">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Today's Cash Sales</div>
                                <div class="zoho-kpi-value" style="color: #10b981;">
                                    LKR <?= isset($todayCashTotal) ? number_format($todayCashTotal, 2) : '0.00' ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="zoho-kpi-card" style="border-left: 4px solid #f59e0b;">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Today's Credit Sales</div>
                                <div class="zoho-kpi-value" style="color: #f59e0b;">
                                    LKR <?= isset($todayCreditTotal) ? number_format($todayCreditTotal, 2) : '0.00' ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI SUMMARY CARDS - ROW 1 (REVENUE & CASH INFLOW) -->
            <div class="row">
                <!-- TOTAL SALES -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-sales">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Sales (This Month)</div>
                                <div class="zoho-kpi-value">
                                    LKR <?= number_format($salesMonth, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>

                        <div class="zoho-kpi-subtext">
                            <span>Growth: <?= get_growth_html($salesMonth, $salesPrevMonth) ?></span>
                            <span>This Year: <strong><?= number_format($salesYear, 0) ?></strong></span>
                        </div>

                        <?php $salesProgress = min(($salesMonth / 1000000) * 100, 100); ?>
                        <div class="zoho-progress-track">
                            <div class="zoho-progress-fill" style="width: <?= $salesProgress ?>%;"></div>
                        </div>
                        <div class="zoho-kpi-subtext" style="font-size: 11px;">
                            <span>Monthly Target (1.0M)</span>
                            <span style="color: #0284c7; font-weight: 700;"><?= round($salesProgress, 0) ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- RECEIVED -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-received" onclick="window.location.href='<?= base_url('admin/customer_payments/') ?>'" style="cursor: pointer;">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Received (This Month)</div>
                                <div class="zoho-kpi-value">
                                    LKR <?= number_format($receivedMonth, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-wallet"></i>
                            </div>
                        </div>

                        <div class="zoho-kpi-subtext">
                            <span>Growth: <?= get_growth_html($receivedMonth, $receivedPrevMonth) ?></span>
                            <span>This Year: <strong><?= number_format($receivedYear, 0) ?></strong></span>
                        </div>

                        <?php $receivedProgress = min(($receivedMonth / 800000) * 100, 100); ?>
                        <div class="zoho-progress-track">
                            <div class="zoho-progress-fill" style="width: <?= $receivedProgress ?>%;"></div>
                        </div>
                        <div class="zoho-kpi-subtext" style="font-size: 11px;">
                            <span>Collection Target (800K)</span>
                            <span style="color: #059669; font-weight: 700;"><?= round($receivedProgress, 0) ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- TOTAL RECEIVABLES (DUE) -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-due">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Accounts Receivable</div>
                                <div class="zoho-kpi-value" style="color: #d97706;">
                                    LKR <?= number_format($totalDueAmount, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <div class="zoho-kpi-subtext">
                            <span>Total Customer Outstanding</span>
                            <span class="status-pill status-pill-warning">Receivables</span>
                        </div>
                    </div>
                </div>

                <!-- EXPENSES -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-expenses">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Expenses (This Month)</div>
                                <div class="zoho-kpi-value" style="color: #dc2626;">
                                    LKR <?= number_format($expensesMonth, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                        <div class="zoho-kpi-subtext">
                            <span>Change: <?= get_growth_html($expensesMonth, $expensesPrevMonth, true) ?></span>
                            <span>This Year: <strong><?= number_format($expensesYear, 0) ?></strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI SUMMARY CARDS - ROW 2 (FINANCIAL HEALTH & PENDING ACTIONS) -->
            <div class="row">
                <!-- NET PROFIT -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-profit">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Net Profit (This Month)</div>
                                <div class="zoho-kpi-value" style="color: #4f46e5;">
                                    LKR <?= number_format($netProfitMonth, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                        <div class="zoho-kpi-subtext">
                            <span>Growth: <?= get_growth_html($netProfitMonth, $netProfitPrevMonth) ?></span>
                            <span>This Year: <strong><?= number_format($netProfitYear, 0) ?></strong></span>
                        </div>
                    </div>
                </div>

                <!-- CASH FLOW -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-cashflow" onclick="window.location.href='<?= base_url('admin/cash_flow?from_date='.date('Y-m-01').'&to_date='.date('Y-m-t')) ?>'" style="cursor: pointer;">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Cash Flow (This Month)</div>
                                <div class="zoho-kpi-value" style="color: #0d9488;">
                                    LKR <?= number_format($cashFlowMonth, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-exchange"></i>
                            </div>
                        </div>
                        <div class="zoho-kpi-subtext">
                            <span>Growth: <?= get_growth_html($cashFlowMonth, $cashFlowPrevMonth) ?></span>
                            <span>This Year: <strong><?= number_format($cashFlowYear, 0) ?></strong></span>
                        </div>
                    </div>
                </div>

                <!-- ACCOUNTS PAYABLE (VENDOR DUE) -->
                <div class="col-xl-3 col-md-6">
                    <div class="zoho-kpi-card zoho-kpi-payable">
                        <div class="zoho-kpi-header">
                            <div>
                                <div class="zoho-kpi-label">Accounts Payable</div>
                                <div class="zoho-kpi-value" style="color: #e11d48;">
                                    LKR <?= number_format($totalVendorDue, 2) ?>
                                </div>
                            </div>
                            <div class="zoho-kpi-icon-box">
                                <i class="fa fa-truck"></i>
                            </div>
                        </div>
                        <div class="zoho-kpi-subtext">
                            <span>Total Vendor Outstanding</span>
                            <span class="status-pill status-pill-danger">Payables</span>
                        </div>
                    </div>
                </div>

                <!-- PENDING QUOTATIONS -->
                <div class="col-xl-3 col-md-6">
                    <a href="<?= base_url('admin/quotation') ?>" style="text-decoration: none;">
                        <div class="zoho-kpi-card zoho-kpi-quotation">
                            <div class="zoho-kpi-header">
                                <div>
                                    <div class="zoho-kpi-label">Pending Quotations</div>
                                    <div class="zoho-kpi-value" style="color: #d97706;">
                                        <?= isset($totalPendingQuotationsCount) ? $totalPendingQuotationsCount : 0 ?>
                                    </div>
                                </div>
                                <div class="zoho-kpi-icon-box">
                                    <i class="fa fa-file-text-o"></i>
                                </div>
                            </div>
                            <div class="zoho-kpi-subtext">
                                <span>Valued At</span>
                                <strong>LKR <?= number_format(isset($totalPendingQuotationsAmount) ? $totalPendingQuotationsAmount : 0, 2) ?></strong>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- FINANCIAL SUMMARY STRIP (DUAL BLUE GRADIENT - #019BCB TO #43CEE8) -->
            <div class="zoho-summary-strip">
                <div class="zoho-summary-title">
                    <i class="fa fa-calculator"></i> All-Time Business Financial Snapshot
                </div>
                <div class="zoho-summary-grid">
                    <div class="zoho-summary-item">
                        <div class="zoho-summary-item-label">Total Sales</div>
                        <div class="zoho-summary-item-val" style="color: #ffffff;">
                            LKR <?= number_format($totalSales, 2) ?>
                        </div>
                    </div>
                    <div class="zoho-summary-divider"></div>

                    <div class="zoho-summary-item">
                        <div class="zoho-summary-item-label">Total Expenses</div>
                        <div class="zoho-summary-item-val" style="color: #ffe4e6;">
                            LKR <?= number_format($totalExpenses, 2) ?>
                        </div>
                    </div>
                    <div class="zoho-summary-divider"></div>

                    <div class="zoho-summary-item">
                        <div class="zoho-summary-item-label">Net Profit</div>
                        <div class="zoho-summary-item-val" style="color: #dcfce7;">
                            LKR <?= number_format($netProfit, 2) ?>
                        </div>
                    </div>
                    <div class="zoho-summary-divider"></div>

                    <div class="zoho-summary-item">
                        <div class="zoho-summary-item-label">Total Cash Flow</div>
                        <div class="zoho-summary-item-val" style="color: #e0f2fe;">
                            LKR <?= number_format($cashFlow, 2) ?>
                        </div>
                    </div>
                    <div class="zoho-summary-divider"></div>

                    <div class="zoho-summary-item">
                        <div class="zoho-summary-item-label">Cash In Hand</div>
                        <div class="zoho-summary-item-val" style="color: #fef9c3;">
                            LKR <?= number_format($cashInHand, 2) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTIONABLE DUES & QUOTATIONS TABLES SECTION -->
            
            <!-- CUSTOMER RECEIVABLES & VENDOR PAYABLES SIDE BY SIDE -->
            <div class="row">
                <!-- CUSTOMER PENDING PAYMENTS (RECEIVABLES) -->
                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-clock-o" style="color: #d97706;"></i> Customer Pending Payments (Receivables)</span>
                            <span class="status-pill status-pill-warning">
                                <?= !empty($pendingPaymentsList) ? count($pendingPaymentsList) : 0 ?> Overdue
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Company Name</th>
                                        <th width="140" style="text-align: right;">Due Amount (LKR)</th>
                                        <th width="110" class="text-center">Overdue Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($pendingPaymentsList)): ?>
                                        <?php $counter = 1; ?>
                                        <?php $totalPending = 0; ?>
                                        <?php foreach($pendingPaymentsList as $row): ?>
                                            <?php $totalPending += floatval($row->DueAmount); ?>
                                            <tr>
                                                <td class="text-center"><?= $counter++ ?></td>
                                                <td><strong><?= htmlspecialchars($row->CompanyName ?? '') ?></strong></td>
                                                <td style="text-align: right; color: #dc2626; font-weight: 700;">
                                                    <?= number_format($row->DueAmount, 2) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($row->DaysOverdue > 30): ?>
                                                        <span class="status-pill status-pill-danger">
                                                            <i class="fa fa-exclamation-triangle"></i> <?= $row->DaysOverdue ?> days
                                                        </span>
                                                    <?php elseif($row->DaysOverdue > 0): ?>
                                                        <span class="status-pill status-pill-warning">
                                                            <i class="fa fa-clock-o"></i> <?= $row->DaysOverdue ?> days
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="status-pill status-pill-success">
                                                            <i class="fa fa-check"></i> Due soon
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center" style="padding: 20px; color: #64748b;">
                                                <i class="fa fa-check-circle-o" style="color: #10b981; margin-right: 5px;"></i> No pending customer payments
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(!empty($pendingPaymentsList)): ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">Total Receivables:</th>
                                        <th style="text-align: right; color: #dc2626;">
                                            <?= number_format($totalPending, 2) ?>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- VENDOR OUTSTANDING PAYMENTS (PAYABLES) -->
                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-truck" style="color: #e11d48;"></i> Vendor Outstanding Dues (Payables)</span>
                            <span class="status-pill status-pill-danger">
                                <?= !empty($vendorPendingList) ? count($vendorPendingList) : 0 ?> Vendors
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Vendor Name</th>
                                        <th width="150" style="text-align: right;">Due Amount (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($vendorPendingList)): ?>
                                        <?php $counter = 1; ?>
                                        <?php $totalVendorPending = 0; ?>
                                        <?php foreach($vendorPendingList as $row): ?>
                                            <?php $totalVendorPending += floatval($row->DueAmount); ?>
                                            <tr>
                                                <td class="text-center"><?= $counter++ ?></td>
                                                <td><strong><?= htmlspecialchars($row->VendorName ?? '') ?></strong></td>
                                                <td style="text-align: right; color: #dc2626; font-weight: 700;">
                                                    <?= number_format($row->DueAmount, 2) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center" style="padding: 20px; color: #64748b;">
                                                <i class="fa fa-check-circle-o" style="color: #10b981; margin-right: 5px;"></i> No outstanding vendor dues
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(!empty($vendorPendingList)): ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">Total Payables:</th>
                                        <th style="text-align: right; color: #dc2626;">
                                            <?= number_format($totalVendorPending, 2) ?>
                                        </th>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PENDING QUOTATIONS FULL WIDTH TABLE -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span>
                                <i class="fa fa-file-text-o" style="color: #d97706;"></i> Pending Quotations (Aging Breakdown)
                                <span class="status-pill status-pill-warning" style="margin-left: 8px;">
                                    <?= !empty($pendingQuotationsList) ? count($pendingQuotationsList) : 0 ?> Pending
                                </span>
                            </span>
                            <div>
                                <a href="<?= base_url('admin/quotation') ?>" class="btn btn-zoho btn-zoho-outline m-r-5">
                                    <i class="fa fa-list"></i> View All
                                </a>
                                <a href="<?= base_url('admin/quotation/create') ?>" class="btn btn-zoho btn-zoho-success">
                                    <i class="fa fa-plus"></i> Create Quotation
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th width="130">Quotation No</th>
                                        <th width="100">Date</th>
                                        <th>Company / Customer</th>
                                        <th>Service / Items</th>
                                        <th width="140" style="text-align: right;">Grand Total (LKR)</th>
                                        <th width="120" class="text-center">Pending Days</th>
                                        <th width="130" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($pendingQuotationsList)): ?>
                                        <?php $qCounter = 1; ?>
                                        <?php $sumPendingTotal = 0; ?>
                                        <?php foreach($pendingQuotationsList as $qRow): ?>
                                            <?php $sumPendingTotal += floatval($qRow->GrandTotal); ?>
                                            <tr>
                                                <td class="text-center"><?= $qCounter++ ?></td>
                                                <td><strong><?= htmlspecialchars($qRow->QuotationNo ?? '') ?></strong></td>
                                                <td><?= date('Y-m-d', strtotime($qRow->QuotationDate)) ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($qRow->CompanyName ?? '') ?></strong>
                                                    <?php if(!empty($qRow->DomainName)): ?>
                                                        <br>
                                                        <small style="color: #2563eb;"><?= htmlspecialchars($qRow->DomainName ?? '') ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($qRow->ItemName ?? '-') ?></td>
                                                <td style="text-align: right; font-weight: 700; color: #0f172a;">
                                                    <?= number_format($qRow->GrandTotal, 2) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($qRow->PendingDays > 30): ?>
                                                        <span class="status-pill status-pill-danger">
                                                            <i class="fa fa-exclamation-circle"></i> <?= $qRow->PendingDays ?> Days
                                                        </span>
                                                    <?php elseif($qRow->PendingDays > 14): ?>
                                                        <span class="status-pill status-pill-warning">
                                                            <i class="fa fa-clock-o"></i> <?= $qRow->PendingDays ?> Days
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="status-pill status-pill-info">
                                                            <i class="fa fa-clock-o"></i> <?= $qRow->PendingDays ?> Days
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/quotation/edit/'.$qRow->QuotationID) ?>" class="btn btn-xs btn-warning" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/quotation/print/'.$qRow->QuotationID) ?>" class="btn btn-xs btn-success" target="_blank" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/job_card/create/'.$qRow->QuotationID) ?>" class="btn btn-xs btn-primary" title="Convert to Job Card">
                                                        <i class="fa fa-briefcase"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center" style="padding: 20px; color: #64748b;">
                                                <i class="fa fa-check-circle-o" style="color: #10b981; margin-right: 5px;"></i> No pending quotations found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(!empty($pendingQuotationsList)): ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align: right;">Total Pending Quotation Value:</th>
                                        <th style="text-align: right; color: #dc2626;">
                                            <?= number_format($sumPendingTotal, 2) ?>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS SECTION: MONTHLY SALES & PROFIT -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-bar-chart" style="color: #0284c7;"></i> Monthly Sales Overview</span>
                        </div>
                        <canvas id="monthlySalesChart" height="160"></canvas>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-line-chart" style="color: #10b981;"></i> Monthly Profit Analysis</span>
                        </div>
                        <canvas id="monthlyProfitChart" height="160"></canvas>
                    </div>
                </div>
            </div>

            <!-- TOP PRODUCTS & BANK BALANCES -->
            <div class="row">
                <!-- TOP SELLING PRODUCTS -->
                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-star" style="color: #f59e0b;"></i> Top Selling Products</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th width="80">Item ID</th>
                                        <th>Item Name</th>
                                        <th style="text-align: right;">Quantity Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($topProducts)): ?>
                                        <?php foreach($topProducts as $row): ?>
                                        <tr>
                                            <td><span class="status-pill status-pill-neutral"><?= $row->ItemID ?></span></td>
                                            <td><strong><?= htmlspecialchars($row->ItemName ?? '') ?></strong></td>
                                            <td style="text-align: right; font-weight: 700; color: #0284c7;">
                                                <?= number_format($row->TotalQty, 2) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center" style="padding: 15px; color: #64748b;">No products found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- BANK WISE BALANCE -->
                <div class="col-lg-6">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-university" style="color: #2563eb;"></i> Bank Wise Balance</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th>Bank Name & Account</th>
                                        <th style="text-align: right;">Balance (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grandTotal = 0;
                                    if(!empty($bankBalances)):
                                        foreach($bankBalances as $bank):
                                            $grandTotal += $bank['Balance'];
                                            $balanceClass = '';
                                            $balanceIcon = '';
                                            if($bank['Balance'] < 0) {
                                                $balanceClass = 'color: #dc2626;';
                                                $balanceIcon = 'fa fa-arrow-down';
                                            } elseif($bank['Balance'] > 0) {
                                                $balanceClass = 'color: #059669;';
                                                $balanceIcon = 'fa fa-arrow-up';
                                            } else {
                                                $balanceClass = 'color: #64748b;';
                                                $balanceIcon = 'fa fa-minus';
                                            }
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('admin/home/bank_ledger/'.$bank['BankID']) ?>" 
                                               style="color: #0284c7; text-decoration: none; font-weight: 600;">
                                                <i class="fa fa-building-o" style="margin-right: 4px;"></i> 
                                                <?= htmlspecialchars($bank['BankName'] ?? '') ?> - <?= htmlspecialchars($bank['AccountNumber'] ?? '') ?>
                                            </a>
                                            <br><small style="color: #64748b;"><?= htmlspecialchars($bank['AccountName'] ?? '') ?></small>
                                        </td>
                                        <td style="text-align: right; font-weight: 700; <?= $balanceClass ?>">
                                            <i class="<?= $balanceIcon ?>"></i> <?= number_format($bank['Balance'], 2) ?>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding: 15px; color: #64748b;">No bank accounts found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(!empty($bankBalances)): ?>
                                <tfoot>
                                    <tr>
                                        <td style="text-align: right;"><strong>Total Bank Liquidity:</strong></td>
                                        <td style="text-align: right; font-size: 15px; <?= $grandTotal < 0 ? 'color: #dc2626;' : 'color: #059669;' ?>">
                                            <strong><?= number_format($grandTotal, 2) ?></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOP CUSTOMERS SECTION -->
            <?php if(!empty($topCustomers)): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-users" style="color: #6366f1;"></i> Top Performing Customers</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th>Customer Company</th>
                                        <th style="text-align: right;">Total Generated Sales (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topCustomers as $row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row->CompanyName ?? '') ?></strong></td>
                                        <td style="text-align: right; font-weight: 700; color: #059669;">
                                            LKR <?= number_format($row->TotalSales, 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ANNUAL PERFORMANCE (2017 - CURRENT YEAR) -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-area-chart" style="color: #2563eb;"></i> Annual Financial History</span>
                        </div>
                        
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th>Metric / Year</th>
                                        <?php 
                                        $years = json_decode($chartYears, true);
                                        foreach($years as $year): 
                                        ?>
                                        <th style="text-align: right;"><?= $year ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Turnover (LKR)</strong></td>
                                        <?php 
                                        $values = json_decode($chartTurnover, true);
                                        foreach($values as $value): 
                                        ?>
                                        <td style="text-align: right; <?= $value > 10000000 ? 'color: #dc2626; font-weight: 700;' : '' ?>">
                                            <?= number_format($value, 2) ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td><strong>Received Payments (LKR)</strong></td>
                                        <?php 
                                        $payValues = json_decode($chartPayments, true);
                                        foreach($payValues as $value): 
                                        ?>
                                        <td style="text-align: right; <?= $value > 10000000 ? 'color: #059669; font-weight: 700;' : '' ?>">
                                            <?= number_format($value, 2) ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td><strong>Collection Rate</strong></td>
                                        <?php 
                                        foreach($values as $idx => $tVal): 
                                            $pVal = $payValues[$idx];
                                            $rate = $tVal > 0 ? ($pVal / $tVal) * 100 : 0;
                                        ?>
                                        <td style="text-align: right; font-weight: 700;">
                                            <span class="status-pill <?= $rate < 90 ? 'status-pill-danger' : 'status-pill-success' ?>">
                                                <?= number_format($rate, 1) ?>%
                                            </span>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <canvas id="annualTurnoverChart" height="90" style="margin-top: 15px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- MULTI-YEAR INCOME SUMMARY -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="zoho-card">
                        <div class="zoho-card-title">
                            <span><i class="fa fa-line-chart" style="color: #d97706;"></i> Multi-Year Monthly Income Trends</span>
                        </div>
                        
                        <div class="table-responsive" style="margin-bottom: 20px;">
                            <table class="table zoho-table">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th style="text-align: right;">Jan</th>
                                        <th style="text-align: right;">Feb</th>
                                        <th style="text-align: right;">Mar</th>
                                        <th style="text-align: right;">Apr</th>
                                        <th style="text-align: right;">May</th>
                                        <th style="text-align: right;">Jun</th>
                                        <th style="text-align: right;">Jul</th>
                                        <th style="text-align: right;">Aug</th>
                                        <th style="text-align: right;">Sep</th>
                                        <th style="text-align: right;">Oct</th>
                                        <th style="text-align: right;">Nov</th>
                                        <th style="text-align: right;">Dec</th>
                                        <th style="text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $datasetsDecoded = json_decode($chartDatasets, true);
                                    foreach($datasetsDecoded as $ds): 
                                        $yearLabel = $ds['label'];
                                        $yearData = $ds['data'];
                                        $yearSum = array_sum(array_map('floatval', $yearData));
                                        
                                        // Highlight current year
                                        $isCurrentYear = ($yearLabel == date('Y'));
                                        $rowStyle = $isCurrentYear ? 'background: #fefce8; font-weight: 700;' : '';
                                        $pillClass = $isCurrentYear ? 'status-pill-warning' : 'status-pill-success';
                                        $sumStyle = $isCurrentYear ? 'color: #d97706;' : '';
                                    ?>
                                    <tr style="<?= $rowStyle ?>">
                                        <td><span class="status-pill <?= $pillClass ?>"><?= $yearLabel ?></span></td>
                                        <?php foreach($yearData as $mAmount): ?>
                                            <td style="text-align: right;"><?= floatval($mAmount) > 0 ? number_format(floatval($mAmount), 2) : '-' ?></td>
                                        <?php endforeach; ?>
                                        <td style="text-align: right; <?= $sumStyle ?>">
                                            <?= number_format($yearSum, 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div style="position: relative; height: 320px;">
                                    <canvas id="multiYearLineChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div style="position: relative; height: 320px;">
                                    <canvas id="yearlyPieChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// MONTHLY SALES CHART
new Chart(
    document.getElementById('monthlySalesChart'),
    {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Monthly Sales (LKR)',
                data: <?= $monthlySales ?>,
                backgroundColor: 'rgba(2, 132, 199, 0.85)',
                borderColor: '#0284c7',
                borderRadius: 4,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    }
);

// MONTHLY PROFIT CHART
new Chart(
    document.getElementById('monthlyProfitChart'),
    {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Monthly Profit (LKR)',
                data: <?= $monthlyProfit ?>,
                fill: true,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    }
);

// ANNUAL TURNOVER CHART
var annualCtx = document.getElementById('annualTurnoverChart').getContext('2d');
new Chart(annualCtx, {
    type: 'line',
    data: {
        labels: <?= $chartYears ?>,
        datasets: [{
            label: 'Annual Turnover (LKR)',
            data: <?= $chartTurnover ?>,
            fill: true,
            backgroundColor: 'rgba(37, 99, 235, 0.08)',
            borderColor: '#2563eb',
            borderWidth: 2,
            tension: 0.2,
            pointRadius: 4
        }, {
            label: 'Received Payments (LKR)',
            data: <?= $chartPayments ?>,
            fill: true,
            backgroundColor: 'rgba(16, 185, 129, 0.08)',
            borderColor: '#10b981',
            borderWidth: 2,
            tension: 0.2,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'LKR ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// MULTI-YEAR LINE CHART
var lineCtx = document.getElementById('multiYearLineChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: <?= $chartDatasets ?>
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'LKR ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// YEARLY PIE CHART
var pieCtx = document.getElementById('yearlyPieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: <?= $pieLabels ?>,
        datasets: [{
            data: <?= $pieData ?>,
            backgroundColor: <?= $pieColors ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>

<?php $this->view('includes/footer_end.php') ?>