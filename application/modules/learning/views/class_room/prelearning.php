<?php $this->load->view('learning/app_header'); ?>

<?php
    $dataPl = json_decode($data['cr_prelearning'],true);
?>

<style type="text/css">
    div.prelearning div.card strong{
        font-weight: bold;
    }
    div.alert p{
        margin-bottom: 0px;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full prelearning">
        <div class="p-3 pb-5">
            <div class="card">
                <div class="card-body"><?=$dataPl?$dataPl['Desc']:'';?></div>
            </div>
            <?php if(isset($dataPl['Alert']) && trim($dataPl['Alert'])!="") {?>
                <div class="alert alert-info info mt-2"><?=trim($dataPl['Alert']);?></div>
            <?php } ?>
            <div class="mt-2 text-success">MATERI  PRE LEARNING :</div>
            <?php if(!isset($dataPl['Materi']) || (isset($dataPl['Materi']) && count($dataPl['Materi'])==0)){?>
                <div class="alert alert-danger mt-2">Materi Pre Learning Belum Tersedia.</div>
            <?php }else{ ?>
                <div class="mt-2">
                    <?php
					for($i=0;$i<count($dataPl['Materi']);$i++){
						// hide yg non active
						if($dataPl['Materi'][$i]['Status']=="non-active") continue;
					?>
                        <h4><?=$dataPl['Materi'][$i]['ContentName'];?></h4>
                        <br/>
                        <?php if($dataPl['Materi'][$i]['Type']=="video"){ ?>
                            <?php if(strpos($dataPl['Materi'][$i]['Media'],"youtube") !== false){ ?>
                                <p><iframe width="100%" height="300" src="<?=$dataPl['Materi'][$i]['Media'];?>" frameborder="0" allow="accelerometer;  encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></p>
                            <?php }else{ ?>
                                <video width="100%" height="300" controls>
                                    <source src="<?=URL_MEDIA_VIDEO."/".$dataPl['Materi'][$i]['Media'];?>" type="video/mp4" />
                                </video>
                            <?php } ?>
                        <?php } elseif ($dataPl['Materi'][$i]['Type']=="audio"){
                            $media = $dataPl['Materi'][$i]['Media'];
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
                                $media = URL_MEDIA_DOCUMENT.'/'.$dataPl['Materi'][$i]['Media'];
                                $url = base_url('learning/class_room/materi_doc').'?doc='.base64_encode($media).'&type=MP&materi='.$i.'&cr_id='.$data['cr_id'];
                            ?>
                            <a href="<?=$url?>" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp; Baca Materi </a>
                            &nbsp; 
                            <a href="<?=$media;?>" class="btn btn-primary"><i class="fa fa-download"></i> &nbsp; Download</a>
                        <?php } ?>
                        <hr/>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['PreTest']['Attemp']) && $_SESSION['PreTest']['Attemp']>0){?>
                <div class="mt-2">
                    <a href="<?=base_url('learning/class_room/pretest?cr_id='.$data['cr_id']);?>" type="button" class="btn btn-block btn-lg btn-succes" <?php if($countPLOpen<count($dataPl['Materi'])){echo 'disabled="disabled"';}?>>PRE TEST</a>
                </div>
            <?php } ?>
            <div class="mt-4">
                <a href="<?= base_url('learning/class_room/home?cr_id='.$data['cr_id']) ?>" type="button" class="btn btn-block btn-lg btn-primary p-2 mb-2">Kembali ke Home</a>
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->