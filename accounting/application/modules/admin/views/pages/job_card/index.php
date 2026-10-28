<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">

<div class="page-title-box">

<h4 class="page-title">Job Card</h4>

<ol class="breadcrumb p-0">

<li>
<a href="<?= base_url('admin') ?>">Dashboard</a>
</li>

<li class="active">
Job Card List
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

<i class="fa fa-plus"></i>

Create New

</a>

</div>
</div>

<div class="card-box">

<h4 class="header-title m-t-0 m-b-30">

Job Card Details

</h4>

<div class="table-responsive">

<table id="datatable"
       class="table table-striped table-bordered">

<thead>

<tr>

<th>Job No</th>
<th>Date</th>
<th>Company</th>
<th>Service</th>
<th>Priority</th>
<th>Status</th>
<th>Employee</th>
<th>Amount</th>
<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td><?= $row->JobCardNo ?></td>

<td><?= date('Y-m-d', strtotime($row->JobCardDate)) ?></td>

<td><?= $row->CompanyName ?></td>

<td><?= $row->ItemName ?></td>

<td>

<?php if($row->Priority == 'Low'): ?>

<span class="badge badge-secondary">
Low
</span>

<?php elseif($row->Priority == 'Medium'): ?>

<span class="badge badge-info">
Medium
</span>

<?php elseif($row->Priority == 'High'): ?>

<span class="badge badge-warning">
High
</span>

<?php elseif($row->Priority == 'Urgent'): ?>

<span class="badge badge-danger">
Urgent
</span>

<?php endif; ?>

</td>

<td>

<?php if($row->Status == 'Pending'): ?>

<span class="badge badge-warning">
Pending
</span>

<?php elseif($row->Status == 'In Progress'): ?>

<span class="badge badge-primary">
In Progress
</span>

<?php elseif($row->Status == 'Completed'): ?>

<span class="badge badge-success">
Completed
</span>

<?php elseif($row->Status == 'Cancelled'): ?>

<span class="badge badge-danger">
Cancelled
</span>

<?php else: ?>

<span class="badge badge-secondary">
<?= $row->Status ?>
</span>

<?php endif; ?>

</td>

<td>

<?= isset($row->EmployeeName)
? $row->EmployeeName : '' ?>

</td>

<td style="text-align:right;">

<?= number_format($row->EstimatedAmount,2) ?>

</td>

<td>

<a href="<?= base_url('admin/job_card/edit/'.$row->JobCardID) ?>"
   class="btn btn-warning btn-sm"
   title="Edit">

<i class="fa fa-edit"></i>

</a>

<a href="<?= base_url('admin/job_card/delete/'.$row->JobCardID) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure to delete this job card?')"
   title="Delete">

<i class="fa fa-trash"></i>

</a>

<a href="<?= base_url('admin/job_card/print/'.$row->JobCardID) ?>"
   class="btn btn-success btn-sm"
   target="_blank"
   title="Print">

<i class="fa fa-print"></i>

</a>

<a href="<?= base_url('admin/sales/create/'.$row->JobCardID) ?>"
   class="btn btn-primary btn-sm"
   title="Convert Invoice">

<i class="fa fa-file-text"></i>

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

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

$(document).ready(function(){

    $('#datatable').DataTable({
        "order": [[0, "desc"]]
    });

});

</script>

<?php $this->view('includes/footer_end.php') ?>