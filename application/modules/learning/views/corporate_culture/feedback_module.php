<?php $this->load->view('learning/app_header'); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if($dataStep['MP'][$module]['FbStatus']=='2'  &&  strtotime($dataMp['Module'][$module]['ModuleStart'])<=strtotime(date('Ymd'))){ ?>
            <div class="m-0 p-2 pt-4 alert alert-success text-center" style="border-radius: 0px;">
                <img src="<?=PATH_ASSETS;?>/icon/class_room_icon_lulus.png" style="width: 80px;"/><br/>
                <h2 class="mt-2" style="color: #FFFFFF;">TERIMA KASIH ATAS PENILAIAN ANDA</h2>
            </div>
            <div class="m-2">
                <div class="alert alert-light">
                    Penilaian yang anda berikan sangat berarti bagi kami dalam penyempurnaan pembelajaran di masa yang akan datang.
                </div>
                <a href="<?= base_url('learning/corporate_culture/module?cr_id='.$data['cr_id'].'&module='.$module) ?>" class="btn btn-block btn-lg btn-primary mt-3">MODUL PEMBELAJARAN</a>
                <a href="<?= base_url('learning/corporate_culture/home?cr_id='.$data['cr_id']) ?>" class="btn btn-block btn-lg btn-primary mt-3 mb-4">KEMBALI KE HOME</a>
            </div>
        <?php }else{ ?>
            <h3 class="text-center m-2"><span>EVALUASI MODUL PELATIHAN</span><br/><span style="font-weight: normal;">MODUL <?=$module+1;?> : <?=strtoupper($dataMp['Module'][$module]['ModuleName']);?></span></h3>
            <div class="alert m-2" style="background-color: #e5e5e5;">
                <p><?=$dataMp['Module'][$module]['Feedback']['Desc'];?></p>
            </div>
            <form name="addFeedback" class="form-horizontal" method="post" action="<?= base_url('learning/corporate_culture/feedback_module?cr_id='.$data['cr_id'].'&module='.$module) ?>">
                <div class="m-2">
                    <?php for($i=0;$i<count($fbQuestion);$i++){?>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <?=$fbQuestion[$i];?>
                            </li>
                            <?php if($fbType[$i]=="pilihan"){?>
                                <li class="list-group-item" style="background-color:#f5f5f5;">
                                    <table class="que" style="width:100%;">
                                        <tr align="center">
                                            <?php for($j=1;$j<=10;$j++){?>
                                                <td width="10%"><input type="radio" name="fb[<?=$i;?>]" value="<?=$j;?>" required> <h4><?=$j;?></h4></td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </li>
                            <?php } ?>
                            <?php if($fbType[$i]=="text"){?>
                                <li class="list-group-item" style="background-color:#f5f5f5;">
                                    <table class="que" style="width:100%;">
                                        <tr>
                                            <td><textarea class="form-control" name="fb[<?=$i;?>]" rows="4" required></textarea></td>
                                        </tr>
                                    </table>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="text-center m-2">
                    <button type="submit" name="submitFeedbackModule" value="1" class="btn btn-success">
                        <span>Kirim Penilaian Anda</span>&nbsp;&nbsp;
                        <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                    </button>
                    <a href="<?=base_url('learning/corporate_culture/module?cr_id='.$data['cr_id']);?>" class="btn btn-block btn-lg btn-primary mt-3">MODUL PEMBELAJARAN</a>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<!-- * App Capsule -->