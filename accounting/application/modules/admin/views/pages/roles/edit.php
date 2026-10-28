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
                        <h4 class="page-title">Role Permission</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active"><?= empty($obj->id) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <?php $this->view('includes/notification.php'); ?>
            <?php echo form_open(base_url() . 'admin/roles/update/' . $role->id, ['class' => 'form-validate']); ?>
            <div class="row">



                <div class="col-sm-12">

                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Role Permission Information</h4>

                        <div class="form-group">
                            <label for="formClient-Name">Role Name</label>
                            <input type="text" class="form-control" name="name" id="formClient-Name" required placeholder="Role Name" autofocus value="<?php echo $role->title ?>"  />
                            <?php echo form_error('name'); ?>
                        </div>


                        <div class="row">


                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Permission Name</th>
                                        <th width="50" class="text-center"><input type="checkbox" class="check-select-all-p"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if (!empty($permissions = $this->permissions_model->get())): ?>
                                            <?php foreach ($permissions as $row): ?>
                                                <td><?php echo ucfirst($row->title) ?></td>
                                                <?php
                                                $isChecked = in_array($row->code, $role_permissions) ? 'checked' : '';
                                                ?>
                                                <td width="50" class="text-center"><input type="checkbox" class="check-select-p" name="permission[]" value="<?php echo $row->code ?>" <?php echo $isChecked ?>></td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No Permissions Found</td>
                                        </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <button class="btn btn-success pull-right">Save</button>


                </div>

                <!-- end row -->
            </div>
            <?= form_close() ?>
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

<script>
    $(document).ready(function () {
        $('.form-validate').validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('.check-select-all-p').on('change', function () {

            $('.check-select-p').attr('checked', $(this).is(':checked'));

        })

        $('.table-DT').DataTable({
            "ordering": false,
        });
    })

</script>



<script>
    //Initialize Select2 Elements
    $('.select2').select2()
</script>
