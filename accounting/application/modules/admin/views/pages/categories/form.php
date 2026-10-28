<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<?php ?>

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Category</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/categories') ?>">Categories</a></li>
                            <li class="active"><?= empty($obj->CategoryID) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Category Information</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="CategoryName">Category Name</label>
                                    <input type="text" id="CategoryName" name="form[CategoryName]" 
                                           value="<?= set_value('form[CategoryName]', $obj->CategoryName) ?>" 
                                           class="form-control">
                                    <?= form_error('form[CategoryName]') ?>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="CategoryDescription">Description</label>
                                    <textarea id="CategoryDescription" name="form[CategoryDescription]" 
                                              rows="5" class="form-control"><?= set_value('form[CategoryDescription]', $obj->CategoryDescription) ?></textarea>
                                    <?= form_error('form[CategoryDescription]') ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button class="btn btn-success pull-right">Save</button>

                    <?= form_close() ?>
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container -->

</div>
<!-- content -->

</div>
<!-- End content-page -->

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
