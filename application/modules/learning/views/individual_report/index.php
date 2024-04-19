<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div.grade_bottom_right{
        font-size: 100px;
        font-weight: bold;
        position: absolute;
        bottom: 26px;
        right: 0px;
        z-index: 2;
    }
</style>

<div class="extraHeader p-0">
    <ul class="nav nav-tabs <?=$dcssT1?>" role="tablist">
        <li class="nav-item">
            <a class="nav-link p-1 <?=$dcssT2?>" data-toggle="tab" href="#corporate_culture" role="tab" aria-selected="true">Corporate Culture</a>
        </li>
        <li class="nav-item">
            <a class="nav-link p-1 active" data-toggle="tab" href="#class_room" role="tab" aria-selected="false">Class Room</a>
        </li>
        <li class="nav-item <?=$dcssT2?>">
            <a class="nav-link p-1" data-toggle="tab" href="#knowledge_sharing" role="tab" aria-selected="false">Knowledge Management</a>
        </li>
		<li class="nav-item d-none">
            <a class="nav-link p-1" data-toggle="tab" href="#evaluasi_lv3" role="tab" aria-selected="false">Evaluasi Pelatihan Level 3</a>
        </li>
    </ul>
</div>

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <div class="section full">
        <div class="tab-content m-0">
            <div class="tab-pane fade" id="corporate_culture" role="tabpanel">
                <div class="p-2 d-flex align-items-center" style="background:url('<?= PATH_ASSETS.'img/bg_pelatihan.png' ?>') bottom right no-repeat #f38700; height:140px; position:relative;">
                    <div class="d-block">
                        <h4 class="text-white mb-1 p-0">Laporan Corporate Culture</h4>
                        <h3 class="text-white d-block m-0 p-0"><?= $memberName; ?></h3>
                    </div>
                </div>
                <?php if(count($dataCulture)>0){ ?>
                    <?php foreach ($dataCulture as $i => $data) { ?>
                        <?php
                            $data['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $data['crm_step']);
                            $step = json_decode($data['crm_step'],true);
                        ?>
                        <?php if(isset($step['RESULT']) && @$step['RESULT'] != "" ){ ?>
                            <?php 
                                $bg = 'bg-danger';
                                if(in_array($step['RESULT'],array("A","B","C"))){
                                    $bg = 'bg-success';
                                }

                                $diff = abs(strtotime($data['cr_date_end']) - strtotime($data['cr_date_start']));
                                
                                $years = floor($diff / (365*60*60*24));
                                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                            ?>
                            <div class="card m-2">
                                <div class="card-header p-0 <?= $bg; ?>" style="height: 5px"></div>
                                <div class="card-body p-1">
                                    <h4><?=$data['cr_name'];?></h4>
                                    <div class="mb-1">
                                        <small><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$days;?> Hari Pelatihan</small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-success px-3 py-1"><ion-icon name="checkmark-circle"></ion-icon>Selesai</button>
                                        <?php if(in_array($step['RESULT'],array("A","B","C"))){ ?>
                                            <?php 
                                                $url = base_url('learning/individual_report/show_certificate').'?doc='.base64_encode($step['CERTIFICATE']);
                                            ?>
                                            <a href="<?=$url;?>" class="btn ml-1 px-3 py-1 text-white" style="background:#af5b12;">
                                                <img src="<?= PATH_ASSETS; ?>icon/class_room_icon_certificate.png" style="width: 16px;"/>&nbsp;&nbsp;&nbsp;&nbsp;Sertifikat
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <?php if(in_array($step['RESULT'],array("A","B","C"))){ ?>
                                        <div class="grade_bottom_right" style="color: #C0EBC2;"><?=$step['RESULT'];?></div>
                                    <?php }else{?>
                                        <div class="grade_bottom_right" style="color: #cf1c4e;"><?=$step['RESULT'];?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="card m-2">
                        <div class="card-body p-2 text-center">Tidak/belum ada data laporan pelatihan.</div>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane fade active show" id="class_room" role="tabpanel">
                <div class="p-2 d-flex align-items-center" style="background:url('<?= PATH_ASSETS.'img/bg_pelatihan.png' ?>') bottom right no-repeat #f38700; height:140px; position:relative;">
                    <div class="d-block">
                        <h4 class="text-white mb-1 p-0">Laporan Class Room</h4>
                        <h3 class="text-white d-block m-0 p-0"><?= $memberName; ?></h3>
                    </div>
                </div>
                <?php if(count($dataClassroom)>0){ ?>
                    <?php foreach ($dataClassroom as $i => $data) { ?>
                        <?php
                            $data['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $data['crm_step']);
                            $step = json_decode($data['crm_step'],true);
							
							$arrGrade = array("A","B","C");
							$ui_sertifikat = '';
							$ui_grade = '';
							
							if(isset($step['RESULT']) && @$step['RESULT'] != "" ){
								$bg = 'bg-danger';
								if(in_array($step['RESULT'],$arrGrade)){
									$bg = 'bg-success';
								}

								$diff = abs(strtotime($data['cr_date_end']) - strtotime($data['cr_date_start']));
								
								$years = floor($diff / (365*60*60*24));
								$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
								$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								
								// sertifikat
								if($data['cr_has_certificate']) {
									if(in_array($step['RESULT'],$arrGrade)){
										$url = base_url('learning/individual_report/show_certificate').'?doc='.base64_encode($step['CERTIFICATE']);
										$ui_sertifikat =
											'<a href="'.$url.'" class="btn ml-1 px-3 py-1 text-white" style="background:#af5b12;">
												<img src="'.PATH_ASSETS.'icon/class_room_icon_certificate.png" style="width: 16px;"/>&nbsp;&nbsp;&nbsp;&nbsp;Sertifikat
											</a>';
									}
								}
								
								// grade
								if($data['cr_show_nilai']) {
									if(in_array($step['RESULT'],$arrGrade)){
										$warna_grade = '#C0EBC2';
									} else {
										$warna_grade = '#cf1c4e';
									}
									$ui_grade = '<div class="grade_bottom_right" style="color: '.$warna_grade.';">'.$step['RESULT'].'</div>';
								}
								
							}
                        ?>
							<div class="card m-2">
                                <div class="card-header p-0 <?= $bg; ?>" style="height: 5px"></div>
                                <div class="card-body p-1">
                                    <h4><?=$data['cr_name'];?></h4>
                                    <div class="mb-1">
                                        <small><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$days;?> Hari Pelatihan</small>
                                    </div>
									<div>
                                        <button type="button" class="btn btn-outline-success px-3 py-1"><ion-icon name="checkmark-circle"></ion-icon>Selesai</button>
										<?=$ui_sertifikat?>
									</div>	
									<?=$ui_grade?>
                                </div>
                            </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="card m-2">
                        <div class="card-body p-2 text-center">Tidak/belum ada data laporan pelatihan.</div>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane fade" id="knowledge_sharing" role="tabpanel">
                <?php if(count($dataKS)>0){ ?>
                    <?php foreach ($dataKS as $i => $data) { ?>
                        <div class="card m-2">
                            <div class="card-header p-0 bg-primary" style="height: 5px"></div>
                            <div class="card-body p-1">
                                <small>Article:</small>
                                <h4><?=$data['content_name'];?></h4>
                                <small>Classroom:</small>
                                <h4><?=$data['classroom_name'];?></h4>
                                <div class="mb-1">
                                    <small>Tanggal : <?=$this->function_api->date_indo($data['content_create_date']);?></small>
                                </div>
                                <div>
                                    <?php if($data['content_status']=="publish"){ ?>
                                        <button type="button" class="btn btn-outline-success px-3 py-1"><ion-icon name="checkmark-circle"></ion-icon> Publish</button>
                                    <?php }elseif($data['content_status']=="draft"){ ?>
                                        <button type="button" class="btn btn-outline-warning px-3 py-1"><ion-icon name="document-text"></ion-icon> Draft</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="card m-2">
                        <div class="card-body p-1\2 text-center">Tidak/belum ada data laporan pelatihan.</div>
                    </div>
                <?php } ?>
            </div>
			<div class="tab-pane fade" id="evaluasi_lv3" role="tabpanel">
                <?php if(count($dataEvaluasiLv3)>0){ ?>
                    <?php foreach ($dataEvaluasiLv3 as $i => $data) { ?>
                        <?php 
							$diff = abs(strtotime($data['cr_date_end']) - strtotime($data['cr_date_start']));
							
							$years = floor($diff / (365*60*60*24));
							$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
							$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
							
							$css_pre = '';
							switch (true) {
								case $data['nilai_pre_test']>=75: 
									$css_pre = 'badge-primary';
									break;
								case $data['nilai_pre_test']>=50: 
									$css_pre = 'badge-success';
									break;
								case $data['nilai_pre_test']>=25: 
									$css_pre = 'badge-warning text-dark';
									break;
								default:
									$css_pre = 'badge-danger';
							}
							
							$css_post = '';
							switch (true) {
								case $data['nilai_post_test']>=75: 
									$css_post = 'badge-primary';
									break;
								case $data['nilai_post_test']>=50: 
									$css_post = 'badge-success';
									break;
								case $data['nilai_post_test']>=25: 
									$css_post = 'badge-warning text-dark';
									break;
								default:
									$css_post = 'badge-danger';
							}
							
							// saran dari penilai
							$saran = '';
							$sql = "select jawaban from _classroom_evaluasi_lv3_pairing where cr_id='".$data['cr_id']."' and id_dinilai='".$data['member_id']."' ";
							$res = $this->db->query($sql);
							$row = $res->result_array();
							foreach ($row as $key => $val) {
								$arrJ = json_decode($val['jawaban'],true);
								$saran .= '<li>'.$arrJ['saran'].'</li>';
							}
							if(!empty($saran)) $saran = '<ol class="m-0 pl-2">'.$saran.'</ol>';
							else $saran = $sql;
						?>
                        <div class="card m-2">
                            <div class="card-header p-0 bg-primary" style="height: 5px"></div>
                            <div class="card-body p-1">
                                <h4><?=$data['cr_name'];?></h4>
                                <div class="mb-1">
                                    <small><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$days;?> Hari Pelatihan</small>
                                </div>
								<div class="d-flex justify-content-between">
									<div class="border border-secondary rounded px-3 py-2 text-center">Sebelum Pelatihan<br/><span class="badge <?=$css_pre?>"><?=$data['nilai_pre_test']?></span></div>
									<div class="border border-secondary rounded px-3 py-2 text-center">Sesudah Pelatihan<br/><span class="badge <?=$css_post?>"><?=$data['nilai_post_test']?></span></div>
								</div>
                                <div>
									<div class="border border-secondary rounded mt-2 p-2">
										<h5>Saran dari Penilai:</h5>
										<?=$saran?>
									</div>
								</div>
                            </div>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="card m-2">
                        <div class="card-body p-1\2 text-center">Tidak/belum ada data laporan evaluasi pelatihan level 3.</div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- # App Capsule -->