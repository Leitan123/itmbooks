<?php $this->view('includes/header_start.php'); ?>
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
    <div class="page-title-box">
        <h4 class="page-title">Monthly Payroll & Pay Sheets</h4>
        <ol class="breadcrumb p-0">
            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
            <li class="active">Monthly Payroll</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-info m_b_10 pull-left" href="<?= base_url('admin/payroll/create') ?>">
                <i class="fa fa-plus"></i> Generate New Salary Slip
            </a>
        </div>
    </div>

    <?php if ($this->session->flashdata('notification')): ?>
        <?php $notification = $this->session->flashdata('notification'); ?>
        <div class="alert alert-<?= $notification['alert'] ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="close" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <?= $notification['text'] ?>
        </div>
    <?php endif; ?>

    <div class="card-box">
        <h4 class="header-title m-t-0 m-b-30">Processed Pay Sheets</h4>
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Emp No</th>
                        <th>Employee Name</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>OT (Hrs)</th>
                        <th>Gross Salary</th>
                        <th>EPF (8%)</th>
                        <th>Advance Recovery</th>
                        <th>Net Salary</th>
                        <th>Method</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($records as $row): ?>
                        <tr>
                            <td><strong><?= $row->PayrollMonth ?></strong></td>
                            <td><?= htmlspecialchars($row->EmployeeNo) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row->EmployeeName) ?></strong>
                                <small class="text-muted"><br><?= htmlspecialchars($row->Designation ?: '-') ?></small>
                            </td>
                            <td style="text-align: right;">Rs. <?= number_format($row->BasicSalary, 2) ?></td>
                            <td style="text-align: right;">Rs. <?= number_format($row->Allowance, 2) ?></td>
                            <td style="text-align: right;">
                                Rs. <?= number_format($row->OTAmount, 2) ?>
                                <small class="text-muted"><br>(<?= $row->OTHours ?> hrs @ <?= $row->OTRate ?>)</small>
                            </td>
                            <td style="text-align: right; font-weight: 500;">Rs. <?= number_format($row->GrossSalary, 2) ?></td>
                            <td style="text-align: right; color: #ff5b5b;">Rs. <?= number_format($row->EmployeeEPF, 2) ?></td>
                            <td style="text-align: right; color: #ff9800;">Rs. <?= number_format($row->AdvanceDeduction, 2) ?></td>
                            <td style="text-align: right; font-weight: bold; color: #25D366; background-color: rgba(37, 211, 102, 0.05);">
                                Rs. <?= number_format($row->NetSalary, 2) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row->PaymentMethod) ?>
                                <?php if($row->PaymentMethod == 'Bank' && $row->BankName): ?>
                                    <small class="text-muted"><br>(<?= htmlspecialchars($row->BankName) ?>)</small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/payroll/print_slip/'.$row->SlipID) ?>"
                                   class="btn btn-primary btn-sm"
                                   target="_blank"
                                   title="Print / PDF Pay Slip">
                                    <i class="fa fa-print"></i> Print
                                </a>
                                <a href="<?= base_url('admin/payroll/delete/'.$row->SlipID) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this salary slip? This will restore any advance balances deducted, and reverse the associated general ledger journal entries.')"
                                   title="Delete Slip">
                                    <i class="fa fa-trash"></i>
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
