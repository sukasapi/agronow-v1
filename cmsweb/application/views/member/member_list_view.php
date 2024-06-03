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

            <?php if(has_access('member.create',FALSE) OR has_access_manage_all_member()): ?>
            <a href="<?php echo site_url("member/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>

            <a href="<?php echo site_url("member/import/"); ?>" class="btn btn-outline-info kt-margin-l-10">
                Import
            </a>
            <?php endif; ?>

            <?php if(has_access('member.searchaghris',FALSE) OR has_access_manage_all_member()): ?>
            <a href="<?php echo site_url("member/aghris_search/"); ?>" class="btn btn-outline-info kt-margin-l-10">
                Cari di Aghris
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


                                        <div class="col-12 col-lg-5">
                                            <label>Group</label>
                                            <?php
                                            $selected_value = $this->input->get('group_ids')!=NULL ? $this->input->get('group_ids') : '';

                                            $attr = 'class="form-control" id="select2_group" multiple="multiple"';
                                            echo form_dropdown('group_ids[]', $form_opt_group, $selected_value, $attr);

                                            ?>
                                        </div>

                                        <div class="col-12 col-lg-5">
                                            <label>Level</label>
                                            <?php
                                            $selected_value = $this->input->get('level_ids')!=NULL ? $this->input->get('level_ids') : '';

                                            $attr = 'class="form-control" id="select2_level" multiple="multiple"';
                                            echo form_dropdown('level_ids[]', $form_opt_level, $selected_value, $attr);

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
						
						<div class="row" id="dload">
							<img class="img-fluid" src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
						</div>
					
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Klien</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Level Karyawan</th>
                                        <th class="text-center">NIP</th>
                                        <th class="text-center">No. HP</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">CEO Notes / BOD Share</th>
                                        <th class="text-center">Expert</th>
                                        <th class="text-center">Poin</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Tgl Daftar</th>
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
	$('#dload').hide();
	
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
				dom: 'Bftlpi',
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('member/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
				aLengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				buttons: [
					{
						extend: 'excelHtml5',
						header: true,
						className: 'mt-1 ml-1 btn btn-success', 
						text: 'Unduh File Excel', 
						filename: 'data_member', 
						exportOptions: { columns: ':visible' },
						action: function(e, dt, node, config) {
							var that = this;
							$('#dload').show();
							
							setTimeout(function() {
								$.fn.DataTable.ext.buttons.excelHtml5.action.call(that, e, dt, node, config);
								$('#dload').hide();
							}, 1000);
						}
					},
				],
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'member_id'},
                    {data: 'member_name'},
                    {data: 'nama_klien'},
                    {data: 'group_name'},
                    {data: 'nama_level_karyawan'},
                    {data: 'member_nip'},
                    {data: 'member_phone'},
                    {data: 'member_status'},
                    {data: 'member_ceo'},
                    {data: 'is_expert'},
                    {data: 'member_poin'},
                    {data: 'member_saldo'},
                    {data: 'member_create_date'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: -2,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['member_create_time']+'</small>';

                        },
                    },

                    {
                        targets: -5,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                '1': {'title': 'Expert', 'state': 'success'},
                                '0': {'title': '', 'state': 'dark'},
                                '': {'title': '', 'state': 'warning'},
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
                        },
                    },

                    {
                        targets: -6,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                '1': {'title': 'CEO Notes', 'state': 'info'},
                                '2': {'title': 'BOD Share', 'state': 'success'},
                                '0': {'title': 'Not Allow', 'state': 'dark'},
                                '': {'title': '', 'state': 'warning'},
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
                        },
                    },

                    {
                        targets: -7,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                'active': {'title': 'Active', 'state': 'success'},
                                'block': {'title': 'Block', 'state': 'danger'},
                                '': {'title': '', 'state': 'warning'},
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
                        },
                    },

                    {
                        targets: -1,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<a href="<?php echo site_url("member/detail/"); ?>'+full["member_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
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
    var urlGroup = "<?= site_url('group/ajax_search') ?>";
    var Select2 = {
        init: function() {

            $("#select2_group").select2({
                placeholder: "Cari group..",
                allowClear: !0,
                multiple: true,
                /*ajax: {
                    url: urlGroup,
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
                },*/
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


            $("#select2_level").select2({
                placeholder: "Cari level..",
                allowClear: !0,
                multiple: true,
                ajax: {
                    url: "<?php echo site_url('member_level/ajax_search'); ?>",
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


