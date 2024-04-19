<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat;
        background-size: 100%; border-top: 0px;">
        <div class="left">
            <a href="javascript:history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">ADD QUESTION</div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule" class="demoPage">
        <div class="section full mt-2 mb-2 ml-2 mr-2">
            <div class="wide-block pt-2 pb-2">
                <form action="<?=$form_action; ?>" autocomplete="off" class="needs-validation" method="post" accept-charset="utf-8">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="judul">Judul</label>
                            <input type="text" class="form-control" id="judul" name="expert_name" placeholder="" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Silahkan masukkan judul.</div>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="keterangan">Keterangan</label>
                            <textarea id="address5" rows="2" class="form-control" name="expert_desc" required></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Silahkan tulis pertanyaan anda.</div>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <button class="btn btn-warning btn-sm rounded mt-1" type="submit">SEND</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->