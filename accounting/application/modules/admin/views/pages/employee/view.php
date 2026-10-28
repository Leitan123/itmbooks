<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
    <div class="page-title-box">
        <h4 class="page-title">Employee Details</h4>
        <ol class="breadcrumb p-0">
            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
            <li><a href="<?= base_url('admin/employee') ?>">Employee</a></li>
            <li class="active">View Details</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
    <div class="card-box">
        <div class="row">
            <div class="col-md-6">
                <h4 class="header-title m-t-0 m-b-30">Basic Information</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th width="35%">Employee No</th>
                        <td><?= htmlspecialchars($obj->EmployeeNo) ?></td>
                    </tr>
                    <tr>
                        <th>Employee Name</th>
                        <td><?= htmlspecialchars($obj->EmployeeName) ?></td>
                    </tr>
                    <tr>
                        <th>NIC</th>
                        <td><?= htmlspecialchars($obj->NIC ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Mobile No</th>
                        <td><?= htmlspecialchars($obj->MobileNo ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($obj->Email ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?= nl2br(htmlspecialchars($obj->Address ?: '-')) ?></td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td><?= htmlspecialchars($obj->Designation ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td><?= htmlspecialchars($obj->Department ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Join Date</th>
                        <td><?= htmlspecialchars($obj->JoinDate ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if($obj->Status == 'Active'): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <h4 class="header-title m-t-0 m-b-30">Salary & Costing Information</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th width="45%">Basic Salary</th>
                        <td style="text-align: right;">Rs. <?= number_format($obj->BasicSalary, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Allowance</th>
                        <td style="text-align: right;">Rs. <?= number_format($obj->Allowance, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Total Salary</th>
                        <td style="text-align: right;"><strong>Rs. <?= number_format($obj->BasicSalary + $obj->Allowance, 2) ?></strong></td>
                    </tr>
                    <tr>
                        <th>OT Per Hour</th>
                        <td style="text-align: right;">Rs. <?= number_format($obj->OTPerHour, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Cost Per Minute</th>
                        <td style="text-align: right; background-color: #eafaf1; font-weight: bold; color: #25D366;">
                            Rs. <?= number_format($obj->CostPerMinute, 4) ?>
                        </td>
                    </tr>
                </table>
                
                <div class="alert alert-info m-t-20" style="background-color: rgba(37, 211, 102, 0.1); border-color: rgba(37, 211, 102, 0.2); color: #128C7E;">
                    <strong><i class="fa fa-info-circle"></i> Costing Logic:</strong><br>
                    Cost Per Minute is used to evaluate project labor costs based on time records:
                    <br><br>
                    <code>Cost/Min = (Salary + Allowance) / (Working Days * Working Hours * 60)</code>
                </div>
            </div>
        </div>
        
        <div class="row m-t-30">
            <div class="col-xs-12">
                <a href="<?= base_url('admin/employee/edit/'.$obj->EmployeeID) ?>" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Edit Employee
                </a>
                <a href="<?= base_url('admin/employee') ?>" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
</div>

</div>
</div>
</div>

<?php $this->view('includes/footer_start.php'); ?>
<?php $this->view('includes/footer_end.php'); ?>
