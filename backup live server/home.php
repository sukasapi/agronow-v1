<?php $this->load->view('learning/app_header'); ?>
<style type="text/css">
	#desc * { color: #FFFFFF !important; } 
    div.alert p{
        margin-bottom: 0px;
    }
    .btn-label{
        background: rgba(0, 0, 0, 0.05);
        display: inline-block;
    }
    #appCapsule a.btn{
        height: auto;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-2" style="background-color: #e5e5e5">
            <div class="row">
                <div class="col-3 col-md-2 text-center">
                    <img src="<?= $data['member_image']; ?>" alt="Image" class="imaged rounded" style="object-fit: fill; width: 80px;">
                </div>
                <div class="col-9 col-md-10">
                    <h4>Hi, <?=$data['member_name'];?></h4>
                    <p><span class="text-primary"><?=$data['group_name'];?>,</span> <span class="text-primary">NIP: <?=$data['member_nip'];?></span></p>
                </div>
            </div>
        </div>
        <div class="p-2 mb-1">
            <p class="text-success">Anda telah terdaftar untuk mengikuti pelatihan sebagai berikut:</p>
            <table class="table table-sm">
                <tr>
                    <td>Pelatihan</td>
                    <td>:</td>
                    <td><strong><?=$data['cr_name'];?></strong></td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>
                        <?php
                            $date_start = $this->function_api->date_indo($data['cr_date_start'], 'datetime');
                            $date_end = $this->function_api->date_indo($data['cr_date_end'], 'datetime');
                        ?>
                        <strong>
                            <?php if($date_start == $date_end){ ?>
                                <?=$date_start.' WIB';?>
                            <?php }else{ ?>
                                <?=$date_start.' WIB - '.$date_end.' WIB';?>
                            <?php } ?>
                        </strong>
                    </td>
                </tr>
            </table>
            <?php if ($data['qr_image']): ?>
            <div class="text-center mt-2">
                <img src="<?= $data['qr_image']; ?>" class="imaged w-75">
            </div>
            <?php endif; ?>
            <div class="mt-2">
                <div class="alert alert-primary" id="desc"><?=$data['cr_desc'];?></div>
            </div>
            <div class="mt-2">
                <h4>Tahapan Pelatihan:</h4>
            </div>
            <?php if ($data['cr_has_project_assignment']): ?>
            <div class="mt-2">
                <?php
                    $btn = 'btn-primary';
                    $icon = 'fa fa-hourglass-2';
                    $url = base_url('learning/class_room/project_assignment_peserta?cr_id='.$data['cr_id']);
                    $disabled = 'disabled';
                ?>
                <a href="<?= $url; ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">PROJECT ASSIGNMENT</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if ($data['cr_has_prelearning']): ?>
            <div class="mt-2">
                <?php
                    if ($validity['validPreLearning']){
                        $btn = 'btn-success';
                        $icon = 'fa fa-check';
                    } else {
                        $btn = 'btn-primary';
                        $icon = 'fa fa-hourglass-2';
                    }
                ?>
                <a href="<?= base_url('learning/class_room/prelearning?cr_id='.$data['cr_id']); ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">PRE LEARNING</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if ($data['cr_has_pretest']): ?>
            <div class="mt-2">
                <?php
                    $url        = '#';
                    $btn        = 'btn-secondary';
                    $icon       = 'fa fa-minus';
                    $disabled   = 'disabled';

                    if ($validity['validPreLearning']){
                        $disabled = '';
                        if ($validity['validPretest']){
                            $url = base_url('learning/class_room/pretest?cr_id='.$data['cr_id']);
                            $btn = 'btn-success';
                            $icon = 'fa fa-check';
                        } else {
                            $url = base_url('learning/class_room/pretest?cr_id='.$data['cr_id']);
                            $btn = 'btn-primary';
                            $icon = 'fa fa-hourglass-2';
                        }
                    }
                ?>
                <a href="<?= $url; ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">PRE TEST</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php endif; ?>
            <div class="mt-2">
                <?php
                    $url        = '#';
                    $btn        = 'btn-secondary';
                    $icon       = 'fa fa-minus';
                    $disabled   = 'disabled';

                    if ($validity['validPretest']){
                        $url        = base_url('learning/class_room/module?cr_id='.$data['cr_id']);
                        $disabled   = '';
                        if ($validity['validModule']){
                            $btn        = 'btn-success';
                            $icon       = 'fa fa-check';
                        }else{
                            $btn        = 'btn-primary';
                            $icon       = 'fa fa-hourglass-2';
                        }
                    }
                ?>
                <a href="<?= $url; ?>" type="button" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">TRAINING MODULES</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php if ($data['cr_has_kompetensi_test']): ?>
            <div class="mt-2">
                <?php 
                    $btn = 'btn-secondary';
                    $icon = 'fa fa-minus';
                    $url = '#';
                    $disabled = 'disabled';
                    $text = '';

                    if ($validity['validModule']){
                        $disabled = '';
                        if ($validity['validCompTest']){
                            $score = '';
                            $disabled = '';
                            if (isset($arrScore[0])) {
                                $score = $arrScore[0];
                            }
                            if ($score=="D"){
                                $btn    = 'btn-danger';
                                $icon   = 'fa fa-check';
                                $url    = base_url('learning/class_room/competency?cr_id=' . $data['cr_id'] . '&status=fail');
                            } else {
                                $btn        = 'btn-success';
                                $icon       = 'fa fa-check';
                                if($data['crm_fb'] == ""){
                                    $url    = base_url('learning/class_room/feedback?cr_id='.$data['cr_id']);
                                }else{
                                    $url    = base_url('learning/class_room/competency?cr_id='.$data['cr_id'].'&status=passed');
                                }
                            }
                        } else {
                            if (strtotime($dataCt['ctStart']) <= strtotime(date('Ymd')) && strtotime($dataCt['ctEnd']) >= strtotime(date('Ymd'))) {
                                $btn = 'btn-primary';
                                $icon = 'fa fa-hourglass-2';
                                $url = base_url('learning/class_room/competency?cr_id=' . $data['cr_id']);
                            } else {
                                $text = ($dataCt['ctStart'] && $dataCt['ctEnd'] ? "<br/><small>" . $this->function_api->date_indo($dataCt['ctStart']) . " s/d " . $this->function_api->date_indo($dataCt['ctEnd']) . "</small>" : '');
                            }
                        }
                    }
                ?>
                <a href="<?= $url; ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">COMPETENCY TEST <?= $text; ?></span>
                    <span class="btn-label btn-label-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if ($data['cr_has_knowledge_management']): ?>
            <div class="mt-2">
                <?php
                    $btn = 'btn-secondary';
                    $icon = 'fa fa-minus';
                    $url = '#';
                    $disabled = 'disabled';

                    if($validity['validCompTest']){
                        $disabled   = '';
                        $url        = base_url('learning/class_room/knowledge_management?cr_id='.$data['cr_id']);
                        if($validity['validKnowledgeManagement']){
                            $btn    = 'btn-success';
                            $icon   = 'fa fa-check';
                        }else{
                            $btn    = 'btn-primary';
                            $icon   = 'fa fa-hourglass-2';
                        }
                    }
                ?>
                <a href="<?= $url; ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">KNOWLEDGE MANAGEMENT</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <?php endif; ?>
            <div class="mt-2">
                <?php
                    $btn        = 'btn-secondary';
                    $icon       = 'fa fa-minus';
                    $url        = '#';
                    $disabled   = 'disabled';

                    if($validity['validKnowledgeManagement']){
                        $disabled   = '';
                        if (!$data['crm_fb']){
                            $btn    = 'btn-warning';
                            $url    = base_url('learning/class_room/feedback?cr_id='.$data['cr_id']);
                        } else {
                            $btn        = 'btn-success';
                            $icon       = 'fa fa-check';
                            if(in_array($dataStep['RESULT'],array("A","B","C"))){
                                $url = base_url('learning/class_room/report?cr_id='.$data['cr_id'].'&status=passed');
                            } else {
                                $url = base_url('learning/class_room/report?cr_id='.$data['cr_id'].'&status=fail');
                                if($dataStep['RESULT']=="D"){
                                    $btn = 'btn-danger';
                                    $icon = 'fa fa-check';
                                }
                            }
                        }
                    }
                ?>
                <a href="<?= $url; ?>" class="btn <?= $btn; ?> btn-block justify-content-between p-0 <?= $disabled; ?>">
                    <span class="btn-label btn-label-left float-left px-3 py-2 m-0">•</span>
                    <span class="p-0 m-0">FEEDBACK CLASS & REPORT</span>
                    <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><i class="<?= $icon ?>"></i></span>
                </a>
            </div>
            <div class="mt-3">
                <a href="<?= base_url('learning/class_room/my_classroom') ?>" type="button" class="btn btn-block btn-lg btn-primary p-2">Daftar Pelatihan Saya</a>
            </div>
            <?php if(in_array($memberId, $specialId)){?>
                <div class="mt-5 text-center">
                    <a href="<?= base_url('learning/class_room/reset?cr_id='.$data['cr_id']) ?>" type="button" class="btn btn-danger p-1">RESET CLASSROOM</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->
