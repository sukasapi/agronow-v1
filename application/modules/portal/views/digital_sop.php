<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat;
    background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('portal')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">DIGITAL SOP</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="full-height mb-2">
    <?php if(count($data)>0): ?>
        <ul class="listview image-listview media search-result mb-2">
            <?php foreach($data as $d): ?>
                <li>
                    <a href="<?=base_url("portal/digital_sop/detail/").$d['id'];?>" class="item">
                        <div class="imageWrapper">
                            <?php if(!empty($d['image'])): ?>
                                <img src="<?=$d['image']?>" alt="image" class="imaged" style="object-fit: cover;width: 80px;height: 80px;">
                            <?php else: ?>
                                <div class="i-circle"><?=substr($d['title'],0,1)?></div>
                            <?php endif ?>
                        </div>
                        <div class="in">
                            <div style="width: 100%;">
                                <h4 class="mb-0 text-double"><?=$d['title']?></h4>
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
                    <li class="page-item"><a class="page-link" href="<?=base_url("portal/digital_sop/".($page-1))?>">Previous</a></li>
                <?php endif ?>
                <?php if(count($data)>9): ?>
                    <li class="page-item"><a class="page-link" href="<?=base_url("portal/digital_sop/".($page+1))?>">Next</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</div>