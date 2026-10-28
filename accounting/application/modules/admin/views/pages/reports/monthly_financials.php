<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<style>
    .kpi-card {
        padding: 20px;
        border-radius: 8px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        transition: transform 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
    }
    .kpi-income {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }
    .kpi-expense {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }
    .kpi-profit {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }
    .kpi-card .kpi-title {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 8px;
    }
    .kpi-card .kpi-value {
        font-size: 28px;
        font-weight: 700;
    }
    .kpi-card .kpi-icon {
        float: right;
        font-size: 36px;
        opacity: 0.3;
        margin-top: 5px;
    }
    .filter-panel {
        background: #fdfdfd;
        border: 1px solid #e3e7eb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .table-report th {
        background-color: #34495e;
        color: #ffffff;
        font-weight: 600;
        text-align: center;
        vertical-align: middle !important;
    }
    .table-report td {
        vertical-align: middle !important;
    }
    .total-row {
        background-color: #f2f4f4 !important;
        font-weight: bold;
        border-top: 2px solid #34495e;
        border-bottom: 2px solid #34495e;
    }
    
    @media print {
        /* Hide navigation elements */
        .topbar, .side-menu, .breadcrumb, .filter-panel, .footer, .kpi-card {
            display: none !important;
        }
        /* Expand content-page to 100% width and reset margins */
        .content-page {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 0 !important;
        }
        .card-box {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        body {
            background: #fff !important;
        }
        .table-report th {
            background-color: #34495e !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .total-row {
            background-color: #f2f4f4 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Monthly Income & Expenses</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Monthly Financial Report</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="filter-panel" style="display: flex; align-items: center; flex-wrap: wrap;">
                        <label for="select-year" style="font-weight: 600; margin: 0; font-size: 15px; color: #2c3e50; margin-right: 10px;">
                            <i class="fa fa-calendar"></i> Select Financial Year:
                        </label>
                        <select id="select-year" class="form-control" style="width: 120px; font-weight: bold; height: 38px; display: inline-block;">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button onclick="window.print()" class="btn btn-primary" style="height: 38px; font-weight: bold; margin-left: 15px;">
                            <i class="fa fa-print"></i> Print Report
                        </button>
                        <a href="<?= base_url('admin/home/export_monthly_financials/'.$year) ?>" class="btn btn-success" style="height: 38px; line-height: 24px; font-weight: bold; margin-left: 8px;">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            <?php
            // Calculate year-to-date totals
            $totalSalesYear = 0;
            $totalOtherIncomeYear = 0;
            $totalIncomeYear = 0;
            $totalReceivedYear = 0;
            $totalExpensesYear = 0;
            $totalProfitYear = 0;

            foreach ($monthly_data as $row) {
                $totalSalesYear += $row['sales'];
                $totalOtherIncomeYear += $row['other_income'];
                $totalIncomeYear += $row['total_income'];
                $totalReceivedYear += $row['received'];
                $totalExpensesYear += $row['expenses'];
                $totalProfitYear += $row['profit'];
            }
            ?>

            <!-- KPI Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="kpi-card kpi-income">
                        <i class="fa fa-line-chart kpi-icon"></i>
                        <div class="kpi-title">Total Income (<?= $year ?>)</div>
                        <div class="kpi-value">LKR <?= number_format($totalIncomeYear, 2) ?></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="kpi-card kpi-expense">
                        <i class="fa fa-shopping-cart kpi-icon"></i>
                        <div class="kpi-title">Total Expenses (<?= $year ?>)</div>
                        <div class="kpi-value">LKR <?= number_format($totalExpensesYear, 2) ?></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="kpi-card kpi-profit">
                        <i class="fa fa-money kpi-icon"></i>
                        <div class="kpi-title">Net Profit / Loss (<?= $year ?>)</div>
                        <div class="kpi-value">LKR <?= number_format($totalProfitYear, 2) ?></div>
                    </div>
                </div>
            </div>

            <!-- Month-wise Report Table -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-20">
                            <i class="fa fa-table"></i> Financial Summary Breakdown for Year: <?= $year ?>
                        </h4>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-report">
                                <thead>
                                    <tr>
                                        <th style="width: 12%;">Month</th>
                                        <th style="width: 14%; text-align: right;">Sales Income (LKR)</th>
                                        <th style="width: 14%; text-align: right;">Other Income (LKR)</th>
                                        <th style="width: 15%; text-align: right;">Total Income (LKR)</th>
                                        <th style="width: 16%; text-align: right;">Received Payment (LKR)</th>
                                        <th style="width: 14%; text-align: right;">Expenses (LKR)</th>
                                        <th style="width: 15%; text-align: right;">Net Profit / Loss (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($monthly_data as $row): 
                                        $profitClass = '';
                                        if ($row['profit'] < 0) {
                                            $profitClass = 'text-danger';
                                        } elseif ($row['profit'] > 0) {
                                            $profitClass = 'text-success';
                                        }
                                    ?>
                                        <tr>
                                            <td style="font-weight: 600; color: #2c3e50; text-align: center;"><?= $row['month_name'] ?></td>
                                            <td style="text-align: right;"><?= number_format($row['sales'], 2) ?></td>
                                            <td style="text-align: right;"><?= number_format($row['other_income'], 2) ?></td>
                                            <td style="text-align: right; font-weight: 500; color: #2c3e50;"><?= number_format($row['total_income'], 2) ?></td>
                                            <td style="text-align: right; color: #27ae60; font-weight: 500;"><?= number_format($row['received'], 2) ?></td>
                                            <td style="text-align: right; color: #c0392b;"><?= number_format($row['expenses'], 2) ?></td>
                                            <td style="text-align: right; font-weight: 600;" class="<?= $profitClass ?>">
                                                <?= number_format($row['profit'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                    <!-- Grand Total Row -->
                                    <?php
                                    $finalProfitClass = '';
                                    if ($totalProfitYear < 0) {
                                        $finalProfitClass = 'text-danger';
                                    } elseif ($totalProfitYear > 0) {
                                        $finalProfitClass = 'text-success';
                                    }
                                    ?>
                                    <tr class="total-row">
                                        <td style="text-align: center; text-transform: uppercase;">Total</td>
                                        <td style="text-align: right;"><?= number_format($totalSalesYear, 2) ?></td>
                                        <td style="text-align: right;"><?= number_format($totalOtherIncomeYear, 2) ?></td>
                                        <td style="text-align: right;"><?= number_format($totalIncomeYear, 2) ?></td>
                                        <td style="text-align: right; color: #27ae60;"><?= number_format($totalReceivedYear, 2) ?></td>
                                        <td style="text-align: right; color: #c0392b;"><?= number_format($totalExpensesYear, 2) ?></td>
                                        <td style="text-align: right;" class="<?= $finalProfitClass ?>"><?= number_format($totalProfitYear, 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
</div>

<?php $this->view('includes/footer_start.php'); ?>

<!-- extra js start -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#select-year').on('change', function() {
            var selectedYear = $(this).val();
            window.location.href = "<?= base_url('admin/home/monthly_financials') ?>/" + selectedYear;
        });
    });
</script>
<!-- extra js end -->

<?php $this->view('includes/footer_end.php'); ?>
