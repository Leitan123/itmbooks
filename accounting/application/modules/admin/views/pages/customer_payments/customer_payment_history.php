<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Customer Payment History</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Payment History</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Customer Selection & Date Filter -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Company Name</label>
                        <select id="customer_select" class="form-control select2">
                            <option value="">-- Select Customer --</option>
                             <?php foreach ($customers as $c): ?>
                                <option value="<?= $c->CustomerId ?>"><?= htmlspecialchars(!empty($c->CompanyName) ? $c->CompanyName : $c->CustomerName) ?></option>
                             <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" id="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" id="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3" style="margin-top: 24px;">
                            <button id="filter_btn" class="btn btn-primary">Filter</button>
                            <button id="reset_btn" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>

                    <!-- Payment History Table -->
                    <div class="card-box">
                        <h4 class="header-title">Payment History</h4>
                        <table class="table table-bordered" id="paymentHistoryTable">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<style>
.paid-invoice {
    background-color: #d4edda; /* light green */
    color: #155724; 
}
.paid-seal {
    display: inline-block;
    padding: 3px 8px;
    background-color: #28a745; 
    color: #fff;
    font-weight: bold;
    border-radius: 4px;
    font-size: 12px;
    text-align: center;
}
</style>

<script>
$(document).ready(function() {

    function loadInvoices(customerId, fromDate = '', toDate = '') {
        if(!customerId) return;

        $.getJSON("<?= base_url('admin/Customer_payments/get_customer_invoices_all/') ?>" + customerId, {from: fromDate, to: toDate}, function(res) {
            let rows = "";
            res.forEach(function(inv) {
                let isPaid = parseFloat(inv.due) === 0;
                let rowClass = isPaid ? 'paid-invoice' : '';

                rows += `<tr class="${rowClass}">
                    <td>${inv.SaleID}</td>
                    <td>${inv.SaleDate.split(' ')[0]}</td>
                    <td>${inv.TotalSaleAmount}</td>
                    <td>${inv.PaidAmount}</td>
                    <td>${inv.due}</td>
                    <td>${isPaid ? '<span class="paid-seal">PAID</span>' : ''}</td>
                </tr>`;
            });
            $("#paymentHistoryTable tbody").html(rows);
        });
    }

    // On customer change
    $('#customer_select').change(function() {
        var customerId = $(this).val();
        $("#from_date").val('');
        $("#to_date").val('');
        $("#paymentHistoryTable tbody").empty();
        loadInvoices(customerId);
    });

    // Filter button
    $('#filter_btn').click(function() {
        var customerId = $('#customer_select').val();
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        loadInvoices(customerId, fromDate, toDate);
    });

    // Reset button
    $('#reset_btn').click(function() {
        var customerId = $('#customer_select').val();
        $("#from_date").val('');
        $("#to_date").val('');
        loadInvoices(customerId);
    });

});
</script>

<?php $this->view('includes/footer_end.php'); ?>
