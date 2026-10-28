<?php $this->view('includes/header_start.php'); ?>
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
            <li><a href="<?= base_url('admin/employee_advances') ?>">Salary Advances</a></li>
            <li class="active">Record Advance / Loan</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
    <?= form_open('admin/employee_advances/save') ?>
    <input type="hidden" name="form[AdvanceID]" value="<?= isset($obj->AdvanceID) ? $obj->AdvanceID : 0 ?>">

    <div class="card-box">
        <h4 class="header-title m-t-0 m-b-30">Record New Salary Advance / Loan</h4>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Select Employee <span class="text-danger">*</span></label>
                    <select name="form[EmployeeID]" id="employee_id" class="form-control" required>
                        <option value="">-- Choose Employee --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp->EmployeeID ?>" <?= $obj->EmployeeID == $emp->EmployeeID ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp->EmployeeNo) ?> - <?= htmlspecialchars($emp->EmployeeName) ?> (<?= htmlspecialchars($emp->Designation ?: 'No Designation') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="outstanding_balance_container" style="margin-top: 8px; display: none;">
                        <span class="label label-warning" style="font-size: 90%; padding: 5px 10px; display: inline-block;">
                            Current Outstanding Advance Balance: Rs. <span id="outstanding_balance_val">0.00</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>Advance Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="form[AdvanceDate]" class="form-control" value="<?= htmlspecialchars($obj->AdvanceDate) ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Advance Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="form[Amount]" class="form-control" value="<?= htmlspecialchars($obj->Amount) ?>" placeholder="Enter amount to pay" required>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label>Payment Method <span class="text-danger">*</span></label>
                    <select name="form[PaymentMethod]" id="payment_method" class="form-control" required>
                        <option value="Cash" <?= $obj->PaymentMethod == 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="Bank" <?= $obj->PaymentMethod == 'Bank' ? 'selected' : '' ?>>Bank / Cheque</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3" id="bank_container" style="display: <?= $obj->PaymentMethod == 'Bank' ? 'block' : 'none' ?>;">
                <div class="form-group">
                    <label>Select Bank Account <span class="text-danger">*</span></label>
                    <select name="form[BankID]" id="bank_id" class="form-control">
                        <option value="">-- Choose Bank --</option>
                        <?php foreach($banks as $bank): ?>
                            <option value="<?= $bank->BankID ?>" <?= $obj->BankID == $bank->BankID ? 'selected' : '' ?>>
                                <?= htmlspecialchars($bank->BankName) ?> - <?= htmlspecialchars($bank->AccountNumber) ?> (<?= htmlspecialchars($bank->AccountName) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Remarks / Description</label>
            <textarea name="form[Description]" class="form-control" rows="3" placeholder="Enter reason, description or terms..."><?= htmlspecialchars($obj->Description) ?></textarea>
        </div>

        <div class="alert alert-warning">
            <strong><i class="fa fa-info-circle"></i> Accounting Integration:</strong><br>
            Saving this transaction will automatically create a general ledger journal entry. 
            <ul>
                <li><strong>Debit:</strong> Salary Advances / Employee Loans account (Assets).</li>
                <li><strong>Credit:</strong> Cash Account or Selected Bank Account.</li>
            </ul>
            The balance will automatically show as outstanding for the employee, and can be deducted during the next monthly payroll run.
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Save & Pay Advance
        </button>
        <a href="<?= base_url('admin/employee_advances') ?>" class="btn btn-secondary">
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
$(document).ready(function() {
    function toggleBank() {
        if ($('#payment_method').val() == 'Bank') {
            $('#bank_container').slideDown();
            $('#bank_id').attr('required', true);
        } else {
            $('#bank_container').slideUp();
            $('#bank_id').val('').removeAttr('required');
        }
    }

    $('#payment_method').change(toggleBank);

    // Fetch outstanding balance on employee change
    $('#employee_id').change(function() {
        var empId = $(this).val();
        if (empId) {
            $.ajax({
                url: '<?= base_url("admin/payroll/get_employee_payroll_data") ?>',
                type: 'POST',
                dataType: 'json',
                data: { employeeId: empId },
                success: function(res) {
                    if (res && res.OutstandingAdvances !== undefined) {
                        var bal = parseFloat(res.OutstandingAdvances) || 0;
                        if (bal > 0) {
                            $('#outstanding_balance_val').text(bal.toFixed(2));
                            $('#outstanding_balance_container').slideDown();
                        } else {
                            $('#outstanding_balance_container').slideUp();
                        }
                    } else {
                        $('#outstanding_balance_container').slideUp();
                    }
                },
                error: function() {
                    $('#outstanding_balance_container').slideUp();
                }
            });
        } else {
            $('#outstanding_balance_container').slideUp();
        }
    });

    // Run on page load if employee is already selected
    if ($('#employee_id').val()) {
        $('#employee_id').trigger('change');
    }
});
</script>
<?php $this->view('includes/footer_end.php'); ?>
