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
    <title>Agronow - SplashScreen</title>
    <meta name="description" content="Agronow PWA">
    <meta name="keywords" content="agronow" />
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
	
	<div class="splash-container">
		<div style="position:fixed;top:1em;right:2em;color:black"><h2 id="cd"></h2></div>
		
		<div class="text-center" style="width: 100%;">
            <img src="assets/icon/main_icon.png" alt="logo" style="width: 100px;">
        </div>
        <div class="ss-footer p-2">
            <div class="d-flex justify-content-center" style="height: 100%;">
                <div class="d-flex justify-content-center">
                    <div class="align-self-start">
                        <span class="iconedbox">
                            <ion-icon class="mt-0 mb-0" style="content: url('assets/icon/ss_ico_quotes.png');"></ion-icon>
                        </span>
                    </div>
                    <div class="align-self-center p-2">
                        <h4 class="text-left m-0" id="quotes_text"></h4>
                        <p class="text-right m-0"><i id="quotes_author"></i></p>
                    </div>
                    <div class="align-self-end">
                        <span class="iconedbox">
                            <ion-icon class="mt-0 mb-0" style="content: url('assets/icon/ss_ico_quotes_2.png');"></ion-icon>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="assets/js/lib/popper.min.js"></script>
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
		var i = 3;
		var timer = i*1000;
		var handle = null;
		function countdown() {
			i--;
			$("#cd").html(i);
		}
        $( document ).ready(function() {
			$("#cd").html(i);
			
            $.ajax({
                url: "<?=base_url("splashscreen/get_quotes")?>",
                type: "get",
                dataType: 'json',
            }).done(function(response) {
                $('#quotes_text').text(response.quotes_text);
                $('#quotes_author').text(response.quotes_author);
				handle = setInterval(countdown, 1000);
                setTimeout(() => {
					clearInterval(handle);
					window.location.href = '<?=base_url("home")?>'; 
				}, timer);
            });
        });
        function is_loggedin(){
            // not used
            $.ajax({
                url: "<?=base_url("splashscreen/is_loggedin")?>",
                type: "get",
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'OK'){
                        window.location.href = '<?=base_url("home")?>';
                    }else{
                        window.location.href = '<?=base_url("login")?>';
                    }
                },
                fail: function() {
                    window.location.href = '<?=base_url("login")?>';
                }
            });
        }
    </script>
</body>

</html>