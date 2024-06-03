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
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open_multipart($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Tambah Materi
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">

                                <label>Judul Materi *</label>
                                <input type="text" class="form-control" placeholder="" name="ContentName" required value="<?php
                                if (validation_errors()) {echo set_value('ContentName');}else{echo isset($request) ? htmlentities($request['ContentName'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Type *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Type" required checked="checked" value="document"> Upload Dokumen (PDF)
                                        <span></span>
                                    </label>

                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Type" required value="youtube"> Link Youtube
                                        <span></span>
                                    </label>

                                    <label class="kt-radio kt-radio--solid d-none">
                                        <input type="radio" name="Type" required value="video"> Upload Video
                                        <span></span>
                                    </label>
                                </div>

                                <div id="media-holder">
                                    <label class="mt-3">Dokumen</label><input type="file" class="form-control" name="file" accept=".pdf" required />
                                    <p><small>* Ukuran file maksimum 50 Mb </small></p>
                                </div>

                                <script>
                                    var typeVal = null;
                                    $("input[name='Type']").click(function() {
                                        typeVal = this.value;
                                        if (typeVal=="document"){
                                            $('#media-holder').html('<label class="mt-3">Dokumen</label><input type="file" class="form-control" name="file" accept=".pdf" required />');
                                        } else if(typeVal=="youtube"){
                                            $('#media-holder').html('<label class="mt-3">Link Youtube</label><input type="url" class="form-control" name="Media" required />');
                                        } else if(typeVal=="video"){
                                            $('#media-holder').html('<label class="mt-3">Video</label><input type="file" class="form-control" name="file" accept="video/mp4,video/x-m4v,video/*" required />');
                                        }
                                    });
                                </script>


                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/module_materi/').$classroom['cr_id'].'/'.$this->uri->segment(4); ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
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



<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        language: 'id',
        todayBtn: 'linked',
        todayHighlight:true,
        autoclose:true,
        orientation: "bottom",
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





