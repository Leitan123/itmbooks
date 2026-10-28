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
                        <h4 class="page-title">Location</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/location') ?>"> Location</a></li>
                            <li class="active"> Location List</li>
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

                    <!--
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-info m_b_10 pull-left fa fa-plus" href="<?= current_url() . "/create" ?>"> Create New </a>
                        </div>
                    </div>
                -->
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Location Details</h4>

                        <div class="row">
                            <div class="table-responsive"> <!-- Responsive table wrapper -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Shop Name</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($records as $k => $row): ?>
                                        <tr>
                                            <td><?= $k + 1 ?></td>
                                            <td><?= $row->ShopName ?></td>
                                            <td><?= $row->Address ?></td>
                                            
                                            <td class="text-center">
                                                <a href="<?= current_url() . "/edit/$row->LocationId" ?>" class="btn btn-warning fa fa-edit"> </a>
                                                <a href="<?= current_url() . "/delete/$row->LocationId" ?>" class="btn btn-danger fa fa-times delete-btn"> </a>
                                          
                                            </td>
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
                // Convert dates to timestamps for comparison
                var fromTimestamp = new Date(fromDate).getTime();
                var toTimestamp = new Date(toDate).getTime();

                // Apply filter to the datatable
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var date = new Date(data[1]).getTime(); // Assuming the date column is the second one
                    return (isNaN(fromTimestamp) && isNaN(toTimestamp)) ||
                        (isNaN(fromTimestamp) && date <= toTimestamp) ||
                        (fromTimestamp <= date && isNaN(toTimestamp)) ||
                        (fromTimestamp <= date && date <= toTimestamp);
                });

                table.draw();
                $.fn.dataTable.ext.search.pop();
            }
        });
    });
</script>

<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
