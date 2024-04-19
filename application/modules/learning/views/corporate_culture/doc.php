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
            $targetFilePath = getcwd().'/'.MEDIA_DOCUMENT_PATH.basename($doc);
            if (!is_file($targetFilePath)) {
                $content = @file_get_contents($doc);
                if($content != '') file_put_contents($targetFilePath, $content);
            }else{
                $content = 'content found';
            }
        ?>
        <?php if($content != ''){ ?>
            <?php $media_file = base_url().PDFJS_VIEWER.'?file='.base_url().MEDIA_DOCUMENT_PATH.basename($doc); ?>
            <iframe src="<?= $media_file ?>"></iframe>
        <?php }else{ ?>
            <div class="alert alert-danger m-2 text-center">File tidak ditemukan</div>
        <?php } ?>
    </div>
</div>
<!-- * App Capsule -->