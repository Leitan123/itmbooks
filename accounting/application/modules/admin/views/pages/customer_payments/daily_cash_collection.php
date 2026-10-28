<?php $this->view('includes/header_start.php'); ?>
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet"/>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Select Date</label>
                    <input type="date" id="collection_date" class="form-control" value="<?= $date ?>">
                </div>
                <div class="col-md-2 align-self-end">
                    <button id="filter" class="btn btn-primary">Filter</button>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Daily Cash Collection - <?= $date ?></h4>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Method</th>
                                <th>Total Amount</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($summary)): $i=1; ?>
                                <?php foreach($summary as $row): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $row->PaymentMethod ?></td>
                                    <td class="text-right"><?= number_format($row->TotalAmount,2) ?></td>
                                    <td><?= $row->Details ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">No records found</td></tr>
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
    var date = $('#collection_date').val();
    window.location.href = '<?= base_url("admin/customer_payments/daily_cash_collection") ?>?date=' + date;
});
$(document).ready(function(){
    $('#datatable').DataTable();
});
</script>
<?php $this->view('includes/footer_end.php'); ?>
