<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi"); ?>" class="btn kt-subheader__btn-primary">
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

            <!-- Navigation -->
            <?php
                $submenu_data = $kompetensi;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Peserta (<?= isset($member_count['total'])?$member_count['total']:0 ?>)
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <!--<a href="<?php /*echo site_url('kompetensi/member_add/'.$kompetensi['cr_id']); */?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah
                                </a>-->

                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('member/l_modal_ajax'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm form-control mt-2" data-toggle="modal"
                                        data-target="#kompetensiMemberPicker">Pilih Member
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="kompetensiMemberPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="kompetensiMemberPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="kompetensiMemberPickerModalLabel">Member</h5>
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
                                        <th>Group</th>
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
                                        <td class="text-center">
                                            <a title="Remove" href="<?= site_url('kompetensi/member_remove/'.$kompetensi['cr_id'].'/'.$v['crm_id']); ?>" class="btn text-danger" onclick="return confirm('Anda yakin?')"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!-- END PORTLET MEMBER -->
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

        $.post("<?= site_url('kompetensi/member_add_picker/'.$kompetensi['cr_id']); ?>", {member_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' member.');
            location.reload();
        });
    }
</script>

