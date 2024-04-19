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
    <div class="section full" style="background-color: #e0e8d9">
        <div class="d-flex flex-row p-2">
            <div class="d-flex align-items-center mr-2">
                <img src="<?=PATH_ASSETS?>img/avatar.png" alt="avatar" class="imaged w48 rounded">
            </div>
            <div>
                <p class="m-0" style="font-size: large;"><b>Hi, Test Admin LPP<b></p>
                <p class="m-0 text-primary">LPP,NIP: 123456</p>
            </div>
        </div>
    </div>
    <div class="section full p-2 mb-2">
        <p class="m-0">Anda telah terdaftar untuk mengikuti pembelajaran sebagai berikut:</p>
        <p class="m-0">Pembelajaran: Organizational Culture</p>
        <p class="m-0">Waktu: 24 Jan 2020 00:00 - 31 Jan 2020 00:00</p>
        <div class="wide-block mt-2 p-2" style="background-color: #f4f5f7; border-radius: 10px;">
            <p class="m-0">Learning Purpose:</p>
            <p class="m-0">Setelah mengikuti pembelajran ini, peserta akan mampu menginternalisasikan values "Sinergi, Integritas, Professional" ke dalam aktivitas pekerjaan sehingga value SIPro mampu memandu setiap kegiatan dan aktivitas pekerjaan.</p>
        </div>
        <p class="ml-2 mt-1">Tahap Pembelajaran:</p>
        <div class="d-flex m-1" style="color: white">
            <div class="p-1" style="background-color: #0f7695">1</div>
            <div class="p-1 flex-grow-1" style="background-color: #2088a5">TRAINING MODULES</div>
            <div class="p-1" style="background-color: #0f7695"><ion-icon name="timer-outline"></ion-icon></div>
        </div>
        <div class="d-flex m-1" style="color: white">
            <div class="p-1" style="background-color: #999999">2</div>
            <div class="p-1 flex-grow-1" style="background-color: #b3b3b3">
                COMPETENCY TEST
                <p class="m-0" style="font-size: x-small;">Waktu: 24 Jan 2020 00:00 - 31 Jan 2020 00:00</p>
            </div>
            <div class="p-1" style="background-color: #999999"><ion-icon name="remove-outline"></ion-icon></div>
        </div>
        <div class="d-flex m-1" style="color: white">
            <div class="p-1" style="background-color: #999999">3</div>
            <div class="p-1 flex-grow-1" style="background-color: #b3b3b3">REPORT</div>
            <div class="p-1" style="background-color: #999999"><ion-icon name="remove-outline"></ion-icon></div>
        </div>
        <div class="mt-2 text-center">
            <a href="<?=base_url('learning/corporate_culture/pembelajaran')?>" class="btn btn-success rounded mt-1">DAFTAR PEMBELAJARAN SAYA</a>
        </div>
        <div class="mt-2 text-center">
            <a href="<?=base_url('learning/corporate_culture/pembelajaran')?>" class="btn btn-danger rounded mt-1">RESET CULTURE</a>
        </div>
    </div>
</div>
