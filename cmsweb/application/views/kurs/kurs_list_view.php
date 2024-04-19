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
            <a href="<?php echo site_url("kurs/get_data_cron/"); ?>" class="btn btn-brand kt-margin-l-10">
                Get Data
            </a>
        </div>


    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-lg-12">
                <div class="alert alert-info fade show" role="alert">
                    <div class="alert-icon">
                        <i class="fa fa-info-circle"></i>
                    </div>
                    <div class="alert-text">Data diambil dari bi.go.id secara otomatis</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
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
                                        <th class="text-center">Mata Uang</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Kurs Jual</th>
                                        <th class="text-center">Kurs Beli</th>
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
                processing: false,
                serverSide: false,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('kurs/l_ajax').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'currency'},
                    {data: 'value'},
                    {data: 'sell'},
                    {data: 'buy'},
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


