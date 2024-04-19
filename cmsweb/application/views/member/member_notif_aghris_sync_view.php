<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-12">


                <!-- START PORTLET SYNC AGHRIS RESULT -->
                <?php if ($notif): ?>
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hasil Penambahan Dari Aghris
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>


                    <div class="kt-portlet__body">

                        <?php
                            //print_r($notif);
                        ?>

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Member ID</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($notif as $k => $v): ?>
                                <tr>
                                    <td><?= $v['member_id'] ?></td>
                                    <td><?= $v['member_name'] ?></td>
                                    <td><?= $v['member_nip'] ?></td>
                                    <td><?= $v['status_message'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                        <!--end: Datatable -->


                    </div>

                </div>
                <?php endif; ?>
                <!-- END PORTLET SYNC AGHRIS RESULT -->


                <!-- START PORTLET SYNC AGHRIS RESULT NOT FOUND -->
                <?php if (!$notif): ?>
                    <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                        <div class="kt-portlet__body">

                            <h4 class="text-center text-muted">Hasil Tidak Ditemukan</h4>

                        </div>

                    </div>
                <?php endif; ?>
                <!-- END PORTLET SYNC AGHRIS RESULT NOT FOUND -->

            </div>


        </div>


    </div>


</div>



<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>




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
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "asc" ]],

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






<!--end::Page Resources -->