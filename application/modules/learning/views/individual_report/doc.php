<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe{
        width: 100%;
        height: 81.4vh;
        border: none;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php
            // - use basic iframe -
            // $media_file = $doc.'#toolbar=0';
            
            // - use pdfjs -
            // because pdfjs can't do cross domain,
            // download the file to local
            
//            $targetFilePath = getcwd().'/'.SERTIFIKAT_PATH.basename($doc);
//            if (!is_file($targetFilePath)) {
//                file_put_contents($targetFilePath, file_get_contents($doc));
//            }
			$media_file = base_url().PDFJS_VIEWER.'?file='.$dfile;
        ?>
		<iframe src="<?= $media_file ?>"></iframe>
    </div>
</div>
<!-- * App Capsule -->
