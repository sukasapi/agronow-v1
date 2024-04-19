<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">NOTIFICATION</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <ul class="listview image-listview media mb-2">
        <li>
            <a href="<?= site_url('account/inbox'); ?>" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/notification_ico_inbox.png" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        Inbox
                    </div>
                    <span><?= $data['inbox_count']?$data['inbox_count']:''; ?></span>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('notification/expert_directory'); ?>" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/notification_ico_forum.png" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        Expert Directory
                    </div>
                    <?php if ($data['expert_directory_count']): ?>
                        <span class="badge badge-primary"><?= $data['expert_directory_count']; ?></span>
                    <?php endif; ?>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('notification/whatsnew'); ?>" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/notification_ico_whatsnew.png" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        What's New
                    </div>
                    <span><?= $data['whatsnew_count']?$data['whatsnew_count']:''; ?></span>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('learning/digital_library'); ?>" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/notification_ico_learning_room.png" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        Learning Room
                    </div>
                    <span><?= $data['learning_count']?$data['learning_count']:''; ?></span>
                </div>
            </a>
        </li>
<!--        <li style="background: #e6e7e9">-->
<!--            <a href="" class="item">-->
<!--                <div class="imageWrapper">-->
<!--                    <img src="--><?//=PATH_ASSETS?><!--icon/notification_ico_forum.png" alt="image" class="imaged w64">-->
<!--                </div>-->
<!--                <div class="in">-->
<!--                    <div>-->
<!--                        Forum-->
<!--                    </div>-->
<!--                    <span>&nbsp;</span>-->
<!--                </div>-->
<!--            </a>-->
<!--        </li>-->
        <li style="background: #e6e7e9">
            <a href="#" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/notification_ico_system.png" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        System
                    </div>
                    <span>&nbsp;</span>
                </div>
            </a>
        </li>
    </ul>
</div>
<!-- * App Capsule -->