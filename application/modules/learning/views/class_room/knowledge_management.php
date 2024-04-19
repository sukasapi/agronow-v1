<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('learning/class_room/home?cr_id='.$cr_id)?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">KNOWLEDGE MANAGEMENT</div>
    <div class="right">
        <a onclick="bookmark()" class="headerButton text-success">
            <ion-icon name="bookmark"></ion-icon>
        </a>
        <a onclick="sharelink()" class="headerButton text-success">
            <ion-icon name="share-social-outline"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if(isset($content['image']['media_value'])): ?>
            <?php if(file_exists(MEDIA_IMAGE_PATH.$content['image']['media_value'])): ?>
                <img src="<?=URL_MEDIA_IMAGE.$content['image']['media_value']; ?>" style="width: 100%;" alt="image">
            <?php endif ?>
        <?php endif ?>
        <div class="p-2">
            <h2><?=$content['content_name']?></h2>
            <?php if ($content['content_status'] == 'draft'): ?>
                <div class="alert alert-primary mb-2" role="alert">
                    Data sedang dimoderasi oleh admin
                </div>
            <?php endif; ?>
            <p class="mb-0">Pengarang: <?=$content['content_author']?></p>
            <div class="d-flex flex-row bd-highlight mb-3">
                <div><?=$content['content_publish_date']?></div>
                <div class="ml-2 text-primary">
                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                        <ion-icon name="eye"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?=$content['content_hits']?></span>
                    </span>
                </div>
                <?php if($content['is_liked']):?>
                    <div class="ml-2 text-success">
                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/love-green.png')"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?=$content['like_count']?></span>
                    </span>
                    </div>
                <?php else: ?>
                    <a onclick='btn_like()'>
                        <div class="ml-2 text-primary">
                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/love-blue.png')"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?=$content['like_count']?></span>
                    </span>
                        </div>
                    </a>
                <?php endif ?>
                <div class="ml-2 text-primary">
                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                        <ion-icon name="chatbox-ellipses"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?=$content['comment_count']?></span>
                    </span>
                </div>
            </div>
            <div class="mt-2 content-view">
                <?=str_replace("&quot;",'"',$content['content_desc'])?>
            </div>
            <a href="<?= base_url('learning/class_room/home?cr_id='.$data['cr_id']); ?>" class="btn btn-block btn-lg btn-primary mt-3 mb-4">KEMBALI KE HOME</a>
        </div>
    </div>

    <!-- toast share -->
    <div id="toast-copied" class="toast-box toast-top">
        <div class="in">
            <div class="text">
                URL berhasil disalin
            </div>
        </div>
    </div>
    <!-- toast share -->
    <!-- toast bookmark -->
    <div id="toast-bookmark" class="toast-box toast-top">
        <div class="in">
            <div class="text" id="response_bookmark"></div>
        </div>
    </div>
    <!-- toast bookmark -->
</div>
<!-- * App Capsule -->
<script>
    function bookmark() {
        $.ajax({
            url: "<?=base_url("whatsnew/article/toggle_bookmark/{$content['content_id']}")?>",
            type: "get",
            dataType: 'json',
            success: function(response) {
                if(response.status){
                    document.querySelector("#response_bookmark").innerText = response.msg;
                }
                toastbox('toast-bookmark', 3000);
            }
        });
    }
</script>