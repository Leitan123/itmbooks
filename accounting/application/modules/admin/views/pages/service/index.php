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
                        <h4 class="page-title">Service</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/reload') ?>"> Service</a></li>
                            <li class="active"> Service List</li>
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
                        <h4 class="header-title m-t-0 m-b-30">Service Details</h4>

                     
                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
                                <div class="table-responsive"> 
                                <!-- Table for displaying reload details -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Inquiry</th>
                                            <th>Job Status</th>
                                            <th>Job Details</th>
                                            <th>Technician</th>
                                            <th>Created At</th>
                                            <th>Service Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                      
                                        foreach ($records as $k => $row): 
                                         
                                        ?>
                                            <tr>
                                                <td><?= $row->ServiceID ?></td>
                                                <td><?= htmlspecialchars($row->CustomerName); ?></td>
                                                <td><?= htmlspecialchars($row->Inquiry); ?></td>
                                                <td><?= htmlspecialchars($row->JobStatus); ?></td>
                                                <td><?= htmlspecialchars($row->JobDetails); ?></td>
                                                <td><?= htmlspecialchars($row->Technician); ?></td>
                                                <td><?= htmlspecialchars($row->CreatedAt); ?></td>
                                                <td><?= htmlspecialchars($row->ServiceDate); ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url("admin/service/edit/{$row->ServiceID}") ?>" class="btn btn-warning fa fa-edit" title="Edit"></a>
                                                    <a href="<?= base_url("admin/service/delete/{$row->ServiceID}") ?>" class="btn btn-danger fa fa-times delete-btn" title="Delete"></a>
                                            
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



<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
