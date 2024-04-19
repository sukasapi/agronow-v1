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
    <title>Agronow - Tanda Tangan Digital</title>
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
		<?php if($cr_status_ttd_digital=="pending") { ?>
		<div class="alert border border-info mt-1 mb-2">
			<?php
			$attributes = array('autocomplete'=>"off");
			echo form_open($form_action, $attributes);
			?>
			<input type="hidden" name="approve" value="1"/>
			<button type="submit" class="btn btn-info">Approve</button>
			<?php echo form_close(); ?>
		</div>
		<?php } ?>
	
		<div style="position:relative;">
			<img style="max-width:60px;position:absolute;top:0;right:0;" src="<?=$img_status?>"/>
		</div>
		
		<table>
			<tr>
				<td style="width:1%">Nama</td>
				<td>: <?=$ttd_nama?></td>
			</tr>
			<tr>
				<td>Jabatan</td>
				<td>: <?=$ttd_jabatan?></td>
			</tr>
		</table>

		<p class="mt-1">menyatakan bahwa yang bersangkutan menyetujui menandatangani sertifikat keikutsertaan peserta pada pelatihan <?=$nama_kelas?>.</p>

		<div>Daftar peserta:</div>
		<table class="table table-sm table-bordered">
		<tr>
			<td style="width:1%">No.</td>
			<td style="width:1%">NIK</td>
			<td>Nama Lengkap</td>
			<td>Perusahaan</td>
		</tr>
		<?php 
			$i = 0;
			foreach($data_peserta as $key => $val) {
			$i++;
			$ui =
				'<tr>
					<td>'.$i.'</td>
					<td>'.$val['member_nip'].'</td>
					<td>'.$val['member_name'].'</td>
					<td>'.$val['group_name'].'</td>
				 </tr>';
			echo $ui;
		}
		?>
		</table>
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