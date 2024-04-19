<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member_saldo_setting"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-6">
                <!--Begin::Section-->
                <div class="kt-portlet">

                    <?php
                    $attributes = array('autocomplete'=>"off");
                    echo form_open($form_action, $attributes);
                    ?>
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">

                        <div class="kt-portlet__body">

                            <div class="form-group m-form__group row">

                                <div class="col-lg-12">

                                    <label class="mt-3">Start Date</label>
                                    <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="mss_start" value="<?php
                                    if (validation_errors()) {echo set_value('mss_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['mss_start'])), ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">End Date</label>
                                    <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="mss_end" value="<?php
                                    if (validation_errors()) {echo set_value('mss_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['mss_end'])), ENT_QUOTES) : '';} ?>">


                                    <label class="mt-3">Group</label>
                                    <?php
                                    if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                                    $attr = 'class="form-control" required';
                                    echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                                    ?>

                                    <label class="mt-3">Saldo</label>
                                    <input type="number" class="form-control kt-input" placeholder="" name="mss_saldo" required value="<?php
                                    if (validation_errors()) {echo set_value('mss_saldo');}else{echo isset($request) ? htmlentities($request['mss_saldo'], ENT_QUOTES) : '';} ?>">


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