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

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Sales Returns (Credit Notes)</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Sales Return List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="row m-b-10">
                    <div class="col-lg-12">
                        <a class="btn btn-info pull-left fa fa-plus" href="<?= base_url("admin/Sales_returns/create") ?>"> Create New</a>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Sales Returns Details</h4>
                    <?php $this->view('includes/notification.php'); ?>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Return No</th>
                                    <th>Date</th>
                                    <th>Company</th>
                                    <th>Reference No</th>
                                    <th class="text-right">Total Amount</th>
                                    <th class="text-right">Applied</th>
                                    <th class="text-right">Unused Credit</th>
                                    <th width="180" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($records as $row): ?>
                                    <?php 
                                        $unused = $row->TotalReturnAmount - $row->AppliedAmount;
                                    ?>
                                    <tr>
                                        <td>SR-<?= str_pad($row->SalesReturnID, 5, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= date('Y-m-d', strtotime($row->ReturnDate)) ?></td>
                                        <td><strong><?= htmlspecialchars($row->CompanyName ?? $row->CustomerName ?? 'N/A') ?></strong></td>
                                        <td><?= htmlspecialchars($row->ReferenceNo ?? '-') ?></td>
                                        <td style="text-align:right;"><?= number_format($row->TotalReturnAmount, 2) ?></td>
                                        <td style="text-align:right; color:#1abc9c;"><?= number_format($row->AppliedAmount, 2) ?></td>
                                        <td style="text-align:right; font-weight:bold; color:<?= $unused > 0 ? '#f39c12' : '#7f8c8d' ?>;"><?= number_format($unused, 2) ?></td>
                                        <td class="text-center">
                                            <?php if ($unused > 0): ?>
                                                <a href="<?= base_url('admin/Sales_returns/apply/'.$row->SalesReturnID) ?>" class="btn btn-info btn-sm" title="Apply to Invoice"><i class="fa fa-link"></i> Apply</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('admin/Sales_returns/apply/'.$row->SalesReturnID) ?>" class="btn btn-secondary btn-sm" title="View/Edit Applied"><i class="fa fa-link"></i> Applied</a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('admin/Sales_returns/edit/'.$row->SalesReturnID) ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="<?= base_url('admin/Sales_returns/delete/'.$row->SalesReturnID) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete/cancel this Sales Return? This will revert general ledger and account balances.')" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
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

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>
<script>
$(document).ready(function(){
    $('#datatable').DataTable({
        "order": [[0, "desc"]]
    });
});
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
