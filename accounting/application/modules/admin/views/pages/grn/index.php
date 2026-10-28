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
<h4 class="page-title">GRN (Goods Received Notes)</h4>
<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li class="active">GRN List</li>
</ol>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="col-sm-12">
<div class="row" style="margin-bottom: 15px;">
<div class="col-lg-12">
<a class="btn btn-info fa fa-plus" href="<?= base_url('admin/grn/create') ?>"> Record New GRN</a>
</div>
</div>

<div class="card-box">
<h4 class="header-title m-t-0 m-b-30">GRN Records</h4>
<div class="table-responsive">
<table id="datatable" class="table table-striped table-bordered">
<thead>
<tr>
<th>GRN Number</th>
<th>Date</th>
<th>Vendor</th>
<th>PO Reference</th>
<th>Remarks</th>
<th width="120">Action</th>
</tr>
</thead>
<tbody>
<?php foreach($records as $row): ?>
<tr>
<td><?= $row->GrnNo ?></td>
<td><?= date('Y-m-d', strtotime($row->GrnDate)) ?></td>
<td><strong><?= $row->VendorName ?></strong></td>
<td><?= !empty($row->PurchaseOrderNo) ? $row->PurchaseOrderNo : '<span style="color: #94a3b8;">None</span>' ?></td>
<td><?= $row->Remarks ?></td>
<td>
<a href="<?= base_url('admin/grn/edit/'.$row->GrnID) ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
<a href="<?= base_url('admin/grn/delete/'.$row->GrnID) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this GRN record?')" title="Delete"><i class="fa fa-trash"></i></a>
<a href="<?= base_url('admin/grn/print/'.$row->GrnID) ?>" class="btn btn-success btn-sm" target="_blank" title="Print"><i class="fa fa-print"></i></a>
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
