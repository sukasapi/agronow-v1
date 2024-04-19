<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/knowledge_sharing/')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader">
    <?php echo form_open('learning/knowledge_sharing/search', ['class'=>'search-form']); ?>
        <div class="form-group searchbox">
            <input type="text" class="form-control" placeholder="Search..." name="keyword" value="<?= html_escape(isset($keyword)?$keyword:''); ?>" required>
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
        </div>
    <?php form_close() ?>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height mb-2 bg-white">
    <?php if(count($data)>0): ?>
    <ul class="listview image-listview media search-result mb-2">
    <?php foreach($data as $d): ?>
        <li>
            <a href="<?=base_url("learning/knowledge_sharing/detail/".$d['id'])?>" class="item">
                <div class="imageWrapper">
                <?php if(!empty($d['image'])): ?>
                    <img src="<?=$d['image']?>" alt="image" class="imaged" style="object-fit: cover;width: 80px;height: 80px;">
                <?php else: ?>
                    <div class="i-circle"><?=strtoupper(substr($d['title'],0,1))?></div>
                <?php endif ?>
                </div>
                <div class="in">
                    <div style="width: 100%;">
                        <h4 class="mb-0 text-double"><?=$d['title']?></h4>
                        <div class="mb-0 text-muted">
                            <?=$d['date']?>
                            <div class="mt-0">
                                <div class="d-flex justify-content-end">
                                    <div class="pr-0 pl-0">
                                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                            <ion-icon name="eye"></ion-icon>
                                            <span style="font-size: 15px;">&nbsp;<?=$d['viewed']?></span>
                                        </span>
                                    </div>
                                    <div class="pr-0 pl-0">
                                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/love-grey.png')"></ion-icon>
                                            <span style="font-size: 15px;">&nbsp;<?=$d['like_count']?></span>
                                        </span>
                                    </div>
                                    <!-- <div class="pr-0 pl-0">
                                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                            <ion-icon name="chatbox-ellipses"></ion-icon>
                                            <span style="font-size: 15px;">&nbsp;<?=$d['comment_count']?></span>
                                        </span>
                                    </div> -->
                                </div>
                            </div>
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
    <div class="pt-2 pb-2">
        <nav>
            <ul class="pagination pagination-rounded">
                <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="<?=base_url("learning/knowledge_sharing/".(isset($keyword)?"search/".$keyword."/":"listing/").($page-1)."?cat_id=$cat_id")?>">Previous</a></li>
                <?php endif ?>
                <?php if(count($data)>9): ?>
                <li class="page-item"><a class="page-link" href="<?=base_url("learning/knowledge_sharing/".(isset($keyword)?"search/".$keyword."/":"listing/").($page+1)."?cat_id=$cat_id")?>">Next</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
    <!-- bottom right -->
    <div class="fab-button bottom-right" style="bottom: 80px;">
        <a href="<?=base_url('learning/knowledge_sharing/add?cat_id=').$cat_id?>" class="fab bg-warning">
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