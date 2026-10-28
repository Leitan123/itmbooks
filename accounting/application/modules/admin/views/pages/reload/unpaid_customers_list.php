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
                        <h4 class="page-title">Reload</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/reload') ?>"> Reload</a></li>
                            <li class="active"> Reload List</li>
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
                        <h4 class="header-title m-t-0 m-b-30">Reload Details</h4>

                     
                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
                                <div class="table-responsive"> 
                                <!-- Table for displaying reload details -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Mobile</th>
                                            <th>VC</th>
                                            <th>Provider</th>
                                            <th>Package</th>
                                            <th>Amo.</th>
                                            <th>Add</th>
                                            <th>Amo.</th>
                                            <th>Extra</th>
                                            <th>Amo.</th>
                                            <th>Total</th>
                                            <th>Expiry</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalSum = 0;  // Initialize total sum variable
                                        foreach ($records as $k => $row): 
                                            // Calculate the total amount for this row
                                            $totalAmount = $row->Package + $row->AddOn + $row->ExtraCharge;
                                            $totalSum += $totalAmount;  // Sum total amounts
                                        ?>
                                            <tr>
                                                <td><?= $row->ReloadId ?></td>
                                                <td><?= $row->CreatedDate ?></td>
                                                <td><?= $row->MobileNo ?></td>
                                                <td><?= $row->Vc ?></td>
                                                <td><?= $row->Provider ?></td>
                                                <td><?= $row->PackageD ?></td>
                                                <td><?= number_format($row->Package, 2) ?></td> 
                                                <td><?= $row->AddOnd ?></td>
                                                <td><?= number_format($row->AddOn, 2) ?></td>
                                                <td><?= $row->ExtraCharged ?></td>
                                                <td><?= number_format($row->ExtraCharge, 2) ?></td><td><?= number_format($totalAmount, 2) ?></td>
                                                <td><?= $row->ExpiryON ?></td>
                                                <td><?= $row->PaymentMethod ?></td>
                                                <td><?= $row->PayStatus ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url("admin/reload/edit/{$row->ReloadId}") ?>" class="btn btn-warning fa fa-edit" title="Edit"></a>
                                                    <a href="<?= base_url("admin/reload/delete/{$row->ReloadId}") ?>" class="btn btn-danger fa fa-times delete-btn" title="Delete"></a>
                                                    <button class="btn btn-primary fa fa-print" title="Print Receipt" onclick="printReceipt(<?= $row->ReloadId ?>)"></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="11" class="text-right"><strong>Total Sum</strong></td>
                                            <td class="text-right"><strong><?= number_format($totalSum, 2)  ?></strong></td> 
                                            <td></td>
                                            <td></td>
                                            <td></td><td></td>

                                                <!-- Display total sum of Total Amount -->
                                            <!-- Empty column for Expiry On and Actions -->
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

            "order": [[1, "desc"]] // Order by the second column (Date) in descending order
        });

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

<script>
function printReceipt(reloadId) {
    var url = "<?= base_url('admin/reload/print/') ?>" + reloadId;
    // Open the print view in a new tab or window
    window.open(url, '_blank');
}
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
