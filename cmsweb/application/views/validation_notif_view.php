<?php if(validation_errors()): ?>
    <div class="col-lg-12">

        <div class="alert alert-warning fade show" role="alert">
            <div class="alert-icon">
                <i class="flaticon-warning"></i>
            </div>
            <div class="alert-text"><?php echo validation_errors(); ?></div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>

    </div>
<?php endif; ?>