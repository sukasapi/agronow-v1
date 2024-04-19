<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <?php
                $group_id = $this->input->get('group_id', TRUE);
                ?>
                <a href="<?php echo site_url("forum_group_category?group_id=").$group_id; ?>" class="btn kt-subheader__btn-primary">
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
                    echo form_open_multipart($form_action, $attributes);
                    ?>
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">

                        <div class="kt-portlet__body">

                            <div class="form-group m-form__group row">


                                <div class="col-lg-6">
                                    <?php
                                    $group_id = $this->input->get('group_id', TRUE);
                                    ?>

                                    <input type="hidden" name="cat_desc" value="<?= $group_id ?>" />

                                    <label>Nama Kategori:</label>
                                    <input type="text" class="form-control" placeholder="" name="cat_name" required value="<?php
                                    if (validation_errors()) {echo set_value('cat_name');}else{echo isset($request) ? htmlentities($request['cat_name'], ENT_QUOTES) : '';} ?>">

                                    <label class="mt-3">Sub-Kategori dari:</label>
                                    <select class="form-control kt-input" name="cat_parent">
                                        <?php
                                        foreach ($option_tree as $v){
                                            echo $v;
                                        }
                                        ?>
                                    </select>

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

                                <div class="col-lg-6">
                                    <label>Gambar:</label>
                                    <img id="image" src="<?php echo isset($request['media_value'])?URL_MEDIA_IMAGE.$request['media_value']:'' ?>" width="100%">
                                    <br>
                                    <label class="mt-3">Pilih Gambar</label>
                                    <br>
                                    <input type="file" id="files" name="file" accept="image/x-png,image/gif,image/jpeg">
                                    <script>
                                        document.getElementById("files").onchange = function () {
                                            var reader = new FileReader();
                                            reader.onload = function (e) {
                                                // get loaded data and render thumbnail.
                                                document.getElementById("image").src = e.target.result;
                                            };
                                            // read the image file as a data URL.
                                            reader.readAsDataURL(this.files[0]);
                                        };
                                    </script>

                                    <br>
                                    <small>Gunakan gambar berukuran kotak, contoh: 512x512</small>
                                </div>



                            </div>



                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--solid">
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