<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?= $title; ?></div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mt-2">
        <div class="section-title"><?= $content['content_name']; ?></div>
        <div class="wide-block pt-2 pb-2">
            <?= $content['content_desc']; ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->