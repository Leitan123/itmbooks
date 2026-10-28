<?php $this->view('includes/header_start.php'); ?>
<style>
.summary-card {
    background: #f8f9fa;
    border-left: 4px solid #25D366;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}
.summary-val {
    font-size: 1.5rem;
    font-weight: bold;
    color: #25D366;
}
.deduction-val {
    font-size: 1.2rem;
    font-weight: bold;
    color: #ff5b5b;
}
.contribution-val {
    font-size: 1.1rem;
    font-weight: bold;
    color: #3bafda;
}
</style>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
    <div class="page-title-box">
        <h4 class="page-title">Generate Employee Pay Sheet</h4>
        <ol class="breadcrumb p-0">
            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
            <li><a href="<?= base_url('admin/payroll') ?>">Monthly Payroll</a></li>
            <li class="active">Generate Pay Sheet</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
    <?= form_open('admin/payroll/save', 'id="payrollForm"') ?>
    <input type="hidden" name="form[SlipID]" value="<?= isset($obj->SlipID) ? $obj->SlipID : 0 ?>">

    <div class="card-box">
        <h4 class="header-title m-t-0 m-b-30">Generate Pay Slip & Post to Ledger</h4>
        
        <div class="row">
            <div class="col-lg-4">
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
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Payroll Month <span class="text-danger">*</span></label>
                    <input type="month" name="form[PayrollMonth]" id="payroll_month" class="form-control" value="<?= htmlspecialchars($obj->PayrollMonth) ?>" required>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Payment / Processing Date <span class="text-danger">*</span></label>
                    <input type="date" name="form[PaymentDate]" class="form-control" value="<?= htmlspecialchars($obj->PaymentDate) ?>" required>
                </div>
            </div>
        </div>

        <div class="row m-t-20">
            <!-- Salary Components (Earnings) -->
            <div class="col-md-6">
                <div class="card-box" style="border: 1px solid #e3e3e3; background: #fff;">
                    <h5 class="header-title m-t-0 m-b-20 text-success"><i class="fa fa-money"></i> 1. Earnings (Rs.)</h5>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Basic Salary</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[BasicSalary]" id="basic_salary" class="form-control" value="<?= $obj->BasicSalary ?>" placeholder="Enter basic salary">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Allowance</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[Allowance]" id="allowance" class="form-control" value="<?= $obj->Allowance ?>" placeholder="Enter allowance">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Attendance Allowance</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="custom_allowance_names[]" value="Attendance Allowance">
                            <input type="number" step="0.01" name="custom_allowance_amounts[]" id="attendance_allowance" class="form-control custom_allowance" value="0.00" placeholder="Enter attendance allowance">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Other Allowance</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="custom_allowance_names[]" value="Other Allowance">
                            <input type="number" step="0.01" name="custom_allowance_amounts[]" id="other_allowance" class="form-control custom_allowance" value="0.00" placeholder="Enter other allowance">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">OT Hours</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" name="form[OTHours]" id="ot_hours" class="form-control" value="<?= $obj->OTHours ?>" placeholder="Enter overtime hours">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">OT Rate / Hr</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" name="form[OTRate]" id="ot_rate" class="form-control" value="<?= $obj->OTRate ?>" placeholder="Hourly OT rate">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">OT Amount</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[OTAmount]" id="ot_amount" class="form-control" value="<?= $obj->OTAmount ?>" readonly style="background-color: #eee;">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row" style="font-weight: bold;">
                        <label class="col-sm-4 col-form-label">Gross Salary</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[GrossSalary]" id="gross_salary" class="form-control" value="<?= $obj->GrossSalary ?>" readonly style="background-color: #ddd; font-weight: bold;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deductions & Contributions -->
            <div class="col-md-6">
                <!-- Statutory Deductions -->
                <div class="card-box" style="border: 1px solid #e3e3e3; background: #fff;">
                    <h5 class="header-title m-t-0 m-b-20 text-danger"><i class="fa fa-minus-circle"></i> 2. Deductions & Statutory (Rs.)</h5>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Calculate EPF/ETF</label>
                        <div class="col-sm-8" style="padding-top: 7px;">
                            <input type="checkbox" id="enable_epf_etf" checked>
                            <span class="text-muted"> Apply standard EPF & ETF contributions</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Employee EPF (8%)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[EmployeeEPF]" id="employee_epf" class="form-control" value="<?= $obj->EmployeeEPF ?>" readonly style="background-color: #eee;">
                            <small class="text-muted">Deducted from basic salary</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Outstanding Advance Balance</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rs.</span>
                                <input type="number" step="0.01" id="outstanding_advances" class="form-control" value="0.00" readonly style="background-color: #eee; font-weight: bold;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Advance Deduction</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" name="form[AdvanceDeduction]" id="advance_deduction" class="form-control" value="<?= $obj->AdvanceDeduction ?>" placeholder="Enter amount to recover">
                            <small class="text-danger" id="advance_warning" style="display:none;"></small>
                        </div>
                    </div>
                </div>

                <!-- Employer Contributions -->
                <div class="card-box" style="border: 1px solid #e3e3e3; background: #fff;">
                    <h5 class="header-title m-t-0 m-b-20 text-info"><i class="fa fa-university"></i> 3. Employer Contributions (Paid by Co.)</h5>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Company EPF (12%)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[CompanyEPF]" id="company_epf" class="form-control" value="<?= $obj->CompanyEPF ?>" readonly style="background-color: #eee;">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Company ETF (3%)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" name="form[CompanyETF]" id="company_etf" class="form-control" value="<?= $obj->CompanyETF ?>" readonly style="background-color: #eee;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Payment settings -->
            <div class="col-lg-6">
                <div class="card-box" style="border: 1px solid #e3e3e3; background: #fff;">
                    <h5 class="header-title m-t-0 m-b-20"><i class="fa fa-credit-card"></i> 4. Payment Settlement</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Method <span class="text-danger">*</span></label>
                                <select name="form[PaymentMethod]" id="payment_method" class="form-control" required>
                                    <option value="Cash" <?= $obj->PaymentMethod == 'Cash' ? 'selected' : '' ?>>Cash</option>
                                    <option value="Bank" <?= $obj->PaymentMethod == 'Bank' ? 'selected' : '' ?>>Bank Transfer / Cheque</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6" id="bank_container" style="display: <?= $obj->PaymentMethod == 'Bank' ? 'block' : 'none' ?>;">
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
                </div>
            </div>

            <!-- Totals & Confirmation Summary -->
            <div class="col-lg-6">
                <div class="summary-card">
                    <h5 class="m-t-0 m-b-15">Salary Summary</h5>
                    <div class="row">
                        <div class="col-xs-6">Gross Earnings:</div>
                        <div class="col-xs-6 text-right font-600">Rs. <span id="lbl_gross">0.00</span></div>
                    </div>
                    <div class="row m-t-5">
                        <div class="col-xs-6 text-danger">Total Deductions (EPF 8% + Advance):</div>
                        <div class="col-xs-6 text-right text-danger font-600">- Rs. <span id="lbl_deductions">0.00</span></div>
                    </div>
                    <div class="row m-t-10" style="border-top: 1px solid #ddd; padding-top: 10px;">
                        <div class="col-xs-6" style="font-size: 1.1rem; font-weight: bold;">Net Salary Payable:</div>
                        <div class="col-xs-6 text-right summary-val">Rs. <span id="lbl_net">0.00</span></div>
                    </div>
                    <input type="hidden" name="form[NetSalary]" id="net_salary" value="<?= $obj->NetSalary ?>">
                </div>

                <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> Double-entry Ledger Posting:</strong><br>
                    Saving this salary slip will automatically debit expenses (Gross Salary, Company EPF/ETF) and credit payables & bank/cash.
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-success btn-lg" id="btn_save">
                <i class="fa fa-save"></i> Save & Post Payroll Slip
            </button>
            <a href="<?= base_url('admin/payroll') ?>" class="btn btn-secondary btn-lg">
                Cancel
            </a>
        </div>
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
    // Toggle bank visibility
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

    // Employee Selection Ajax
    $('#employee_id').change(function() {
        var empId = $(this).val();
        if (empId) {
            $.ajax({
                url: '<?= base_url("admin/payroll/get_employee_payroll_data") ?>',
                type: 'POST',
                dataType: 'json',
                data: { employeeId: empId },
                success: function(res) {
                    if (res.error) {
                        alert(res.error);
                    } else {
                        $('#basic_salary').val(res.BasicSalary.toFixed(2));
                        $('#allowance').val(res.Allowance.toFixed(2));
                        $('#ot_rate').val(res.OTPerHour.toFixed(2));
                        $('#outstanding_advances').val(res.OutstandingAdvances.toFixed(2));
                        
                        // Default advance deduction to 0
                        $('#advance_deduction').val('0.00');
                        
                        // Recalculate
                        calculatePayroll();
                    }
                },
                error: function() {
                    alert('Error retrieving employee salary settings.');
                }
            });
        } else {
            // Clear values
            $('#basic_salary').val('0.00');
            $('#allowance').val('0.00');
            $('.custom_allowance').val('0.00');
            $('#ot_rate').val('0.00');
            $('#ot_hours').val('');
            $('#ot_amount').val('0.00');
            $('#gross_salary').val('0.00');
            $('#employee_epf').val('0.00');
            $('#company_epf').val('0.00');
            $('#company_etf').val('0.00');
            $('#outstanding_advances').val('0.00');
            $('#advance_deduction').val('0.00');
            
            calculatePayroll();
        }
    });

    // Inputs trigger recalculation
    $('#basic_salary, #allowance, #ot_hours, #ot_rate, #advance_deduction, #enable_epf_etf, .custom_allowance').on('input change', function() {
        calculatePayroll();
    });

    function calculatePayroll() {
        var basic = parseFloat($('#basic_salary').val()) || 0;
        var allowance = parseFloat($('#allowance').val()) || 0;
        var otHrs = parseFloat($('#ot_hours').val()) || 0;
        var otRate = parseFloat($('#ot_rate').val()) || 0;
        var outstandingAdv = parseFloat($('#outstanding_advances').val()) || 0;
        var advanceDed = parseFloat($('#advance_deduction').val()) || 0;

        var totalCustomAllowances = 0;
        $('.custom_allowance').each(function() {
            totalCustomAllowances += parseFloat($(this).val()) || 0;
        });

        // Math
        var otAmount = otHrs * otRate;
        var gross = basic + allowance + otAmount + totalCustomAllowances;
        var empEpf = 0;
        var coEpf = 0;
        var coEtf = 0;
        if ($('#enable_epf_etf').is(':checked')) {
            empEpf = basic * 0.08;
            coEpf = basic * 0.12;
            coEtf = basic * 0.03;
        }

        // Deduction validation
        var maxDeduction = outstandingAdv;
        // Also cap deduction so Net salary cannot be negative
        var maxPossibleDeduction = gross - empEpf;
        if (maxPossibleDeduction < 0) maxPossibleDeduction = 0;
        
        var warningText = "";
        if (advanceDed > outstandingAdv) {
            advanceDed = outstandingAdv;
            $('#advance_deduction').val(advanceDed.toFixed(2));
            warningText = "Deduction capped to outstanding balance (Rs. " + outstandingAdv.toFixed(2) + ")";
        }
        if (advanceDed > maxPossibleDeduction) {
            advanceDed = maxPossibleDeduction;
            $('#advance_deduction').val(advanceDed.toFixed(2));
            warningText = "Deduction capped to prevent negative net salary (Rs. " + maxPossibleDeduction.toFixed(2) + ")";
        }

        if (warningText) {
            $('#advance_warning').text(warningText).show();
        } else {
            $('#advance_warning').hide();
        }

        var totalDeductions = empEpf + advanceDed;
        var net = gross - totalDeductions;

        // Populate fields
        $('#ot_amount').val(otAmount.toFixed(2));
        $('#gross_salary').val(gross.toFixed(2));
        $('#employee_epf').val(empEpf.toFixed(2));
        $('#company_epf').val(coEpf.toFixed(2));
        $('#company_etf').val(coEtf.toFixed(2));
        $('#net_salary').val(net.toFixed(2));

        // Label outputs
        $('#lbl_gross').text(gross.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#lbl_deductions').text(totalDeductions.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#lbl_net').text(net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        // Disable submit button if Net Salary is invalid or employee not selected
        if (net < 0 || !$('#employee_id').val()) {
            $('#btn_save').attr('disabled', true);
        } else {
            $('#btn_save').removeAttr('disabled');
        }
    }

    // Run initial calculation
    calculatePayroll();
});
</script>
<?php $this->view('includes/footer_end.php'); ?>
