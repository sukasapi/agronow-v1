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
                                        <th class="text-center">Judul</th>
                                        <th class="text-center">Total Pesan</th>
                                        <th class="text-center">Update</th>
                                        <th class="text-center" width="16px"></th>
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
                    url  : '<?php echo site_url('inbox/l_ajax').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'inbox_id'},
                    {data: 'inbox_create_date'},
                    {data: 'sender_name'},
                    {data: 'inbox_title'},
                    {data: 'total_message'},
                    {data: 'inbox_update_date'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['inbox_create_time']+'</small>';

                        },
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return data+'<small><br>('+full['sender_group_name']+')</small>';

                        },
                    },

                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("inbox/detail/"); ?>'+full["inbox_id"]+'#form-reply" title="View">'+data+'</a>';

                        },
                    },
                    {
                        targets: 4,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            return data;

                        },
                    },
                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("inbox/detail/"); ?>'+full["inbox_id"]+'#form-reply" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
                                '<i class="la la-eye"></i>\n' +
                                '</a>';

                        },
                    },
                    {
                        targets: -2,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['inbox_update_time']+'</small>';

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


