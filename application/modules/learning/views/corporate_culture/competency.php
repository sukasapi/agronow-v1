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

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
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
            <?php if($type == 'passed'){ ?>
                <?php
                    if (isset($data['cr_has_certificate']) && $data['cr_has_certificate'] === '1'):
                    $certificate_url = '';
                    if(strpos($certificate, 'http') !== false){
                        if(strpos($certificate, base_url()) === false){
                            // other site
                            $targetFilePath = getcwd().'/'.SERTIFIKAT_PATH.basename($certificate);
                            if (!is_file($targetFilePath)) {
                                file_put_contents($targetFilePath, file_get_contents($certificate));
                            }
                        }
                    }

                    $certificate_url = base_url().SERTIFIKAT_PATH.basename($certificate);
                ?>
                <div class="m-3 text-center">
                    <img src="<?= PATH_ASSETS.'icon/class_room_icon_certificate.png'; ?>" style="width: 200px;">
                    <br/>
                    <br/>
                    <div class="justify-content-between">
                        <a href="<?= base_url('learning/corporate_culture/sertifikat_doc').'?doc='.base64_encode($certificate_url).'&cr_id='.$data['cr_id']; ?>" class="btn btn-success">Lihat Sertifikat</a> &nbsp;
                        <a href="<?= $certificate_url; ?>" class="btn btn-primary" download>Download</a>
                    </div>
                </div>
                    <?php endif; ?>
            <?php }; ?>
            <div class="m-3">
                <a href="<?=base_url('learning/corporate_culture/home?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-primary">KEMBALI KE HOME</a>
            </div>
        <?php }else{ ?>
            <h3 class="text-center m-2">COMPETENCY TEST</h3>
            <?php if($dataCt['Desc'] != ""){ ?>
                <div class="alert alert-info m-2"><?=$dataCt['Desc'];?></div>
            <?php } ?>
            <div class="m-2">
                <span>Kesempatan Competency Test: <strong>1 kali</strong></span>
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
                    <form action="<?= base_url('learning/corporate_culture/competency?cr_id='.$data['cr_id']) ?>" id="form" method="post" class="form-horizontal">
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
                                    <div>KONFIRMASI<br/>COMPETENCY TEST</div>
                                    <img src="<?=PATH_ASSETS;?>icon/class_room_icon_confirm.png" style="width: 80px;"/>
                                </div>
                                <div align="center my-3">
                                    <p>Pastikan anda telah menjawab dengan benar.</p>
                                    <p style="padding-top:30px;">Klik tombol <strong>SUBMIT</strong> untuk mengetahui hasil COMPETENCY TEST anda.</p>
                                </div>
                                
                                <div class="row my-3">
                                    <div class="col-6 col-sm-6 pull-left" align="left"> 
                                        <button  type="button" class="btn btn-warning" id="backToTest">
                                            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>&nbsp;&nbsp;
                                            <span>KEMBALI</span>
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6 pull-right" align="right">
                                        <button type="submit" name="sendAnswerCompetency" class="btn btn-success" value="1">
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