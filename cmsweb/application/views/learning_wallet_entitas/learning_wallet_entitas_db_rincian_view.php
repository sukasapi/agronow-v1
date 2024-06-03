<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);

$ui = "";
$addSql = "";

// if(!empty($group_id)) $addSql .= " and g.group_id='".$group_id."' ";
if(!empty($tahun_terpilih)) $addSql .= " and w.tahun='".$tahun_terpilih."' ";
// if(!empty($cnapeln)) $addSql .= " and c.cr_name like '%".$cnapeln."%' ";

$i = 0;
$sqlF =
	"select w.lokasi_offline, count(w.id) as jumlah
	 from _classroom c, _learning_wallet_classroom w
	 where
		w.id=c.id_lw_classroom and c.qc_member_id>0 and c.cr_status='publish' and w.status='aktif'
		".$addSql."
	 group by w.lokasi_offline
	 order by w.lokasi_offline";
$resF = $this->db->query($sqlF);
$rowF = $resF->result_array();
foreach($rowF as $keyF => $valF) {
	$i++;
	if(empty($valF['lokasi_offline'])) $valF['lokasi_offline'] = '-online-';
	
	$ui .=
		"<tr>
			<td>".$i."</td>
			<td>".$valF['lokasi_offline']."</td>
			<td>".$valF['jumlah']."</td>
		 </tr>";
}
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
			
			<div class="col-xl-12">
			
				<!-- FILTER -->
				<div class="col-xl-12">
					<div class="kt-portlet kt-portlet--head-sm">
						<div class="kt-portlet__body" id="body-filter">
							<div class="row">
								<div class="col-xl-12">
									<form class="kt-form">
										<div class="row">
											<div class="col-12">
												<label>Nama Pelatihan AgroNow</label>
												<input type="text" id="cnapeln" class="form-control" placeholder="" name="cnapeln" value="<?=$cnapeln?>"/>
											</div>
										</div>
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
			</div>
			
			<div class="col-xl-12">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
						<div class="alert alert-info">
							<b>catatan</b>:<br/>
							<ul>
								<li>Sifat data: real-time</li>
								<li>Menu ini hanya menampilkan data realisasi (sudah diverifikasi oleh pengelola kelas)</li>
								<li>Untuk mengunduh semua data, pilih opsi show 'All' terlebih dahulu</li>
							</ul>
						</div>
						
						<b>Ringkasan Lokasi dari Seluruh Pelatihan (Unfiltered)</b>
						<table class="table table-sm table-bordered">
							<tr>
								<td>No</td>
								<td>Nama Lokasi</td>
								<td>Jumlah Pelatihan</td>
							</tr>
							<?=$ui?>
						</table>
						
						<div class="row" id="dload">
							<img class="img-fluid" src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
						</div>
						
						<!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
										<th class="text-center">Entitas</th>
										<th class="text-center">Nama Pelatihan AgroNow</th>
										<th class="text-center">Nama Pelatihan AgroWallet</th>
										<th class="text-center">Lokasi</th>
										<th class="text-center">Tanggal Mulai AgroWallet</th>
										<th class="text-center">Tanggal Selesai AgroWallet</th>
                                        <th class="text-center">NIK Karyawan</th>
                                        <th class="text-center">Nama Karyawan</th>
										<th class="text-center">Nominal</th>
										<th class="text-center">JPL</th>
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
				dom: 'Btlpi',
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('learning_wallet_entitas/l_ajax_db_rincian').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                /* language: {
					searchPlaceholder: "masukkan nama karyawan",
                    "infoFiltered": ""
                }, */
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
						filename: 'data_rincian', 
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
                order: [[ 5, "asc" ]],
                columns: [
                    {data: 'no', sortable: false},
					{data: 'group_name', sortable: false},
					{data: 'nama_pelatihan_agronow', sortable: false},
					{data: 'nama_pelatihan_agrowallet', sortable: false},
					{data: 'lokasi_offline', sortable: false},
					{data: 'tgl_mulai', sortable: false},
					{data: 'tgl_selesai', sortable: false},
                    {data: 'nik_karyawan', sortable: false},
                    {data: 'nama_karyawan', sortable: false},
					{data: 'nominal', sortable: false},
					{data: 'jumlah_jam', sortable: false},
                ],
                /* columnDefs: [
                    {
                        targets: 3,
                        orderable: false,
                        render: function(data, type, full, meta) {
							var durl = "<?=site_url('learning_wallet_entitas/l_modal_ajax_realisasi_karyawan/')?>"+full['id_group']+"/<?=$tahun_terpilih?>/"+full['id_member']+"/0";
							var ui = '<button data-backdrop="static" data-remote="'+durl+'" type="button" class="btn btn-outline-info btn-sm ml-2" data-toggle="modal" data-target="#modal_picker">'+full['nama_karyawan']+'</button>';
							return ui;
                        },
                    },
                ], */
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