<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Agronow - <?=$title?></title>
    <meta name="description" content="Agronow PWA">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="<?=PATH_ASSETS?>icon/main_icon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=PATH_ASSETS?>icon/main_icon.png">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/style.css">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/custom.css?v=1.1">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/font-awesome.min.css">
	
	<link rel="stylesheet" href="<?=PATH_ASSETS?>plugins/datatables/datatables.bundle.css">
	<link rel="stylesheet" href="<?=PATH_ASSETS?>plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>plugins/sweetalert2/sweetalert2.min.css">

     <!-- Jquery -->
     <script src="<?=PATH_ASSETS?>js/lib/jquery-3.4.1.min.js"></script>
    <?php
        foreach($this->customcss as $c){
            $this->load->view('css/'.$c);
        }
    ?>
	
	<style>
	#blur_note {z-index:1000;background:#E5E7E9;color:#000;border:3px solid #E74C3C;position:fixed;top:50%;left:50%;-webkit-transform: translate(-50%,-50%);transform:translate(-50%,-50%);padding:1.25em;width:300px;}
	</style>

<!--    <link rel="manifest" href="/__manifest.json">-->
</head>

<body style="background-color: black">
    <!-- loading -->
    <div class="loading" style="display: none">Loading&#8230;</div>
    <!-- loading -->

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->