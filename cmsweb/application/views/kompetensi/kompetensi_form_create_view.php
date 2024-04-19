<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-7">

                <!-- START PORTLET CONTENT -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Kompetensi
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
                                <label>Nama Kompetensi *</label>
                                <input type="text" class="form-control" placeholder="" name="cr_name" required value="<?php
                                if (validation_errors()) {echo set_value('cr_name');}else{echo isset($request) ? htmlentities($request['cr_name'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Kategori *</label>
                                <?php
                                if (validation_errors()) {$val = set_value('cat_id');}else{$val = isset($request) ? htmlentities($request['cat_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="select2_cat" required';
                                echo form_dropdown('cat_id', $form_opt_cat, $val, $attr);
                                ?>


                                <label class="mt-3">Jumlah Level *</label>
                                <input type="number" class="form-control" id="cr_komp_max_lv" min="1" max="99" placeholder="" name="cr_komp_max_lv" required value="<?php
                                if (validation_errors()) {echo set_value('cr_komp_max_lv');}else{echo isset($request) ? htmlentities($request['cr_komp_max_lv'], ENT_QUOTES) : '';} ?>">



                                <label class="mt-3">Keterangan</label>
                                <textarea id="content" name="cr_desc" style="min-height: 300px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('cr_desc');}else{echo isset($request) ? htmlentities($request['cr_desc'], ENT_QUOTES) : '';} ?></textarea>

                                <label class="mt-3">Prasyarat</label>
                                <br><small>Daftar pelatihan yang wajib diikuti jika kompetensi kurang</small>
                                <textarea name="cr_materi" style="min-height: 300px" class="form-control content-tinymce"><?php
                                    if (validation_errors()) {echo set_value('cr_materi');}else{echo isset($request) ? htmlentities($request['cr_materi'], ENT_QUOTES) : '';} ?></textarea>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONTENT -->


            </div>

            <div class="col-lg-5">


                <!-- START PORTLET TANGGAL -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Waktu
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

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tahun *</label>
                                        <input type="number" class="form-control" placeholder="yyyy" name="cr_year" required value="<?php
                                        if (validation_errors()) {echo set_value('cr_year');}else{echo isset($request) ? htmlentities($request['cr_year'], ENT_QUOTES) : '';} ?>">

                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <label>Tanggal Mulai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="cr_date_start" value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_start'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tanggal Selesai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="cr_date_end" value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_end'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET TANGGAL -->


                <!-- START PORTLET HARIAN -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Kompetensi Harian
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

                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_is_daily" required checked="" value="0" <?php
                                        echo set_value('cr_is_daily', ($editable)?$request['cr_is_daily']:'') == '0' ? "checked" : "checked";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_is_daily" required value="1" <?php
                                        echo set_value('cr_is_daily', ($editable)?$request['cr_is_daily']:'') == '1' ? "checked" : "";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET HARIAN -->



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

                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_status" id="cr_status_draft" required checked="" value="draft" <?php
                                        echo set_value('cr_status', ($editable)?$request['cr_status']:'') == 'draft' ? "checked" : "checked";
                                        ?>> Draft
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_status" id="cr_status_publish" required value="publish" <?php
                                        echo set_value('cr_status', ($editable)?$request['cr_status']:'') == 'publish' ? "checked" : "";
                                        ?>> Publish
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
                                        <button type="submit" id="btn-save" class="btn btn-success pl-5 pr-5">Simpan</button>
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



<script>


    function validate_level() {
        var level = $("#cr_komp_max_lv").val();
        var cat_id = $("#select2_cat").val();

        var url = '<?= site_url('kompetensi/is_valid_level_category/') ?>'+level+'/'+cat_id;

        $.get( url, function( data ) {
            var obj = JSON.parse(data);

            //console.log(obj);
            if (obj.status==false){
                //$("#cr_komp_max_lv").val('');
                alert(obj.message);

                $('#cr_status_publish').prop('disabled', true);
                $('#cr_status_draft').prop('checked', true);


            } else{

                $('#cr_status_publish').prop('disabled', false);
            }

        });

    }


    $( "#cr_komp_max_lv" ).keyup(function() {
        validate_level();
    });


    $( "#select2_cat" ).keyup(function() {
        validate_level();
    });
</script>



<!--end::Page Resources -->