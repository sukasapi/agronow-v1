<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member_poin_setting/detail/").$mps_monthly['mps_id']; ?>" class="btn kt-subheader__btn-primary">
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

        <input type="hidden" name="mps_id" value="<?php echo $mps_monthly['mps_id']; ?>" />

        <input type="hidden" name="mps_monthly_id" value="<?php echo $mps_monthly['mps_monthly_id']; ?>" />

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

                                <label>Min %</label>
                                <input type="number" class="form-control" placeholder="" name="mps_monthly_percent_min" required value="<?php
                                if (validation_errors()) {echo set_value('mps_monthly_percent_min');}else{echo isset($request) ? htmlentities($request['mps_monthly_percent_min'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Max %</label>
                                <input type="number" class="form-control" placeholder="" name="mps_monthly_percent_max" required value="<?php
                                if (validation_errors()) {echo set_value('mps_monthly_percent_max');}else{echo isset($request) ? htmlentities($request['mps_monthly_percent_max'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Poin</label>
                                <input type="number" class="form-control" placeholder="" name="mps_monthly_poin" required value="<?php
                                if (validation_errors()) {echo set_value('mps_monthly_poin');}else{echo isset($request) ? htmlentities($request['mps_monthly_poin'], ENT_QUOTES) : '';} ?>">

                            </div>
                        </div>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <!-- END PORTLET  -->



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