<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/expert_directory/detail_expert/'.$data['expert_member']['em_id'])?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">EXPERT CONSULTATION</div>
    <div class="right">
        <a href="#" class="headerButton">
            <ion-icon name="search-outline"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section">
        <?php if ($data['chats']): ?>
        <?php foreach ($data['chats'] as $chat): ?>
        <a href="<?= $chat['detail_url']; ?>">
            <div class="d-flex m-1 p-1" style="background-color: white; color: black">
                <div class="p-1">
                    <img src="<?=PATH_ASSETS?>img/avatar.png" alt="avatar" class="imaged rounded" style="width:25px">
                </div>
                <div class="flex-grow-1 p-1">
                    <div>
                        <div class="d-flex justify-content-between">
                            <p class="m-0"><b><?= $chat['member_name']; ?></b></p>
                            <?php if ($chat['unread']): ?>
                            <span class="badge badge-primary"><?= $chat['unread']; ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="m-0 text-double"><?= $chat['expert_name']; ?></p>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="section full">
                <div class="d-flex justify-content-center mt-3">
                    <p>Data tidak ditemukan</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!$data['expert_member']['is_current']): ;?>
    <!-- bottom right -->
    <div class="fab-button bottom-right" style="bottom: 80px;">
        <a href="<?=base_url('learning/expert_directory/new_chat/'.$data['expert_member']['em_id'])?>" class="fab bg-warning">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
    <!-- * bottom right -->
    <?php endif; ?>
</div>
<!-- * App Capsule -->
<script>
    let capsuleWidth = document.getElementById("appCapsule").offsetWidth;
    let offsets = document.getElementById("appCapsule").getBoundingClientRect();
    let fab_pos = offsets.right-capsuleWidth+20;
    let fab = document.getElementsByClassName("fab-button");
    if (fab.length){
        fab[0].style.right = fab_pos+"px";
    }
</script>