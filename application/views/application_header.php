<!-- App Header -->
<?php if($header_type == 1){ ?>
    <!-- Home -->
    <div class="appHeader bg-agronow scrolled" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
        <div class="left pageTitle">
            <img src="<?=PATH_ASSETS?>icon/logo_white.png" class="imaged w64">
        </div>
        <div class="pageTitle" style="color:white">HOME</div>
        <div class="right">
            <a href="<?=site_url('learning/attendance')?>" class="headerButton p-0">
                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_ico_scan.png')" class="text-white"></ion-icon>
            </a>
            <a href="<?=site_url('account/my_bookmark'); ?>" class="headerButton p-0">
                <ion-icon name="bookmark" class="text-white"></ion-icon>
            </a>
            <a href="<?=site_url('notification')?>" class="headerButton p-0">
                <ion-icon name="notifications" class="mr-1 text-white"></ion-icon>
                <span class="badge badge-danger" id="notificationCount"></span>
            </a>
        </div>
    </div>
<?php }elseif($header_type == 2){ ?>
    <!-- Learning Room, Portal, What's New, Account -->
    <div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
        <div class="left pageTitle">
            <img src="<?=PATH_ASSETS?>icon/logo_white.png" class="imaged w64">
        </div>
        <div class="pageTitle"><?= strtoupper($header_title); ?></div>
        <div class="right">
            <a href="<?=site_url('learning/attendance')?>" class="headerButton p-0">
                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_ico_scan.png')" class="text-white"></ion-icon>
            </a>
            <a href="<?=site_url('account/my_bookmark'); ?>" class="headerButton p-0">
                <ion-icon name="bookmark" class="text-white"></ion-icon>
            </a>
            <a href="<?=site_url('notification')?>" class="headerButton p-0">
                <ion-icon name="notifications" class="mr-1 text-white"></ion-icon>
                <span class="badge badge-danger" id="notificationCount"></span>
            </a>
        </div>
    </div>
<?php }elseif($header_type == 3){ ?>
    <div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
        <div class="left">
            <a href="<?=isset($back_url) ? $back_url : 'javascript:history.back()';?>" class="headerButton">
                <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
            </a>
        </div>
        <div class="pageTitle"><?=strtoupper($title)?></div>
    </div>
<?php } ?>
<!-- * App Header -->