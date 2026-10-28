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

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Receivables Summary</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Summary of Receivables</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row" style="margin-bottom:15px;">
                <div class="col-md-3">
                    <label>Month</label>
                    <select id="filter_month" class="form-control">
                        <option value="">-- All Months --</option>
                        <?php for($m=1;$m<=12;$m++): ?>
                            <option value="<?= $m ?>"><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Year</label>
                    <select id="filter_year" class="form-control">
                        <option value="">-- All Years --</option>
                        <?php 
                        $currentYear = date('Y');
                        for($y=$currentYear-5; $y<=$currentYear; $y++): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3" style="margin-top:25px;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="only_due"> Show Only Customers with Due
                        </label>
                    </div>
                </div>
                <div class="col-md-3" style="margin-top:25px;">
                    <button id="filter_btn" class="btn btn-primary">Filter</button>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Receivables Details</h4>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th>Total Invoiced</th>
                                    <th>Total Paid</th>
                                    <th>Total Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $t_invoice = 0; $t_paid = 0; $t_due = 0;
                                if(!empty($summary)): $i=1;
                                    foreach($summary as $s):
                                        $t_invoice += $s->TotalInvoiced;
                                        $t_paid += $s->TotalPaid;
                                        $t_due += $s->TotalDue;
                                ?>
                                    <tr <?= ($s->TotalDue>0)? 'class="table-danger"':'' ?> >
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/customer_payments/invoice_aging/'.$s->CustomerId) ?>">
                                                <?= htmlspecialchars(!empty($s->CompanyName) ? $s->CompanyName : $s->CustomerName) ?>
                                            </a>
                                        </td>

                                        <td class="text-right"><?= number_format($s->TotalInvoiced,2) ?></td>
                                        <td class="text-right"><?= number_format($s->TotalPaid,2) ?></td>
                                        <td class="text-right"><?= number_format($s->TotalDue,2) ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total:</th>
                                    <th class="text-right"><?= number_format($t_invoice,2) ?></th>
                                    <th class="text-right"><?= number_format($t_paid,2) ?></th>
                                    <th class="text-right"><?= number_format($t_due,2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<!-- DataTables JS -->
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.buttons.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/jszip.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/pdfmake.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/vfs_fonts.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/buttons.html5.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/buttons.print.min.js") ?>"></script>

<script>
$(document).ready(function () {
    var table = $('#datatable').DataTable({
        "pageLength": 25,
        "order": [[0,"asc"]],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', title: 'Receivables Summary' },
            { extend: 'pdf', title: 'Receivables Summary' },
            { extend: 'print', title: 'Receivables Summary' }
        ]
    });

    // Filter Button
    $('#filter_btn').click(function(){
        var month = $('#filter_month').val();
        var year = $('#filter_year').val();
        var only_due = $('#only_due').is(':checked') ? 1 : 0;

        // Reload page with GET parameters
        var url = '<?= base_url("admin/customer_payments/receivables_summary") ?>?';
        if(month) url += 'month='+month+'&';
        if(year) url += 'year='+year+'&';
        if(only_due) url += 'only_due=1';
        window.location.href = url;
    });
});
</script>

<?php $this->view('includes/footer_end.php') ?>
