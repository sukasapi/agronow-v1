<?php $this->load->view('learning/app_header'); ?>
<style type="text/css">
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
            <p class="text-success">Anda telah terdaftar untuk mengikuti kompetensi sebagai berikut:</p>
            <table class="table table-sm">
                <tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td><strong><?=$category['cat_name'];?></strong></td>
                </tr>
                <tr>
                    <td>Tipe</td>
                    <td>:</td>
                    <td><strong><?=$data['cr_is_daily']=='1'?'Harian':'Reguler';?></strong></td>
                </tr>
                <tr>
                    <td>Kompetensi</td>
                    <td>:</td>
                    <td><strong><?=$data['cr_name'];?></strong></td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>
                        <?php
                            $date_start = $this->function_api->date_indo($data['cr_date_start'], 'dd FF YYYY');
                            $date_end = $this->function_api->date_indo($data['cr_date_end'], 'dd FF YYYY');
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
            <?php if($data['cr_desc']!=""){?>
                <div class="mt-2">
                    <div class="alert alert-primary content-view"><?=$data['cr_desc'];?></div>
                </div>
            <?php } ?>
            <div class="mt-2">
                <h4>Level Kompetensi:</h4>
            </div>
            <?php 
                $max_step = $data['cr_komp_max_lv'];
                $juml_soal = 3;
                $step = $dataStep['step'];

                $daily_available = true;
                if ($data['cr_is_daily'] === '1'){
                    $today = date('Y-m-d');
                    $daily_available = $dataStep['latest_work'] >= $today ? false:true;
                }
            ?>
            <?php if (!$daily_available): ?>
            <div class="alert alert-danger mt-2">Anda sudah mengerjakan kompetensi untuk hari ini</div>
            <?php endif; ?>
            <?php if($max_step > 0){ ?>
                <div class="mt-2">
                    <?php for ($i = 1; $i <= $max_step; $i++) { ?>
                        <?php
                            $css_button = ($i <= $step)? 'success' : 'secondary';
                            $juml_banksoal = $this->kompetensi_model->count_bank_soal($data['cat_id'],$i);
                            $note = '';
                            
                            if($juml_banksoal<$juml_soal) {
                                $css_button = 'danger';
                                $note = 'Jumlah bank soal hanya ada '.$juml_banksoal.' (minimal '.$juml_soal.' soal)';
                            } else {
                                $note = '<i class="fa fa-minus"></i>';
                            }

                            if($i<$step) {
                                $url = base_url('learning/kompetensi/evaluasi?cr_id='.$data['cr_id']);
                                $note = '<i class="fa fa-check"></i>';
                            } else if($i==$step) {
                                $url = base_url('learning/kompetensi/evaluasi?cr_id='.$data['cr_id']);
                            } else {
                                $url = 'javascript:void(0);';
                            }

                            if($css_button=="danger") {
                                $url = 'javascript:void(0);';
                            }

                            if (!$daily_available){
                                $url = 'javascript:void(0);';
                                $css_button = 'secondary';
                            }

                            // ujian sudah selesai?
                            if($dataStep['is_done_all']=="1") {
                                $url = 'javascript:void(0);';
                                
                                if(($dataStep['hasil']+1)==$i) {
                                    $css_button = 'danger';
                                    $note = '<i class="fa fa-times"></i>';
                                }
                            }
                        ?>
                        <a href="<?= $url; ?>" class="btn btn-<?= $css_button; ?> btn-block justify-content-between p-0">
                            <span class="btn-label btn-label-left float-left px-3 py-2 m-0"><?= $i; ?></span>
                            <span class="p-0 m-0">Level <?= $i; ?></span>
                            <span class="btn-label btn-label-right float-right px-3 py-2 m-0"><?= $note; ?></span>
                        </a>
                        <?php if ($prasyarat && ($dataStep['hasil']+1)==$i): ?>
                            <div class="section full pb-2">
                                <div class="section-title">Recommended Class</div>
                                <div class="carousel-<?=count($prasyarat)==1?'one':'multiple'?> owl-carousel owl-theme">
                                    <?php foreach ($prasyarat as $cr) { ?>
                                        <div class="item">
                                            <div class="card product-card">
                                                <div class="card-body">
                                                    <h2 class="title text-single mt-1 p-0" style="height: 40px;"><?= $cr['cr_name'] ?></h2>
                                                    <p class="text"><?=$this->function_api->date_indo($cr['cr_date_start']);?> - <?=$this->function_api->date_indo($cr['cr_date_end']);?></p>
                                                    <div class="price px-0">
                                                        <ion-item>
                                                            <ion-icon item-start name="wallet" class="icon"></ion-icon>
                                                            <?= $this->function_api->number($cr['cr_price']); ?>
                                                        </ion-item>
                                                    </div>
                                                    <a href="#" class="btn btn-sm btn-warning btn-block"> BELI</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            <?php }else{ ?>
                <div class="alert alert-danger mt-2">Data tidak ditemukan.</div>
            <?php } ?>
            <?php if(in_array($memberId, $specialId)){?>
                <div class="mt-5 text-center">
                    <a href="<?= base_url('learning/kompetensi/reset?cr_id='.$data['cr_id']) ?>" type="button" class="btn btn-danger p-1">RESET CLASSROOM</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>