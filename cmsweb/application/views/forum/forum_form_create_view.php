<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("forum"); ?>" class="btn kt-subheader__btn-primary">
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
                <div class="kt-portlet" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Topik
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
                                <label>Judul <span class="required" aria-required="true"> * </span></label>
                                <input type="text" class="form-control" placeholder="" name="forum_name" required value="<?php
                                if (validation_errors()) {echo set_value('forum_name');}else{echo isset($request) ? htmlentities($request['forum_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Kategori *</label>
                                <select class="form-control kt-input" name="cat_id">
                                    <?php
                                    foreach ($option_tree as $v){
                                        echo $v;
                                    }
                                    ?>
                                </select>
                                <?php
                                /*if (validation_errors()) {$val = set_value('cat_id');}else{$val = isset($request) ? htmlentities($request['cat_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="select2_cat"';
                                echo form_dropdown('cat_id', $form_opt_cat, $val, $attr);*/
                                ?>

                                <label class="mt-3">Deskripsi <span class="required" aria-required="true"> * </span></label>
                                <textarea id="content" name="forum_desc" style="min-height: 500px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('forum_desc');}else{echo isset($request) ? htmlentities($request['forum_desc'], ENT_QUOTES) : '';} ?></textarea>



                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONTENT -->



            </div>

            <div class="col-lg-4">


                <!-- START PORTLET CONFIG -->
                <div class="kt-portlet" data-ktportlet="true">
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


                                <label>Sticky <span class="required" aria-required="true"> * </span></label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="forum_sticky" required checked="" value="0" <?php
                                        echo set_value('forum_sticky', ($editable)?$request['forum_sticky']:'') == '0' ? "checked" : "checked";
                                        ?>> General
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="forum_sticky" required value="1" <?php
                                        echo set_value('forum_sticky', ($editable)?$request['forum_sticky']:'') == '1' ? "checked" : "";
                                        ?>> Sticky
                                        <span></span>
                                    </label>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONFIG -->



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
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        language: 'id',
        todayBtn: 'linked',
        todayHighlight:true
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
                    url: "<?php echo site_url('forum_category/ajax_search'); ?>",
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