<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">EXPERT DIRECTORY</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="demoPage" style="padding: 40px 0px;">
    <div class="section full mt-0">
        <div class="wide-block" style="background-color: #f2f5c6; border-top: 0px; border-radius: 20px;">
            <div class="d-flex justify-content-center pt-5">
                <div class="d-flex align-items-end">
                    <img src="<?=PATH_ASSETS?>img/expert.png" style="object-fit: fill; width:180px">
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <div class="text-center"><p class="m-0">Konsultasi dengan para Expert di sini!</p></div>
                        <div class="mt-1 ml-2 mr-2"><a href="<?= site_url('learning/expert_directory/search'); ?>" class="btn btn-block btn-warning btn-sm rounded"><ion-icon name="search"></ion-icon>Search Expert</a></div>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="pt-4 text-center text-muted">-->
<!--            <h4>Pilih Bidang Keahlian</h4>-->
<!--        </div>-->
        <div class="p-2">
            <?php foreach ($data as $cat): ?>
                <div class="card m-2" style="background: url('<?=PATH_ASSETS?>icon/home_profile_bg_corporatecltr.png') no-repeat center; background-size: 140% 140%; border-top: 0;">
                    <a href="<?=base_url('learning/expert_directory/list_expert/'.$cat['cat_id'])?>">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center text-white">
                                <div class="text-center">
                                    <?php if ($cat['cat_image']): ?>
                                        <img src="<?= site_url(MEDIA_IMAGE_PATH.$cat['cat_image']); ?>" alt="image" style="width: 100%; max-width: 50px;">
                                    <?php else: ?>
                                        <img src="<?=PATH_ASSETS?>icon/home_forum_ils.png" alt="image" style="width: 100%; max-width: 50px;">
                                    <?php endif; ?>
                                    <br>
                                    <span><?= $cat['total']; ?></span>
                                </div>
                                <span class="ml-2 flex-grow-1 font-weight-bold" style="font-size: larger;"><?= $cat['cat_name']; ?></span>
                                <div class="p-1 d-flex align-items-center"><ion-icon name="arrow-forward" style="font-size: 30px;"></ion-icon></div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->
