<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            <?= !empty($obj->DrawingID) ? 'Edit Drawings Entry' : 'Create Drawings Entry' ?>
                        </h4>

                        <ol class="breadcrumb p-0">
                            <li>
                                <a href="<?= base_url('admin') ?>">Dashboard</a>
                            </li>
                            <li>
                                <a href="<?= base_url('admin/drawings') ?>">Drawings</a>
                            </li>
                            <li class="active">
                                <?= !empty($obj->DrawingID) ? 'Edit' : 'Create' ?>
                            </li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Drawings / Personal Withdrawals Information</h4>

                        <form method="post" action="" enctype="multipart/form-data">
                            <!-- Row 1: Primary Details -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-3">
                                    <label>Date *</label>
                                    <input type="date"
                                           name="DrawingDate"
                                           class="form-control"
                                           value="<?= !empty($obj->DrawingDate) ? date('Y-m-d', strtotime($obj->DrawingDate)) : date('Y-m-d') ?>"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label>Director / Owner *</label>
                                    <select name="EmployeeID"
                                            id="EmployeeID"
                                            class="form-control select2"
                                            style="width: 100%;"
                                            required>
                                        <option value="">Select Director</option>
                                        <?php foreach($directors as $row){ ?>
                                            <option value="<?= $row->EmployeeID ?>" 
                                                <?= !empty($obj->EmployeeID) && $obj->EmployeeID == $row->EmployeeID ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row->EmployeeName) ?> (<?= htmlspecialchars($row->Designation) ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label>Withdrawal Type *</label>
                                    <select name="WithdrawalType"
                                            id="WithdrawalType"
                                            class="form-control"
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="Personal Expense" <?= (!empty($obj->WithdrawalType) && $obj->WithdrawalType == 'Personal Expense') ? 'selected' : '' ?>>Personal Expense</option>
                                        <option value="Cash Withdrawal" <?= (!empty($obj->WithdrawalType) && $obj->WithdrawalType == 'Cash Withdrawal') ? 'selected' : '' ?>>Cash Withdrawal</option>
                                        <option value="School Fees" <?= (!empty($obj->WithdrawalType) && $obj->WithdrawalType == 'School Fees') ? 'selected' : '' ?>>School Fees</option>
                                        <option value="Medical" <?= (!empty($obj->WithdrawalType) && $obj->WithdrawalType == 'Medical') ? 'selected' : '' ?>>Medical</option>
                                        <option value="Other" <?= (!empty($obj->WithdrawalType) && $obj->WithdrawalType == 'Other') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 2: Payment details and Amount -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-3">
                                    <label>Paid From *</label>
                                    <select name="PaymentMethod"
                                            id="PaymentMethod"
                                            class="form-control"
                                            required>
                                        <option value="Cash" <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Cash') ? 'selected' : '' ?>>Cash</option>
                                        <option value="Bank" <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Bank') ? 'selected' : '' ?>>Bank</option>
                                    </select>
                                </div>

                                <div class="col-md-5 bank-section" 
                                     style="display: <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Bank') ? 'block' : 'none' ?>;">
                                    <label>Bank Account *</label>
                                    <select name="BankID" id="BankID" class="form-control">
                                        <option value="">Select Bank Account</option>
                                        <?php foreach($banks as $bank){ ?>
                                            <option value="<?= $bank->BankID ?>"
                                                <?= (!empty($obj->BankID) && $obj->BankID == $bank->BankID) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($bank->BankName) ?> - <?= htmlspecialchars($bank->AccountNumber) ?> (<?= htmlspecialchars($bank->AccountName) ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Amount (LKR) *</label>
                                    <input type="number"
                                           step="0.01"
                                           name="Amount"
                                           class="form-control"
                                           value="<?= !empty($obj->Amount) ? $obj->Amount : '' ?>"
                                           required>
                                </div>
                            </div>

                            <!-- Row 3: Beneficiary and Description -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-4">
                                    <label>Beneficiary / Payee</label>
                                    <input type="text"
                                           name="Payee"
                                           class="form-control"
                                            placeholder="e.g. School / Person / Supplier"
                                           value="<?= !empty($obj->Payee) ? htmlspecialchars((string)$obj->Payee) : '' ?>">
                                </div>

                                <div class="col-md-8">
                                    <label>Description</label>
                                    <input type="text"
                                           name="Description"
                                           class="form-control"
                                           placeholder="e.g. Daughter's 2nd Term School Fees"
                                           value="<?= !empty($obj->Description) ? htmlspecialchars((string)$obj->Description) : '' ?>">
                                </div>
                            </div>

                            <!-- Row 4: Attachment Upload -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-6">
                                    <label>Attachment (Receipt - Optional)</label>
                                    <input type="file" name="Attachment" class="form-control">
                                    <?php if(!empty($obj->Attachment)): ?>
                                        <div style="margin-top: 10px;">
                                            <span class="label label-info">Current File:</span>
                                            <a href="<?= base_url('media/doc/' . $obj->Attachment) ?>" target="_blank">
                                                <?= htmlspecialchars((string)$obj->Attachment) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Row 5: Action Buttons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-save"></i> Save Drawings Entry
                                    </button>
                                    <a href="<?= base_url('admin/drawings') ?>" class="btn btn-default waves-effect">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>
$(document).ready(function() {
    // Dynamic display of Bank selection
    $('#PaymentMethod').change(function() {
        var method = $(this).val();
        if (method === 'Bank') {
            $('.bank-section').slideDown();
            $('#BankID').attr('required', 'required');
        } else {
            $('.bank-section').slideUp();
            $('#BankID').removeAttr('required').val('');
        }
    });

    // On edit load, adjust required attributes
    if ($('#PaymentMethod').val() === 'Bank') {
        $('#BankID').attr('required', 'required');
    }
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
