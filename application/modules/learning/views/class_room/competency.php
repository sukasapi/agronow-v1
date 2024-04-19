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
</style>

<div id="blur_note">Upps, Anda keluar dari area soal. Klik disini untuk kembali ke area soal.</div>

<?php
// tampilan 
$label_kesempatan_test = '';
$label_kesempatan_test2 = '';
$css_btn_prev = 'd-none';
$css_btn_submit = 'd-none';

if($this->data['dataCt']['Attemp']=="1") {
	$label_kesempatan_test = '1 kali';
	$label_kesempatan_test2 = 'Submit';
	$css_btn_prev = 'd-none';
	$css_btn_submit = '';
} else if($this->data['dataCt']['Attemp']=="N") {
	$label_kesempatan_test = 'Tanpa batas';
	$label_kesempatan_test2 = 'Preview Nilai';
	$css_btn_prev = '';
	$css_btn_submit = 'd-none';
}

// cek tanggal dan jam dl
$time_now = strtotime("now");
$tgl_mulai = $this->data['dataCt']['ctStart'];
$tgl_selesai = $this->data['dataCt']['ctEnd'];
$time_start = $this->data['dataCt']['cr_time_start'];
$time_end = $this->data['dataCt']['cr_time_end'];

// mulai
$arrTime = explode(':',$time_start);
if(count($arrTime)=="2") {
	$arrTime[0] = (int) $arrTime[0];
	$arrTime[1] = (int) $arrTime[1];
	$jam = ($arrTime[0]<10)? "0".$arrTime[0] : $arrTime[0];
	$menit = ($arrTime[1]<10)? "0".$arrTime[1] : $arrTime[1];
	$time_start = $jam.':'.$menit.':00';
} else {
	$time_start = '00:00:00';
}
$time_start_ujian = strtotime($tgl_mulai.' '.$time_start);
$tgl_mulai_ujian = date('d F Y H:i:s',$time_start_ujian);

// selesai
$arrTime = explode(':',$time_end);
if(count($arrTime)=="2") {
	$arrTime[0] = (int) $arrTime[0];
	$arrTime[1] = (int) $arrTime[1];
	$jam = ($arrTime[0]<10)? "0".$arrTime[0] : $arrTime[0];
	$menit = ($arrTime[1]<10)? "0".$arrTime[1] : $arrTime[1];
	$time_end = $jam.':'.$menit.":59";
} else {
	$time_end = '00:00:59';
}
$time_end_ujian = strtotime($tgl_selesai.' '.$time_end);
$tgl_selesai_ujian = date('d F Y H:i:s',$time_end_ujian);

$is_allowed = ($time_now>=$time_start_ujian && $time_now<=$time_end_ujian)? true : false;


if(!$is_allowed) {
	$ui =
		'<div id="appCapsule">
		 <div class="section full mb-5">
			<div class="alert alert-info mt-2">
				Ujian dibuka '.$tgl_mulai_ujian.' s.d '.$tgl_selesai_ujian.'
			</div>
		 </div>
		 </div>';
		 
	echo $ui;
} else {
?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mb-5">
        <?php if($dataStep['CT']['ctStatus']=="2" ){ ?>
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
            <h3 class="text-center m-2">HASIL COMPETENCY TEST</h3>
            <?php if ($data['cr_show_nilai']): ?>
            <div class="text-center py-5 result">
                <div class="row">
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
                </div>
            </div>
            <?php else: ?>
                <div class="text-center py-5 result">
                    <div class="row">
                        <div class="col-12 align-self-center py-1">
                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
                            <h2>SELESAI</h2>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="m-3">
                <a href="<?=base_url('learning/class_room/home?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-primary mb-2">KEMBALI KE HOME</a>
            </div>
        <?php }else{ ?>
            <h3 class="text-center m-2">COMPETENCY TEST</h3>
            <?php if($dataCt['Desc'] != ""){ ?>
                <div class="alert alert-info m-2"><?=$dataCt['Desc'];?></div>
            <?php } ?>
            <div class="m-2">
				<span>Ujian dibuka <strong><?=$tgl_mulai_ujian.' s.d '.$tgl_selesai_ujian?></strong></span><br/>
                <span>Kesempatan Competency Test: <strong><?=$label_kesempatan_test?></strong></span>
            </div> 
            <?php 
                $quePerPage = $dataCt['QuePerPage'];
                $soalPage = 1;
                if(!$soal){
                    $soalPage = 1;
                }else{
                    $soalPage = ceil(count($soal)/intval($quePerPage)); 
                }
            ?>
            <input type="hidden" id="soalPage" value="<?= $soalPage; ?>">
			<input type="hidden" id="jumlah_soal" value="<?= count($soal); ?>">
            <div class="card bg-success rounded-0 sticky-top">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-9 py-3 pl-3">
                            <i class="fa fa-question-circle"></i> &nbsp; <?=@count($soal);?> soal
                        </div>
                        <div class="col-3 py-3 text-center bg-dark">
                            <i class="fa fa-clock-o"></i>  &nbsp; <span id="timer"><?= isset($dataCt['TimeLimit']) ? $dataCt['TimeLimit'] : '01:00'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 pb-0 text-center">
                <button type="button" class="button btn btn-lg btn-primary btn-outline-primary" id="button_start_competencytest" onclick="startCompetencyTest()">START COMPETENCY TEST</button>
            </div>
            <div id="divSoal" class="m-2 my-0">
                <?php if(!$soal){?>
                    <div class="alert alert-info">Data Competency Test tidak ditemukan.</div>
                <?php }else{?>
                    <form action="<?= base_url('learning/class_room/competency?cr_id='.$data['cr_id']) ?>" id="form" method="post" class="form-horizontal">
                        <input type="hidden" id="soalShow" value="1">
                        <?php
                        $i = 0;
                        foreach ($soal as $crs_id => $sl):
                            $i++;
                            $class = ceil(($i)/$quePerPage);
                            $classSoal = "soal".$class;
                            ?>
                            <ul class="list-group <?=$classSoal;?> mb-2" id="que<?=$i;?>" style="display:<?php if($classSoal=="soal1"){echo "block";}else{echo"none";}?>;">
                                <li class="list-group-item" id="soal<?=$i;?>" style="padding:5px 10px;">
                                    <table class="que" style="width:100%;">
                                        <tr>
                                            <td width="20"><?=$i;?>.</td>
                                            <td><?=url2image($sl['que']);?></td>
                                        </tr>
                                    </table>
                                </li>
                                <li class="list-group-item" style="background-color:#f5f5f5;">
                                    <table class="que" style="width:100%;">
                                        <?php foreach ($sl['ans'] as $j => $an): ?>
                                            <tr>
                                                <?php if(in_array($memberId, $specialId)){ ?>
                                                    <td width="30">
                                                        <input type="radio" name="choice[<?=$crs_id;?>]" id="choice<?=$crs_id;?>-<?=$j;?>" class="radio<?=$classSoal;?>" value="<?=$an;?>" required <?php if($an==$sl['right']){ echo 'checked'; } ?>>
                                                    </td>
                                                    <td <?php if($an==$sl['right']){ echo 'style="background:#B4DFF7;"';}?>>
                                                        <label for="choice<?=$crs_id;?>-<?=$j;?>" style="display:block; font-weight:normal; padding-left:5px;"> <?=$an;?></label>
                                                    </td>
                                                <?php }else{ ?>
                                                    <td width="30">
                                                        <input type="radio" name="choice[<?=$crs_id;?>]" id="choice<?=$crs_id;?>-<?=$j;?>" class="radio<?=$classSoal;?>" value="<?=$an;?>" required>
                                                    </td>
                                                    <td><label for="choice<?=$crs_id;?>-<?=$j;?>" style="display:block;font-weight:normal; padding-left:5px;"> <?=$an;?></label></td>
                                                <?php } ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </li>
                            </ul>
                        <?php
                        endforeach; ?>
                        <div class="row my-3" id="btnPrevNext">
                            <div class="col-6 col-sm-6 pull-left" align="left">
                                <button  type="button" id="prev" class="btn btn-warning" style="display:none;">
                                    <span class="btn-label"><i class="fa fa-arrow-left"></i></span>&nbsp;&nbsp;
                                    <span>Sebelumnya</span>
                                </button>
                            </div>
                            <div class="col-6 col-sm-6 pull-right" align="right">
                                <button type="button" id="next" class="btn btn-success" disabled="disabled">
                                    <span>Selanjutnya</span>&nbsp;&nbsp;
                                    <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                                </button>

                                <button type="button" id="submitConfirm" class="btn btn-success" style="display:none;">
                                    <span>Selanjutnya</span>&nbsp;&nbsp;
                                    <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                        <div class="p-2 mt-4" id="confirmTest" style="position:fixed; z-index:999999; width:100%; height:100%; top:0; left:0; background-color:#ffffff; overflow:hidden; display:none;">
                            <div class="text-center">
                                <div class="confirmtest">
                                    <div>KONFIRMASI<br/>COMPETENCY TEST</div>
                                    <img src="<?=PATH_ASSETS;?>icon/class_room_icon_confirm.png" style="width: 80px;"/>
                                </div>
                                <div align="center my-3" id="hasil_comp">
                                    <p>Pastikan anda telah menjawab dengan benar.</p>
                                    <p style="padding-top:30px;">Klik tombol <strong><?=$label_kesempatan_test2?></strong> untuk mengetahui hasil COMPETENCY TEST anda.</p>
                                </div>
                                
                                <div class="row my-3">
                                    <div class="col-6 col-sm-6 pull-left" align="left"> 
                                        <button  type="button" class="btn btn-warning" id="backToTest">
                                            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>&nbsp;&nbsp;
                                            <span>Cek Kembali</span>
                                        </button>
										
										<a class="d-none btn btn-warning" id="retakeTest" href="<?=base_url('learning/class_room/competency?cr_id='.$data['cr_id'])?>">Ulangi Ujian</a>
                                    </div>
                                    <div class="col-6 col-sm-6 pull-right" align="right">
										
										<div id="err_juml_jawaban_ui" class="text-danger"></div>
										
										<button type="button" name="previewAnswerCompetency" id="previewAnswerCompetency" class="btn btn-info <?=$css_btn_prev?>" value="1">
											<span>Preview Nilai</span>&nbsp;&nbsp;
                                            <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                                        </button>
										
										<button type="submit" name="sendAnswerCompetency" id="sendAnswerCompetency" class="btn btn-success <?=$css_btn_submit?>" value="1">
                                            <span>Submit</span>&nbsp;&nbsp;
                                            <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                                        </button>
										
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
<!-- * App Capsule -->

<?php } ?>