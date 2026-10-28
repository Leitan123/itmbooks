<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Customer Payment</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/customer_payments') ?>">Customer Payments</a></li>
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select name="form[CustomerId]" id="customer_select" class="form-control select2">
                                        <option value="">-- Select Customer --</option>
                                        <?php foreach ($customers as $c): ?>
                                            <option value="<?= $c->CustomerId ?>">
                                                <?= htmlspecialchars($c->CompanyName) ?><?= !empty($c->DomainName) ? ' - ' . htmlspecialchars($c->DomainName) : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="CreditBalance" name="form[CreditBalance]" value="0">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <input type="date" name="form[PaymentDate]" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Total Payment Due</label>
                                    <input type="text" id="paymentDue" class="form-control" name="form[PaymentDue]" readonly value="0.00">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Total Paid Amount</label>
                                    <input type="text" id="paidAmount" class="form-control" name="form[PaidAmount]" readonly value="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Table -->
                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <h4>Outstanding Bills</h4>
                                <table class="table table-bordered" id="invoiceTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>PO No</th>
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
                        <!-- Payment Method -->
                        <div class="row">

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label>Payment Method</label>

                                    <select id="paymentMethod"
                                            name="form[PaymentMethod]"
                                            class="form-control">

                                        <option value="">Select Payment Method</option>

                                        <option value="Cash">Cash</option>

                                        <option value="Card">Card</option>

                                        <option value="Bank Transfer">
                                            Bank Transfer
                                        </option>

                                        <option value="Cheque">
                                            Cheque
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <!-- BANK -->

                            <div class="col-lg-4 bank-fields"
                                 style="display:none;">

                                <div class="form-group">

                                    <label>Bank</label>

                                    <select name="form[BankID]" id="bankSelect" class="form-control select2" style="width: 100%;">
                                        <option value="" data-balance="">Select Bank</option>

                                        <?php if(!empty($banks)){ ?>

                                            <?php foreach($banks as $bank){ ?>

                                                <option value="<?= $bank->BankID ?>" data-balance="<?= number_format($bank->CurrentBalance, 2) ?>">

                                                    <?= $bank->BankName ?> - <?= $bank->AccountNumber ?> (Rs. <?= number_format($bank->CurrentBalance, 2) ?>)

                                                </option>

                                            <?php } ?>

                                        <?php } ?>

                                    </select>
                                    <span id="bankBalanceDisplay" style="font-weight: bold; color: #1abc9c; margin-top: 5px; display: block; font-size: 13px;"></span>

                                </div>

                            </div>

                        </div>


                        <!-- CHEQUE DETAILS -->

                        <div class="row cheque-fields"
                             style="display:none;">

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label>Cheque Number</label>

                                    <input type="text"
                                           name="form[ChequeNumber]"
                                           class="form-control">

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label>Cheque Date</label>

                                    <input type="date"
                                           name="form[ChequeDate]"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                        <!-- Cheque Fields (Initially Hidden) -->
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

    // Reset amounts and table
    function resetAmounts() {
        $("#invoiceTable tbody").empty();
        $("#paymentDue").val("0.00");
        $("#paidAmount").val("0.00");
        $("#CreditBalance").val("0");
    }

    // Load invoices for selected customer
    $('#customer_select').change(function () {
        resetAmounts();
        var customerId = $(this).val();

        if (!customerId) return;

        $.getJSON("<?= base_url('admin/Customer_payments/get_customer_invoices/') ?>" + customerId, function (res) {
            let rows = "";
            let totalDue = 0;

            res.forEach(function (inv) {
                totalDue += parseFloat(inv.due);

                rows += `
                    <tr>
                    
                        <td>${inv.SaleDate.split(' ')[0]}</td>
                        <td>${inv.SaleID}</td>
                        <td>${inv.PONo}</td>
                        <td>${inv.RefNo}</td>
                        <td>${inv.TotalSaleAmount}</td>
                        <td>${inv.PaidAmount}</td>
                        <td>${inv.due}</td>
                        <td>
                            <input type="number" min="0" max="${inv.due}" class="form-control pay-now" 
                                name="form[invoices][${inv.SaleID}]" value="0" step="0.01">
                        </td>
                    </tr>`;
            });

            $("#paymentDue").val(totalDue.toFixed(2));
            $("#invoiceTable tbody").html(rows);
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

    // Show/hide cheque fields based on payment method
    $('#paymentMethod').change(function(){

        var method = $(this).val();
        $('#bankBalanceDisplay').html('');

        $('.bank-fields').hide();
        $('.cheque-fields').hide();

        if(method === 'Bank Transfer')
        {
            $('.bank-fields').show();
        }

        if(method === 'Cheque')
        {
            $('.bank-fields').show();
            $('.cheque-fields').show();
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

    // Auto-clear form after successful payment
    <?php if ($this->session->flashdata('notification') && $this->session->flashdata('notification')['alert'] == 'success'): ?>
        $("#customer_select").val("").trigger("change");
        resetAmounts();
        $("#paymentMethod").val("");
    <?php endif; ?>

});
</script>

<?php $this->view('includes/footer_end.php') ?>
