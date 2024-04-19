<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("culture"); ?>" class="btn kt-subheader__btn-primary">
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
            $submenu_data = $culture;
            $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open_multipart($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $culture['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Sertifikat
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">

                                <label>Logo *</label>
                                <br>
                                <?php if($request['Logo']): ?>
                                <img src="<?php if (validation_errors()) {echo set_value('Logo');}else{echo isset($request) ? htmlentities(isset($request['Logo'])?URL_MEDIA_IMAGE.$request['Logo']:'', ENT_QUOTES) : '';} ?>" width="400px" height="120px">
                                <?php endif ; ?>
                                <br><br>
                                <input type="file" class="form-control" name="Logo" >
                                <small class="form-text text-muted">
                                    Ukuran 400x120px. Format gambar : png.
                                </small>
                                <br>

                                <label class="mt-4">Text 1 *</label>
                                <input type="text" class="form-control" placeholder="" name="Text1" value="<?php
                                if (validation_errors()) {echo set_value('Text1');}else{echo isset($request) ? htmlentities($request['Text1'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-4">Text 2 *</label>
                                <input type="text" class="form-control" placeholder="" name="Text2" value="<?php
                                if (validation_errors()) {echo set_value('Text2');}else{echo isset($request) ? htmlentities($request['Text2'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-4">Text 3 *</label>
                                <input type="text" class="form-control" placeholder="" name="Text3" value="<?php
                                if (validation_errors()) {echo set_value('Text3');}else{echo isset($request) ? htmlentities($request['Text3'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-4">Text 4 *</label>
                                <input type="text" class="form-control" placeholder="" name="Text4" value="<?php
                                if (validation_errors()) {echo set_value('Text4');}else{echo isset($request) ? htmlentities($request['Text4'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-4">Signature *</label>
                                <br>
                                <?php if($request['Signature']): ?>
                                <img src="<?php if (validation_errors()) {echo set_value('Signature');}else{echo isset($request) ? htmlentities(isset($request['Signature'])?URL_MEDIA_IMAGE.$request['Signature']:'', ENT_QUOTES) : '';} ?>" width="200px" height="80px">
                                <?php endif; ?>
                                <br><br>
                                <input type="file" class="form-control" name="Signature" >
                                <small class="form-text text-muted">
                                    Ukuran 200x80px. Format gambar : png.
                                </small>
                                <br>



                                <label class="mt-4">Penanda Tangan *</label>
                                <input type="text" class="form-control" placeholder="" required name="Text5" value="<?php
                                if (validation_errors()) {echo set_value('Text5');}else{echo isset($request) ? htmlentities($request['Text5'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-4">Jabatan *</label>
                                <input type="text" class="form-control" placeholder="" required name="Text6" value="<?php
                                if (validation_errors()) {echo set_value('Text6');}else{echo isset($request) ? htmlentities($request['Text6'], ENT_QUOTES) : '';} ?>">



                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('culture/certificate/').$culture['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->

            </div>


        </div>
    </div>

</div>




<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "asc" ]],
            });
        };

        return {
            //main function to initiate the module
            init: function() {
                initTable1();
            },
        };

    }();

    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    });
</script>


<script>
    $(document).ready(function(){

        // Delete
        $('.remove-soal').click(function(){
            var el = this;

            // Culture id
            var cultureId = $(this).data('culture-id');
            var soalId = $(this).data('soal-id');

            var confirmalert = confirm("Are you sure?");

            if (confirmalert == true) {
                // AJAX Request
                $.ajax({
                    url: 'remove.php',
                    type: 'POST',
                    data: { cr_id:cultureId , crs_id:soalId },
                    success: function(response){

                        if(response == 1){
                            // Remove row from HTML Table
                            $('#soal-'+soalId).css('background','tomato');
                            $('#soal-'+soalId).fadeOut(400,function(){
                                $(this).remove();
                            });
                        }else{
                            alert('Invalid ID.');
                        }

                    },
                    error:function(){
                        alert("Network error");
                    }
                });
            }

        });

    });
</script>



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

