<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        min-width: 1000px;
    }
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    .btn-sm {
        padding: 4px 8px;
        font-size: 11px;
    }
</style>
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Customers</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/customer') ?>">Customer</a></li>
                            <li class="active">Customer List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Customer Table -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">
                            <i class="fa fa-users"></i> Customer Details
                            <a href="<?= base_url('admin/customer/create') ?>" class="btn btn-info pull-right btn-sm">
                                <i class="fa fa-plus"></i> Add New Customer
                            </a>
                        </h4>

                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="90">Reg Date</th>
                                        <th width="100">Customer No</th>
                                        <th>Customer Name</th>
                                        <th>Company Name</th>
                                        <th>Mobile</th>
                                        <th>WhatsApp</th>
                                        <th>Group</th>
                                        <th class="text-right">Credit Limit</th>
                                        <th width="80" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $k => $row): ?>
                                    <tr>
                                         <td class="text-center"><?= $k + 1 ?></td>
                                         <td class="text-center"><?= (!empty($row->RegDate) && $row->RegDate != '0000-00-00') ? date('d-m-Y', strtotime($row->RegDate)) : '-' ?></td>
                                         <td><?= htmlspecialchars($row->CustomerNo ?: '-') ?></td>
                                         <td><?= htmlspecialchars($row->CustomerName ?: '-') ?></td>
                                         <td><?= htmlspecialchars($row->CompanyName ?: '-') ?></td>
                                         <td><?= htmlspecialchars($row->MobileNo ?: '-') ?></td>
                                         <td><?= htmlspecialchars($row->WhatsAppNo ?: '-') ?></td>
                                         <td><?= htmlspecialchars($row->CustomerGroup ?: '-') ?></td>
                                         <td class="text-right"><?= number_format($row->CreditLimit ?: 0, 2) ?> LKR</td>
                                         <td class="text-center">
                                              <a href="<?= base_url('admin/customer/edit/' . $row->CustomerId) ?>" class="btn btn-warning btn-xs" style="padding: 2px 5px; font-size: 10px; line-height: 1;" title="Edit">
                                                  <i class="fa fa-edit"></i>
                                              </a>
                                              <a href="<?= base_url('admin/customer/delete/' . $row->CustomerId) ?>" class="btn btn-danger btn-xs" style="padding: 2px 5px; font-size: 10px; line-height: 1;" title="Delete" onclick="return confirm('Are you sure?')">
                                                  <i class="fa fa-trash"></i>
                                              </a>
                                         </td>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable({
            "order": [[0, "asc"]],
            "pageLength": 10,
            "responsive": true,
            "language": {
                "emptyTable": "No customers found",
                "info": "Showing _START_ to _END_ of _TOTAL_ customers",
                "infoEmpty": "Showing 0 to 0 of 0 customers",
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>