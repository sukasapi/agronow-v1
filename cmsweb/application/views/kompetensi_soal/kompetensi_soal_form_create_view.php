<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi_soal"); ?>" class="btn kt-subheader__btn-primary">
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
        echo form_open($form_action, $attributes);
        ?>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-8">

                <!-- START PORTLET CONTENT -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Soal
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
                                <label>Kategori *</label>
                                <?php
                                if (validation_errors()) {$val = set_value('cat_id');}else{$val = isset($request) ? htmlentities($request['cat_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="select2_cat" required';
                                echo form_dropdown('cat_id', $form_opt_cat, $val, $attr);
                                ?>

                                <label class="mt-3">Level *</label>
                                <input type="number" class="form-control" placeholder="" name="crs_level" required value="<?php
                                if (validation_errors()) {echo set_value('crs_level');}else{echo isset($request) ? htmlentities($request['crs_level'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Pertanyaan *</label>
                                <textarea id="content" class="form-control" style="min-height: 500px" placeholder="" name="crs_question"><?php
                                    if (validation_errors()) {echo set_value('crs_question');}else{echo isset($request) ? htmlentities($request['crs_question'], ENT_QUOTES) : '';} ?></textarea>


                                <label class="mt-3">Jawaban Benar *</label>
                                <input type="text" class="form-control" placeholder="" name="crs_right" required value="<?php
                                if (validation_errors()) {echo set_value('crs_right');}else{echo isset($request) ? htmlentities($request['crs_right'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Jawaban Salah A *</label>
                                <input type="text" class="form-control" placeholder="" name="crs_answer1" required value="<?php
                                if (validation_errors()) {echo set_value('crs_answer1');}else{echo isset($request) ? htmlentities($request['crs_answer1'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Jawaban Salah B *</label>
                                <input type="text" class="form-control" placeholder="" name="crs_answer2" required value="<?php
                                if (validation_errors()) {echo set_value('crs_answer2');}else{echo isset($request) ? htmlentities($request['crs_answer2'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Jawaban Salah C *</label>
                                <input type="text" class="form-control" placeholder="" name="crs_answer3" required value="<?php
                                if (validation_errors()) {echo set_value('crs_answer3');}else{echo isset($request) ? htmlentities($request['crs_answer3'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Durasi Soal (detik) *</label>
                                <input type="number" class="form-control" placeholder="" name="crs_durasi_detik" required value="<?php
                                if (validation_errors()) {echo set_value('crs_durasi_detik');}else{echo isset($request) ? htmlentities($request['crs_durasi_detik'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Status:</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="crs_status" required checked="" value="publish" <?php
                                        echo set_value('crs_status', ($editable)?$request['crs_status']:'') == 'publish' ? "checked" : "checked";
                                        ?>> Publish
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="crs_status" required value="draft" <?php
                                        echo set_value('crs_status', ($editable)?$request['crs_status']:'') == 'draft' ? "checked" : "";
                                        ?>> Draft
                                        <span></span>
                                    </label>
                                </div>


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
                <!-- END PORTLET CONTENT -->


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

            $('.timepicker').timepicker({
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


            $("#select2_cat").select2({
                placeholder: "Cari..",
                allowClear: !0,
                multiple: false,
                ajax: {
                    url: "<?php echo site_url('kompetensi_category/ajax_search'); ?>",
                    dataType: "json",
                    delay: 50,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: 0
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0
            }).on('select2:unselecting', function() {
                $(this).data('unselecting', true);
            }).on('select2:opening', function(e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });



        }
    };
    jQuery(document).ready(function() {
        Select2.init()
    });
</script>



<!--end::Page Resources -->