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

            <br />

    
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Customer Balance Details</h4>

                        <div class="row">
                            <div class="table-responsive"> <!-- Responsive table wrapper -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>Customer Name</th>
                                         <th>Company Name</th>
                                         <th>Domain</th>
                                         <th>Service</th>
                                         <th>No. of Days Pending</th>
                                         <th>Total Payment Due</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                         
                                             <?php $i = 1; foreach ($records as $record): 
                                                 $due = $record->TotalSaleAmount - $record->TotalPaidAmount;
                                                 if (round($due, 2) <= 0) {
                                                     continue;
                                                 }
                                             ?>
                                             <tr>
                                                 <td><?= $i++; ?></td>
                                                 <td><?= $record->CustomerName; ?></td>
                                                 <td><?= $record->CompanyName; ?></td>
                                                 <td><?= !empty($record->DomainName) ? htmlspecialchars($record->DomainName) : '-'; ?></td>
                                                 <td><?= !empty($record->ServiceNames) ? htmlspecialchars($record->ServiceNames) : '-'; ?></td>
                                                 <td><?= isset($record->DaysPending) ? $record->DaysPending : 0; ?></td>
                                                 <td style="text-align: right;"><?= number_format($due, 2); ?></td> <!-- Correct calculation with 2 decimal places -->
                                             </tr>
                                             <?php endforeach; ?>
                                     

                                     </tbody>
                                     <tfoot>
                                         <tr>
                                             <th colspan="6" style="text-align: right;">Total Amount:</th>
                                             <th style="text-align: right;" id="total-due-amount">0.00</th>
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
            "responsive": true,
            "autoWidth": false
        });

        function calculateTotal() {
            var total = 0;
            table.rows({filter: 'applied'}).every(function () {
                var amountStr = $('<div>').html(this.data()[6]).text().replace(/,/g, '');
                var amount = parseFloat(amountStr) || 0;
                total += amount;
            });
            $('#total-due-amount').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }

        // Recalculate total when table draws
        table.on('draw', function () {
            calculateTotal();
        });

        // Initial calculation
        calculateTotal();
    });
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
