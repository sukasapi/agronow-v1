<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member_poin_setting"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-6">

                <!-- START PORTLET  -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-12">

                                <label>Start Date</label>
                                <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="mps_start" value="<?php
                                if (validation_errors()) {echo set_value('mps_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['mps_start'])), ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">End Date</label>
                                <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="mps_end" value="<?php
                                if (validation_errors()) {echo set_value('mps_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['mps_end'])), ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Daily</label>
                                <input type="number" class="form-control" placeholder="" name="mps_daily" required value="<?php
                                if (validation_errors()) {echo set_value('mps_daily');}else{echo isset($request) ? htmlentities($request['mps_daily'], ENT_QUOTES) : '';} ?>">

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET  -->


                <!-- START PORTLET  -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Class Room
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
                            <div class="col-3">
                                <label class="mt-3">Join</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cr_join" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cr_join');}else{echo isset($request) ? htmlentities($request['mps_cr_join'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade A</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cr_grade_a" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cr_grade_a');}else{echo isset($request) ? htmlentities($request['mps_cr_grade_a'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade B</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cr_grade_b" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cr_grade_b');}else{echo isset($request) ? htmlentities($request['mps_cr_grade_b'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade C</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cr_grade_c" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cr_grade_c');}else{echo isset($request) ? htmlentities($request['mps_cr_grade_c'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade D</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cr_grade_d" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cr_grade_d');}else{echo isset($request) ? htmlentities($request['mps_cr_grade_d'], ENT_QUOTES) : '';} ?>">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET  -->



            </div>

            <div class="col-lg-6">

                <!-- START PORTLET  -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Corporate Culture
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
                            <div class="col-3">
                                <label class="mt-3">Join</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cc_join" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cc_join');}else{echo isset($request) ? htmlentities($request['mps_cc_join'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade A</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cc_grade_a" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cc_grade_a');}else{echo isset($request) ? htmlentities($request['mps_cc_grade_a'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade B</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cc_grade_b" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cc_grade_b');}else{echo isset($request) ? htmlentities($request['mps_cc_grade_b'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade C</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cc_grade_c" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cc_grade_c');}else{echo isset($request) ? htmlentities($request['mps_cc_grade_c'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-3">
                                <label class="mt-3">Grade D</label>
                                <input type="number" class="form-control" placeholder="" name="mps_cc_grade_d" required value="<?php
                                if (validation_errors()) {echo set_value('mps_cc_grade_d');}else{echo isset($request) ? htmlentities($request['mps_cc_grade_d'], ENT_QUOTES) : '';} ?>">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET  -->

                <!-- START PORTLET  -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Knowledge Sharing
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
                            <div class="col-4">
                                <label class="mt-3">Approved</label>
                                <input type="number" class="form-control" placeholder="" name="mps_ks_approved" required value="<?php
                                if (validation_errors()) {echo set_value('mps_ks_approved');}else{echo isset($request) ? htmlentities($request['mps_ks_approved'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-4">
                                <label class="mt-3">Reject</label>
                                <input type="number" class="form-control" placeholder="" name="mps_ks_reject" required value="<?php
                                if (validation_errors()) {echo set_value('mps_ks_reject');}else{echo isset($request) ? htmlentities($request['mps_ks_reject'], ENT_QUOTES) : '';} ?>">
                            </div>

                            <div class="col-4">
                                <label class="mt-3">Liked</label>
                                <input type="number" class="form-control" placeholder="" name="mps_ks_liked" required value="<?php
                                if (validation_errors()) {echo set_value('mps_ks_liked');}else{echo isset($request) ? htmlentities($request['mps_ks_liked'], ENT_QUOTES) : '';} ?>">
                            </div>

                        </div>

                    </div>
                </div>
                <!-- END PORTLET  -->


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
<script type="text/javascript">
    function checkAll(ele,className) {
        var checkboxes = document.getElementsByClassName(className);
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' ) {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }

    $(".check-mlevel").click(function() {
        if ($(".check-mlevel").prop(":checked")) {

        } else {
            $('#check-mlevel-all').prop("checked", false);
        }
    });

    $(".check-bidang").click(function() {
        if ($(".check-bidang").prop(":checked")) {

        } else {
            $('#check-bidang-all').prop("checked", false);
        }
    });
</script>

<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        language: 'id',
        todayBtn: 'linked',
        todayHighlight:true,
        autoclose:true
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


<script>
    var Select2 = {
        init: function() {

            $("#select2-content-tags").select2({
                tags: true,
                tokenSeparators: [',']
            });


        }
    };
    jQuery(document).ready(function() {
        Select2.init()
    });
</script>




<!--end::Page Resources -->