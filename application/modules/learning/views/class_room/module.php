<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    img{
        width: 100%; 
        height: auto;
    }
    .btn-label{
        background: rgba(0, 0, 0, 0.05);
        display: inline-block;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <h3 class="text-center m-2">MODUL PELATIHAN</h3>
        <div class="m-2">
            <?php
			$competency_allowed = (bool)$data['cr_has_kompetensi_test'];
			if(isset($dataMp['Desc']) && $dataMp['Desc']!=""){?>
                <div class="alert alert-info mb-2">
                    <p><?=html_entity_decode($dataMp['Desc']);?></p>
                </div>
            <?php } ?>
            <a href="<?=base_url('learning/class_room/rencana_pembelajaran?cr_id='.$data['cr_id']);?>" class="btn btn-primary btn-block mb-2"><i class="fa fa-file"></i> &nbsp; Lihat Rencana Pembelajaran</a>
            <?php if(count($dataMp['Module']) == 0){ ?>
                <div class="alert alert-info">
                    <p>Saat ini belum ada modul pelatihan.</p>
                </div>
            <?php }else{ ?>
                <?php
                $i = 0;
                $totalModul = count($dataMp['Module']);
                foreach($dataMp['Module'] as $module){ ?>
                    <?php if($module['Materi']){ 

                                    $validmodule_cek="ok";
                                    //cek modul sebelumnya
                                    $ib=$i-1;
                                    if($i > 0){
                                        if(isset($dataMp['Module'][$ib]['Assignment']) && $dataMp['Module'][$ib]['Assignment']=="ya"){
                                           //echo "asignment sebelumnya sebagai syarat"."<br>";
                                            //cek jika sudah ada file terupload
                                           if($validity[$ib]['Assignment']=="done"){
                                             //   echo "assignment telah terupload<br>";
                                                $validmodule_cek="ok";
                                           }else{
                                              //  echo "assignment belum terupload<br>";
                                                $validmodule_cek="fail";
                                           }
                                          // echo $validity[$ib]['Assignment'];
                                        }else{
                                           // echo "asignment sebelumnya tidak sebagai syarat<br>";
                                            $validmodule_cek="ok";
                                        }
                                      
                                    }else{
                                        $validmodule_cek="ok";
                                    }
							if (!$validity[$i]['Feedback']) $competency_allowed = false;
                            if($validity[$i]['ModuleActive']){
                                 if($validmodule_cek =="ok"){
                                        $card_class = 'bg-success';
                                        $mod_enable = true;
                                    }else{
                                        $card_class = 'bg-secondary';
                                        $mod_enable = false;
                                    }
                            } else {
                                $card_class = 'bg-secondary';
                                $mod_enable = false;
                            }
                        ?>
                        <div class="card mb-2 <?= $card_class; ?>">
                            <div class="card-header p-2">
                                Modul <?=($i+1);?> :<br/>
                                <h4 style="color: white;"><?=$module['ModuleName'];?></h4>
                                <?php if($module['ModuleStart']!="" && $module['ModuleEnd']!=""){ ?>
                                    <hr class="m-0" style="border-color: white;">
                                    <small style="color:#e5e5e5;"><i class="fa fa-clock-o"></i> <?= $this->function_api->date_indo($module['ModuleStart']);?> s/d <?=$this->function_api->date_indo($module['ModuleEnd']);?></small>
                                <?php } ?>
                            </div>
                            <div class="card-body p-0" style="background-color: #f5f5f5;">
                                <table class="table">
                                    <tbody>
                                        <?php
                                        $j = 0;
                                        foreach($module['Materi'] as $materi){ ?>
                                            <?php
                                                if(!isset($dataStep['MP'][$i]['Materi'][0])){$dataStep['MP'][$i]['Materi'][0]="0";}
                                                if($dataStep['MP'][$i]['Materi'][0]=="0" && strtotime($module['ModuleStart'])<=strtotime(date('Ymd'))){
                                                    $dataStep['MP'][$i]['Materi'][0]="1";
                                                }
                                            ?>
                                            <tr>
                                                <?php 
                                                    $url = '';
                                                    if($mod_enable && (($dataStep['MP'][$i]['Materi'][$j] == "1" && strtotime($module['ModuleStart']) <= strtotime(date('Ymd'))) || $dataStep['MP'][$i]['Materi'][$j]=="2")){
                                                        $url = base_url('learning/class_room/materi1?cr_id='.$data['cr_id']).'&module='.$i.'&materi='.$j;
                                                    }
                                                ?>
                                                <td>
                                                    <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                    <?=$materi['ContentName'];?>
                                                    <?php if($url != ''){ ?></a><?php } ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                        if($materi['Type'] == "video"){
                                                            $icon = 'fa-video-camera';
                                                        }else{
                                                            $icon = 'fa-file-pdf-o';
                                                        }
                                                    ?>
                                                    <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                    <i class="fa <?= $icon ?>"></i>
                                                    <?php if($url != ''){ ?></a><?php } ?>
                                                </td>
                                                <td width="40">
                                                    <?php if($dataStep['MP'][$i]['Materi'][$j]=="2"){ ?>
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width:16px;" />
                                                    <?php }else{ ?>
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width:16px;" />
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php if($module['Quiz'] && $module['Quiz'][$j]['Status']=="active"){?>
                                                <?php $url = base_url('learning/class_room/kuis1?cr_id='.$data['cr_id'].'&module='.$i.'&materi='.$j) ?>
                                                <tr>
                                                    <?php  if($j==0){?>
                                                        <td colspan="2"><a href="<?= $url ?>">Quiz</a></td>
                                                    <?php }else{ ?>
                                                        <td colspan="2">Quiz Materi <?=($j+1);?></td>
                                                    <?php } ?>

                                                    <td width="80" align="right">
                                                        <!-- ??? where we set the session ??? -->
                                                        <?php if(isset($_SESSION['StepMateri']) && $_SESSION['StepMateri'] > $j){?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                        <?php } else{ ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php
                                        $j++;
                                        } ?>
                                        <?php if (isset($module['ModuleLinkZoom']) && $module['ModuleLinkZoom']): ?>
                                            <tr>
                                                <?php
                                                if($validity[$i]['Materi'] && strtotime($module['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($module['ModuleEnd'])>=strtotime(date('Ymd'))){
                                                    $zoomLink = $module['ModuleLinkZoom'];
                                                }else{
                                                    $zoomLink = '';
                                                }
                                                ?>
                                                <td colspan="3">
                                                    <strong>
                                                        <?= $zoomLink?"<a href='$zoomLink'>":""; ?>
                                                        LINK ZOOM
                                                        <?= $zoomLink?"</a>":""; ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php 
                                        
                                        if(isset($module['Assignment']) && $module['Assignment']=="ya"){
                                            $urlassignment=base_url("learning/class_room/assignment_module?cr_id=".$data['cr_id']."&module=".$i);
                                            if($validity[$i]['Assignment']=="done"){
                                               $pathnext=$data['cr_id']."_".$validity[$i]['MemberId']."_".$i;
                                            ?>
                                                <tr>
                                                    <td>
                                                        ASSIGNMENT MODUL
                                                    </td>
                                                    <td class="text-right"><a href="<?=base_url("learning/class_room/readpdf?")."act=module&pathnext=".$pathnext?>"> <i class="fa fa-file-pdf-o"></i></a></td>
                                                    <td>
                                                    <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                    </td>
                                                </tr>
                                            <?php
                                            }else{
                                                $urlassignment=base_url("learning/class_room/assignment_module?cr_id=".$data['cr_id']."&module=".$i);
                                                $pathnext=$data['cr_id']."_".$validity[$i]['MemberId']."_".$i;
                                                ?>
                                                <tr>
                                                <td>
                                                            <?php 
                                                                if( $validmodule_cek=="ok"){
                                                                    ?>
                                                                        <a href="<?=$urlassignment?>">Assignment Modul</a>
                                                                    <?php 
                                                                }else{
                                                                    ?>
                                                                        Assignment Modul
                                                                    <?php
                                                                }
                                                            ?>
                                                           
                                                        </td>
                                                    <td  class="text-right"></td>
                                                    <td>
                                                    <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                       
                                        }else{

                                        }
                                 
                                    ?>
                                        <?php if ($data['cr_has_learning_point']): ?>
                                            <?php if ($module['LearningPoint']['Status'] == "active"):
                                                $url = '';
                                                if ($validity[$i]['Materi'] && $module['ModuleStart'] <= strtotime(date('Ymd')) && strtotime($module['ModuleEnd'] )>= strtotime(date('Ymd'))){
                                                    $url = site_url('learning/class_room/learning_point?cr_id='.$data['cr_id'].'&module='.$i);
                                                }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                                LEARNING POINT <?=($i+1);?>
                                                                <?php if($url != ''){ ?></a><?php } ?>
                                                        </strong>
                                                    </td>
                                                    <td width="40" align="right" style="color:#5d9cec ; font-weight:bold;"></td>
                                                    <td width="40" valign="top" align="right">
                                                        <?php
                                                        if($dataStep['MP'][$i]['LearningPoint']['status']=='2' &&  strtotime($module['ModuleStart'])<=strtotime(date('Ymd'))){
                                                            ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                        <?php }else{
                                                            $mod_enable = false; ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($module['Evaluasi']['Status'] == "active"):
                                                $url = '';
                                                if ($validity[$i]['Materi'] && strtotime($module['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($module['ModuleEnd'])>=strtotime(date('Ymd'))){
                                                    $url = base_url('learning/class_room/evaluasi?cr_id='.$data['cr_id'].'&module='.$i);
                                                }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                                EVALUASI MODUL <?=($i+1);?>
                                                                <?php if($url != ''){ ?></a><?php } ?>
                                                        </strong>
                                                    </td>
                                                    <td width="40" align="right" style="color:#5d9cec ; font-weight:bold;">
                                                        <?php
                                                        $evaScore ="";
                                                        if(isset($dataStep['MP'][$i]['EvaScore']) && $dataStep['MP'][$i]['EvaScore']!=""){
                                                            $arrEvaScore = explode("-",$dataStep['MP'][$i]['EvaScore']);
                                                            $evaScore = $arrEvaScore[0];
															if($data['cr_show_nilai'] && $evaScore!="D"){ echo $evaScore; }
                                                            ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td width="40" valign="top" align="right">
                                                        <?php
                                                        if($dataStep['MP'][$i]['EvaStatus']=='2' &&  strtotime($module['ModuleStart'])<=strtotime(date('Ymd'))){
                                                        ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                        <?php }else{
                                                            $mod_enable = false; ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($module['Feedback']['Status'] == "active"){ //jika feedback active ?>
                                            <tr>
                                                <td colspan="2">
                                                    <?php if(!isset($dataStep['MP'][$i]['FbStatus'])){$dataStep['MP'][$i]['FbStatus']="0";} ?>
                                                    <?php
													$url_fb_real = base_url('learning/class_room/feedback_module?cr_id='.$data['cr_id'].'&module='.$i);
													$url = '';
													?>
                                                    <?php
														if($validity[$i]['EvaLearningPoint'] && strtotime($module['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($module['ModuleEnd'])>=strtotime(date('Ymd'))){
															// ada evaluasi modul? cek dl ybs lulus apa ga
															if($module['Evaluasi']['Status']=="active") {
																if(strtoupper($evaScore)=="A") $url = $url_fb_real;
															} else {
																$url = $url_fb_real;
															}
                                                        }
                                                    ?>
                                                    <strong>
														<?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                        FEEDBACK MODUL <?=($i+1);?>
                                                        <?php if($url != ''){ ?></a><?php } ?>
                                                    </strong>
                                                </td>
                                                <td width="40" valign="top">
                                                    <?php if(!isset($dataStep['MP'][$i]['FbStatus'])){$dataStep['MP'][$i]['FbStatus']="0";} ?>
                                                    <?php if($dataStep['MP'][$i]['FbStatus']=='2'){?>
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                    <?php }else{
                                                        $mod_enable = false; ?>
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                    <?php }  ?>
                                                </td>
                                            </tr> 
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                <?php
                    $i++;
                } ?>
            <?php } ?>
            <?php
            if ($data['cr_has_kompetensi_test']):
                $arrScore = explode("-",$dataStep['CT']['ctScore']);
                $score = "";
                if(isset($arrScore[0])){
                    $score = $arrScore[0];
                }

                $url = '#';
                $btn = 'btn-secondary';
                $icon = 'fa fa-minus';

                if($dataStep['CT']['ctStatus']=="2"){
                    $icon = 'fa fa-check';

                    if($score == "D"){
                        $btn = 'btn-danger';
                    }else{
                        $btn = 'btn-success';
                    }
                }else{
                    if($competency_allowed && date('Y-m-d') >= date('Y-m-d',strtotime($dataCt['ctStart'])) && date('Y-m-d')<=date('Y-m-d',strtotime($dataCt['ctEnd']))){
                        $url = base_url('learning/class_room/competency?cr_id='.$data['cr_id']);
                        $btn = 'btn-primary';
                        $icon = 'fa fa-hourglass-2';
                    }
                }
            ?>
            <a href="<?= $url; ?>" type="button" class="btn btn-block <?= $btn; ?> mt-2 p-0 justify-content-between">
                <span class="p-2 m-0 text-left">COMPETENCY TEST
                    <?php if ($dataCt['ctStart'] && $dataCt['ctEnd']): ?>
                        <br/><small><?= $this->function_api->date_indo($dataCt['ctStart']); ?> s/d <?= $this->function_api->date_indo($dataCt['ctEnd']); ?></small>
                    <?php endif; ?>
                </span>
                <span class="btn-label btn-label-right px-3 py-2 m-0"><i class="<?= $icon; ?>"></i></span>
            </a>
            <?php endif; ?>
            <a href="<?=base_url('learning/class_room/home?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-primary mt-2 mb-4">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
<!-- * App Capsule -->
