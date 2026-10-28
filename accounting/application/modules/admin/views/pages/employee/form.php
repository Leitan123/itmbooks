<?php $this->view('includes/header_start.php'); ?>
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

<li>
<a href="<?= base_url('admin/employee') ?>">
Employee
</a>
</li>

<li class="active">

<?= isset($obj->EmployeeID)
? 'Edit Employee'
: 'Add Employee' ?>

</li>

</ol>

<div class="clearfix"></div>

</div>

</div>
</div>

<div class="row">
<div class="col-sm-12">

<?= form_open_multipart('admin/employee/save') ?>

<input type="hidden"
       name="form[EmployeeID]"

       value="<?= isset($obj->EmployeeID)
       ? $obj->EmployeeID : 0 ?>">

<div class="card-box">

<h4 class="header-title m-b-30">

Basic Information

</h4>

<div class="row">

<div class="col-lg-4">
<div class="form-group">

<label>Employee No</label>

<input type="text"
       name="form[EmployeeNo]"
       class="form-control"

       value="<?= isset($obj->EmployeeNo)
       ? $obj->EmployeeNo : '' ?>"

       required>

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Employee Name</label>

<input type="text"
       name="form[EmployeeName]"
       class="form-control"

       value="<?= isset($obj->EmployeeName)
       ? $obj->EmployeeName : '' ?>"

       required>

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>NIC</label>

<input type="text"
       name="form[NIC]"
       class="form-control"

       value="<?= isset($obj->NIC)
       ? $obj->NIC : '' ?>">

</div>
</div>

</div>

<div class="row">

<div class="col-lg-4">
<div class="form-group">

<label>Mobile No</label>

<input type="text"
       name="form[MobileNo]"
       class="form-control"

       value="<?= isset($obj->MobileNo)
       ? $obj->MobileNo : '' ?>">

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Email</label>

<input type="email"
       name="form[Email]"
       class="form-control"

       value="<?= isset($obj->Email)
       ? $obj->Email : '' ?>">

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Designation</label>

<input type="text"
       name="form[Designation]"
       class="form-control"

       value="<?= isset($obj->Designation)
       ? $obj->Designation : '' ?>">

</div>
</div>

</div>

<div class="row">

<div class="col-lg-3">
<div class="form-group">

<label>Department</label>

<input type="text"
       name="form[Department]"
       class="form-control"

       value="<?= isset($obj->Department)
       ? $obj->Department : '' ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Join Date</label>

<input type="date"
       name="form[JoinDate]"
       class="form-control"

       value="<?= isset($obj->JoinDate)
       ? $obj->JoinDate : '' ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Member No (EPF/ETF)</label>

<input type="text"
       name="form[MemberNo]"
       class="form-control"
       placeholder="e.g. 1"

       value="<?= isset($obj->MemberNo)
       ? $obj->MemberNo : '' ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Status</label>

<select name="form[Status]"
        class="form-control">

<option value="Active"

<?= isset($obj->Status)
&& $obj->Status == 'Active'
? 'selected' : '' ?>>

Active

</option>

<option value="Inactive"

<?= isset($obj->Status)
&& $obj->Status == 'Inactive'
? 'selected' : '' ?>>

Inactive

</option>

</select>

</div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>Login Password</label>
            <input type="password" name="form[Password]" class="form-control" placeholder="<?= isset($obj->EmployeeID) && $obj->EmployeeID ? 'Leave blank to keep current password' : 'Enter login password (default is 123456)' ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control" accept="image/*">
            <?php if (!empty($obj->EmployeeID) && file_exists(FCPATH . 'media/img/employees/' . $obj->EmployeeID . '.png')): ?>
                <div class="m-t-10">
                    <img src="<?= base_url('media/img/employees/' . $obj->EmployeeID . '.png?' . time()) ?>" width="100" class="img-thumbnail" alt="Profile Picture">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>

<hr>

<h4 class="header-title m-b-30">

Salary & Costing Information

</h4>

<div class="row">

<div class="col-lg-3">
<div class="form-group">

<label>Basic Salary</label>

<input type="number"
       step="0.01"
       name="form[BasicSalary]"
       class="form-control BasicSalary"

       value="<?= isset($obj->BasicSalary)
       ? $obj->BasicSalary : 0 ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Allowance</label>

<input type="number"
       step="0.01"
       name="form[Allowance]"
       class="form-control Allowance"

       value="<?= isset($obj->Allowance)
       ? $obj->Allowance : 0 ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>OT Per Hour</label>

<input type="number"
       step="0.01"
       name="form[OTPerHour]"
       class="form-control"

       value="<?= isset($obj->OTPerHour)
       ? $obj->OTPerHour : 0 ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Total Salary</label>

<input type="text"
       class="form-control TotalSalary"
       readonly>

</div>
</div>

</div>

<div class="row">

<div class="col-lg-3">
<div class="form-group">

<label>Working Days / Month</label>

<input type="number"
       class="form-control WorkingDays"
       value="26">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Working Hours / Day</label>

<input type="number"
       class="form-control WorkingHours"
       value="8">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Total Monthly Minutes</label>

<input type="text"
       class="form-control MonthlyMinutes"
       readonly>

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Cost Per Minute</label>

<input type="number"
       step="0.01"
       name="form[CostPerMinute]"
       class="form-control CostPerMinute"
       readonly

       value="<?= isset($obj->CostPerMinute)
       ? $obj->CostPerMinute : 0 ?>">

</div>
</div>

</div>

<div class="form-group">

<label>Address</label>

<textarea name="form[Address]"
          class="form-control"
          rows="4"><?= isset($obj->Address)
          ? $obj->Address : '' ?></textarea>

</div>

<hr>

<div class="alert alert-info">

<strong>Professional Costing Method:</strong>

<br><br>

Cost Per Minute =
(Total Salary + Allowance)
÷
(Working Days × Working Hours × 60)

<br><br>

This helps to calculate:

<ul>

<li>Employee project cost</li>

<li>Project profitability</li>

<li>Productivity tracking</li>

<li>Client profitability</li>

</ul>

</div>

<button type="submit"
        class="btn btn-primary">

<i class="fa fa-save"></i>

Save Employee

</button>

<a href="<?= base_url('admin/employee') ?>"
   class="btn btn-secondary">

Cancel

</a>

</div>

<?= form_close() ?>

</div>
</div>

</div>
</div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>

$(document).ready(function(){

    function calculateCosting()
    {
        var basicSalary =
            parseFloat(
                $('.BasicSalary').val()
            ) || 0;

        var allowance =
            parseFloat(
                $('.Allowance').val()
            ) || 0;

        var workingDays =
            parseFloat(
                $('.WorkingDays').val()
            ) || 26;

        var workingHours =
            parseFloat(
                $('.WorkingHours').val()
            ) || 8;

        /*
        TOTAL SALARY
        */

        var totalSalary =
            basicSalary + allowance;

        $('.TotalSalary')
            .val(totalSalary.toFixed(2));

        /*
        MONTHLY MINUTES
        */

        var monthlyMinutes =
            workingDays
            *
            workingHours
            *
            60;

        $('.MonthlyMinutes')
            .val(monthlyMinutes);

        /*
        COST PER MINUTE
        */

        var costPerMinute = 0;

        if(monthlyMinutes > 0)
        {
            costPerMinute =
                totalSalary
                /
                monthlyMinutes;
        }

        $('.CostPerMinute')
            .val(costPerMinute.toFixed(2));
    }

    $(document).on(
        'input',
        '.BasicSalary,.Allowance,.WorkingDays,.WorkingHours',
        function(){

            calculateCosting();

        }
    );

    calculateCosting();

});

</script>

<?php $this->view('includes/footer_end.php'); ?>