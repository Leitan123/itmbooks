<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">

<div class="page-title-box">

<h4 class="page-title">

Job Card Management

</h4>

<ol class="breadcrumb p-0">

<li>
<a href="<?= base_url('admin') ?>">
Dashboard
</a>
</li>

<li>
<a href="<?= base_url('admin/job_card') ?>">
Job Card
</a>
</li>

<li class="active">

<?= isset($obj->JobCardID)
? 'Edit Job Card'
: 'Create Job Card' ?>

</li>

</ol>

<div class="clearfix"></div>

</div>

</div>
</div>

<div class="row">
<div class="col-sm-12">

<?= form_open('admin/job_card/save') ?>

<input type="hidden"
       name="form[JobCardID]"
       value="<?= isset($obj->JobCardID)
       ? $obj->JobCardID : 0 ?>">

<input type="hidden"
       name="form[QuotationID]"
       value="<?= isset($quotation->QuotationID)
       ? $quotation->QuotationID : 0 ?>">

<div class="card-box">

<h4 class="header-title m-b-30">

Basic Information

</h4>

<div class="row">

<div class="col-lg-3">
<div class="form-group">

<label>Job Card No</label>

<input type="text"
       name="form[JobCardNo]"
       class="form-control"

       value="<?= isset($obj->JobCardNo)
       ? $obj->JobCardNo
       : 'JC-'.date('YmdHis') ?>"

       required>

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Job Date</label>

<input type="date"
       name="form[JobCardDate]"
       class="form-control"

       value="<?= isset($obj->JobCardDate)
       ? $obj->JobCardDate
       : date('Y-m-d') ?>"

       required>

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Priority</label>

<select name="form[Priority]"
        class="form-control">

<option value="Low"

<?= isset($obj->Priority)
&& $obj->Priority == 'Low'
? 'selected' : '' ?>>

Low

</option>

<option value="Medium"

<?= !isset($obj->Priority)
|| $obj->Priority == 'Medium'
? 'selected' : '' ?>>

Medium

</option>

<option value="High"

<?= isset($obj->Priority)
&& $obj->Priority == 'High'
? 'selected' : '' ?>>

High

</option>

<option value="Urgent"

<?= isset($obj->Priority)
&& $obj->Priority == 'Urgent'
? 'selected' : '' ?>>

Urgent

</option>

</select>

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Status</label>

<select name="form[Status]"
        class="form-control">

<option value="Pending"

<?= !isset($obj->Status)
|| $obj->Status == 'Pending'
? 'selected' : '' ?>>

Pending

</option>

<option value="In Progress"

<?= isset($obj->Status)
&& $obj->Status == 'In Progress'
? 'selected' : '' ?>>

In Progress

</option>

<option value="Completed"

<?= isset($obj->Status)
&& $obj->Status == 'Completed'
? 'selected' : '' ?>>

Completed

</option>

<option value="Cancelled"

<?= isset($obj->Status)
&& $obj->Status == 'Cancelled'
? 'selected' : '' ?>>

Cancelled

</option>

</select>

</div>
</div>

</div>

<div class="row">

<div class="col-lg-4">
<div class="form-group">

<label>Company Name</label>

<select name="form[CustomerID]"
        id="CustomerID"
        class="form-control select2"
        required>

<option value="">
Select Company
</option>

<?php foreach($customers as $customer): ?>

<option value="<?= $customer->CustomerId ?>"
        data-customer="<?= $customer->CustomerName ?>"
        data-domain="<?= $customer->DomainName ?>"

<?php

if(

(isset($obj->CustomerID)
&& $obj->CustomerID == $customer->CustomerId)

||

(isset($quotation->CustomerID)
&& $quotation->CustomerID == $customer->CustomerId)

)

{
    echo 'selected';
}

?>

>

<?= !empty($customer->CompanyName) ? $customer->CompanyName : $customer->CustomerName ?> (<?= $customer->CustomerName ?>)

</option>

<?php endforeach; ?>

</select>

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Customer Name</label>

<input type="text"
       id="CustomerName"
       name="form[CustomerName]"
       class="form-control"
       readonly

       value="<?= isset($obj->CustomerName)
       ? $obj->CustomerName : '' ?>">

</div>
</div>

<div class="col-lg-4" style="display: none;">
<div class="form-group">

<label>Domain Name</label>

<input type="text"
       id="DomainName"
       name="form[DomainName]"
       class="form-control"
       readonly

       value="<?= isset($obj->DomainName)
       ? $obj->DomainName : '' ?>">

</div>
</div>



<div class="col-lg-6">
<div class="form-group">

<label>Service</label>

<select name="form[ServiceID]"
        class="form-control select2"
        required>

<option value="">
Select Service
</option>

<?php foreach($items as $item): ?>

<option value="<?= $item->ItemID ?>"

<?php

if(

(isset($obj->ServiceID)
&& $obj->ServiceID == $item->ItemID)

||

(isset($quotation_items[0]->ItemID)
&& $quotation_items[0]->ItemID == $item->ItemID)

)

{
    echo 'selected';
}

?>

>

<?= $item->ItemName ?>

</option>

<?php endforeach; ?>

</select>

</div>
</div>

</div>

<div class="row">

<div class="col-lg-6">
<div class="form-group">

<label>Job Title</label>

<input type="text"
       name="form[Title]"
       class="form-control"

       value="<?= isset($obj->Title)
       ? $obj->Title
       : (
           isset($quotation_items[0]->ItemName)
           ? $quotation_items[0]->ItemName
           : ''
       ) ?>"

       required>

</div>
</div>

<?php

$totalAmount = 0;

if(isset($quotation->GrandTotal))
{
    $totalAmount = $quotation->GrandTotal;
}
elseif(!empty($quotation_items))
{
    foreach($quotation_items as $qitem)
    {
        $totalAmount += $qitem->TotalAmount;
    }
}

?>

<div class="col-lg-3">
<div class="form-group">

<label>Estimated Amount</label>

<input type="number"
       step="0.01"
       name="form[EstimatedAmount]"
       class="form-control"
       value="<?= isset($obj->EstimatedAmount)
       ? $obj->EstimatedAmount
       : (
            isset($quotation->GrandTotal)
            ? $quotation->GrandTotal
            : $totalAmount
         ) ?>">

</div>
</div>

<div class="col-lg-3">
<div class="form-group">

<label>Estimated Days</label>

<input type="number"
       name="form[EstimatedDays]"
       class="form-control"

       value="<?= isset($obj->EstimatedDays)
       ? $obj->EstimatedDays : 1 ?>">

</div>
</div>

</div>

<div class="form-group">

<label>Description</label>

<textarea name="form[Description]"
          class="form-control"
          rows="4"><?= isset($obj->Description)
          ? $obj->Description
          : (
              isset($quotation->Remarks)
              ? $quotation->Remarks
              : ''
          ) ?></textarea>

</div>

<div class="row">

<div class="col-lg-6">
<div class="form-group">

<label>Start Date</label>

<input type="date"
       name="form[StartDate]"
       class="form-control"

       value="<?= isset($obj->StartDate)
       ? $obj->StartDate
       : date('Y-m-d') ?>">

</div>
</div>

<div class="col-lg-6">
<div class="form-group">

<label>Deadline</label>

<input type="date"
       name="form[Deadline]"
       class="form-control"

       value="<?= isset($obj->Deadline)
       ? $obj->Deadline : '' ?>">

</div>
</div>

</div>

<hr>

<h4 class="header-title m-b-20">

Employee Work Tracking

</h4>

<p class="text-muted">

Track employee working time, costing,
productivity and project profitability.

</p>

<div id="employee-container">

<div class="row employee-row">

<div class="col-lg-2">
<div class="form-group">

<label>Employee</label>

<select name="employees[0][EmployeeID]"
        class="form-control select2 employee-select">

<option value="">
Select
</option>

<?php foreach($employees as $employee): ?>

<option value="<?= $employee->EmployeeID ?>">

<?= $employee->EmployeeName ?>

</option>

<?php endforeach; ?>

</select>

</div>
</div>

<div class="col-lg-2">
<div class="form-group">

<label>Work Date</label>

<input type="date"
       name="employees[0][WorkDate]"
       class="form-control"
       value="<?= date('Y-m-d') ?>">

</div>
</div>

<div class="col-lg-1">
<div class="form-group">

<label>Days</label>

<input type="number"
       name="employees[0][Days]"
       class="form-control Days"
       value="0">

</div>
</div>

<div class="col-lg-1">
<div class="form-group">

<label>Hours</label>

<input type="number"
       name="employees[0][Hours]"
       class="form-control Hours"
       value="0">

</div>
</div>

<div class="col-lg-1">
<div class="form-group">

<label>Minutes</label>

<input type="number"
       name="employees[0][Minutes]"
       class="form-control Minutes"
       value="0">

</div>
</div>

<div class="col-lg-2">
<div class="form-group">

<label>Cost / Minute</label>

<input type="number"
       step="0.01"
       name="employees[0][CostPerMinute]"
       class="form-control CostPerMinute"
       value="0"
       readonly>

</div>
</div>

<div class="col-lg-2">
<div class="form-group">

<label>Total Cost</label>

<input type="text"
       name="employees[0][TotalCost]"
       class="form-control TotalCost"
       value="0.00"
       readonly>

</div>
</div>

<div class="col-lg-1">
<div class="form-group">

<label>&nbsp;</label>

<button type="button"
        class="btn btn-danger remove-employee">

X

</button>

</div>
</div>

</div>

</div>

<button type="button"
        class="btn btn-success"
        id="addEmployee">

<i class="fa fa-plus"></i>

Add Employee

</button>

<hr>

<div class="form-group">

<label>Remarks</label>

<textarea name="form[Remarks]"
          class="form-control"
          rows="3"><?= isset($obj->Remarks)
          ? $obj->Remarks : '' ?></textarea>

</div>

<button type="submit"
        class="btn btn-primary">

<i class="fa fa-save"></i>

Save Job Card

</button>

<a href="<?= base_url('admin/job_card') ?>"
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

    /*
    SELECT2
    */

    $('.select2').select2();

    /*
    ADD EMPLOYEE
    */

    $('#addEmployee').click(function(){

        var index =
            $('.employee-row').length;

        var html = `

        <div class="row employee-row m-t-10">

            <div class="col-lg-2">
            <div class="form-group">

            <select name="employees[\${index}][EmployeeID]"
                    class="form-control select2 employee-select">

                <option value="">
                    Select
                </option>

                <?php foreach($employees as $employee): ?>

                <option value="<?= $employee->EmployeeID ?>">

                    <?= $employee->EmployeeName ?>

                </option>

                <?php endforeach; ?>

            </select>

            </div>
            </div>

            <div class="col-lg-2">
            <div class="form-group">

            <input type="date"
                   name="employees[\${index}][WorkDate]"
                   class="form-control"
                   value="<?= date('Y-m-d') ?>">

            </div>
            </div>

            <div class="col-lg-1">
            <div class="form-group">

            <input type="number"
                   name="employees[\${index}][Days]"
                   class="form-control Days"
                   value="0">

            </div>
            </div>

            <div class="col-lg-1">
            <div class="form-group">

            <input type="number"
                   name="employees[\${index}][Hours]"
                   class="form-control Hours"
                   value="0">

            </div>
            </div>

            <div class="col-lg-1">
            <div class="form-group">

            <input type="number"
                   name="employees[\${index}][Minutes]"
                   class="form-control Minutes"
                   value="0">

            </div>
            </div>

            <div class="col-lg-2">
            <div class="form-group">

            <input type="number"
                   step="0.01"
                   name="employees[\${index}][CostPerMinute]"
                   class="form-control CostPerMinute"
                   value="0"
                   readonly>

            </div>
            </div>

            <div class="col-lg-2">
            <div class="form-group">

            <input type="text"
                   name="employees[\${index}][TotalCost]"
                   class="form-control TotalCost"
                   value="0.00"
                   readonly>

            </div>
            </div>

            <div class="col-lg-1">
            <div class="form-group">

            <button type="button"
                    class="btn btn-danger remove-employee">

                X

            </button>

            </div>
            </div>

        </div>

        `;

        $('#employee-container')
            .append(html);

        $('.select2').select2();

    });

    /*
    REMOVE EMPLOYEE
    */

    $(document).on(
        'click',
        '.remove-employee',
        function(){

            $(this)
                .closest('.employee-row')
                .remove();

        }
    );

    /*
    AUTO LOAD COST PER MINUTE
    */

    $(document).on(
        'change',
        '.employee-select',
        function(){

            var row =
                $(this)
                .closest('.employee-row');

            var employeeID =
                $(this).val();

            $.ajax({

                url:
                "<?= base_url('admin/job_card/get_employee_cost') ?>",

                type: "POST",

                data: {
                    EmployeeID: employeeID
                },

                dataType: "json",

                success: function(data)
                {
                    row.find('.CostPerMinute')
                        .val(data.CostPerMinute);

                    calculateRow(row);
                }

            });

        }
    );

    /*
    CALCULATE ROW
    */

    function calculateRow(row)
    {
        var days =
            parseFloat(
                row.find('.Days').val()
            ) || 0;

        var hours =
            parseFloat(
                row.find('.Hours').val()
            ) || 0;

        var minutes =
            parseFloat(
                row.find('.Minutes').val()
            ) || 0;

        var cost =
            parseFloat(
                row.find('.CostPerMinute').val()
            ) || 0;

        /*
        1 DAY = 8 HOURS
        */

        var totalMinutes =
            (days * 8 * 60)
            +
            (hours * 60)
            +
            minutes;

        /*
        TOTAL COST
        */

        var totalCost =
            totalMinutes * cost;

        row.find('.TotalCost')
            .val(totalCost.toFixed(2));
    }

    $(document).on(
        'input',
        '.Days,.Hours,.Minutes',
        function(){

            var row =
                $(this)
                .closest('.employee-row');

            calculateRow(row);

        }
    );

    $('.select2').select2();

function loadCustomerDetails()
{
    var selected =
        $('#CustomerID option:selected');

    $('#CustomerName').val(
        selected.data('customer')
    );

    $('#DomainName').val(
        selected.data('domain')
    );
}

$('#CustomerID').change(function(){

    loadCustomerDetails();

});

loadCustomerDetails();


});
    
</script>

<?php $this->view('includes/footer_end.php'); ?>