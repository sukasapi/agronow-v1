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
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $survey;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <?php $no=0; foreach ($request as $k => $v): ?>
                <?php $no++; ?>
                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head kt-bg-fill-info">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title  text-light">
                                Pertanyaan Ke <?= $no ?>
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

                        <label>Pertanyaan</label>
                        <textarea name="Question[<?= $k ?>]" style="min-height: 100px" class="form-control"><?php
                            if (validation_errors()) {echo set_value('Question');}else{echo isset($v['Question']) ? htmlentities($v['Question'], ENT_QUOTES) : '';} ?></textarea>

                        <label class="mt-3">Model</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Model[<?= $k ?>]" required value="multiple-choice" <?php
                                echo set_value('Model', $v['Model'] == 'multiple-choice' ? "checked" : "");
                                ?>> MULTIPLE CHOICE
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="Model[<?= $k ?>]" required value="essay" <?php
                                echo set_value('Model', $v['Model'] == 'essay' ? "checked" : "");
                                ?>> ESSAY
                                <span></span>
                            </label>
                        </div>

                        <div id="holder-multiple-choice-<?= $k ?>">
                            <label class="mt-3">Tipe Jawaban Multiple Choice</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type[<?= $k ?>]" required value="text" <?php
                                    echo set_value('Type', $v['Type'] == 'text' ? "checked" : "");
                                    ?>> TEXT
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type[<?= $k ?>]" required value="image" <?php
                                    echo set_value('Type', $v['Type'] == 'image' ? "checked" : "");
                                    ?>> IMAGE
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="Type[<?= $k ?>]" required value="text-image" <?php
                                    echo set_value('Type', $v['Type'] == 'text-image' ? "checked" : "");
                                    ?>> TEXT & IMAGE
                                    <span></span>
                                </label>
                            </div>



                            <!-- Begin Pilihan -->
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <label><b>Pilihan Multiple Choice</b></label>
                                </div>
                            </div>

                            <label class="mt-4">Pilihan 1</label>
                            <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[0]" value="<?php
                            if (validation_errors()) {echo set_value('ChoiceText[0]');}else{echo isset($v['ChoiceText'][0]) ? htmlentities($v['ChoiceText'][0], ENT_QUOTES) : '';} ?>">

                            <div class="row">
                                <div class="col-9">
                                    <input type="file" class="form-control input-file mt-3" placeholder="" name="ChoiceImage[0]">
                                </div>
                                <div class="col-3">
                                    <img src="<?= isset($v['ChoiceImage'][0])?URL_MEDIA_IMAGE.$v['ChoiceImage'][0]:'' ?>">
                                </div>
                            </div>



                            <label class="mt-4">Pilihan 2</label>
                            <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[1]" value="<?php
                            if (validation_errors()) {echo set_value('ChoiceText[1]');}else{echo isset($v['ChoiceText'][1]) ? htmlentities($v['ChoiceText'][1], ENT_QUOTES) : '';} ?>">

                            <div class="row">
                                <div class="col-9">
                                    <input type="file" class="form-control input-file mt-3" placeholder="" name="ChoiceImage[1]">
                                </div>
                                <div class="col-3">
                                    <img src="<?= isset($v['ChoiceImage'][1])?URL_MEDIA_IMAGE.$v['ChoiceImage'][1]:'' ?>">
                                </div>
                            </div>

                            <label class="mt-4">Pilihan 3</label>
                            <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[2]" value="<?php
                            if (validation_errors()) {echo set_value('ChoiceText[2]');}else{echo isset($v['ChoiceText'][2]) ? htmlentities($v['ChoiceText'][2], ENT_QUOTES) : '';} ?>">

                            <div class="row">
                                <div class="col-9">
                                    <input type="file" class="form-control input-file mt-3" placeholder="" name="ChoiceImage[2]">
                                </div>
                                <div class="col-3">
                                    <img src="<?= isset($v['ChoiceImage'][2])?URL_MEDIA_IMAGE.$v['ChoiceImage'][2]:'' ?>">
                                </div>
                            </div>


                            <label class="mt-4">Pilihan 4</label>
                            <input type="text" class="form-control input-text" placeholder="" name="ChoiceText[3]" value="<?php
                            if (validation_errors()) {echo set_value('ChoiceText[3]');}else{echo isset($v['ChoiceText'][3]) ? htmlentities($v['ChoiceText'][3], ENT_QUOTES) : '';} ?>">

                            <div class="row">
                                <div class="col-9">
                                    <input type="file" class="form-control input-file mt-3" placeholder="" name="ChoiceImage[3]">
                                </div>
                                <div class="col-3">
                                    <img src="<?= isset($v['ChoiceImage'][3])?URL_MEDIA_IMAGE.$v['ChoiceImage'][3]:'' ?>">
                                </div>
                            </div>


                            <!-- End Pilihan -->

                        </div>


                    </div>
                </div>
                <!-- END PORTLET QUESTION -->
                <?php endforeach; ?>

            </div>


        </div>
    </div>

</div>

