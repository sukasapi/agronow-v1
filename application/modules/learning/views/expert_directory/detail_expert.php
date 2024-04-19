<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/expert_directory/list_expert/'.$data['cat_id'])?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">DETAIL EXPERT</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full p-2 mb-2">
        <div class="d-flex flex-row mb-2">
            <div class="d-flex align-items-center">
                <img src="<?= $data['member_image']; ?>" alt="avatar" class="imaged rounded" style="width:70px">
            </div>
            <div class="flex-grow-1 ml-2">
                <div>
                    <p class="m-0"><b><?= $data['em_name']; ?></b></p>
                    <p class="m-0" style="color:#a1389d"><?= $data['institution']; ?></p>
                    <div class="d-flex flex-row">
                        <div class="d-flex align-items-center">
                            <ion-icon name="person"></ion-icon>
                        </div>
                        <div class="flex-grow-1">
                            <p class="m-0" style="font-size:10px">&nbsp;<?= $data['title']; ?></p>
                        </div>
                    </div>
<!--                    <div class="d-flex align-items-center mt-1 mb-1">-->
<!--                        <span class="badge badge-warning">4.8</span>&nbsp;-->
<!--                        <ion-icon name="star" style="font-size: 20px; color:#fcc102"></ion-icon>-->
<!--                        <ion-icon name="star" style="font-size: 20px; color:#fcc102"></ion-icon>-->
<!--                        <ion-icon name="star" style="font-size: 20px; color:#fcc102"></ion-icon>-->
<!--                        <ion-icon name="star" style="font-size: 20px; color:#fcc102"></ion-icon>-->
<!--                        <ion-icon name="star-half" style="font-size: 20px; color:#fcc102"></ion-icon>&nbsp;-->
<!--                        <span class="m-0" style="font-size:10px">(128 Reviews)</span>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
        <p class="m-0"><b>Profile</b></p>
        <p class="m-0"><?= $data['profile']; ?></p>
        <?php if ($data['is_current']): ;?>
<!--        <div class="text-center p-2">-->
<!--            <a href="--><?//=base_url('home')?><!--" class="btn btn-warning rounded mt-1">EDIT PROFILE</a>-->
<!--        </div>-->
        <?php endif; ?>
        <div class="card mt-2">
            <div class="card-header" style="background:#fc3677; color:white">
                Data Pendidikan
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($data['education'] as $edu): ;?>
                    <div class="item">
                        <div class="dot bg-danger"></div>
                        <div class="content">
                            <h4 class="title" style="color:#61a9e1"><?= $edu['education']; ?></h4>
                            <div class="text" style="font-size:15px"><b><?= $edu['institution']; ?></b></div>
                            <div class="text"><i>Lulus Tahun <?= $edu['year']; ?></i></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header" style="background:#5ba3f8; color:white">
                Data Pengalaman
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php
                    if ($data['experience']):
                    foreach ($data['experience'] as $exp): ;?>
                    <div class="item">
                        <div class="dot bg-danger"></div>
                        <div class="content">
                            <h4 class="title" style="color:#61a9e1"><?= $exp['title']; ?></h4>
                            <div class="text" style="font-size:15px"><b><?= $exp['institution']; ?></b></div>
                            <div class="text"><i><?= $exp['yearStart'].' - '.$exp['yearEnd']; ?></i></div>
                        </div>
                    </div>
                    <?php endforeach;
                    endif;?>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header" style="background:#5cad37; color:white">
                Data Keahlian
            </div>
            <div class="card-body">
                <?php foreach ($data['qualification'] as $qua): ;?>
                <div class="content mb-4">
                    <h4 class="title m-0" style="color:#61a9e1"><?= $qua['title']; ?></h4>
                    <div class="mt-1 mb-1">
                        <?php $tmp_score = $qua['score'];
                        for($i=1;$i<=5; $i++):
                            if ($tmp_score >= 1): ?>
                            <ion-icon name="star" style="font-size: 20px; color:#fcc102"></ion-icon>
                            <?php $tmp_score--;
                            elseif ($tmp_score >= 0.5):
                            $tmp_score -= 0.5; ?>
                            <ion-icon name="star-half" style="font-size: 20px; color:#fcc102"></ion-icon>
                            <?php else: ?>
                            <ion-icon name="star-outline" style="font-size: 20px; color:#fcc102"></ion-icon>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="text" style="font-size:15px"><b>Skor <?= $qua['score']; ?></b></div>
                    <div class="text" style="font-size:15px"><b>Masa Berlaku <?= $qua['year']?$qua['year']:'-'; ?></b></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- bottom right -->
    <div class="fab-button bottom-right" style="bottom: 80px">
        <a href="<?=base_url('learning/expert_directory/list_chat/'.$data['em_id'])?>" class="fab bg-warning">
            <ion-icon name="chatbox-ellipses"></ion-icon>
        </a>
    </div>
    <!-- * bottom right -->
</div>
<!-- * App Capsule -->
<script>
    let capsuleWidth = document.getElementById("appCapsule").offsetWidth;
    let offsets = document.getElementById("appCapsule").getBoundingClientRect();
    let fab_pos = offsets.right-capsuleWidth+20;
    let fab = document.getElementsByClassName("fab-button");
    if (fab.length){
        fab[0].style.right = fab_pos+"px";
    }
</script>