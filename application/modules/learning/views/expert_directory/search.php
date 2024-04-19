<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">EXPERT DIRECTORY</div>
</div>
<!-- * App Header -->


<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height" style="padding: 40px 0px;">
    <div class="section full mt-2">
        <div class="wide-block pt-2 pb-2">
            <div class="search-form">
                <div class="form-group searchbox">
                    <input type="text" name="keyword" id="search_text" class="form-control" placeholder="Search" value="">
                    <i class="input-icon">
                        <ion-icon name="search-outline"></ion-icon>
                    </i>
                </div>
                <div class="form-group m-0 mt-1">
                <div class="custom-control custom-radio d-inline mr-2">
                    <input type="radio" id="opsi1" name="opsi" class="custom-control-input" value="lokasi">
                    <label class="custom-control-label p-0" for="opsi1">Lokasi</label>
                </div>
                <div class="custom-control custom-radio d-inline mr-2">
                    <input type="radio" id="opsi2" name="opsi" class="custom-control-input" value="nama">
                    <label class="custom-control-label p-0" for="opsi2">Nama</label>
                </div>
                <div class="custom-control custom-radio d-inline mr-2">
                    <input type="radio" id="opsi3" name="opsi" class="custom-control-input" value="bidang" checked>
                    <label class="custom-control-label p-0" for="opsi3">Bidang</label>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-0">
        <div id="result">
        </div>
    </div>
</div>
<!-- * App Capsule -->
