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
    <div class="section full p-2 mb-2">
        <form class="needs-validation" action="#" method="POST">
            <p class="text-center m-0"><b>EVALUASI PESERTA DIKLAT TERHADAP PELAKSANAAN PEMBELAJARAN<b></p>
            <p class="text-center"><b>MODUL 1 : WHY CULTURE?<b></p>
            <div class="wide-block mt-2 p-2" style="background-color: #f4f5f7; border-radius: 10px;">
                <p class="m-0">Agar diisi untuk memberi kesempatan bagi Panitia Penyelenggara dalam penyempurnaan pembelajaran di masa yang akan datang dengan memberikan pilihan pada angka yang sesuai.</p>
                <div class="d-flex justify-content-center">
                    <div class="d-flex align-items-stretch" style="width:50%">
                        <div class="text-center bg-primary m-2 p-2" style="width:100%; border-radius: 15px">
                            <p class="text-center" style="font-size: 50px">4</p>
                            <p class="m-0 text-center">sangat memuaskan</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-stretch" style="width:50%">
                        <div class="text-center bg-success m-2 p-2" style="width:100%; border-radius: 15px">
                            <p class="text-center" style="font-size: 50px">3</p>
                            <p class="m-0 text-center">memuaskan</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="d-flex align-items-stretch" style="width:50%">
                        <div class="text-center bg-warning m-2 p-2" style="width:100%; border-radius: 15px">
                            <p class="text-center" style="font-size: 50px">2</p>
                            <p class="m-0 text-center">tidak memuaskan</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-stretch" style="width:50%">
                        <div class="text-center bg-danger m-2 p-2" style="width:100%; border-radius: 15px">
                            <p class="text-center" style="font-size: 50px">1</p>
                            <p class="m-0 text-center">sangat tidak memuaskan</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wide-block mt-2 ml-1 mr-1 p-0">
                <p class="p-2 m-0" style="background-color: #77ba53; color: white">Mutu isi materi 
                untuk penambahan keterampilan / pengetahuan</p>
                <div class="d-flex justify-content-around p-2">
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="mutu4" name="mutu" class="custom-control-input">
                            <label class="custom-control-label p-0" for="mutu4"></label>
                        </div>
                        <div class="text-center mt-1">4</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="mutu3" name="mutu" class="custom-control-input">
                            <label class="custom-control-label p-0" for="mutu3"></label>
                        </div>
                        <div class="text-center mt-1">3</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="mutu2" name="mutu" class="custom-control-input">
                            <label class="custom-control-label p-0" for="mutu2"></label>
                        </div>
                        <div class="text-center mt-1">2</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="mutu1" name="mutu" class="custom-control-input">
                            <label class="custom-control-label p-0" for="mutu1"></label>
                        </div>
                        <div class="text-center mt-1">1</div>
                    </div>
                </div>
            </div>
            <div class="wide-block mt-2 ml-1 mr-1 p-0">
                <p class="p-2 m-0" style="background-color: #77ba53; color: white">Penyampaian materi dalam aplikasi</p>
                <div class="d-flex justify-content-around p-2">
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="penyampaian4" name="penyampaian" class="custom-control-input">
                            <label class="custom-control-label p-0" for="penyampaian4"></label>
                        </div>
                        <div class="text-center mt-1">4</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="penyampaian3" name="penyampaian" class="custom-control-input">
                            <label class="custom-control-label p-0" for="penyampaian3"></label>
                        </div>
                        <div class="text-center mt-1">3</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="penyampaian2" name="penyampaian" class="custom-control-input">
                            <label class="custom-control-label p-0" for="penyampaian2"></label>
                        </div>
                        <div class="text-center mt-1">2</div>
                    </div>
                    <div class="d-flex flex-column bd-highlight mb-3">
                        <div class="custom-control custom-radio d-inline">
                            <input type="radio" id="penyampaian1" name="penyampaian" class="custom-control-input">
                            <label class="custom-control-label p-0" for="penyampaian1"></label>
                        </div>
                        <div class="text-center mt-1">1</div>
                    </div>
                </div>
            </div>
            <div class="wide-block mt-2 ml-1 mr-1 p-0">
                <p class="p-2 m-0" style="background-color: #77ba53; color: white">Kesimpulan penilaian secara umum</p>
                <div class="p-2">
                    <textarea id="deskripsi" rows="6" class="form-control" name="content_desc" required></textarea>
                </div>
            </div>
            <div class="text-right mt-2">
                <button class="btn btn-primary" type="submit">kirim penilaian anda</button>
            </div>
        </form>
        <div class="mt-2">
            <a href="<?=base_url('home')?>" class="btn btn-warning btn-block rounded mt-1">MODUL PEMBELAJARAN</a>
        </div>
    </div>
</div>
