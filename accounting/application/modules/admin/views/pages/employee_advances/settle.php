<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
    <div class="page-title-box">
        <h4 class="page-title">Settle Salary Advance / Loan Manually</h4>
        <ol class="breadcrumb p-0">
            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
            <li><a href="<?= base_url('admin/employee_advances') ?>">Salary Advances</a></li>
            <li class="active">Settle Advance</li>
        </ol>
        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
    <?= form_open('admin/employee_advances/save_settlement', 'id="settlementForm"') ?>
    <input type="hidden" name="AdvanceID" value="<?= $advance->AdvanceID ?>">

    <div class="card-box">
        <h4 class="header-title m-t-0 m-b-30">Record Direct Payment Return</h4>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Employee Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($advance->EmployeeNo) ?> - <?= htmlspecialchars($advance->EmployeeName) ?> (<?= htmlspecialchars($advance->Designation ?: '-') ?>)" readonly style="background-color: #eee;">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>Settle / Return Date <span class="text-danger">*</span></label>
                    <input type="date" name="SettleDate" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
        </div>

        <div class="row m-t-15">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Original Advance Amount</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        <input type="text" class="form-control" value="<?= number_format($advance->Amount, 2) ?>" readonly style="background-color: #eee;">
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Amount Already Recovered</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        <input type="text" class="form-control" value="<?= number_format($advance->AmountRecovered, 2) ?>" readonly style="background-color: #eee; color: #25D366;">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <?php $outstanding = $advance->Amount - $advance->AmountRecovered; ?>
                <div class="form-group">
                    <label>Current Outstanding Balance</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rs.</span>
                        <input type="number" id="outstanding_bal" class="form-control" value="<?= $outstanding ?>" readonly style="background-color: #eee; font-weight: bold; color: #ff5b5b;">
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row m-t-15">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Settle Amount (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" max="<?= $outstanding ?>" name="SettleAmount" id="settle_amount" class="form-control" value="<?= $outstanding ?>" placeholder="Enter returned payment amount" required>
                    <small class="text-muted" id="settle_warning" style="color: #ff5b5b; display: none;">Settle amount cannot exceed outstanding balance.</small>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label>Refund Method <span class="text-danger">*</span></label>
                    <select name="PaymentMethod" id="payment_method" class="form-control" required>
                        <option value="Cash">Cash Return</option>
                        <option value="Bank">Bank Transfer / Cheque</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3" id="bank_container" style="display: none;">
                <div class="form-group">
                    <label>Select Deposit Bank Account <span class="text-danger">*</span></label>
                    <select name="BankID" id="bank_id" class="form-control">
                        <option value="">-- Choose Bank --</option>
                        <?php foreach($banks as $bank): ?>
                            <option value="<?= $bank->BankID ?>">
                                <?= htmlspecialchars($bank->BankName) ?> (Acc: <?= htmlspecialchars($bank->AccountNumber ?: '-') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Remarks / Description</label>
            <textarea name="Description" class="form-control" rows="3" placeholder="Enter remarks about this settlement..."></textarea>
        </div>

        <div class="alert alert-warning">
            <strong><i class="fa fa-info-circle"></i> Double-Entry General Ledger Posting:</strong><br>
            Saving this settlement will automatically create a general ledger journal entry. 
            <ul>
                <li><strong>Debit:</strong> Cash Account or Selected Bank Account (increases assets).</li>
                <li><strong>Credit:</strong> Salary Advances / Employee Loans account (decreases outstanding asset loan balance).</li>
            </ul>
        </div>

        <button type="submit" class="btn btn-success" id="btn_submit">
            <i class="fa fa-save"></i> Save Settlement
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

    // Limit validation
    $('#settle_amount').on('input change', function() {
        var outstanding = parseFloat($('#outstanding_bal').val()) || 0;
        var settle = parseFloat($(this).val()) || 0;

        if (settle > outstanding) {
            $(this).val(outstanding.toFixed(2));
            $('#settle_warning').fadeIn();
        } else {
            $('#settle_warning').fadeOut();
        }
    });
});
</script>
<?php $this->view('includes/footer_end.php'); ?>
