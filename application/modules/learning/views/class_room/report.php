<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div.alert p{
        margin-bottom: 5px;
    }
    table.que td{
        vertical-align: top;
    }
    table.que label{
        margin-bottom: 0px;
    }
    .confirmtest{
        background: #F4AD13;
        padding:30px 0;
        margin-bottom:30px;
        text-align:center;
        font-size:24px;
        line-height:34px;
        font-weight:800;
        color:#ffffff;
    }
    div.result{
        background-color: <?= ($type == 'passed') ? '#0BCD0F' : '#CC1417'; ?>
    }
    div.result img{
        width: 80px;
    }
    div.result h2{
        color: #FFFFFF;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if (!$data['cr_has_kompetensi_test']):
            $certificate = $dataStep['CERTIFICATE'];
            $type = 'passed';
            ?>
            <style type="text/css">
                div.result{
                    background-color: #0BCD0F;
                }
                div.result img{
                    width: 80px;
                }
                div.result h2{
                    color: #FFFFFF;
                }
            </style>
            <div class="text-center py-5 result">
                <div class="row">
                    <div class="col-12 align-self-center py-1">
                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
                        <h2>SELESAI</h2>
                    </div>
                </div>
            </div>
        <?php elseif($data['cr_has_kompetensi_test'] && $dataStep['CT']['ctStatus']=="2" ): ?>
            <?php
            $arrScore = explode("-",$dataStep['CT']['ctScore']);
            $score = "";
            if(isset($arrScore[0])){
                $score = $arrScore[0];
            }

            $certificate = $dataStep['CERTIFICATE'];
            $type = 'passed';

            if($score == 'D'){
                $type = 'failed';
            }
            ?>
            <style type="text/css">
                div.result{
                    background-color: <?= ($type == 'passed') ? '#0BCD0F' : '#CC1417'; ?>
                }
                div.result img{
                    width: 80px;
                }
                div.result h2{
                    color: #FFFFFF;
                }
            </style>
<!--            <h3 class="text-center m-2">HASIL COMPETENCY TEST</h3>-->
            <div class="text-center py-5 result">
                <div class="row">
                    <?php if ($data['cr_show_nilai']): ?>
                        <div class="col-6 align-self-center py-1">
                            <?php if($type == 'passed'){ ?>
                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
                                <h2>LULUS</h2>
                            <?php }else{ ?>
                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_gagal.png" class="mb-1">
                            <?php } ?>
                        </div>
                        <div class="col-6 align-self-center py-1" style="font-size:120px; font-weight:800; color:<?= ($type == 'passed') ? '#ADF3C4' : '#FFFFFF'; ?>;">
                            <?php
                            if(isset($step['RESULT'])){ echo $step['RESULT'];}
                            elseif(isset($dataStep['RESULT'])){ echo $dataStep['RESULT'];}
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="col-12 align-self-center py-1">
                            <?php if($type == 'passed'){ ?>
                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
                                <h2>SELESAI</h2>
                            <?php }else{ ?>
                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_gagal.png" class="mb-1">
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($type != 'failed'){ ?>
            <?php
            if ($data['cr_has_certificate'] == '1'):
//				$targetFilePath = getcwd().'/'.SERTIFIKAT_PATH.basename($certificate);
//				if (!is_file($targetFilePath)) {
//					file_put_contents($targetFilePath, file_get_contents($certificate));
//				}
				$certificate_url = base_url().SERTIFIKAT_PATH.basename($certificate).'&v='.uniqid('');
				$certificate_url_dl = base_url().SERTIFIKAT_PATH.basename($certificate).'?v='.uniqid('');
                ?>
                <div class="m-3 text-center">
					<?php
					$pesanS = $this->session->flashdata('info');
					if(!empty($pesanS)){
						echo '<div class="alert alert-info mb-2">'.$pesanS.'</div>';
					} ?>
					
                    <img src="<?= PATH_ASSETS.'icon/class_room_icon_certificate.png'; ?>" style="width: 200px;">
                    <br/>
                    <br/>
                    <div class="justify-content-between">
						<a href="<?= base_url('learning/class_room/sertifikat_doc').'?doc='.base64_encode($certificate_url).'&cr_id='.$data['cr_id']; ?>" class="btn btn-success">Lihat Sertifikat</a> &nbsp;
                        <a href="<?= $certificate_url_dl; ?>" class="btn btn-primary" download>Download</a><br/>
						<a href="<?= base_url('learning/class_room/sertifikat_reset?cr_id='.$data['cr_id']); ?>" class="btn btn-primary mt-1">Regenerate Sertifikat</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php }; ?>
        <div class="m-3">
            <a href="<?=base_url('learning/class_room/home?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-primary mb-2">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
<!-- * App Capsule -->
