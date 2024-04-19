<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>


    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <!-- Navigation -->
            <?php
            $submenu_data = $classroom;
            $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Materi
                            </h3>

                        </div>
                        <div class="kt-portlet__head-toolbar">
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
                                                <h5 class="modal-title" id="LibraryModalLabel">Digital Library</h5>
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

                                        $.post("<?= site_url('classroom/module_materi_add_digital_library/'.$classroom['cr_id'].'/'.$module_id); ?>", {id: id}, function(result){
                                            var r = $.parseJSON(result);
                                            alert(r.message);
                                        });

                                        $('.modal').modal('hide');
                                        location.reload();
                                    });
                                </script>
                                <!--end::Modal-->

                                <a href="<?= site_url('classroom/module_materi_add/').$classroom['cr_id'].'/'.$this->uri->segment(4) ?>" class="btn btn-outline-info btn-sm <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Tambah Materi</a>

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
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
                                    <td class="text-center"><?= isset($v['Type'])?$v['Type']:'' ?></td>
                                    <td>
                                        <?php if (isset($v['Type']) && $v['Type']=='video'): ?>

                                            <?php
                                                $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
                                                $has_match_youtube = preg_match($yt_rx, $v['Media']);
                                                if(strpos($v['Media'], "http") === 0):
                                            ?>
                                                <a href="<?= prep_url($v['Media']) ?>" target="_blank"><?= $v['Media'] ?></a>
                                            <?php else: ?>
                                                <a href="<?= URL_MEDIA_VIDEO.$v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                            <?php endif; ?>

                                        <?php elseif (isset($v['Type']) && $v['Type']=='document'): ?>
                                            <a href="<?= URL_MEDIA_DOCUMENT.$v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                        <?php elseif (isset($v['Type']) && $v['Type']=='youtube'): ?>
                                            <a href="<?= $v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                        <?php else: ?>
                                            <a href="<?= URL_MEDIA_DOCUMENT.$v['Media'] ?>" target="_blank"><?= $v['Media'] ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= isset($v['Status']) ? $v['Status'] : NULL ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('classroom/module_materi_edit/').$classroom['cr_id'].'/'.$this->uri->segment(4).'/'.$k ?>" class="btn btn-outline-info btn-icon btn-circle btn-sm <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        <a href="<?= site_url('classroom/module_materi_delete/').$classroom['cr_id'].'/'.$this->uri->segment(4).'/'.$k ?>" class="btn btn-outline-danger btn-icon btn-circle btn-sm d-none"  onclick="return confirm('Anda yakin menghapus Materi?')" title="Hapus">
                                            <i class="fa fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>

                            </table>

                            <?php if (isset($request['Materi'])): ?>
							<div class="<?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <small>Drag & Drop baris untuk mengubah urutan</small>
                            <br><br>
                            <form action="<?php echo site_url('classroom/module_materi_update_tree/'.$classroom['cr_id'].'/'.$module_id); ?>" method="post" accept-charset="utf-8">
                                <div class="form-group">
                                    <input type="hidden" id="inputOrder" name="order" />
                                    <button type="submit" id="saveMenu" class="btn btn-brand btn-sm"><i class="fa fa-network-wired"></i> Simpan Struktur</button>
                                </div>
                            </form>
							</div>
                            <?php endif; ?>

                        </div>
                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>

        </div>
    </div>
    <!-- end:: Content -->


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




