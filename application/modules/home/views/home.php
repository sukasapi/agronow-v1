<?php $this->load->view('application_header', ['header_title' => 'What\'s New', 'header_type' => 1]); ?>

<!-- App Capsule -->
<div id="appCapsule" class="pt-0">
    <div class="section full">
        <div class="wide-block" style="background: url('<?=PATH_ASSETS?>icon/home_bg_header.png') no-repeat;background-size: 100% 90%; border-top: 0;">
            <div class="d-flex pt-2">
                <p class="text-white" id="livedatetime">Today</p>
            </div>
            <div class="d-flex justify-content-start pt-2">
                <div class="d-flex align-items-center">
                    <img src="<?=PATH_ASSETS?>icon/home_mascot.png" width="100px">
                </div>
                <div class="d-flex align-items-center ml-5">
                    <h3 class="text-white" style="vertical-align: middle;">
						<?=$data['salam'].'<br>'; ?>
						<?='<div class="mt-0 badge badge-primary">AgroWallet '.$wallet_tahun.': '.$wallet_saldo.'</div><br>'; ?>
						<?='<div class="mt-0 badge badge-primary">'.$nama_level_karyawan.'</div><br>'; ?>
						<?='<div class="mt-0 badge badge-primary">'.$data['member']['group'].'</div>'; ?>
					</h3>
                </div>
            </div>
            <div class="row h-200">
                <div class="col-12 my-auto mt-2">
                    <div class="card bg-white" style="border-radius: 32px !important;">
                        <div class="card-body p-0">
                            <div class="d-flex flex-row p-1">
                                <?php
									$frame = 'silver';
									if (isset($data['member']['badge_level']['mpl_name'])){
										if($data['member']['badge_level']['mpl_name'] == 'GOLD'){
											$frame = 'gold';
										}elseif($data['member']['badge_level']['mpl_name'] == 'PLATINUM'){
											$frame = 'platinum';
										}
									}
                                ?>
                                <div class="d-flex align-items-center text-center" style="background: url('<?= PATH_ASSETS.'/img/frame_foto_'.$frame.'.png' ?>');background-size: 80px; background-repeat: no-repeat; background-position: center center;width: 110px;">
                                    <img src="<?= $data['member']['member_image']; ?>" alt="image" class="imaged rounded" style="object-fit: fill;width: 60px; margin: auto;">
                                </div>
                                <div class="d-flex flex-column p-1" style="width:100%">
                                    <div class="">
                                        <h4 class="card-text text-single"><?= $data['member']['name']; ?></h4>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <div>
                                            <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" alt="image" width="24px">&nbsp;<b><?= $data['member']['rank_global']; ?></b>
                                        </div>
                                        <div>
                                           <img src="<?=PATH_ASSETS?>icon/home_coin.png" alt="image" width="24px">&nbsp;<b><?= $this->function_api->number($data['member']['total_saldo']); ?></b>
                                        </div>
                                        <div>
                                            <img src="<?=PATH_ASSETS?>icon/home_profile_ico_star.png" alt="image" width="24px">&nbsp;
                                            <b><?= $this->function_api->number($data['member']['total_point']); ?></b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<!-- fitur sementara: untuk nampilin info terkait agrowallet -->
	<div class="tab-content m-2">
		<div class="card border border-info">
			<div class="card-header bg-info text-white">Info AgroWallet</div>
			<div class="card-body">				
				<?php
				$sqlLW = "select content_desc from _content where content_id='16240' ";
				$queryLW = $this->db->query($sqlLW);
				$dataLW = $queryLW->result_array();
				
				echo $dataLW[0]['content_desc'];
				?>
			</div>
		</div>
	</div>
	
	<?php /*
    <div class="tab-content mt-1">
        <!-- pilled tab -->
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <div class="section full mt-1">
                <div class="d-flex">
                    <div class="section-title flex-fill">Leaderboard</div>
                    <div class="align-self-center pr-2">
                        <a href="<?= site_url('home/leaderboard'); ?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                    </div>
                </div>
                <div class="wide-block pt-2 pb-2">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                THIS PERIOD
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                THIS MONTH
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#contact" role="tab">
                                <?= $data['member']['group']; ?>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2">
                        <div class="tab-pane fade show active" id="home" role="tabpanel">
                            <div class="carousel-no-padding owl-carousel owl-theme">
                                <?php foreach ($data['leaderboard']['all_time'] as $m): ?>
                                <!--item-->
                                <div class="d-flex flex-row p-0">
                                    <div class="d-flex align-items-center p-1">
                                        <div class="login-header">
                                            <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                            <div class="centered">
                                                <h2><?= $m['member_rank']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <img src="<?= validate_member_image($m['member_image']); ?>" class="imaged w48">
                                    </div>
                                    <div class="d-flex flex-column p-1">
                                        <div>
                                            <h4 class="card-text text-single"><?=
                                                strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?></h4>
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
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel">
                            <div class="carousel-no-padding owl-carousel owl-theme">
                                <?php foreach ($data['leaderboard']['this_month'] as $m): ?>
                                <div class="d-flex flex-row p-0">
                                    <div class="d-flex align-items-center p-1">
                                        <div class="login-header">
                                            <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                            <div class="centered">
                                                <h2><?= $m['member_rank']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <img src="<?= validate_member_image($m['member_image']); ?>" class="imaged w48">
                                    </div>
                                    <div class="d-flex flex-column p-1">
                                        <div>
                                            <h4 class="card-text text-single"><?=
                                                strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?></h4>
                                        </div>
                                        <div class="mt-1">
                                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png');"></ion-icon>
                                                <span style="font-size: 15px;">&nbsp;<?= $m['member_poin']; ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="carousel-no-padding owl-carousel owl-theme">
                                <?php foreach ($data['leaderboard']['group'] as $m): ?>
                                <div class="d-flex flex-row p-0">
                                    <div class="d-flex align-items-center p-1">
                                        <div class="login-header">
                                            <img src="<?=PATH_ASSETS?>icon/home_profile_ico_trophy.png" class="imaged w48">
                                            <div class="centered">
                                                <h2><?= $m['member_rank']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center p-1">
                                        <img src="<?= validate_member_image($m['member_image']); ?>" class="imaged w48">
                                    </div>
                                    <div class="d-flex flex-column p-1">
                                        <div>
                                            <h4 class="card-text text-single"><?=
                                                strlen($m['member_name']) > 18 ? substr($m['member_name'], 0, 18) . '...' : $m['member_name']; ?></h4>
                                        </div>
                                        <div class="mt-1">
                                            <span class="iconedbox iconedbox-sm" style="width: 60px;">
                                                <ion-icon style="content: url('<?=PATH_ASSETS?>icon/home_profile_ico_star.png');"></ion-icon>
                                                <span style="font-size: 15px;">&nbsp;<?= $m['member_poin']; ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- * pilled tab -->
    </div>
	*/ ?>

    <div class="section mt-1 mb-1 pl-0 pr-0">
        <div style="height: 50px;overflow: hidden;position: relative;background: #633a00; color: white">
            <p class="running-text"><?=$data['cne']?></p>
        </div>
    </div>

    <?php if(count($classroom_list) > 0 || count($culture_list) > 0){ ?>
        <div class="section full">
            <div class="section-title">My Class</div>
<!--            <div class="pl-2">-->
                <div class="carousel-offering owl-carousel owl-theme">
                    <?php foreach ($classroom_list as $cr) { ?>
                        <div class="item">
                            <div class="row">
                                <div class="col-12 my-auto">
                                    <div class="section m-0 p-0">
                                        <div class="card text-center">
                                            <div class="card-header p-1"><h4>Class Room</h4></div>
                                            <div class="card-body">
                                                <p class="card-text text-double" style="height: 5ex;"><?= $cr['cr_name'] ?></p>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $cr['cr_percent']; ?>%;" aria-valuenow="<?= $cr['cr_percent']; ?>" aria-valuemin="0" aria-valuemax="100"><?= $cr['cr_percent']; ?>%</div>
                                                </div>
                                                <?php if($cr['cr_lp'] != ''){ ?>
                                                    <a href="<?= base_url('learning/class_room/info?cr_id='.$cr['cr_id']); ?>" class="btn btn-warning btn-sm rounded">Detail</a>
                                                <?php }else{ ?>
                                                    <a href="<?= base_url('learning/class_room/home?cr_id='.$cr['cr_id']); ?>" class="btn btn-warning btn-sm rounded">Detail</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php foreach ($culture_list as $cr) { ?>
                        <div class="item">
                            <div class="row">
                                <div class="col-12 my-auto">
                                    <div class="section m-0 p-0">
                                        <div class="card text-center">
                                            <div class="card-header p-1"><h4>Corporate Culture</h4></div>
                                            <div class="card-body">
                                                <p class="card-text text-double"><?= $cr['cr_name'] ?></p>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $cr['cr_percent']; ?>%;" aria-valuenow="<?= $cr['cr_percent']; ?>" aria-valuemin="0" aria-valuemax="100"><?= $cr['cr_percent']; ?>%</div>
                                                </div>
                                                <?php if($cr['cr_lp'] != ''){ ?>
                                                    <a href="<?= base_url('learning/corporate_culture/info?cr_id='.$cr['cr_id']); ?>" class="btn btn-warning btn-sm rounded">Detail</a>
                                                <?php }else{ ?>
                                                    <a href="<?= base_url('learning/corporate_culture/home?cr_id='.$cr['cr_id']); ?>" class="btn btn-warning btn-sm rounded">Detail</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
<!--            </div>-->
        </div>
    <?php } ?>

	<?php if(count($classroom_offering) > 0){ ?>
		<div class="section full mt-1">
            <div class="wide-block py-3">
                <div class="section-title">Recommended Class</div>
                <div class="carousel-<?=count($classroom_offering)==1?'one':'multiple'?> owl-carousel owl-theme">
                    <?php foreach ($classroom_offering as $cr) { ?>
                    <div class="item">
                        <div class="card product-card">
                            <div class="card-body">
                                <h2 class="title text-single mt-1 pb-2"><?= $cr['cr_name'] ?></h2>
                                <span class="text text-nowrap"><?=$this->function_api->date_indo($cr['cr_date_start']);?> - <?=$this->function_api->date_indo($cr['cr_date_end']);?></span>
                                <div class="price px-0">
                                    <ion-icon item-start name="wallet" class="icon"></ion-icon> <?= $this->function_api->number($cr['cr_price']); ?>
                                </div>
                                <a href="#" class="btn btn-sm btn-warning btn-block"> BELI</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
		</div>
	<?php } ?>

    <?php if(count($competencies) > 0 ){ ?>
        <div class="section full">
            <div class="section-title">My Competencies</div>
            <div class="carousel-offering owl-carousel owl-theme">
                <?php foreach ($competencies as $cpt):
//                    if ($cpt['cr_is_daily']) continue;
                    $cpt['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $cpt['crm_step']);
                    $dataStep = json_decode($cpt['crm_step'],true);
                    if ($dataStep && $dataStep['is_done_all']){
                        $prcntg = 100;
                    } else {
                        $capaian = $dataStep && $dataStep['hasil'] ? $dataStep['hasil'] : 0;
                        $prcntg = round(($capaian/$cpt['cr_komp_max_lv'])*100);
                    }

                    $url = base_url('learning/kompetensi/detail?cr_id='.$cpt['cr_id']);
                    ?>
                    <div class="item">
                        <div class="row">
                            <div class="col-12 my-auto">
                                <div class="section m-0 p-0">
                                    <div class="card text-center">
                                        <div class="card-header p-1"><h4>Competency</h4></div>
                                        <div class="card-body">
                                            <p class="card-text text-double" style="height: 5ex;"><?= $cpt['cr_name'] ?></p>
                                            <div class="progress mb-2">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $prcntg;?>%;" aria-valuenow="<?= $prcntg;?>" aria-valuemin="0" aria-valuemax="100"><?= $prcntg;?>%</div>
                                            </div>
                                            <a href="<?= base_url('learning/kompetensi/detail?cr_id='.$cpt['cr_id']); ?>" class="btn btn-warning btn-sm rounded">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php } ?>

    <div class="section full mt-2">
        <div class="wide-block p-1">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <?php for ($k=1;$k<(count($ads));$k++): ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="<?= $k; ?>"></li>
                    <?php endfor; ?>
                </ol>
                <div class="carousel-inner" style="border-radius: 1rem;">
                    <?php
                    $ad_active = ' active';
                    foreach ($ads as $ad): ?>
                    <div class="carousel-item<?= $ad_active ;?>">
                        <a href="<?= $ad->ads_link; ?>" target="_blank">
                            <img class="d-block w-100" src="<?=site_url().'media/image/'.$ad->ads_image?>" alt="<?= $ad->ads_sponsor; ?>">
                        </a>
                    </div>
                    <?php
                    $ad_active = '';
                    endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <div class="section full">
        <div class="wide-block pt-1 pb-2" style="background: #e0fdb3;">
            <div class="d-flex mb-2">
                <div class="section-title flex-fill p-0">Latest News</div>
                <div class="align-self-center">
                    <a href="<?=base_url('whatsnew/news')?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                </div>
            </div>
            <div class="carousel-multiple owl-carousel owl-theme">
                <?php foreach($data['news'] as $d): ?>
                <a href="<?=$d['detail_url']?>">
                <div class="item">
                    <div class="card">
                        <?php if(!empty($d['image'])):?>
                        <img src="<?=$d['image']?>" class="card-img-top" alt="image" style="width: 100%; height: 111px; object-fit: cover;">
                        <?php else: ?>
                        <img src="assets/img/sample/photo/wide1.jpg" class="card-img-top" alt="image" style="width: 100%; max-height: 111px; object-fit: cover;">
                        <?php endif ?>
                        <div class="card-body p-1">
                            <p class="text-triple m-0" style="font-size:13px;color:black;line-height: 2.5ex;height: 7.5ex;"><b><?=$d['title']?></b></p>
                            <p class="card-text text-right"><small><?=$d['date']?></small></p>
                        </div>
                    </div>
                </div>
                </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="section full">
        <div class="carousel-no-padding owl-carousel owl-theme">
            <div class="item">
                <div class="mt-2 mb-2">
					<div class="d-flex justify-content-between pl-2 pr-2 pb-1">
						<div class="section-title p-0">CEO Note</div>
                        <div>
                            <a href="<?=base_url('whatsnew/ceo_note')?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                        </div>
                    </div>
                    <?php if(!empty($data['ceo_note']['image'])):?>
                    <img src="<?=$data['ceo_note']['image']?>" class="pl-2 pr-2" alt="image" style="width: 100%; max-height: 210px; object-fit: cover;">
                    <?php else: ?>
                    <img src="assets/img/sample/photo/wide1.jpg" class="pl-2 pr-2" alt="image" style="width: 100%; max-height: 210px; object-fit: cover;">
                    <?php endif ?>
                    <div class="d-flex justify-content-between pl-2 pr-2">
                        <div style="max-width:80%">
                            <p class="text-single mb-1"><b><?=$data['ceo_note']['title']?></b></p>
                            <p class="card-text"><small><?=$data['ceo_note']['date']?></small></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="<?= $data['ceo_note']['detail_url']; ?>" class="btn btn-warning btn-sm rounded">READ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="mt-2 mb-2">
                    <div class="d-flex justify-content-between pl-2 pr-2 pb-1">
                        <div class="section-title p-0">BOD Share</div>
                        <div>
                            <a href="<?=base_url('whatsnew/bod_share')?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                        </div>
                    </div>
                    <?php if(!empty($data['bod_share']['image'])):?>
                    <img src="<?=$data['bod_share']['image']?>" class="pl-2 pr-2" alt="image" style="width: 100%; max-height: 210px; object-fit: cover;">
                    <?php else: ?>
                    <img src="assets/img/sample/photo/wide1.jpg" class="pl-2 pr-2" alt="image" style="width: 100%; max-height: 210px; object-fit: cover;">
                    <?php endif ?>
                    <div class="d-flex justify-content-between pl-2 pr-2">
                        <div style="max-width:80%">
                            <p class="text-single mb-1"><b><?=$data['bod_share']['title']?></b></p>
                            <p class="card-text"><small><?=$data['bod_share']['date']?></small></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="<?= $data['bod_share']['detail_url']; ?>" class="btn btn-warning btn-sm rounded">READ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section full">
        <div class="wide-block pt-1 pb-1" style="background: #fdefc6;">
            <div class="d-flex justify-content-between mb-2">
                <div class="section-title p-0">Latest Article</div>
                <div>
                    <a href="<?=base_url('whatsnew/article')?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                </div>
            </div>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" style="border-radius: 1rem;">
                    <ol id="myCarousel-indicators" class="carousel-indicators m-0" style="justify-content: flex-end;">
                        <?php foreach($data['article'] as $k=>$d): ?>
                        <li data-target="#myCarousel" data-slide-to="<?=$k?>" <?=$k==0?'class="active"':''?>></li>
                        <?php endforeach ?>
                    </ol>
                    <?php foreach($data['article'] as $k=>$d): ?>
                    <div class="carousel-item <?=$k==0?'active':''?>" style="height: 200px;">
                        <a href="<?=$d['detail_url']?>">
                        <div class="card bg-dark text-white">
                            <?php if(!empty($d['image'])):?>
                            <img src="<?=$d['image']?>" class="card-img overlay-img" alt="image" style="width: 100%; max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                            <img src="<?= PATH_ASSETS; ?>img/sample/photo/wide1.jpg" class="card-img overlay-img" alt="image" style="width: 100%; max-height: 200px; object-fit: cover;">
                            <?php endif ?>
                            <div class="card-img-overlay">
                                <p class="text-text"><b><?=$d['title']?></b></p>
                                <p class="card-text" style="padding-top: 20%"><small><?=$d['date']?></small></p>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php endforeach ?>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <div class="section full p-2">
        <div class="row">
            <div class="col-6 my-auto">
                <a href="<?= site_url('learning/expert_directory'); ?>" style="color: #4f5050">
                    <div class="card mb-2">
                        <div class="text-center p-1">
                            <img src="<?=PATH_ASSETS?>icon/home_exprtdir_ils.png" alt="image" style="object-fit: cover;width:100px;height:100px;">
                        </div>
                        <div class="card-body p-1 text-center">
                            <p class="text-single"><b>Expert Directory</b></p>
                            <p class="card-text" style="line-height: 2.5ex;height: 7.5ex;font-size: 13px;">Diskusi dengan Expert dan selesaikan masalah anda</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 my-auto">
                <a href="<?= site_url('learning/forum'); ?>" style="color: #4f5050">
                    <div class="card mb-2">
                        <div class="text-center p-1">
                            <img src="<?=PATH_ASSETS?>icon/home_forum_ils.png" alt="image" style="object-fit: cover;width:100px;height:100px;">
                        </div>
                        <div class="card-body p-1 text-center">
                            <p class="text-single"><b>Forum</b></p>
                            <p class="card-text" style="line-height: 2.5ex;height: 7.5ex;font-size: 13px;">Saling menyapa dan berbagi pengalaman</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 my-auto">
                <a href="<?= site_url('learning/digital_library'); ?>" style="color: #4f5050">
                    <div class="card mb-2">
                        <div class="text-center p-1">
                            <img src="<?=PATH_ASSETS?>icon/learningroom_ico_digilib.png" alt="image" style="object-fit: cover;width:100px;height:100px;">
                        </div>
                        <div class="card-body p-1 text-center">
                            <p class="text-single"><b>Digital Library</b></p>
                            <p class="card-text" style="line-height: 2.5ex;height: 7.5ex;font-size: 13px;">Dokumen digital dalam format teks, audio, dan video</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 my-auto">
                <a href="<?= site_url('learning/knowledge_sharing'); ?>" style="color: #4f5050">
                    <div class="card mb-2">
                        <div class="text-center p-1">
                            <img src="<?=PATH_ASSETS?>icon/learningroom_ico_knwldgshr.png" alt="image" style="object-fit: cover;width:100px;height:100px;">
                        </div>
                        <div class="card-body p-1 text-center">
                            <p class="text-single"><b>Knowledge Management</b></p>
                            <p class="card-text" style="line-height: 2.5ex;height: 7.5ex;font-size: 13px;">Sarana berbagi pengetahuan antar member</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="section full">
        <div class="wide-block pt-1 pb-2">
            <div class="d-flex">
                <div class="section-title flex-fill p-0 mb-2">Announcement</div>
                <div class="align-self-center">
                    <a href="<?=base_url('whatsnew/announcement')?>"><ion-icon name="chevron-forward-outline" style="color: black;"></ion-icon></a>
                </div>
            </div>
			<div class="card">
				<img src="<?=$data['announcement']['image']?>" class="card-img-top" alt="image">
				<div class="card-body">
					<h6 class="card-title"><?=$data['announcement']['title']?></h6>
					<div class="text-triple"><?=$data['announcement']['isi']?></div>
					<div class="text-right">
						<a href="<?=base_url('whatsnew/announcement/detail/'.$data['announcement']['id'])?>" class="btn btn-warning rounded mt-2">BACA</a>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
