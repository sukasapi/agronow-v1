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

<div id="blur_note">Upps, Anda keluar dari area soal. Klik disini untuk kembali ke area soal.</div>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full pretest mb-5">
        <?php if($dataStep['PT']['ptStatus'] == '2' && $reTest == '0'){ ?>
            <?php
                $arrPtScore =explode("-",$dataStep['PT']['ptScore']);
                $score = $arrPtScore[0];
                $countSoal = $arrPtScore[1];
                $true = $arrPtScore[2];
                $false = $arrPtScore[3];

                $vd = [];
                $vd['result_title'] = "HASIL PRETEST";
                $vd['result_head'] = array('Jumlah Soal', 'Benar', 'Salah');
                $vd['result_body'] = array($countSoal, $true, $false);
                $vd['buttons'] = [];

                if($score=="D"){
                    $vd['type'] = 'failed';
                    $vd['buttons'][] = array('url' => base_url('learning/class_room/pretest?cr_id='.$data['cr_id'].'&reTest=1'), 'btn_color' => 'btn-primary', 'title' => 'ULANGI PRETEST');
                }else{
                    $vd['type'] = 'passed';
                    $vd['buttons'][] = array('url' => base_url('learning/class_room/module?cr_id='.$data['cr_id']), 'btn_color' => 'btn-warning', 'title' => 'MODUL PELATIHAN');
                }

                $vd['buttons'][] = array('url' => base_url('learning/class_room/home?cr_id='.$data['cr_id']), 'btn_color' => 'btn-primary', 'title' => 'KEMBALI KE HOME');

                $this->load->view('learning/class_room/result', $vd);
            ?>
        <?php }else{ ?>
            <h3 class="text-center m-2">PRE TEST</h3>
            <?php if(@$dataPt['Desc']!=""){ ?>
                <div class="alert alert-info m-2"><?=$dataPt['Desc'];?></div>
            <?php } ?>

            <?php 
            $quePerPage = $dataPt['QuePerPage']?$dataPt['QuePerPage']:1;
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
                            <i class="fa fa-clock-o"></i>  &nbsp; <span id="timer"><?= isset($dataPt['TimeLimit']) ? $dataPt['TimeLimit'] : '01:00'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 pb-0 text-center">
            	<button type="button" class="button btn btn-lg btn-primary btn-outline-primary" id="button_start_pretest" onclick="startPreTest()">START PRE TEST</button>
            </div>
            <div id="divSoal" class="m-2 my-0">
            	<?php if(!$soal){?>
            		<div class="alert alert-info">Data Pretest tidak ditemukan.</div>
            	<?php }else{?>
            		<form action="<?= base_url('learning/class_room/pretest?cr_id='.$data['cr_id']) ?>" id="form" method="post" class="form-horizontal">
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
                                    <div>KONFIRMASI<br/>PRE TEST</div>
                                    <img src="<?=PATH_ASSETS;?>icon/class_room_icon_confirm.png" style="width: 80px;"/>
                                </div>
                                <div align="center my-3">
                                    <p>Pastikan anda telah menjawab dengan benar.</p>
                                    <p style="padding-top:30px;">Klik tombol <strong>SUBMIT</strong> untuk mengetahui hasil PRE TEST anda.</p>
                                </div>
                                
                                <div class="row my-3">
                                    <div class="col-6 col-sm-6 pull-left" align="left"> 
                                        <button  type="button" class="btn btn-warning" id="backToTest">
                                            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>&nbsp;&nbsp;
                                            <span>KEMBALI</span>
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6 pull-right" align="right">
										<div id="err_juml_jawaban_ui" class="text-danger"></div>
                                        <button type="submit" name="sendAnswer" id="sendAnswer" class="btn btn-success" value="1">
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