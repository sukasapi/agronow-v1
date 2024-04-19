<?php if($this->session->flashdata('flash_msg')==TRUE): ?>
    <div class="col-lg-12">

        <div class="alert alert-<?php echo $this->session->flashdata('flash_msg_type') ?> fade show" role="alert">
            <div class="alert-icon">
                <?php
                if ($this->session->flashdata('flash_msg_status')==TRUE) {
                    echo "<i class=\"flaticon2-check-mark\"></i>";
                } else{
                    echo "<i class=\"flaticon-warning\"></i>";
                }
                ?>
            </div>
            <div class="alert-text"><?php echo $this->session->flashdata('flash_msg_text'); ?></div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>

    </div>
<?php endif; ?>