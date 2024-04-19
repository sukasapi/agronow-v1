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
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>

            <?php if(has_access('configsaldo.create',FALSE)): ?>
            <a href="<?php echo site_url("member_saldo_setting/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>
            <?php endif; ?>
        </div>

        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">

                <a href="<?= site_url('member_saldo_setting/sync_saldo') ?>" class="btn kt-subheader__btn-primary" aria-haspopup="true" aria-expanded="false">
                    <i class="flaticon2-refresh"></i> Sync
                </a>

                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn kt-subheader__btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-download"></i> Top Up Saldo
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="<?= site_url('member_saldo_setting/topup_group') ?>" class="kt-nav__link <?= has_access("configsaldo.edit",FALSE)?"":"d-none" ?>">
                                    <span class="kt-nav__link-text">Per Group</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="<?= site_url('member_saldo_setting/topup_member') ?>" class="kt-nav__link <?= has_access("configsaldo.edit",FALSE)?"":"d-none" ?>">
                                    <span class="kt-nav__link-text">Per Member</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="<?= site_url('member_saldo_setting/topup_all') ?>" class="kt-nav__link <?= has_access("configsaldo.edit",FALSE)?"":"d-none" ?>">
                                    <span class="kt-nav__link-text">Semua Member</span>
                                </a>
                            </li>


                        </ul>
                    </div>
                </div>


            </div>
        </div>


    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-xl-12">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Start</th>
                                        <th class="text-center">End</th>
                                        <th class="text-center" width="70px"></th>
                                    </tr>
                                </thead>


                            </table>
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
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('member_saldo_setting/l_ajax').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'mss_id'},
                    {data: 'group_name'},
                    {data: 'mss_saldo'},
                    {data: 'mss_start'},
                    {data: 'mss_end'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [

                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("member_saldo_setting/edit/"); ?>'+full["mss_id"]+'" class="btn btn-outline-info btn-icon btn-circle btn-sm <?= has_access("configsaldo.edit",FALSE)?"":"d-none" ?>" title="Edit">\n' +
                                '<i class="la la-pencil"></i>\n' +
                                '</a>\n' +

                                '<a href="<?php echo site_url("member_saldo_setting/delete/"); ?>'+full["mss_id"]+'" class="ml-1 btn btn-outline-danger btn-icon btn-circle btn-sm <?= has_access("configsaldo.delete",FALSE)?"":"d-none" ?>" title="Hapus">\n' +
                                '<i class="la la-trash"></i>\n' +
                                '</a>';
                        },
                    },


                ],
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