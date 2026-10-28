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

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Chart Of Accounts</h4>

                        <ol class="breadcrumb p-0">
                            <li>
                                <a href="<?= base_url('admin') ?>">Dashboard</a>
                            </li>

                            <li class="active">
                                Chart Of Accounts
                            </li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Add Account Form -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">
                            Add New Account
                        </h4>

                        <form method="post" action="<?= base_url('admin/chart_of_accounts/save') ?>">

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Account Code</label>

                                        <input type="text"
                                               name="AccountCode"
                                               class="form-control"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Account Name</label>

                                        <input type="text"
                                               name="AccountName"
                                               class="form-control"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Account Type</label>

                                        <select name="AccountTypeID"
                                                class="form-control"
                                                required>

                                            <option value="">Select Type</option>

                                            <?php foreach($types as $type){ ?>

                                                <option value="<?= $type->AccountTypeID ?>">
                                                    <?= $type->AccountTypeName ?>
                                                </option>

                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Opening Balance</label>

                                        <input type="number"
                                               step="0.01"
                                               name="OpeningBalance"
                                               class="form-control"
                                               value="0.00">
                                    </div>
                                </div>

                                <div class="col-md-1">

                                    <div class="form-group">

                                        <label>&nbsp;</label>

                                        <button type="submit"
                                                class="btn btn-primary btn-block">

                                            Save

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <!-- Account List -->
            <div class="row">

                <div class="col-sm-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">
                            Account List
                        </h4>

                        <div class="table-responsive">

                            <table id="datatable"
                                   class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th>#</th>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                        <th>Opening Balance</th>
                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php $i = 1; ?>

                                    <?php foreach($accounts as $row){ ?>

                                        <tr>

                                            <td><?= $i++ ?></td>

                                            <td>
                                                <?= $row->AccountCode ?>
                                            </td>

                                            <td>
                                                <?= $row->AccountName ?>
                                            </td>

                                            <td>
                                                <?= $row->AccountTypeName ?>
                                            </td>

                                            <td style="text-align: right;">

                                                <?= number_format($row->OpeningBalance, 2) ?>

                                            </td>

                                            <td>

                                                <?php if($row->Status == 1){ ?>

                                                    <span class="badge badge-success">
                                                        Active
                                                    </span>

                                                <?php } else { ?>

                                                    <span class="badge badge-danger">
                                                        Inactive
                                                    </span>

                                                <?php } ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<!-- extra js start -->

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

    $(document).ready(function () {

        $('#datatable').DataTable({

            "order": [[0, "desc"]],
            "responsive": true,
            "autoWidth": false

        });

    });

</script>

<!-- extra js end -->

<?php $this->view('includes/footer_end.php') ?>