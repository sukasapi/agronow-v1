<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat;
        background-size: 100%; border-top: 0px;">
        <div class="left">
            <a href="javascript:history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">TAMBAH TOPIK BARU</div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule" class="demoPage">
        <div class="section full mt-2 mb-2 ml-2 mr-2">
            <?php
            if ($this->session->flashdata('item')): ?>
            <div class="alert alert-outline-danger mb-1" role="alert">
                <?=$this->session->flashdata('item')?>
            </div>
            <?php endif ?>
            <div class="wide-block pt-2 pb-2">
                <form action="<?=base_url('learning/forum/insert')?>" autocomplete="off" class="needs-validation" method="post" accept-charset="utf-8">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="kategori">Kategori</label>
                            <select name="kategori" class="form-control custom-select form-rounded" style="width: 100%">
<!--                                <option value="0">Semua Kategori</option>-->
                                <?php foreach($category as $c): ?>
                                    <optgroup label="<?=$c['cat_name']?>">
                                        <?php foreach($c['sub_cat'] as $s): ?>
                                        <option value="<?=$s['cat_id']?>"><?=$s['cat_name']?></option>
                                        <?php endforeach ?>
                                    </optgroup>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="topik">Topik</label>
                            <input type="text" class="form-control" id="topik" name="topik" placeholder="....." required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" rows="2" class="form-control" name="deskripsi" required></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
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