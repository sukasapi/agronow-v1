
<!DOCTYPE html>

<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>

    <title>Login | AgroNow</title>
    <meta name="description" content="Login page">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name=“robots” content="noimageindex,nofollow,nosnippet,noindex">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Fonts -->



    <!--begin::Page Custom Styles(used by this page) -->
    <link href="<?php echo base_url('assets'); ?>/css/pages/general/login/login-1.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="<?php echo base_url('assets'); ?>/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <link href="<?php echo base_url('assets'); ?>/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/aside/dark.css" rel="stylesheet" type="text/css" />        <!--end::Layout Skins -->

    <link rel="shortcut icon" href="<?php echo base_url('assets'); ?>/media/logos/favicon.png" />
</head>
<!-- end::Head -->

<!-- begin::Body -->
<body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >

<!-- begin:: Page -->
<?php $this->load->view(@$page); ?>
<!-- end:: Page -->


<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {"colors":{"state":{"brand":"#5d78ff","dark":"#282a3c","light":"#ffffff","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
</script>
<!-- end::Global Config -->




</body>
<!-- end::Body -->
</html>

