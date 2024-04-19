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
    <title>Agronow - Login</title>
    <meta name="description" content="Agronow PWA">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="<?=PATH_ASSETS?>icon/main_icon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=PATH_ASSETS?>icon/main_icon.png">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/style.css">
    <link rel="stylesheet" href="<?=PATH_ASSETS?>css/custom.css">
<!--    <link rel="manifest" href="/__manifest.json">-->
</head>

<body style="background-color: black">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0 pb-0">

        <div class="login-form bg-white" style="height: 100vh">
            <div class="login-header">
                <img src="<?=PATH_ASSETS?>icon/ipfi_login_bg.png" style="width:100%;">
                <div style="position:absolute;top:2%;left:50%;transform: translateX(-50%);max-width:200px;">
                    <div class="section">
                        <img style="" src="<?=PATH_ASSETS?>icon/ipfi_logo_white.png" alt="image" class="form-image">
                    </div>
                </div>
            </div>
            <div class="section mt-1">
                <h1>Login IPFI</h1>
                <h4>Silahkan login menggunakan NIP dan Password.</h4>
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
                <form action="<?=base_url('login/auth?k=ipfi')?>" method="POST">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <select name="group_id" class="form-control custom-select form-rounded">
                                <option selected disabled>Group</option>
                                <?php foreach ($groups as $i=>$group):
                                    if ($group['group_id']<=15) continue; ?>
                                <option value="<?= $group['group_id']; ?>"><?= $group['group_name']; ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" name="nip" class="form-control form-rounded" placeholder="NIP">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" name="password" class="form-control form-rounded" placeholder="Password">
                            <i class="show-password">
                                <ion-icon name="eye"></ion-icon>
                            </i>
                        </div>
                    </div>
					
					<div class="form-group mt-1">
                        <div class="text-right mt-1 mr-0">
                            <a href="<?= site_url('forgot?k=ipfi'); ?>" class="text-muted">Forgot Password?</a>
                        </div>
                        <div class="row align-items-center mt-2">
                            <div class="col text-left">
                                <a href="<?= site_url('login'); ?>" class="btn btn-outline-secondary btn-sm">&laquo; kembali</a>
                            </div>
                            <div class="col text-right">
                                <strong>LOGIN <button type="submit" class="btn rounded btn-warning ml-2">
                                        <ion-icon name="arrow-forward" class="mr-0"></ion-icon>
                                    </button></strong>
                            </div>
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
<!--    <script src="--><?//=PATH_ASSETS?><!--js/custom.js"></script>-->

    <script>
        AddtoHome(1000, 'once');
    </script>
</body>

</html>