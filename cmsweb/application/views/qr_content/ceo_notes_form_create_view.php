<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("employee"); ?>" class="btn kt-subheader__btn-primary">
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

                <!-- START PORTLET PERSONAL -->
                <div class="kt-portlet" data-ktportlet="true">
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
                                <input type="text" class="form-control" placeholder="" name="name" required value="<?php
                                if (validation_errors()) {echo set_value('name');}else{echo isset($request) ? htmlentities($request['name'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Deskripsi <span class="required" aria-required="true"> * </span></label>
                                <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
                                <script>tinymce.init({selector:'#content'});</script>
                                <textarea id="content" class="form-control">Next, use our Get Started docs to setup Tiny!</textarea>


                                <label class="mt-3">Sumber CEO Notes</label>
                                <input type="text" class="form-control" placeholder="" name="domicile_address" value="<?php
                                if (validation_errors()) {echo set_value('domicile_address');}else{echo isset($request) ? htmlentities($request['domicile_address'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Pengarang CEO Notes</label>
                                <input type="text" class="form-control" placeholder="" name="phone" value="<?php
                                if (validation_errors()) {echo set_value('phone');}else{echo isset($request) ? htmlentities($request['phone'], ENT_QUOTES) : '';} ?>">


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PERSONAL -->

                <!-- START PORTLET NOMOR REKENING -->
                <div class="kt-portlet" data-ktportlet="true">
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


                                <label class="mt-3">SEO Title <span class="required" aria-required="true"> * </span></label>
                                <input type="text" class="form-control" placeholder="" name="bank_account_number" required value="<?php
                                if (validation_errors()) {echo set_value('bank_account_number');}else{echo isset($request) ? htmlentities($request['bank_account_number'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">SEO Keywords <span class="required" aria-required="true"> * </span></label>
                                <input type="text" class="form-control" placeholder="" name="bank_account_name" required value="<?php
                                if (validation_errors()) {echo set_value('bank_account_name');}else{echo isset($request) ? htmlentities($request['bank_account_name'], ENT_QUOTES) : '';} ?>">

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET NOMOR REKENING -->


            </div>

            <div class="col-lg-4">

                <!-- START PORTLET PEGAWAI -->
                <div class="kt-portlet" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Cover Image
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

                                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80" width="100%">


                                <label class="mt-3">Pilih Gambar</label>
                                <input type="file">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PEGAWAI -->

                <!-- START PORTLET GAJI DAN PAJAK -->
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


                                <label class="mt-3">Status <span class="required" aria-required="true"> * </span></label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="is_salary_taxable" required checked="" value="1" <?php
                                        echo set_value('is_salary_taxable', ($editable)?$request['is_salary_taxable']:'') == '1' ? "checked" : "checked";
                                        ?>> Draft
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="is_salary_taxable" required value="0" <?php
                                        echo set_value('is_salary_taxable', ($editable)?$request['is_salary_taxable']:'') == '0' ? "checked" : "";
                                        ?>> Publish
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-3">Publish</label>
                                <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="birth_date" value="<?php
                                if (validation_errors()) {echo set_value('birth_date');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['birth_date'])), ENT_QUOTES) : '';} ?>">



                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET GAJI DAN PAJAK -->



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

            $("#select2-bank").select2({
                placeholder: "Cari..",
                allowClear: !0,
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


            $("#select2_employee").select2({
                placeholder: "Cari..",
                allowClear: !0,
                multiple: false,
                ajax: {
                    url: "<?php echo site_url('employee/ajax_search'); ?>",
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


<script type="text/javascript">
    $('#username').on('keypress', function(e) {
        if (e.which == 32){
            return false;
        }
    });

    $("#username").change(function(){
        $("#messageUsername").html("checking...");


        var username=$("#username").val();

        $.ajax({
            type:"post",
            url:"<?php echo site_url('user/check_username_ajax'); ?>",
            data:"username="+username,
            success:function(data){
                if(data==0){
                    $("#messageUsername").html("Username telah dipakai");
                }
                else{
                    $("#messageUsername").html("Username tersedia");
                }
            }
        });

    });
</script>

<!--end::Page Resources -->