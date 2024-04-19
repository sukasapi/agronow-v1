<?php $this->load->view('learning/app_header'); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php
            $media = $data['media'];
            $media_file = '';

            // - use basic iframe -
            // if($media['media_value'] != ''){
            //     $media_file = URL_MEDIA_DOCUMENT.$media['media_value'];
            //     // remove print and print button on iframe
            //     $media_file .= '#toolbar=0';
            // }
            
            // - use google viewer -
            // if($media['media_value'] != ''){
            //     $media_file = 'https://docs.google.com/viewer?url='.URL_MEDIA_DOCUMENT.$media['media_value'].'&embedded=true';
            // }
            
            // - use pdfjs -
            if($media['media_value'] != ''){
                // because pdfjs can't do cross domain,
                // download the file to local
                $targetFilePath = getcwd().'/'.MEDIA_DOCUMENT_PATH.basename($media['media_value']);
                if (!is_file($targetFilePath)) {
                    file_put_contents($targetFilePath, file_get_contents(URL_MEDIA_DOCUMENT.$media['media_value']));
                }

                $media_file = base_url().PDFJS_VIEWER.'?file='.base_url().MEDIA_DOCUMENT_PATH.$media['media_value'];
            }
        ?>
        <iframe src="<?= $media_file ?>" zooming="true" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
</div>
<style type="text/css">
    iframe{
        width: 100%;
        height: 83vh;
        border: 0px;
    }
</style>
<!-- * App Capsule -->