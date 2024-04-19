<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div.result{
        background-color: #0BCD0F;
    }
    div.result img{
        width: 80px;
    }
    div.result h2{
        color: #FFFFFF;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="text-center p-2 mb-2 result">
            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
            <h2>TERIMA KASIH ATAS PENILAIAN ANDA</h2>
        </div>
        <div class="m-2">
            <div class="alert alert-info mb-2">Penilaian yang anda berikan sangat berarti bagi kami dalam penyempurnaan aplikasi di masa yang akan datang.</div>
            <a href="<?=base_url('home');?>" type="button" class="btn btn-block btn-lg btn-secondary mb-2">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
<!-- # App Capsule -->