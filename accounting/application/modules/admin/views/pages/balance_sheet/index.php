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

<style>
    .balance-sheet-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .balance-sheet-card:hover {
        transform: translateY(-5px);
    }
    
    .asset-row {
        background-color: #d9edf7;
        font-weight: bold;
    }
    
    .liability-row {
        background-color: #f2dede;
        font-weight: bold;
    }
    
    .equity-row {
        background-color: #dff0d8;
        font-weight: bold;
    }
    
    .total-row {
        background-color: #fcf8e3;
        font-weight: bold;
        font-size: 1.1em;
    }
    
    .balanced {
        color: green;
        font-weight: bold;
    }
    
    .unbalanced {
        color: red;
        font-weight: bold;
    }
    
    .account-sub {
        padding-left: 25px !important;
    }
    
    .summary-card {
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .summary-card h4 {
        margin-top: 0;
        margin-bottom: 15px;
    }
    
    .summary-card h2 {
        margin-bottom: 0;
        font-weight: bold;
    }
    
    .table-balance-sheet {
        margin-bottom: 0;
    }
    
    .table-balance-sheet th,
    .table-balance-sheet td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    .verification-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 5px;
        margin-top: 20px;
        font-size: 14px;
    }
    
    @media print {
        /* Hide navigation elements */
        .topbar, .side-menu, .breadcrumb, .filter-panel, .footer {
            display: none !important;
        }
        /* Expand content-page to 100% width and reset margins */
        .content-page {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 0 !important;
        }
        .card-box {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        body {
            background: #fff !important;
        }
    }
</style>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            <i class="fa fa-balance-scale"></i> Balance Sheet
                        </h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Balance Sheet</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-panel" style="margin-bottom: 20px; background: #fdfdfd; border: 1px solid #e3e7eb; padding: 15px; border-radius: 8px;">
                <form method="GET" action="<?= base_url('admin/balance_sheet') ?>" id="filterForm" class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label style="font-weight: 600; color: #2c3e50;">As of Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="<?= htmlspecialchars($to_date) ?>">
                    </div>
                    <div class="col-md-9 col-sm-6 col-xs-12" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-success" style="font-weight: bold; height: 38px; width: 100px;">Filter</button>
                        <button type="button" onclick="resetFilter()" class="btn btn-default" style="font-weight: bold; height: 38px; width: 100px; margin-left: 5px;">Reset</button>
                        <button type="button" onclick="window.print()" class="btn btn-primary" style="font-weight: bold; height: 38px; width: 100px; margin-left: 5px;"><i class="fa fa-print"></i> Print</button>
                        <a href="<?= base_url('admin/balance_sheet/export_csv?to_date='.$to_date) ?>" class="btn btn-info" style="font-weight: bold; height: 38px; line-height: 24px; width: 120px; margin-left: 5px;"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                    </div>
                </form>
            </div>

            <script>
                function resetFilter() {
                    window.location.href = '<?= base_url("admin/balance_sheet") ?>';
                }
            </script>

            <!-- Balance Sheet Date -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info text-center">
                        <strong>As of: <?= date('F d, Y', strtotime($to_date)) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="summary-card balance-sheet-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h4><i class="fa fa-building"></i> Total Assets</h4>
                        <h2><?= number_format($totalAssets, 2) ?></h2>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="summary-card balance-sheet-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <h4><i class="fa fa-credit-card"></i> Total Liabilities</h4>
                        <h2><?= number_format($totalLiabilities, 2) ?></h2>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <div class="summary-card balance-sheet-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <h4><i class="fa fa-users"></i> Total Equity</h4>
                        <h2><?= number_format($totalEquity, 2) ?></h2>
                    </div>
                </div>
            </div>

            <!-- Main Balance Sheet -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">
                            <i class="fa fa-file-text-o"></i> Statement of Financial Position
                        </h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-balance-sheet">
                                <thead>
                                    <tr style="background: #4F81BD; color: #fff;">
                                        <th width="70%">Account</th>
                                        <th width="30%" style="text-align: right;">Amount (<?= $this->session->userdata('currency_symbol') ?: '$' ?>)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- ASSETS SECTION -->
                                    <tr class="asset-row">
                                        <td><strong><i class="fa fa-arrow-right"></i> ASSETS</strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($totalAssets, 2) ?></strong></td>
                                    </tr>
                                    
                                    <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-money"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=1') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Cash in Hand
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($cash, 2) ?></td>
                                     </tr>
                                    
                                    <tr>
                                        <td class="account-sub">
                                            <i class="fa fa-university"></i> 
                                            <a href="javascript:void(0);" id="showBankBalance" style="color: #337ab7; text-decoration: none;">
                                                Bank Balances
                                            </a>
                                        </td>
                                        <td style="text-align: right;"><?= number_format($bank, 2) ?></td>
                                    </tr>
                                    
                                    <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-users"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=3') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Accounts Receivable
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($receivable, 2) ?></td>
                                     </tr>
                                    
                                    <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-cubes"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=4') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Inventory
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($inventory, 2) ?></td>
                                     </tr>
                                    
                                    <tr>
                                        <td class="account-sub"><i class="fa fa-building-o"></i> Fixed Assets</td>
                                        <td style="text-align: right;"><?= number_format($fixedAssets, 2) ?></td>
                                    </tr>
                                    
                                    <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-briefcase"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=40') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Investment Account
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($investments, 2) ?></td>
                                     </tr>
                                     
                                     <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-user"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=82') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Salary Advances / Employee Loans
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($salaryAdvances, 2) ?></td>
                                     </tr>
                                    
                                    <!-- LIABILITIES SECTION -->
                                    <tr class="liability-row">
                                        <td><strong><i class="fa fa-arrow-right"></i> LIABILITIES</strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($totalLiabilities, 2) ?></strong></td>
                                    </tr>
                                    
                                    <?php if(empty($liabilityDetails)): ?>
                                    <tr>
                                        <td class="account-sub"><i class="fa fa-info-circle"></i> No outstanding liabilities</td>
                                        <td style="text-align: right;">0.00</td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach($liabilityDetails as $lib): ?>
                                        <tr>
                                            <td class="account-sub">
                                                <i class="fa fa-money"></i> 
                                                <a href="<?= base_url('admin/general_ledger?account='.$lib->AccountID) ?>" style="color: #337ab7; text-decoration: none;">
                                                    <?= htmlspecialchars($lib->AccountName) ?>
                                                </a>
                                            </td>
                                            <td style="text-align: right;"><?= number_format($lib->Balance, 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <!-- EQUITY SECTION -->
                                    <tr class="equity-row">
                                        <td><strong><i class="fa fa-arrow-right"></i> EQUITY</strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($totalEquity, 2) ?></strong></td>
                                    </tr>
                                    
                                    <tr>
                                         <td class="account-sub">
                                             <i class="fa fa-database"></i> 
                                             <a href="<?= base_url('admin/general_ledger?account=6') ?>" style="color: #337ab7; text-decoration: none;">
                                                 Opening Equity
                                             </a>
                                         </td>
                                         <td style="text-align: right;"><?= number_format($openingEquity, 2) ?></td>
                                     </tr>
                                    
                                    <tr>
                                        <td class="account-sub"><i class="fa fa-line-chart"></i> Current Period Revenue</td>
                                        <td style="text-align: right;"><?= number_format($revenue, 2) ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="account-sub" style="padding-left: 40px;"><i class="fa fa-minus"></i> Cost of Goods Sold (COGS)</td>
                                        <td style="text-align: right;">(<?= number_format($cogs, 2) ?>)</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="account-sub" style="padding-left: 40px;"><i class="fa fa-minus"></i> Operating Expenses</td>
                                        <td style="text-align: right;">(<?= number_format($expenses, 2) ?>)</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="account-sub"><i class="fa fa-trophy"></i> Net Income (Loss)</td>
                                        <td style="text-align: right;"><strong><?= number_format($netIncome, 2) ?></strong></td>
                                    </tr>
                                    
                                    <?php if(isset($drawings) && $drawings > 0): ?>
                                    <tr>
                                        <td class="account-sub" style="padding-left: 40px; color: #ff5b5b;">
                                            <i class="fa fa-minus"></i> Less: Personal Drawings
                                        </td>
                                        <td style="text-align: right; color: #ff5b5b;">(<?= number_format($drawings, 2) ?>)</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <tr>
                                        <td class="account-sub"><i class="fa fa-adjust"></i> Historical Balance Adjustment</td>
                                        <td style="text-align: right;"><?= number_format($historicalAdjustment, 2) ?></td>
                                    </tr>
                                    
                                    <!-- GRAND TOTAL -->
                                    <tr class="total-row">
                                        <td><strong><i class="fa fa-calculator"></i> TOTAL LIABILITIES & EQUITY</strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($liabilitiesPlusEquity, 2) ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Verification Badge -->
                        <div class="text-center">
                            <?php if($isBalanced): ?>
                                <div class="verification-badge balanced" style="background: #dff0d8; border: 1px solid #3c763d;">
                                    <i class="fa fa-check-circle"></i> ✓ BALANCED: Assets = Liabilities + Equity
                                </div>
                            <?php else: ?>
                                <div class="verification-badge unbalanced" style="background: #f2dede; border: 1px solid #a94442;">
                                    <i class="fa fa-exclamation-triangle"></i> ⚠ UNBALANCED: Difference of <?= number_format($difference, 2) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h5><i class="fa fa-info-circle"></i> Notes:</h5>
                        <ul class="m-b-0">
                            <li>Accounts Receivable represents outstanding customer payments.</li>
                            <li>Accounts Payable represents outstanding vendor payments.</li>
                             <li>Net Income is calculated as Revenue - (COGS + Operating Expenses).</li>
                             <li>A Historical Balance Adjustment has been applied to reconcile opening/historical ledger discrepancies.</li>
                             <li>This balance sheet is automatically generated based on your transaction history.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bank Balance Modal -->
<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #4F81BD; color: white;">
                <h4 class="modal-title">
                    <i class="fa fa-university"></i> Bank Balance Details
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th style="text-align: right;">Balance</th>
                        </tr>
                    </thead>
                    <tbody id="bankBalanceBody">
                        <tr>
                            <td colspan="3" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background: #f9f9f9;">
                            <td colspan="2" style="text-align: right;">Total Bank Balance:</td>
                            <td style="text-align: right;" id="bankBalanceTotal">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    $('#showBankBalance').on('click', function(e){
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url("admin/balance_sheet/get_bank_balances") ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function(){
                $('#bankBalanceBody').html(
                    '<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading bank details...</td></tr>'
                );
            },
            success: function(response){
                var html = '';
                var total = 0;
                
                if(response.length === 0) {
                    html = '<tr><td colspan="3" class="text-center">No bank accounts found</td></tr>';
                } else {
                    $.each(response, function(index, bank){
                        var balance = parseFloat(bank.Balance);
                        total += balance;
                        
                        var bankLink = bank.AccountID 
                            ? '<a href="<?= base_url("admin/general_ledger?account=") ?>' + bank.AccountID + '">' + bank.BankName + '</a>' 
                            : bank.BankName;
                        
                        html += '<tr>' +
                                    '<td>' + bankLink + '</td>' +
                                    '<td>' + (bank.AccountNumber || 'N/A') + '</td>' +
                                    '<td style="text-align: right;">' + 
                                        balance.toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }) + 
                                    '</td>' +
                                '</tr>';
                    });
                }
                
                $('#bankBalanceBody').html(html);
                $('#bankBalanceTotal').html(
                    total.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );
                
                $('#bankModal').modal('show');
            },
            error: function(){
                $('#bankBalanceBody').html(
                    '<tr><td colspan="3" class="text-center text-danger">Error loading bank balances</td></tr>'
                );
            }
        });
    });
});
</script>

<!-- extra js end -->

<?php $this->view('includes/footer_end.php'); ?>