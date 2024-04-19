<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/corporate_culture')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full p-2">
        <div class="text-center">
            <p><b>KONFIRMASI EVALUASI<b></p>
            <div class="text-warning">
                <ion-icon name="alert-circle-outline" style="font-size: 85px"></ion-icon>
            </div>
            <p class="m-0">Pastikan anda telah menjawab dengan benar.</p>
            <p>Klik tombol <b>SUBMIT</b> untuk mengetahui hasil EVALUASI anda.</p>
        </div>
        <div class="d-flex justify-content-between p-2">
            <a href="<?=base_url('home')?>" class="btn btn-warning rounded mt-1">KEMBALI</a>
            <a href="<?=base_url('home')?>" class="btn btn-success rounded mt-1">SUBMIT</a>
        </div>
    </div>
</div>
