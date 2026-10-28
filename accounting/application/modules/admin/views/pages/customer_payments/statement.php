<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<style>
/* Screen styles */
.table-soa th, .table-soa td { vertical-align: middle; padding:6px 8px; font-size:13px; }
.table-soa tbody tr:nth-child(odd) { background:#f9f9f9; }
.paid-row { background:#e9f7ef !important; }
.label-success { background:#28a745; color:#fff; padding:2px 5px; border-radius:4px; font-size:11px; }

/* Print styles */
@media print {
    body { font-family: Arial, sans-serif; margin:20px; color:#000; }
    .no-print { display:none !important; }

    #print_header, #print_filters { display:block !important; text-align:center; margin-bottom:15px; }
    #print_header img { height:80px; margin-bottom:5px; }
    #print_header h3 { margin:5px 0; font-size:16px; }
    #print_header p { margin:2px 0; font-size:12px; }

    #print_filters { font-size:12px; margin-bottom:10px; }

    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd !important; padding:6px 8px; font-size:13px; }
    th { background-color: #f5f5f5; }
    .table-soa tbody tr:nth-child(odd) { background:#f9f9f9; }
    .paid-row { background:#e9f7ef !important; }
    tfoot th, tfoot td { font-weight:bold; background-color:#f5f5f5; }
}
</style>

<div class="content-page">
  <div class="content">
    <div class="container">

      <div class="row">
        <div class="col-xs-12">
          <div class="page-title-box">
            <h4 class="page-title">Customer Statement of Accounts</h4>
            <ol class="breadcrumb p-0">
              <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
              <li class="active">Statement</li>
            </ol>
          </div>
        </div>
      </div>

      <?php $this->view('includes/notification.php'); ?>

      <div class="row">
        <div class="col-md-12">
          <div class="card-box">

            <!-- Controls -->
            <form id="soaForm" class="form-inline no-print" onsubmit="return false;">
              <div class="form-group">
                <label>Company Name</label>
                <select id="soa_customer" class="form-control select2" style="width:220px;">
                  <option value="">-- Select Customer --</option>
                  <?php foreach ($customers as $c): ?>
                    <option value="<?= $c->CustomerId ?>"><?= htmlspecialchars(!empty($c->CompanyName) ? $c->CompanyName : $c->CustomerName) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label>From</label>
                <input type="date" id="soa_from" class="form-control">
              </div>

              <div class="form-group">
                <label>To</label>
                <input type="date" id="soa_to" class="form-control">
              </div>

              <div class="form-group">
                <label><input type="checkbox" id="soa_show_all" checked> Show All Invoices</label>
              </div>

              <button id="soa_load" class="btn btn-primary">Load Statement</button>
              <button id="soa_print" class="btn btn-default" style="margin-left:10px; display:none;">Print</button>
            </form>

            <hr>

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

            <!-- Print Filters -->
            <div id="print_filters" style="display:none;">
              <strong>Company Name:</strong> <span id="print_customer"></span> |
              <strong>From:</strong> <span id="print_from"></span> |
              <strong>To:</strong> <span id="print_to"></span>
              <hr>
            </div>

            <!-- Statement Table -->
            <div id="soa_result" style="margin-top:15px;">
              <div><strong>Opening Balance:</strong> <span id="soa_opening">0.00</span></div>

              <table class="table table-bordered table-soa" id="soa_table" style="margin-top:10px;">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Ref</th>
                    <th>Description</th>
                    <th class="text-right">Debit (Invoice)</th>
                    <th class="text-right">Credit (Payment)</th>
                    <th class="text-right">Balance</th>
                    <th>Note</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>
$(document).ready(function() {
    $('select.select2').select2();

    function formatDateOnly(dt){ return dt ? dt.split(' ')[0] : ''; }
    function formatNumber(n){ return parseFloat(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    $('#soa_load').click(function() {
        var customerId = $('#soa_customer').val();
        if (!customerId) { alert('Please select a customer'); return; }

        var from = $('#soa_from').val();
        var to = $('#soa_to').val();
        var show_all = $('#soa_show_all').is(':checked') ? '1' : '0';

        $.ajax({
            url: '<?= base_url('admin/Customer_payments/get_statement_data') ?>',
            data: { customerId: customerId, from: from, to: to, show_all: show_all },
            dataType: 'json',
            success: function(resp) {
                if (resp.error) { alert(resp.error); return; }

                $('#soa_opening').text(formatNumber(resp.opening_balance));

                var rows='';
                var entries=resp.entries;
                for(var i=0;i<entries.length;i++){
                    var e=entries[i];
                    var date=formatDateOnly(e.date);
                    var debit=e.debit?formatNumber(e.debit):'';
                    var credit=e.credit?formatNumber(e.credit):'';
                    var balance=e.balance?formatNumber(e.balance):'';
                    var note='';

                    if(e.type=='invoice' && typeof e.due!='undefined' && parseFloat(e.due)<=0){
                        note='<span class="label label-success">PAID</span>';
                        rows+='<tr class="paid-row">';
                    } else if(e.type=='payment'){
                        note=e.payment_method?e.payment_method:'';
                        rows+='<tr>';
                    } else{
                        rows+='<tr>';
                    }

                    rows+='<td>'+date+'</td>';
                    rows+='<td>'+e.ref+'</td>';
                    rows+='<td>'+e.description+'</td>';
                    rows+='<td class="text-right">'+debit+'</td>';
                    rows+='<td class="text-right">'+credit+'</td>';
                    rows+='<td class="text-right">'+balance+'</td>';
                    rows+='<td>'+note+'</td>';
                    rows+='</tr>';
                }
                $('#soa_table tbody').html(rows);

                // Show print button
                $('#print_customer').text($('#soa_customer option:selected').text());
                $('#print_from').text(from || 'N/A');
                $('#print_to').text(to || 'N/A');
                $('#soa_print').show();
            },
            error: function(xhr, st, err){ alert('Failed to load statement: '+err); }
        });
    });

    $('#soa_print').click(function(){
        window.print();
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
