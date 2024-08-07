<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("user"); ?>" class="btn kt-subheader__btn-primary">
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

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-8">

                <!-- START PORTLET AKSES -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Akses
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
                                <input type="text" class="form-control" placeholder="" name="user_name" required value="<?php
                                if (validation_errors()) {echo set_value('user_name');}else{echo isset($request) ? htmlentities($request['user_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Email *</label>
                                <input type="email" class="form-control" placeholder="" name="user_email" required value="<?php
                                if (validation_errors()) {echo set_value('user_email');}else{echo isset($request) ? htmlentities($request['user_email'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Password *</label>
                                <input type="password" class="form-control" placeholder="" name="user_password" required value="<?php
                                if (validation_errors()) {echo set_value('user_password');}else{echo isset($request) ? htmlentities($request['user_password'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Konfirmasi Password *</label>
                                <input type="password" class="form-control" placeholder="" name="user_password_confirm" required value="<?php
                                if (validation_errors()) {echo set_value('user_password_confirm');}else{echo isset($request) ? htmlentities($request['user_password_confirm'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Level</label>
                                <?php
                                if (validation_errors()) {$val = set_value('user_level_id');}else{$val = isset($request) ? htmlentities($request['user_level_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control"';
                                echo form_dropdown('user_level_id', $form_opt_level, $val, $attr);
                                ?>

                                <label class="mt-3">Klien</label>
                                <?php
                                if (validation_errors()) {$val = set_value('id_klien');}else{$val = isset($request) ? htmlentities($request['id_klien'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="id_klien"';
                                echo form_dropdown('id_klien', $form_opt_klien, $val, $attr);
                                ?>

                                <label class="mt-3">Group</label>
                                <?php
                                if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="group_id"';
                                echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                                ?>

                                <script>
                                    $('#id_klien').change(function(){
                                        $("#group_id").empty();

                                        var id = $(this).val();

                                        // Generate Group
                                        $.ajax({
                                            type: "GET",
                                            url: "<?= site_url('group/ajax_get_by_klien/') ?>"+id,
                                            success: function( data ) {
                                                //alert(data);

                                                data = JSON.parse(data);
                                                // loop through our returned data and add an option to the select for each province returned
                                                $('#group_id').append($('<option>', {value:'', text:'-'}));
                                                $.each(data, function(i, item) {
                                                    $('#group_id').append($('<option>', {value:i, text:item}));
                                                });
                                            }
                                        });



                                    });



                                </script>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET SEO -->


            </div>

            <div class="col-lg-4">


                <!-- START PORTLET STATUS -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Status
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

                                <label>Status</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="user_status" required checked="" value="active" <?php
                                        echo set_value('user_status', ($editable)?$request['user_status']:'') == 'active' ? "checked" : "checked";
                                        ?>> Active
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="user_status" required value="block" <?php
                                        echo set_value('user_status', ($editable)?$request['user_status']:'') == 'block' ? "checked" : "";
                                        ?>> Block
                                        <span></span>
                                    </label>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET STATUS -->


            </div>

            <div class="col-lg-12">
                <!--Begin::Section-->
                <div class="kt-portlet">

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <!--End::Section-->
            </div>

        </div>

        <?php echo form_close(); ?>

    </div>


</div>

<!--begin::Page Resources -->


<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datetimepicker({
        format: "dd/mm/yyyy hh:ii",
        language: 'id',
        todayBtn: true,
        todayHighlight:true,
        autoclose: true
    });
</script>

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