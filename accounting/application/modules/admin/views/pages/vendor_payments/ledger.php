<?php $this->view('includes/header_start.php'); ?>
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Vendor Ledger - <?= $vendor->VendorName ?></h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/vendor_payments') ?>">Vendor Payments</a></li>
                            <li class="active">Ledger</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <br />

            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Ledger Transactions</h4>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Purchase No</th>
                                        <th>Debit (Purchase)</th>
                                        <th>Credit (Payment)</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1; 
                                    $balance = 0;
                                    foreach($records as $record): 
                                        $balance += ($record->TotalAmount - $record->PaidAmount); 
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $record->Date ?></td>
                                            <td><?= $record->PurchaseID ?></td>
                                            <td><?= number_format($record->TotalAmount, 2) ?></td>
                                            <td><?= number_format($record->PaidAmount, 2) ?></td>
                                            <td><?= number_format($balance, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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
