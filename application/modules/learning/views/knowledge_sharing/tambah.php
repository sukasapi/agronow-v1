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
                <form action="<?=base_url('learning/knowledge_sharing/add')?>" autocomplete="off" class="needs-validation" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="kategori">Kategori</label>
                            <select name="kategori" class="form-control" style="width: 100%" required>
                                <option value="" disabled <?= !$selected_cat_id?'selected':''; ?>>Semua Kategori</option>
                                <?php foreach($category as $c): ?>
                                    <?php if(count($c['child'])>0): ?>
                                    <optgroup label="<?=$c['cat_name']?>">
                                        <?php foreach($c['child'] as $s): ?>
                                            <?php if (count($s['child'])>0): ?>
                                                <option value="<?=$s['cat_id']?>" <?= $s['cat_id']==$selected_cat_id?'selected':''; ?> disabled>- <?=$s['cat_name']?></option>
                                                <?php foreach ($s['child'] as $x): ?>
                                                    <option value="<?=$x['cat_id']?>" <?= $x['cat_id']==$selected_cat_id?'selected':''; ?>>- - <?=$x['cat_name']?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="<?=$s['cat_id']?>" <?= $s['cat_id']==$selected_cat_id?'selected':''; ?>>- <?=$s['cat_name']?></option>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                    </optgroup>
                                    <?php else: ?>
                                    <option value="<?=$c['cat_id']?>" <?= $c['cat_id']==$selected_cat_id?'selected':''; ?>><?=$c['cat_name']?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="judul">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="" maxlength="100" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="keterangan">Keterangan</label>
                            <textarea id="keterangan" rows="5" class="form-control" name="keterangan" maxlength="1000" required></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-wrapper">
                            <label class="label">Upload Dokumen</label>
                            <div class="custom-file-upload">
                                <input type="file" id="fileuploadInput" name="content_doc" accept=".pdf">
                                <label for="fileuploadInput">
                                    <span>
                                        <strong>
                                            <ion-icon name="cloud-upload-outline"></ion-icon>
                                            <i>Tap to Upload</i>
                                        </strong>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <button class="btn btn-warning btn-sm rounded mt-1" type="submit">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->