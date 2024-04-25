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

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-xl-12">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Berita
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
                                        <th class="text-center">Dilihat</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($news['data'] as $k => $v): ?>
                                <tr>
                                    <td width="16px"><?= $k+1 ?></td>
                                    <td><?= parseDateReadable($v['content_publish_date']) ?></td>
                                    <td><a href="<?= site_url('news/detail/').$v['content_id'] ?>" target="_blank"><?= $v['content_name'] ?></a> </td>
                                    <td><?= $v['content_hits'] ?></td>
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

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Artikel
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
                                    <th class="text-center">Dilihat</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($article['data'] as $k => $v): ?>
                                    <tr>
                                        <td width="16px"><?= $k+1 ?></td>
                                        <td><?= parseDateReadable($v['content_publish_date']) ?></td>
                                        <td><a href="<?= site_url('article/detail/').$v['content_id'] ?>" target="_blank"><?= $v['content_name'] ?></a> </td>
                                        <td><?= $v['content_hits'] ?></td>
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

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Knowledge Sharing
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
                                    <th class="text-center">Dilihat</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($knowledge_sharing['data'] as $k => $v): ?>
                                    <tr>
                                        <td width="16px"><?= $k+1 ?></td>
                                        <td><?= parseDateReadable($v['content_publish_date']) ?></td>
                                        <td><a href="<?= site_url('knowledge_sharing/detail/').$v['content_id'] ?>" target="_blank"><?= $v['content_name'] ?></a> </td>
                                        <td><?= $v['content_hits'] ?></td>
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




