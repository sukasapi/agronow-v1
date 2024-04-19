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
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>


            <div class="col-lg-9">


                <!-- begin:: Filter -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true" id="portlet-filter">

                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">

                            <div class="col-lg-12">
                                <form class="kt-form" method="get">

                                    <div class="row">

                                        <div class="col-12">
                                            <label>Range Tanggal</label>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <style>
                                                    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover{
                                                        color: #e1e1e1;
                                                    }

                                                    .datepicker tbody tr > td.day.new{
                                                        color: #e1e1e1;
                                                    }
                                                    .datepicker tbody tr > td.day.old{
                                                        color: #e1e1e1;
                                                    }
                                                </style>
                                                <div class="col-6 col-lg-4">
                                                    <input id="StartDate" type="text" name="filter[date-begin]" value="<?= @$_GET['filter']['date-begin'] ?>" class="form-control date-picker" data-date-start-date="<?= date('d/m/Y',strtotime($classroom['cr_date_start'])) ?>" data-date-end-date="<?= date('d/m/Y',strtotime($classroom['cr_date_end'])) ?>" placeholder="Tgl. Mulai" autocomplete="off">
                                                </div>
                                                <div class="col-6 col-lg-4">
                                                    <input id="EndDate" type="text" name="filter[date-end]" value="<?= @$_GET['filter']['date-end'] ?>" class="form-control date-picker" data-date-start-date="<?= date('d/m/Y',strtotime($classroom['cr_date_start'])) ?>" data-date-end-date="<?= date('d/m/Y',strtotime($classroom['cr_date_end'])) ?>" placeholder="Tgl. Berakhir" autocomplete="off">
                                                </div>

                                                <script>
                                                    $("#EndDate").change(function () {
                                                        var startDate = document.getElementById("StartDate").value;
                                                        var endDate = document.getElementById("EndDate").value;

                                                        var d1 = startDate.split('/');
                                                        d1 = new Date(d1.pop(), d1.pop() - 1, d1.pop());

                                                        var d2 = endDate.split('/');
                                                        d2 = new Date(d2.pop(), d2.pop() - 1, d2.pop());

                                                        if (d2 < d1) {
                                                            alert("Tgl akhir harus lebih besar dari tgl awal.");
                                                            document.getElementById("EndDate").value = "";
                                                        }
                                                    });

                                                    $("#StartDate").change(function () {
                                                        var startDate = document.getElementById("StartDate").value;
                                                        var endDate = document.getElementById("EndDate").value;

                                                        var d1 = startDate.split('/');
                                                        d1 = new Date(d1.pop(), d1.pop() - 1, d1.pop());

                                                        var d2 = endDate.split('/');
                                                        d2 = new Date(d2.pop(), d2.pop() - 1, d2.pop());

                                                        if (d2 < d1) {
                                                            alert("Tgl awal harus lebih kecil dari tgl akhir.");
                                                            document.getElementById("StartDate").value = "";
                                                        }
                                                    });
                                                </script>

                                                <div class="col-12 col-lg-2">
                                                    <button type="submit" class="form-control btn btn-outline-brand btn-sm">Filter</button>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <a href="<?= site_url('classroom/attendance/').$classroom['cr_id'] ?>" class="form-control btn btn-outline-secondary btn-sm">Clear</a>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>


                </div>
                <!--end:: Filter-->


                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Absensi
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
                                <!--<a href="<?php /*echo site_url('classroom/member_add/'.$classroom['cr_id']); */?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah
                                </a>-->

                                <a href="<?= site_url('classroom/attendance_scan') ?>" class="btn btn-outline-info btn-sm mr-2">Scan QR</a>

                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('classroom/member_modal_ajax/').$classroom['cr_id']; ?>" type="button"
                                        class="btn btn-outline-info btn-sm" data-toggle="modal"
                                        data-target="#classroomMemberPicker">Absensi Manual
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="classroomMemberPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="classroomMemberPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="classroomMemberPickerModalLabel">Member</h5>
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
                                        <th>Channel</th>
                                        <th>Waktu Absensi</th>
                                        <!--<th></th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($member): ?>
                                <?php $no=0; foreach ($member as $v): ?>
                                    <tr>
                                        <td><?php $no++; echo $no; ?></td>
                                        <td><?= $v['member_name'] ?><br><small>NIP: <?= $v['member_nip'] ?></small></td>
                                        <td><?= $v['group_name'] ?></td>
                                        <td><?= $v['cra_channel'] ?></td>
                                        <td><?= parseDateShortReadable($v['cra_create_date']) ?>, <?= parseTimeReadable($v['cra_create_date']) ?></td>
										<!--
                                        <td class="text-center">
                                            <a title="Remove" href="<?= site_url('classroom/attendance_remove/'.$classroom['cr_id'].'/'.$v['cra_id']); ?>" class="btn text-danger" onclick="return confirm('Anda yakin?')"><i class="fa fa-trash"></i></a>
                                        </td>
										-->
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

        $.post("<?= site_url('classroom/attendance_add_picker/'.$classroom['cr_id']); ?>", {member_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' member.');
            location.reload();
        });
    }
</script>

