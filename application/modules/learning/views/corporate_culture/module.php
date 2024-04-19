<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div.alert p{
        margin-bottom: 0px;
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
            <?php if(isset($dataMp['Desc']) && $dataMp['Desc']!=""){?>
                <div class="alert alert-info mb-2">
                    <p><?=$dataMp['Desc'];?></p>
                </div>
            <?php } ?>
            <a href="<?=base_url('learning/corporate_culture/rencana_pembelajaran?cr_id='.$data['cr_id']);?>" class="btn btn-primary btn-block mb-2"><i class="fa fa-file"></i> &nbsp; Lihat Rencana Pembelajaran</a>
            <?php if(count($dataMp['Module']) == 0){ ?>
                <div class="alert alert-info">
                    <p>Saat ini belum ada modul pelatihan.</p>
                </div>
            <?php }else{ ?>
                <?php for($i=0;$i<count($dataMp['Module']);$i++){ ?>
                    <?php if($dataMp['Module'][$i]['Materi'][0]['ContentName']!=""){ ?>
                        <?php 
                            $card_class = 'bg-secondary';
                            if(strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd'))){
                                $card_class = 'bg-success';
                            }
                        ?>
                        <div class="card mb-2 <?= $card_class; ?>">
                            <div class="card-header p-1">
                                Modul <?=($i+1);?> :<br/>
                                <h4 style="color: white;"><?=$dataMp['Module'][$i]['ModuleName'];?></h4>
                                <?php if($dataMp['Module'][$i]['ModuleStart']!="" && $dataMp['Module'][$i]['ModuleEnd']!=""){ ?>
                                    <hr class="m-0" style="border-color: white;">
                                    <small style="color:#e5e5e5;"><i class="fa fa-clock-o"></i> <?= $this->function_api->date_indo($dataMp['Module'][$i]['ModuleStart']);?> s/d <?=$this->function_api->date_indo($dataMp['Module'][$i]['ModuleEnd']);?></small>
                                <?php } ?>
                            </div>
                            <div class="card-body p-0" style="background-color: #f5f5f5;">
                                <table class="table">
                                    <tbody>
                                        <?php for($j=0;$j<count($dataMp['Module'][$i]['Materi']);$j++){ ?>
                                            <?php
                                                if(!isset($dataStep['MP'][$i]['Materi'][0])){$dataStep['MP'][$i]['Materi'][0]="0";}
                                                if($dataStep['MP'][$i]['Materi'][0]=="0" && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd'))){
                                                    $dataStep['MP'][$i]['Materi'][0]="1";
                                                }
                                            ?>
                                            <!-- Materi & Quiz -->
                                            <?php for($j=0;$j<count($dataMp['Module'][$i]['Materi']);$j++){ ?>
                                                <?php
                                                    if(!isset($dataStep['MP'][$i]['Materi'][0])){$dataStep['MP'][$i]['Materi'][0]="0";}
                                                    if($dataStep['MP'][$i]['Materi'][0]=="0" && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd'))){
                                                        $dataStep['MP'][$i]['Materi'][0]="1";
                                                    }
                                                ?>
                                                <tr>
                                                    <?php 
                                                        $url = '';
                                                        if(($dataStep['MP'][$i]['Materi'][$j] == "1" && strtotime($dataMp['Module'][$i]['ModuleStart']) <= strtotime(date('Ymd'))) || $dataStep['MP'][$i]['Materi'][$j]=="2"){
                                                            $url = base_url('learning/corporate_culture/materi1?cr_id='.$data['cr_id']).'&module='.$i.'&materi='.$j;
                                                        }
                                                    ?>
                                                    <td>
                                                        <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                        <?=$dataMp['Module'][$i]['Materi'][$j]['ContentName'];?>
                                                        <?php if($url != ''){ ?></a><?php } ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                            if($dataMp['Module'][$i]['Materi'][$j]['Type'] == "video"){
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
                                                <?php if($dataMp['Module'][$i]['Quiz'] && $dataMp['Module'][$i]['Quiz'][$j]['Status']=="active"){?>
                                                    <?php $url = base_url('learning/corporate_culture/kuis1?cr_id='.$data['cr_id'].'&module='.$i.'&materi='.$j) ?>
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
                                            <?php } ?>
                                            <!-- # Materi & Quiz -->
                                            <!-- Evaluasi -->
                                            <?php if($dataMp['Module'][$i]['Evaluasi']['Status'] != "non-active"){ ?>
                                                <tr>
                                                    <?php $url = ''; ?>
                                                    <?php 
                                                        if(($dataStep['MP'][$i]['EvaStatus']=='1') && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($dataMp['Module'][$i]['ModuleEnd'])>=strtotime(date('Ymd'))){
                                                                $url = base_url('learning/corporate_culture/evaluasi?cr_id='.$data['cr_id'].'&module='.$i);
                                                        }elseif($dataStep['MP'][$i]['EvaStatus']=='2' && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd'))){
                                                            $url = base_url('learning/corporate_culture/evaluasi?cr_id='.$data['cr_id'].'&module='.$i);
                                                        }
                                                    ?>
                                                    <td colspan="2">
                                                        <strong>
                                                            <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                            EVALUASI MODUL <?=($i+1);?>
                                                            <?php if($url != ''){ ?></a><?php } ?>
                                                        </strong>
                                                    </td>
                                                    <td width="40" valign="top" align="right">
                                                        <?php  
                                                        if($dataStep['MP'][$i]['EvaStatus']=='2'){
                                                            ?>
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                        <?php }else{ ?>                    
                                                            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <!-- # Evaluasi -->
                                            <!-- Feedback -->
                                            <tr>
                                                <td colspan="2">
                                                    <?php $url = ''; ?>
                                                    <?php
                                                        if($dataStep['MP'][$i]['FbStatus']=='1' && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($dataMp['Module'][$i]['ModuleEnd'])>=strtotime(date('Ymd'))){
                                                            $url = base_url('learning/corporate_culture/feedback_module?cr_id='.$data['cr_id'].'&module='.$i);
                                                        }elseif($dataStep['MP'][$i]['FbStatus']=='2' && strtotime($dataMp['Module'][$i]['ModuleStart'])<=strtotime(date('Ymd'))){
                                                            // sudah memberikan feedback
                                                            $url = base_url('learning/corporate_culture/feedback_module?cr_id='.$data['cr_id'].'&module='.$i);
                                                        }
                                                    ?>
                                                    <strong>
                                                        <?php if($url != ''){ ?><a href="<?= $url ?>"><?php } ?>
                                                        FEEDBACK  MODUL <?=($i+1);?>
                                                        <?php if($url != ''){ ?></a><?php } ?>
                                                    </strong>
                                                </td>
                                                <td width="40" valign="top">
                                                    <?php if($dataStep['MP'][$i]['FbStatus']=='2'){?>
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" style="width: 16px;" />
                                                    <?php }else{ ?>                    
                                                        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" style="width: 16px;" />
                                                    <?php }  ?>
                                                </td>
                                            </tr>
                                            <!-- # Feedback -->
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php
                    $arrScore = explode("-",$dataStep['CT']['ctScore']);
                    $score = "";
                    if(isset($arrScore[0])){
                        $score = $arrScore[0];
                    }
                    if($dataCt['ctStart'] == ""){ $dataCt['ctStart'] = $data['cr_date_end']; }
                    if($dataCt['ctEnd'] == ""){ $dataCt['ctEnd'] = $data['cr_date_end']; }
                ?>
                <?php
                    $url = '#';
                    $btn = 'btn-secondary';
                    $icon = 'fa fa-minus';

                    $text_start = $this->function_api->date_indo($dataCt['ctStart']);
                    $text_end = $this->function_api->date_indo($dataCt['ctEnd']);

                    if($dataStep['CT']['ctStatus']=="2"){
                        $icon = 'fa fa-check';

                        if($score == "D"){
                            $btn = 'btn-danger';
                        }else{
                            $btn = 'btn-success';
                        }
                    }else if($dataStep['CT']['ctStatus']=="1" ){
                        if(date('Y-m-d') >= date('Y-m-d',strtotime($dataCt['ctStart'])) && date('Y-m-d')<=date('Y-m-d',strtotime($dataCt['ctEnd']))){
                            $url = base_url('learning/corporate_culture/competency?cr_id='.$data['cr_id']);
                            $btn = 'btn-primary';
                            $icon = 'fa fa-hourglass-2';
                        }
                    }
                ?>
                <a href="<?= $url; ?>" type="button" class="btn btn-block <?= $btn; ?> mt-2 p-0 justify-content-between">
                    <span class="p-2 m-0">COMPETENCY TEST<br/>
                    <small><?= $text_start; ?> s/d <?= $text_end; ?></small></span>
                    <span class="btn-label btn-label-right px-3 py-2 m-0"><i class="<?= $icon; ?>"></i></span>
                </a>
                <a href="<?=base_url('learning/corporate_culture/home?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-primary mt-2 mb-4">KEMBALI KE HOME</a>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->