<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"
      type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet"
      type="text/css"/>
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
                        <h4 class="page-title">Brand</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Brand') ?>"> Brand</a></li>
                            <li class="active"> Brand List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <?php if (hasPermissions('brand_add')): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-info m_b_10 pull-left fa fa-plus "
                               href="<?= current_url() . "/create" ?>"> Create New </a>
                        </div>
                    </div>
                <?php endif ?>
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Brand Details</h4>

                    <div class="row">
                        <div class="table-responsive"> <!-- Responsive table wrapper -->
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th> #</th>
                                        <th> Brand</th>
                                        <th> Category</th>
                                        <th> Description</th>
                                        <?php if (hasPermissions('brand_edit') || hasPermissions('brand_delete')): ?>
                                            <th> Action</th>
                                        <?php endif ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $k => $row): ?>
                                        <tr>
                                            <td> <?= $k + 1 ?>  </td>
                                            <td> <?= $row->BrandName ?>  </td>
                                            <td> <?= $row->CategoryName ?>  </td>
                                            <td> <?= $row->BrandDescription ?>  </td>
                                            <?php if (hasPermissions('brand_edit') || hasPermissions('brand_delete')): ?>
                                                <td class="text-center">
                                                    <?php if (hasPermissions('brand_edit')): ?>
                                                        <a href="<?= current_url() . "/edit/$row->BrandID" ?>"
                                                           class="btn btn-warning fa fa-edit "> Edit </a>
                                                       <?php endif ?>
                                                       <?php if (hasPermissions('brand_delete')): ?>
                                                        <a href="<?= current_url() . "/delete/$row->BrandID" ?>"
                                                           class="btn btn-danger fa fa-times delete-btn "> Delete </a>
                                                    <?php endif ?>   
                                                </td>
                                            <?php endif ?>
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

