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
                                Progress Peserta (<?= isset($member_count['total'])?$member_count['total']:0 ?>)
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('kompetensi/progress_member_excel/'),$kompetensi['cr_id'] ?>" class="btn btn-outline-info btn-sm mr-2">Export Excel</a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="table-responsive">

                            <table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">
                                <?php $max_lv = $kompetensi['cr_komp_max_lv']; ?>
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">No</th>
                                        <th class="text-center" rowspan="2">Nama</th>
                                        <th class="text-center" rowspan="2">NIP</th>
                                        <th class="text-center" rowspan="2">Group</th>

                                        <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
                                        <th class="text-center" colspan="2">Kompetensi Level <?= $i ?></th>
                                        <?php endfor; ?>

                                        <th class="text-center" rowspan="2">Selsai?</th>
                                        <th class="text-center" rowspan="2">Grade</th>
                                        <th class="text-center" rowspan="2">Grade OK?</th>
                                        <th class="text-center" rowspan="2">Tanggal Submit</th>
                                    </tr>
                                    <tr>
                                        <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
                                        <th>Jumlah Soal</th>
                                        <th>Jawaban Benar</th>
                                        <?php endfor; ?>

                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($member): ?>
                                <?php
                                    $no=0; foreach ($member as $v):
                                    $result = json_decode($v['crm_step'],TRUE);
                                    //print_r($result);
                                ?>
                                    <tr>
                                        <td><?php $no++; echo $no; ?></td>
                                        <td><?= $v['member_name'] ?></td>
                                        <td><?= $v['member_nip'] ?></td>
                                        <td><?= $v['group_name'] ?></td>

                                        <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
                                        <td><?= isset($result['level'][$i]) ? sizeof($result['level'][$i]['pertanyaan']) : '' ?></td>
                                        <td>
                                            <?php
                                            $jawaban_benar = '';
                                            if (isset($result['level'][$i])){
                                                $jawaban_benar = 0;
                                                foreach ($result['level'][$i]['pertanyaan'] as $v){
                                                    if ($v['jawaban'] == '1'){
                                                        $jawaban_benar++;
                                                    }
                                                }
                                            }
                                            echo $jawaban_benar;
                                            ?>
                                        </td>
                                        <?php endfor; ?>

                                        <td><?= isset($result['is_done_all']) ? ($result['is_done_all']==1 ? 'Ya' :'' ) : '' ?></td>
                                        <td><?= isset($result['hasil']) ? ($result['hasil'] ? $result['hasil'] : '') : '' ?></td>
                                        <td><?= isset($result['hasil']) ? ($result['hasil']==$max_lv ? 'Ya' : 'Tidak') : '' ?></td>
                                        <td><?= isset($result['tgl_submit']) ? ($result['tgl_submit'] ? date('d/m/Y H:i',strtotime($result['tgl_submit'])) : '') : '' ?></td>
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

