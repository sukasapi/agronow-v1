<?php $this->load->view('learning/app_header'); ?>
<?php
    $fbQuestion = $fb['Question'];
    $fbType = $fb['Type'];
?>
<style type="text/css">
    div.result{
        background-color: #0BCD0F;
    }
    div.result h2{
        color: #FFFFFF;
    }
    div.result h5{
        color: #FFFFFF;
        font-weight: normal;
    }
    table.que h4{
        font-weight: normal;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mb-5">
        <?php if($type == 'confirm'){ ?>
            <div class="m-0 p-2 pt-4 alert alert-success text-center" style="border-radius: 0px;">
                <img src="<?=PATH_ASSETS;?>/icon/class_room_icon_lulus.png" style="width: 80px;"/><br/>
                <h2 class="mt-2" style="color: #FFFFFF;">TERIMA KASIH ATAS PENILAIAN ANDA</h2>
            </div>
			<div class="text-left"><small>&nbsp;uid:&nbsp;<?=$data['crm_id'].'.'.base64_encode($data['crm_id'])?></small></div>
            <div class="m-2">
                <div class="alert alert-light">
                    Penilaian yang anda berikan sangat berarti bagi kami dalam penyempurnaan pembelajaran di masa yang akan datang.
                </div>
                <a href="<?= base_url('learning/class_room/home?cr_id='.$data['cr_id']); ?>" class="btn btn-block btn-lg btn-primary mt-3 mb-4">KEMBALI KE HOME</a>
            </div>
        <?php }else{ ?>
            <div class="text-center py-4 my-2 px-2 result">
                <h2>Anda Telah Menyelesaikan Competency Test</h2>
                <h5>Setelah mengisi evaluasi akhir ini, Anda akan mendapatkan sertifikat pelatihan.</h5>
            </div>
            <h3 class="text-center m-2">EVALUASI PELATIHAN<br/><?=strtoupper($data['cr_name']);?></h3>
            <div class="card m-2">
                <div class="card-body"><?=$fb['Desc'];?></div>
            </div>
            <form name="addFeedback" class="form-horizontal" method="post" action="<?= base_url('learning/class_room/feedback?cr_id='.$data['cr_id']); ?>">
                <?php for($i=0;$i<count($fbQuestion);$i++){?>
                    <ul class="list-group m-2">
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
                <div class="m-2">
                    <div class="text-center m-2 mb-4">
                        <button type="submit" name="submitFeedback" value="1" class="btn btn-success">
                            <span>Kirim Penilaian Anda</span>&nbsp;&nbsp;
                            <span class="btn-label"><i class="fa fa-arrow-right"></i></span>
                        </button>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<!-- * App Capsule -->