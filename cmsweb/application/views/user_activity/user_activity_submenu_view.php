<?php

$add_css = '';
if($this->session->user_level_id!="1") {
	$add_css = 'd-none';
}

?>

<div class="col-lg-3 <?=$add_css?>">
    <div class="list-group">
        <a href="<?php echo site_url('user_activity/'); ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='')?'active':NULL; ?>">Aktivitas Admin</a>
        <a href="<?php echo site_url('user_activity/read_ceo_notes/'); ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='read_ceo_notes')?'active':NULL; ?>">Aktivitas Baca CEO Notes</a>
        <a href="<?php echo site_url('user_activity/read_bod_share/'); ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='read_bod_share')?'active':NULL; ?>">Aktivitas Baca BOD Share</a>
    </div>
</div>