<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member"); ?>" class="btn kt-subheader__btn-primary">
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

                <!-- START PORTLET PERSONAL -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Personal
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
                                <input type="text" class="form-control" placeholder="" name="member_name" required value="<?php
                                if (validation_errors()) {echo set_value('member_name');}else{echo isset($request) ? htmlentities($request['member_name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Telp / HP *</label>
                                <input type="text" class="form-control" placeholder="" name="member_phone" required value="<?php
                                if (validation_errors()) {echo set_value('member_phone');}else{echo isset($request) ? htmlentities($request['member_phone'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Provinsi</label>
                                <?php
                                if (validation_errors()) {$val = set_value('member_province');}else{$val = isset($request) ? htmlentities($request['member_province'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control"';
                                echo form_dropdown('member_province', $form_opt_province, $val, $attr);
                                ?>

                                <label class="mt-3">Kota / Kab</label>
                                <input type="text" class="form-control" placeholder="" name="member_city" value="<?php
                                if (validation_errors()) {echo set_value('member_city');}else{echo isset($request) ? htmlentities($request['member_city'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Alamat</label>
                                <textarea name="member_address" style="min-height: 100px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('member_address');}else{echo isset($request) ? htmlentities($request['member_address'], ENT_QUOTES) : '';} ?></textarea>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PERSONAL -->

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


                                <label>NIP *</label>
                                <input type="text" id="nip" class="form-control" placeholder="" name="member_nip" required value="<?php
                                if (validation_errors()) {echo set_value('member_nip');}else{echo isset($request) ? htmlentities($request['member_nip'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Email</label>
                                <input type="email" class="form-control" placeholder="" name="member_email" value="<?php
                                if (validation_errors()) {echo set_value('member_email');}else{echo isset($request) ? htmlentities($request['member_email'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Password *</label>
                                <input type="password" class="form-control" placeholder="" name="member_password" required value="<?php
                                if (validation_errors()) {echo set_value('member_password');}else{echo isset($request) ? htmlentities($request['member_password'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Konfirmasi Password *</label>
                                <input type="password" class="form-control" placeholder="" name="member_password_confirm" required value="<?php
                                if (validation_errors()) {echo set_value('member_password_confirm');}else{echo isset($request) ? htmlentities($request['member_password_confirm'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Group *</label>
                                <?php
                                if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                                $attr = 'id="group_id" class="form-control" required';
                                echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                                ?>

                                <label class="mt-3">Jabatan</label>
                                <?php
                                if (validation_errors()) {$val = set_value('jabatan_id');}else{$val = isset($request) ? htmlentities($request['jabatan_id'], ENT_QUOTES) : NULL;}

                                $attr = 'id="jabatan_id" class="form-control" ';
                                echo form_dropdown('jabatan_id', $form_opt_jabatan, $val, $attr);
                                ?>

                                <small>Ketika insert jabatan maka otomatis member akan dimasukkan sebagai perserta kompetensi berdasarkan jabatan</small>
                                <br>

                                <script>
                                    $('#group_id').change(function(){
                                        $("#jabatan_id").empty();

                                        var id = $(this).val();

                                        // Generate Jabatan
                                        $.ajax({
                                            type: "GET",
                                            url: "<?= site_url('jabatan/ajax_get_by_group/') ?>"+id,
                                            success: function( data ) {
                                                //alert(data);

                                                data = JSON.parse(data);
                                                // loop through our returned data and add an option to the select for each province returned
                                                $('#jabatan_id').append($('<option>', {value:'', text:'-'}));
                                                $.each(data, function(i, item) {
                                                    $('#jabatan_id').append($('<option>', {value:i, text:item}));
                                                });
                                            }
                                        });

                                        // Cek NIP Group
                                        var nip = $('#nip').val();
                                        $.ajax({
                                            type: "GET",
                                            url: "<?= site_url('member/ajax_get_by_group_nip/') ?>"+id+"/"+nip,
                                            success: function( data ) {
                                                //alert(data);

                                                data = JSON.parse(data);
                                                if (data){
                                                    alert('NIP sudah ada');
                                                    $('#nip').val('');
                                                }
                                            }
                                        });

                                    });


                                    $('#nip').keyup(function(){
                                        var group_id = $('#group_id').val();
                                        var nip = $(this).val();

                                        $.ajax({
                                            type: "GET",
                                            url: "<?= site_url('member/ajax_get_by_group_nip/') ?>"+group_id+"/"+nip,
                                            success: function( data ) {
                                                //alert(data);

                                                data = JSON.parse(data);
                                                if (data){
                                                    alert('NIP sudah ada');
                                                    $('#nip').val('');
                                                }
                                            }
                                        });

                                    });
                                </script>
								
								<label class="mt-3">Level Karyawan</label>
                                <?php
                                if (validation_errors()) {$val = set_value('id_level_karyawan');}else{$val = isset($request) ? htmlentities($request['id_level_karyawan'], ENT_QUOTES) : NULL;}

                                $attr = 'id="id_level_karyawan" class="form-control" ';
                                echo form_dropdown('id_level_karyawan', $form_opt_level_karyawan, $val, $attr);
                                ?>
                                <br>

                                <script>
                                    $('#group_id').change(function(){
                                        $("#id_level_karyawan").empty();

                                        var id = $(this).val();

                                        // Generate Jabatan
                                        $.ajax({
                                            type: "GET",
                                            url: "<?= site_url('member/ajax_get_level_karyawan_by_group/') ?>"+id,
                                            success: function( data ) {
                                                // alert(data);

                                                data = JSON.parse(data);
                                                $('#id_level_karyawan').append($('<option>', {value:'', text:'-'}));
                                                $.each(data, function(i, item) {
													$('#id_level_karyawan').append($('<option>', {value:item.id, text:item.nama}));
                                                });
                                            }
                                        });

                                    });
                                </script>

                                <label class="mt-3">Level</label>
                                <?php
                                if (validation_errors()) {$val = set_value('mlevel_id');}else{$val = isset($request) ? htmlentities($request['mlevel_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control"';
                                echo form_dropdown('mlevel_id', $form_opt_level, $val, $attr);
                                ?>

                                <label class="mt-3">Bidang</label>
                                <?php
                                if (validation_errors()) {$val = set_value('member_desc');}else{$val = isset($request) ? htmlentities($request['member_desc'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control"';
                                echo form_dropdown('member_desc', $form_opt_bidang, $val, $attr);
                                ?>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET SEO -->


            </div>

            <div class="col-lg-4">

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
                                        <input type="radio" name="member_status" required checked="" value="active" <?php
                                        echo set_value('member_status', ($editable)?$request['member_status']:'') == 'active' ? "checked" : "checked";
                                        ?>> Active
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="member_status" required value="block" <?php
                                        echo set_value('member_status', ($editable)?$request['member_status']:'') == 'block' ? "checked" : "";
                                        ?>> Block
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-3">Create CEO Notes / BOD Share</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="member_ceo" required checked="" value="0" <?php
                                        echo set_value('member_ceo', ($editable)?$request['member_ceo']:'') == '0' ? "checked" : "checked";
                                        ?>> Not Allow
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="member_ceo" required value="1" <?php
                                        echo set_value('member_ceo', ($editable)?$request['member_ceo']:'') == '1' ? "checked" : "";
                                        ?>> CEO Share
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="member_ceo" required value="2" <?php
                                        echo set_value('member_ceo', ($editable)?$request['member_ceo']:'') == '2' ? "checked" : "";
                                        ?>> BOD Notes
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