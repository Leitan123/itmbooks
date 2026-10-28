<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">

<div class="page-title-box">

<h4 class="page-title">

Employee Management

</h4>

<ol class="breadcrumb p-0">

<li>
<a href="<?= base_url('admin') ?>">
Dashboard
</a>
</li>

<li class="active">

Employee List

</li>

</ol>

<div class="clearfix"></div>

</div>

</div>
</div>

<div class="col-sm-12">

<div class="row">
<div class="col-lg-12">

<a class="btn btn-info m_b_10 pull-left"
   href="<?= base_url('admin/employee/create') ?>">

<i class="fa fa-plus"></i>

Create New

</a>

</div>
</div>

<div class="card-box">

<h4 class="header-title m-t-0 m-b-30">

Employee Details

</h4>

<div class="table-responsive">

<table id="datatable"
       class="table table-striped table-bordered">

<thead>

<tr>

<th>Employee No</th>

<th>Employee Name</th>

<th>Mobile No</th>

<th>Designation</th>

<th>Department</th>

<th>Basic Salary</th>

<th>Cost / Minute</th>

<th>Status</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td>

<?= $row->EmployeeNo ?>

</td>

<td>

<?= $row->EmployeeName ?>

</td>

<td>

<?= $row->MobileNo ?>

</td>

<td>

<?= $row->Designation ?>

</td>

<td>

<?= $row->Department ?>

</td>

<td style="text-align:right;">

<?= number_format(
    $row->BasicSalary,
    2
) ?>

</td>

<td style="text-align:right;">

<?= number_format(
    $row->CostPerMinute,
    2
) ?>

</td>

<td>

<?php if($row->Status == 'Active'): ?>

<span class="badge badge-success">

Active

</span>

<?php else: ?>

<span class="badge badge-danger">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<a href="<?= base_url('admin/employee/edit/'.$row->EmployeeID) ?>"
   class="btn btn-warning btn-sm"
   title="Edit">

<i class="fa fa-edit"></i>

</a>

<a href="<?= base_url('admin/employee/details/'.$row->EmployeeID) ?>"
   class="btn btn-info btn-sm"
   title="View">

<i class="fa fa-eye"></i>

</a>

<a href="<?= base_url('admin/employee/delete/'.$row->EmployeeID) ?>"
   class="btn btn-danger btn-sm"

   onclick="return confirm('Are you sure to delete this employee?')"

   title="Delete">

<i class="fa fa-trash"></i>

</a>

<a href="<?= base_url('admin/job_card?employee='.$row->EmployeeID) ?>"
   class="btn btn-primary btn-sm"
   title="Assigned Jobs">

<i class="fa fa-briefcase"></i>

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

        "order": [[0, "desc"]],

        "pageLength": 25

    });

});

</script>

<?php $this->view('includes/footer_end.php') ?>