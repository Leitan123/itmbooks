<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Warehouse</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/warehouse') ?>">Warehouse</a></li>
                            <li class="active"><?= empty($obj->WarehouseID) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Warehouse Information</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="WarehouseName">Warehouse Name <span class="text-danger">*</span></label>
                                    <input required type="text" id="WarehouseName" name="form[WarehouseName]" class="form-control" 
                                        value="<?= isset($obj->WarehouseName) ? htmlspecialchars($obj->WarehouseName) : '' ?>">
                                    <?= form_error('form[WarehouseName]') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="Location">Location / Address</label>
                                    <input type="text" id="Location" name="form[Location]" class="form-control" 
                                        value="<?= isset($obj->Location) ? htmlspecialchars($obj->Location) : '' ?>">
                                    <?= form_error('form[Location]') ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ContactPerson">Contact Person</label>
                                    <input type="text" id="ContactPerson" name="form[ContactPerson]" class="form-control" 
                                        value="<?= isset($obj->ContactPerson) ? htmlspecialchars($obj->ContactPerson) : '' ?>">
                                    <?= form_error('form[ContactPerson]') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="Phone">Phone Number</label>
                                    <input type="text" id="Phone" name="form[Phone]" class="form-control" 
                                        value="<?= isset($obj->Phone) ? htmlspecialchars($obj->Phone) : '' ?>">
                                    <?= form_error('form[Phone]') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success pull-right">Save Warehouse</button>
                    <a href="<?= base_url('admin/warehouse') ?>" class="btn btn-secondary pull-right m-r-10">Cancel</a>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
