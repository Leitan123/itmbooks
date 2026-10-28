<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Customer Payments</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/customer_payments') ?>">Customer Payments</a></li>
                            <li class="active">Payment List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Date filter inputs -->
                <div class="col-lg-3">
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-lg-3">
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-lg-3 align-self-end">
                    <button id="filter" class="btn btn-success w-100">Filter</button>
                </div>
            </div>
            <br />

            <div class="col-sm-12">

                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Payment Details</h4>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Receipt No.</th>
                                        <th>Payment Date</th>
                                        <th>Company</th>
                                        <th>Paid Amount</th>
                                        <th>Payment Method</th>
                                        <th>Cheque</th>
                                        <th>User</th>
                                        <?php if (hasPermissions('customer_payment_edit') || hasPermissions('customer_payment_delete')): ?>
                                            <th>Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $row): ?>
                                        <tr>
                                            <td><?= isset($row->PaymentID) ? $row->PaymentID : 'N/A' ?></td>
                                            <td><?= isset($row->PaymentDate) ? date('Y-m-d', strtotime($row->PaymentDate)) : 'N/A' ?></td>
                                            <td><?= isset($row->CompanyName) ? $row->CompanyName : 'N/A' ?></td>

                                            <td style="text-align: right;"><?= number_format(isset($row->PaidAmount) ? $row->PaidAmount : 0, 2) ?></td>

                                            <td><?= isset($row->PaymentMethod) ? $row->PaymentMethod : 'N/A' ?></td>
                                            <td>
                                                <?= isset($row->BankName) ? $row->BankName : 'N/A' ?> |
                                                <?= isset($row->ChequeNumber) ? $row->ChequeNumber : 'N/A' ?> |
                                                <?= isset($row->ChequeDate) ? $row->ChequeDate : 'N/A' ?>
                                            </td>
                                            <td><?= !empty($row->UserName) ? $row->UserName : '-' ?></td>

                                            <?php if (hasPermissions('customer_payment_edit') || hasPermissions('customer_payment_delete')): ?>
                                                <td class="text-center">
                                                    <?php if (hasPermissions('customer_payment_edit')): ?>
                                                        <a href="<?= current_url() . "/print_receipt/$row->PaymentID" ?>" class="btn btn-success fa fa-print" target="_blank"></a>
                                                    <?php endif; ?>
                                                    <?php if (hasPermissions('customer_payment_delete')): ?>
                                                        <a href="<?= current_url() . "/delete/$row->PaymentID" ?>" class="btn btn-danger fa fa-times delete-btn"></a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total Received Amount:</strong></td>
                                        <td class="text-right" id="total-amount" style="font-weight: bold;">0.00</td>
                                        <td colspan="<?= (hasPermissions('customer_payment_edit') || hasPermissions('customer_payment_delete')) ? 4 : 3 ?>"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <!-- container -->
    </div>
    <!-- content -->
</div><!-- End content-page -->

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<!-- Required datatable js -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
    var table = $('#datatable').DataTable({
        "order": [[0, "desc"]],
        "responsive": true,
        "autoWidth": false
    });

    function calculateTotal() {
        var total = 0;
        table.rows({filter: 'applied'}).every(function () {
            var amount = parseFloat(this.data()[3].replace(/,/g, '')) || 0;
            total += amount;
        });
        $('#total-amount').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    $('#filter').on('click', function () {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();

        $.fn.dataTable.ext.search.push(function (settings, data) {
            var date = new Date(data[1]).getTime();
            var from = fromDate ? new Date(fromDate).getTime() : null;
            var to = toDate ? new Date(toDate).getTime() : null;
            if ((!from && !to) || (!from && date <= to) || (from <= date && !to) || (from <= date && date <= to)) return true;
            return false;
        });

        table.draw();
        $.fn.dataTable.ext.search.pop();
        calculateTotal();
    });

    calculateTotal();
});


</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
