<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('learning/forum/listing?cat_id='.$forum['cat_id'])?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">DETAIL FORUM</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="wide-block p-2">
        <div class="d-flex align-items-center" style="background-color: white; color: black; border-radius: 15px;">
            <div class="p-1 d-flex align-items-center">
                <img src="<?= $forum['member_image']; ?>" alt="avatar" class="imaged rounded" style="width:70px">
            </div>
            <div class="p-1 flex-grow-1">
                <div>
                    <p class="m-0" style="color:#a1389d">by <?=$forum['member_name']?> <span style="color:#0f7696">(<?=$forum['group_name']?>)</span></p>
                    <div class="d-flex bd-highlight">
                        <div class="align-self-center">
                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_participan.png')"></ion-icon>
                                <span style="font-size: 15px;">&nbsp;<?=$forum['participant']?></span>
                            </span>
                        </div>
                        <div class="align-self-center">
                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_comment.png')"></ion-icon>
                                <span style="font-size: 15px;">&nbsp;<?=$forum['chat_count']?></span>
                            </span>
                        </div>
                        <div class="flex-grow-1 text-right" style="color:#0f7696"><?=$forum['date']?></div>
                    </div>
                </div>
            </div>
        </div>
        <p class="m-0 text-double" style="font-size: larger"><b><?=$forum['forum_name']?></b></p>
    </div>
    <div class="wide-block mt-1 p-2">
        <div class="content-view">
            <?=str_replace("&quot;",'"',$forum['group_name'])?>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Komentar (<?=$forum['chat_count']?>)</div>
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
</div>
<!-- * App Capsule -->