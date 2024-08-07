<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("digital_library_category"); ?>" class="btn kt-subheader__btn-primary">
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

                                <input type="hidden" name="cat_id" value="<?php echo $request['cat_id']; ?>" />

                                <div class="col-lg-6">
                                    <label>Nama Kategori:</label>
                                    <input type="text" class="form-control kt-input" placeholder="" name="cat_name" required value="<?php
                                    if (validation_errors()) {echo set_value('cat_name');}else{echo isset($request) ? htmlentities($request['cat_name'], ENT_QUOTES) : '';} ?>">


                                    <!-- Superadmin -->
                                    <?php if(!my_klien()): ?>
                                        <label class="mt-3">Klien:</label>
                                        <div class="kt-checkbox-inline">
                                            <?php
                                            $arr_value = getKlienBySectionData($request['section_id'],$request['cat_id'],'category');
                                            if (validation_errors()){
                                                $arr_value = set_value('klien')?set_value('klien'):array();
                                            }
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


                                    <label class="mt-3">Status:</label>
                                    <div class="kt-radio-inline">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="cat_status" required checked="" value="0" <?php
                                            echo set_value('cat_status', ($editable)?$request['cat_status']:'') == '0' ? "checked" : "checked";
                                            ?>> Non-Active
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="cat_status" required value="1" <?php
                                            echo set_value('cat_status', ($editable)?$request['cat_status']:'') == '1' ? "checked" : "";
                                            ?>> Active
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
                                        <button type="submit" class="btn btn-info pl-5 pr-5">Update</button>
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

            <?php if(!my_klien()): ?>
            // Handle Klien Blank
            checkedKlien = $(".check-klien:checked").length;
            if(!checkedKlien) {
                alert("Anda harus mencentang setidaknya satu Klien.");
                return false;
            }
            <?php endif; ?>

        });
    });
</script>


<!--end::Page Resources -->