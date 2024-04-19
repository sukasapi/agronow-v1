<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('portal/digital_sop')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">DIGITAL SOP</div>
    <div class="right">
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if(isset($content['image']['media_value'])): ?>
            <?php if(@getimagesize('https://agronow.co.id/media/image/'.$content['image']['media_value']) !== false): ?>
                <img src="<?='https://agronow.co.id/media/image/'.$content['image']['media_value']; ?>" style="width: 100%;" alt="image">
            <?php elseif(file_exists(MEDIA_IMAGE_PATH.$content['image']['media_value'])): ?>
                <img src="<?=URL_MEDIA_IMAGE.$content['image']['media_value']; ?>" style="width: 100%;" alt="image">
            <?php endif ?>
        <?php endif ?>
        <div class="p-2">
            <h2><?=$content['content_name']?></h2>
            <div class="d-flex justify-content-between mb-3">
                <div><?=$this->function_api->date_indo($content['content_publish_date'])?></div>
            </div>
            <div class="mt-2 content-view">
                <?=$content['content_desc']?>
            </div>
        </div>
    </div>

</div>
<!-- * App Capsule -->