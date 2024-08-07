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

            <!--<a href="<?php /*echo site_url("member_saldo/create/"); */?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>-->
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
                                        <th class="text-center">Member</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">NIP</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Source</th>
                                        <th class="text-center">Class Room</th>
                                        <th class="text-center">Tanggal</th>
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
                    url  : '<?php echo site_url('member_saldo/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'ms_id'},
                    {data: 'member_name'},
                    {data: 'group_name'},
                    {data: 'member_nip'},
                    {data: 'ms_type'},
                    {data: 'ms_saldo'},
                    {data: 'ms_name'},
                    {data: 'ms_source'},
                    {data: 'cr_name'},
                    {data: 'ms_create_date'},
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['ms_create_time']+'</small>';

                        },
                    },

                    {
                        targets: -2,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'</small>';

                        },
                    },

                    {
                        targets: -4,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'</small>';

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


