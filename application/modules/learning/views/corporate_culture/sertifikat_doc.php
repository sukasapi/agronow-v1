<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    div#appCapsule{
        padding: 56px 0 46px 0px;
    }
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
            $media_file = base_url().PDFJS_VIEWER.'?file='.$doc;
        ?>
        <iframe src="<?= $media_file ?>"></iframe>
    </div>
</div>
<!-- * App Capsule -->