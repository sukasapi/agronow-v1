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



            <?php if(has_access('classroom.create',FALSE)): ?>

            <a href="<?php echo site_url("classroom/create/"); ?>" class="btn btn-brand kt-margin-l-10">

                Tambah

            </a>

            <?php endif; ?>

        </div>





    </div>

    <!-- end:: Subheader -->





    <!-- begin:: Content -->

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

        <div class="row">



            <?php

            $this->load->view('flash_notif_view');

            ?>



            <!-- PESANAN -->

            <div class="col-lg-12">



                <div class="row">

                    <?php

                    $cr_type = $this->input->get('cr_type');

                    ?>

                    <div class="col-3">

                        <a href="<?= site_url('classroom?cr_type=elearning') ?>" class="btn btn-block btn-<?= $cr_type=="elearning"?'info':'secondary' ?> btn-square">e-Learning</a>

                    </div>

                    <div class="col-3">

                        <a href="<?= site_url('classroom?cr_type=inclasstraining') ?>" class="btn btn-block btn-<?= $cr_type=="inclasstraining"?'info':'secondary' ?> btn-square">In Class Training</a>

                    </div>

                </div>



            </div>



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

                                        <th class="text-center">Kategori</th>

                                        <th class="text-center">Nama Pelatihan</th>

                                        <th class="text-center">Tanggal</th>

                                        <th class="text-center" width="50px">Jam</th>

                                        <th class="text-center">Peserta</th>

                                        <th class="text-center">Status</th>

                                        <th class="text-center">Harga</th>

                                        <th class="text-center">PIC</th>

                                        <th class="text-center">Dibuat Oleh</th>

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

                    url  : '<?php echo site_url('classroom/l_ajax?').$_SERVER['QUERY_STRING']; ?>',

                    type : "POST"

                },

                language: {

                    "infoFiltered": ""

                },

                order: [[ 0, "desc" ]],



                columns: [

                    {data: 'cr_id'},

                    {data: 'cat_name'},

                    {data: 'cr_name'},

                    {data: 'cr_date_start'},

                    {data: 'cr_date_detail'},

                    {data: 'user_count'},

                    {data: 'cr_status'},

                    {data: 'cr_price'},
                    ///tambahan PIC
                    {data: 'cr_pic'},

                    {data: 'user_name'},

                    {data: '', responsivePriority: -1},

                ],

                columnDefs: [

                    {

                        targets: 1,

                        render: function(data, type, full, meta) {

                            return '<small>'+data+'</small>';



                        },

                    },

                    {

                        targets: 3,

                        render: function(data, type, full, meta) {

                            return '<small>'+data+' - '+full['cr_date_end']+'</small>';



                        },

                    },

                    {

                        targets: 4,

                        render: function(data, type, full, meta) {

                            return data+' Jam';



                        },

                    },

                    {

                        targets: 2,

                        render: function(data, type, full, meta) {

                            return '<a href="<?php echo site_url("classroom/detail/"); ?>'+full["cr_id"]+'" title="View">'+data+'</a>';



                        },

                    },



                    {

                        targets: -1,

                        title: '',

                        orderable: false,

                        render: function(data, type, full, meta) {

                            return '<a href="<?php echo site_url("classroom/detail/"); ?>'+full["cr_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +

                                '<i class="la la-eye"></i>\n' +

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





