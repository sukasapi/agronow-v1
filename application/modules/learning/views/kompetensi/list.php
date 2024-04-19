<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    table.prelearningtable{
        font-size: 14px;
    }
    table.prelearningtable tr:hover{
        color: #3AC518;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <?php if(count($datas)>0){?>
                <?php foreach ($datas as $i => $data) { ?>
                    <?php
                        $data['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $data['crm_step']);
                        $dataStep = json_decode($data['crm_step'],true);

                        $url = base_url('learning/kompetensi/detail?cr_id='.$data['cr_id']);
                    ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 style="font-size: 14px;"><?= $data['cr_name'] ?></h4>
                            <small><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?> <br /></small>
                            <table class="table table-bordered mt-2 prelearningtable">
                                <tbody>
                                    <tr>
                                        <td>GRADE</td>
                                        <td class="text-center">
                                            <?php if(@$dataStep['is_done_all'] == '1'){ ?>
                                                <?php
                                                    $color = 'text-danger';
                                                    $icon = 'fa-times';
                                                    if($dataStep['hasil'] >= $data['cr_komp_max_lv']) {
                                                        $color = 'text-success';
                                                        $icon = 'fa-check';
                                                    }
                                                ?>
                                                <strong class="<?=$color?>"><i class="fa <?=$icon?>"></i>&nbsp;Level&nbsp;<?=$dataStep['hasil'].'&nbsp;dari&nbsp;'.$data['cr_komp_max_lv'].'';?></strong>
                                            <?php }else{ ?>
                                                <img src="<?= PATH_ASSETS ?>icon/class_room_icon_close.png" width="16" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php if(@$dataStep['is_done_all'] && @$dataStep['hasil'] < $data['cr_komp_max_lv']) { ?>
                                        <tr>
                                            <td colspan="2">Untuk memenuhi level kompetensi anda diwajibkan mengikuti pelatihan: <?=$data['cr_materi']?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <a href="<?=$url;?>" class="btn btn-primary mt-2">Detail Kompetensi</a>
                        </div>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="text-center">data tidak ditemukan</div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->