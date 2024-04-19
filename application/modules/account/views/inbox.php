<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('account')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">INBOX</div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader">
    <form class="search-form">
        <div class="form-group searchbox">
            <input type="text" class="form-control" placeholder="Search...">
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
        </div>
    </form>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <?php if(count($data['inbox'])>0): ?>
    <ul class="listview image-listview media search-result mb-2">
        <?php foreach($data['inbox'] as $ibx): ?>
        <li>
            <a href="<?= $ibx['detail_url']; ?>" class="item">
                <div class="imageWrapper">
                    <div class="i-circle"><?= strtoupper(substr($ibx['title'],0,1)) ?></div>
                </div>
                <div class="in">
                    <div style="width: 100%;">
                        <div class="text-muted float-right" style="font-size: xx-small;">
                            <?= $ibx['date']; ?>
                        </div>
                        <h4 class="mb-05"><?= $ibx['title']; ?></h4>
                        <div class="text-muted text-double">
                            <?= $ibx['desc']; ?>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
    <?php else: ?>
    <div class="section full">
        <div class="d-flex justify-content-center mt-3">
            <p>Data tidak ditemukan</p>
        </div>
    </div>
    <?php endif ?>

    <!-- bottom right -->
    <div class="fab-button animate bottom-right" style="bottom: 80px;">
        <a href="<?=base_url('account/inbox/add')?>" class="fab bg-warning">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
    <!-- * bottom right -->
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