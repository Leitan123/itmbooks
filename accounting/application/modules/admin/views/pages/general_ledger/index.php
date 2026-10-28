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

<?php
if (!function_exists('get_reference_link')) {
    function get_reference_link($ref) {
        if (empty($ref)) return '#';
        $parts = explode('-', $ref);
        if (count($parts) < 2) return '#';
        $prefix = $parts[0];
        $id = $parts[1];
        
        switch ($prefix) {
            case 'EXP':
                return base_url('admin/expenses/edit/' . $id);
            case 'CP':
                return base_url('admin/customer_payments/edit/' . $id);
            case 'VP':
                return base_url('admin/vendor_payments/edit/' . $id);
            case 'PUR':
                return base_url('admin/purchases/edit/' . $id);
            case 'INC':
                return base_url('admin/incomes');
            case 'JN':
                return base_url('admin/journal_entries/edit/' . $id);
            case 'ADV':
                return base_url('admin/employee_advances');
            case 'SAL':
                return base_url('admin/payroll');
            default:
                return '#';
        }
    }
}
?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- Page Title -->
            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            General Ledger

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li class="active">

                                General Ledger

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>

            <!-- Ledger Table -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">
                            Ledger Transactions <?= !empty($selected_account) ? ' - ' . htmlspecialchars($selected_account) : '' ?>
                        </h4>

                        <div class="table-responsive">

                            <table id="datatable"
                                   class="table table-bordered table-striped">

                                <thead>

                                     <tr style="background:#4F81BD; color:#fff;">

                                         <th>#</th>

                                         <th>Date</th>

                                         <th>Reference</th>

                                         <th>Account Name</th>

                                         <th>Customer / Domain / Payee</th>

                                         <th>Description</th>

                                         <th style="text-align:right;">

                                             Debit

                                         </th>

                                         <th style="text-align:right;">

                                             Credit

                                         </th>

                                     </tr>

                                 </thead>

                                 <tbody>

                                     <?php

                                     $i = 1;

                                     $totalDebit = 0;
                                     $totalCredit = 0;

                                     foreach($records as $row){

                                         $totalDebit += $row->DebitAmount;
                                         $totalCredit += $row->CreditAmount;

                                     ?>

                                         <tr>

                                             <td>

                                                 <?= $i++ ?>

                                             </td>

                                             <td>

                                                 <?= $row->JournalDate ?>

                                             </td>

                                             <td>
                                                 <?php $ref_link = get_reference_link($row->ReferenceNo); ?>
                                                 <?php if ($ref_link !== '#'): ?>
                                                     <a href="<?= $ref_link ?>" target="_blank" style="text-decoration: underline; font-weight: bold;"><?= htmlspecialchars($row->ReferenceNo) ?></a>
                                                 <?php else: ?>
                                                     <?= htmlspecialchars($row->ReferenceNo) ?>
                                                 <?php endif; ?>
                                             </td>

                                             <td>

                                                 <?= $row->AccountName ?>

                                             </td>

                                             <td>

                                                 <?= htmlspecialchars($row->CustomerDomain) ?>

                                             </td>

                                             <td>

                                                 <?= htmlspecialchars(!empty($row->Description) ? $row->Description : $row->JournalDescription) ?>

                                             </td>

                                             <td style="text-align:right;
                                                        color:#3c763d;
                                                        font-weight:bold;">

                                                 <?= number_format($row->DebitAmount,2) ?>

                                             </td>

                                             <td style="text-align:right;
                                                        color:#a94442;
                                                        font-weight:bold;">

                                                 <?= number_format($row->CreditAmount,2) ?>

                                             </td>

                                         </tr>

                                     <?php } ?>

                                 </tbody>

                                 <tfoot>

                                     <tr style="background:#d9edf7;">

                                         <th colspan="6"
                                             style="text-align:right;">

                                             TOTAL

                                         </th>

                                         <th style="text-align:right;
                                                    color:#3c763d;">

                                             <?= number_format($totalDebit,2) ?>

                                         </th>

                                         <th style="text-align:right;
                                                    color:#a94442;">

                                             <?= number_format($totalCredit,2) ?>

                                         </th>

                                     </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Summary Cards -->
            <div class="row">

                <!-- Total Debit -->
                <div class="col-lg-6">

                    <div class="card-box"
                         style="background:#dff0d8;">

                        <h4 style="color:#3c763d;">

                            Total Debit

                        </h4>

                        <h2 style="color:#3c763d;">

                            <?= number_format($totalDebit,2) ?>

                        </h2>

                    </div>

                </div>

                <!-- Total Credit -->
                <div class="col-lg-6">

                    <div class="card-box"
                         style="background:#f2dede;">

                        <h4 style="color:#a94442;">

                            Total Credit

                        </h4>

                        <h2 style="color:#a94442;">

                            <?= number_format($totalCredit,2) ?>

                        </h2>

                    </div>

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

        $('#datatable').DataTable({

            "order": [[1, "desc"]],
            "responsive": true,
            "autoWidth": false

        });

    });

</script>

<!-- extra js end -->

<?php $this->view('includes/footer_end.php') ?>