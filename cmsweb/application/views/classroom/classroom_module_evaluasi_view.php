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
                                Evaluasi Modul
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label>Keterangan</label>
                                <textarea id="content" name="Desc" style="min-height: 400px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('Desc');}else{echo isset($request) ? $request['Desc'] : '';} ?></textarea>


                                <label class="mt-4">Waktu Test *</label>
                                <div class="row">
                                    <div class="col-lg-3">

                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="" name="TimeLimitMinute" required value="<?php
                                            if (validation_errors()) {echo set_value('TimeLimit');}else{echo isset($request) ? htmlentities(explode(':',$request['TimeLimit'])[0], ENT_QUOTES) : '';} ?>">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >Menit</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3">

                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="" name="TimeLimitSecond" required value="<?php
                                            if (validation_errors()) {echo set_value('TimeLimit');}else{echo isset($request['TimeLimit'][1]) ? htmlentities(explode(':',$request['TimeLimit'])[1], ENT_QUOTES) : '';} ?>">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >Detik</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-6">

                                        <label class="mt-4">Kesempatan Test *</label>
                                        <?php
                                        if (validation_errors()) {$val = set_value('Attemp');}else{$val = isset($request) ? htmlentities($request['Attemp'], ENT_QUOTES) : NULL;}

                                        $form_opt_attemp = array(
                                            '' => 'Tanpa Batas',
                                            '1' => '1',
                                            // '2' => '2',
                                            // '3' => '3',
                                        );

                                        $attr = 'class="form-control" id="select2_cat"';
                                        echo form_dropdown('Attemp', $form_opt_attemp, $val, $attr);
                                        ?>
                                        <small class="form-text text-muted">
                                            Kesempatan 1 kali berarti test hanya 1 kali, tidak dapat diulang.
                                        </small>

                                    </div>
                                    <div class="col-lg-6">

                                        <label class="mt-4">Tampilan Soal per Halaman *</label>
                                        <?php
                                        if (validation_errors()) {$val = set_value('QuePerPage');}else{$val = isset($request) ? htmlentities($request['QuePerPage'], ENT_QUOTES) : NULL;}

                                        $form_opt_queperpage = array(
                                            '1'     => '1',
                                            '5'     => '5',
                                            '10'    => '10',
                                            '15'    => '15',
                                            '20'    => '20',
                                            '25'    => '25',
                                        );

                                        $attr = 'class="form-control" id="select2_cat"';
                                        echo form_dropdown('QuePerPage', $form_opt_queperpage, $val, $attr);
                                        ?>

                                    </div>
                                </div>


                                <label class="mt-4">Syarat Lulus *</label>
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="" name="ReqPassed" required value="<?php
                                            if (validation_errors()) {echo set_value('ReqPassed');}else{echo isset($request) ? htmlentities($request['ReqPassed'], ENT_QUOTES) : '';} ?>">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >Jawaban Benar</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            Diisi dengan angka.
                                        </small>

                                    </div>
                                </div>


                                <label class="mt-4">Urutan Soal *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Random" required checked="" value="acak" <?php
                                        echo set_value('Random', ($editable)?$request['Random']:'') == 'acak' ? "checked" : "checked";
                                        ?>> Acak
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Random" required value="urut" <?php
                                        echo set_value('Random', ($editable)?$request['Random']:'') == 'urut' ? "checked" : "";
                                        ?>> Urut
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-4">Status *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Status" required checked="" value="active" <?php
                                        echo set_value('Status', ($editable)?$request['Status']:'') == 'active' ? "checked" : "checked";
                                        ?>> Active
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="Status" required value="non-active" <?php
                                        echo set_value('Status', ($editable)?$request['Status']:'') == 'non-active' ? "checked" : "";
                                        ?>> Non-Active
                                        <span></span>
                                    </label>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('classroom/module/').$classroom['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->

                <!-- START PORTLET SOAL -->
                <div class="kt-portlet" id="soal">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Soal
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('classroom_soal/l_modal_ajax'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm form-control mt-2 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" data-toggle="modal"
                                        data-target="#classroomSoalPicker">Pilih Soal
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="classroomSoalPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="classroomSoalPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="classroomSoalPickerModalLabel">Soal</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-center">
                                                    <img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end modal -->

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm nowraps" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center" width="16px">No</th>
                                    <th class="text-center">Question / Answer</th>
                                    <th class="text-center" width="16px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($soal): ?>
                                    <?php $no=0; foreach ($soal as $k => $v): ?>
                                        <tr id="soal-<?php echo $v['crs_id']; ?>">
                                            <td><?php $no ++; echo $no; ?></td>
                                            <td>
                                                <p class="mb-3 mt-3"><b><?php echo $v['crs_question']; ?></b></p>
                                                <table class="table table-bordered table-sm mb-3">
                                                    <tr>
                                                        <td class="bg-success text-center text-light" width="25px">a</td>
                                                        <td width="50%"><?php echo $v['crs_right']; ?></td>
                                                        <td class="bg-secondary text-center" width="25px">c</td>
                                                        <td width="50%"><?php echo $v['crs_answer2']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-secondary text-center" width="25px">b</td>
                                                        <td width="50%"><?php echo $v['crs_answer1']; ?></td>
                                                        <td class="bg-secondary text-center" width="25px">d</td>
                                                        <td width="50%"><?php echo $v['crs_answer3']; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="text-center">
                                                <a title="Remove" href="<?= site_url('classroom/module_evaluasi_remove_soal/'.$classroom['cr_id'].'/'.$this->uri->segment(4).'/'.$v['crs_id']); ?>" class="remove-soal btn text-danger <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" onclick="return confirm('Anda yakin?')" data-classroom-id="<?= $classroom['cr_id']; ?>" data-soal-id="<?= $v['crs_id']; ?>"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                        <!--end: Datatable -->

                    </div>
                </div>
                <!-- END PORTLET SOAL -->
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
                //scrollY: '50vh',
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



<script>
    function addSelectedItem(selectedItems)
    {
        // console.log(selectedItems);
        var ids = [];
        $.each(selectedItems, function( index, value ) {
            ids.push(value.crs_id);
        });

        $.post("<?= site_url('classroom/module_evaluasi_add_soal/'.$classroom['cr_id']).'/'.$this->uri->segment(4); ?>", {crs_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' soal.');
            location.reload();
        });
    }
</script>




