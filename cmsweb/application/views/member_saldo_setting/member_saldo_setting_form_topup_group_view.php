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

                                    <label class="mt-3">Sumber</label>
                                    <?php
                                    if (validation_errors()) {$val = set_value('ms_source');}else{$val = isset($request) ? htmlentities($request['ms_source'], ENT_QUOTES) : NULL;}

                                    $attr = 'class="form-control" id="ms_source" required';
                                    echo form_dropdown('ms_source', $form_opt_source, $val, $attr);
                                    ?>

                                    <label class="mt-3">Group</label>
                                    <?php
                                    if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                                    $attr = 'class="form-control" id="group_id" required';
                                    echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                                    ?>

                                    <script>
                                        $("#group_id").on('change', function(){
                                            var val = $(this).val();

                                            $.get("<?= site_url('member_saldo_setting/ajax_get_config_saldo_group/') ?>"+val, function(data, status){
                                                $("#saldo").val(data);
                                            });
                                        });

                                        $("#ms_source").on('change', function(){
                                            var val = $(this).val();

                                            if (val=='Reward'){
                                                groupID = $("#group_id").val();
                                                $.get("<?= site_url('member_saldo_setting/ajax_get_config_saldo_group/') ?>"+groupID, function(data, status){
                                                    $("#saldo").val(data);
                                                });

                                                $('#saldo').prop('readonly', true);
                                            } else{
                                                $('#saldo').prop('readonly', false);
                                            }


                                        });
                                    </script>

                                    <label class="mt-3">Saldo</label>
                                    <input id="saldo" type="number" min="1" class="form-control kt-input" placeholder="" name="ms_saldo" required value="<?php
                                    if (validation_errors()) {echo set_value('ms_saldo');}else{echo isset($request) ? htmlentities($request['ms_saldo'], ENT_QUOTES) : '';} ?>">
                                    <small>Saldo harus lebih dari 0</small>

                                </div>


                            </div>



                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions m-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" onclick="return confirm('Anda yakin?')" class="btn btn-success pl-5 pr-5">TOP UP</button>
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