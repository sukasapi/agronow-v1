<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("learning_room/detail/").$content['content_id']; ?>" class="btn kt-subheader__btn-primary">
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
        <input type="hidden" name="content_id" value="<?php echo $content['content_id']; ?>" >
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
                                Konten
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
                                <input type="text" class="form-control" placeholder="" name="content_name" required value="<?php
                                if (validation_errors()) {echo set_value('content_name');}else{echo isset($request) ? htmlentities($request['content_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Deskripsi <span class="required" aria-required="true"> * </span></label>
                                <textarea id="content" name="content_desc" style="min-height: 500px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('content_desc');}else{echo isset($request) ? htmlentities($request['content_desc'], ENT_QUOTES) : '';} ?></textarea>

                                <label class="mt-3">Sumber</label>
                                <input type="text" class="form-control" placeholder="" name="content_source" value="<?php
                                if (validation_errors()) {echo set_value('content_source');}else{echo isset($request) ? htmlentities($request['content_source'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Pengarang</label>
                                <input type="text" class="form-control" placeholder="" name="content_author" value="<?php
                                if (validation_errors()) {echo set_value('content_author');}else{echo isset($request) ? htmlentities($request['content_author'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Tag</label>
                                <?php


                                if (validation_errors()) {
                                    $val_tags = set_value('content_tags');
                                    foreach ($val_tags as $k => $v) {
                                        $val[$v] = $v;
                                        $selected_val[$v]  = $v;
                                    }
                                }else{
                                    $val  = array();
                                    $selected_val  = NULL;
                                    if ($content['content_tags']){
                                        $tag_array = explode(',',$content['content_tags']);
                                        foreach ($tag_array as $v){
                                            $val[$v] = $v;
                                            $selected_val[$v]  = $v;
                                        }
                                    }

                                }

                                $attr = 'class="form-control" id="select2-content-tags" multiple="multiple"';
                                echo form_dropdown('content_tags[]', $val, $selected_val, $attr);

                                ?>


                            </div>
                        </div>

                    </div>


                </div>
                <!-- END PORTLET CONTENT -->

                <!-- START PORTLET SEO -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                SEO
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


                                <label>SEO Title</label>
                                <input type="text" class="form-control" placeholder="" name="content_seo_title" value="<?php
                                if (validation_errors()) {echo set_value('content_seo_title');}else{echo isset($request) ? htmlentities($request['content_seo_title'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">SEO Keywords</label>
                                <input type="text" class="form-control" placeholder="" name="content_seo_keyword" value="<?php
                                if (validation_errors()) {echo set_value('content_seo_keyword');}else{echo isset($request) ? htmlentities($request['content_seo_keyword'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">SEO Deskripsi</label>
                                <textarea name="content_seo_desc" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('content_seo_desc');}else{echo isset($request) ? htmlentities($request['content_seo_desc'], ENT_QUOTES) : '';} ?></textarea>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET SEO -->

            </div>


            <div class="col-lg-8">
                <!--Begin::Section-->
                <div class="kt-portlet">

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-info pl-5 pr-5">Update</button>
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