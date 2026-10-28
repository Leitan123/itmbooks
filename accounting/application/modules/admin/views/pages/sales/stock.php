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
                        <h4 class="page-title">Stock</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Sales') ?>">Sales</a></li>
                            <li class="active">Stock List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

         
                <div class="col-sm-12">
                
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Stock Details</h4>

                        <div class="row">
                            <div class="table-responsive"> <!-- Responsive table wrapper -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Item Name</th>
                                        <th>Description</th>
                                        <th>Total Purchases</th>
                                        <th>Total Sales</th>
                                        <th>Stock</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stockData as $stock): ?>
                                            <tr>
                                                <td><?= $stock->CategoryName ?></td>
                                                <td><?= $stock->ItemName ?></td>
                                                <td><?= $stock->ItemDescription ?></td>
                                                <td><?= $stock->TotalPurchases ?></td>
                                                <td><?= $stock->TotalSales ?></td>
                                                <td><?= $stock->Stock ?></td>
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
        $('#datatable').DataTable();
    });
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
