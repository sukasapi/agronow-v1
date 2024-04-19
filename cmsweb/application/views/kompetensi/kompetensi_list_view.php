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

            <?php if(has_access('kompetensi.create',FALSE)): ?>
            <a href="<?php echo site_url("kompetensi/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>
            <?php endif; ?>
        </div>

        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <?php
                    if ($year_selected AND is_numeric($year_selected)):
                ?>
                <a href="<?= site_url('kompetensi/duplicate_by_year/').$year_selected ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-copy"></i>
                    Duplikat
                </a>
                <?php endif; ?>
            </div>
        </div>


    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-lg-2">

                <div class="list-group">
                    <?php
                        foreach ($available_year as $v):
                        if($v['year']):
                    ?>
                    <a href="<?php echo site_url('kompetensi/?year=').$v['year']; ?>" class="list-group-item list-group-item-action <?php echo ($this->input->get('year')==$v['year'])?'active':NULL; ?>"><?= $v['year'] ?></a>
                    <?php
                        endif;
                        endforeach;
                    ?>

                    <a href="<?php echo site_url('kompetensi/?year='); ?>" class="list-group-item list-group-item-action <?php echo (!$this->input->get('year'))?'active':NULL; ?>">N/A</a>

                </div>

            </div>

            <div class="col-lg-10">

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
                                    <th class="text-center">Nama Kompetensi</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Peserta</th>
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
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('kompetensi/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
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
                    {data: 'user_count'},
                    {data: 'cr_status'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+' - '+full['cr_date_end']+'</small>';

                        },
                    },

                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("kompetensi/detail/"); ?>'+full["cr_id"]+'" title="View">'+data+'</a>';

                        },
                    },

                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("kompetensi/detail/"); ?>'+full["cr_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
                                '<i class="la la-eye"></i>\n' +
                                '</a>';

                        },
                    },


                    {
                        targets: -3,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data;

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



