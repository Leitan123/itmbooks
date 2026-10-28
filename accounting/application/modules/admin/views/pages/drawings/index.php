<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>

<style>
    .dataTables_filter, .dataTables_length {
        margin-bottom: 15px;
    }
    .total-amount-display {
        font-size: 18px;
        font-weight: bold;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .total-amount-display span {
        font-size: 24px;
        margin-left: 10px;
    }
    .alert-custom {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideIn 0.5s ease;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Drawings / Personal Withdrawals</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Drawings</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- CREATE NEW DRAWINGS BUTTON -->
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?= base_url('admin/drawings/create') ?>" class="btn btn-info btn-create pull-left">
                        <i class="fa fa-plus"></i> Create Drawings Entry
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-lg-4">
                    <label>From Date</label>
                    <input type="date" id="from_date" class="form-control" value="<?= htmlspecialchars((string)$from_date) ?>">
                </div>
                <div class="col-lg-4">
                    <label>To Date</label>
                    <input type="date" id="to_date" class="form-control" value="<?= htmlspecialchars((string)$to_date) ?>">
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="filter" class="btn btn-success btn-block">Filter</button>
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="reset_filter" class="btn btn-default btn-block">Reset</button>
                </div>
            </div>

            <br>

            <!-- Total Amount Display -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="total-amount-display" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fa fa-calendar"></i> This Month's Withdrawals: 
                        <strong><span id="monthTotal"><?= number_format($monthTotal, 2) ?></span></strong>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="total-amount-display" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fa fa-money"></i> Total Withdrawals (Search wise): 
                        <strong><span id="totalAmount"><?= number_format($totalAmount, 2) ?></span></strong>
                    </div>
                </div>
            </div>

            <!-- Drawings List Table -->
            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Drawings / Personal Withdrawals List</h4>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Director / Owner</th>
                                <th>Withdrawal Type</th>
                                <th>Paid From</th>
                                <th style="text-align:right;">Amount</th>
                                <th>Beneficiary / Payee</th>
                                <th>Description</th>
                                <th>Attachment</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($records as $row): ?>
                            <tr>
                                <td><?= $row->DrawingID ?></td>
                                <td><?= date('Y-m-d', strtotime($row->DrawingDate)) ?></td>
                                <td><?= htmlspecialchars((string)$row->DirectorName) ?></td>
                                <td><?= htmlspecialchars((string)$row->WithdrawalType) ?></td>
                                <td>
                                    <?php if($row->PaymentMethod == 'Cash'): ?>
                                        Cash
                                    <?php else: ?>
                                        Bank: <?= htmlspecialchars((string)$row->BankName) ?><br><small><?= htmlspecialchars((string)$row->AccountNumber) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right amount-cell" data-amount="<?= $row->Amount ?>">
                                    <?= number_format($row->Amount, 2) ?>
                                </td>
                                <td><?= htmlspecialchars((string)$row->Payee) ?></td>
                                <td><?= htmlspecialchars((string)$row->Description) ?></td>
                                <td class="text-center">
                                    <?php if(!empty($row->Attachment)): ?>
                                        <a href="<?= base_url('media/doc/' . $row->Attachment) ?>" target="_blank" class="btn btn-xs btn-info" title="View Attachment">
                                            <i class="fa fa-paperclip"></i> View
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/drawings/edit/'.$row->DrawingID) ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" 
                                       class="btn btn-danger btn-sm delete-drawing" 
                                       title="Delete"
                                       data-id="<?= $row->DrawingID ?>"
                                       data-amount="<?= number_format($row->Amount, 2) ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($records)): ?>
                            <tr>
                                <td colspan="10" class="text-center">No drawings found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right;">Total</th>
                                <th style="text-align:right;color:red;font-size:16px;" id="footerTotal">
                                    <?= number_format($totalAmount, 2) ?>
                                </th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Alert Popup -->
<div id="customAlert" class="alert-custom" style="display:none;">
    <button type="button" class="close" onclick="$('#customAlert').fadeOut();">&times;</button>
    <strong id="alertMessage"></strong>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#d9534f; color:white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this drawings entry?</p>
                <p><strong>Drawings ID:</strong> <span id="delete_drawing_id"></span></p>
                <p><strong>Amount:</strong> LKR <span id="delete_drawing_amount"></span></p>
                <p class="text-danger">This action cannot be undone and will reverse the associated journal entry!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>
$(document).ready(function(){
    // Function to show custom alert
    function showAlert(message, type) {
        var alertDiv = $('#customAlert');
        var bgColor = type === 'success' ? '#5cb85c' : (type === 'error' ? '#d9534f' : '#f0ad4e');
        alertDiv.css('background', bgColor);
        alertDiv.css('color', 'white');
        alertDiv.css('padding', '15px');
        alertDiv.css('border-radius', '5px');
        alertDiv.css('box-shadow', '0 2px 10px rgba(0,0,0,0.2)');
        $('#alertMessage').html(message);
        alertDiv.fadeIn();
        setTimeout(function() {
            alertDiv.fadeOut();
        }, 3000);
    }
    
    // Function to calculate total from visible rows only
    function updateTotalDisplay() {
        var total = 0;
        $('#datatable tbody tr:visible').each(function() {
            var amountText = $(this).find('td:eq(5)').text();
            var amount = parseFloat(amountText.replace(/,/g, ''));
            if (!isNaN(amount)) {
                total += amount;
            }
        });
        
        $('#footerTotal').text(total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $('#totalAmount').text(total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }
    
    // Initialize DataTable
    var table = $('#datatable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 10,
        "language": {
            "emptyTable": "No drawings found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries"
        },
        "columnDefs": [
            { type: 'date', targets: 1 }
        ],
        "drawCallback": function() {
            setTimeout(function() {
                updateTotalDisplay();
            }, 100);
        }
    });

    // Date filter function
    $('#filter').click(function(){
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();

        if(!fromDate && !toDate) {
            showAlert('Please select at least one date range', 'warning');
            return;
        }

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var date = new Date(data[1]);
                var from = fromDate ? new Date(fromDate) : null;
                var to = toDate ? new Date(toDate) : null;

                if(from) from.setHours(0, 0, 0, 0);
                if(to) to.setHours(23, 59, 59, 999);
                
                if(from && to) {
                    return date >= from && date <= to;
                } else if(from) {
                    return date >= from;
                } else if(to) {
                    return date <= to;
                }
                return true;
            }
        );
        
        table.draw();
        $.fn.dataTable.ext.search.pop();
        
        showAlert('Filter applied successfully', 'success');
    });

    // Reset filter
    $('#reset_filter').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $.fn.dataTable.ext.search = [];
        table.search('').columns().search('').draw();
        showAlert('Filter reset successfully', 'success');
    });
    
    // Handle search input
    $('#datatable_filter input').unbind().on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Delete drawings with confirmation modal
    $('#datatable').on('click', '.delete-drawing', function(e){
        e.preventDefault();
        var drawingId = $(this).data('id');
        var drawingAmount = $(this).data('amount');
        
        $('#delete_drawing_id').text(drawingId);
        $('#delete_drawing_amount').text(drawingAmount);
        $('#confirmDeleteBtn').attr('href', '<?= base_url("admin/drawings/delete/") ?>' + drawingId);
        $('#deleteModal').modal('show');
    });
    
    // Show flash message
    <?php if($this->session->flashdata('notification')): ?>
        <?php $notif = $this->session->flashdata('notification'); ?>
        showAlert('<?= strip_tags($notif['text']) ?>', '<?= $notif['alert'] ?>');
    <?php endif; ?>
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
