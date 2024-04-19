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
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Pengirim</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Usulan</th>
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
                    url  : '<?php echo site_url('forum_group_category_suggest/l_ajax'); ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'fs_id'},
                    {data: 'fs_create_date'},
                    {data: 'member_name'},
                    {data: 'group_name'},
                    {data: 'fs_name'},
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['fs_create_time']+'</small>';

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


