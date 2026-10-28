<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Vendor Payment Details - <?= $vendor->VendorName ?></h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/vendor_payments') ?>">Vendor Payments</a></li>
                            <li class="active">Details</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <br />

            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Purchase & Payment Details</h4>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Purchase No</th>
                                        <th>Purchase Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Running Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $runningDue = 0;
                                    foreach($records as $record):
                                        $total = isset($record->TotalAmount) ? $record->TotalAmount : 0;
                                        $paid = isset($record->PaidAmount) ? $record->PaidAmount : 0;

                                        // Calculate due including previous running due
                                        $due = ($total + $runningDue) - $paid;

                                        // Update running balance for next row
                                        $runningDue = $due > 0 ? $due : 0;
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $record->PurchaseID ?></td>
                                            <td><?= $record->PurchaseDate ?></td>
                                            <td><?= number_format($total, 2) ?></td>
                                            <td><?= number_format($paid, 2) ?></td>
                                            <td><?= number_format($due, 2) ?></td>
                                            <td><?= number_format($runningDue, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- container -->
    </div>
    <!-- content -->
</div><!-- End content-page -->

<?php $this->view('includes/footer_start.php') ?>
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.responsive.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.js") ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable({
            responsive: true,
            "order": [[ 0, "asc" ]]
        });
    });
</script>
<?php $this->view('includes/footer_end.php') ?>
