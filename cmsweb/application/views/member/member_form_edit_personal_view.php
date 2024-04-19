<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member/detail/".$member['member_id']); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">

        <?php
        $attributes = array('autocomplete'=>"off");
        echo form_open_multipart($form_action, $attributes);
        ?>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">
        <input type="hidden" name="member_id" value="<?= $member['member_id'] ?>" />

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-8">

                <!-- START PORTLET PERSONAL -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Personal
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-12">


                                <label>Nama *</label>
                                <input type="text" class="form-control" placeholder="" name="member_name" required value="<?php
                                if (validation_errors()) {echo set_value('member_name');}else{echo isset($request) ? htmlentities($request['member_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Telp / HP *</label>
                                <input type="text" class="form-control" placeholder="" name="member_phone" required value="<?php
                                if (validation_errors()) {echo set_value('member_phone');}else{echo isset($request) ? htmlentities($request['member_phone'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Provinsi</label>
                                <?php
                                if (validation_errors()) {$val = set_value('member_province');}else{$val = isset($request) ? htmlentities($request['member_province'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control"';
                                echo form_dropdown('member_province', $form_opt_province, $val, $attr);
                                ?>

                                <label class="mt-3">Kota / Kab</label>
                                <input type="text" class="form-control" placeholder="" name="member_city" value="<?php
                                if (validation_errors()) {echo set_value('member_city');}else{echo isset($request) ? htmlentities($request['member_city'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Alamat</label>
                                <textarea name="member_address" style="min-height: 100px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('member_address');}else{echo isset($request) ? htmlentities($request['member_address'], ENT_QUOTES) : '';} ?></textarea>


                            </div>
                        </div>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-info pl-5 pr-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- END PORTLET PERSONAL -->

            </div>


        </div>

        <?php echo form_close(); ?>

    </div>


</div>




<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>






<!--end::Page Resources -->