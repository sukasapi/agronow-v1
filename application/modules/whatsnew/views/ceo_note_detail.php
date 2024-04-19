<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('whatsnew/ceo_note')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">CEO Note</div>
    <div class="right">
        <a onclick="bookmark()" class="headerButton text-success">
            <?php if ($content['is_bookmarked']): ;?>
                <ion-icon id="btnBookmark" name="bookmark"></ion-icon>
            <?php else: ?>
                <ion-icon id="btnBookmark" name="bookmark-outline"></ion-icon>
            <?php endif;?>
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
            <p class="mb-0">Pengarang: <?=$content['content_author']?></p>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <?=$content['content_publish_date']?>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                            <ion-icon name="eye" class="text-success"></ion-icon>
                            <span style="font-size: 15px;">&nbsp;<?=$content['content_hits']?></span>
                        </span>
                    </div>
                    <?php if($content['is_liked']):?>
                    <span class="iconedbox iconedbox-sm text-primary" style="width: 60px;">
                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/love-blue.png')"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?=$content['like_count']?></span>
                    </span>
                    <?php else: ?>
                    <a onclick='btn_like()'>
                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                            <ion-icon style="content: url('<?=PATH_ASSETS?>icon/love-green.png')"></ion-icon>
                            <span style="font-size: 15px;">&nbsp;<?=$content['like_count']?></span>
                        </span>
                    </a>
                    <?php endif ?>
                    <div>
                        <span class="iconedbox iconedbox-sm" style="width: 60px;">
                            <ion-icon name="chatbox-ellipses" class="text-success"></ion-icon>
                            <span style="font-size: 15px;">&nbsp;<?=$content['comment_count']?></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-2 content-view">
                <?=str_replace("&quot;",'"',$content['content_desc'])?>
            </div>
            <?php if ($content['content_source']): ?>
            <p class="mb-0 mt-3" style="font-style: italic">Sumber: <?=$content['content_source']?></p>
            <?php endif;?>
        </div>
    </div>

    <div class="section full mt-2 mb-2">
        <div class="section-title">Komentar</div>
        <div class="wide-block pb-2">
            <div class="mt-1 pb-2">
                <form id="commentForm" method="post">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center" style="width: 100%">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <input type="text" class="form-control" name="comment_text" id="comment_text" placeholder="Tulis komentar anda" autocomplete="off">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ml-1">
                            <button type="submit" id="send" class="btn btn-icon btn-primary rounded">
                                <ion-icon name="send"></ion-icon>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- comment block -->
            <div id="showComments">
            </div>
            <!-- * comment block -->
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