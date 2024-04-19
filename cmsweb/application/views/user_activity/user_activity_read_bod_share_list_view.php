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

            <!-- Navigation -->
            <?php
                $submenu_data = NULL;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">

                <!--begin::Portlet-->
                <div class="kt-portlet">

                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover nowraps" id="kt_table_json">
                            </table>
                        </div>

                        <style type="text/css">
                            .dataTables_wrapper .dataTable th, .dataTables_wrapper .dataTable td{
                                vertical-align : top;
                            }
                        </style>

                        <script src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@v1.0.0/dataTables.rowsGroup.js"></script>

                        <script>
                            $(document).ready( function () {
                                var data = <?= $table_json ?>;
                                var table = $('#kt_table_json').DataTable({
                                    scrollY: '50vh',
                                    scrollX: true,
                                    scrollCollapse: true,
                                    responsive: false,
                                    columns: [
                                        {
                                            name: 'first',
                                            title: 'No',
                                        },
                                        {
                                            name: 'second',
                                            title: 'Judul BOD Share',
                                            orderable: 'false'
                                        },
                                        {
                                            title: 'Group',
                                        },
                                        {
                                            title: 'Total Baca',
                                        },
                                    ],
                                    data: data,
                                    rowsGroup: [
                                        'first:name',
                                        'second:name'
                                    ],
                                    pageLength: '50',
                                });
                            } );
                        </script>


                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->


                <!--begin::Portlet-->
                <!--<div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-hover nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Judul BOD Share</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Total Baca</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php /*foreach ($content as $k => $v): */?>
                                <tr>
                                    <td rowspan="<?/*= sizeof($group)+1 */?>" width="16px"><?/*= $k+1 */?></td>
                                    <td rowspan="<?/*= sizeof($group)+1 */?>"><a href="<?/*= site_url('ceo_notes/detail/').$v['content_id'] */?>" target="_blank"><?/*= $v['content_name'] */?></a> <br><small>Penulis : <?/*= $v['content_author'] */?></small> </td>
                                </tr>

                                    <?php /*foreach ($group as $i => $j): */?>
                                    <tr>
                                        <td><?/*= $j['group_name'] */?></td>
                                        <td>
                                            <?php
/*                                                $index = $v['content_id'].$j['group_id'];
                                                if (isset($content_hits[$index])){
                                                    echo $content_hits[$index]['total_view'];
                                                }else{
                                                    echo "0";
                                                }
                                            */?>
                                        </td>
                                    </tr>
                                    <?php /*endforeach; */?>
                                <?php /*endforeach; */?>
                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>-->
                <!--end::Portlet-->

            </div>

        </div>
    </div>
    <!-- end:: Content -->


</div>




