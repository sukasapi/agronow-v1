<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="#" class="headerButton">
            <img src="<?=PATH_ASSETS?>icon/logo_white.png" class="imaged w64">
        </a>
    </div>
    <div class="pageTitle">ACCOUNT</div>
    <div class="right">
        <a href="<?=base_url('learning/attendance')?>" class="headerButton p-0">
            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_ico_scan.png')"></ion-icon>
        </a>
        <a href="<?=base_url('notification')?>" class="headerButton p-0">
            <ion-icon name="notifications"></ion-icon>
            <span class="badge badge-danger" id="notificationCount"></span>
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mt-0" style="padding: 0px;">
        <div class="wide-block" style="background: url('<?=PATH_ASSETS?>icon/home_bg_header.png'); background-repeat: no-repeat;
        background-size: 100% 85%; border-top: 0px;">
            <div class="pt-3">
                <div class="card bg-white" style="border-radius: 32px !important;">
                    <div class="card-body p-1">
						<div class="d-flex flex-row p-1">
							<?php
							$frame = 'silver';
							if (isset($data['badge_level']['mpl_name'])){
								if($data['badge_level']['mpl_name'] == 'GOLD'){
									$frame = 'gold';
								}elseif($data['badge_level']['mpl_name'] == 'PLATINUM'){
									$frame = 'platinum';
								}
							}
							?>
							<div class="d-flex align-items-center text-center" style="background: url('<?= PATH_ASSETS.'/img/frame_foto_'.$frame.'.png' ?>');background-size: 80px; background-repeat: no-repeat; background-position: center center;width: 110px;">
								<img src="<?= $data['member_image']; ?>" alt="image" class="imaged rounded" style="object-fit: fill;width: 60px; margin: auto;">
							</div>
							<div class="d-flex flex-column p-1" style="width:100%">
								<div class="">
									<h4 class="card-text text-single"><?= $data['name']; ?></h4>
								</div>
								<div class="d-flex justify-content-between mt-3">
									<div>
										<img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" alt="image" width="24px">&nbsp;<b><?= $data['rank_global']; ?></b>
									</div>
									<div>
										<img src="<?=PATH_ASSETS?>icon/home_coin.png" alt="image" width="24px">&nbsp;<b><?= $this->function_api->number($data['total_saldo']); ?></b>
									</div>
									<div>
										<img src="<?=PATH_ASSETS?>icon/home_profile_ico_star.png" alt="image" width="24px">&nbsp;
										<b><?= $this->function_api->number($data['total_point']); ?></b>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full">
        <div class="p-3">
            <ul class="listview image-listview">
                <li>
                    <a href="<?=base_url('account/profile')?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_editprofile.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>EDIT PROFILE</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=base_url('account/my_bookmark')?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_bookmark.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>MY BOOKMARK</div>
                            <?php if ($data['bookmark_count']>0): ?>
                            <span class="badge bg-success"><?= $data['bookmark_count']; ?></span>
                            <?php endif;?>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=base_url('account/inbox')?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_inbox.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>INBOX</div>
                            <?php if ($data['inbox_count']>0): ?>
                            <span class="badge bg-success"><?= $data['inbox_count']; ?></span>
                            <?php endif;?>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?=base_url('account/history')?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>RIWAYAT SALDO & POIN</div>
                        </div>
                    </a>
                </li>
                <?php if ($data['contact_us']): ?>
                <li>
                    <a href="<?= site_url('account/contact_us'); ?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_contact.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>CONTACT US</div>
                        </div>
                    </a>
                </li>
                <?php endif;
                if ($data['faq']):?>
                <li>
                    <a href="<?= site_url('account/faq'); ?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_faq.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>FAQ</div>
                        </div>
                    </a>
                </li>
                <?php endif;
                if ($data['privacy_policy']):?>
                <li>
                    <a href="<?= site_url('account/privacy_policy'); ?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_privacypolice.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>PRIVACY POLICY</div>
                        </div>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="<?=base_url('logout')?>" class="item">
                        <span class="iconedbox mr-3">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_logout.png')"></ion-icon>
                        </span>
                        <div class="in">
                            <div>LOGOUT</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
