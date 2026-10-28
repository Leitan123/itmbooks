<?php $this->view('includes/header_start.php'); ?>

<!-- Custom Styles for Premium POS Layout -->
<style>
    /* Override defaults for high-end POS interface */
    .content-page {
        margin-left: 240px;
        overflow: hidden;
    }
    
    .pos-container {
        display: flex;
        height: calc(100vh - 70px);
        margin: -20px;
        background-color: #f4f6f9;
        font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    }
    
    /* Left Panel - Products and Filters */
    .pos-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
        border-right: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .pos-header-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
    }
    
    .pos-search-wrapper {
        position: relative;
        flex: 1;
    }
    
    .pos-search-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }
    
    .pos-search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        font-size: 14px;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
    }
    
    .pos-search-input:focus {
        border-color: #1abc9c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
    }
    
    /* Category Scrollbar */
    .pos-categories {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 10px;
        margin-bottom: 15px;
        scrollbar-width: thin;
    }
    
    .pos-categories::-webkit-scrollbar {
        height: 4px;
    }
    
    .pos-categories::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 4px;
    }
    
    .category-pill {
        white-space: nowrap;
        padding: 8px 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        color: #4a5568;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    
    .category-pill.active, .category-pill:hover {
        background: linear-gradient(135deg, #1abc9c, #16a085);
        border-color: #1abc9c;
        color: #ffffff;
        box-shadow: 0 4px 6px rgba(26, 188, 156, 0.15);
    }
    
    /* Item Cards Grid */
    .pos-items-grid {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        overflow-y: auto;
        padding-bottom: 20px;
    }
    
    .item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
        height: 310px;
    }
    
    .item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        border-color: #1abc9c;
    }
    
    .item-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 5px;
    }
    
    .item-card-badge {
        font-size: 9px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 4px;
        text-transform: uppercase;
        background-color: rgba(26, 188, 156, 0.1);
        color: #16a085;
    }
    
    .item-card-stock {
        font-size: 10px;
        color: #718096;
    }
    
    .item-card-stock.low {
        color: #e53e3e;
        font-weight: bold;
    }

    .item-card-img-wrapper {
        width: 100%;
        height: 105px;
        background: #f8fafc;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1f5f9;
    }

    .item-card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-card-img-wrapper.placeholder {
        background: #f1f5f9;
    }

    .item-card-img-placeholder {
        font-size: 28px;
        color: #cbd5e1;
    }
    
    .item-card-name {
        font-size: 13px;
        font-weight: 600;
        color: #2d3748;
        line-height: 1.3;
        margin-bottom: 5px;
        height: 34px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .item-card-brand {
        font-size: 10px;
        color: #a0aec0;
        margin-bottom: 1px;
    }

    .item-card-code {
        font-size: 10px;
        color: #718096;
        margin-bottom: 4px;
        font-family: monospace;
        background: #f8fafc;
        padding: 1px 4px;
        border-radius: 3px;
        display: inline-block;
    }
    
    .item-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .item-card-price {
        font-size: 14px;
        font-weight: 700;
        color: #2b6cb0;
    }
    
    .item-card-add-btn {
        background: #f7fafc;
        border: 1px solid #cbd5e0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5568;
        font-weight: bold;
        transition: all 0.2s;
    }
    
    .item-card:hover .item-card-add-btn {
        background: #1abc9c;
        border-color: #1abc9c;
        color: #ffffff;
    }
    
    /* Right Panel - Cart */
    .pos-right {
        width: 380px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 10px rgba(0,0,0,0.02);
    }
    
    .cart-header {
        padding: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .cart-customer-wrapper {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .cart-customer-wrapper label {
        font-size: 12px;
        font-weight: 600;
        color: #718096;
        margin-bottom: 0;
    }
    
    .cart-items-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px 20px;
    }
    
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    
    .cart-item-details {
        flex: 1;
        margin-right: 10px;
    }
    
    .cart-item-title {
        font-size: 13px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
    }
    
    .cart-item-price {
        font-size: 12px;
        color: #718096;
    }
    
    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .qty-btn {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px solid #cbd5e0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .qty-btn:hover {
        background: #edf2f7;
        color: #2d3748;
    }
    
    .cart-item-qty {
        font-size: 13px;
        font-weight: 600;
        color: #2d3748;
        min-width: 20px;
        text-align: center;
    }
    
    .cart-item-total {
        font-size: 13px;
        font-weight: 700;
        color: #2d3748;
        min-width: 75px;
        text-align: right;
        margin-right: 10px;
    }
    
    .cart-item-delete {
        color: #e53e3e;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    
    .cart-item-delete:hover {
        opacity: 1;
    }
    
    .empty-cart-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #a0aec0;
        gap: 15px;
    }
    
    .empty-cart-state i {
        font-size: 48px;
    }
    
    /* Cart Footer Summary */
    .cart-footer {
        padding: 20px;
        background: #f7fafc;
        border-top: 1px solid #e2e8f0;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #4a5568;
        margin-bottom: 8px;
    }
    
    .summary-row.discount-row input {
        width: 80px;
        padding: 2px 5px;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
        text-align: right;
        font-size: 13px;
    }
    
    .summary-row.total-row {
        font-size: 18px;
        font-weight: 800;
        color: #1a202c;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
    }
    
    .cart-actions {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 10px;
        margin-top: 15px;
    }
    
    .btn-reset {
        background: #edf2f7;
        color: #4a5568;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        font-weight: 600;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-reset:hover {
        background: #e2e8f0;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #1abc9c, #16a085);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        padding: 12px;
        box-shadow: 0 4px 6px rgba(26, 188, 156, 0.2);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-checkout:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 14px rgba(26, 188, 156, 0.3);
    }
    
    /* Payment Modal Styles */
    .payment-modal-total {
        font-size: 32px;
        font-weight: 800;
        color: #2b6cb0;
        text-align: center;
        margin-bottom: 20px;
        background: #ebf8ff;
        padding: 15px;
        border-radius: 8px;
        border: 1px dashed #bee3f8;
    }
    
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .payment-method-card {
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .payment-method-card i {
        font-size: 20px;
        margin-bottom: 8px;
        color: #718096;
        display: block;
    }
    
    .payment-method-card span {
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .payment-method-card.active {
        border-color: #1abc9c;
        background-color: rgba(26, 188, 156, 0.05);
    }
    
    .payment-method-card.active i, .payment-method-card.active span {
        color: #1abc9c;
    }
    
    /* Change Calculation display */
    .change-calc {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f7fafc;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #edf2f7;
        margin-top: 15px;
    }
    
    .change-label {
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .change-value {
        font-size: 20px;
        font-weight: 800;
        color: #2d3748;
    }
    
    .change-value.surplus {
        color: #38a169;
    }
    
    .change-value.deficit {
        color: #e53e3e;
    }
    
    /* Responsive adjustment for topbar layout compatibility */
    @media (max-width: 1024px) {
        .pos-container {
            flex-direction: column;
            height: auto;
            overflow-y: auto;
        }
        .pos-right {
            width: 100%;
            height: auto;
        }
    }
</style>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="pos-container">
            
            <!-- Left Side: Product List and Search -->
            <div class="pos-left">
                
                <!-- Search & Filters Header -->
                <div class="pos-header-actions">
                    <div class="pos-search-wrapper">
                        <i class="fa fa-search"></i>
                        <input type="text" id="posSearch" class="pos-search-input" placeholder="Search items by Name, Category or Brand...">
                    </div>
                </div>
                
                <!-- Category Tabs -->
                <div class="pos-categories" id="categoriesScroll">
                    <div class="category-pill active" data-category="all">All Categories</div>
                    <?php foreach($categories as $cat): ?>
                        <div class="category-pill" data-category="<?= $cat->CategoryID ?>"><?= $cat->CategoryName ?></div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Items Grid -->
                <div class="pos-items-grid" id="itemsGrid">
                    <?php foreach($items as $item): ?>
                        <?php 
                            // Determine stock level class
                            $stockClass = '';
                            if($item->ItemType == 'Service') {
                                $stockText = 'Service';
                            } else {
                                $stockText = 'Stock: ' . $item->Stock;
                                if($item->Stock <= 0) {
                                    $stockClass = 'low';
                                    $stockText = 'Out of Stock';
                                } else if ($item->Stock <= 5) {
                                    $stockClass = 'low';
                                    $stockText = 'Only ' . $item->Stock . ' left';
                                }
                            }
                        ?>
                        <div class="item-card" 
                             data-id="<?= $item->ItemID ?>" 
                             data-name="<?= htmlspecialchars($item->ItemName ?? '') ?>" 
                             data-price="<?= $item->SellingPrice ?>" 
                             data-category="<?= $item->CategoryID ?>" 
                             data-brand="<?= htmlspecialchars($item->BrandName ?? '') ?>"
                             data-stock="<?= $item->Stock ?>"
                             data-type="<?= $item->ItemType ?>"
                             data-code="<?= htmlspecialchars($item->ItemCode ?? '') ?>">
                            <div class="item-card-header">
                                <span class="item-card-badge"><?= htmlspecialchars($item->CategoryName ?? '') ?></span>
                                <span class="item-card-stock <?= $stockClass ?>"><?= $stockText ?></span>
                            </div>
                            
                            <?php if (!empty($item->Image)): ?>
                                <div class="item-card-img-wrapper">
                                    <img src="<?= base_url($item->Image) ?>" alt="<?= htmlspecialchars($item->ItemName ?? '') ?>">
                                </div>
                            <?php else: ?>
                                <div class="item-card-img-wrapper placeholder">
                                    <i class="fa <?= $item->ItemType == 'Service' ? 'fa-gears' : 'fa-shopping-bag' ?> item-card-img-placeholder"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="item-card-brand"><?= htmlspecialchars($item->BrandName ?? '') ?></div>
                                <div class="item-card-code"><i class="fa fa-barcode"></i> <?= htmlspecialchars($item->ItemCode ?? 'N/A') ?></div>
                                <div class="item-card-name"><?= htmlspecialchars($item->ItemName ?? '') ?></div>
                            </div>
                            <div class="item-card-footer">
                                <span class="item-card-price">Rs. <?= number_format($item->SellingPrice, 2) ?></span>
                                <button class="item-card-add-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
            
            <!-- Right Side: Cart Summary -->
            <div class="pos-right">
                
                <!-- Cart Header (Customer Selector) -->
                <div class="cart-header">
                    <div class="cart-customer-wrapper">
                        <label><i class="fa fa-user"></i> Customer</label>
                        <select id="cartCustomerId" class="form-control select2">
                            <option value="">-- Walk-in Customer --</option>
                            <?php foreach($customers as $cust): ?>
                                <option value="<?= $cust->CustomerId ?>"><?= htmlspecialchars($cust->CompanyName ?? '') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Cart Items scroll area -->
                <div class="cart-items-list" id="cartItemsContainer">
                    <!-- Default Empty State -->
                    <div class="empty-cart-state" id="emptyCartState">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Basket is empty. Select items to add.</span>
                    </div>
                    <!-- Cart items appended dynamically here -->
                </div>
                
                <!-- Summary / Checkout Panel -->
                <div class="cart-footer">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summarySubtotal">Rs. 0.00</span>
                    </div>
                    <div class="summary-row discount-row">
                        <span>Discount (Rs.)</span>
                        <input type="number" id="summaryDiscount" value="0" min="0" step="any">
                    </div>
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <span id="summaryTotal">Rs. 0.00</span>
                    </div>
                    
                    <div class="cart-actions">
                        <button class="btn-reset" id="btnResetCart"><i class="fa fa-trash"></i> Reset</button>
                        <button class="btn-checkout" id="btnCheckout"><i class="fa fa-shopping-cart"></i> Checkout</button>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>

<!-- Checkout Payment Modal -->
<div id="paymentModal" class="modal fade" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="paymentModalLabel"><i class="fa fa-credit-card"></i> Pay Bill</h4>
            </div>
            <div class="modal-body">
                
                <!-- Grand Total Display -->
                <div class="payment-modal-total" id="modalGrandTotal">Rs. 0.00</div>
                
                <!-- Payment Method Toggle -->
                <label class="control-label" style="font-weight:600; margin-bottom:10px;">Payment Method</label>
                <div class="payment-methods-grid">
                    <div class="payment-method-card active" data-method="Cash">
                        <i class="fa fa-money"></i>
                        <span>Cash</span>
                    </div>
                    <div class="payment-method-card" data-method="Card">
                        <i class="fa fa-credit-card-alt"></i>
                        <span>Card</span>
                    </div>
                    <div class="payment-method-card" data-method="Bank Transfer">
                        <i class="fa fa-bank"></i>
                        <span>Bank</span>
                    </div>
                    <div class="payment-method-card" data-method="Cheque">
                        <i class="fa fa-file-text-o"></i>
                        <span>Cheque</span>
                    </div>
                </div>
                
                <!-- Cash Received / Amount Paid Input -->
                <div class="form-group">
                    <label class="control-label" id="amountPaidLabel">Amount Received (Rs.)</label>
                    <input type="number" id="amountPaid" class="form-control input-lg" style="font-size: 20px; height: 50px; font-weight: bold; text-align: right;" placeholder="0.00" step="any">
                </div>
                
                <!-- Dynamic Fields for Card / Bank / Cheque -->
                <div id="bankAccountWrapper" class="form-group" style="display:none;">
                    <label class="control-label">Deposit To (Bank Account)</label>
                    <select id="paymentBankId" class="form-control select2" style="width:100%;">
                        <option value="">-- Select Bank Account --</option>
                        <?php foreach($banks as $b): ?>
                            <option value="<?= $b->BankID ?>"><?= htmlspecialchars($b->BankName ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="cardDetailsWrapper" style="display:none; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px; background: #f8fafc;">
                    <div style="font-weight: bold; margin-bottom: 10px; font-size: 13px; color: #4a5568;"><i class="fa fa-plug"></i> Card Terminal Integration</div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="form-group" style="margin-bottom:10px;">
                                <label style="font-size:11px; color:#718096; margin-bottom: 2px;">Terminal IP Address</label>
                                <input type="text" id="inputTerminalIp" class="form-control input-sm" value="192.168.1.150">
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <div class="form-group" style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:#718096; margin-bottom: 2px;">&nbsp;</label>
                                <button type="button" class="btn btn-primary btn-sm btn-block" id="btnSyncTerminal" style="font-weight: bold; padding: 5px;"><i class="fa fa-refresh"></i> Link Terminal</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="font-size:11px; color:#718096; margin-bottom: 2px;">Card Brand</label>
                                <select id="cardBrand" class="form-control input-sm">
                                    <option value="Visa">Visa</option>
                                    <option value="Mastercard">Mastercard</option>
                                    <option value="Amex">Amex</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="font-size:11px; color:#718096; margin-bottom: 2px;">Approval Code / Ref</label>
                                <input type="text" id="cardTxnRef" class="form-control input-sm" placeholder="Auth / Ref No">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="chequeDetailsWrapper" style="display:none;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Cheque Number</label>
                                <input type="text" id="chequeNumber" class="form-control" placeholder="Cheque No">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Cheque Date</label>
                                <input type="date" id="chequeDate" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Balance / Change Due display -->
                <div class="form-group" style="margin-top: 15px;">
                    <label class="control-label" id="changeModalLabel">Change Due</label>
                    <input type="text" id="changeModalValue" class="form-control input-lg" style="font-size: 20px; height: 50px; font-weight: bold; text-align: right; background-color: #f8fafc; color: #2e7d32;" value="Rs. 0.00" readonly>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-lg" id="btnConfirmPayment"><i class="fa fa-check"></i> Finalize & Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="modal fade" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="receiptModalLabel"><i class="fa fa-print"></i> Thermal Receipt</h4>
            </div>
            <div class="modal-body" style="padding: 0;">
                <!-- Frame containing the print layout -->
                <iframe id="receiptFrame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnPrintReceipt"><i class="fa fa-print"></i> Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Card Terminal Sync Modal -->
<div id="cardTerminalModal" class="modal fade" role="dialog" aria-labelledby="cardTerminalModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" style="max-width: 350px; margin-top: 10%;">
        <div class="modal-content text-center" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            <div class="modal-body" style="padding: 30px;">
                <div class="terminal-animation-wrapper" style="margin-bottom: 20px;">
                    <i class="fa fa-desktop fa-4x text-primary" id="terminalIcon" style="transition: all 0.3s;"></i>
                    <div style="margin-top: 15px; font-weight: bold; font-size: 16px; color: #2d3748;" id="terminalStatusTitle">Connecting...</div>
                </div>
                <p class="text-muted" id="terminalStatusDesc" style="font-size: 13px;">Establishing connection to card terminal at <span id="targetTerminalIp">192.168.1.150</span>...</p>
                <div class="progress" style="height: 6px; margin-bottom: 0; margin-top: 20px;">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="terminalProgressBar"></div>
                </div>
                <button type="button" class="btn btn-default btn-block" id="btnCancelTerminalSync" style="margin-top: 20px; border-radius: 8px;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<!-- POS Client Logic -->
<script>
    $(document).ready(function() {
        // Initialize Select2 dropdowns
        $('.select2').select2();
        
        let cart = [];
        let paymentMethod = 'Cash';
        
        // Add Item Event
        $(document).on('click', '.item-card', function(e) {
            const card = $(this);
            const id = card.data('id');
            const name = card.data('name');
            const price = parseFloat(card.data('price')) || 0;
            const stock = parseFloat(card.data('stock')) || 0;
            const type = card.data('type');
            
            addToCart(id, name, price, stock, type);
        });
        
        // Add item helper
        function addToCart(id, name, price, stock, type) {
            const existing = cart.find(item => item.id === id);
            
            if (existing) {
                // If it's a product and has a positive stock level, check stock limit
                if (type !== 'Service' && stock > 0 && existing.qty + 1 > stock) {
                    swal("Stock Limit Reached", "Cannot add more. Only " + stock + " in stock.", "warning");
                    return;
                }
                existing.qty += 1;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    stock: stock,
                    type: type
                });
            }
            renderCart();
        }
        
        // Render Cart elements
        function renderCart() {
            const container = $('#cartItemsContainer');
            
            if(cart.length === 0) {
                $('#emptyCartState').show();
                $('.cart-item').remove();
                calculateTotals();
                return;
            }
            
            $('#emptyCartState').hide();
            // Clear items and rebuild
            container.find('.cart-item').remove();
            
            cart.forEach(item => {
                const total = item.qty * item.price;
                const row = `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-details">
                            <div class="cart-item-title">${item.name}</div>
                            <div class="cart-item-price">Rs. ${item.price.toFixed(2)}</div>
                        </div>
                        <div class="cart-item-controls">
                            <button class="qty-btn btn-minus"><i class="fa fa-minus"></i></button>
                            <span class="cart-item-qty">${item.qty}</span>
                            <button class="qty-btn btn-plus"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="cart-item-total">Rs. ${total.toFixed(2)}</div>
                        <div class="cart-item-delete"><i class="fa fa-trash-o"></i></div>
                    </div>
                `;
                container.append(row);
            });
            
            calculateTotals();
        }
        
        // Cart controls: Increase/Decrease Qty
        $(document).on('click', '.btn-plus', function() {
            const id = $(this).closest('.cart-item').data('id');
            const item = cart.find(i => i.id === id);
            if (item) {
                if(item.type !== 'Service' && item.stock > 0 && item.qty + 1 > item.stock) {
                    swal("Stock Limit Reached", "Only " + item.stock + " items available in stock.", "warning");
                    return;
                }
                item.qty += 1;
                renderCart();
            }
        });
        
        $(document).on('click', '.btn-minus', function() {
            const id = $(this).closest('.cart-item').data('id');
            const item = cart.find(i => i.id === id);
            if (item) {
                item.qty -= 1;
                if(item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
                renderCart();
            }
        });
        
        $(document).on('click', '.cart-item-delete', function() {
            const id = $(this).closest('.cart-item').data('id');
            cart = cart.filter(i => i.id !== id);
            renderCart();
        });
        
        // Reset Cart
        $('#btnResetCart').click(function() {
            if(cart.length > 0) {
                swal({
                    title: "Clear Basket?",
                    text: "Are you sure you want to remove all items from the basket?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, clear it!",
                    closeOnConfirm: true
                }, function() {
                    cart = [];
                    renderCart();
                });
            }
        });
        
        // Discount check
        $('#summaryDiscount').on('input', function() {
            calculateTotals();
        });
        
        // Compute Cart Totals
        function calculateTotals() {
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += (item.qty * item.price);
            });
            
            const discount = parseFloat($('#summaryDiscount').val()) || 0;
            const total = Math.max(0, subtotal - discount);
            
            $('#summarySubtotal').text('Rs. ' + subtotal.toFixed(2));
            $('#summaryTotal').text('Rs. ' + total.toFixed(2));
            
            return {
                subtotal: subtotal,
                discount: discount,
                total: total
            };
        }
        
        // Filter by Category
        $('.category-pill').click(function() {
            $('.category-pill').removeClass('active');
            $(this).addClass('active');
            
            const category = $(this).data('category');
            filterGrid(category, $('#posSearch').val());
        });
        
        // Search items
        $('#posSearch').on('input', function() {
            const category = $('.category-pill.active').data('category');
            filterGrid(category, $(this).val());
        });
        
        function filterGrid(category, query) {
            query = query.toLowerCase().trim();
            
            $('.item-card').each(function() {
                const card = $(this);
                const cardCat = card.data('category');
                const cardName = card.data('name').toLowerCase();
                const cardBrand = card.data('brand').toLowerCase();
                
                const catMatch = (category === 'all' || cardCat == category);
                const nameMatch = (!query || cardName.includes(query) || cardBrand.includes(query));
                
                if (catMatch && nameMatch) {
                    card.show();
                } else {
                    card.hide();
                }
            });
        }
        
        // Show Checkout Modal
        $('#btnCheckout').click(function() {
            if(cart.length === 0) {
                swal("Empty Basket", "Please add at least one item to proceed to payment.", "warning");
                return;
            }
            
            const totals = calculateTotals();
            $('#modalGrandTotal').text('Rs. ' + totals.total.toFixed(2));
            $('#amountPaid').val(totals.total.toFixed(2)); // Default cash to exact total
            
            // Set payment modal elements
            $('.payment-method-card').removeClass('active');
            $('.payment-method-card[data-method="Cash"]').addClass('active');
            paymentMethod = 'Cash';
            
            $('#bankAccountWrapper').hide();
            $('#cardDetailsWrapper').hide();
            $('#chequeDetailsWrapper').hide();
            
            recalculateModalChange();
            
            // Open modal
            $('#paymentModal').modal('show');
        });
        
        // Select Payment Method
        $('.payment-method-card').click(function() {
            $('.payment-method-card').removeClass('active');
            $(this).addClass('active');
            
            paymentMethod = $(this).data('method');
            
            if (paymentMethod === 'Cash') {
                $('#amountPaidLabel').text('Amount Received (Rs.)');
                $('#bankAccountWrapper').hide();
                $('#cardDetailsWrapper').hide();
                $('#chequeDetailsWrapper').hide();
            } else if (paymentMethod === 'Card') {
                $('#amountPaidLabel').text('Amount Paid (Rs.)');
                $('#bankAccountWrapper').show();
                $('#cardDetailsWrapper').show();
                $('#chequeDetailsWrapper').hide();
            } else if (paymentMethod === 'Bank Transfer') {
                $('#amountPaidLabel').text('Amount Paid (Rs.)');
                $('#bankAccountWrapper').show();
                $('#cardDetailsWrapper').hide();
                $('#chequeDetailsWrapper').hide();
            } else if (paymentMethod === 'Cheque') {
                $('#amountPaidLabel').text('Cheque Amount (Rs.)');
                $('#bankAccountWrapper').show();
                $('#cardDetailsWrapper').hide();
                $('#chequeDetailsWrapper').show();
            }
            recalculateModalChange();
        });
        
        // Card Terminal Sync Logic
        let terminalSyncTimer = null;
        $('#btnSyncTerminal').click(function() {
            const terminalIp = $('#inputTerminalIp').val() || '192.168.1.150';
            const totals = calculateTotals();
            
            $('#targetTerminalIp').text(terminalIp);
            $('#terminalStatusTitle').text('Connecting...');
            $('#terminalStatusDesc').text('Establishing connection to card terminal at ' + terminalIp + '...');
            $('#terminalIcon').removeClass().addClass('fa fa-desktop fa-4x text-primary');
            $('#terminalProgressBar').removeClass('progress-bar-success').addClass('progress-bar-striped active');
            
            $('#cardTerminalModal').modal('show');
            
            // REAL INTEGRATION NOTE:
            // To integrate a real terminal over local network:
            // Fetch terminal API endpoint:
            // fetch('http://' + terminalIp + ':8080/payment', {
            //     method: 'POST',
            //     body: JSON.stringify({ amount: totals.total, transType: 'SALE' })
            // })
            // .then(res => res.json())
            // .then(data => { ... handle success ... });

            // Step 1: Connecting (1s)
            terminalSyncTimer = setTimeout(function() {
                $('#terminalStatusTitle').text('Waiting for Card...');
                $('#terminalStatusDesc').html('Request sent for <b>Rs. ' + totals.total.toFixed(2) + '</b>. Please tap, swipe or insert card on the terminal.');
                $('#terminalIcon').removeClass().addClass('fa fa-credit-card fa-4x text-info fa-pulse');
                
                // Step 2: Authorizing (2s)
                terminalSyncTimer = setTimeout(function() {
                    $('#terminalStatusTitle').text('Authorizing...');
                    $('#terminalStatusDesc').text('Processing transaction... Please do not remove card.');
                    $('#terminalIcon').removeClass().addClass('fa fa-refresh fa-4x text-warning fa-spin');
                    
                    // Step 3: Approved (4s)
                    terminalSyncTimer = setTimeout(function() {
                        const approvalCode = 'AUTH-' + Math.floor(100000 + Math.random() * 900000);
                        const brands = ['Visa', 'Mastercard', 'Amex'];
                        const randomBrand = brands[Math.floor(Math.random() * brands.length)];
                        
                        $('#terminalStatusTitle').text('Payment Approved!');
                        $('#terminalStatusDesc').html('Approved! Auth Ref: <b>' + approvalCode + '</b>.<br>Card Type: <b>' + randomBrand + '</b>.');
                        $('#terminalIcon').removeClass().addClass('fa fa-check-circle fa-4x text-success');
                        $('#terminalProgressBar').removeClass('progress-bar-striped active').addClass('progress-bar-success');
                        
                        // Populate POS inputs
                        $('#cardTxnRef').val(approvalCode);
                        $('#cardBrand').val(randomBrand);
                        
                        // Auto-close modal after 1.5s
                        terminalSyncTimer = setTimeout(function() {
                            $('#cardTerminalModal').modal('hide');
                        }, 1500);
                        
                    }, 2000);
                }, 2000);
            }, 1200);
        });
        
        $('#btnCancelTerminalSync').click(function() {
            clearTimeout(terminalSyncTimer);
            $('#cardTerminalModal').modal('hide');
            swal("Sync Cancelled", "Card reader synchronization cancelled by cashier.", "info");
        });
        
        // Recalculate Modal Change
        $('#amountPaid').on('input', function() {
            recalculateModalChange();
        });
        
        function recalculateModalChange() {
            const totals = calculateTotals();
            const paid = parseFloat($('#amountPaid').val()) || 0;
            const diff = paid - totals.total;
            
            const display = $('#changeModalValue');
            const label = $('#changeModalLabel');
            
            if (diff >= 0) {
                label.text('Change Due');
                display.val('Rs. ' + diff.toFixed(2));
                display.css('color', '#2e7d32');
            } else {
                label.text('Amount Due');
                display.val('Rs. ' + Math.abs(diff).toFixed(2));
                display.css('color', '#d32f2f');
            }
        }
        
        // Confirm Checkout Payment (AJAX Submit)
        $('#btnConfirmPayment').click(function() {
            const totals = calculateTotals();
            const customerId = $('#cartCustomerId').val();
            const paidAmount = parseFloat($('#amountPaid').val()) || 0;
            
            if (paidAmount < totals.total) {
                swal("Outstanding Balance", "The payment must cover the exact total balance.", "warning");
                return;
            }
            
            const submitData = {
                form: {
                    CustomerId: customerId,
                    TotalDiscount: totals.discount,
                    TotalSaleAmount: totals.total,
                    PaymentMethod: paymentMethod,
                    PaidAmount: paidAmount,
                    BankID: $('#paymentBankId').val(),
                    ChequeNumber: paymentMethod === 'Card' ? ($('#cardBrand').val() + ' - ' + $('#cardTxnRef').val()) : $('#chequeNumber').val(),
                    ChequeDate: paymentMethod === 'Card' ? dateToYMD(new Date()) : $('#chequeDate').val(),
                    items: cart.map(i => ({
                        ItemID: i.id,
                        Quantity: i.qty,
                        QtyType: 'Pcs',
                        SalePrice: i.price,
                        DiscountAmount: 0 // POS item level discounts can be added later
                    }))
                }
            };
            
            function dateToYMD(date) {
                var d = date.getDate();
                var m = date.getMonth() + 1; //Month from 0-11
                var y = date.getFullYear();
                return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
            }
            
            // Disable button during submit
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            
            $.ajax({
                url: BaseUrl + 'admin/Sales/save_pos',
                type: 'POST',
                data: JSON.stringify(submitData),
                contentType: 'application/json',
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fa fa-check"></i> Finalize & Print');
                    if (response && response.success) {
                        $('#paymentModal').modal('hide');
                        
                        swal({
                            title: "Sale Finalized!",
                            text: response.message,
                            type: "success",
                            showCancelButton: true,
                            confirmButtonClass: "btn-primary",
                            confirmButtonText: "Print Invoice/Receipt",
                            cancelButtonText: "New Sale",
                            closeOnConfirm: false
                        }, function(isConfirm) {
                            if (isConfirm) {
                                // Load standard print layout in iframe modal
                                const invoiceUrl = BaseUrl + 'admin/Sales/print_invoice/' + response.SaleID;
                                $('#receiptFrame').attr('src', invoiceUrl);
                                $('#receiptModal').modal('show');
                                swal.close();
                            } else {
                                // Refresh / Clear cart
                                cart = [];
                                renderCart();
                                window.location.reload(); // Refresh to update stocks
                            }
                        });
                        
                    } else {
                        swal("Checkout Failed", response.message || "An error occurred.", "error");
                    }
                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false).html('<i class="fa fa-check"></i> Finalize & Print');
                    swal("System Error", "Unable to finalize the transaction. Please try again.", "error");
                }
            });
        });
        
        // Print Receipt in iframe
        $('#btnPrintReceipt').click(function() {
            const frame = document.getElementById('receiptFrame');
            frame.contentWindow.focus();
            frame.contentWindow.print();
        });
        
        // Reset view states on Modal hide
        $('#receiptModal').on('hidden.bs.modal', function () {
            cart = [];
            renderCart();
            window.location.reload(); // Reload to refresh inventory levels
        });
    });
</script>

<?php $this->view('includes/footer_end.php'); ?>
