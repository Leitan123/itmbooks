<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>

<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>

<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<style>
    @media print {
        /* Hide navigation elements */
        .topbar, .side-menu, .breadcrumb, .filter-panel, .footer {
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
    }
</style>

<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Profit & Loss Statement</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Profit & Loss</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-panel" style="margin-bottom: 20px; background: #fdfdfd; border: 1px solid #e3e7eb; padding: 15px; border-radius: 8px;">
                <form method="GET" action="<?= base_url('admin/profit_loss') ?>" id="filterForm" class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" value="<?= htmlspecialchars($from_date) ?>">
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="<?= htmlspecialchars($to_date) ?>">
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-success" style="font-weight: bold; height: 38px; width: 100px;">Filter</button>
                        <button type="button" onclick="resetFilter()" class="btn btn-default" style="font-weight: bold; height: 38px; width: 100px; margin-left: 5px;">Reset</button>
                        <button type="button" onclick="window.print()" class="btn btn-primary" style="font-weight: bold; height: 38px; width: 100px; margin-left: 5px;"><i class="fa fa-print"></i> Print</button>
                        <a href="<?= base_url('admin/profit_loss/export_csv?from_date='.$from_date.'&to_date='.$to_date) ?>" class="btn btn-info" style="font-weight: bold; height: 38px; line-height: 24px; width: 120px; margin-left: 5px;"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                    </div>
                </form>
            </div>

            <script>
                function resetFilter() {
                    window.location.href = '<?= base_url("admin/profit_loss") ?>';
                }
            </script>
            <!-- Profit & Loss Table -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">

                            Financial Performance

                        </h4>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>

                                    <tr style="background:#4F81BD; color:#fff;">

                                        <th>

                                            Description

                                        </th>

                                        <th style="text-align:right;">

                                            Amount

                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr style="background:#dff0d8;">
                                        <th colspan="2">
                                            INCOME
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>Sales Income</td>
                                        <td style="text-align:right;">
                                            <?= number_format($sales_income,2) ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Other Income</td>
                                        <td style="text-align:right;">
                                            <?= number_format($other_income,2) ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Total Income</th>
                                        <th style="text-align:right;color:green;">
                                            <?= number_format($income,2) ?>
                                        </th>
                                    </tr>

                                    <tr style="background:#f2dede;">
                                        <th colspan="2">
                                            EXPENSES
                                        </th>
                                    </tr>

                                    <?php foreach($expense_accounts as $row){ ?>

                                    <tr>

                                    <td>
                                        <a href="<?= base_url('admin/general_ledger?account=' . $row->AccountID) ?>">
                                            <?= htmlspecialchars($row->AccountName) ?>
                                        </a>
                                    </td>

                                    <td style="text-align:right;">

                                        <?= number_format($row->Amount,2) ?>

                                    </td>
                                  

                                    </tr>

                                    <?php } ?>

                                    <tr>

                               
                                    <th>Total Expenses</th>

                                    <th style="text-align:right;color:red;">

                                        <?= number_format($expense,2) ?>

                                    </th>
                               

                                    </tr>

                                    <tr style="background:#d9edf7;">

                                  
                                    <th>NET PROFIT / LOSS</th>

                                    <th style="text-align:right;">

                                        <?= number_format($income - $expense,2) ?>

                                    </th>
                              

                                    </tr>

                                    </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Summary Cards -->
            <div class="row">

                <!-- Income -->
                <div class="col-lg-4">

                    <div class="card-box"
                         style="background:#dff0d8;">

                        <h4 style="color:#3c763d;">

                            Total Income

                        </h4>

                        <h2 style="color:#3c763d;">

                            <?= number_format($income,2) ?>

                        </h2>

                    </div>

                </div>

                <!-- Expenses -->
                <div class="col-lg-4">

                    <div class="card-box"
                         style="background:#f2dede;">

                        <h4 style="color:#a94442;">

                            Total Expenses

                        </h4>

                        <h2 style="color:#a94442;">

                            <?= number_format($expense,2) ?>

                        </h2>

                    </div>

                </div>

                <!-- Net Profit -->
                <div class="col-lg-4">

                    <?php

                    $netProfit =
                        $income - $expense;

                    ?>

                    <div class="card-box"
                         style="background:#d9edf7;">

                        <h4 style="color:#31708f;">

                            Net Profit

                        </h4>

                        <h2 style="color:#31708f;">

                            <?= number_format($netProfit,2) ?>

                        </h2>

                    </div>

                </div>

            </div>

            <!-- Profit Status -->
            <div class="row">

                <div class="col-lg-12">

                    <?php if($netProfit >= 0){ ?>

                        <div class="card-box"
                             style="background:#dff0d8;">

                            <h3 style="color:#3c763d;">

                                Business Running in Profit

                            </h3>

                        </div>

                    <?php } else { ?>

                        <div class="card-box"
                             style="background:#f2dede;">

                            <h3 style="color:#a94442;">

                                Business Running in Loss

                            </h3>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php $this->view('includes/footer_start.php') ?>

<!-- extra js start -->

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

    $(document).ready(function () {

        $('.table').DataTable({

            "paging": false,
            "searching": false,
            "info": false

        });

    });

</script>

<!-- extra js end -->

<?php $this->view('includes/footer_end.php') ?>