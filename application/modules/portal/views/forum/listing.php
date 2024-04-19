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

<!-- Extra Header -->
<div class="extraHeader" style="height:75px">
    <form class="search-form mt-1" method="GET" action="<?=base_url('portal/forum/listing')?>">
        <select name="cat_id" class="form-control custom-select form-rounded" style="width: 100%" onchange='this.form.submit()'>
            <option value="0">Semua Kategori</option>
            <?php foreach($category as $c): ?>
                <option value="<?=$c['cat_id']?>" <?=$cat_id==$c['cat_id']?'selected':''?>><?=$c['cat_name']?></option>
                <?php foreach($c['sub_cat'] as $s): ?>
                <option value="<?=$s['cat_id']?>" <?=$cat_id==$s['cat_id']?'selected':''?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$s['cat_name']?></option>
                <?php endforeach ?>
            <?php endforeach ?>
        </select>
        <p class="m-0 mb-1 text-single text-primary"><b>Arsip Forum <?=$cat_name?></b></p>
    </form>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height mt-2">
    <div class="section p-1">
        <?php if(count($data) > 0): ?>
        <?php foreach($data as $d): ?>
        <a href="<?=base_url('portal/forum/detail/'.$d['id'])?>">
            <?php if ($d['sticky']): ?>
            <div class="d-flex align-items-center m-1 p-1" style="background-color: rgb(121,181,94); color: black; border-radius: 15px;">
                <?php else: ?>
            <div class="d-flex align-items-center m-1 p-1" style="background-color: white; color: black; border-radius: 15px;">
                <?php endif; ?>
                <div class="p-1 d-flex align-items-center">
                    <img src="<?=PATH_ASSETS?>img/avatar.png" alt="avatar" class="imaged rounded" style="width:70px">
                </div>
                <div class="p-1 flex-grow-1">
                    <div>
                        <p class="m-0 text-double"><b><?=$d['title']?></b></p>
                        <p class="m-0" style="color:#a1389d">by <?=$d['member']?> <span style="color:#0f7696">(<?=$d['group']?>)</span></p>
                        <div class="d-flex bd-highlight">
                            <div class="align-self-center">
                                <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_participan.png')"></ion-icon>
                                    <span style="font-size: 15px;">&nbsp;<?=$d['participan']?></span>
                                </span>
                            </div>
                            <div class="align-self-center">
                                <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_comment.png')"></ion-icon>
                                    <span style="font-size: 15px;">&nbsp;<?=$d['comment']?></span>
                                </span>
                            </div>
                            <div class="flex-grow-1 text-right" style="color:#0f7696;font-size: 13px;"><?=$d['date']?></div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach ?>
        <?php else: ?>
        <div class="d-flex justify-content-center mt-3">
            <p>Data tidak ditemukan</p>
        </div>
        <?php endif ?>
        <div class="pt-2 pb-2">
            <nav>
                <ul class="pagination pagination-rounded">
                    <?php if($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?=base_url("learning/forum/list/".($page-1)."?cat_id=$cat_id")?>">Previous</a></li>
                    <?php endif ?>
                    <?php if(count($data)>9): ?>
                        <li class="page-item"><a class="page-link" href="<?=base_url("learning/forum/list/".($page+1)."?cat_id=$cat_id")?>">Next</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
    <!-- bottom right -->
    <div class="fab-button animate bottom-right dropdown" style="bottom: 80px;">
        <a href="<?=base_url('portal/forum/tambah?cat_id=').$selected_cat_id?>" class="fab bg-warning">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
    <!-- * bottom right -->
</div>
<!-- * App Capsule -->