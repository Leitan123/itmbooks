<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="IT MARTX">

    <!-- App Favicon -->
    <link href="<?= base_url() ?>media/images/icon.png" rel="icon" type="image/png" sizes="16x16">

    <!-- App title -->
    <?php
    $CI =& get_instance();
    $CI->load->model('Company_model', 'header_company_model');
    $company = $CI->header_company_model->get_company();
    $title_prefix = !empty($company) && !empty($company->CompanyName) ? $company->CompanyName . ' | ' : '';
    $theme_color = !empty($company) && !empty($company->ThemeColor) ? $company->ThemeColor : '#2f3d4f';
    $theme_color_hover = $theme_color . 'cc';
    ?>
    <title><?= htmlspecialchars($title_prefix) ?><?= TITLE ?> </title>

    <!-- App CSS -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css"/>
    <style>
        /* Dynamic Theme Color override for account/login screen */
        .btn-success {
            background-color: <?= $theme_color ?> !important;
            border-color: <?= $theme_color ?> !important;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active {
            background-color: <?= $theme_color_hover ?> !important;
            border-color: <?= $theme_color_hover ?> !important;
        }
    </style>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url('assets/js/modernizr.min.js') ?>"></script>

</head>


<body>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
