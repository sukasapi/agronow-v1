<?php $this->load->view('learning/app_header'); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if($dataStep['MP'][$module]['EvaStatus'] == '2' && strtotime($dataMp['Module'][$module]['ModuleStart']) <= strtotime(date('Ymd'))): ?>
            <div class="m-0 p-2 pt-4 alert alert-success text-center" style="border-radius: 0px;">
                <img src="<?=PATH_ASSETS;?>/icon/class_room_icon_lulus.png" style="width: 80px;"/><br/>
                <h2 class="mt-2" style="color: #FFFFFF;">ANDA SUDAH MENGIRIM POIN PEMBELAJARAN</h2>
            </div>
            <div class="m-2">
                <a href="<?= base_url('learning/class_room/module?cr_id='.$data['cr_id'].'&module='.$module) ?>" class="btn btn-block btn-lg btn-primary mt-3">MODUL PEMBELAJARAN</a>
                <a href="<?= base_url('learning/class_room/home?cr_id='.$data['cr_id']) ?>" class="btn btn-block btn-lg btn-primary mt-3 mb-4">KEMBALI KE HOME</a>
            </div>
        <?php else: ?>
            <h3 class="text-center m-2"><span>POIN PEMBELAJARAN</span><br/><span style="font-weight: normal;">MODUL <?=$module+1;?> : <?=strtoupper($dataMp['Module'][$module]['ModuleName']);?></span></h3>
            <div class="alert m-2" style="background-color: #e5e5e5;">
                <span>Silahkan tulis poin-poin pembelajaran Saudara</span>
            </div>
            <form name="addFeedback" class="form-horizontal" method="post" action="<?= base_url('learning/class_room/learning_point?cr_id='.$data['cr_id'].'&module='.$module) ?>">
                <div class="m-2">
                    <table class="que" style="width:100%;">
                        <tr>
                            <td><textarea class="form-control" name="learning_point" rows="4" required></textarea></td>
                        </tr>
                    </table>
                </div>
                <div class="text-center m-2">
                    <button type="submit" name="submitLearningPoint" value="1" class="btn btn-success">
                        <span>Kirim Poin Pembelajaran Anda</span>&nbsp;&nbsp;
                        <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                    </button>
                    <a href="<?=base_url('learning/class_room/module?cr_id='.$data['cr_id']);?>" class="btn btn-block btn-lg btn-primary mt-3 mb-3">MODUL PEMBELAJARAN</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<!-- * App Capsule -->