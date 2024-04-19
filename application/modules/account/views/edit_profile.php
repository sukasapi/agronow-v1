<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('account')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">EDIT PROFILE</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="demoPage" style="padding: 40px 0px;">
    <div class="section full mt-0">
        <div class="wide-block" style="background: url('<?=PATH_ASSETS?>icon/home_bg_header.png'); background-repeat: no-repeat;
        background-size: 100% 100%; border-top: 0px;">
            <div class="d-flex flex-column p-2">
                <div class="d-flex justify-content-center">
                    <img src="<?=$data['member_image']?>" alt="image" class="imaged w120 rounded" style="object-fit: fill;" id="photo_profile">
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-warning btn-sm rounded mt-1" id="change_photo" data-toggle="modal" data-target="#croppieModal">CHANGE PHOTO</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section full mt-2 mb-2 ml-2 mr-2">
        <?php
        if ($this->session->flashdata('item')):
            $message = $this->session->flashdata('item');
        ?>
            <div class="alert alert-<?=$message['color']?> mb-1" role="alert">
                <?=$message['message']?>
            </div>
        <?php endif ?>
        <div class="section-title">Edit Profile</div>
        <div class="wide-block pt-2 pb-2">
            <form class="needs-validation" action="<?=base_url('account/profile/edit')?>" method="POST">
                <input type="hidden" name="image" value="" id="image">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap anda" value="<?=$data['member_name']?>" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan masukkan nama lengkap anda.</div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan E-mail anda" value="<?=$data['member_email']?>" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan masukkan E-mail anda.</div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Telephone</label>
                        <input type="number" name="phone" class="form-control" placeholder="Masukkan Telephone anda" value="<?=$data['member_phone']?>" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan masukkan Telephone anda.</div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-warning btn-sm rounded mt-1" type="submit">SAVE</button>
                </div>
            </form>
        </div>
    </div>

    <div class="section full mt-2 mb-5 ml-2 mr-2">
        <div class="section-title">CHANGE PASSWORD</div>
        <div class="wide-block pt-2 pb-2">
            <form class="needs-validation" action="<?=base_url('account/profile/changePass')?>" method="POST">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Old Password</label>
                        <input type="password" name="old_password" class="form-control" placeholder="...." required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan masukkan password lama anda.</div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="...." required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan masukkan password baru anda.</div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Confirm New Password</label>
                        <input type="password" name="confirm_new_pass" class="form-control" placeholder="...." required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Silahkan tulis ulang password baru anda.</div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-warning btn-sm rounded mt-1" type="submit">SAVE PASSWORD</button>
                </div>
            </form>
        </div>
    </div>

    <div class="section full mt-2 mb-5 ml-2 mr-2">
        <div class="section-title">CHANGE JABATAN</div>
        <div class="wide-block pt-2 pb-2">
            <form class="needs-validation" action="<?=base_url('account/profile/changeJabatan')?>" method="POST" novalidate>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Jabatan Sekarang</label>
                        <input type="text" class="form-control" disabled="disabled" value="<?= $data['jabatan_name'] ?>" style="background-color: #EEE">
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Jabatan Baru</label>
                        <select name="jabatan_id" class="form-control" required="required">
                            <option value="">....</option>
                            <?php foreach ($list_jabatan as $i => $jbt) { ?>
                                <?php $sel = ''; // if($jbt['jabatan_id'] == $data['jabatan_id']){ $sel = 'selected="selected"'; } ?>
                                <option value="<?= $jbt['jabatan_id'] ?>" <?= $sel ?>><?= $jbt['jabatan_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-warning btn-sm rounded mt-1" type="submit">UBAH JABATAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * App Capsule -->