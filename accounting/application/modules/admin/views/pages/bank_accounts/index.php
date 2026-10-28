<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>

<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>

<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>"
      rel="stylesheet"
      type="text/css"/>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>


<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- PAGE TITLE -->
            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            Bank Accounts

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li class="active">

                                Bank Accounts

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <!-- CARD -->
            <div class="card-box">

                <div class="row">

                    <div class="col-md-6">

                        <h4 class="header-title m-t-0">

                            Bank Account List

                        </h4>

                    </div>

                    <div class="col-md-6 text-right">

                        <a href="<?= base_url('admin/Bank_accounts/create') ?>"
                           class="btn btn-primary">

                            <i class="fa fa-plus"></i>

                            Add Bank

                        </a>

                    </div>

                </div>

                <br>


                <!-- TABLE -->
                <table id="datatable"
                       class="table table-bordered table-striped">

                    <thead>

                        <tr style="background:#4F81BD;
                                   color:#fff;">

                            <th>Bank</th>

                            <th>Account Name</th>

                            <th>Account Number</th>

                            <th>Branch</th>

                            <th>Opening Balance</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach($records as $row){ ?>

                        <tr>

                            <td>

                                <?= $row->BankName ?>

                            </td>

                            <td>

                                <?= $row->AccountName ?>

                            </td>

                            <td>

                                <?= $row->AccountNumber ?>

                            </td>

                            <td>

                                <?= $row->Branch ?>

                            </td>

                            <td>

                                <?= number_format($row->OpeningBalance,2) ?>

                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<?php $this->view('includes/footer_start.php') ?>

<!-- DATATABLE -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

$(document).ready(function () {

    $('#datatable').DataTable();

});

</script>

<?php $this->view('includes/footer_end.php') ?>