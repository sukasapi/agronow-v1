<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("pelatihan/detail/").$request['pelatihan_id']; ?>" class="btn kt-subheader__btn-primary">
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

                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Pelatihan
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
                                <input type="hidden" name="pelatihan_id" value="<?= $request['pelatihan_id'] ?>"
                                <label>Nama Pelatihan *</label>
                                <input type="text" class="form-control" placeholder="" name="pelatihan_name" required value="<?php
                                if (validation_errors()) {echo set_value('pelatihan_name');}else{echo isset($request) ? htmlentities($request['pelatihan_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Alamat Lokasi</label>
                                <textarea name="pelatihan_location" style="min-height: 100px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('pelatihan_location');}else{echo isset($request) ? htmlentities($request['pelatihan_location'], ENT_QUOTES) : '';} ?></textarea>

                                <label class="mt-3">Keterangan</label>
                                <textarea name="pelatihan_desc" style="min-height: 100px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('pelatihan_desc');}else{echo isset($request) ? htmlentities($request['pelatihan_desc'], ENT_QUOTES) : '';} ?></textarea>

                            </div>
                        </div>

                    </div>

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
                <!-- END PORTLET MEMBER -->



            </div>



        </div>

        <?php echo form_close(); ?>

    </div>


</div>

<!--begin::Page Resources -->

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


<script>
    // Class definition
    var KTBootstrapTimepicker = function() {
        // Private functions
        var timePickerInit = function() {

            $('.time-picker').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: false,
                showMeridian: false,
                snapToStep: true
            });


        };
        return {
            // public functions
            init: function() {
                timePickerInit();
            }
        };
    }();
    jQuery(document).ready(function() {
        KTBootstrapTimepicker.init();
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