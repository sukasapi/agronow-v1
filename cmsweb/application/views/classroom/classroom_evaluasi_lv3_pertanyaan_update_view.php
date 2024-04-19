<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url('classroom/evaluasi_lv3/'.$classroom['cr_id']); ?>" class="btn kt-subheader__btn-primary">
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
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <?=$judul?> Pertanyaan
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

                        <div class="col-12">
							<label>Pertanyaan *</label>
							<textarea name="pertanyaan" style="min-height: 100px" required class="form-control"><?=$request['pertanyaan']?></textarea>
						</div>

						<div class="col-12">
							<label class="mt-3">Kategori *</label>
							<select name="kategori" class="form-control" <?php if($mode=="edit") echo ' disabled '; ?>>
								<option value="skill" <?=$seld_skill?>>Skill</option>
								<option value="attitude" <?=$seld_attitude?>>Attitude</option>
								<option value="behaviour" <?=$seld_behaviour?>>Behaviour</option>
							</select>
						</div>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/evaluasi_lv3/'.$classroom['cr_id']); ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
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