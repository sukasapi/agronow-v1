<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
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
            $this->load->view('validation_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open_multipart($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Edit Pertanyaan Feedback Modul
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <!--<a href="<?php /*echo site_url('classroom/member_add/'.$classroom['cr_id']); */?>" class="btn btn-outline-light btn-sm  btn-icon-md">
                                    <i class="flaticon2-trash"></i> Hapus
                                </a>-->
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <label>Pertanyaan *</label>
                        <textarea name="Question" style="min-height: 100px" required class="form-control"><?php
                            if (validation_errors()) {echo set_value('Question');}else{echo isset($request['Question'][$question_id]) ? htmlentities($request['Question'][$question_id], ENT_QUOTES) : '';} ?></textarea>

                        <label class="mt-3">Type *</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Type" required value="pilihan" <?php
                                echo set_value('Type', $request['Type'][$question_id] == 'pilihan' ? "checked" : "");
                                ?>> PILIHAN
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Type" required value="text" <?php
                                echo set_value('Type', $request['Type'][$question_id] == 'text' ? "checked" : "");
                                ?>> TEXT
                                <span></span>
                            </label>
                        </div>



                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/module_feedback/').$classroom['cr_id'].'/'.$module_id; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET QUESTION -->

                <?php echo form_close(); ?>

            </div>


        </div>
    </div>

</div>





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