<?php $this->load->view('learning/app_header'); ?>

<?php
    $step = $dataStep['step'];
?>

<style type="text/css">
    table.que td{
        vertical-align: top;
    }
    table.que label{
        margin-bottom: 0px;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <h3 class="text-center m-2">KOMPETENSI LEVEL <?=($step);?></h3>
        <div class="m-2">
            <span>Kesempatan Evaluasi Test <strong>1 kali</strong>: </span>
        </div>
        <div class="card bg-success rounded-0 sticky-top">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-9 py-3 pl-3">
                        <i class="fa fa-question-circle"></i> &nbsp; no <?=$current_no_soal.' dari '.$juml_soal?> soal
                    </div>
                    <div class="col-3 py-3 text-center bg-dark">
                        <i class="fa fa-clock-o"></i>  &nbsp; <span id="timer"><?= $durasi; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php if($warning != ''){ ?>
            <div class="alert alert-info mt-2"><?=$warning?></div>
        <?php }else{ ?>
            <div class="p-3 pb-0 text-center">
                <button type="button" class="button btn btn-lg btn-primary btn-outline-primary" id="mulaiUjian" onclick="mulaiUjian()">Mulai Ujian</button>
            </div>
            <div id="divSoal" class="m-2 my-0" style="<?= $style; ?>">
                <form action="<?= base_url('learning/kompetensi/evaluasi?cr_id='.$data['cr_id']); ?>" id="dform" method="post" class="form-horizontal">
                    <ul class="list-group mb-2">
                        <li class="list-group-item" id="soal<?=$current_no_soal;?>" style="padding:5px 10px;">
                            <table class="que" style="width:100%;">
                                <tr>
                                    <td width="20"><?=$current_no_soal;?>.</td>
                                    <td class="content-view"><?=$soal[0]['crs_question'];?></td>
                                </tr>
                            </table>
                        </li>
                        <li class="list-group-item" style="background-color:#f5f5f5;">
                            <table class="que" style="width:100%;">
                                <?php for($j=0;$j<=3;$j++) { ?>
                                    <tr>
                                        <td width="30">
                                            <input type="radio" name="choice" id="choice<?=$j;?>" class="radioSoal" value="<?=$arrJ[$j];?>" required>
                                        </td>
                                        <td <?php if($arrJ[$j]==$jawaban_benar && $display_jaw_benar==true){ echo 'style="background:#B4DFF7;"';}?>>
                                            <label for="choice<?=$j;?>" style="display:block;font-weight:normal; padding-left:5px;"> <?=$arrJ[$j]?></label>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </li>
                    </ul>
                    <input type="hidden" id="forcedSubmit" name="forcedSubmit" value="0"/>
                    <div class="mt-2 pb-5 text-center">
                        <button type="button" id="kirim" class="btn btn-success" disabled><?=$button_teks?>
                           <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i></span>
                        </button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>