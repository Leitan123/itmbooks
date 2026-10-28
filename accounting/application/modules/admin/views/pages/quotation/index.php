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

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">

<div class="page-title-box">

<h4 class="page-title">Quotation</h4>

<ol class="breadcrumb p-0">

<li>
<a href="<?= base_url('admin') ?>">Dashboard</a>
</li>

<li class="active">
Quotation List
</li>

</ol>

<div class="clearfix"></div>

</div>

</div>
</div>

<div class="col-sm-12">

<div class="row">
<div class="col-lg-12">

<a class="btn btn-info m_b_10 pull-left fa fa-plus"
   href="<?= current_url() . "/create" ?>">

Create New

</a>

</div>
</div>

<div class="card-box">

<h4 class="header-title m-t-0 m-b-30">
Quotation Details
</h4>

<div class="table-responsive">

<table id="datatable"
       class="table table-striped table-bordered">

<thead>

<tr>

<th>Quotation No</th>
<th>Date</th>
<th>Company</th>
<th>Service</th>
<th>Sub Total</th>
<th>Discount</th>
<th>Grand Total</th>
<th>Status</th>
<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td><?= $row->QuotationNo ?></td>

<td><?= date('Y-m-d', strtotime($row->QuotationDate)) ?></td>

<td>
    <strong><?= $row->CompanyName ?></strong>

    <?php if(!empty($row->DomainName)){ ?>
        <br>
        <small style="color:#007bff;">
            <?= $row->DomainName ?>
        </small>
    <?php } ?>
</td>

<td><?= $row->ItemName ?></td>

<td style="text-align:right;">
<?= number_format($row->SubTotal,2) ?>
</td>

<td style="text-align:right;">
<?= number_format($row->DiscountAmount,2) ?>
</td>

<td style="text-align:right;">
<?= number_format($row->GrandTotal,2) ?>
</td>

<td>

<?php if($row->Status == 'Pending'): ?>

    <span class="badge badge-warning">
        Pending
    </span>

<?php elseif($row->Status == 'Approved'): ?>

    <span class="badge badge-success">
        Approved
    </span>

<?php elseif($row->Status == 'Rejected'): ?>

    <span class="badge badge-danger">
        Rejected
    </span>

<?php elseif($row->Status == 'Converted'): ?>

    <span class="badge badge-info">
        Converted
    </span>

<?php else: ?>

    <span class="badge badge-secondary">
        <?= $row->Status ?>
    </span>

<?php endif; ?>

</td>

<td>

<a href="<?= base_url('admin/quotation/edit/'.$row->QuotationID) ?>"
   class="btn btn-warning btn-sm"
   title="Edit">

    <i class="fa fa-edit"></i>

</a>

<a href="<?= base_url('admin/quotation/delete/'.$row->QuotationID) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure to delete this quotation?')"
   title="Delete">

    <i class="fa fa-trash"></i>

</a>

<a href="<?= base_url('admin/quotation/print/'.$row->QuotationID) ?>"
   class="btn btn-success btn-sm"
   target="_blank"
   title="Print">

    <i class="fa fa-print"></i>

</a>

<a href="<?= base_url('admin/job_card/create/'.$row->QuotationID) ?>"
   class="btn btn-primary btn-sm"
   title="Convert Job Card">

    <i class="fa fa-briefcase"></i>

</a>

<a href="<?= base_url('admin/sales/create_from_quotation/'.$row->QuotationID) ?>"
   class="btn btn-info btn-sm"
   title="Convert to Invoice">

    <i class="fa fa-file-text-o"></i>

</a>

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

