<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe{
        width: 100%;
        height: 300px;
        border: 1px solid #4F5050;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="card bg-light m-2">
            <div class="card-body p-1">
                <table class="table table-sm">
                    <tr>
                        <td width="75">Modul <?=($module+1);?></td>
                        <td width="10">:</td>
                        <td><strong><?=$dataMp['Module'][$module]['ModuleName'];?></strong></td>
                    </tr>
                    <tr valign="top">
                        <td>Materi  <?=($materi+1);?></td>
                        <td>:</td>
                        <td><strong><?=$dataMp['Module'][$module]['Materi'][$materi]['ContentName'];?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="m-2">
            <p><strong>Berikut ini adalah materi pelatihan:</strong></p>
            <h4><?=$dataMp['Module'][$module]['Materi'][$materi]['ContentName'];?></h4>
            <?php if($dataMp['Module'][$module]['Materi'][$materi]['Type']=="video"){ ?>
                <?php if(strpos($dataMp['Module'][$module]['Materi'][$materi]['Media'],"youtube")>0){ ?>
                    <p><iframe width="100%" height="300" src="<?=$dataMp['Module'][$module]['Materi'][$materi]['Media'];?>" frameborder="0" allow="accelerometer;  encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
                <?php }else{ ?>
                    <video width="100%" height="300" controls>
                        <source src="<?=URL_MEDIA_VIDEO."/".$dataMp['Module'][$module]['Materi'][$materi]['Media'];?>" type="video/mp4" />
                    </video>
                <?php } ?>
            <?php } elseif ($dataMp['Module'][$module]['Materi'][$materi]['Type']=="audio"){
                $media = $dataMp['Module'][$module]['Materi'][$materi]['Media'];
                $targetFilePath = getcwd().'/'.MEDIA_AUDIO_PATH.basename($media);
                if (!is_file($targetFilePath)) {
                    file_put_contents($targetFilePath, file_get_contents(URL_MEDIA_AUDIO.$media));
                }

                $audio = base_url().MEDIA_AUDIO_PATH.'/'.$media
                ;?>
                <div class="wide-block text-center py-3 px-2">
                    <audio id="audio" controls controlsList="nodownload" style="width: 100%;" src="<?= $audio; ?>">
                        <!-- <source src="" type="audio/mpeg" /> -->
                        Your browser does not support the audio element.
                    </audio>
                </div>
            <?php }else{ ?>
                <?php 
                    $media_url = URL_MEDIA_DOCUMENT.$dataMp['Module'][$module]['Materi'][$materi]['Media'];
                ?>
                <a href="<?=base_url('learning/corporate_culture/materi_doc').'?cr_id='.$data['cr_id'].'&doc='.base64_encode($media_url).'&type=MP&materi='.$materi;?>" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp; Baca Materi </a>
                <?php if($dataMp['Module'][$module]['Materi'][$materi]['Media'] != "business-case-5eaf6c90b83e6.pdf"){?>
                    <a href="<?= $media_url ?>" class="btn btn-primary" download><i class="fa fa-download"></i> &nbsp; Download</a>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="m-2 mt-3 mb-3">
            <a href="<?=base_url('learning/corporate_culture/module?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-success">MODUL PELATIHAN</a>
        </div>
    </div>
</div>
<!-- * App Capsule -->