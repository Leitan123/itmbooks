<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Vendor Payment</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/vendor_payments') ?>">Vendor Payments</a></li>
                            <li class="active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Payment Details</h4>

                        <div class="row">
                            <!-- Vendor Select -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <select name="form[VendorID]" id="vendor_select" class="form-control select2">
                                        <option value="">-- Select Vendor --</option>
                                        <?php foreach ($vendors as $v): ?>
                                            <option value="<?= $v->VendorID ?>"><?= $v->VendorName ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="CreditBalance" name="form[CreditBalance]" value="0">

                            <!-- Manual Receipt Number -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Receipt No</label>
                                    <input type="text"
                                           name="form[ManualReceiptNo]"
                                           class="form-control"
                                           placeholder="Enter receipt number">
                                </div>
                            </div>


                            <!-- Payment Date -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <input type="date" name="form[PaymentDate]" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>

                            <!-- Total Payment Due -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Total Payment Due</label>
                                    <input type="text" id="paymentDue" class="form-control" name="form[PaymentDue]" readonly value="0.00">
                                </div>
                            </div>

                            <!-- Total Paid Amount -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Total Paid Amount</label>
                                    <input type="text" id="paidAmount" class="form-control" name="form[PaidAmount]" readonly value="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Pending Purchases Table -->
                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <h4>Outstanding Bills</h4>
                                <table class="table table-bordered" id="purchaseTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Purchase No</th>
                                            <th>PO</th>
                                            <th>Ref No</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Due Amount</th>
                                            <th>Pay Now</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select id="paymentMethod" name="form[PaymentMethod]" class="form-control">
                                        <option value="">Select Payment Method</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div class="row bank-transfer-fields" style="display:none; margin-bottom: 15px;">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Bank Account</label>
                                    <select name="form[BankID]" id="bankSelect" class="form-control select2" style="width: 100%;">
                                        <option value="" data-balance="">-- Select Bank Account --</option>
                                        <?php foreach ($banks as $bank): ?>
                                            <option value="<?= $bank->BankID ?>" data-balance="<?= number_format($bank->CurrentBalance, 2) ?>">
                                                <?= $bank->BankName ?> - <?= $bank->AccountNumber ?> (Rs. <?= number_format($bank->CurrentBalance, 2) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span id="bankBalanceDisplay" style="font-weight: bold; color: #1abc9c; margin-top: 5px; display: block; font-size: 13px;"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Cheque Fields -->
                        <div class="row cheque-fields" style="display:none;">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Cheque Number</label>
                                    <input type="text" name="form[ChequeNumber]" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Cheque Date</label>
                                    <input type="date" name="form[ChequeDate]" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" name="form[BankName]" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-success pull-right">Save</button>
                    <?= form_close() ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<script>
$(document).ready(function() {

    // Reset table and amounts
    function resetAmounts() {
        $("#purchaseTable tbody").empty();
        $("#paymentDue").val("0.00");
        $("#paidAmount").val("0.00");
        $("#CreditBalance").val("0");
    }

    // Load pending purchases for selected vendor
    $('#vendor_select').change(function () {
        resetAmounts();
        var vendorId = $(this).val();
        if (!vendorId) return;

        $.getJSON("<?= base_url('admin/Vendor_payments/get_vendor_purchases/') ?>" + vendorId, function (res) {
            let rows = "";
            let totalDue = 0;

            res.forEach(function (purchase) {
                totalDue += parseFloat(purchase.due);

                rows += `
                    <tr>
                        <td>${purchase.PurchaseDate.split(' ')[0]}</td>
                        <td>${purchase.PurchaseID}</td>
                        <td>${purchase.PONo}</td>
                        <td>${purchase.RefNo}</td>
                        <td>${purchase.TotalAmount}</td>
                        <td>${purchase.PaidAmount}</td>
                        <td>${purchase.due}</td>
                        <td>
                            <input type="number" min="0" max="${purchase.due}" class="form-control pay-now" 
                                name="form[purchases][${purchase.PurchaseID}]" value="0" step="0.01">
                        </td>
                    </tr>`;
            });

            $("#paymentDue").val(totalDue.toFixed(2));
            $("#purchaseTable tbody").html(rows);
        });
    });


    // Update total Paid Amount dynamically
    $(document).on("input", ".pay-now", function () {
        let sum = 0;
        $(".pay-now").each(function () {
            let val = parseFloat($(this).val());
            if (!isNaN(val)) sum += val;
        });
        $("#paidAmount").val(sum.toFixed(2));
    });

    // Show/hide cheque/bank fields
    $('#paymentMethod').change(function() {
        var method = $(this).val();
        $('#bankBalanceDisplay').html('');
        if (method === "Cheque") {
            $('.cheque-fields').show();
            $('.bank-transfer-fields').hide();
        } else if (method === "Bank Transfer") {
            $('.bank-transfer-fields').show();
            $('.cheque-fields').hide();
        } else {
            $('.cheque-fields').hide();
            $('.bank-transfer-fields').hide();
            $('input[name="form[ChequeNumber]"], input[name="form[ChequeDate]"], input[name="form[BankName]"]').val('');
            $('select[name="form[BankID]"]').val('').trigger('change');
        }
    });

    // Show selected bank balance dynamically
    $(document).on('change', '#bankSelect', function() {
        var balance = $(this).find(':selected').data('balance');
        if (balance) {
            $('#bankBalanceDisplay').html('<i class="fa fa-info-circle"></i> Current Balance: Rs. ' + balance);
        } else {
            $('#bankBalanceDisplay').html('');
        }
    });

});
</script>

<?php $this->view('includes/footer_end.php') ?>
