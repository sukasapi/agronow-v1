<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">FORUM</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full p-3">
        <?php foreach($data as $d): ?>
        <div class="mb-2" style="background:white">
            <a href="<?=base_url('learning/forum/sub/'.$d['cat_id'])?>" style="color:black">
                <div class="d-flex align-items-center p-2" style="background:white">
                    <div class="text-center">
                        <img src="<?=$d['cat_image']?>" alt="image" style="object-fit: cover;width:50px;height:50px;">
                    </div>
                    <div class="p-1 text-center">
                        <p class="m-0 ml-2" style="font-size: x-large;"><small><?=$d['cat_name']?></small></p>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach ?>
    </div>
</div>