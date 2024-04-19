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
    <title>Agronow - Forgot Password</title>
    <meta name="description" content="Agronow PWA">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="<?=PATH_ASSETS?>icon/main_icon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=PATH_ASSETS?>icon/main_icon.png">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/style.css">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/custom.css">
<!--    <link rel="manifest" href="/__manifest.json">-->
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0 pb-0">

        <div class="login-form mt-0">
            <div class="login-header">
                <img src="<?=$url_img1?>" style="width:100%;">
                <div <?=$css_logo?>>
                    <div class="section">
                        <img src="<?=$url_img2?>" alt="image" class="form-image">
                    </div>
                </div>
            </div>
            <div class="section mt-1">
                <h1>Forgot Password</h1>
                <h4>Silahkan masukkan alamat e-mail</h4>
            </div>
            <div class="section mt-1">
            <?php
            if ($this->session->flashdata('item')):
                $message = $this->session->flashdata('item');
            ?>
                <div class="alert alert-danger mb-1" role="alert">
                    <?=$message['message']?>
                </div>
            <?php endif ?>
                <form action="<?=base_url('login/forgot?k='.$kat_login)?>" method="POST">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" name="email" class="form-control form-rounded" placeholder="E-mail" required>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div class="text-right mt-2 mr-0">
                            <a href="<?=$url_back; ?>" class="text-muted">Back to login</a>
                        </div>
                        <div class="text-right mt-3 mr-0">
                            <strong><button type="submit" class="btn rounded btn-warning ml-2">
                                Submit
                            </button></strong>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->



    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="<?=PATH_ASSETS?>js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="<?=PATH_ASSETS?>js/lib/popper.min.js"></script>
    <script src="<?=PATH_ASSETS?>js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="<?=PATH_ASSETS?>js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="<?=PATH_ASSETS?>js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <!-- Base Js File -->
    <script src="<?=PATH_ASSETS?>js/base.js"></script>
    <script src="<?=PATH_ASSETS?>js/custom.js"></script>

    <script>
        AddtoHome(1000, 'once');
    </script>
</body>

</html>