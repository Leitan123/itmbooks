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
<h4 class="page-title">Purchase Orders</h4>
<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li class="active">Purchase Order List</li>
</ol>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="col-sm-12">
<div class="row" style="margin-bottom: 15px;">
<div class="col-lg-12">
<a class="btn btn-info fa fa-plus" href="<?= base_url('admin/purchase_orders/create') ?>"> Create Purchase Order</a>
</div>
</div>

<div class="card-box">
<h4 class="header-title m-t-0 m-b-30">Purchase Order Details</h4>
<div class="table-responsive">
<table id="datatable" class="table table-striped table-bordered">
<thead>
<tr>
<th>PO Number</th>
<th>Date</th>
<th>Vendor</th>
<th>Sub Total</th>
<th>Discount</th>
<th>Grand Total</th>
<th>Status</th>
<th width="120">Action</th>
</tr>
</thead>
<tbody>
<?php foreach($records as $row): ?>
<tr>
<td><?= $row->PurchaseOrderNo ?></td>
<td><?= date('Y-m-d', strtotime($row->PurchaseOrderDate)) ?></td>
<td><strong><?= $row->VendorName ?></strong></td>
<td style="text-align:right;"><?= number_format($row->SubTotal,2) ?></td>
<td style="text-align:right;"><?= number_format($row->DiscountAmount,2) ?></td>
<td style="text-align:right; font-weight: bold;"><?= number_format($row->GrandTotal,2) ?></td>
<td>
    <?php if($row->Status == 'Pending'): ?>
        <span class="label label-warning">Pending</span>
    <?php elseif($row->Status == 'Approved'): ?>
        <span class="label label-success">Approved</span>
    <?php elseif($row->Status == 'Received'): ?>
        <span class="label label-info">Received</span>
    <?php else: ?>
        <span class="label label-default"><?= $row->Status ?></span>
    <?php endif; ?>
</td>
<td>
<a href="<?= base_url('admin/purchase_orders/edit/'.$row->PurchaseOrderID) ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
<a href="<?= base_url('admin/purchase_orders/delete/'.$row->PurchaseOrderID) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this PO?')" title="Delete"><i class="fa fa-trash"></i></a>
<a href="<?= base_url('admin/purchase_orders/print/'.$row->PurchaseOrderID) ?>" class="btn btn-success btn-sm" target="_blank" title="Print"><i class="fa fa-print"></i></a>
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
