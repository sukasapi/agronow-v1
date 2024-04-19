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

            <?php if(has_access('expertmember.create',FALSE)): ?>
            <a href="<?php echo site_url("expert_member/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>

            <div>

                <button data-backdrop="static"
                        data-remote="<?php echo site_url('member/l_single_modal_ajax'); ?>" type="button"
                        class="btn btn-outline-info btn-sm form-control mt-2" data-toggle="modal"
                        data-target="#classroomMemberPicker">Pilih Member
                </button>

            </div>
            <?php endif; ?>
        </div>

        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">

            </div>
        </div>

    </div>
    <!-- end:: Subheader -->


    <!--begin::Modal-->
    <div class="modal fade" id="classroomMemberPicker" tabindex="-1" role="dialog"
         aria-labelledby="classroomMemberPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="classroomMemberPickerModalLabel">Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        <img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.picker-member', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');


            if (window.confirm("Jadikan member "+name+" sebagai expert?")) {

                $.post("<?= site_url('expert_member/member_add_picker/'); ?>", {id: id}, function(result){
                    var r = $.parseJSON(result);
                    var em_id = r.em_id;

                    if (em_id==0){
                        alert(r.message);
                    }else{
                        $('.modal').modal('hide');
                        window.location = "<?= site_url('expert_member/detail/') ?>"+em_id;
                    }

                });

            } else {

            }




        });
    </script>
    <!-- end modal -->


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
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Keahlian</th>
                                        <th class="text-center">Grup</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Status</th>
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
                    url  : '<?php echo site_url('expert_member/l_ajax').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],

                columns: [
                    {data: 'em_id'},
                    {data: 'em_name'},
                    {data: 'em_concern'},
                    {data: 'group_name'},
                    {data: 'cat_name'},
                    {data: 'em_status'},
                    {data: 'em_create_date'},
                    {data: '', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets: -2,
                        render: function(data, type, full, meta) {
                            return '<small>'+data+'<br>'+full['em_create_time']+'</small>';

                        },
                    },


                    {
                        targets: -3,
                        className: 'text-center',
                        render: function(data, type, full, meta) {
                            var status = {
                                'active': {'title': 'Active', 'state': 'success'},
                                'block': {'title': 'Block', 'state': 'danger'},
                                '': {'title': '-', 'state': 'warning'},
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
                            return '<a href="<?php echo site_url("expert_member/detail/"); ?>'+full["em_id"]+'" class="btn btn-outline-primary btn-icon btn-circle btn-sm" title="View">\n' +
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


