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
            <!--<span class="kt-subheader__separator kt-subheader__separator--v"></span>

            <a href="<?php /*echo site_url("ads/create/"); */?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>-->
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

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                20 Konten Terbanyak Didownload
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover  nowraps" id="kt_table-">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Judul</th>
                                        <th class="text-center">Jumlah Download</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($content_download as $k => $v): ?>
                                <tr>
                                    <td width="16px"><?= $k+1 ?></td>
                                    <td><?= parseDateReadable($v['content_publish_date']) ?></td>
                                    <td><a href="<?= getSiteUrlBySection($v['section_id']).'detail/'.$v['content_id'] ?>" target="_blank"><?= $v['content_name'] ?></a> </td>
                                    <td class="text-center"><?= $v['total_download'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>


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




