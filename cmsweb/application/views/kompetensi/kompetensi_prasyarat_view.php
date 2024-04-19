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

                <?php
                    if ($kompetensi['cr_komp_max_lv']):
                    for ($lv = 1; $lv <= $kompetensi['cr_komp_max_lv']; $lv++):
                ?>

                <!-- START PORTLET KONTEN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                LEVEL <?= $lv ?>
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">


                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('classroom/l_modal_ajax?is_price=1'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm ml-2 load-picker" data-toggle="modal"
                                        data-target="#kompetensiPrasyaratPicker" data-level="<?= $lv ?>">Pilih Classroom
                                </button>


                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="table-responsive">

                            <table class="table table-sm table-striped table-bordered table-hover nowrap- kt_table" id="kt_table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Kategori</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($prasyarat[$lv]): ?>
                                    <?php $no=0; foreach ($prasyarat[$lv] as $v): ?>
                                        <tr>
                                            <td><?php $no++; echo $no; ?></td>
                                            <td><?= $v['cr_name'] ?></td>
                                            <td><?= $v['cat_name'] ?></td>
                                            <td class="text-center">
                                                <a title="Remove" href="<?= site_url('kompetensi/prasyarat_remove/'.$kompetensi['cr_id'].'/'.$v['crm_id']); ?>" class="btn text-danger" onclick="return confirm('Anda yakin?')"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->

                <?php
                    endfor;
                    endif;
                ?>


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




<!--begin::Modal-->
<div class="modal fade" id="kompetensiPrasyaratPicker" tabindex="-1" role="dialog"
     aria-labelledby="kompetensiPrasyaratPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kompetensiPrasyaratPickerModalLabel">Classroom</h5>
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


<script>
    $( ".load-picker" ).click(function() {
        var url, level = '';
        var level = $(this).data("level");
        window.url = "<?= site_url('kompetensi/prasyarat_add_picker/'.$kompetensi['cr_id'].'/'); ?>"+level;

    });

    function addSelectedItem(selectedItems)
    {
        //console.log(selectedItems);
        var ids = [];
        $.each(selectedItems, function( index, value ) {
            ids.push(value.cr_id);
        });

        $.post(window.url, {cr_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' prasyarat.');
            location.reload();
        });
    }
</script>
