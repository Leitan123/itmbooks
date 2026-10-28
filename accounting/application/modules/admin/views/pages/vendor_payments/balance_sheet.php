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
                        <h4 class="page-title">Vendor Balance Steet</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/vendor_payments') ?>">Vendor Payments</a></li>
                            <li class="active">Payment List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

      
            <br />

       
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Vendor Balance Details</h4>

                        <div class="row">
                            <div class="table-responsive"> <!-- Responsive table wrapper -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vendor Name</th>
                                        <th>Total Payment Due</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        
                                            <?php $i = 1; foreach ($records as $record): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $record->VendorName; ?></td>
                                                <td><?= number_format(($record->TotalPurchaseAmount - $record->TotalPaidAmount), 2); ?></td> <!-- Correct calculation with 2 decimal places -->
                                            </tr>
                                            <?php endforeach; ?>
                                    

                                    </tbody>
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
