<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    #tahap div.blue{
        background-color: #2088a5;
        color: white;
    }
    #tahap div.side-blue{
        background-color: #0f7695;
        color: white;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="d-flex flex-row p-2" style="background-color: #e5e5e5">
            <div class="d-flex align-items-center mr-2">
                <img src="<?=PATH_ASSETS?>img/avatar.png" alt="avatar" class="imaged w48 rounded">
            </div>
            <div>
                <p class="m-0" style="font-size: large;"><b>Hi, <?=$data['member_name'];?>P<b></p>
                <p class="m-0 text-primary"><?=$data['group_name'];?>, NIP: <?=$data['member_nip'];?></p>
            </div>
        </div>
        <div class="p-2">
            <p class="m-0">Anda telah terdaftar untuk mengikuti pembelajaran sebagai berikut:</p>
            <p class="m-0">Pembelajaran: <?=$data['cr_name'];?></p>
            <?php
                $date_start = $this->function_api->date_indo($data['cr_date_start']);
                $date_end = $this->function_api->date_indo($data['cr_date_end']);
            ?>
            <p class="m-0">
                Waktu : 
                <?php if($date_start == $date_end){ ?>
                    <?=$date_start.' WIB';?>
                <?php }else{ ?>
                    <?=$date_start.' WIB - '.$date_end.' WIB';?>
                <?php } ?>
            </p>
        </div>
        <div class="card m-2 mt-0" style="background-color: #f4f5f7;">
            <div class="card-body p-2">
                <?=$data['cr_desc'];?>
            </div>
        </div>
        <div class="m-2" id="tahap">
            <p class="mb-1">Tahap Pembelajaran:</p>
            <a href="" class="d-flex mb-1" style="color: white">
                <div class="p-1 px-2 side-blue">1</div>
                <div class="p-1 flex-grow-1 blue">TRAINING MODULES</div>
                <div class="p-1 side-blue"><i class="fa fa-hourglass-2"></i></div>
            </a>
        </div>
    </div>
</div>
<!-- * App Capsule -->