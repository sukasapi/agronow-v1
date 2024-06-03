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

            <div class="container">
				<div class="row">
					<div class="col-8">
						<?php
						$attributes = array('autocomplete'=>'off','method'=>'post');
						echo form_open($form_action, $attributes);
						?>
						<!-- START PORTLET -->
						<div class="kt-portlet">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Pencarian
									</h3>
								</div>
							</div>

							<div class="kt-portlet__body">
							
								<div class="row">
									<div class="col-12 mb-2">
										<label>Kode</label>
										<input type="text" class="form-control" name="kode" value="<?=$request['kode']?>"/>
									</div>
								</div>
								
								<div class="row">
									<div class="col-6 mb-2">
										<label>Bulan Mulai Pelatihan</label>
										<select class="form-control kt-input" name="bulan">
											<option value="0"></option>
											<?php
											foreach($arrBulan as $key => $val) {
												$seld = ($request['bulan']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
											}
											?>
										</select>
									</div>
									
									<div class="col-6 mb-2">
										<label>Tahun Mulai Pelatihan</label>
										<select class="form-control kt-input" name="tahun">
											<option value="0"></option>
											<?php
											foreach($arrTahun as $key => $val) {
												$seld = ($request['tahun']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$key.'</option>';
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="row">
									<div class="col-12 mb-2">
										<label>Status Penyelenggaraan <span class="text-danger">*</span></label>
										<select class="form-control kt-input" name="sp">
											<option value="0"></option>
											<?php
											foreach($arrSP as $key => $val) {
												$seld = ($request['sp']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
											}
											?>
										</select>
									</div>
								</div>
								
							</div>

							<div class="kt-portlet__foot">
								<div class="kt-form__actions kt-form__actions--solid">
									<div class="row">
										<div class="col-lg-12">
											<button type="submit" class="btn btn-success pl-5 pr-5">Lihat Data</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- END PORTLET -->

						<?php echo form_close(); ?>
					</div>
					<div class="col-4">
						<div class="bg-white rounded border border-info p-2">
							<b>Informasi</b>:<br/>
							<ul>
								<!--
								<li>Karyawan bisa memesan pelatihan tanggal <?=$arrKonfig['pengajuan_mulai'].' sd '.$arrKonfig['pengajuan_selesai']?> setiap bulannya.</li>
								<li>Bagian SDM bisa menyetujui pelatihan tanggal <?=$arrKonfig['approval_mulai'].' sd '.$arrKonfig['approval_selesai']?> setiap bulannya.</li>
								-->
								<li>By default, data yang muncul hanya pelatihan dengan peminat setidaknya satu orang karyawan.</li>
								<li>Untuk mengubah status penyelenggaraan pada pelatihan yg tidak ada pemesannya, gunakan opsi <b>tidak ada peserta</b></li>
								<li>Status Lainnya: dibatalkan oleh karyawan, ditolak verifikator, tidak jadi diselenggarakan.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
        </div>
		
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title clearfix">
						Daftar Data
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="mb-2 clearfix">
					<div class="float-left">
						<div class="border border-info p-3 rounded">
							<i class="fa-solid fa-circle-exclamation"></i>&nbsp;informasi:<br/>
							<ul>
								<li>klik pada header tabel untuk mengurutkan data</li>
							</ul>
						</div>
					</div>
					<div class="float-right d-none">
						<a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="unduh(';')">Unduh CSV (dotcomma)</a>
						<a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="unduh(',')">Unduh CSV (comma)</a>
					</div>
				</div>
				
				<!--begin: Datatable-->
				<table id="dt" class="table table-striped table-bordered" width="100%"></table>
				<!--end: Datatable-->
			</div>
		</div>
		
    </div>
    <!-- end:: Content -->

</div>

<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/exporting.js')?>"></script>
<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/offline-exporting.js')?>"></script>

<script>
var datatable = null;

// searching
// datatable.columns(0).search('100').draw();

function unduh(format) {
	if(format==";") {
		datatable.button(0).trigger();
	} else if(format==",") {
		datatable.button(1).trigger();
	}
}
	
$(document).ready(function(){
	datatable = $('#dt').DataTable({
		dom: 'Btpli',
		searching : true,
		filter: true,
		scrollX: true,
		lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
		order: [[3, 'asc']],
		buttons: [
			{
				extend: 'excelHtml5',
				header: true,
				className: 'mt-1 ml-1 btn btn-success', 
				text: 'Unduh File Excel', 
				filename: 'data_tracking_pelatihan', 
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
		data: [<?=$jsonDT?>],
        columns: [
			{ data: 'no', title: 'No.', type: 'num' },
			{ title: 'Kode/Pelatihan/PIC', render: function ( data, type, row, meta ) { return 'kode:&nbsp;'+row.kode+'<br/>'+row.nama+' ['+row.id+']'+'<br/>PIC:&nbsp;'+row.pic; } },
            // { data: 'nama', title: 'Nama Pelatihan', render: function ( data, type, row, meta ) { return row.nama+' ['+row.id+']'; } },
			{ data: 'tgl_mulai', title: 'Tanggal Mulai' },
			{ data: 'status', title: 'Status' },
			{ title: '&nbsp;', render: function ( data, type, row, meta ) { return '<a href="<?=base_url('learning_wallet/tracking_penyelenggaraan_detail/'.$request['tahun'].'/')?>'+row.id+'">Aksi</a>'; } },
			{ data: 'juml_minimal', title: 'Minimal Peserta', type: 'num' },
			{ data: 'juml_approve', title: 'Status Disetujui', type: 'num' },
			{ data: 'juml_waiting', title: 'Menunggu Persetujuan', type: 'num' },
			{ data: 'juml_disapprove', title: 'Status Lainnya', type: 'num' },
			{ data: 'tercapai', title: 'Kuota Tercapai?', visible: false },
        ]
    });
});
</script>