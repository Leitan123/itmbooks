<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Warehouses</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/warehouse') ?>">Warehouses</a></li>
                            <li class="active">Warehouse List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-info m-b-10 fa fa-plus" href="<?= base_url('admin/warehouse/create') ?>"> Add New Warehouse</a>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Warehouse Details</h4>

                        <div class="row">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Warehouse Name</th>
                                        <th>Location / Address</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($records as $k => $row): ?>
                                        <tr>
                                            <td><?= $k + 1 ?></td>
                                            <td><strong><?= htmlspecialchars($row->WarehouseName) ?></strong></td>
                                            <td><?= !empty($row->Location) ? htmlspecialchars($row->Location) : '-' ?></td>
                                            <td><?= !empty($row->ContactPerson) ? htmlspecialchars($row->ContactPerson) : '-' ?></td>
                                            <td><?= !empty($row->Phone) ? htmlspecialchars($row->Phone) : '-' ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url("admin/warehouse/edit/$row->WarehouseID") ?>" class="btn btn-warning fa fa-edit"></a>
                                                <a href="<?= base_url("admin/warehouse/delete/$row->WarehouseID") ?>" class="btn btn-danger fa fa-times delete-btn" onclick="return confirm('Are you sure you want to delete this warehouse?');"></a>
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

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>
<!-- extra js start -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.responsive.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php'); ?>
