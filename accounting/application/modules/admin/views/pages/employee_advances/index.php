<?php $this->view('includes/header_start.php'); ?>
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
    <div class="page-title-box">
        <h4 class="page-title">Salary Advances & Loans</h4>
        <ol class="breadcrumb p-0">
            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
            <li class="active">Salary Advances</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-info m_b_10 pull-left" href="<?= base_url('admin/employee_advances/create') ?>">
                <i class="fa fa-plus"></i> Record New Advance / Loan
            </a>
        </div>
    </div>

    <div class="card-box">
        <h4 class="header-title m-t-0 m-b-30">Advance Ledger</h4>
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Emp No</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Amount Paid</th>
                        <th>Recovered</th>
                        <th>Outstanding Balance</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($records as $row): 
                        $outstanding = $row->Amount - $row->AmountRecovered;
                        ?>
                        <tr>
                            <td><?= $row->AdvanceDate ?></td>
                            <td><?= $row->EmployeeNo ?></td>
                            <td><?= htmlspecialchars($row->EmployeeName) ?></td>
                            <td><?= htmlspecialchars($row->Designation ?: '-') ?></td>
                            <td style="text-align: right;">Rs. <?= number_format($row->Amount, 2) ?></td>
                            <td style="text-align: right; color: #25D366;">Rs. <?= number_format($row->AmountRecovered, 2) ?></td>
                            <td style="text-align: right; font-weight: bold; color: <?= $outstanding > 0 ? '#ff5b5b' : '#25D366' ?>;">
                                Rs. <?= number_format($outstanding, 2) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row->PaymentMethod) ?>
                                <?php if($row->PaymentMethod == 'Bank' && $row->BankName): ?>
                                    <small class="text-muted"><br>(<?= htmlspecialchars($row->BankName) ?>)</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->AmountRecovered == 0): ?>
                                    <span class="badge badge-danger">Unpaid / Outstanding</span>
                                <?php elseif($outstanding > 0): ?>
                                    <span class="badge badge-warning">Partially Recovered</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Fully Recovered</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="white-space: nowrap;">
                                <?php if($outstanding > 0): ?>
                                    <a href="<?= base_url('admin/employee_advances/settle/'.$row->AdvanceID) ?>"
                                       class="btn btn-success btn-sm"
                                       title="Settle Loan (Direct Return)">
                                        <i class="fa fa-check-circle"></i> Settle
                                    </a>
                                <?php endif; ?>
                                <?php if($row->AmountRecovered == 0): ?>
                                    <a href="<?= base_url('admin/employee_advances/delete/'.$row->AdvanceID) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this salary advance? This will automatically reverse the double-entry accounting journal transaction.')"
                                       title="Delete & Reverse Entry">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <?php if($outstanding == 0): ?>
                                        <span class="text-muted"><i class="fa fa-lock" title="Fully recovered transaction is locked."></i> Locked</span>
                                    <?php endif; ?>
                                <?php endif; ?>
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
