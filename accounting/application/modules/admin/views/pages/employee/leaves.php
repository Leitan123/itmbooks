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

Leave Applications

</h4>

<ol class="breadcrumb p-0">

<li>
<a href="<?= base_url('admin') ?>">
Dashboard
</a>
</li>

<li>
<a href="<?= base_url('admin/employee') ?>">
Employee
</a>
</li>

<li class="active">

Leave Requests

</li>

</ol>

<div class="clearfix"></div>

</div>

</div>
</div>

<div class="col-sm-12">

<div class="card-box">

<h4 class="header-title m-t-0 m-b-30">

Employee Leave Requests

</h4>

<?php if($this->session->flashdata('notification')): ?>
    <div class="alert alert-<?= $this->session->flashdata('notification')['alert'] ?>">
        <?= $this->session->flashdata('notification')['text'] ?>
    </div>
<?php endif; ?>

<div class="table-responsive">

<table id="datatable"
       class="table table-striped table-bordered">

<thead>

<tr>

<th>Date Requested</th>

<th>Employee No</th>

<th>Employee Name</th>

<th>Leave Type</th>

<th>Start Date</th>

<th>End Date</th>

<th>Days</th>

<th>Reason</th>

<th>Status</th>

<th>Admin Remarks</th>

<th width="120">

Action

</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): 
    $start = new DateTime($row->StartDate);
    $end = new DateTime($row->EndDate);
    $diff = $start->diff($end)->format("%a");
    $days = $diff + 1;
?>

<tr>

<td>
<?= date('Y-m-d H:i', strtotime($row->CreatedDate)) ?>
</td>

<td>
<?= $row->EmployeeNo ?>
</td>

<td>
<?= $row->EmployeeName ?>
</td>

<td>
<?= $row->LeaveType ?>
</td>

<td>
<?= $row->StartDate ?>
</td>

<td>
<?= $row->EndDate ?>
</td>

<td>
<?= $days ?>
</td>

<td>
<?= htmlspecialchars($row->Reason) ?>
</td>

<td>

<?php if($row->Status == 'Approved'): ?>

<span class="badge badge-success">Approved</span>

<?php elseif($row->Status == 'Rejected'): ?>

<span class="badge badge-danger">Rejected</span>

<?php else: ?>

<span class="badge badge-warning">Pending</span>

<?php endif; ?>

</td>

<td>
<?= htmlspecialchars($row->Remarks ?? '') ?>
</td>

<td>

<button class="btn btn-primary btn-sm" 
        onclick="manageLeave(<?= $row->LeaveID ?>, '<?= $row->Status ?>', '<?= htmlspecialchars($row->Remarks ?? '', ENT_QUOTES) ?>')"
        title="Update Status">
    <i class="fa fa-edit"></i> Manage
</button>

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

<!-- Modal for managing leave status -->
<div id="leaveModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= form_open('admin/employee/save_leave_status') ?>
            <div class="modal-header">
                <h5 class="modal-title">Update Leave Request Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="LeaveID" name="LeaveID">
                
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="Status" id="Status" required>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Remarks / Comments</label>
                    <textarea class="form-control" name="Remarks" id="Remarks" rows="3" placeholder="Enter remarks..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
            <?= form_close() ?>
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

function manageLeave(id, status, remarks) {
    $('#LeaveID').val(id);
    $('#Status').val(status);
    $('#Remarks').val(remarks);
    $('#leaveModal').modal('show');
}

</script>

<?php $this->view('includes/footer_end.php') ?>
