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
                                Pre Learning
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label>Keterangan</label>
                                <textarea id="content" name="Desc" style="min-height: 400px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('Desc');}else{echo isset($request) ? $request['Desc'] : '';} ?></textarea>

                                <label class="mt-3">Alert</label>
                                <textarea name="Alert" style="min-height: 400px" class="form-control content-tinymce"><?php
                                    if (validation_errors()) {echo set_value('Alert');}else{echo isset($request) ? $request['Alert'] : '';} ?></textarea>


                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('classroom/prelearning/').$classroom['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->

                <!-- START PORTLET MATERI -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Materi
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
							
								<!--
                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('digital_library/l_modal_ajax'); ?>"
                                        type="button" class="btn btn-outline-info btn-sm"
                                        data-toggle="modal" data-target="#LibraryPickerModal">
                                    Tambah dari Digital Library
                                </button>
								-->

                                <!--begin::Modal-->
                                <div class="modal fade" id="LibraryPickerModal" tabindex="-1" role="dialog"
                                     aria-labelledby="vehicleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="vehicleModalLabel">Digital Library</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-center">
                                                    <img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Modal-->

                                <script>
                                    $(document).on('click', '.picker-digital-library', function () {
                                        var id = $(this).data('id');

                                        $.post("<?= site_url('classroom/prelearning_materi_add_digital_library/'.$classroom['cr_id']); ?>", {id: id}, function(result){
                                            var r = $.parseJSON(result);
                                            alert(r.message);
                                        });

                                        $('.modal').modal('hide');
                                        location.reload();
                                    });
                                </script>
                                <!--end::Modal-->


                                <a href="<?php echo site_url('classroom/prelearning_materi_add/'.$classroom['cr_id']); ?>" class="ml-2 btn btn-outline-primary btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah Baru
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm nowraps" id="kt_table-">
                                <thead>
                                <tr>
                                    <th class="text-center">Nama Materi</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Link</th>
									<th class="text-center">Status</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                <?php if (isset($request['Materi'])): ?>
                                    <?php foreach ($request['Materi'] as $k => $v): ?>
                                        <tr id="<?= $k ?>">
                                            <td><?= $v['ContentName'] ?></td>
                                            <td class="text-center"><?= $v['Type'] ?></td>
                                            <td>
                                                <?php if ($v['Type']=='video'): ?>

                                                    <?php
                                                        $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
                                                        $has_match_youtube = preg_match($yt_rx, $v['Media'], $yt_matches);
                                                        if($has_match_youtube):
                                                    ?>
                                                            <a href="<?= $v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                                    <?php else: ?>
                                                            <a href="<?= URL_MEDIA_VIDEO.$v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                                    <?php endif; ?>

                                                <?php elseif ($v['Type']=='document'): ?>
                                                    <a href="<?= URL_MEDIA_DOCUMENT.$v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                                <?php elseif ($v['Type']=='youtube'): ?>
                                                    <a href="<?= $v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                                <?php endif; ?>
                                            </td>
											<td class="text-center"><?= isset($v['Status']) ? $v['Status'] : NULL ?></td>
                                            <td class="text-center" width="100px">
                                                <a href="<?= site_url('classroom/prelearning_materi_edit/').$classroom['cr_id'].'/'.$k ?>" class="btn btn-outline-info btn-icon btn-circle btn-sm <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
												<!--
                                                <a href="<?= site_url('classroom/prelearning_materi_delete/').$classroom['cr_id'].'/'.$k ?>" class="btn btn-outline-danger btn-icon btn-circle btn-sm <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>"  onclick="return confirm('Anda yakin menghapus Modul?')" title="Hapus">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
												-->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>

                            </table>

                            <?php if (isset($request['Materi'])): ?>
							<!--
                                <small>Drag & Drop baris untuk mengubah urutan</small>
                                <br><br>
                                <form class=" <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" action="<?php echo site_url('classroom/prelearning_materi_update_tree/'.$classroom['cr_id']); ?>" method="post" accept-charset="utf-8">
                                    <div class="form-group">
                                        <input type="hidden" id="inputOrder" name="order" />
                                        <button type="submit" id="saveMenu" class="btn btn-brand btn-sm"><i class="fa fa-network-wired"></i> Simpan Struktur</button>
                                    </div>
                                </form>
							-->
                            <?php endif; ?>


                        </div>
                        <!--end: Datatable -->

                    </div>
                </div>
                <!-- END PORTLET MATERI -->
            </div>


        </div>
    </div>

</div>




<link href="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<style>
    .ui-sortable-helper {
        display: table;
    }
</style>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script>

    var arr = $.map($('#sortable').find('tr'), function(el) {
        return $(el).attr('id') ;
    });
    var arrJson = JSON.stringify(arr);
    $('#inputOrder').val(arrJson);
    console.log(arrJson);
    $( "#sortable" ).sortable({
        stop: function(e, ui) {
            arr = $.map($(this).find('tr'), function(el) {
                return $(el).attr('id') ;
            });
            arrJson = JSON.stringify(arr);
            $('#inputOrder').val(arrJson);
            console.log(arrJson);
            /*console.log($.map($(this).find('tr'), function(el) {
                return $(el).index() + ' = ' +$(el).attr('id') ;
            }));*/
        }
    });
    $( "#sortable" ).disableSelection();




</script>




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
        $('.remove-materi').click(function(){
            var el = this;

            // Classroom id
            var classroomId = $(this).data('classroom-id');
            var soalId = $(this).data('soal-id');

            var confirmalert = confirm("Are you sure?");

            if (confirmalert == true) {
                // AJAX Request
                $.ajax({
                    url: 'remove.php',
                    type: 'POST',
                    data: { cr_id:classroomId , crs_id:soalId },
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


<!--
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
-->
