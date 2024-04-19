<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("forum"); ?>" class="btn kt-subheader__btn-primary">
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
                                Forum
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('forum/edit/'.$forum['forum_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("forum.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">Judul</label>
                                <h5><?php echo $forum['forum_name']; ?></h5>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Deskripsi</label>
                                <div>
                                    <?php echo $forum['forum_desc']; ?>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Kategori</label>
                                <div>
                                    <?php echo $forum['cat_name']; ?>
                                </div>
                            </div>


                        </div>
                        

                    </div>
                </div>
                <!-- END PORTLET KONTEN -->


                <!-- START PORTLET KOMENTAR -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Komentar (<?= $comment_count?$comment_count['total']:0 ?>)
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <!--begin: Datatable -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm nowraps  kt_table" id="kt_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Author</th>
                                        <th class="text-center">Komentar</th>
                                        <th class="text-center" width="16px"></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php if($comment): ?>
                                        <?php foreach($comment as $v): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <small>
                                                    <?= parseDateShortReadable($v['fc_create_date']) ?><br><?= parseTimeReadable($v['fc_create_date']) ?>
                                                    </small>
                                                </td>
                                                <td class="align-middle">
                                                    <small>
                                                    <?= $v['member_name'] ?><br>(<?= $v['group_name'] ?>)
                                                    </small>
                                                </td>
                                                <td class="align-middle"><?= $v['fc_desc'] ?></td>
                                                <td class="align-middle text-center">
                                                    <a href="<?= site_url('forum/remove_comment/').$v['fc_id'] ?>" title="Hapus" onclick="return confirm('Are you sure?')" class="text-danger <?= has_access("forum.delete",FALSE)?"":"d-none" ?>"><i class="fa fa-trash-alt"></i> </a>
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
                </div>
                <!-- END PORTLET KOMENTAR -->


                <!-- START PORTLET USER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Anggota (<?= $member_count?$member_count['total']:0 ?>)
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <!--begin: Datatable -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm nowraps kt_table" id="kt_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">NIP</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php if($member): ?>
                                    <?php foreach($member as $v): ?>
                                    <tr>
                                        <td><?= $v['member_name'] ?></td>
                                        <td><?= $v['group_name'] ?></td>
                                        <td><?= $v['member_nip'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                            <!--end: Datatable -->


                        </div>


                    </div>
                </div>
                <!-- END PORTLET USER -->


            </div>

            <div class="col-lg-4">

                <!-- START PORTLET COVER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-lg-4">
                                <center>
                                    <img style="object-fit: cover" src="<?php echo URL_MEDIA_IMAGE.$forum['member_image']; ?>" class="rounded-circle" width="72px" height="72px">
                                </center>
                            </div>
                            <div class="col-lg-8 pt-3">
                                <h6><?= $forum['member_name']; ?></h6>
                                (<?= $forum['group_name']; ?>)
                            </div>
                        </div>


                        <dl class="row mt-5">

                            <dt class="col-5">Tanggal Dibuat</dt>
                            <dd class="col-7">: <?= parseDateReadable($forum['forum_create_date']) ?></dd>

                            <dt class="col-5">Tanggal Update</dt>
                            <dd class="col-7">: <?= parseDateReadable($forum['forum_update_date']) ?></dd>

                        </dl>


                    </div>
                </div>
                <!-- END PORTLET COVER -->


                <!-- START PORTLET DELETE -->
                <div class="kt-portlet <?= has_access("forum.delete",FALSE)?"":"d-none" ?>">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hapus Forum
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
                                    <div class="alert-text"><small>Forum yang dihapus tidak dapat dikembalikan</small></div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>

                                <center>
                                    <a href="<?php echo site_url('forum/delete/'.$forum['forum_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                        <i class="flaticon2-trash"></i> Hapus Forum
                                    </a>
                                </center>

                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET DELETE -->

            </div>


        </div>
    </div>

</div>





<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('.kt_table');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: false,
                stateSave: false,
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],


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



