<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Customer Payment</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/customer_payments') ?>">Customer Payments</a></li>
                            <li class="active"><?= empty($obj->PaymentID) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Payment Details</h4>

                        <div class="row">
                            <!-- Customer Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customer_select">Customer</label>
                                    <select name="form[CustomerId]" id="customer_select" class="form-control select2">
                                        <option value="">-- Select Customer --</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->CustomerId ?>"><?= $customer->CustomerName ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Credit Balance Field -->
          
                            <input type="hidden" id="CreditBalance" name="form[CreditBalance]" class="form-control" value="0.00" readonly>
                            
                            <!-- Payment Date Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="paymentDate">Payment Date</label>
                                    <input type="date" id="paymentDate" name="form[PaymentDate]" class="form-control" 
                                        value="<?= isset($obj->PaymentDate) ? htmlspecialchars($obj->PaymentDate) : date('Y-m-d') ?>">
                                </div>
                            </div>

                            <!-- Payment Due Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="paymentDue">Payment Due</label>
                                    <input required type="number" id="paymentDue" name="form[PaymentDue]" class="form-control" 
                                        value="<?= isset($obj->PaymentDue) ? htmlspecialchars($obj->PaymentDue) : '0.00' ?>" 
                                        step="0.01" min="0" readonly> <!-- Set readonly since it's updated via AJAX -->
                                </div>
                            </div>

                            <!-- Paid Amount Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="paidAmount">Paid Amount</label>
                                    <input type="number" id="paidAmount" name="form[PaidAmount]" class="form-control" required>
                                </div>
                            </div>

                        
                            <!-- Payment Method Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="paymentMethod">Payment Method</label>
                                    <select id="paymentMethod" name="form[PaymentMethod]" class="form-control">
                                        <option value="">Select Payment Method</option>
                                        <option value="Cash" <?= (isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Cash') ? 'selected' : '' ?>>Cash</option>
                                        <option value="Card" <?= (isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Card') ? 'selected' : '' ?>>Card</option>
                                        <option value="Bank Transfer" <?= (isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Bank Transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                                        <option value="Cheque" <?= (isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Cheque') ? 'selected' : '' ?>>Cheque</option>
                                    </select>
                                    <?= form_error('form[PaymentMethod]') ?>
                                </div>
                            </div>

                            <!-- Cheque Fields (initially hidden) -->
                            <div id="chequeFields" style="display: none; margin-top: 15px;">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="chequeNumber">Cheque Number</label>
                                            <input type="text" id="chequeNumber" name="form[ChequeNumber]" class="form-control"
                                                value="<?= isset($obj->ChequeNumber) ? htmlspecialchars($obj->ChequeNumber) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="chequeDate">Cheque Date</label>
                                            <input type="date" id="chequeDate" name="form[ChequeDate]" class="form-control"
                                                value="<?= isset($obj->ChequeDate) ? htmlspecialchars($obj->ChequeDate) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="bankName">Bank</label>
                                            <select id="bankName" name="form[BankName]" class="form-control">
                                                <option value="">Select Bank</option>
                                                <?php 
                                                $banks = array('BOC', 'Sampath', 'Commercial', 'DFCC', 'Peoples', 'NDB', 'NTB', 'HSBC', 'Standard');
                                                foreach ($banks as $bank) {
                                                    $selected = (isset($obj->BankName) && $obj->BankName === $bank) ? 'selected' : '';
                                                    echo '<option value="'.$bank.'" '.$selected.'>'.$bank.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success pull-right">Save</button>

                    <?= form_close() ?>
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container -->

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->

<script>
$('#customer_select').change(function () {
    var customerId = $(this).val();
    if (customerId) {
        $.ajax({
            url: '<?= base_url("admin/Customer_payments/get_total_due_amount/") ?>' + customerId,
            method: 'GET',
            success: function (response) {
                console.log(response); // Debugging: Log the response
                try {
                    var data = JSON.parse(response);
                    var paymentDue = parseFloat(data.PaymentDue || 0);
                    var creditLimit = parseFloat(data.CreditLimit || 0);
                    $('#paymentDue').val(paymentDue.toFixed(2));
                    $('#CreditBalance').val((data.TotalSaleAmount - data.TotalPaidAmount).toFixed(2));
                } catch (e) {
                    console.error('Error parsing response:', e);
                    $('#paymentDue').val('0.00');
                    $('#CreditBalance').val('0.00');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#paymentDue').val('0.00');
                $('#CreditBalance').val('0.00');
            }
        });
    } else {
        $('#paymentDue').val('0.00');
        $('#CreditBalance').val('0.00');
    }
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var paymentSelect = document.getElementById("paymentMethod");
        var chequeFields = document.getElementById("chequeFields");
        var chequeNumber = document.getElementById("chequeNumber");
        var chequeDate = document.getElementById("chequeDate");
        var bankName = document.getElementById("bankName");

        function toggleChequeFields() {
            if (paymentSelect.value === "Cheque") {
                chequeFields.style.display = "block";
                chequeNumber.required = true;
                chequeDate.required = true;
                bankName.required = true;
            } else {
                chequeFields.style.display = "none";
                chequeNumber.required = false;
                chequeDate.required = false;
                bankName.required = false;
            }
        }

        paymentSelect.addEventListener("change", toggleChequeFields);

        // Trigger on page load
        toggleChequeFields();
    });
</script>


<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
