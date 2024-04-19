<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/expert_directory')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">LIST OF EXPERT</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="wide-block pt-2 pb-2">
        <div class="search-form">
            <div class="form-group searchbox">
                <input type="text" name="keyword" id="search_text" class="form-control" placeholder="Search by name or skill" value="">
                <input type="hidden" name="kategori" id="kategori" value="<?= $category['cat_id']; ?>">
                <i class="input-icon">
                    <ion-icon name="search-outline"></ion-icon>
                </i>
            </div>
        </div>
    </div>
    <div class="section p-1">
        <div class="btn btn-sm btn-text-secondary btn-block"><?= $category['cat_name']; ?></div>
        <div id="result">
        </div>
    </div>
</div>
<!-- * App Capsule -->
