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
                        <h4 class="page-title">Permission</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li> 
                            <li class="active"> Permission List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

    
                <div class="col-sm-12">
                    <?php if (hasPermissions('permissions_add')): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-info m_b_10 pull-left fa fa-plus "
                               href="<?= current_url() . "/add" ?>"> Create New </a>
                        </div>
                    </div>
                     <?php endif ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Permission Details</h4>

                        <div class="row">
                            <div class="table-responsive"> <!-- Responsive table wrapper -->
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                         <th>ID</th>
										<th>Permission</th>
										<th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach ($records as $row): ?>
            <tr>
              <td width="60"><?php echo $row->id ?></td>
              <td>
                <?php echo $row->title ?>
              </td>
              <td>
                <?php if (hasPermissions('permissions_edit')): ?>
				  <a href="<?= current_url() . "/edit/$row->id" ?>"
                                                   class="btn btn-warning fa fa-edit "> Edit </a>
                                                <!--<a href="<?= current_url() . "/delete/$row->id" ?>"-->
                                                <!--   class="btn btn-danger fa fa-times delete-btn "> Delete </a>-->
												   <?php endif ?>
              </td>
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

