<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("ads"); ?>" class="btn kt-subheader__btn-primary">
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
                                Materi Ads
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

                                <label>Gambar *</label>
                                <div id="files">
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="kv-explorer form-control" required type="file" name="file">
                                        </div>
                                    </div>
                                    <small>Max size: 5mb, format jpg. png atau gif, ukuran gambar ideal 1024 px (lebar) x 500 px (tinggi)</small>
                                </div>


                                <label class="mt-3">Sponsor *</label>
                                <input type="text" class="form-control" placeholder="" name="ads_sponsor" required value="<?php
                                if (validation_errors()) {echo set_value('ads_sponsor');}else{echo isset($request) ? htmlentities($request['ads_sponsor'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Link</label>
                                <input type="text" class="form-control" placeholder="" name="ads_link" value="<?php
                                if (validation_errors()) {echo set_value('ads_link');}else{echo isset($request) ? htmlentities($request['ads_link'], ENT_QUOTES) : '';} ?>">



                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONTENT -->


            </div>

            <div class="col-lg-5">


                <!-- START PORTLET STATUS -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Pengaturan
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
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="ads_start" required value="<?php
                                        if (validation_errors()) {echo set_value('ads_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['ads_start'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tanggal Selesai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="ads_end" required value="<?php
                                        if (validation_errors()) {echo set_value('ads_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['ads_end'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>

                                <label class="mt-3">Status *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_status" required checked="" value="active" <?php
                                        echo set_value('ads_status', ($editable)?$request['ads_status']:'') == 'active' ? "checked" : "checked";
                                        ?>> Active
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_status" required value="block" <?php
                                        echo set_value('ads_status', ($editable)?$request['ads_status']:'') == 'block' ? "checked" : "";
                                        ?>> Block
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_status" required value="expired" <?php
                                        echo set_value('ads_status', ($editable)?$request['ads_status']:'') == 'expired' ? "checked" : "";
                                        ?>> Expired
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-3">Posisi *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_position" required checked="" value="top" <?php
                                        echo set_value('ads_position', ($editable)?$request['ads_position']:'') == 'top' ? "checked" : "checked";
                                        ?>> Top
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_position" required value="middle" <?php
                                        echo set_value('ads_position', ($editable)?$request['ads_position']:'') == 'middle' ? "checked" : "";
                                        ?>> Middle
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ads_position" required value="bottom" <?php
                                        echo set_value('ads_position', ($editable)?$request['ads_position']:'') == 'bottom' ? "checked" : "";
                                        ?>> Bottom
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-3">Urutan *</label>
                                <input type="number" class="form-control" placeholder="" pattern="/+d" name="ads_order" required value="<?php
                                if (validation_errors()) {echo set_value('ads_order');}else{echo isset($request) ? htmlentities($request['ads_order'], ENT_QUOTES) : '';} ?>">


                                <!-- Superadmin -->
                                <?php if(!my_klien()): ?>
                                    <label class="mt-3">Klien:</label>
                                    <div class="kt-checkbox-inline">
                                        <?php
                                        $arr_value =  set_value('klien')?set_value('klien'):array();
                                        ?>

                                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                            <input type="checkbox" id="check-klien-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="klien[]" onchange="checkAll(this,'check-klien')"> SEMUA
                                            <span></span>
                                        </label>

                                        <?php foreach(getKlienAll() as $k => $v): ?>
                                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                                <input type="checkbox" class="check-klien" <?php echo in_array($v['id'],$arr_value)==TRUE?'checked':''; ?>
                                                       value="<?php echo $v['id']; ?>" name="klien[]"> <?php echo $v['nama']; ?>
                                                <span></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

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

                                    $(".check-klien").click(function() {
                                        if ($(".check-klien").prop(":checked")) {

                                        } else {
                                            $('#check-klien-all').prop("checked", false);
                                        }
                                    });
                                </script>

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

            <?php if(!my_klien()): ?>
            // Handle Klien Blank
            checkedKlien = $(".check-klien:checked").length;
            if(!checkedKlien) {
                alert("Anda harus mencentang setidaknya satu Klien.");
                return false;
            }
            <?php endif; ?>

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>


<link href="<?php echo base_url('assets/vendors/custom/bootstrap-fileinput'); ?>/css/fileinput.css" media="all"
      rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/vendors/custom/bootstrap-fileinput'); ?>/themes/explorer/theme.css" media="all"
      rel="stylesheet" type="text/css"/>

<script src="<?php echo base_url('assets/vendors/custom/bootstrap-fileinput'); ?>/js/plugins/sortable.js"
        type="text/javascript"></script>
<script src="<?php echo base_url('assets/vendors/custom/bootstrap-fileinput'); ?>/js/fileinput.js"
        type="text/javascript"></script>
<script src="<?php echo base_url('assets/vendors/custom/bootstrap-fileinput'); ?>/themes/explorer/theme.js"
        type="text/javascript"></script>

<script type="text/javascript">
    $(".kv-explorer").fileinput({
        theme: 'explorer',
        //uploadUrl: '#',
        overwriteInitial: false,
        showUpload: false,
        showUploadedThumbs: false,
        uploadIcon: false,
        showClose: false,
        showCancel: false,
        dropZoneEnabled: false,
        browseOnZoneClick: false,
        maxFileCount: 5,
        autoReplace: false,
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'pdf'],
        layoutTemplates: {progress: ''}
    });

</script>

<style type="text/css">
    .kv-file-upload {
        display: none
    }

    ;
    .progress {
        display: none
    }

    ;
</style>






<!--end::Page Resources -->