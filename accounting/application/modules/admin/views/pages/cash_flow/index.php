<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap4.min.css') ?>"
      rel="stylesheet">

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

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

<div class="content">

<div class="container">

<!-- Page Title -->
<div class="row">

<div class="col-xs-12">

<div class="page-title-box">

<h4 class="page-title">

Cash Flow Statement

</h4>

<ol class="breadcrumb p-0">

<li>

<a href="<?= base_url('admin') ?>">

Dashboard

</a>

</li>

<li class="active">

Cash Flow

</li>

</ol>

<div class="clearfix"></div>

</div>

</div>

</div>

            <!-- Filter Section -->
            <div class="filter-panel" style="margin-bottom: 20px; background: #fdfdfd; border: 1px solid #e3e7eb; padding: 15px; border-radius: 8px;">
                <form method="GET" action="<?= base_url('admin/cash_flow') ?>" id="filterForm" class="row">
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
                        <a href="<?= base_url('admin/cash_flow/export_csv?from_date='.$from_date.'&to_date='.$to_date) ?>" class="btn btn-info" style="font-weight: bold; height: 38px; line-height: 24px; width: 120px; margin-left: 5px;"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                    </div>
                </form>
            </div>

            <script>
                function resetFilter() {
                    window.location.href = '<?= base_url("admin/cash_flow") ?>';
                }
            </script>

<!-- Cash Flow Table -->
<div class="row">

<div class="col-lg-12">

<div class="card-box">

<h4 class="header-title m-t-0 m-b-30">

Cash Flow Summary

</h4>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>Description</th>

<th style="text-align:right;">

Amount

</th>

</tr>

</thead>

<tbody>

<!-- CASH INFLOW -->

<tr style="background:#dff0d8;">

<th colspan="2">

CASH INFLOW

</th>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#customerPaymentsModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Customer Payments</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($customerPayments,2) ?>

</td>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#otherIncomeModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Other Income</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($income,2) ?>

</td>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#otherInflowModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Other Inflows (Journals)</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($otherInflow,2) ?>

</td>

</tr>

<tr>

<th>Total Cash Inflow</th>

<th style="text-align:right; color:green;">

<?= number_format($totalInflow,2) ?>

</th>

</tr>


<!-- CASH OUTFLOW -->

<tr style="background:#f2dede;">

<th colspan="2">

CASH OUTFLOW

</th>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#vendorPaymentsModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Vendor Payments</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($vendorPayments,2) ?>

</td>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#expensesModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Expenses</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($expenses,2) ?>

</td>

</tr>

<tr style="cursor: pointer;" data-toggle="modal" data-target="#otherOutflowModal">

<td><a href="javascript:void(0);" style="font-weight: 600; color: #337ab7;"><i class="fa fa-search"></i> Other Outflows (Journals)</a></td>

<td style="text-align:right; font-weight: bold; color: #337ab7;">

<?= number_format($otherOutflow,2) ?>

</td>

</tr>

<tr>

<th>Total Cash Outflow</th>

<th style="text-align:right; color:red;">

<?= number_format($totalOutflow,2) ?>

</th>

</tr>


<!-- NET CASH FLOW -->

<tr style="background:#d9edf7;">

<th>

Net Cash Flow

</th>

<th style="text-align:right;">

<?= number_format($netCashFlow,2) ?>

</th>

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

<!-- Customer Payments Modal -->
<div class="modal fade" id="customerPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="customerPaymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #dff0d8; color: #3c763d;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="customerPaymentsModalLabel" style="font-weight: bold; font-size: 16px;">Customer Payments Details</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Method</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customerPaymentDetails)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customerPaymentDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->PaymentMethod) ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                    <td style="text-align: center;">
                                        <a href="<?= base_url('admin/customer_payments/print_receipt/' . $det->PaymentID) ?>" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
                                        <a href="<?= base_url('admin/customer_payments/edit/' . $det->PaymentID) ?>" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Other Income Modal -->
<div class="modal fade" id="otherIncomeModal" tabindex="-1" role="dialog" aria-labelledby="otherIncomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #dff0d8; color: #3c763d;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="otherIncomeModalLabel" style="font-weight: bold; font-size: 16px;">Other Incomes Details</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account / Type</th>
                            <th>Method</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($incomeDetails)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($incomeDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->PaymentMethod) ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Payments Modal -->
<div class="modal fade" id="vendorPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="vendorPaymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f2dede; color: #a94442;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="vendorPaymentsModalLabel" style="font-weight: bold; font-size: 16px;">Vendor Payments Details</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Vendor Name</th>
                            <th>Method</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vendorPaymentDetails)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vendorPaymentDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->PaymentMethod) ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Expenses Modal -->
<div class="modal fade" id="expensesModal" tabindex="-1" role="dialog" aria-labelledby="expensesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f2dede; color: #a94442;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="expensesModalLabel" style="font-weight: bold; font-size: 16px;">Expenses Details</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account Name / Type</th>
                            <th>Method</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($expenseDetails)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($expenseDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->PaymentMethod) ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                    <td style="text-align: center;">
                                        <a href="<?= base_url('admin/expenses/edit/' . $det->ExpenseID) ?>" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Other Inflow Modal -->
<div class="modal fade" id="otherInflowModal" tabindex="-1" role="dialog" aria-labelledby="otherInflowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #dff0d8; color: #3c763d;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="otherInflowModalLabel" style="font-weight: bold; font-size: 16px;">Other Inflows Details (General Ledger)</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Opposing Account</th>
                            <th>Description</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($otherInflowDetails)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($otherInflowDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><strong><?= htmlspecialchars($det->ReferenceNo) ?></strong></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->Description ?: '-') ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Other Outflow Modal -->
<div class="modal fade" id="otherOutflowModal" tabindex="-1" role="dialog" aria-labelledby="otherOutflowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f2dede; color: #a94442;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: right;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="otherOutflowModalLabel" style="font-weight: bold; font-size: 16px;">Other Outflows Details (General Ledger)</h5>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Opposing Account</th>
                            <th>Description</th>
                            <th style="text-align: right;">Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($otherOutflowDetails)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888;">No transactions found in this period.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($otherOutflowDetails as $det): ?>
                                <tr>
                                    <td><?= htmlspecialchars($det->Date) ?></td>
                                    <td><strong><?= htmlspecialchars($det->ReferenceNo) ?></strong></td>
                                    <td><?= htmlspecialchars($det->Name ?: '-') ?></td>
                                    <td><?= htmlspecialchars($det->Description ?: '-') ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?= number_format($det->Amount, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<?php $this->view('includes/footer_end.php') ?>