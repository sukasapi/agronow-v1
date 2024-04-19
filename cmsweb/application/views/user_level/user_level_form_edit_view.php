<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("user_level"); ?>" class="btn kt-subheader__btn-primary">
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

                                <input type="hidden" name="user_level_id" value="<?php echo $request['user_level_id']; ?>" />

                                <div class="col-lg-6">
                                    <label>Nama Level:</label>
                                    <input type="text" class="form-control kt-input" placeholder="" name="user_level_name" required value="<?php
                                    if (validation_errors()) {echo set_value('user_level_name');}else{echo isset($request) ? htmlentities($request['user_level_name'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Status:</label>
                                    <div class="kt-radio-inline">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="user_level_status" required checked="" value="0" <?php
                                            echo set_value('user_level_status', ($editable)?$request['user_level_status']:'') == '0' ? "checked" : "checked";
                                            ?>> Draft
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="user_level_status" required value="1" <?php
                                            echo set_value('user_level_status', ($editable)?$request['user_level_status']:'') == '1' ? "checked" : "";
                                            ?>> Active
                                            <span></span>
                                        </label>
                                    </div>

                                </div>


                            </div>

                            <div><hr></div>


                            <div class="form-group row">

                                <div class="col-12">

                                    <div class="alert alert-warning fade show" role="alert">
                                        <div class="alert-icon">
                                            <i class="fa fa-info-circle"></i>
                                        </div>
                                        <div class="alert-text">Pastikan Anda juga memilih Parent Menu yang sesuai agar menu yang Anda pilih muncul di dashboard</div>
                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <label class="mt-3">Akses:</label><br>
                                    <!-- select all boxes -->
                                    <div class="form-check ml-3">
                                    <input type="checkbox" class="form-check-input" name="select-all" id="select-all" />
                                    <label class="form-check-label" for="select-all">
                                        <b>Pilih Semua</b>
                                    </label>
                                    </div>
                                </div>


                                <script>
                                    $('#select-all').click(function(event) {
                                        if(this.checked) {
                                            // Iterate each checkbox
                                            $(':checkbox').each(function() {
                                                this.checked = true;
                                            });
                                        } else {
                                            $(':checkbox').each(function() {
                                                this.checked = false;
                                            });
                                        }
                                    });
                                </script>


                                <!-- START: PARENT MENU -->
                                <?php foreach ($available_access_menu as $k => $v): ?>

                                    <?php if($v['menu_name']=='Parent Menu'): ?>
                                    <div class="col-3 mt-3">
                                        <b><?= $v['menu_name'] ?></b>
                                        <br>

                                        <?php foreach ($access as $i => $j): ?>
                                            <?php if($v['menu_name']==$j['menu_name']): ?>

                                                <div class="form-check ml-3">
                                                    <input class="form-check-input" name="access_ids[]" type="checkbox" value="<?= $j['access_id'] ?>" id="access_id_<?= $j['access_id'] ?>" <?= in_array($j['access_id'],$current_access_ids) ? 'checked':'' ?>>
                                                    <label class="form-check-label" for="access_id_<?= $j['access_id'] ?>">
                                                        <?= $j['access_name'] ?>
                                                    </label>
                                                </div>



                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                    </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                                <!-- END: PARENT MENU -->


                                <?php foreach ($available_access_menu as $k => $v): ?>

                                    <?php if($v['menu_name']!='Parent Menu'): ?>
                                    <div class="col-3 mt-3">
                                        <b><?= $v['menu_name'] ?></b>
                                        <br>

                                        <?php foreach ($access as $i => $j): ?>
                                            <?php if($v['menu_name']==$j['menu_name']): ?>

                                                <div class="form-check ml-3">
                                                    <input class="form-check-input" name="access_ids[]" type="checkbox" value="<?= $j['access_id'] ?>" id="access_id_<?= $j['access_id'] ?>" <?= in_array($j['access_id'],$current_access_ids) ? 'checked':'' ?>>
                                                    <label class="form-check-label" for="access_id_<?= $j['access_id'] ?>">
                                                        <?= $j['access_name'] ?>
                                                    </label>
                                                </div>



                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                    </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>

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