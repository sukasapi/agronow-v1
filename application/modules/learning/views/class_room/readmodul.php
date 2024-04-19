<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe{
        width: 100%;
        height: 83vh;
        border: none;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full prelearning">
        <?php
            // - use basic iframe -
            // $media_file = $doc.'#toolbar=0';
            
            // - use pdfjs -
            // because pdfjs can't do cross domain,
            // download the file to local
            
            $content = '';
            $targetFilePath = $fileurl;
           
            if (!is_file($targetFilePath)) {
                $content = @file_get_contents($fileurl);
                if($content != '') file_put_contents($targetFilePath, $content);
            }else{
                $content = 'content found';
            }
        ?>
        <?php if($content != ''){ ?>
            <?php 
          $media_file = base_url().$file; 
          $media_file = base_url().PDFJS_VIEWER.'?file='.$media_file;
            ?>
           <!-- <iframe src='<?php $media_file?>' frameborder="0" height="100%" width="100%"></iframe>-->
            <iframe src="<?= $media_file ?>"></iframe>
         
        <?php }else{ ?>
            <div class="alert alert-danger m-2 text-center">File tidak ditemukan</div>
        <?php } ?>
    </div>
</div>
<!-- * App Capsule -->