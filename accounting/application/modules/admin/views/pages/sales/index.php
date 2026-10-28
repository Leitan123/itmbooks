<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" />
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" />
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" />
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Sales</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/sales') ?>">Sales</a></li>
                            <li class="active">Sales List</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="row mb-3">
                <div class="col-lg-3">
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-lg-3">
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-lg-3">
                    <button id="filter" class="btn btn-success w-100">Filter</button>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title m-b-30">Sales Details</h4>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Inv. No</th>
                                    <th>Sale Date</th>
                                    <th>Company</th>
                                    <th class="text-right">Total Amount</th>
                                    <th>User</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($records as $row): ?>
                                    <tr>
                                        <td><?= isset($row->SaleID) ? $row->SaleID : 'N/A' ?></td>

                                        <td>
                                            <?= isset($row->SaleDate)
                                                ? date('Y-m-d', strtotime($row->SaleDate))
                                                : 'N/A' ?>
                                        </td>

                                        <td><?= isset($row->CompanyName) ? $row->CompanyName : 'N/A' ?></td>

                                        <td class="text-right">
                                            <?= number_format(
                                                isset($row->TotalSaleAmount) ? $row->TotalSaleAmount : 0,
                                                2
                                            ) ?>
                                        </td>

                                        <td><?= isset($row->UserName) ? $row->UserName : 'N/A' ?></td>

                                        <td class="text-center">
                                            <?php if (hasPermissions('sales_edit')): ?>
                                                <a href="<?= base_url('admin/sales/edit/'.$row->SaleID) ?>"
                                                   class="btn btn-warning btn-sm fa fa-edit"></a>

                                                <a href="<?= base_url('admin/sales/delete/'.$row->SaleID) ?>"
                                                   class="btn btn-danger btn-sm fa fa-times delete-btn"></a>
                                            <?php endif; ?>

                                            <a href="<?= base_url('admin/sales/print_invoice/'.$row->SaleID) ?>"
                                               class="btn btn-success btn-sm fa fa-print"
                                               target="_blank"></a>



                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total Sales:</strong></td>
                                    <td class="text-right" id="total-amount"><strong>0.00</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<!-- extra js start -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>
$(document).ready(function () {

    var table = $('#datatable').DataTable({
        order: [[0, 'desc']]
    });

    function calculateTotal() {
        var total = 0;

        table.rows({ filter: 'applied' }).every(function () {
            var amount = parseFloat(this.data()[3].replace(/,/g, '')) || 0;
            total += amount;
        });

        $('#total-amount').text(
            total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );
    }

    $('#filter').on('click', function () {
        var from = $('#from_date').val();
        var to = $('#to_date').val();

        $.fn.dataTable.ext.search.push(function (settings, data) {
            var rowDate = new Date(data[1]).getTime();
            var fromDate = from ? new Date(from).getTime() : null;
            var toDate = to ? new Date(to).getTime() : null;

            if ((!fromDate || rowDate >= fromDate) &&
                (!toDate || rowDate <= toDate)) {
                return true;
            }
            return false;
        });

        table.draw();
        $.fn.dataTable.ext.search.pop();
        calculateTotal();
    });

    calculateTotal();
});
</script>
<!-- extra js end -->

<?php $this->view('includes/footer_end.php'); ?>
