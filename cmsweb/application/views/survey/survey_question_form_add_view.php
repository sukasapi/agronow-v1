<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("survey"); ?>" class="btn kt-subheader__btn-primary">
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
                $submenu_data = $survey;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open_multipart($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="survey_id" value="<?= $survey['survey_id'] ?>" >

                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Tambah Pertanyaan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <!--<a href="<?php /*echo site_url('survey/member_add/'.$survey['survey_id']); */?>" class="btn btn-outline-light btn-sm  btn-icon-md">
                                    <i class="flaticon2-trash"></i> Hapus
                                </a>-->
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <label>Pertanyaan *</label>
                        <textarea name="Question" style="min-height: 100px" required class="form-control"><?php
                            if (validation_errors()) {echo set_value('Question');}else{echo isset($request['Question']) ? htmlentities($request['Question'], ENT_QUOTES) : '';} ?></textarea>

                        <label class="mt-3">Model *</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Model" required value="multiple-choice" <?php
                                echo set_value('Model', $request['Model'] == 'multiple-choice' ? "" : "");
                                ?>> MULTIPLE CHOICE
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Model" required value="essay" <?php
                                echo set_value('Model', $request['Model'] == 'essay' ? "" : "");
                                ?>> ESSAY
                                <span></span>
                            </label>
                        </div>

                        <script>
                            $(document).ready(function(){
                                $('.choice').hide();
                            });


                            var modelVal = null;
                            $("input[name='Model']").click(function() {
                                modelVal = this.value;
                                if (modelVal=='essay'){
                                    $('#holder-multiple-choice').hide();
                                }else{
                                    $('#holder-multiple-choice').show();
                                }
                            });
                        </script>


                        <div id="holder-multiple-choice" style="display: none;">
                            <label class="mt-3">Tipe Jawaban Multiple Choice</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type" value="text" <?php
                                    echo set_value('Type', $request['Type'] == 'text' ? "" : "");
                                    ?>> TEXT
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type" value="image" <?php
                                    echo set_value('Type', $request['Type'] == 'image' ? "" : "");
                                    ?>> IMAGE
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type" value="text-image" <?php
                                    echo set_value('Type', $request['Type'] == 'text-image' ? "" : "");
                                    ?>> TEXT & IMAGE
                                    <span></span>
                                </label>
                            </div>

                            <script>
                                var typeVal = null;
                                $("input[name='Type']").click(function() {
                                    $('.choice').show();

                                    typeVal = this.value;
                                    if (typeVal=='text'){
                                        $('.input-text').show();
                                        $('.input-holder-image').hide();
                                    }else if(typeVal=='image'){
                                        $('.input-text').hide();
                                        $('.input-holder-image').show();
                                    }else if(typeVal=='text-image'){
                                        $('.input-text').show();
                                        $('.input-holder-image').show();
                                    }
                                });
                            </script>


                            <!-- Begin Pilihan -->
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <label><b>Pilihan Multiple Choice</b></label>
                                </div>
                            </div>


                            <div class="choice">
                                <label class="mt-4">Pilihan 1</label>
                                <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[0]" value="<?php
                                if (validation_errors()) {echo set_value('ChoiceText[0]');}else{echo isset($request['ChoiceText'][0]) ? htmlentities($request['ChoiceText'][0], ENT_QUOTES) : '';} ?>">

                                <div class="row input-holder-image">
                                    <div class="col-9">
                                        <input type="file" class="form-control input-file mt-3" placeholder="" accept="image/*" multiple name="ChoiceImage[0]">
                                    </div>
                                    <div class="col-3">
                                        <img src="<?= isset($request['ChoiceImage'][0])?URL_MEDIA_IMAGE.$request['ChoiceImage'][0]:'' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="choice">
                                <label class="mt-4">Pilihan 2</label>
                                <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[1]" value="<?php
                                if (validation_errors()) {echo set_value('ChoiceText[1]');}else{echo isset($request['ChoiceText'][1]) ? htmlentities($request['ChoiceText'][1], ENT_QUOTES) : '';} ?>">

                                <div class="row input-holder-image">
                                    <div class="col-9">
                                        <input type="file" class="form-control input-file mt-3" placeholder="" accept="image/*" multiple name="ChoiceImage[1]">
                                    </div>
                                    <div class="col-3">
                                        <img src="<?= isset($request['ChoiceImage'][1])?URL_MEDIA_IMAGE.$request['ChoiceImage'][1]:'' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="choice">
                                <label class="mt-4">Pilihan 3</label>
                                <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[2]" value="<?php
                                if (validation_errors()) {echo set_value('ChoiceText[2]');}else{echo isset($request['ChoiceText'][2]) ? htmlentities($request['ChoiceText'][2], ENT_QUOTES) : '';} ?>">

                                <div class="row input-holder-image">
                                    <div class="col-9">
                                        <input type="file" class="form-control input-file mt-3" placeholder="" accept="image/*" multiple name="ChoiceImage[2]">
                                    </div>
                                    <div class="col-3">
                                        <img src="<?= isset($request['ChoiceImage'][2])?URL_MEDIA_IMAGE.$request['ChoiceImage'][2]:'' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="choice">
                                <label class="mt-4">Pilihan 4</label>
                                <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[3]" value="<?php
                                if (validation_errors()) {echo set_value('ChoiceText[3]');}else{echo isset($request['ChoiceText'][3]) ? htmlentities($request['ChoiceText'][3], ENT_QUOTES) : '';} ?>">

                                <div class="row input-holder-image">
                                    <div class="col-9">
                                        <input type="file" class="form-control input-file mt-3" placeholder="" accept="image/*" multiple name="ChoiceImage[3]">
                                    </div>
                                    <div class="col-3">
                                        <img src="<?= isset($request['ChoiceImage'][3])?URL_MEDIA_IMAGE.$request['ChoiceImage'][3]:'' ?>">
                                    </div>
                                </div>
                            </div>


                            <!-- End Pilihan -->

                        </div>


                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('survey/question/').$survey['survey_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
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