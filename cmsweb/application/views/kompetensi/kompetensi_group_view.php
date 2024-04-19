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
                                Group (<?= isset($group_count['total'])?$group_count['total']:0 ?>)
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('kompetensi/group_sync_member/'.$kompetensi['cr_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md" onclick="return confirm('Semua member yang memiliki group pada list group ini akan ditambahkan sebagai peserta?')">
                                    <i class="flaticon2-refresh"></i> Sync Peserta
                                </a>

                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('group/l_modal_ajax'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm ml-2" data-toggle="modal"
                                        data-target="#kompetensiGroupPicker">Pilih Group
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="kompetensiGroupPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="kompetensiGroupPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="kompetensiGroupPickerModalLabel">Group</h5>
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
                                    <th>Group</th>
                                    <th>Group</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($group): ?>
                                    <?php $no=0; foreach ($group as $v): ?>
                                        <tr>
                                            <td><?php $no++; echo $no; ?></td>
                                            <td><?= $v['group_name'] ?></td>
                                            <td><?= $v['group_name'] ?></td>
                                            <td class="text-center">
                                                <a title="Remove" href="<?= site_url('kompetensi/group_remove/'.$kompetensi['cr_id'].'/'.$v['crm_id']); ?>" class="btn text-danger" onclick="return confirm('Anda yakin? Semua peserta pada Group ini akan ikut terhapus!')"><i class="fa fa-trash"></i></a>
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
            ids.push(value.group_id);
        });

        $.post("<?= site_url('kompetensi/group_add_picker/'.$kompetensi['cr_id']); ?>", {group_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' group.');
            location.reload();
        });
    }
</script>

