<?php $this->view('includes/header_start.php'); ?>
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" id="from_date" class="form-control" value="<?= $from ?>">
                </div>
                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" id="to_date" class="form-control" value="<?= $to ?>">
                </div>
                <div class="col-md-2 align-self-end">
                    <button id="filter" class="btn btn-primary">Filter</button>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Cheque Register</h4>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Date</th>
                                <th>Company Name</th>
                                <th>Amount</th>
                                <th>Bank Name</th>
                                <th>Cheque No</th>
                                <th>Cheque Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($cheques)): $i=1; ?>
                                <?php foreach($cheques as $row): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= date('Y-m-d', strtotime($row->PaymentDate)) ?></td>
                                    <td><?= htmlspecialchars(!empty($row->CompanyName) ? $row->CompanyName : $row->CustomerName) ?></td>
                                    <td class="text-right"><?= number_format($row->PaidAmount,2) ?></td>
                                    <td><?= htmlspecialchars($row->BankName) ?></td>
                                    <td><?= $row->ChequeNumber ?></td>
                                    <td><?= $row->ChequeDate ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No records found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>
<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>
<script>
$('#filter').click(function(){
    var from = $('#from_date').val();
    var to = $('#to_date').val();
    window.location.href = '<?= base_url("admin/customer_payments/cheque_register") ?>?from=' + from + '&to=' + to;
});
$(document).ready(function(){
    $('#datatable').DataTable();
});
</script>
<?php $this->view('includes/footer_end.php'); ?>
