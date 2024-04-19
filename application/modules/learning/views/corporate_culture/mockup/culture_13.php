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
            <div class="text-success">
                <ion-icon name="checkmark-circle-outline" style="font-size: 85px"></ion-icon>
                <p><b>TERIMAKASIH ATAS PENILAIAN ANDA</b></p>
            </div>
        </div>
        <p class="m-0 text-center">Penilaian yang anda berikan sangat berarti bagi kami dalam penyempurnaan pembelajaran di masa yang kan datang</p>
        <div class="text-center p-2">
            <a href="<?=base_url('home')?>" class="btn btn-block btn-success rounded mt-1">MODUL PEMBELAJARAN</a>
            <a href="<?=base_url('home')?>" class="btn btn-block btn-warning rounded mt-1">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
