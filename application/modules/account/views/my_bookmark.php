<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">MY BOOKMARK</div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader">
    <?php echo form_open('account/search_bookmark', ['class'=>'search-form']); ?>
        <div class="form-group searchbox">
            <input type="text" class="form-control" placeholder="Search..." name="keyword" value="<?= html_escape(isset($keyword)?$keyword:''); ?>">
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
        </div>
    <?php form_close() ?>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <ul class="listview image-listview media search-result mb-2">
    <?php foreach($data as $d): ?>
        <li>
            <a href="<?= $d['detail_url']; ?>" class="item">
                <div class="imageWrapper">
                    <img src="<?=PATH_ASSETS?>icon/account_ico_bookmark.png" alt="image" class="imaged w32">
                </div>
                <div style="width: 100%;">
                    <h3><?= $d['title']; ?></h3>
                    <div class="text-muted"><?= $d['date']; ?></div>
                    <div class="text-right">
                        <span class="badge badge-success"><?= $d['section_name']; ?></span>
                    </div>
                </div>
            </a>
        </li>
    <?php endforeach ?>
    </ul>
    <div class="pt-2 pb-2">
        <nav>
            <ul class="pagination pagination-rounded">
                <?php if($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?=base_url("account/my_bookmark/".(isset($keyword)?"search/".$keyword."/":"index/").($page-1))?>">Previous</a></li>
                <?php endif ?>
                <?php if(count($data)>9): ?>
                    <li class="page-item"><a class="page-link" href="<?=base_url("account/my_bookmark/".(isset($keyword)?"search/".$keyword."/":"index/").($page+1))?>">Next</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</div>
<!-- * App Capsule -->