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
    <title>Invoice - <?= htmlspecialchars($customer->DomainName ?: 'No Domain') ?></title>
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
        .config-panel input {
            width: 100%;
            padding: 8px;
            border: 1px solid #90cdf4;
            border-radius: 4px;
            font-size: 13px;
            box-sizing: border-box;
            font-family: inherit;
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
        .btn-save-print {
            background-color: #38a169;
            color: white;
        }
        .btn-save-print:hover {
            background-color: #2f855a;
        }
        .btn-close {
            background-color: #edf2f7;
            color: #4a5568;
        }
        .btn-close:hover {
            background-color: #e2e8f0;
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
                padding: 1.2cm 2cm 2.0cm 2cm;
                box-sizing: border-box;
            }
            .config-panel, .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="config-panel">
    <div style="font-weight: bold; font-size: 14px; margin-bottom: 15px; color: #2b6cb0;">
        Invoice Configuration (Hidden when printing/saving PDF)
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px;">
        <div>
            <label>Invoice Number</label>
            <input type="text" id="input-invoice-no" value="<?= isset($saved_notice) ? htmlspecialchars($saved_notice->InvoiceNo) : 'IJH' . date('dmy') . '-H' ?>">
        </div>
        <div>
            <label>Invoice Date</label>
            <input type="text" id="input-invoice-date" value="<?= date('F j, Y') ?>">
        </div>
        <div>
            <label>Select Domain</label>
            <select id="select-domain" style="width: 100%; padding: 8px; border: 1px solid #90cdf4; border-radius: 4px; font-size: 13px; box-sizing: border-box; font-family: inherit;">
                <?php 
                $primary_val = 'www.' . ltrim(htmlspecialchars($customer->DomainName ?: 'domain.com'), 'w.');
                $selected_domain = isset($saved_notice) ? htmlspecialchars($saved_notice->ToDomain) : $primary_val;
                ?>
                <option value="<?= $primary_val ?>" <?= $selected_domain == $primary_val ? 'selected' : '' ?>><?= $primary_val ?> (Primary)</option>
                <?php if (isset($customer->AdditionalDomainsDetail) && is_array($customer->AdditionalDomainsDetail)): ?>
                    <?php foreach($customer->AdditionalDomainsDetail as $domain): ?>
                        <?php $domain_val = 'www.' . ltrim(htmlspecialchars($domain->DomainName), 'w.'); ?>
                        <option value="<?= $domain_val ?>" <?= $selected_domain == $domain_val ? 'selected' : '' ?>><?= $domain_val ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <label>To (Domain)</label>
            <input type="text" id="input-to-domain" value="<?= isset($saved_notice) ? htmlspecialchars($saved_notice->ToDomain) : 'www.' . ltrim(htmlspecialchars($customer->DomainName ?: 'domain.com'), 'w.') ?>">
        </div>
        <div>
            <label>Detail Description</label>
            <input type="text" id="input-detail-desc" value="<?= isset($saved_notice) ? htmlspecialchars($saved_notice->DetailDesc) : 'Renewal Domain + Hosting' ?>">
        </div>
        <div>
            <label>Renewal Period</label>
            <input type="text" id="input-period" value="<?= isset($saved_notice) ? htmlspecialchars($saved_notice->RenewalPeriod) : date('Y') . ' to ' . (date('Y') + 1) ?>">
        </div>
        <div>
            <label>Amount (LKR)</label>
            <input type="number" id="input-amount" value="<?= isset($saved_notice) ? number_format($saved_notice->Amount, 2, '.', '') : number_format($default_amount, 2, '.', '') ?>" step="100">
        </div>
        <div>
            <label>Deposit To (Bank)</label>
            <?php
            $default_bank_id = 4; // Sampath Bank by default
            if (isset($saved_notice) && isset($saved_notice->BankID)) {
                $default_bank_id = $saved_notice->BankID;
            }
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

<div class="invoice-box">
    <!-- Top Content Container -->
    <div>
        <!-- Center Logo -->
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="<?= $logo_url ?>" alt="Company Logo" style="height: 140px; width: auto; object-fit: contain;">
        </div>

        <!-- Title INVOICE -->
        <div style="text-align: center; font-size: 26px; font-weight: bold; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; color: #000;">
            INVOICE
        </div>

        <!-- Details Flex -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px;">
            <div style="display: grid; grid-template-columns: auto 1fr; gap: 6px 15px; font-size: 13px; line-height: 1.5; color: #2d3748;">
                <div style="color: #718096; font-weight: 500;">Invoice No:</div>
                <div><strong id="lbl-invoice-no" style="font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 14px; color: #1a202c;">IJH010626-H</strong></div>
                <div style="color: #718096; font-weight: 500;">Date:</div>
                <div id="lbl-date" style="font-weight: 500; color: #2d3748;">June 1, 2026</div>
                <div style="color: #718096; font-weight: 500; margin-top: 8px;">To:</div>
                <div style="margin-top: 8px;"><span id="lbl-to" style="font-weight: 700; color: #1a202c; font-size: 14px;">www.jayatha.lk</span></div>
            </div>
            <div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode(base_url('admin/customer/subscription_notice/' . $customer->CustomerId)) ?>" alt="QR Code" style="width: 100px; height: 100px;">
            </div>
        </div>

        <!-- Details Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"></th>
                    <th>DETAIL</th>
                    <th style="width: 100px; text-align: center;">UNIT</th>
                    <th style="width: 80px; text-align: center;">QTY</th>
                    <th style="width: 150px; text-align: right;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: bold;">01</td>
                    <td style="line-height: 1.6; vertical-align: top;">
                        <strong id="tbl-detail-desc">Renewal Domain + Hosting</strong><br>
                        <span id="tbl-domain">www.jayatha.lk</span><br>
                        <br>
                        <span id="tbl-period">2026 to 2027</span>
                    </td>
                    <td style="text-align: center; vertical-align: top; color: #718096;">-</td>
                    <td style="text-align: center; vertical-align: top; color: #718096;">-</td>
                    <td style="text-align: right; vertical-align: top; font-weight: bold; font-size: 14px;" id="tbl-item-total">24,400.00</td>
                </tr>
                <tr style="border-top: 2px solid #ccc; font-weight: bold;">
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                    <td style="border: none; text-align: center; color: #718096; font-weight: normal;">----------</td>
                    <td style="border: none; text-align: center; color: #718096; font-weight: normal;">----------</td>
                    <td style="text-align: right; font-size: 15px; border-bottom: 4px double #000; font-weight: bold;" id="tbl-grand-total">24,400.00</td>
                </tr>
            </tbody>
        </table>

        <!-- Wordings & Bank Info -->
        <?php
        $display_bank = null;
        foreach ($banks as $b) {
            if ($b->BankID == $default_bank_id) {
                $display_bank = $b;
                break;
            }
        }
        if (!$display_bank && !empty($banks)) {
            $display_bank = $banks[0];
        }

        $holder_name = 'IT MARTX';
        $display_bank_name = '';
        if ($display_bank) {
            $b_name = $display_bank->BankName;
            if (strcasecmp($b_name, 'HNB') === 0) {
                $display_bank_name = 'Hatton National Bank';
            } elseif (strcasecmp($b_name, 'NTB') === 0) {
                $display_bank_name = 'Nation Trust Bank';
            } else {
                $display_bank_name = $b_name;
            }

            if (stripos($b_name, 'Sampath') !== false) {
                $holder_name = 'IT MARTX';
            } else {
                $holder_name = 'IT MARTX (PVT) LTD';
            }
        }
        ?>
        <div style="font-size: 13px; margin-bottom: 25px; line-height: 1.6;">
            <div>Total invoice amount in rupees, <strong id="lbl-total-words">twenty four thousand four hundred</strong> only.</div>
            <br>
            <div>Drawn cheque favour in <strong id="lbl-cheque-favour">"<?= htmlspecialchars($holder_name) ?>"</strong></div>
            <br>
            <div>OR Deposit to:</div>
            <div id="lbl-bank-details" style="font-weight: bold; margin-top: 5px;">
                <?php if ($display_bank): ?>
                    <?= htmlspecialchars($holder_name) ?><br>
                    <?= htmlspecialchars($display_bank->AccountNumber) ?><br>
                    <?= htmlspecialchars($display_bank_name) ?><br>
                    <?= htmlspecialchars($display_bank->Branch) ?> Branch
                <?php else: ?>
                    IT MARTX<br>
                    103714019354<br>
                    Sampath Bank<br>
                    Kotahena Branch
                <?php endif; ?>
            </div>

            <!-- Terms & Conditions -->
<div style="margin-top: 18px; border-top: 1px solid #d1d5db; padding-top: 10px;">
    <strong style="font-size: 13px;">Terms &amp; Conditions:</strong>
    <div style="margin-top: 5px; font-size: 12px; color: #4a5568;">
        • This invoice excludes the Annual Maintenance Contract (AMC).
    </div>
</div>
        </div>
    </div>

    <!-- Bottom Footer Container -->
    <div style="border-top: 2px solid #3182ce; padding-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; font-size: 11px; color: #4a5568; line-height: 1.6; position: relative;">
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
                <!-- Red Square -->
                <rect x="9" y="24" width="22" height="22" rx="4" fill="none" stroke="#e53935" stroke-width="4.5" transform="rotate(12 20 35)" />
                <!-- Green Square -->
                <rect x="29" y="24" width="22" height="22" rx="4" fill="none" stroke="#43a047" stroke-width="4.5" transform="rotate(-15 40 35)" />
                <!-- Blue Square -->
                <rect x="49" y="24" width="22" height="22" rx="4" fill="none" stroke="#1e88e5" stroke-width="4.5" transform="rotate(18 60 35)" />
                <!-- Yellow Square -->
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

<div class="action-bar">
    <button onclick="window.close()" class="btn btn-close">Close</button>
    <button onclick="saveAndPrint(false)" class="btn btn-print">Print / Save PDF</button>
    <button onclick="saveAndPrint(true)" class="btn btn-save-print">Save invoice & Print</button>
</div>

<script type="text/javascript">
    var isSaving = false;
    function saveAndPrint(saveInvoice) {
        if (isSaving) return;
        
        var invoiceNo = $('#input-invoice-no').val();
        var date = $('#input-invoice-date').val();
        var toDomain = $('#input-to-domain').val();
        var detailDesc = $('#input-detail-desc').val();
        var period = $('#input-period').val();
        var amount = $('#input-amount').val();

        isSaving = true;
        // Disable buttons to avoid multiple clicks
        $('.btn-print, .btn-save-print').prop('disabled', true);

        $.ajax({
            url: '<?= base_url("admin/customer/save_subscription_notice") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                customer_id: '<?= $customer->CustomerId ?>',
                invoice_no: invoiceNo,
                invoice_date: date,
                to_domain: toDomain,
                detail_desc: detailDesc,
                period: period,
                amount: amount,
                bank_id: $('#select-bank').val(),
                save_invoice: saveInvoice ? 1 : 0
            },
            success: function(response) {
                if (response.status === 'success') {
                    if (saveInvoice) {
                        // Replace the save button with a disabled one to prevent any further saves
                        $('.btn-save-print').replaceWith('<button class="btn btn-save-print" disabled style="background-color: #cbd5e0; cursor: not-allowed; color: #718096;" title="Renewal invoice is already sent and pending payment.">Invoice Already Saved</button>');
                    }
                    $('.btn-print').prop('disabled', false);
                    isSaving = false;
                    window.print();
                } else {
                    $('.btn-print, .btn-save-print').prop('disabled', false);
                    isSaving = false;
                    alert('Error saving records: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                $('.btn-print, .btn-save-print').prop('disabled', false);
                isSaving = false;
                alert('Network error. Failed to save records.');
            }
        });
    }

    function numberToWords(amount) {
        var words = '';
        var num = Math.floor(amount);
        
        if (num === 0) {
            return 'Zero';
        }

        var units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
                     'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tens_list = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function convertLessThanThousand(n) {
            var str = '';
            if (n >= 100) {
                str += units[Math.floor(n / 100)] + ' hundred ';
                n %= 100;
            }
            if (n >= 20) {
                str += tens_list[Math.floor(n / 10)] + ' ';
                n %= 10;
            }
            if (n > 0) {
                str += units[n] + ' ';
            }
            return str.trim();
        }

        if (num >= 1000000) {
            words += convertLessThanThousand(Math.floor(num / 1000000)) + ' million ';
            num %= 1000000;
        }
        if (num >= 1000) {
            words += convertLessThanThousand(Math.floor(num / 1000)) + ' thousand ';
            num %= 1000;
        }
        if (num > 0) {
            words += convertLessThanThousand(num);
        }
        
        var result = words.trim();
        return result.charAt(0).toLowerCase() + result.slice(1);
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

        function updateInvoice() {
            var invoiceNo = $('#input-invoice-no').val();
            var date = $('#input-invoice-date').val();
            var toDomain = $('#input-to-domain').val();
            var detailDesc = $('#input-detail-desc').val();
            var period = $('#input-period').val();
            var amount = parseFloat($('#input-amount').val()) || 0;

            $('#lbl-invoice-no').text(invoiceNo);
            $('#lbl-date').text(date);
            $('#lbl-to').text(toDomain);
            $('#tbl-domain').text(toDomain);
            $('#tbl-detail-desc').text(detailDesc);
            $('#tbl-period').text(period);
            
            var formattedAmount = amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            $('#tbl-item-total').text(formattedAmount);
            $('#tbl-grand-total').text(formattedAmount);

            var words = numberToWords(amount);
            $('#lbl-total-words').text(words.toLowerCase());

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

        $('#input-invoice-no, #input-invoice-date, #input-to-domain, #input-detail-desc, #input-period, #input-amount, #select-bank').on('input change', updateInvoice);

        var domainsData = {
            "<?= strtolower(ltrim(htmlspecialchars($customer->DomainName ?: 'domain.com'), 'w.')) ?>": {
                "DomainName": "<?= htmlspecialchars($customer->DomainName) ?>",
                "ExpiryDate": "<?= !empty($customer->ServiceExpiry) && $customer->ServiceExpiry != '0000-00-00' ? date('F j, Y', strtotime($customer->ServiceExpiry)) : '' ?>",
                "AnnualFee": "<?= number_format($default_amount, 2, '.', '') ?>",
                "Period": "<?= !empty($customer->ServiceExpiry) && $customer->ServiceExpiry != '0000-00-00' ? date('Y', strtotime($customer->ServiceExpiry)) . ' to ' . (date('Y', strtotime($customer->ServiceExpiry)) + 1) : date('Y') . ' to ' . (date('Y') + 1) ?>"
            }
            <?php if (isset($customer->AdditionalDomainsDetail) && is_array($customer->AdditionalDomainsDetail)): ?>
                <?php foreach ($customer->AdditionalDomainsDetail as $d): ?>,
                "<?= strtolower(ltrim(htmlspecialchars($d->DomainName), 'w.')) ?>": {
                    "DomainName": "<?= htmlspecialchars($d->DomainName) ?>",
                    "ExpiryDate": "<?= !empty($d->ExpiryDate) && $d->ExpiryDate != '0000-00-00' ? date('F j, Y', strtotime($d->ExpiryDate)) : '' ?>",
                    "AnnualFee": "<?= number_format($d->AnnualFee, 2, '.', '') ?>",
                    "Period": "<?= !empty($d->ExpiryDate) && $d->ExpiryDate != '0000-00-00' ? date('Y', strtotime($d->ExpiryDate)) . ' to ' . (date('Y', strtotime($d->ExpiryDate)) + 1) : date('Y') . ' to ' . (date('Y') + 1) ?>"
                }
                <?php endforeach; ?>
            <?php endif; ?>
        };

        var unpaidInvoiceDomains = <?= json_encode($customer->UnpaidInvoiceDomains) ?>;

        $('#select-domain').on('change', function() {
            var selectedVal = $(this).val();
            var domainKey = selectedVal.replace(/^www\./i, '').toLowerCase();
            var data = domainsData[domainKey] || domainsData["<?= strtolower(ltrim(htmlspecialchars($customer->DomainName ?: 'domain.com'), 'w.')) ?>"];
            
            $('#input-to-domain').val(selectedVal);
            
            if (data.AnnualFee && parseFloat(data.AnnualFee) > 0) {
                $('#input-amount').val(parseFloat(data.AnnualFee).toFixed(2));
            }
            if (data.Period) {
                $('#input-period').val(data.Period);
            }
            
            // Check if this domain has an unpaid invoice
            if (unpaidInvoiceDomains.indexOf(domainKey) !== -1) {
                $('.btn-save-print').prop('disabled', true).css({
                    'background-color': '#cbd5e0',
                    'cursor': 'not-allowed',
                    'color': '#718096'
                }).text('Invoice Already Saved').attr('title', 'Renewal invoice is already sent and pending payment.');
            } else {
                $('.btn-save-print').prop('disabled', false).css({
                    'background-color': '',
                    'cursor': '',
                    'color': ''
                }).text('Save invoice & Print').removeAttr('title');
            }
            
            $('#input-to-domain, #input-invoice-date, #input-amount, #input-period').trigger('change');
        });
        
        // Initial call
        updateInvoice();
        $('#select-domain').trigger('change');
    });
</script>

</body>
</html>
