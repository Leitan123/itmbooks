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
                        <h4 class="page-title">Location</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/location') ?>">Location</a></li>
                            <li class="active"><?= empty($obj->LocationId) ? "Create" : "Edit" ?></li>
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
                        <h4 class="header-title m-t-0 m-b-30">General Information</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="shopName">Shop Name</label>
                                    <input required type="text" id="ShopName" name="form[ShopName]" class="form-control" 
                                        value="<?= isset($obj->ShopName) ? htmlspecialchars($obj->ShopName) : '' ?>">
                                    <?= form_error('form[ShopName]') ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="Address" name="form[Address]" class="form-control" rows="3"><?= isset($obj->Address) ? htmlspecialchars($obj->Address) : '' ?></textarea>
                                    <?= form_error('form[Address]') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success pull-right">Save</button>
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
