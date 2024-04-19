<!-- App Header -->
<div class="appHeader text-light d-print-none" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?= isset($back_url) ? $back_url : 'javascript:history.back()';?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
	<?php if(isset($menu_kanan_atas)) { echo $menu_kanan_atas; } ?>
</div>
<!-- * App Header -->