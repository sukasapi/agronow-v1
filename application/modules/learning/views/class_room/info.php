<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe, div#info img{
        width: 100%;
        height: auto;
    }
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <div class="card p-2" id="info">
                <?=html_entity_decode($data['cr_lp']);?>
                <p>
                    <a href="<?=base_url('learning/class_room/home?cr_id='.$data['cr_id']);?>" class="btn btn-block btn-lg btn-primary">Masuk ke Pembelajaran</a>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->