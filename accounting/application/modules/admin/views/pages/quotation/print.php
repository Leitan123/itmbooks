<?php
$CI =& get_instance();
$CI->load->model('Company_model', 'print_company_model');
$company = $CI->print_company_model->get_company();
$logo_url = base_url("media/images/logo.png");
if (!empty($company) && !empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)) {
    $logo_url = base_url('media/img/' . $company->Logo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - <?= !empty($quotation->DomainName) ? htmlspecialchars($quotation->DomainName) . ' - ' : '' ?><?= htmlspecialchars($quotation->QuotationNo) ?></title>
    <!-- Modern Standard Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
            color: #2d3748;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .invoice-box {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.2cm 2cm 2.0cm 2cm;
            margin: 15px auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .invoice-table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 700;
            padding: 10px 12px;
            font-size: 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-table td {
            padding: 12px;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            color: #2d3748;
        }
        .action-bar {
            width: 21cm;
            margin: 20px auto 30px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            box-sizing: border-box;
        }
        .btn {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: inherit;
            transition: all 0.15s ease;
        }
        .btn-print {
            background-color: #3182ce;
            color: white;
        }
        .btn-print:hover {
            background-color: #2b6cb0;
        }
        .btn-close {
            background-color: #edf2f7;
            color: #4a5568;
        }
        .btn-close:hover {
            background-color: #e2e8f0;
        }
        .config-panel {
            width: 21cm;
            margin: 10px auto 0 auto;
            border: 1px solid #bee3f8;
            padding: 20px;
            border-radius: 8px;
            background-color: #ebf8ff;
            box-sizing: border-box;
            font-size: 13px;
        }
        .config-panel label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #2b6cb0;
            margin-bottom: 5px;
        }
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                background-color: #fff;
                padding: 0;
            }
            .invoice-box {
                width: 21cm;
                height: 29.7cm;
                margin: 0;
                border: none;
                box-shadow: none;
                border-radius: 0;
                padding: 1.0cm 1.5cm 1.5cm 1.5cm;
                box-sizing: border-box;
            }
            .config-panel, .action-bar {
                display: none !important;
            }
            .logo-container {
                margin-bottom: 15px !important;
            }
            .logo-container img {
                height: 90px !important;
            }
            #doc-title-heading {
                font-size: 20px !important;
                margin-bottom: 15px !important;
            }
            .details-flex {
                margin-bottom: 15px !important;
            }
            .invoice-table {
                margin-bottom: 15px !important;
            }
            .invoice-table th {
                padding: 6px 10px !important;
                font-size: 11.5px !important;
            }
            .invoice-table td {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }
            .remarks-section {
                margin-top: 15px !important;
                padding: 10px !important;
            }
            .bank-details-section {
                margin-top: 15px !important;
                padding-top: 10px !important;
            }
            .signature-block {
                margin-top: 15px !important;
            }
            .footer-container {
                margin-top: 25px !important;
                padding-top: 10px !important;
            }
        }
    </style>
</head>
<body>

<div class="config-panel">
    <div style="font-weight: bold; font-size: 14px; margin-bottom: 15px; color: #2b6cb0;">
        Document Print Settings (Hidden when printing/saving PDF)
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
        <div>
            <label>Document Title</label>
            <select id="select-doc-type" onchange="updateDocType()" style="width: 100%; padding: 8px; border: 1px solid #90cdf4; border-radius: 4px; font-size: 13px; box-sizing: border-box; font-family: inherit;">
                <option value="Quotation">QUOTATION</option>
                <option value="Invoice">INVOICE</option>
            </select>
        </div>
        <div>
            <label>Deposit To (Bank)</label>
            <?php
            $default_bank_id = 4; // Sampath Bank by default
            ?>
            <select id="select-bank" style="width: 100%; padding: 8px; border: 1px solid #90cdf4; border-radius: 4px; font-size: 13px; box-sizing: border-box; font-family: inherit;">
                <?php foreach ($banks as $bank): ?>
                    <?php
                    $b_name = $bank->BankName;
                    if (strcasecmp($b_name, 'HNB') === 0) {
                        $display_b_name = 'Hatton National Bank';
                    } elseif (strcasecmp($b_name, 'NTB') === 0) {
                        $display_b_name = 'Nation Trust Bank';
                    } else {
                        $display_b_name = $b_name;
                    }
                    ?>
                    <option value="<?= $bank->BankID ?>" <?= $bank->BankID == $default_bank_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($display_b_name) ?> - <?= htmlspecialchars($bank->AccountNumber) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="action-bar">
    <button class="btn btn-print" onclick="window.print()">Print / Save PDF</button>
    <button class="btn btn-close" onclick="window.close()">Close Window</button>
</div>

<div class="invoice-box">
    <!-- Top Content Container -->
    <div>
        <!-- Center Logo -->
        <div class="logo-container" style="text-align: center; margin-bottom: 30px;">
            <img src="<?= $logo_url ?>" alt="Company Logo" style="height: 120px; width: auto; object-fit: contain;">
        </div>

        <!-- Title QUOTATION -->
        <div id="doc-title-heading" style="text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 35px; text-transform: uppercase; letter-spacing: 2px; color: #000;">
            QUOTATION
        </div>

        <!-- Details Flex -->
        <div class="details-flex" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; font-size: 13px; line-height: 1.6;">
            <!-- Left Side: Quotation Info & Client Info -->
            <div style="display: flex; gap: 40px;">
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 4px 15px; color: #2d3748;">
                    <div id="doc-no-label" style="color: #718096; font-weight: 500;">Quotation No:</div>
                    <div><strong style="font-family: Consolas, monospace; font-size: 14px; color: #1a202c;"><?= htmlspecialchars($quotation->QuotationNo) ?></strong></div>
                    
                    <div style="color: #718096; font-weight: 500;">Date:</div>
                    <div style="font-weight: 500; color: #2d3748;"><?= date('F j, Y', strtotime($quotation->QuotationDate)) ?></div>
                    
                    <div style="color: #718096; font-weight: 500;">Validity:</div>
                    <div style="color: #2d3748; font-weight: 500;">30 Days</div>
                </div>

                <div style="color: #2d3748;">
                    <div style="color: #718096; font-weight: 500; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Quotation For:</div>
                    <div style="font-weight: 700; color: #1a202c; font-size: 14px;"><?= htmlspecialchars($quotation->CompanyName) ?></div>
                    <?php if(!empty($quotation->CustomerName)): ?>
                        <div style="font-weight: 500; color: #4a5568;"><?= htmlspecialchars($quotation->CustomerName) ?></div>
                    <?php endif; ?>
                    <?php if(!empty($quotation->Email)): ?>
                        <div style="color: #4a5568;"><?= htmlspecialchars($quotation->Email) ?></div>
                    <?php endif; ?>
                    <?php if(!empty($quotation->Address)): ?>
                        <div style="color: #718096; font-size: 12px; margin-top: 2px; max-width: 250px; text-align: left;"><?= nl2br(htmlspecialchars($quotation->Address)) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side: QR Code -->
            <div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode(base_url('admin/quotation/print/' . $quotation->QuotationID)) ?>" alt="QR Code" style="width: 100px; height: 100px;">
            </div>
        </div>

        <!-- Quotation Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">#</th>
                    <th style="width: 52%;">Description / Service Details</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 15%; text-align: right;">Unit Price (LKR)</th>
                    <th style="width: 15%; text-align: right;">Amount (LKR)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $idx = 1;
                foreach($items as $item): 
                ?>
                <tr>
                    <td style="text-align: center; color: #718096;"><?= $idx++ ?></td>
                    <td>
                        <strong style="color: #1a202c;"><?= htmlspecialchars($item->ItemName) ?></strong>
                    </td>
                    <td style="text-align: center;"><?= $item->Qty ?></td>
                    <td style="text-align: right;"><?= number_format($item->UnitPrice, 2) ?></td>
                    <td style="text-align: right; font-weight: 500;"><?= number_format($item->TotalAmount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals Block -->
        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
            <div style="width: 320px; font-size: 13px; line-height: 2.0; border-top: 1.5px solid #e2e8f0; padding-top: 10px;">
                <div style="display: flex; justify-content: space-between; color: #4a5568;">
                    <span>Sub Total:</span>
                    <span style="font-weight: 500;"><?= number_format($quotation->SubTotal, 2) ?></span>
                </div>
                <?php if($quotation->DiscountAmount > 0): ?>
                    <div style="display: flex; justify-content: space-between; color: #e53e3e;">
                        <span>Discount:</span>
                        <span style="font-weight: 500;">-<?= number_format($quotation->DiscountAmount, 2) ?></span>
                    </div>
                <?php endif; ?>
                <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: #1a202c; border-top: 1px double #cbd5e0; border-bottom: 1px double #cbd5e0; padding: 6px 0; margin-top: 6px;">
                    <span>Grand Total:</span>
                    <span style="color: #2b6cb0;">LKR <?= number_format($quotation->GrandTotal, 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Remarks Section -->
        <?php if(!empty($quotation->Remarks)): ?>
            <div class="remarks-section" style="margin-top: 40px; background-color: #f7fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="font-weight: bold; font-size: 11px; text-transform: uppercase; color: #718096; margin-bottom: 6px; letter-spacing: 0.5px;">Description:</div>
                <div style="font-size: 12.5px; line-height: 1.5; color: #4a5568; white-space: pre-wrap;"><?= htmlspecialchars($quotation->Remarks) ?></div>
            </div>
        <?php endif; ?>

        <!-- Bank Details Section -->
        <div class="bank-details-section" style="margin-top: 25px; font-size: 13px; line-height: 1.6; border-top: 1.5px solid #e2e8f0; padding-top: 15px;">
            <div style="font-weight: bold; font-size: 11px; text-transform: uppercase; color: #718096; margin-bottom: 6px; letter-spacing: 0.5px;">Bank Details:</div>
            <div>Drawn cheque favour in <strong id="lbl-cheque-favour">"IT MARTX"</strong></div>
            <div style="margin-top: 8px;">OR Deposit to:</div>
            <div id="lbl-bank-details" style="font-weight: bold; margin-top: 4px;">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Bottom Footer Container -->
    <div>
        <!-- Bottom Signature Block -->
        <div class="signature-block" style="margin-top: 30px; display: flex; flex-direction: column; align-items: flex-start; font-size: 12px; color: #4a5568;">
            <div style="width: 220px; display: flex; justify-content: center; height: 60px; margin-bottom: 2px;">
                <img src="<?= base_url('media/images/signature_transparent.png') ?>" alt="Signature" style="height: 65px; width: auto; mix-blend-mode: multiply;">
            </div>
            <div style="text-align: center; width: 220px; border-top: 1px solid #cbd5e0; padding-top: 8px;">
                <strong style="color: #4a5568;">IT MARTX (PVT) LTD</strong>
                <div style="margin-top: 2px; font-size: 11px;">Authorized Signature</div>
            </div>
        </div>

        <!-- Bottom Footer Container (Identical to subscription notice) -->
        <div class="footer-container" style="border-top: 2px solid #3182ce; padding-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; font-size: 11px; color: #4a5568; line-height: 1.6; position: relative; margin-top: 50px;">
            <div style="width: 65%; min-width: 0; white-space: nowrap;">
                <strong>Address:</strong> 200, 4th Floor, George R De Silva Mawatha, Colombo 13, Sri Lanka.<br>
                <strong>Telephone:</strong> +94 113 670 717 / +94 701 449 779<br>
                <strong>Email:</strong> info@itmartx.com
            </div>
            <div style="position: absolute; left: 50%; transform: translateX(-50%); bottom: 5px; font-weight: bold; font-size: 12px; color: #2b6cb0;">
                www.itmartx.com
            </div>
            <div style="width: 35%; text-align: right; min-width: 0;">
                <!-- Zoho Authorized Partner Logo -->
                <svg width="152" height="80" viewBox="0 0 190 100" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;">
                    <!-- Background Container -->
                    <rect width="190" height="100" rx="8" fill="#f4f5f7" />
                    <!-- Blue Right Side -->
                    <path d="M 98 0 L 182 0 A 8 8 0 0 1 190 8 L 190 92 A 8 8 0 0 1 182 100 L 98 100 Z" fill="#1565C0" />
                    
                    <!-- Left Side: Zoho Interlocking Squares -->
                    <rect x="9" y="24" width="22" height="22" rx="4" fill="none" stroke="#e53935" stroke-width="4.5" transform="rotate(12 20 35)" />
                    <rect x="29" y="24" width="22" height="22" rx="4" fill="none" stroke="#43a047" stroke-width="4.5" transform="rotate(-15 40 35)" />
                    <rect x="49" y="24" width="22" height="22" rx="4" fill="none" stroke="#1e88e5" stroke-width="4.5" transform="rotate(18 60 35)" />
                    <rect x="69" y="24" width="22" height="22" rx="4" fill="none" stroke="#fbc02d" stroke-width="4.5" transform="rotate(-10 80 35)" />
                    
                    <!-- ZOHO Text -->
                    <text x="50" y="76" font-family="'Inter', -apple-system, BlinkMacSystemFont, sans-serif" font-weight="800" font-size="11" fill="#000000" letter-spacing="3" text-anchor="middle">ZOHO</text>
                    
                    <!-- Right Side: Authorized Partner Text -->
                    <text x="112" y="34" font-family="'Inter', -apple-system, BlinkMacSystemFont, sans-serif" font-weight="700" font-size="17" fill="#ffffff">Zoho</text>
                    <text x="112" y="55" font-family="'Inter', -apple-system, BlinkMacSystemFont, sans-serif" font-weight="500" font-size="12" fill="#ffffff">Authorized</text>
                    <text x="112" y="74" font-family="'Inter', -apple-system, BlinkMacSystemFont, sans-serif" font-weight="500" font-size="12" fill="#ffffff">Partner</text>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
function updateDocType() {
    var docType = document.getElementById('select-doc-type').value;
    var titleHeading = document.getElementById('doc-title-heading');
    var noLabel = document.getElementById('doc-no-label');
    var domain = "<?= !empty($quotation->DomainName) ? htmlspecialchars($quotation->DomainName) : '' ?>";
    var quoteNo = "<?= htmlspecialchars($quotation->QuotationNo) ?>";

    if (docType === 'Invoice') {
        titleHeading.innerText = 'INVOICE';
        noLabel.innerText = 'Invoice No:';
        document.title = 'Invoice - ' + (domain ? domain + ' - ' : '') + quoteNo;
    } else {
        titleHeading.innerText = 'QUOTATION';
        noLabel.innerText = 'Quotation No:';
        document.title = 'Quotation - ' + (domain ? domain + ' - ' : '') + quoteNo;
    }
}

$(document).ready(function() {
    var banksData = {
        <?php foreach ($banks as $bank): ?>
            "<?= $bank->BankID ?>": {
                "BankName": "<?= htmlspecialchars($bank->BankName) ?>",
                "AccountName": "<?= htmlspecialchars($bank->AccountName) ?>",
                "AccountNumber": "<?= htmlspecialchars($bank->AccountNumber) ?>",
                "Branch": "<?= htmlspecialchars($bank->Branch) ?>"
            },
        <?php endforeach; ?>
    };

    function updateBankDetails() {
        var bankId = $('#select-bank').val();
        var bank = banksData[bankId];
        if (bank) {
            var bName = bank.BankName;
            var displayBankName = bName;
            if (bName.toUpperCase() === 'HNB') {
                displayBankName = 'Hatton National Bank';
            } else if (bName.toUpperCase() === 'NTB') {
                displayBankName = 'Nation Trust Bank';
            }

            var holderName = 'IT MARTX (PVT) LTD';
            if (bName.toLowerCase().indexOf('sampath') !== -1) {
                holderName = 'IT MARTX';
            }

            $('#lbl-bank-details').html(
                holderName + '<br>' +
                bank.AccountNumber + '<br>' +
                displayBankName + '<br>' +
                bank.Branch + ' Branch'
            );

            // Update drawn cheque favour label
            $('#lbl-cheque-favour').text('"' + holderName + '"');
        }
    }

    $('#select-bank').on('change', updateBankDetails);
    
    // Initial call
    updateBankDetails();
});
</script>
</body>
</html>
