<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Vendor Payment History</h4>

                        <ol class="breadcrumb p-0">
                            <li>
                                <a href="<?= base_url('admin') ?>">
                                    Dashboard
                                </a>
                            </li>

                            <li class="active">
                                Vendor Payment History
                            </li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Vendor Selection -->
            <div class="row">
                <div class="col-sm-12">

                    <div class="form-group">
                        <label>Select Vendor</label>

                        <select id="vendor_select" class="form-control select2">
                            <option value="">-- Select Vendor --</option>

                            <?php foreach ($vendors as $v): ?>
                                <option value="<?= $v->VendorID ?>">
                                    <?= $v->VendorName ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date Filters -->
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>From Date</label>

                                <input type="date"
                                       id="from_date"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>To Date</label>

                                <input type="date"
                                       id="to_date"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3" style="margin-top:24px;">

                            <button id="filter_btn"
                                    class="btn btn-primary">
                                Filter
                            </button>

                            <button id="reset_btn"
                                    class="btn btn-secondary">
                                Reset
                            </button>

                        </div>

                    </div>

                    <!-- Payment Table -->
                    <div class="card-box">

                        <h4 class="header-title">
                            Vendor Payment History
                        </h4>

                        <div class="table-responsive">

                            <table class="table table-bordered"
                                   id="vendorPaymentTable">

                                <thead>
                                    <tr>
                                        <th>Purchase ID</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
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
</div>

<?php $this->view('includes/footer_start.php'); ?>

<style>

.paid-row {
    background-color: #d4edda;
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
}

</style>

<script>

$(document).ready(function () {

    function loadVendorPayments(vendorId, fromDate = '', toDate = '')
    {
        if (!vendorId)
            return;

        $.getJSON(
            "<?= base_url('admin/vendor_payments/get_vendor_purchases/') ?>" + vendorId,

            {
                from: fromDate,
                to: toDate
            },

            function (res)
            {
                let rows = "";

                $.each(res, function (i, row)
                {
                    let isPaid =
                        parseFloat(row.due) <= 0;

                    let rowClass =
                        isPaid ? 'paid-row' : '';

                    rows += `
                        <tr class="${rowClass}">

                            <td>${row.PurchaseID}</td>

                            <td>
                                ${row.PurchaseDate}
                            </td>

                            <td>
                                ${parseFloat(row.TotalAmount).toFixed(2)}
                            </td>

                            <td>
                                ${parseFloat(row.PaidAmount).toFixed(2)}
                            </td>

                            <td>
                                ${parseFloat(row.due).toFixed(2)}
                            </td>

                            <td>
                                ${
                                    isPaid
                                    ? '<span class="paid-seal">PAID</span>'
                                    : ''
                                }
                            </td>

                        </tr>
                    `;
                });

                $("#vendorPaymentTable tbody").html(rows);
            }
        );
    }

    // Vendor Change
    $('#vendor_select').change(function ()
    {
        let vendorId = $(this).val();

        $("#from_date").val('');
        $("#to_date").val('');

        $("#vendorPaymentTable tbody").empty();

        loadVendorPayments(vendorId);
    });

    // Filter
    $('#filter_btn').click(function ()
    {
        let vendorId =
            $('#vendor_select').val();

        let fromDate =
            $('#from_date').val();

        let toDate =
            $('#to_date').val();

        loadVendorPayments(
            vendorId,
            fromDate,
            toDate
        );
    });

    // Reset
    $('#reset_btn').click(function ()
    {
        let vendorId =
            $('#vendor_select').val();

        $("#from_date").val('');
        $("#to_date").val('');

        loadVendorPayments(vendorId);
    });

});

</script>

<?php $this->view('includes/footer_end.php'); ?>