<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('whatsnew')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->
<?php if($ceo_note_status){ ?>
    <!-- Extra Header -->
    <div class="extraHeader p-0">
        <ul class="nav nav-tabs lined" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#all" role="tab">
                    SEMUA CEO NOTE
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#mine" role="tab">
                    TULISAN SAYA
                </a>
            </li>
        </ul>
    </div>
    <!-- * Extra Header -->
<?php } ?>

<!-- App Capsule -->
<div id="appCapsule" class="<?php if($ceo_note_status){ ?>extra-header-active<?php } ?> mb-5">
    <div class="tab-content mt-1">
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            <div id="ceoContents" class="listview">
            </div>
        </div>
        <?php if($ceo_note_status){ ?>
            <div class="tab-pane fade" id="mine" role="tabpanel">
                <div id="myContents" class="listview">
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if($ceo_note_status){ ?>
        <!-- bottom right -->
        <div class="fab-button bottom-right" style="bottom: 80px;">
            <a href="<?=base_url('whatsnew/ceo_note/add')?>" class="fab bg-warning">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div>
        <!-- * bottom right -->
    <?php } ?>
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