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
                        <h4 class="page-title">Customer Balance Steet</h4>
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

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Customer Balance Details</h4>

                        <!-- Modal for showing customer details -->
<div id="customerDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="customerName"></h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Receipt</th>
                            <th>Paid Amount</th>
                        </tr>
                    </thead>
                    <tbody id="customerDetailsBody">
                        <!-- Dynamically filled by JavaScript -->
                    </tbody>
                </table>
                <div class="text-right">
                    <p><strong>Total Amount:</strong> <span id="totalAmount"></span></p>
                    <p><strong>Paid Amount:</strong> <span id="paidAmount"></span></p>
                    <p><strong>Payment Due:</strong> <span id="paymentDue"></span></p>
                </div>
            </div>
        </div>
    </div>
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
        var table = $('#datatable').DataTable();

        

        // Date filter logic
        $('#filter').on('click', function () {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();

            if (fromDate && toDate) {
                var fromTimestamp = new Date(fromDate).getTime();
                var toTimestamp = new Date(toDate).getTime();

                // Apply date range filter to the displayed rows only
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var date = new Date(data[1]).getTime(); // Assuming the date column is the second one
                    return (isNaN(fromTimestamp) && isNaN(toTimestamp)) ||
                        (isNaN(fromTimestamp) && date <= toTimestamp) ||
                        (fromTimestamp <= date && isNaN(toTimestamp)) ||
                        (fromTimestamp <= date && date <= toTimestamp);
                });

                table.draw();
                $.fn.dataTable.ext.search.pop();
            } else {
                // Reset if no dates are selected
                table.draw();
            }
        });

        
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
