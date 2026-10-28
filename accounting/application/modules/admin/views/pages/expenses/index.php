<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
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
                        <h4 class="page-title">Expenses</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Expenses</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- CREATE NEW EXPENSE BUTTON -->
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?= base_url('admin/expenses/create') ?>" class="btn btn-info btn-create pull-left">
                        <i class="fa fa-plus"></i> Create New Expense
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-lg-3">
                    <label>From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-lg-3">
                    <label>To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="filter" class="btn btn-success btn-block">Filter</button>
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="reset_filter" class="btn btn-default btn-block">Reset</button>
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="export_excel" class="btn btn-info btn-block">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </button>
                </div>
            </div>

            <br>

            <!-- Total Amount Display -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="total-amount-display" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fa fa-calendar"></i> This Month's Expenses: 
                        <strong><span id="monthTotal"><?= number_format($monthTotal, 2) ?></span></strong>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="total-amount-display" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fa fa-money"></i> Total Expenses (Search wise): 
                        <strong><span id="totalAmount"><?= number_format($totalAmount, 2) ?></span></strong>
                    </div>
                </div>
            </div>

            <!-- Expense List Table -->
            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Expense List</h4>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Expense Account</th>
                                <th>Customer / Domain</th>
                                <th>Employee</th>
                                <th>Payee</th>
                                <th>Description</th>
                                <th>Bank</th>
                                <th style="text-align:right;">Amount</th>
                                <th>Payment Method</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($records as $row): ?>
                            <tr>
                                <td><?= $row->ExpenseID ?></td>
                                <td><?= date('Y-m-d', strtotime($row->ExpenseDate)) ?></td>
                                <td><?= $row->AccountCode ?> - <?= $row->AccountName ?></td>
                                <td><?= !empty($row->Domains) ? $row->Domains : '-' ?></td>
                                <td><?= !empty($row->EmployeeName) ? $row->EmployeeName : '-' ?></td>
                                <td><?= $row->Payee ?></td>
                                <td><?= $row->Description ?></td>
                                <td>
                                    <?php if(!empty($row->BankName)): ?>
                                        <?= $row->BankName ?><br><small><?= $row->AccountNumber ?></small>
                                    <?php else: ?> - <?php endif; ?>
                                </td>
                                <td class="text-right amount-cell" data-amount="<?= $row->Amount ?>">
                                    <?= number_format($row->Amount, 2) ?>
                                </td>
                                <td><?= $row->PaymentMethod ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/expenses/edit/'.$row->ExpenseID) ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/expenses/delete/'.$row->ExpenseID) ?>" 
                                       class="btn btn-danger btn-sm delete-expense" 
                                       title="Delete"
                                       data-id="<?= $row->ExpenseID ?>"
                                       data-amount="<?= number_format($row->Amount, 2) ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($records)): ?>
                            <tr>
                                <td colspan="11" class="text-center">No expenses found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right;">Total Expenses</th>
                                <th style="text-align:right;color:red;font-size:16px;" id="footerTotal">
                                    <?= number_format($totalAmount, 2) ?>
                                </th>
                                <th colspan="2"></th>
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
                <p>Are you sure you want to delete this expense?</p>
                <p><strong>Expense ID:</strong> <span id="delete_expense_id"></span></p>
                <p><strong>Amount:</strong> LKR <span id="delete_expense_amount"></span></p>
                <p class="text-danger">This action cannot be undone!</p>
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
    // Register custom date range filter in Datatables globally once
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            
            if (!fromDate && !toDate) {
                return true; // No filter applied
            }
            
            var rowDate = data[1].trim();
            if (!rowDate) return true;
            
            if (fromDate && rowDate < fromDate) {
                return false;
            }
            if (toDate && rowDate > toDate) {
                return false;
            }
            return true;
        }
    );

    // Function to calculate total from all matching/filtered rows
    function updateTotalDisplay() {
        var total = 0;
        table.rows({ filter: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            var amountText = data[8];
            var cleanText = amountText.replace(/<[^>]*>/g, '').replace(/,/g, '').trim();
            var amount = parseFloat(cleanText);
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

        // Also update monthTotal display dynamically when filtering
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        if (fromDate || toDate) {
            $('#monthTotal').text(total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        } else {
            $('#monthTotal').text(parseFloat(<?= json_encode($monthTotal) ?>).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }
    }
    
    // Initialize DataTable
    var table = $('#datatable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 10,
        "language": {
            "emptyTable": "No expenses found",
            "info": "Showing _START_ to _END_ of _TOTAL_ expenses",
            "infoEmpty": "Showing 0 to 0 of 0 expenses",
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
        
        table.draw();
        showAlert('Filter applied successfully', 'success');
    });

    // Reset filter
    $('#reset_filter').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        table.search('').columns().search('').draw();
        showAlert('Filter reset successfully', 'success');
    });
    
    // Handle search input
    $('#datatable_filter input').unbind().on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Export to CSV
    $('#export_excel').click(function(){
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        
        var url = '<?= base_url("admin/expenses/export_csv") ?>?';
        if(fromDate) url += 'from_date=' + fromDate + '&';
        if(toDate) url += 'to_date=' + toDate;
        
        window.location.href = url;
        showAlert('Exporting expenses...', 'success');
    });
    
    // Delete expense with confirmation modal
    $('.delete-expense').click(function(e){
        e.preventDefault();
        var expenseId = $(this).data('id');
        var expenseAmount = $(this).data('amount');
        
        $('#delete_expense_id').text(expenseId);
        $('#delete_expense_amount').text(expenseAmount);
        $('#confirmDeleteBtn').attr('href', '<?= base_url("admin/expenses/delete/") ?>' + expenseId);
        $('#deleteModal').modal('show');
    });
    
    // Show success message after delete (if coming from delete action)
    <?php if($this->session->flashdata('notification')): ?>
        <?php $notif = $this->session->flashdata('notification'); ?>
        showAlert('<?= strip_tags($notif['text']) ?>', '<?= $notif['alert'] ?>');
    <?php endif; ?>
    
    // Initial total calculation
    setTimeout(function() {
        updateTotalDisplay();
    }, 500);
});
</script>

<?php $this->view('includes/footer_end.php'); ?>