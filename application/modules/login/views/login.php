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

        <div class="login-form bg-white" style="min-height: 100vh">
            <div class="login-header">
                <img src="<?=PATH_ASSETS?>icon/login_bg.png" style="width:100%;">
                <div class="centered">
                    <div class="section">
                        <img src="<?=PATH_ASSETS?>icon/logo_white.png" alt="image" class="form-image">
                    </div>
                </div>
            </div>
			<div class="section mt-2">
                <h4>silahkan klik salah satu menu di bawah ini untuk login</h4>
            </div>
            <div class="section mt-4">
				<a href="<?=site_url('login_agronow')?>" class="text-success">
					<div class="row border border-success rounded align-items-center mb-2">
						<div class="col-2">
							<img src="<?=PATH_ASSETS?>icon/ikon_s_AgroNow.png" class="imaged w48">
						</div>
						<div class="col-8 font-weight-bold text-dark">
							LOGIN ANAK PERUSAHAAN
						</div>
						<div class="col-2"><ion-icon name="chevron-forward-circle-outline"></ion-icon></div>
					</div>
				</a>
				
				<a href="<?=site_url('login_cucu')?>" class="text-success">
					<div class="row border border-success rounded align-items-center mb-2">
						<div class="col-2">
							<img src="<?=PATH_ASSETS?>icon/ikon_s_AgroNow.png" class="imaged w48">
						</div>
						<div class="col-8 font-weight-bold text-dark">
							LOGIN CUCU PERUSAHAAN
						</div>
						<div class="col-2"><ion-icon name="chevron-forward-circle-outline"></ion-icon></div>
					</div>
				</a>
				
				<a href="<?=site_url('login_aghris')?>" class="text-success">
					<div class="row border border-success rounded align-items-center mb-2">
						<div class="col-2">
							<img src="<?=PATH_ASSETS?>icon/ikon_s_Aghris.png" class="imaged w48">
						</div>
						<div class="col-8 font-weight-bold text-dark">
							LOGIN AGHRIS
						</div>
						<div class="col-2"><ion-icon name="chevron-forward-circle-outline"></ion-icon></div>
					</div>
				</a>
				
				<a href="<?=site_url('login_ipfi')?>" class="text-success d-none">
					<div class="row border border-success rounded align-items-center mb-2">
						<div class="col-2">
							<img src="<?=PATH_ASSETS?>icon/ikon_s_IPFI.png" class="imaged w48">
						</div>
						<div class="col-8 font-weight-bold text-dark">
							LOGIN IPFI
						</div>
						<div class="col-2"><ion-icon name="chevron-forward-circle-outline"></ion-icon></div>
					</div>
				</a>
				
				<a href="<?=site_url('login_umum')?>" class="text-success">
					<div class="row border border-success rounded align-items-center mb-2">
						<div class="col-2">
							<img src="<?=PATH_ASSETS?>icon/ikon_s_AgroNow.png" class="imaged w48">
						</div>
						<div class="col-8 font-weight-bold text-dark">
							LOGIN UMUM
						</div>
						<div class="col-2"><ion-icon name="chevron-forward-circle-outline"></ion-icon></div>
					</div>
				</a>
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