<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("group"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-lg-12">
                <!--Begin::Section-->
                <div class="kt-portlet">

                    <?php
                    $attributes = array('autocomplete'=>"off");
                    echo form_open($form_action, $attributes);
                    ?>
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">

                        <div class="kt-portlet__body">

                            <div class="form-group m-form__group row">

                                <div class="col-lg-6">
                                    <label>Nama Group* :</label>
                                    <input type="text" class="form-control kt-input" placeholder="" name="group_name" required value="<?php
                                    if (validation_errors()) {echo set_value('group_name');}else{echo isset($request) ? htmlentities($request['group_name'], ENT_QUOTES) : '';} ?>">


                                    <label class="mt-3">Kode di Aghris:</label>
                                    <input type="text" class="form-control kt-input" placeholder="" name="aghris_company_code" value="<?php
                                    if (validation_errors()) {echo set_value('aghris_company_code');}else{echo isset($request) ? htmlentities($request['aghris_company_code'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Klien:</label>
                                    <?php
                                    if (validation_errors()) {$val = set_value('id_klien');}else{$val = isset($request) ? htmlentities($request['id_klien'], ENT_QUOTES) : NULL;}

                                    $attr = 'class="form-control" required';
                                    echo form_dropdown('id_klien', $form_opt_klien, $val, $attr);
                                    ?>

                                    <label class="mt-3">Status:</label>
                                    <div class="kt-radio-inline">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_status" required checked="" value="block" <?php
                                            echo set_value('group_status', ($editable)?$request['group_status']:'') == 'block' ? "checked" : "checked";
                                            ?>> Non-Active
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_status" required value="active" <?php
                                            echo set_value('group_status', ($editable)?$request['group_status']:'') == 'active' ? "checked" : "";
                                            ?>> Active
                                            <span></span>
                                        </label>
                                    </div>

                                    <label class="mt-3">Portal:</label>
                                    <div class="kt-radio-inline">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_portal" required checked="" value="0" <?php
                                            echo set_value('group_portal', ($editable)?$request['group_portal']:'') == '0' ? "checked" : "checked";
                                            ?>> Non-Active
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_portal" required value="1" <?php
                                            echo set_value('group_portal', ($editable)?$request['group_portal']:'') == '1' ? "checked" : "";
                                            ?>> Active
                                            <span></span>
                                        </label>
                                    </div>

                                    <label class="mt-3">Memiliki Level:</label>
                                    <div class="kt-radio-inline">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_has_level" required checked="" value="0" <?php
                                            echo set_value('group_has_level', ($editable)?$request['group_has_level']:'') == '0' ? "checked" : "checked";
                                            ?>> Tidak
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="group_has_level" required value="1" <?php
                                            echo set_value('group_has_level', ($editable)?$request['group_has_level']:'') == '1' ? "checked" : "";
                                            ?>> Ya
                                            <span></span>
                                        </label>
                                    </div>

                                </div>


                            </div>



                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions m-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>

                </div>
                <!--End::Section-->
            </div>

        </div>

    </div>

</div>


<!--begin::Page Resources -->
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
        });
    });
</script>


<!--end::Page Resources -->