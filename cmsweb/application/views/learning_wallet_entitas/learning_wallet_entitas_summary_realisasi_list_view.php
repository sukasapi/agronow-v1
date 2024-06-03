<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);

$id_entitas = '34';
$tahun = '2024';
$id_member = '8684';

$arrData = $this->learning_wallet_model->get_detail_realisasi($id_entitas,$id_member,$tahun);

$ui = '<table class="table table-sm table-bordered">'.$ui.'</table>';
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
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
				
					<pre><?print_r($arrData);?></pre>


                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">
                            <div class="col-xl-12">
                                <form class="kt-form">
                                    <div class="row">
                                        <div class="col-12 col-lg-5">
                                            <label>Group</label>
                                            <?php
                                            $selected_value = $group_id!=NULL ? $group_id : '';

                                            $attr = 'class="form-control" id="group_id"';
                                            echo form_dropdown('group_id', $form_opt_group, $selected_value, $attr);

                                            ?>
                                        </div>

                                        <div class="col-12 col-lg-5">
                                            <label>Tahun</label>
                                            <?php
                                            $selected_value = $tahun_terpilih!=NULL ? $tahun_terpilih : '';

                                            $attr = 'class="form-control" id="tahun" ';
                                            echo form_dropdown('tahun', $form_opt_tahun, $selected_value, $attr);

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
			
<pre>
sumber data pelatihan: classroom agronow
opsi classroom agronow:
- dikelola internal
  > real peserta diambil dari list peserta agronow
- dikelola external (agrowallet)
  > real peserta diambil dari mana?
    - lms external?
	- gform?
	- excel?
	- webinar?
- dikelola external (khusus non ptpn)
  > bisa diabaikan
  
>> sebelum publish ke entitas, perlu checkbox ga? sebagai list peserta yg disetujui

*** bug
- peserta batal mesan jadi ga bisa mesen lagi
  >> dipesankan lsg oleh entitas?
  >> batal bisa pesen ulang?

</pre>
			
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
										<th class="text-center">Kode</th>
                                        <th class="text-center">Nama Pelatihan</th>
                                        <th class="text-center">Tanggal Mulai</th>
										<th class="text-center">Tanggal Selesai</th>
										<th class="text-center">Jumlah Pengajuan</th>
										<th class="text-center">Status Penyelenggaraan</th>
										<th class="text-center">Aksi</th>
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
                    url  : '<?php echo site_url('learning_wallet_entitas/l_ajax_konfig_dashboard_penyelenggaraan').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
					searchPlaceholder: "masukkan nama pelatihan",
                    "infoFiltered": ""
                },
                order: [[ 5, "desc" ]],

                columns: [
                    {data: 'id'},
					{data: 'kode', sortable: false},
                    {data: 'nama_pelatihan', sortable: false},
                    {data: 'tanggal_mulai', sortable: false},
					{data: 'tanggal_selesai', sortable: true},
					{data: 'jumlah_pengajuan', sortable: false},
					{data: 'status', sortable: false},
					{data: 'aksi'},
                ],
                columnDefs: [
                    {
                        targets: 7,
                        orderable: false,
                        render: function(data, type, full, meta) {
							return '<a href="<?php echo site_url('learning_wallet_entitas/dashboard_penyelenggaraan_detail?group_id='.$group_id.'&tahun='.$tahun_terpilih.'&bulan='.$bulan.'&kategori='.$kategori); ?>&idc='+full['id']+'">detail</a>';
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