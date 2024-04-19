<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="#" class="headerButton">
            <img src="<?=PATH_ASSETS?>icon/logo_white.png" class="imaged w64">
        </a>
    </div>
    <div class="pageTitle">PORTAL <?= $group['group_name']; ?></div>
    <div class="right">
        <a href="<?=site_url('learning/attendance')?>" class="headerButton p-0">
            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_ico_scan.png')"></ion-icon>
        </a>
        <a href="<?=site_url('account/my_bookmark'); ?>" class="headerButton p-0">
            <ion-icon name="bookmark"></ion-icon>
        </a>
        <a href="<?=site_url('notification')?>" class="headerButton p-0">
            <ion-icon name="notifications"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <div class="card" style="background: url('<?=PATH_ASSETS?>icon/home_profile_bg_corporatecltr.png') no-repeat center; background-size: 140% 140%; border-top: 0;">
                <a href="<?=base_url('portal/forum')?>">
                    <div class="card-body p-2">
                        <p class="mb-0">
                            <img src="<?=PATH_ASSETS?>icon/home_forum_ils.png" alt="image" style="width: 100%; max-width: 114px;">
                            <span class="ml-2 text-white" style="font-size: larger;"><b>FORUM</b></span>
                        </p>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card" style="background: url('<?=PATH_ASSETS?>icon/home_profile_bg_corporatecltr.png') no-repeat center; background-size: 140% 140%; border-top: 0px;">
                <a href="<?=base_url('portal/digital_sop')?>">
                    <div class="card-body p-2">
                        <p class="mb-0">
                            <img src="<?=PATH_ASSETS?>icon/portal_digitalsop_ils.png" alt="image" style="width: 100%; max-width: 114px;">
                            <span class="ml-2 text-white" style="font-size: larger;"><b>DIGITAL SOP</b></span>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>