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
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Tambah Modul
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">

                                <label>Nama Modul *</label>
                                <input type="text" class="form-control" placeholder="" name="ModuleName" required value="<?php
                                if (validation_errors()) {echo set_value('ModuleName');}else{echo isset($request) ? htmlentities($request['ModuleName'], ENT_QUOTES) : '';} ?>">

                                <label class="mt-3">Link Zoom</label>
                                <input type="text" class="form-control" placeholder="" name="ModuleLinkZoom" value="<?php
                                if (validation_errors()) {echo set_value('ModuleLinkZoom');}else{echo isset($request) ? htmlentities($request['ModuleLinkZoom'], ENT_QUOTES) : '';} ?>">


                                <div class="row">
                                    <div class="col-lg-3">

                                        <label class="mt-3">Tgl Mulai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="ModuleStart" required value="<?php
                                        if (validation_errors()) {echo set_value('ModuleStart');}else{echo isset($request['ModuleStart']) ? htmlentities(date('d/m/Y',strtotime($request['ModuleStart'])), ENT_QUOTES) : NULL;} ?>">


                                    </div>
                                    <div class="col-lg-3">

                                        <label class="mt-3">Tgl Selesai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="ModuleEnd" required value="<?php
                                        if (validation_errors()) {echo set_value('ModuleEnd');}else{echo isset($request['ModuleEnd']) ? htmlentities(date('d/m/Y',strtotime($request['ModuleEnd'])), ENT_QUOTES) : NULL;} ?>">


                                    </div>
                                </div>



                            </div>


                        </div>
                        <div class="row">
                                    <div class="col-lg-12">
                                        <label class="mt-3"> Membutuhkan Assignment Tugas ?</label>
                                        <br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="is_assignment" name="is_assignment" value="ya">
                                                <label class="form-check-label" for="is_assignment">ya</label>
                                            </div>
                                    </div>
                                     <!---- tambah informasi assignment -->
                                     <div class="col-lg-12" id="text-assignment">
                                        <label class="mt-3"> Informasi Modul Assignment</label>
                                        <textarea class="form-control" id="info_assignment" name="infoassignment"></textarea>
                                    </div>
                                    <!---- ENd Tambah informasi assignment -->
                               </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/module/').$classroom['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
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
        if($("#is_assignment").is(":checked")){
                $("#text-assignment").show();
            }else{
                $("#text-assignment").hide();
            }
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
        $("#is_assignment").on('change',function(e){
            e.preventDefault();
            
            if($(this).is(":checked")){
                $("#text-assignment").show();
            }else{
                $("#text-assignment").hide();
            }
        })

    });
</script>





