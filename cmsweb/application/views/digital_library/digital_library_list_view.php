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

            <?php if(has_access('digitallibrary.create',FALSE)): ?>
            <a href="<?php echo site_url("digital_library/create/"); ?>" class="btn btn-brand kt-margin-l-10">
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

            <div class="col-xl-12">

                <!-- FILTER -->
                <div class="kt-portlet kt-portlet--head-sm">

                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">

                            <div class="col-xl-12">

                                <form class="kt-form">

                                    <div class="row">

                                        <div class="col-12 col-lg-5">
                                            <label>Klien</label>
                                            <?php
                                            $selected_value = $this->input->get('id_klien')!=NULL ? $this->input->get('id_klien') : '';

                                            $attr = 'class="form-control" id="select2_klien"';
                                            echo form_dropdown('id_klien', $form_opt_klien, $selected_value, $attr);

                                            ?>
                                        </div>


                                        <div class="col-12 col-lg-2">
                                            <label></label>
                                            <button type="submit" class="form-control btn btn-info btn-sm mt-2"><i class="la la-search"></i> Cari</button>
                                        </div>



                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>

                </div>

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
                                        <th class="text-center">Publish</th>
                                        <th class="text-center">Gambar</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Judul</th>
                                        <th class="text-center">Klien</th>
                                        <th class="text-center">Dilihat</th>
                                        <!--<th class="text-center">Document</th>-->
                                        <th class="text-center">Jenis Konten</th>
                                        <th class="text-center">Status</th>
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
                stateSave: true,
                ajax: {
                    url  : '<?php echo site_url('digital_library/l_ajax').'?section_id='.$section_id.'&withcategorytype=yes&'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'content_id'},
                    {data: 'content_create_date'},
                    {data: 'content_publish_date'},
                    {data: 'media_value'},
                    {data: 'cat_name'},
                    {data: 'content_name'},
                    {data: 'nama_klien'},
                    {data: 'content_hits'},
                    /*{data: 'document_value'},*/
                    {data: 'content_type_name'},
                    {data: 'content_status'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['content_create_time']+'</small>';

                        },
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['content_publish_time']+'</small>';

                        },
                    },
                    {
                        targets: 3,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (typeof(data) !== 'undefined' && full['media_type']=='image'){
                                return '<img src="<?php echo URL_MEDIA_IMAGE; ?>'+data+'" width="96px" />'
                            }else{
                                return '';
                            }

                        },
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("digital_library/detail/"); ?>'+full["content_id"]+'" title="View">'+data+'</a>'+
                                '<br><small>Author : '+full['content_author']+'</small>';

                        },
                    },
                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("digital_library/detail/"); ?>'+full["content_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
                                '<i class="la la-eye"></i>\n' +
                                '</a>';

                        },
                    },
                    {
                        targets: -2,
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
                    /*{
                        targets: -4,
                        orderable: false,
                        class: "text-center",
                        render: function(data, type, full, meta) {
                            if (data){
                                return '<a target="_blank" href="'+data+'"><i class="la la-file-pdf-o la-3x"></i></a>'
                            }else{
                                return '';
                            }

                        },
                    },*/


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

            $("#select2_klien").select2({
                placeholder: "Cari klien..",
                allowClear: !0,
                multiple: false,
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