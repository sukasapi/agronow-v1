<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('portal/forum')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">FORUM PORTAL <?=$this->session->userdata('member_group')?></div>
</div>
<!-- * App Header -->
<?php if ($contents): ?>
    <!-- Extra Header -->
    <div class="extraHeader">
        <?php echo form_open('portal/forum/search', ['class'=>'search-form']); ?>
        <div class="form-group searchbox">
            <input type="hidden" name="cat_id" value="<?= $cat_id; ?>">
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
        <?php if($breadcrumbs): ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php foreach ($breadcrumbs as $bc): ?>
                        <?php if ($bc['cat_id'] == $cat_id): ?>
                            <li class="breadcrumb-item">
                                <?= $bc['cat_name']; ?>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="<?= site_url('portal/forum/sub?cat_id='.$bc['cat_id']); ?>" class="text-muted">
                                    <?= $bc['cat_name']; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>
        <?php if ($categories): ?>
        <div class="row">
            <?php foreach($categories as $d): ?>
            <div class="col-6 my-auto mt-2">
                <a href="<?=base_url('portal/forum/sub?cat_id='.$d['cat_id'])?>" style="color:black">
                    <div class="" style="background:white">
                        <div class="text-center p-1">
                            <?php if(!empty($d['cat_image'])): ?>
                            <img src="<?=URL_MEDIA_IMAGE."/".$d['cat_image']?>" alt="image" style="object-fit: cover;width:50px;height:50px;">
                            <?php else: ?>
                            <img src="<?=PATH_ASSETS?>icon/home_profile_bg_pp_silver.png" alt="image" style="object-fit: cover;width:50px;height:50px;">
                            <?php endif ?>
                        </div>
                        <div class="p-1 text-center">
                            <p class="card-text"><small><?=$d['cat_name']?></small></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="section p-1">
        <?php if($contents): ?>
            <?php foreach($contents as $d):
                if ($d['sticky']):?>
                    <a href="<?=base_url('portal/forum/detail/'.$d['id'])?>">
                        <div class="d-flex align-items-center m-1 p-1" style="background-color: rgb(121,181,94); color: black; border-radius: 15px;">
                            <div class="p-1 d-flex align-items-center">
                                <img src="<?= $d['member_image']; ?>" alt="avatar" class="imaged rounded" style="width:50px">
                            </div>
                            <div class="p-1 flex-grow-1">
                                <div>
                                    <p class="m-0 text-double"><b><?=$d['title']?></b></p>
                                    <p class="m-0" style="color:#a1389d">by <?=$d['member']?> <span style="color:#0f7696">(<?=$d['group']?>)</span></p>
                                    <div class="d-flex bd-highlight">
                                        <div class="align-self-center">
                                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_participan.png')"></ion-icon>
                                                <span style="font-size: 15px;">&nbsp;<?=$d['participant']?></span>
                                            </span>
                                        </div>
                                        <div class="align-self-center">
                                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_comment.png')"></ion-icon>
                                                <span style="font-size: 15px;">&nbsp;<?=$d['comment']?></span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 text-right" style="color:#0f7696"><?=$d['date']?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="<?=base_url('portal/forum/detail/'.$d['id'])?>">
                        <div class="d-flex align-items-center m-1 p-1" style="background-color: white; color: black; border-radius: 15px;">
                            <div class="p-1 d-flex align-items-center">
                                <img src="<?= $d['member_image']; ?>" alt="avatar" class="imaged rounded" style="width:70px">
                            </div>
                            <div class="p-1 flex-grow-1">
                                <div>
                                    <p class="m-0 text-double"><b><?=$d['title']?></b></p>
                                    <p class="m-0" style="color:#a1389d">by <?=$d['member']?></p>
                                    <div class="d-flex bd-highlight">
                                        <div class="align-self-center">
                                <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_participan.png')"></ion-icon>
                                    <span style="font-size: 15px;">&nbsp;<?=$d['participant']?></span>
                                </span>
                                        </div>
                                        <div class="align-self-center">
                                <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_comment.png')"></ion-icon>
                                    <span style="font-size: 15px;">&nbsp;<?=$d['comment']?></span>
                                </span>
                                        </div>
                                        <div class="flex-grow-1 text-right" style="color:#0f7696"><?=$d['date']?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach ?>
        <?php elseif (!$categories): ?>
            <div class="d-flex justify-content-center mt-3">
                <p>Data tidak ditemukan</p>
            </div>
        <?php endif ?>
        <div class="pt-2 pb-2">
            <nav>
                <ul class="pagination pagination-rounded">
                    <?php if($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?=base_url("portal/forum/sub?page=".($page-1)."&cat_id=").$selected_category['cat_id']?>">Previous</a></li>
                    <?php endif ?>
                    <?php if(count($contents)>9): ?>
                        <li class="page-item"><a class="page-link" href="<?=base_url("portal/forum/sub?page=".($page+1)."&cat_id=").$selected_category['cat_id']?>">Next</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php if (!$categories): ?>
    <!-- bottom right -->
    <div class="fab-button bottom-right" style="bottom: 80px;">
        <a href="<?=base_url('portal/forum/tambah?cat_id=').$cat_id?>" class="fab bg-warning">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
    <!-- * bottom right -->
    <?php endif; ?>
</div>
<script>
    let capsuleWidth = document.getElementById("appCapsule").offsetWidth;
    let offsets = document.getElementById("appCapsule").getBoundingClientRect();
    let fab_pos = offsets.right-capsuleWidth+20;
    let fab = document.getElementsByClassName("fab-button");
    if (fab.length){
        fab[0].style.right = fab_pos+"px";
    }
</script>