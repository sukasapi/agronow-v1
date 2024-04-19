<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("survey"); ?>" class="btn kt-subheader__btn-primary">
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
                                Survey
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
                                <label>Nama Survey *</label>
                                <input type="text" class="form-control" placeholder="" name="survey_name" required value="<?php
                                if (validation_errors()) {echo set_value('survey_name');}else{echo isset($request) ? htmlentities($request['survey_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Keterangan</label>
                                <textarea id="content" name="survey_desc" style="min-height: 400px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('survey_desc');}else{echo isset($request) ? htmlentities($request['survey_desc'], ENT_QUOTES) : '';} ?></textarea>


                                <label class="mt-3">Status *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="survey_status" required checked="" value="draft" <?php
                                        echo set_value('survey_status', ($editable)?$request['survey_status']:'') == 'draft' ? "checked" : "checked";
                                        ?>> Draft
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="survey_status" required value="publish" <?php
                                        echo set_value('survey_status', ($editable)?$request['survey_status']:'') == 'publish' ? "checked" : "";
                                        ?>> Publish
                                        <span></span>
                                    </label>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONTENT -->


            </div>

            <div class="col-lg-5">


                <!-- START PORTLET WAKTU -->
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
                                        <label>Tanggal Mulai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="survey_date_start" value="<?php
                                        if (validation_errors()) {echo set_value('survey_date_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['survey_date_start'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tanggal Selesai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="survey_date_end" value="<?php
                                        if (validation_errors()) {echo set_value('survey_date_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['survey_date_end'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET WAKTU -->


                <!-- START PORTLET GAMBAR -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Gambar
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
                                <img id="image" src="<?php echo isset($request['media_value'])?URL_MEDIA_IMAGE.$request['media_value']:'' ?>" width="100%">
                                <br>
                                <label class="mt-3">Pilih Gambar</label>
                                <br>
                                <input type="file" id="files" name="file" accept="image/x-png,image/gif,image/jpeg">
                                <br><br>
                                <p>Gunakan gambar dengan ratio 16:9, contoh: 1280x720</p>
                                <script>
                                    document.getElementById("files").onchange = function () {
                                        var reader = new FileReader();
                                        reader.onload = function (e) {
                                            // get loaded data and render thumbnail.
                                            document.getElementById("image").src = e.target.result;
                                        };
                                        // read the image file as a data URL.
                                        reader.readAsDataURL(this.files[0]);
                                    };
                                </script>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET GAMBAR -->


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





<!--end::Page Resources -->