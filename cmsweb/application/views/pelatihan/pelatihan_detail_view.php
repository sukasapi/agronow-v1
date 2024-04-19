<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("pelatihan"); ?>" class="btn kt-subheader__btn-primary">
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


            <div class="col-lg-8">

                <!-- START PORTLET KONTEN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Pelatihan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('pelatihan/edit/'.$pelatihan['pelatihan_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("pelatihan.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Pelatihan</label>
                                <h5><?php echo $pelatihan['pelatihan_name']; ?></h5>
                            </div>


                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Tanggal</label>
                                <p><?php echo parseDateReadable($pelatihan['pelatihan_date_start']); ?> - <?php echo parseDateReadable($pelatihan['pelatihan_date_end']); ?></p>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Lokasi</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$pelatihan['pelatihan_location']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Keterangan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$pelatihan['pelatihan_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->


                <!-- START PORTLET PENGALAMAN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Jadwal
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('pelatihan/edit_schedule/'.$pelatihan['pelatihan_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("pelatihan.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="col-12">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table-">
                                <thead>
                                <tr>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Jam Mulai</th>
                                    <th class="text-center">Jam Selesai</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $arr_schedule = json_decode($pelatihan['pelatihan_date_detail'],TRUE);
                                if ($arr_schedule):
                                    foreach ($arr_schedule as $k => $v):
                                        ?>
                                        <tr>
                                            <td><?= parseDateReadable($k) ?></td>
                                            <td class="text-center"><?= parseTimeReadable(explode('-',$v)[0]) ?></td>
                                            <td class="text-center"><?= parseTimeReadable(explode('-',$v)[1]) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PENGALAMAN -->


            </div>


            <div class="col-lg-4">

                <!-- START PORTLET COVER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                QR Code
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <center>
                        <img src="<?php echo URL_MEDIA_IMAGE.$pelatihan['pelatihan_qrcode']; ?>" class="img-fluid" width="180px">
                        <a href="<?php echo URL_MEDIA_IMAGE.$pelatihan['pelatihan_qrcode']; ?>" target="_blank" class="btn btn-info">Download QR Code</a>
                        </center>

                    </div>
                </div>
                <!-- END PORTLET COVER -->

                <!-- START PORTLET DELETE -->
                <div class="kt-portlet <?= has_access("pelatihan.delete",FALSE)?"":"d-none" ?>">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hapus Pelatihan
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="alert alert-outline-info fade show" role="alert">
                                    <div class="alert-icon">
                                        <small><i class="flaticon-warning"></i></small>
                                    </div>
                                    <div class="alert-text"><small>Pelatihan yang dihapus tidak dapat dikembalikan</small></div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>

                                <center>
                                    <a href="<?php echo site_url('pelatihan/delete/'.$pelatihan['pelatihan_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                        <i class="flaticon2-trash"></i> Hapus Pelatihan
                                    </a>
                                </center>

                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET DELETE -->

            </div>

            <!-- Tabel Absensi Peserta -->
            <div class="col-lg-12">
                <!-- START PORTLET PESERTA -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Peserta (<?= $member_count?$member_count['total']:'' ?>)
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('member/l_modal_ajax'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm form-control mt-2" data-toggle="modal"
                                        data-target="#pelatihanMemberPicker">Absensi Manual
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="pelatihanMemberPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="pelatihanMemberPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="pelatihanMemberPickerModalLabel">Member</h5>
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
                        <div class="table-responsive">

                            <table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Grup</th>
                                    <th>Channel</th>
                                    <th>Waktu Absensi</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($member): ?>
                                    <?php $no=0; foreach ($member as $v): ?>
                                        <tr>
                                            <td><?php $no++; echo $no; ?></td>
                                            <td><?= $v['member_name'] ?><br><small>NIP: <?= $v['member_nip'] ?></small></td>
                                            <td><?= $v['group_name'] ?></td>
                                            <td><?= $v['pm_channel'] ?></td>
                                            <td><?= parseDateShortReadable($v['pm_create_date']) ?>, <?= parseTimeReadable($v['pm_create_date']) ?></td>
                                            <td class="text-center">
                                                <a title="Remove" href="<?= site_url('pelatihan/member_remove/'.$pelatihan['pelatihan_id'].'/'.$v['pm_id']); ?>" class="btn text-danger" onclick="return confirm('Anda yakin?')"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!-- END PORTLET PESERTA -->
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
                searchDelay: 500,
                processing: true,
                serverSide: false,
                stateSave: true,

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
    function addSelectedItem(selectedItems)
    {
        // console.log(selectedItems);
        var ids = [];
        $.each(selectedItems, function( index, value ) {
            ids.push(value.member_id);
        });

        $.post("<?= site_url('pelatihan/member_add_picker/'.$pelatihan['pelatihan_id']); ?>", {member_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' member.');
            location.reload();
        });
    }
</script>
