<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('portal/forum/sub?cat_id='.$content['cat_id'])?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">DETAIL FORUM PORTAL <?=$this->session->userdata('member_group')?></div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="wide-block p-2">
        <div class="d-flex align-items-center" style="background-color: white; color: black; border-radius: 15px;">
            <div class="p-1 d-flex align-items-center">
                <img src="<?=PATH_ASSETS?>img/avatar.png" alt="avatar" class="imaged rounded" style="width:70px">
            </div>
            <div class="p-1 flex-grow-1">
                <div>
                    <p class="m-0" style="color:#a1389d">by <?=$content['member']?></p>
                    <div class="d-flex bd-highlight">
                        <div class="align-self-center">
                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_participan.png')"></ion-icon>
                                <span style="font-size: 15px;">&nbsp;<?=$content['participant']?></span>
                            </span>
                        </div>
                        <div class="align-self-center">
                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/forum_archive_ico_comment.png')"></ion-icon>
                                <span style="font-size: 15px;">&nbsp;<?=$content['comment']?></span>
                            </span>
                        </div>
                        <div class="flex-grow-1 text-right" style="color:#0f7696"><?=$content['date']?></div>
                    </div>
                </div>
            </div>
        </div>
        <p class="m-0 text-double" style="font-size: larger"><b><?=$content['title']?></b></p>
    </div>
    <div class="wide-block mt-1 p-2">
        <div class="content-view">
            <?=str_replace("&quot;",'"',$content['desc'])?>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Diskusi</div>
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