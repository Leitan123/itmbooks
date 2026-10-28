<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<style>
/* Screen styles */
.ledger-controls { display:flex; gap:10px; align-items:flex-end; margin-bottom:15px; flex-wrap:wrap; }
.table-ledger th, .table-ledger td { vertical-align: middle; padding:6px 8px; font-size:13px; }
.paid-row { background: #e9f7ef !important; } /* light green */
.badge-paid { background:#28a745; color:white; padding:2px 6px; border-radius:4px; font-size:11px; }
.print-toolbar { margin-bottom: 12px; display:flex; gap:8px; align-items:center; }

/* Print styles */
@media print {
    body { margin:20px; color:#000; font-family: Arial, sans-serif; }
    .no-print { display:none !important; }

    /* Header */
    #print_header, #print_filters { display:block !important; text-align:center; margin-bottom:15px; }
    #print_header img { height:80px; margin-bottom:5px; }
    #print_header h3 { margin:5px 0; font-size:16px; }
    #print_header p { margin:2px 0; font-size:12px; }

    #print_filters { font-size:12px; margin-bottom:10px; }

    /* Keep table style same as screen */
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd !important; padding:6px 8px; font-size:13px; }
    th { background-color: #f5f5f5; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; }
    .paid-row { background-color: #e9f7ef !important; }

    tfoot th, tfoot td { font-weight:bold; background-color:#f5f5f5; }
}
</style>

<div class="content-page">
  <div class="content">
    <div class="container">

      <div class="row">
        <div class="col-xs-12">
          <div class="page-title-box">
            <h4 class="page-title">Customer Ledger</h4>
            <ol class="breadcrumb p-0">
              <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
              <li class="active">Customer Ledger</li>
            </ol>
          </div>
        </div>
      </div>

      <?php $this->view('includes/notification.php'); ?>

      <div class="row">
        <div class="col-md-12 card-box">

          <!-- Controls -->
          <div class="ledger-controls no-print">
            <div style="min-width:260px">
              <label>Company Name</label>
              <select id="ledger_customer" class="form-control select2">
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $c): ?>
                  <option value="<?= $c->CustomerId ?>"><?= htmlspecialchars(!empty($c->CompanyName) ? $c->CompanyName : $c->CustomerName) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label>From</label>
              <input type="date" id="ledger_from" class="form-control" value="">
            </div>
            <div>
              <label>To</label>
              <input type="date" id="ledger_to" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>

            <div>
              <button id="loadLedger" class="btn btn-primary" style="margin-top:4px">Load Ledger</button>
            </div>

            <div style="margin-left:auto" class="print-toolbar no-print">
              <button id="btnPrint" class="btn btn-default">Print</button>
              <button id="btnExportCsv" class="btn btn-info">Export CSV</button>
            </div>
          </div>

          <!-- Print Header -->
          <?php
          $CI =& get_instance();
          $CI->load->model('Company_model', 'print_company_model');
          $company = $CI->print_company_model->get_company();
          $logo_url = base_url("media/images/logo.png");
          if (!empty($company) && !empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)) {
              $logo_url = base_url('media/img/' . $company->Logo);
          }
          ?>
          <div id="print_header" style="display:none;">
            <img src="<?= $logo_url ?>" alt="Company Logo" style="max-height: 80px; object-fit: contain;">
            <p>Address: <?= !empty($company->Address) ? htmlspecialchars($company->Address) : '#22B Quarry Road, Colombo - 12' ?></p>
            <p>Phone: <?= !empty($company->Telephone) ? htmlspecialchars($company->Telephone) : '+94 112 323 224 / +94 702 662 274' ?></p>
            <hr>
          </div>

          <!-- Ledger Table -->
          <div id="ledger_header" style="margin-bottom:10px"></div>

          <table class="table table-striped table-bordered table-ledger">
            <thead>
              <tr>
                <th style="width:110px">Date</th>
                <th style="width:120px">Ref / Invoice</th>
                <th>Description</th>
                <th style="text-align:right">Debit (Invoice)</th>
                <th style="text-align:right">Credit (Payment)</th>
                <th style="text-align:right">Balance</th>
              </tr>
            </thead>
            <tbody id="ledger_body">
              <tr><td colspan="6" class="text-center">Select customer and click "Load Ledger"</td></tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" style="text-align:right">Opening Balance</th>
                <th style="text-align:right" id="opening_balance">0.00</th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>

    </div>
  </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>
$(document).ready(function(){
    $('select.select2').select2();

    function formatDateOnly(dt){ return dt ? dt.split(' ')[0] : ''; }
    function formatNumber(n){ return parseFloat(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    $('#loadLedger').click(function(){
        var customerId = $('#ledger_customer').val();
        if(!customerId){ alert('Please select a customer'); return; }

        var from = $('#ledger_from').val();
        var to = $('#ledger_to').val();

        $('#ledger_body').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

        $.post("<?= base_url('admin/Customer_payments/get_customer_ledger') ?>", { customerId:customerId, from:from, to:to }, function(res){
            try{ var data=(typeof res==='string')?JSON.parse(res):res; }
            catch(e){ $('#ledger_body').html('<tr><td colspan="6" class="text-danger">Invalid server response</td></tr>'); return; }

            $('#ledger_header').html('<strong>Company Name:</strong> '+$('#ledger_customer option:selected').text()+
                (from?' &nbsp; <strong>From:</strong> '+from:'')+(to?' &nbsp; <strong>To:</strong> '+to:''));

            // Print filters
            $('#print_customer').text($('#ledger_customer option:selected').text());
            $('#print_from').text(from || 'N/A');
            $('#print_to').text(to || 'N/A');

            $('#opening_balance').text(formatNumber(data.opening_balance));

            var rows='';
            if(!data.entries || !data.entries.length){
                rows='<tr><td colspan="6" class="text-center">No transactions found for the selected range.</td></tr>';
                $('#ledger_body').html(rows); return;
            }

            data.entries.forEach(function(e){
                var rowClass=''; var paidBadge='';
                if(e.type==='invoice' && typeof e.due!=='undefined' && parseFloat(e.due)<=0.001){ rowClass='paid-row'; paidBadge=' <span class="badge-paid">PAID</span>'; }
                rows+='<tr class="'+rowClass+'">';
                rows+='<td>'+formatDateOnly(e.date)+'</td>';
                rows+='<td>'+(e.ref)+(e.type==='invoice'?paidBadge:'')+'</td>';
                rows+='<td>'+(e.description||(e.type==='invoice'?'Invoice':'Payment'))+'</td>';
                rows+='<td style="text-align:right">'+(e.debit?formatNumber(e.debit):'')+'</td>';
                rows+='<td style="text-align:right">'+(e.credit?formatNumber(e.credit):'')+'</td>';
                rows+='<td style="text-align:right">'+formatNumber(e.balance)+'</td>';
                rows+='</tr>';
            });

            $('#ledger_body').html(rows);
        }).fail(function(){ $('#ledger_body').html('<tr><td colspan="6" class="text-danger">Failed to load ledger. Please try again.</td></tr>'); });
    });

    // Print
    $('#btnPrint').click(function(){ window.print(); });

    // Export CSV
    $('#btnExportCsv').click(function(){
        var csv=[]; var headers=[];
        $('.table-ledger thead th').each(function(){ headers.push($(this).text().trim()); });
        csv.push(headers.join(','));

        $('#ledger_body tr').each(function(){
            var cols=[];
            $(this).find('td').each(function(){ cols.push('"'+$(this).text().replace(/,/g,'').trim()+'"'); });
            if(cols.length) csv.push(cols.join(','));
        });

        csv.push('""','""','"Opening Balance"','""','""','"'+$('#opening_balance').text().trim()+'"');

        var blob=new Blob([csv.join('\n')],{type:'text/csv;charset=utf-8;'});
        var link=document.createElement('a'); link.setAttribute('href',URL.createObjectURL(blob));
        link.setAttribute('download','customer_ledger_'+$('#ledger_customer').val()+'.csv'); document.body.appendChild(link);
        link.click(); document.body.removeChild(link);
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
