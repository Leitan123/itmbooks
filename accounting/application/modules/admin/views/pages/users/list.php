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
                        <h4 class="page-title">User</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li> 
                            <li class="active"> User List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <?php if (hasPermissions('users_add')): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-info m_b_10 pull-left fa fa-plus "
                               href="<?= current_url() . "/add" ?>"> Create New </a>
                        </div>
                    </div>
                <?php endif ?>
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">User Details</h4>

                    <div class="row">
                        <div class="table-responsive"> <!-- Responsive table wrapper -->
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>User Role</th>
                                        <th>Last Login</th> 
                                        <?php if (hasPermissions('users_edit') || hasPermissions('users_delete')): ?>
                                            <th> Action</th>
                                        <?php endif ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $row): ?>

                                        <tr>
                                            <td  ><?php echo $row->UserId ?></td> 
                                            <td>
                                                <?php echo $row->Name ?>
                                            </td> 
                                            <td><?php echo ucfirst($this->roles_model->getById($row->role)->title) ?></td>
                                            <td><?php echo ($row->last_login != '0000-00-00 00:00:00') ? date("l, F j, Y h:i A", strtotime($row->last_login)) : 'No Record' ?></td>
                                            <?php if (hasPermissions('users_edit') || hasPermissions('users_delete')): ?>
                                                <td>
                                                    <?php if (hasPermissions('users_edit')): ?>
                                                        <a href="<?php echo current_url() . '/edit/' . $row->UserId ?>" class="btn btn-warning fa fa-edit" title="Edit User" data-toggle="tooltip"> </a>
                                                    <?php endif ?>  
                                                    <?php if (hasPermissions('users_delete')): ?>
                                                        <?php if ($row->UserId != 1 && logged('UserId') != $row->UserId): ?>
                                                            <a href="<?php echo current_url() . '/delete/' . $row->UserId ?> " class="btn btn-danger fa fa-times delete-btn"   title="Delete user" data-toggle="tooltip"> </a>
                                                        <?php else: ?>
                                                            <a href="#"   title="User Can not Delete" data-toggle="tooltip" class="btn btn-danger fa fa-times delete-icon disabled-icon" disabled> </a>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </td>
                                            <?php endif ?>
                                        </tr>

                                    <?php endforeach ?> 
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

