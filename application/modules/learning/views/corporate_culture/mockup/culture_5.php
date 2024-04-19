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
        <p class="text-center"><b>EVALUASI MODUL 1<b></p>
        <div class="wide-block mt-2 p-2" style="background-color: #f4f5f7; border-radius: 10px;">
            <p class="m-0">Evaluasi terhadap materi modul ini dilakukan melalui sejumlah tes yang harus dijawab. Evaluasi terdiri dari 10 soal dengan kriteria lulus 60%</p>
        </div>
        <p class="ml-2">Kesempatan Evaluasi Test (1) Kali</p>
    </div>
    <div class="section full">
        <div class="d-flex mt-1" style="background-color: #77ba53; color: white">
            <div class="p-1 d-flex align-items-center"><ion-icon name="help-circle" style="font-size: 25px;"></ion-icon></div>
            <div class="p-1 flex-grow-1 align-items-center">10 Soal</div>
            <div class="p-1 d-flex align-items-center" style="background-color: #399d16">
                <span class="iconedbox iconedbox-sm" style="width: 65px;">
                    <ion-icon name="time" style="font-size: 25px;"></ion-icon>
                    <span style="font-size: 15px;">&nbsp;07.00</span>
                </span>
            </div>
        </div>
        <div class="wide-block mt-2 ml-1 mr-1 p-0">
            <p class="p-2 m-0" style="background-color: #77ba53; color: white">1, Apa manfaat adanya budaya perusahaan</p>
            <div class="input-list">
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio11" name="customRadioList" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio11">Menjadikan perusahaan memiliki nilai pengukuran tingkat kesehatan budaya yang tinggi</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio12" name="customRadioList" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio12">Menjadikan perusahaan memiliki nilai pengukuran tingkat kesehatan budaya yang tinggi</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio13" name="customRadioList" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio13">Menjadikan perusahaan memiliki nilai pengukuran tingkat kesehatan budaya yang tinggi</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio14" name="customRadioList" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio14">Menjadikan perusahaan memiliki nilai pengukuran tingkat kesehatan budaya yang tinggi</label>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between p-2">
            <a href="<?=base_url('home')?>" class="btn btn-warning rounded mt-1"><ion-icon class="m-0 mr-2" name="arrow-back"></ion-icon>SEBELUMNYA</a>
            <a href="<?=base_url('home')?>" class="btn btn-success rounded mt-1">SELANJUTNYA<ion-icon class="m-0 ml-1" name="arrow-forward"></ion-icon></a>
        </div>
    </div>
</div>
