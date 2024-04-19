<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    table.prelearningtable{
        font-size: 14px;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <?php if(count($datas)>0){?>
                <?php foreach ($datas as $i => $data) { ?>
                    <?php
                        $step = json_decode($data['crm_step'],true);

                        if(isset($data['cr_lp']) && @$data['cr_lp']!=""){
                            $url = base_url('learning/class_room/info?cr_id='.$data['cr_id']);
                        }else{
                            $url = base_url('learning/class_room/home?cr_id='.$data['cr_id']);
                        }

                        $diff = abs(strtotime($data['cr_date_end']) - strtotime($data['cr_date_start']));
                        
                        $years = floor($diff / (365*60*60*24));
                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                        $days = floor($diff / (60*60*24));
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 style="font-size: 14px;"><?= $data['cr_name'] ?></h4>
                            <small><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?> <br /><?=$days;?> Hari Pelatihan</small>
                            <table class="table table-bordered mt-2 prelearningtable">
                                <tbody>
                                    <tr>
                                        <td>Modul Pelatihan</td>
                                        <td class="text-center">
                                            <?php if(isset($step['CT']['ctStatus']) && $step['CT']['ctStatus']=="1"){?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" width="16" />
                                            <?php }elseif(isset($step['CT']['ctStatus']) && $step['CT']['ctStatus']=="2"){?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" width="16" />
                                            <?php }else{?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" width="16" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php if ($data['cr_has_kompetensi_test']): ?>
                                    <tr>
                                        <td>Competency Test</td>
                                        <td align="center">
                                            <?php if(isset($step['CT']['ctStatus']) && $step['CT']['ctStatus']=="2"){?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_open.png" width="16" />
                                            <?php }else{?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" width="16" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($data['cr_show_nilai']): ?>
                                    <tr>
                                        <td>GRADE</td>
                                        <td align="center">
                                            <?php if(isset($step['RESULT']) && $step['RESULT']!="" && ($data['cr_has_knowledge_management']?$data['content_id'] != '':true)){?>
                                                <strong><?=$step['RESULT'];?></strong>
                                            <?php }else{?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" width="16" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <a href="<?=$url;?>" class="btn btn-primary mt-2">Detail Pelatihan</a>
                        </div>
                    </div>
                    <br/>
                <?php } ?>
            <?php }else{ ?>
                <div class="text-center">Tidak/belum ada data laporan pembelajaran.</div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->
