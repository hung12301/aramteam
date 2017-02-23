<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?= isset($data['title']) ? $data['title'] . ' | ' : '' ?>MTVip1.COM | Hệ thống Facebook tự động</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= SITE_URL ?>/public/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= SITE_URL ?>/public/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?= SITE_URL ?>/public/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="<?= SITE_URL ?>/public/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?= SITE_URL ?>/public/css/style.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="<?= SITE_URL ?>/public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Sweet Alert Css -->
    <link href="<?= SITE_URL ?>/public/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Bootstrap Spinner Css -->
    <link href="<?= SITE_URL ?>/public/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- JQuery DataTable Css -->
    <link href="<?= SITE_URL ?>/public/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    
    <!-- Wait Me CSS -->
    <link href="<?= SITE_URL ?>/public/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= SITE_URL ?>/public/css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="<?= isset($data['body-theme']) ? $data['body-theme'] : 'theme-red' ?>">