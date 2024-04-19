<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('account/inbox')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">INBOX</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <?php
    $prev = '';
    foreach ($data['inbox'] as $ibxs):
        $curr = $ibxs;
        if ($prev != $ibxs):
            $prev = $curr;
    ?>
    <div class="message-divider">
        <?= $ibxs[0]['day']; ?>
    </div>
    <?php endif;?>
    <?php foreach ($ibxs as $ibx): ?>
    <?php if ($this->member_id == $ibx['sender_id']): ?>
    <div class="message-item user">
        <div class="content">
            <div class="bubble">
                <?= $ibx['desc']; ?>
            </div>
            <div class="footer"><?= $ibx['time']; ?></div>
        </div>
    </div>
    <?php else: ?>
    <div class="message-item">
        <img src="<?= $ibx['image']; ?>" alt="avatar" class="avatar">
        <div class="content">
            <div class="title"><?= $ibx['sender']=='member'?$ibx['sender_name']:'Admin'; ?></div>
            <div class="bubble">
                <?=$ibx['desc']?>
            </div>
            <div class="footer"><?= $ibx['time']; ?></div>
        </div>
    </div>
    <?php endif ?>
    <?php endforeach; ?>
    <?php endforeach;?>
</div>
<!-- * App Capsule -->

<!-- chat footer -->
<div class="chatFooter">
    <form>
        <a href="javascript:;" class="btn btn-icon btn-secondary rounded" data-toggle="modal" data-target="#addActionSheet">
            <ion-icon name="add"></ion-icon>
        </a>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" placeholder="Type a message...">
                <i class="clear-input">
                    <ion-icon name="close-circle"></ion-icon>
                </i>
            </div>
        </div>
        <button type="button" class="btn btn-icon btn-primary rounded">
            <ion-icon name="send"></ion-icon>
        </button>
    </form>
</div>
<!-- * chat footer -->