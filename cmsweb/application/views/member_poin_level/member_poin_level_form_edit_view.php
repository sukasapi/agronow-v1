<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member_poin_level"); ?>" class="btn kt-subheader__btn-primary">
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

                                <input type="hidden" name="mpl_id" value="<?php echo $request['mpl_id']; ?>" />

                                <div class="col-lg-12">
                                    <label>Nama Level</label>
                                    <input type="text" class="form-control kt-input" placeholder="" name="mpl_name" required value="<?php
                                    if (validation_errors()) {echo set_value('mpl_name');}else{echo isset($request) ? htmlentities($request['mpl_name'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Min</label>
                                    <input type="number" min="0" class="form-control kt-input" placeholder="" name="mpl_poin_min" required value="<?php
                                    if (validation_errors()) {echo set_value('mpl_poin_min');}else{echo isset($request) ? htmlentities($request['mpl_poin_min'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Max</label>
                                    <input type="number" min="0" class="form-control kt-input" placeholder="" name="mpl_poin_max" required value="<?php
                                    if (validation_errors()) {echo set_value('mpl_poin_max');}else{echo isset($request) ? htmlentities($request['mpl_poin_max'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Saldo Reward</label>
                                    <input type="number" min="0" class="form-control kt-input" placeholder="" name="mpl_reward_saldo" required value="<?php
                                    if (validation_errors()) {echo set_value('mpl_reward_saldo');}else{echo isset($request) ? htmlentities($request['mpl_reward_saldo'], ENT_QUOTES) : '';} ?>">


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
        });
    });
</script>


<!--end::Page Resources -->