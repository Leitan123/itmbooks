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

                            Items & Services

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li class="active">

                                Item List

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <div class="col-sm-12">

                <?php if (hasPermissions('item_add')): ?>

                    <div class="row">

                        <div class="col-lg-12">

                            <a class="btn btn-primary m-b-20 pull-right"
                               href="<?= current_url() . "/create" ?>">

                                <i class="fa fa-plus"></i>

                                Create New

                            </a>

                        </div>

                    </div>

                <?php endif ?>


                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">

                        Item / Service Details

                    </h4>


                    <div class="row">

                        <div class="table-responsive">

                            <table id="datatable"
                                   class="table table-striped table-bordered">

                                <thead>

                                    <tr style="background:#4F81BD;color:#fff;">

                                        <th>#</th>

                                        <th>Type</th>

                                        <th>Category</th>

                                        <th>Brand</th>

                                        <th>Item / Service</th>

                                        <th>Purchase</th>

                                        <th>Selling</th>

                                        <?php if (hasPermissions('item_edit') || hasPermissions('item_delete')): ?>

                                            <th>Action</th>

                                        <?php endif ?>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($records as $k => $row): ?>

                                        <tr>

                                            <td>

                                                <?= $k + 1 ?>

                                            </td>


                                            <td>

                                                <?php if($row->ItemType == 'Service'){ ?>

                                                    <span class="label label-success">

                                                        Service

                                                    </span>

                                                <?php } else { ?>

                                                    <span class="label label-primary">

                                                        Product

                                                    </span>

                                                <?php } ?>

                                            </td>


                                            <td>

                                                <?= $row->CategoryName ?>

                                            </td>

                                            <td>

                                                <?= $row->BrandName ?>

                                            </td>

                                            <td>

                                                <strong>

                                                    <?= $row->ItemName ?>

                                                </strong>

                                                <br>

                                                <small>

                                                    <?= $row->ItemDescription ?>

                                                </small>

                                            </td>





                                            <td style="text-align:right;">

                                                <?= number_format($row->PurchasePrice,2) ?>

                                            </td>

                                            <td style="text-align:right;">

                                                <?= number_format($row->SellingPrice,2) ?>

                                            </td>


                                            <?php if (hasPermissions('item_edit') || hasPermissions('item_delete')): ?>

                                                <td class="text-center">

                                                    <?php if (hasPermissions('item_edit')): ?>

                                                        <a href="<?= current_url() . "/edit/$row->ItemID" ?>"
                                                           class="btn btn-warning btn-sm">

                                                            <i class="fa fa-edit"></i>

                                                        </a>

                                                    <?php endif ?>


                                                    <?php if (hasPermissions('item_delete')): ?>

                                                        <a href="<?= current_url() . "/delete/$row->ItemID" ?>"
                                                           class="btn btn-danger btn-sm delete-btn">

                                                            <i class="fa fa-trash"></i>

                                                        </a>

                                                    <?php endif ?>

                                                </td>

                                            <?php endif ?>

                                        </tr>

                                    <?php endforeach; ?>

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

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

$(document).ready(function () {

    $('#datatable').DataTable();

});

</script>

<?php $this->view('includes/footer_end.php') ?>