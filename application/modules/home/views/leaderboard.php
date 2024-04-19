<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">LEADERBOARD</div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader p-0">
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#alltime" role="tab">
                THIS PERIOD
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#thismonth" role="tab">
                THIS MONTH
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#group" role="tab">
                <?= $data['group']['group_name']; ?>
            </a>
        </li>
    </ul>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active mb-5">
    <div class="tab-content mt-1">
        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="alltime" role="tabpanel">
                <div class="listview">
                    <?php foreach ($data['leaderboard']['all_time'] as $m): ?>
                    <li<?=$m['member_id']==$this->member_id?' style="background: #ffec8a;"':'' ; ?>>
                        <!--item-->
                        <div class="d-flex flex-row p-0">
                            <div class="d-flex align-items-center p-1">
                                <div class="login-header">
                                    <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                    <div class="centered">
                                        <h2><?= $m['rank']; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center p-1">
                                <img src="<?= $m['member_image']; ?>" class="imaged w48">
                            </div>
                            <div class="d-flex flex-column p-1">
                                <div>
                                    <?php if ($m['member_id'] == $this->member_id): ?>
                                        <h3 class="card-text text-single">
                                            <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                        </h3>
                                    <?php else: ?>
                                        <h4 class="card-text text-single">
                                            <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                        </h4>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-1">
                                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png');"></ion-icon>
                                        <span style="font-size: 15px;">&nbsp;<?= $m['member_poin']; ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end item-->
                    </li>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="thismonth" role="tabpanel">
                <div class="listview">
                    <?php foreach ($data['leaderboard']['this_month'] as $m): ?>
                    <li<?=$m['member_id']==$this->member_id?' style="background: #ffec8a;"':'' ; ?>>
                        <div class="d-flex flex-row p-0">
                            <div class="d-flex align-items-center p-1">
                                <div class="login-header">
                                    <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                    <div class="centered">
                                        <h2><?= $m['rank']; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center p-1">
                                <img src="<?= $m['member_image']; ?>" class="imaged w48">
                            </div>
                            <div class="d-flex flex-column p-1">
                                <div>
                                    <?php if ($m['member_id'] == $this->member_id): ?>
                                    <h3 class="card-text text-single">
                                        <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                    </h3>
                                    <?php else: ?>
                                    <h4 class="card-text text-single">
                                        <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                    </h4>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-1">
                                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png');"></ion-icon>
                                        <span style="font-size: 15px;">&nbsp;<?= $m['member_poin']; ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="group" role="tabpanel">
                <div class="listview">
                    <?php foreach ($data['leaderboard']['group'] as $m): ?>
                    <li<?=$m['member_id']==$this->member_id?' style="background: #ffec8a;"':'' ; ?>>
                        <div class="d-flex flex-row p-0">
                            <div class="d-flex align-items-center p-1">
                                <div class="login-header">
                                    <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                    <div class="centered">
                                        <h2><?= $m['rank']; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center p-1">
                                <img src="<?= $m['member_image']; ?>" class="imaged w48">
                            </div>
                            <div class="d-flex flex-column p-1">
                                <div>
                                    <?php if ($m['member_id'] == $this->member_id): ?>
                                        <h3 class="card-text text-single">
                                            <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                        </h3>
                                    <?php else: ?>
                                        <h4 class="card-text text-single">
                                            <?=strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?>
                                        </h4>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-1">
                                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                        <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png');"></ion-icon>
                                        <span style="font-size: 15px;">&nbsp;<?= $m['member_poin']; ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
</div>
<!-- * App Capsule -->