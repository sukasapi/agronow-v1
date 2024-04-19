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

            <div class="col-lg-3">
                <div class="kt-portlet kt-iconbox kt-iconbox--animate">
                    <div class="kt-portlet__body p-0">
                        <div class="kt-iconbox__body">
                            <div class="kt-iconbox__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                    </g>
                                </svg> </div>
                            <div class="kt-iconbox__desc">
                                <h3 class="kt-iconbox__title">
                                    <?php echo number_format($count_member,0,',','.'); ?>
                                </h3>
                                <div class="kt-iconbox__content">
                                    Total Member
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="kt-portlet kt-iconbox kt-iconbox--animate">
                    <div class="kt-portlet__body p-0">
                        <div class="kt-iconbox__body">
                            <div class="kt-iconbox__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                                        <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                                    </g>
                                </svg>
                            </div>
                            <div class="kt-iconbox__desc">
                                <h3 class="kt-iconbox__title">
                                    <?php echo number_format($count_content_download,0,',','.'); ?>
                                </h3>
                                <div class="kt-iconbox__content">
                                    Total Download
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="kt-portlet kt-iconbox kt-iconbox--animate">
                    <div class="kt-portlet__body p-0">
                        <div class="kt-iconbox__body">
                            <div class="kt-iconbox__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M13.6855025,18.7082217 C15.9113859,17.8189707 18.682885,17.2495635 22,17 C22,16.9325178 22,13.1012863 22,5.50630526 L21.9999762,5.50630526 C21.9999762,5.23017604 21.7761292,5.00632908 21.5,5.00632908 C21.4957817,5.00632908 21.4915635,5.00638247 21.4873465,5.00648922 C18.658231,5.07811173 15.8291155,5.74261533 13,7 C13,7.04449645 13,10.79246 13,18.2438906 L12.9999854,18.2438906 C12.9999854,18.520041 13.2238496,18.7439052 13.5,18.7439052 C13.5635398,18.7439052 13.6264972,18.7317946 13.6855025,18.7082217 Z" fill="#000000"/>
                                        <path d="M10.3144829,18.7082217 C8.08859955,17.8189707 5.31710038,17.2495635 1.99998542,17 C1.99998542,16.9325178 1.99998542,13.1012863 1.99998542,5.50630526 L2.00000925,5.50630526 C2.00000925,5.23017604 2.22385621,5.00632908 2.49998542,5.00632908 C2.50420375,5.00632908 2.5084219,5.00638247 2.51263888,5.00648922 C5.34175439,5.07811173 8.17086991,5.74261533 10.9999854,7 C10.9999854,7.04449645 10.9999854,10.79246 10.9999854,18.2438906 L11,18.2438906 C11,18.520041 10.7761358,18.7439052 10.4999854,18.7439052 C10.4364457,18.7439052 10.3734882,18.7317946 10.3144829,18.7082217 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="kt-iconbox__desc">
                                <h3 class="kt-iconbox__title">
                                    <?php echo number_format($count_content_elearning,0,',','.'); ?>
                                </h3>
                                <div class="kt-iconbox__content">
                                    Materi eLearning
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="kt-portlet kt-iconbox kt-iconbox--animate">
                    <div class="kt-portlet__body p-0">
                        <div class="kt-iconbox__body">
                            <div class="kt-iconbox__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z" fill="#000000"/>
                                        <path d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="kt-iconbox__desc">
                                <h3 class="kt-iconbox__title">
                                    <?php echo $count_ads; ?>
                                </h3>
                                <div class="kt-iconbox__content">
                                    Iklan Aktif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Member Baru
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body p-3">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tgl Gabung</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($new_member as $k => $v): ?>
                                <tr>
                                    <td>
                                        <?php echo $v['member_name']; ?><br>
                                        <small class="text-muted"><?php echo $v['group_name']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo parseDateShortReadable($v['member_create_date']); ?>
                                    </td>
                                </tr>
                                <?php endforeach;; ?>
                                </tbody>

                            </table>
                        </div>
                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->

            </div>

            <div class="col-lg-2">
                <div class="kt-portlet">
                    <div class="kt-portlet__body  p-3 text-center">
                        <h4>
                            <i class="la la-android text-success" style="font-size: 32px;"></i>
                            <?php echo number_format($count_member_android,'0',',','.') ?>
                        </h4>
                        <h6>Member Android</h6>
                        <a href="<?= site_url('dashboard/excel/android') ?>" class="btn btn-outline-brand btn-sm mt-3">Download Excel</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="kt-portlet">
                    <div class="kt-portlet__body  p-3 text-center">
                        <h4>
                            <i class="la la-apple text-brand" style="font-size: 32px;"></i>
                            <?php echo number_format($count_member_ios,'0',',','.') ?>
                        </h4>
                        <h6>Member IOS</h6>
                        <a href="<?= site_url('dashboard/excel/ios') ?>" class="btn btn-outline-brand btn-sm mt-3">Download Excel</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="kt-portlet">
                    <div class="kt-portlet__body  p-3 text-center">
                        <h4>
                            <i class="la la-globe text-primary" style="font-size: 32px;"></i>
                            <?php echo number_format($count_member_web,'0',',','.') ?>
                        </h4>
                        <h6>Member Web</h6>
                        <a href="<?= site_url('dashboard/excel/web') ?>" class="btn btn-outline-brand btn-sm mt-3">Download Excel</a>
                    </div>
                </div>
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
                serverSide: false,
                stateSave: false,
                paging: false,
                sorting:false,
                searching:false,

                language: {
                    "infoFiltered": ""
                },


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
