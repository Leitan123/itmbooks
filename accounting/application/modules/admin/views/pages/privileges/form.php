<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Privilege</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/privileges') ?>"> Privileges</a></li>
                            <li class="active"> <?= empty($obj->PrivilegeId) ? "Create" : "Edit" ?> </li>
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
                        <h4 class="header-title m-t-0 m-b-30">Privilege Information</h4>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name">Privilege Name</label>
                                                <input required type="text" id="Name" name="form[Name]"
                                                       value="<?= set_value('form[Name]', $obj->Name) ?>"
                                                       class="form-control">
                                                <?= form_error('form[Name]') ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea id="description" name="form[Description]" class="form-control" rows="1"
                                                          placeholder="Brief description of what this privilege allows"><?= isset($obj->Description) ? htmlspecialchars($obj->Description) : '' ?></textarea>
                                                <?= form_error('form[Description]') ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="createdDate">Created Date</label>
                                                <input type="text" id="CreatedDate" name="form[CreatedDate]" 
                                                    class="form-control" readonly
                                                    value="<?= isset($obj->CreatedDate) ? htmlspecialchars($obj->CreatedDate) : date('Y-m-d H:i:s') ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="isActive">Status</label>
                                                <select id="isActive" name="form[IsActive]" class="form-control">
                                                    <option value="1" <?= isset($obj->IsActive) && $obj->IsActive == 1 ? 'selected' : '' ?>>Active</option>
                                                    <option value="0" <?= isset($obj->IsActive) && $obj->IsActive == 0 ? 'selected' : '' ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Save Privilege</button>
                        <a href="<?= base_url('admin/privileges') ?>" class="btn btn-default">Cancel</a>
                    </div>

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
<script>
    $(document).ready(function() {
        // Initialize any privilege-specific scripts here
    });
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>