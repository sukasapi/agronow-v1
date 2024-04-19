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
            <p><b>HASIL COMPETENCY TEST<b></p>
            <div class="d-flex justify-content-around">
                <div class="align-self-center text-center text-success">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 85px"></ion-icon>
                    <p><b>LULUS</b></p>
                </div>
                <div class="align-self-center text-center text-success" style="font-size: 85px">
                    A
                </div>
            </div>
        </div>
        <div class="text-center mb-2">
            <img src="<?=PATH_ASSETS?>img/sample/avatar/avatar1.jpg" class="card-img-top" alt="image" style="width: 50%; object-fit: cover;">
        </div>
        <div class="row ml-3 mr-3">
            <div class="col-6">
                <a href="<?=base_url('home')?>" class="btn btn-block btn-primary mt-1">LIHAT SERTIFIKAT</a>
            </div>
            <div class="col-6">
                <a href="<?=base_url('home')?>" class="btn btn-block btn-primary mt-1">DOWNLOAD</a>
            </div>
        </div>
        <div class="text-center p-2">
            <a href="<?=base_url('home')?>" class="btn btn-block btn-warning rounded mt-1">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
