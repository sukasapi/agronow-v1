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
            <!--<span class="kt-subheader__separator kt-subheader__separator--v"></span>

            <a href="<?php /*echo site_url("ads/create/"); */?>" class="btn btn-brand kt-margin-l-10">
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
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Posisi</th>
                                        <th class="text-center">Sponsor/Link</th>
                                        <th class="text-center">Tgl Mulai</th>
                                        <th class="text-center">Tgl Selesai</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Web</th>
                                        <th class="text-center">Android</th>
                                        <th class="text-center">IOS</th>
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
                    url  : '<?php echo site_url('report_ads/l_ajax').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'ads_id'},
                    {data: 'ads_image'},
                    {data: 'ads_position'},
                    {data: 'ads_sponsor'},
                    {data: 'ads_date_start'},
                    {data: 'ads_date_end'},
                    {data: 'ads_status'},
                    {data: 'total'},
                    {data: 'web'},
                    {data: 'android'},
                    {data: 'ios'},
                ],
                columnDefs: [
                    {
                        targets: 1,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (typeof(data) !== 'undefined'){
                                return '<img src="<?php echo URL_MEDIA_IMAGE; ?>'+data+'" width="96px" />'
                            }else{
                                return '';
                            }

                        },
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return '<b>'+data+'</b>'+'<br><small class="text-muted"><a href="'+full["ads_link"]+'" title="View" target="_blank">'+full["ads_link"]+'</a></small>';

                        },
                    },

                    {
                        targets: -5,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                'active': {'title': 'Active', 'state': 'success'},
                                'expired': {'title': 'Expired', 'state': 'danger'},
                                'draft': {'title': 'Draft', 'state': 'dark'},
                                '': {'title': '-', 'state': 'warning'},
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
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


