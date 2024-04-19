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

            <?php if(has_access('forum.create',FALSE)): ?>
            <a href="<?php echo site_url("forum/create/"); ?>" class="btn btn-brand kt-margin-l-10">
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
                                        <th class="text-center">Author</th>
                                        <th class="text-center">Judul</th>
                                        <th class="text-center">Publish</th>
                                        <th class="text-center">Updated</th>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Comment</th>
                                        <th class="text-center">Klien</th>
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
                    url  : '<?php echo site_url('forum/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'forum_id'},
                    {data: 'member_name'},
                    {data: 'forum_name'},
                    {data: 'forum_create_date'},
                    {data: 'forum_update_date'},
                    {data: 'user_count'},
                    {data: 'comment_count'},
                    {data: 'nama_klien'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['forum_create_time']+'</small>';

                        },
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['forum_update_time']+'</small>';

                        },
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("forum/detail/"); ?>'+full["forum_id"]+'" title="View">'+data+'</a>';

                        },
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return data+'<br><small>'+full['group_name']+'</small>';

                        },
                    },
                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("forum/detail/"); ?>'+full["forum_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
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

