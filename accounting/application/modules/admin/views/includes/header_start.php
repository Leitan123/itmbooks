<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="IT MARTX">

    <!-- App Favicon -->

    <!-- App title -->
    <?php
    $CI =& get_instance();
    $CI->load->model('Company_model', 'header_company_model');
    $company = $CI->header_company_model->get_company();
    $title_prefix = !empty($company) && !empty($company->CompanyName) ? $company->CompanyName . ' | ' : '';
    ?>
    <title><?= htmlspecialchars($title_prefix) ?><?= TITLE ?> </title>

    <link href="<?= base_url() ?>media/images/icon.png" rel="icon" type="image/png" sizes="16x16">
    <!-- Switchery css -->
    <link href="<?= base_url('assets/plugins/switchery/switchery.min.css') ?>" rel="stylesheet"/>

<!--=========================================== Date Picker ========================================================================-->
    <link href="<?= base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

<!--    ======================================================================================================-->


    <script type="text/javascript">
        var BaseUrl = "<?=base_url()?>";
        var Api = "<?=base_url()?>api/";
    </script>

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('7b98d4665f12dc313bbb', {
            cluster: 'ap2',
            encrypted: true
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my_event', function(data) {
//            document.getElementById('event').innerHTML = data.message;
//            alert(data.message);
            swal("Order Alert",data.message,"success" );
        });
    </script>
