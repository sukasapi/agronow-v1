<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">RIWAYAT SALDO DAN POIN</div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader p-0">
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#poin" role="tab">
                POIN
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#saldo" role="tab">
                SALDO
            </a>
        </li>
    </ul>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active mb-5">
    <div class="tab-content mt-1">
        <!-- poin tab -->
        <div class="tab-pane fade show active" id="poin" role="tabpanel">
            <div class="section mt-2">
                <?php if ($data['poin_history']): ?>
                <div class="wide-block">
                    <!-- timeline -->
                    <div class="timeline timed">
                        <?php $prev = '';
                        $today = date('D, m Y');
                        foreach ($data['poin_history'] as $h):
                            $date = date_create($h['mp_create_date']);
                            $tgl = date_format($date, 'D, d M');
                            $curr = $tgl;
                            $time = date_format($date, 'H:i');
                            if ($h['mp_poin']): ;?>
                            <div class="item">
                                <?php if ($curr != $today && $prev!=$curr ):
                                    $prev=$curr ;?>
                                    <span class="time"><?= $tgl.'<br>'.$time; ?></span>
                                <?php else: ?>
                                    <span class="time"><?= $time; ?></span>
                                <?php endif;?>
                                <div class="dot bg-success"></div>
                                <div class="content">
                                    <h4 class="title"><?= $h['mp_name']; ?></h4>
                                    <div class="text">+<?= $h['mp_poin']; ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ;?>
                    </div>
                    <!-- * timeline -->
                </div>
                <?php else: ?>
                    <div class="m-2 text-center">Belum ada riwayat poin</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="tab-pane fade" id="saldo" role="tabpanel">
            <div class="section mt-2">
                <?php if ($data['saldo_history']): ?>
                <div class="wide-block">
                    <!-- timeline -->
                    <div class="timeline timed">
                        <?php $prev = '';
                        $today = date('D, m Y');
                        foreach ($data['saldo_history'] as $h):
                            $date = date_create($h['ms_create_date']);
                            $tgl = date_format($date, 'D, d M');
                            $curr = $tgl;
                            $time = date_format($date, 'H:i');
                            if ($h['ms_saldo']): ;?>
                                <div class="item">
                                    <?php if ($curr != $today && $prev!=$curr ):
                                        $prev=$curr ;?>
                                        <span class="time"><?= $tgl.'<br>'.$time; ?></span>
                                    <?php else: ?>
                                        <span class="time"><?= $time; ?></span>
                                    <?php endif;?>
                                    <div class="dot <?= $h['ms_type']=='IN'?'bg-success':'bg-danger'; ?>"></div>
                                    <div class="content">
                                        <h4 class="title"><?= $h['ms_name']; ?></h4>
                                        <div class="text"><?= $this->function_api->number($h['ms_saldo']); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ;?>
                    </div>
                    <!-- * timeline -->
                </div>
                <?php else: ?>
                    <div class="m-2 text-center">Belum ada riwayat saldo</div>
                <?php endif; ?>
            </div>
        </div>
</div>
<!-- * App Capsule -->