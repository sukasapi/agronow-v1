<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <?php if(isset($sub)): ?>
        <a href="<?=base_url('learning/knowledge_sharing')?>" class="headerButton">
        <?php else: ?>
        <a href="<?=base_url('learning')?>" class="headerButton">
        <?php endif ?>
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<?php if ($contents): ?>
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
<?php endif; ?>

<!-- App Capsule -->
<div id="appCapsule" class="<?= $contents?'extra-header-active':''; ?>">
    <div class="section full p-3">
        <?php if($breadcumb): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcumb as $bc): ?>
                    <?php if ($bc['cat_id'] == $cat_id): ?>
                        <li class="breadcrumb-item">
                            <?= $bc['cat_name']; ?>
                        </li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('learning/knowledge_sharing/index?cat_id='.$bc['cat_id']); ?>" class="text-muted">
                                <?= $bc['cat_name']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
        <?php endif; ?>
        <div class="row">
            <?php foreach($categories as $d): ?>
            <div class="col-6 my-auto mt-2">
                <a href="<?=base_url('learning/knowledge_sharing/listing?cat_id='.$d['cat_id'])?>" style="color:black">
                    <div class="" style="background:white">
                        <div class="text-center p-1">
                            <img src="<?=URL_MEDIA_IMAGE."/".$d['cat_image']?>" alt="image" style="object-fit: cover;width:50px;height:50px;">
                        </div>
                        <div class="p-1 text-center">
                            <p class="card-text"><small><?=$d['cat_name']?></small></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach ?>
        </div>

        <?php if ($contents): ?>
        <div class="mt-5">
        <ul class="listview image-listview media search-result mb-2">
            <?php foreach($contents as $d): ?>
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
        </div>
        <?php endif ?>
        <div class="pt-2 pb-2">
            <nav>
                <ul class="pagination pagination-rounded">
                    <?php if($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?=base_url("learning/knowledge_sharing/".(isset($keyword)?"search/".$keyword."/":"listing/").($page-1)."?cat_id=$cat_id")?>">Previous</a></li>
                    <?php endif ?>
                <?php if(count($contents)>9): ?>
                    <li class="page-item"><a class="page-link" href="<?=base_url("learning/knowledge_sharing/".(isset($keyword)?"search/".$keyword."/":"listing/").($page+1)."?cat_id=$cat_id")?>">Next</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
</div>