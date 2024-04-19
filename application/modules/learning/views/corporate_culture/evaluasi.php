<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div.alert p{
        margin-bottom: 0px;
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

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if($ulang != '1' && $dataStep['MP'][$module]['EvaStatus'] == '2' && strtotime($dataMp['Module'][$module]['ModuleStart']) <= strtotime(date('Ymd'))){ ?>
            <?php
                $arrEvaScore = explode("-",$dataStep['MP'][$module]['EvaScore']);
                $score = $arrEvaScore[0];
                $countSoal = $arrEvaScore[1];
                $true = $arrEvaScore[2];
                $false = $arrEvaScore[3];

                $vd = [];
                $vd['result_title'] = "HASIL EVALUASI MODUL ".($module+1);
                $vd['result_head'] = array('Jumlah Soal', 'Benar', 'Salah');
                $vd['result_body'] = array($countSoal, $true, $false);
                $vd['buttons'] = [];
                
                if($score=="D"){
                    $vd['type'] = 'failed';
                    if ($attempt == 0){
                        $vd['buttons'][] = array('url' => '#', 'btn_color' => 'btn-secondary disabled', 'title' => 'ULANGI EVALUASI');
                    } else {
                        $vd['buttons'][] = array('url' => base_url('learning/class_room/evaluasi?cr_id='.$data['cr_id'].'&module='.$module.'&ulang=1'), 'btn_color' => 'btn-primary', 'title' => 'ULANGI EVALUASI');
                    }
                }else{
                    $vd['type'] = 'passed';
                }

                $vd['buttons'][] = array('url' => base_url('learning/corporate_culture/module?cr_id='.$data['cr_id']), 'btn_color' => 'btn-warning', 'title' => 'MODUL PELATIHAN');
                $vd['buttons'][] = array('url' => base_url('learning/corporate_culture/home?cr_id='.$data['cr_id']), 'btn_color' => 'btn-primary', 'title' => 'KEMBALI KE HOME');

                $this->load->view('learning/corporate_culture/result', $vd);
            ?>
        <?php }else{
            if ($attempt == 0) redirect(base_url('learning/corporate_culture/home?cr_id='.$data['cr_id']));
            ?>
            <h3 class="text-center m-2">EVALUASI</h3>
            <?php if($dataMp['Module'][$module]['Evaluasi']['Desc']!=""){ ?>
                <div class="alert alert-info m-2"><?=$dataMp['Module'][$module]['Evaluasi']['Desc'];?></div>
            <?php } ?>
            <div class="m-2">
                <span>Kesempatan Evaluasi Test: <strong><?=$dataMp['Module'][$module]['Evaluasi']['Attemp']>=0?$dataMp['Module'][$module]['Evaluasi']['Attemp'].' kali':'Tidak Terbatas';?></strong></span>
            </div> 
            <?php 
                $quePerPage = $dataMp['Module'][$module]['Evaluasi']['QuePerPage'];
                $soalPage = 1;
                if(!$soal){
                    $soalPage = 1;
                }else{
                    $soalPage = ceil(count($soal)/intval($quePerPage)); 
                }
            ?>
            <input type="hidden" id="soalPage" value="<?= $soalPage; ?>">
            <div class="card bg-success rounded-0 sticky-top">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-9 py-3 pl-3">
                            <i class="fa fa-question-circle"></i> &nbsp; <?=@count($soal);?> soal
                        </div>
                        <div class="col-3 py-3 text-center bg-dark">
                            <i class="fa fa-clock-o"></i>  &nbsp; <span id="timer"><?= isset($dataMp['Module'][$module]['Evaluasi']['TimeLimit']) ? $dataMp['Module'][$module]['Evaluasi']['TimeLimit'] : '01:00'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 pb-0 text-center">
                <button type="button" class="button btn btn-lg btn-primary btn-outline-primary" id="button_start_evaluasitest" onclick="startEvaluasiTest()">START EVALUASI TEST</button>
            </div>
            <div id="divSoal" class="m-2 my-0">
                <?php if(!$soal){?>
                    <div class="alert alert-info">Data Evaluasi tidak ditemukan.</div>
                <?php }else{?>
                    <form action="<?= base_url('learning/corporate_culture/evaluasi?cr_id='.$data['cr_id'].'&module='.$module) ?>" id="form" method="post" class="form-horizontal">
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
                                            <td><?=$sl['que'];?></td>
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
                        <div class="p-2" id="confirmTest" style="position:fixed; z-index:999999; width:100%; height:100%; top:0; left:0; background-color:#ffffff; overflow:hidden; display:none;">
                            <div class="text-center">
                                <div class="confirmtest">
                                    <div>KONFIRMASI<br/>EVALUASI</div>
                                    <img src="<?=PATH_ASSETS;?>icon/class_room_icon_confirm.png" style="width: 80px;"/>
                                </div>
                                <div align="center my-3">
                                    <p>Pastikan anda telah menjawab dengan benar.</p>
                                    <p style="padding-top:30px;">Klik tombol <strong>SUBMIT</strong> untuk mengetahui hasil EVALUASI anda.</p>
                                </div>
                                
                                <div class="row my-3">
                                    <div class="col-6 col-sm-6 pull-left" align="left"> 
                                        <button  type="button" class="btn btn-warning" id="backToTest">
                                            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>&nbsp;&nbsp;
                                            <span>KEMBALI</span>
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6 pull-right" align="right">
                                        <button type="submit" name="sendAnswerEvaluasi" class="btn btn-success" value="1">
                                            <span>SUBMIT</span>&nbsp;&nbsp;
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