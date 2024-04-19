<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div#info img{
        width: 100%;
        height: auto;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mb-5">
        <h3 class="text-center p-2">RENCANA PEMBELAJARAN</h3>
        <div class="p-2 pt-0" id="info">
            <?=html_entity_decode($data['cr_rp']);?>
            <?php if($dataStep['RP']=="0"){ ?>
                <form class="mt-3" action="<?= base_url('learning/class_room/rencana_pembelajaran?cr_id='.$data['cr_id']) ?>" method="POST">
                    <button type="submit" name="doAgree" class="btn btn-block btn-lg btn-primary" value="1" style="font-size:16px;">Saya Menyetujui Rencana Pembelajaran Ini</button>
                </form>
            <?php }else{ ?>
                <p class="mt-3">
                    <a href="<?=base_url('learning/class_room/module?cr_id='.$data['cr_id']);?>" class="btn btn-block btn-lg btn-primary">MODUL PELATIHAN</a>
                </p>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->