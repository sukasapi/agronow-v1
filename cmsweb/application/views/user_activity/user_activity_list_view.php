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

            <a href="<?php /*echo site_url("user_activity/create/"); */?>" class="btn btn-brand kt-margin-l-10">
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

            <!-- Navigation -->
            <?php
                $submenu_data = NULL;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="64px">Tanggal</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Kategori Aktivitas</th>
                                        <th class="text-center">Section</th>
                                        <th class="text-center">Catatan</th>
                                        <th class="text-center">Data ID</th>
                                        <th class="text-center">IP Address</th>
                                        <!--<th class="text-center" width="70px"></th>-->
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
                    url  : '<?php echo site_url('user_activity/l_ajax').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'user_activity_create_date'},
                    {data: 'user_name'},
                    {data: 'user_activity_type'},
                    {data: 'section_name'},
                    {data: 'user_activity_desc'},
                    {data: 'data_id'},
                    {data: 'ip_address'},
                    /*{data: '', responsivePriority: -1},*/
                ],
                columnDefs: [


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


