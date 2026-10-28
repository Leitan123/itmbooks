<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            <?= !empty($obj->ExpenseID) ? 'Edit Expense' : 'Create Expense' ?>
                        </h4>

                        <ol class="breadcrumb p-0">
                            <li>
                                <a href="<?= base_url('admin') ?>">Dashboard</a>
                            </li>
                            <li>
                                <a href="<?= base_url('admin/expenses') ?>">Expenses</a>
                            </li>
                            <li class="active">
                                <?= !empty($obj->ExpenseID) ? 'Edit' : 'Create' ?>
                            </li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Expense Information</h4>

                        <form method="post" action="">
                            <!-- Row 1: Primary Details -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-2">
                                    <label>Date *</label>
                                    <input type="date"
                                           name="ExpenseDate"
                                           class="form-control"
                                           value="<?= !empty($obj->ExpenseDate) ? date('Y-m-d', strtotime($obj->ExpenseDate)) : date('Y-m-d') ?>"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label>Expense Account *</label>
                                    <select name="AccountID"
                                            id="AccountID"
                                            class="form-control select2"
                                            style="width: 100%;"
                                            required>
                                        <option value="">Select Account</option>
                                        <?php foreach($accounts as $row){ ?>
                                            <option value="<?= $row->AccountID ?>" 
                                                <?= !empty($obj->AccountID) && $obj->AccountID == $row->AccountID ? 'selected' : '' ?>>
                                                <?= $row->AccountCode ?> - <?= $row->AccountName ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Amount *</label>
                                    <input type="number"
                                           step="0.01"
                                           name="Amount"
                                           class="form-control"
                                           value="<?= !empty($obj->Amount) ? $obj->Amount : '' ?>"
                                           required>
                                </div>

                                <div class="col-md-3">
                                    <label>Payment Method</label>
                                    <select name="PaymentMethod"
                                            id="PaymentMethod"
                                            class="form-control">
                                        <option value="Cash" <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Cash') ? 'selected' : '' ?>>Cash</option>
                                        <option value="Bank" <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Bank') ? 'selected' : '' ?>>Bank</option>
                                        <option value="Cheque" <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Cheque') ? 'selected' : '' ?>>Cheque</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 2: Dynamic Bank / Cheque / Employee Details -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-4 bank-section" 
                                     style="display: <?= (!empty($obj->PaymentMethod) && ($obj->PaymentMethod == 'Bank' || $obj->PaymentMethod == 'Cheque')) ? 'block' : 'none' ?>;">
                                    <label>Bank Account</label>
                                    <select name="BankID" class="form-control">
                                        <option value="">Select Bank</option>
                                         <?php foreach($banks as $bank){ ?>
                                             <option value="<?= $bank->BankID ?>"
                                                 <?= (!empty($obj->BankID) && $obj->BankID == $bank->BankID) ? 'selected' : '' ?>>
                                                 <?= htmlspecialchars($bank->BankName) ?> - <?= htmlspecialchars($bank->AccountNumber) ?> (<?= htmlspecialchars($bank->AccountName) ?>)
                                             </option>
                                         <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-4 cheque-section" 
                                     style="display: <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Cheque') ? 'block' : 'none' ?>;">
                                    <label>Cheque No</label>
                                    <input type="text"
                                           name="ChequeNo"
                                           class="form-control"
                                           value="<?= !empty($obj->ChequeNo) ? $obj->ChequeNo : '' ?>">
                                </div>

                                <div class="col-md-4 cheque-section" 
                                     style="display: <?= (!empty($obj->PaymentMethod) && $obj->PaymentMethod == 'Cheque') ? 'block' : 'none' ?>;">
                                    <label>Cheque Date</label>
                                    <input type="date"
                                           name="ChequeDate"
                                           class="form-control"
                                           value="<?= !empty($obj->ChequeDate) ? date('Y-m-d', strtotime($obj->ChequeDate)) : '' ?>">
                                </div>

                                <div class="col-md-4 employee-section" 
                                     style="display: <?= (!empty($obj->EmployeeID)) ? 'block' : 'none' ?>;">
                                    <label>Employee</label>
                                    <select name="EmployeeID"
                                            id="EmployeeID"
                                            class="form-control">
                                        <option value="">Select Employee</option>
                                        <?php foreach($employees as $emp){ ?>
                                            <option value="<?= $emp->EmployeeID ?>"
                                                <?= (!empty($obj->EmployeeID) && $obj->EmployeeID == $emp->EmployeeID) ? 'selected' : '' ?>>
                                                <?= $emp->EmployeeName ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 3: Customer / Domain (Full Width to ensure fit and prevent wrapping alignment breaks) -->
                            <div class="row customer-section" 
                                 style="margin-bottom: 15px; display: <?= (!empty($obj->CustomerIds) && count($obj->CustomerIds) > 0) ? 'block' : 'none' ?>;">
                                <div class="col-md-12">
                                    <label>Customer / Domain</label>
                                    <select name="CustomerIds[]"
                                            class="form-control select2"
                                            multiple
                                            style="width: 100%;">
                                        <option value="">Select Customer</option>
                                         <?php foreach($customers as $customer){ 
                                             $domains = [];
                                             if (!empty($customer->DomainName)) {
                                                 $domains[] = $customer->DomainName;
                                             }
                                             if (!empty($customer->AdditionalDomains)) {
                                                 $domains = array_merge($domains, $customer->AdditionalDomains);
                                             }
                                             $domains = array_filter(array_unique($domains));
                                             
                                             if (empty($domains)) {
                                         ?>
                                             <option value="<?= $customer->CustomerId ?>:"
                                                 <?= (!empty($obj->CustomerIds) && in_array($customer->CustomerId . ':', $obj->CustomerIds)) ? 'selected' : '' ?>>
                                                 <?= htmlspecialchars($customer->CompanyName) ?> - No Domain
                                             </option>
                                         <?php 
                                             } else {
                                                 foreach ($domains as $domain) {
                                         ?>
                                             <option value="<?= $customer->CustomerId ?>:<?= htmlspecialchars($domain) ?>"
                                                 <?= (!empty($obj->CustomerIds) && in_array($customer->CustomerId . ':' . $domain, $obj->CustomerIds)) ? 'selected' : '' ?>>
                                                 <?= htmlspecialchars($customer->CompanyName) ?> - <?= htmlspecialchars($domain) ?>
                                             </option>
                                         <?php 
                                                 }
                                             }
                                         } 
                                         ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 4: General Details and Save Button -->
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Payee</label>
                                    <input type="text"
                                           name="Payee"
                                           id="Payee"
                                           class="form-control"
                                           value="<?= !empty($obj->Payee) ? $obj->Payee : '' ?>">
                                </div>

                                <div class="col-md-3">
                                    <label>Description</label>
                                    <input type="text"
                                           name="Description"
                                           class="form-control"
                                           value="<?= !empty($obj->Description) ? $obj->Description : '' ?>">
                                </div>

                                <div class="col-md-4">
                                    <label>Remarks</label>
                                    <input type="text"
                                           name="Remarks"
                                           class="form-control"
                                           value="<?= !empty($obj->Remarks) ? $obj->Remarks : '' ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Save
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="ExpenseID" value="<?= !empty($obj->ExpenseID) ? $obj->ExpenseID : '' ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>
$(document).ready(function(){
    $('#PaymentMethod').change(function(){
        var method = $(this).val();

        if(method == 'Bank')
        {
            $('.bank-section').show();
            $('.cheque-section').hide();
        }
        else if(method == 'Cheque')
        {
            $('.bank-section').show();
            $('.cheque-section').show();
        }
        else
        {
            $('.bank-section').hide();
            $('.cheque-section').hide();
        }
    });

    $('#AccountID').change(function(){
        var accountText = $("#AccountID option:selected").text().toLowerCase();

        if(accountText.indexOf('salary') >= 0)
        {
            $('.employee-section').show();
            $('#Payee').prop('readonly', true);
        }
        else
        {
            $('.employee-section').hide();
            $('#Payee').prop('readonly', false);
        }
    });

    $('#EmployeeID').change(function(){
        var employeeName = $("#EmployeeID option:selected").text();
        $('#Payee').val(employeeName);
    });

    // Check for hosting/domain related accounts
    $('#AccountID').trigger('change');
    
    // Check for customer section visibility
    var checkAccountForCustomer = function(){
        var account = $("#AccountID option:selected").text().toLowerCase();
        
        if(account.indexOf('hosting') >= 0 ||
           account.indexOf('domain') >= 0 ||
           account.indexOf('email') >= 0 ||
           account.indexOf('server') >= 0 ||
           account.indexOf('subscription') >= 0)
        {
            $('.customer-section').show();
        }
        else
        {
            $('.customer-section').hide();
        }
    };
    
    $('#AccountID').change(checkAccountForCustomer);
    checkAccountForCustomer();
});
</script>

<?php $this->view('includes/footer_end.php'); ?>