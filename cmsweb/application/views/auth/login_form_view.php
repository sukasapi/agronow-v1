

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">


            <!--begin::Aside-->
            <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url(<?php echo base_url('assets'); ?>/media/bg/login-bg.jpg);">
                <div class="kt-grid__item">

                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver" style="height: 150px;">

                </div>
                <div class="kt-grid__item">
                    <div class="kt-login__info">

                    </div>
                </div>
            </div>
            <!--begin::Aside-->


            <!--begin::Content-->
            <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">


                <!--begin::Body-->
                <div class="kt-login__body">



                    <div class="kt-login__signin">

                        <?php if(validation_errors()==TRUE): ?>

                            <div class="alert alert-danger alert-dismissable fade show" role="alert">
                                <div class="alert-text"><?php echo validation_errors(); ?></div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="kt-login__form">
                            <h3 class="kt-login__title"><center>Login to Dashboard</center></h3>

                            <?php
                            $attributes = array('id'=>'form-validator','class' => 'kt-login__form kt-form', 'autocomplete'=>"off");
                            echo form_open($form_action, $attributes);
                            ?>
                            <input autocomplete="false" name="hidden" type="text" style="display:none;">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-last" type="password" placeholder="Password" name="password"  autocomplete="off">
                            </div>
                            <div class="kt-login__extra">

                            </div>
                            <div class="mt-5">
                                <center>
                                <button type="submit" class="btn btn-brand pl-5 pr-5">Login</button>
                                </center>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                </div>
                <!--end::Body-->
            </div>
            <!--end::Content-->
        </div>
    </div>
</div>

<!-- end:: Page -->