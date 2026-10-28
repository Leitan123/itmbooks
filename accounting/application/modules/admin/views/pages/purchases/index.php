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
                        <h4 class="page-title">Purchases</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Purchases') ?>">Purchases</a></li>
                            <li class="active">Purchase List</li>
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
                <div class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-info m_b_10 pull-left fa fa-plus" href="<?= current_url() . "/create" ?>"> Create New </a>
                    </div>
                </div>
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Purchase Details</h4>

                    <div class="row">
                        <div class="table-responsive"> <!-- Responsive table wrapper -->
                            <table id="datatable" class="table table-striped table-bordered">


                                <thead>
                                    <tr>

                                        <th>P.O No.</th>
                                        <th>Purchase Date</th>
                                        <th>M. PO</th>
                                        <th>Ref No.</th>
                                        <th>Vendor Name</th>
                                        <th>Total Amount</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $k => $row): ?>
                                        <tr>

                                            <td><?= isset($row->PurchaseID) ? $row->PurchaseID : 'N/A' ?></td>
                                            <td><?= date('Y-m-d', strtotime($row->PurchaseDate)) ?></td>
                                            <td><?= isset($row->PONo) ? $row->PONo : 'N/A' ?></td>
                                            <td><?= isset($row->RefNo) ? $row->RefNo : 'N/A' ?></td>
                                            <td><?= isset($row->VendorName) ? $row->VendorName : 'N/A' ?></td>
                                            <td style="text-align:right;"><?= number_format(isset($row->TotalPurchaseAmount) ? $row->TotalPurchaseAmount : 0, 2) ?></td>
                                            <td><?= isset($row->UserName) ? $row->UserName : 'N/A' ?></td>


                                            <td class="text-center">
                                                <?php if (hasPermissions('purchases_edit')): ?>
                                                    <a href="<?= current_url() . "/edit/$row->PurchaseID" ?>" class="btn btn-warning fa fa-edit"></a>
                                                <?php endif ?>
                                                <?php if (hasPermissions('purchases_delete')): ?>
                                                    <a href="<?= current_url() . "/delete/$row->PurchaseID" ?>" class="btn btn-danger fa fa-times delete-btn"></a>
                                                <?php endif ?>
                                                <a href="<?= current_url() . "/print_purchase/$row->PurchaseID" ?>" class="btn btn-success fa fa-print" target="_blank"></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><strong>Total Purchase Amount:</strong></td>
                                        <td colspan="1" id="total-amount" class="text-right" style="text-align:right; font-weight: bold;">0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>

                                </tbody>


                            </table>
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
            // Initialize DataTables
            var table = $('#datatable').DataTable({
                "order": [[0, "desc"]] // Sort by first column in descending order
            });

            // Function to calculate the total amount
            function calculateTotal() {
                var total = 0;

                // Iterate through visible rows to sum the PaidAmount column
                table.rows({filter: 'applied'}).every(function (rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var amount = parseFloat(data[5].replace(/,/g, '')) || 0; // Assuming PaidAmount is in the 4th column
                    total += amount;
                });

                // Update the total amount in the HTML
                $('#total-amount').text(total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            // Filter logic
            $('#filter').on('click', function () {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();

                if (fromDate && toDate) {
                    var fromTimestamp = new Date(fromDate).getTime();
                    var toTimestamp = new Date(toDate).getTime();

                    // Apply date range filter
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        var date = new Date(data[1]).getTime(); // Assuming PaymentDate is in the 2nd column
                        return (isNaN(fromTimestamp) && isNaN(toTimestamp)) ||
                                (isNaN(fromTimestamp) && date <= toTimestamp) ||
                                (fromTimestamp <= date && isNaN(toTimestamp)) ||
                                (fromTimestamp <= date && date <= toTimestamp);
                    });

                    table.draw();
                    $.fn.dataTable.ext.search.pop(); // Remove custom filter
                } else {
                    // Reset filtering
                    table.draw();
                }

                // Recalculate total after filter
                calculateTotal();
            });

            // Initial total calculation
            calculateTotal();
        });

    </script>

    <!-- extra js end -->
    <?php $this->view('includes/footer_end.php') ?>
