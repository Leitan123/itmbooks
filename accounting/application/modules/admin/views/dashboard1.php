<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url('assets/css/dashboard.css') ?>"
      rel="stylesheet"
      type="text/css" />

<style>
    .pending-table {
        margin-top: 20px;
    }
    .pending-table .table {
        margin-bottom: 0;
    }
    .badge-danger {
        background-color: #d9534f;
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-size: 12px;
    }
    .badge-warning {
        background-color: #f0ad4e;
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-size: 12px;
    }
    .badge-success {
        background-color: #5cb85c;
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-size: 12px;
    }
</style>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- PAGE TITLE -->
            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            ERP Dashboard

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li class="active">

                                ERP Overview

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <!-- SUMMARY CARDS - ROW 1 -->
            <div class="row">

                <!-- TOTAL SALES -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#d9edf7;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-shopping-cart dashboard-icon"
                                   style="color:#31708f;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#31708f;
                                           font-weight:bold;">

                                    <?= number_format($totalSales,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Total Sales

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- RECEIVED -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#dff0d8;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-wallet dashboard-icon"
                                   style="color:#3c763d;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#3c763d;
                                           font-weight:bold;">

                                    <?= number_format($totalPaidAmount,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Received Amount

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- DUE -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#fcf8e3;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-chart-line dashboard-icon"
                                   style="color:#8a6d3b;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#8a6d3b;
                                           font-weight:bold;">

                                    <?= number_format($totalDueAmount,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Total Due

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- EXPENSES -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#f2dede;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-money-bill dashboard-icon"
                                   style="color:#a94442;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#a94442;
                                           font-weight:bold;">

                                    <?= number_format($totalExpenses,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Total Expenses

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SUMMARY CARDS - ROW 2 -->
            <div class="row">

                <!-- NET PROFIT -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#d9edf7;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-chart-pie dashboard-icon"
                                   style="color:#31708f;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#31708f;
                                           font-weight:bold;">

                                    <?= number_format($netProfit,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Net Profit

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- CASH FLOW -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#dff0d8;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-chart-area dashboard-icon"
                                   style="color:#3c763d;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#3c763d;
                                           font-weight:bold;">

                                    <?= number_format($cashFlow,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Cash Flow

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- PENDING PAYMENT (CUSTOMER DUE) -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#fcf8e3;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-clock-o dashboard-icon"
                                   style="color:#8a6d3b;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#8a6d3b;
                                           font-weight:bold;">

                                    <?= number_format($pendingPayment,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Pending Payment

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- CASH IN HAND -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#fff3cd;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-money-bill-wave dashboard-icon"
                                   style="color:#856404;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#856404;
                                           font-weight:bold;">

                                    <?= number_format($cashInHand,2) ?>

                                </h2>

                                <p class="text-muted">

                                    Cash In Hand

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SUMMARY CARDS - ROW 3 -->
            <div class="row">

                <!-- ACTIVE CUSTOMERS -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#eeeeee;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-users dashboard-icon"
                                   style="color:#555;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#555;
                                           font-weight:bold;">

                                    <?= $total_customers ?>

                                </h2>

                                <p class="text-muted">

                                    Active Customers

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ACTIVE VENDORS -->
                <div class="col-xl-3 col-md-6">

                    <div class="card-box"
                         style="background:#eeeeee;">

                        <div class="widget-chart-1">

                            <div class="widget-chart-box-1 float-left">

                                <i class="fa fa-truck dashboard-icon"
                                   style="color:#555;"></i>

                            </div>

                            <div class="widget-detail-1 text-right">

                                <h2 style="color:#555;
                                           font-weight:bold;">

                                    <?= $total_vendors ?>

                                </h2>

                                <p class="text-muted">

                                    Active Vendors

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FINANCIAL SUMMARY -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box"
                         style="background:#fcf8e3;">

                        <h4 style="color:#8a6d3b;">

                            Financial Summary

                        </h4>

                        <p style="font-size:16px;">

                            Total Sales :
                            <strong>

                                <?= number_format($totalSales,2) ?>

                            </strong>

                            |

                            Expenses :
                            <strong>

                                <?= number_format($totalExpenses,2) ?>

                            </strong>

                            |

                            Net Profit :
                            <strong>

                                <?= number_format($netProfit,2) ?>

                            </strong>

                            |

                            Cash Flow :
                            <strong>

                                <?= number_format($cashFlow,2) ?>

                            </strong>

                            |

                            Cash In Hand :
                            <strong>
                                <?= number_format($cashInHand,2) ?>
                            </strong>

                        </p>

                    </div>

                </div>

            </div>


            <!-- CHARTS -->
            <div class="row">

                <!-- MONTHLY SALES -->
                <div class="col-lg-6">

                    <div class="card-box">

                        <h4 class="header-title">

                            Monthly Sales Overview

                        </h4>

                        <canvas id="monthlySalesChart"
                                height="150"></canvas>

                    </div>

                </div>


                <!-- MONTHLY PROFIT -->
                <div class="col-lg-6">

                    <div class="card-box">

                        <h4 class="header-title">

                            Monthly Profit Analysis

                        </h4>

                        <canvas id="monthlyProfitChart"
                                height="150"></canvas>

                    </div>

                </div>

            </div>


            <!-- TOP CUSTOMERS -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title">

                            Top Customers

                        </h4>

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <thead>

                                    <tr style="background:#4F81BD;
                                               color:#fff;">

                                        <th>Customer</th>

                                        <th>Total Sales</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach($topCustomers as $row){ ?>

                                    <tr>

                                        <td>

                                            <?= $row->CompanyName ?>

                                        </td>

                                        <td>

                                            <?= number_format($row->TotalSales,2) ?>

                                        </td>

                                    </tr>

                                    <?php } ?>

                                </tbody>

                             </table>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CUSTOMER PENDING PAYMENTS TABLE -->
            <div class="row pending-table">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="header-title">
                            <i class="fa fa-clock-o"></i> Customer Pending Payments
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr style="background:#4F81BD; color:#fff;">
                                        <th width="40">#</th>
                                        <th>Company Name</th>
                                        <th width="150" style="text-align:right;">Due Amount</th>
                                        <th width="120" style="text-align:center;">Overdue Days</th>
                                        <th width="110">Sale Date</th>
                                        <th width="120">Contact No</th>
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
                                                <td><strong><?= htmlspecialchars($row->CompanyName) ?></strong></td>
                                                <td style="text-align:right; color:#d9534f; font-weight:bold;">
                                                    <?= number_format($row->DueAmount, 2) ?>
                                                </td>
                                                <td style="text-align:center;">
                                                    <?php if($row->DaysOverdue > 30): ?>
                                                        <span class="badge-danger" style="background:#d9534f; padding:5px 10px; border-radius:3px; color:white;">
                                                            <?= $row->DaysOverdue ?> days
                                                        </span>
                                                    <?php elseif($row->DaysOverdue > 0): ?>
                                                        <span class="badge-warning" style="background:#f0ad4e; padding:5px 10px; border-radius:3px; color:white;">
                                                            <?= $row->DaysOverdue ?> days
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge-success" style="background:#5cb85c; padding:5px 10px; border-radius:3px; color:white;">
                                                            Due soon
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center"><?= date('d-m-Y', strtotime($row->SaleDate)) ?></td>
                                                <td><?= htmlspecialchars($row->MobileNo ?: '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No pending payments found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(!empty($pendingPaymentsList)): ?>
                                <tfoot>
                                    <tr style="background:#f5f5f5;">
                                        <th colspan="2" style="text-align:right;">Total Pending:</th>
                                        <th style="text-align:right; color:#d9534f; font-size:16px;">
                                            <?= number_format($totalPending, 2) ?>
                                        </th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- TOP PRODUCTS -->
            <div class="row">

                <div class="col-lg-6">

                    <div class="card-box">

                        <h4 class="header-title">

                            Top Selling Products

                        </h4>

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <thead>

                                    <tr style="background:#4F81BD;
                                               color:#fff;">

                                        <th>Item ID</th>

                                        <th>Item Name</th>

                                        <th>Quantity</th>

                                    </td>

                                </thead>

                                <tbody>

                                    <?php foreach($topProducts as $row){ ?>

                                    <tr>

                                        <td>

                                            <?= $row->ItemID ?>

                                         </td>

                                        <td>

                                            <?= $row->ItemName ?>

                                         </td>

                                        <td>

                                            <?= number_format($row->TotalQty,2) ?>

                                         </td>

                                    </tr>

                                    <?php } ?>

                                </tbody>

                             </table>

                        </div>

                    </div>

                </div>


                <!-- BANK WISE BALANCE -->
                <div class="col-lg-6">

                    <div class="card-box">

                        <h4 class="header-title">

                            Bank Wise Balance

                        </h4>

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <thead>

                                    <tr style="background:#4F81BD;color:#fff;">

                                        <th>Bank Name</th>

                                        <th style="text-align:right;">
                                            Balance
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php
                                    $grandTotal = 0;

                                    foreach($bankBalances as $bank)
                                    {
                                        $grandTotal += $bank['Balance'];
                                    ?>

                                         <tr>

                                            <td>

                                                <a href="<?= base_url('admin/home/bank_ledger/'.$bank['BankID']) ?>">

                                                    <?= $bank['BankName'] ?>

                                                </a>

                                            </td>

                                            <td style="text-align:right;">

                                                <?= number_format($bank['Balance'],2) ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                    <tr style="font-weight:bold;background:#f5f5f5;">

                                        <td>Total</td>

                                        <td style="text-align:right;">

                                            <?= number_format($grandTotal,2) ?>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

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

        labels: [

            'Jan','Feb','Mar',
            'Apr','May','Jun',
            'Jul','Aug','Sep',
            'Oct','Nov','Dec'

        ],

        datasets: [{

            label: 'Monthly Sales',

            data: <?= $monthlySales ?>,

            backgroundColor:
                'rgba(54,162,235,0.7)',

            borderColor:
                'rgba(54,162,235,1)',

            borderWidth: 1

        }]
    },

    options: {

        responsive: true

    }

});


// MONTHLY PROFIT CHART
new Chart(
    document.getElementById('monthlyProfitChart'),
    {

    type: 'line',

    data: {

        labels: [

            'Jan','Feb','Mar',
            'Apr','May','Jun',
            'Jul','Aug','Sep',
            'Oct','Nov','Dec'

        ],

        datasets: [{

            label: 'Monthly Profit',

            data: <?= $monthlyProfit ?>,

            fill: false,

            borderColor: '#4CAF50',

            backgroundColor: '#4CAF50',

            tension: 0.3

        }]
    },

    options: {

        responsive: true

    }

});

</script>

<?php $this->view('includes/footer_end.php') ?> 