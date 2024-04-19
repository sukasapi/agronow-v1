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

            <?php if(has_access('kompetensisoal.create',FALSE)): ?>
            <a href="<?php echo site_url("kompetensi_soal/create/"); ?>" class="btn btn-brand kt-margin-l-10">
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

            <!-- FILTER -->
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--head-sm">


                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">

                            <div class="col-xl-12">

                                <form class="kt-form">

                                    <div class="row">


                                        <div class="col-12 col-lg-10">
                                            <label>Kategori</label>
                                            <?php
                                            $selected_value = $this->input->get('category_ids')!=NULL ? $this->input->get('category_ids') : '';

                                            $attr = 'class="form-control" id="select2_category" multiple="multiple"';
                                            echo form_dropdown('category_ids[]', $form_opt_category, $selected_value, $attr);

                                            ?>
                                        </div>


                                        <div class="col-12 col-lg-2">
                                            <label></label>
                                            <button type="submit" class="form-control btn btn-info btn-sm mt-2"><i class="la la-filter"></i> Filter</button>
                                        </div>



                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-12">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Question / Answer</th>
                                        <th class="text-center" width="48px">Level</th>
                                        <th class="text-center" width="48px">Durasi</th>
                                        <th class="text-center" width="64px">Status</th>
                                        <th class="text-center" width="64px"></th>
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


<style type="text/css">
    table img{
        max-width: 300px !important;
        height: auto;
    }
</style>

<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
                // scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('kompetensi_soal/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'crs_id'},
                    {data: 'cat_name'},
                    {data: 'crs_question'},
                    {data: 'crs_level'},
                    {data: 'crs_durasi_detik'},
                    {data: 'crs_status'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [

                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("kompetensi_soal/edit/"); ?>'+full["crs_id"]+'" class="btn btn-outline-info btn-icon btn-circle btn-sm <?= has_access("kompetensisoal.edit",FALSE)?"":"d-none" ?>" title="Edit">\n' +
                                '<i class="la la-pencil"></i>\n' +
                                '</a>\n' +

                                '<a href="<?php echo site_url("kompetensi_soal/delete/"); ?>'+full["crs_id"]+'" class="ml-1 btn btn-outline-danger btn-icon btn-circle btn-sm <?= has_access("kompetensisoal.delete",FALSE)?"":"d-none" ?>" title="Hapus">\n' +
                                '<i class="la la-trash"></i>\n' +
                                '</a>';
                        },
                    },

                    {
                        targets: -2,
                        className:'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                'publish': {'title': 'Publish', 'state': 'success'},
                                'pending': {'title': 'Pending', 'state': 'warning'},
                                'draft': {'title': 'Draft', 'state': 'dark'},
                                '': {'title': '-', 'state': 'warning'},
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
                        },
                    },

                    {
                        targets: -3,
                        className:'text-center',
                    },

                    {
                        targets: -4,
                        className:'text-center',
                    },


                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'</small>';

                        },
                    },

                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return '<b>'+data+
                                '</b><br><br><table class="table table-bordered table-sm">'+
                                '<tr>'+
                                '<td class="bg-success text-center text-light" width="25px">a</td>'+
                                '<td width="50%">'+full['crs_right']+'</td>'+
                                '<td class="bg-secondary text-center" width="25px">c</td>'+
                                '<td width="50%">'+full['crs_answer2']+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                '<td class="bg-secondary text-center" width="25px">b</td>'+
                                '<td width="50%">'+full['crs_answer1']+'</td>'+
                                '<td class="bg-secondary text-center" width="25px">d</td>'+
                                '<td width="50%">'+full['crs_answer3']+'</td>'+
                                '</tr>'+
                                '</table><br>';
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


<!--SELECT 2-->
<script>
    var Select2 = {
        init: function() {

            $("#select2_category").select2({
                placeholder: "Cari kategori..",
                allowClear: !0,
                multiple: true,
                ajax: {
                    url: "<?php echo site_url('kompetensi_category/ajax_search'); ?>",
                    dataType: "json",
                    delay: 50,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: 0
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0
            }).on('select2:unselecting', function() {
                $(this).data('unselecting', true);
            }).on('select2:opening', function(e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });


        }
    };
    jQuery(document).ready(function() {
        Select2.init()
    });
</script>
