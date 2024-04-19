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
    <?php if ($data['notifications']): ?>
        <ul class="listview image-listview media pt-1">
            <?php foreach ($data['notifications'] as $notif): ?>
                <li>
                    <a href="<?= site_url('learning/expert_directory/chat/').$notif['expert_id']; ?>" class="item">
                        <div class="in">
                            <div>
                                <?= $notif['member_name']; ?>
                                <div class="text-muted"><?= $notif['ec_desc']; ?></div>
                            </div>
                        </div>
                        <?php if ($notif['unread']): ?>
                        <span class="badge badge-primary"><?= $notif['unread']; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="section full">
            <div class="d-flex justify-content-center mt-3">
                <p>Data tidak ditemukan</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- * App Capsule -->