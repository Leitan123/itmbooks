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
                        <h4 class="page-title">Users</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Create</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <form action="<?=base_url() .'admin/users/save'?>" method="post">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">User Information</h4>

                        <div class="row">

                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="Name">Name</label>
                                    <input type="text" id="Name" name="Name" 
                                           
                                           class="form-control">
                                    <?= form_error('form[Name]') ?>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                        <label for="code">User Name</label>
                                    <input type="text" id="Username" name="Username" 
                                           
                                           class="form-control">
                                    <?= form_error('form[Username]') ?>
                                </div>
                            </div>

                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="Password">Password</label>
                                    <input type="password" id="Password" name="Password" 
                                           
                                           class="form-control">
                                    <?= form_error('form[Password]') ?>
                                </div>
                            </div>
                            
                               <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select id="role" name="role" class="form-control">
                                        <option value="">Select Role</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role->id  ?>" <?= ($obj->role  == $role->id ) ? 'selected' : '' ?>>
                                                <?= $role->title ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
