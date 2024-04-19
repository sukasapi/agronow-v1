<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe, div#info img{
        width: 100%;
        height: auto;
    }
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">

	<?php
	if(empty($arrK)) { // data tidak ditemukan
	?>

	<div class="m-2 p-3 alert alert-info">Tidak dapat menampilkan data, PIC entitas tahun <?=$tahun_terpilih?> belum diatur.</div>

	<?php
	} else { // data ditemukan
		$CI =& get_instance();
		
		$cstatus = '';
		
		$arrFilterStatus = array();
		$arrFilterStatus['disetujui'] = 'Disetujui';
		$arrFilterStatus['ditolak'] = 'Ditolak';
		$arrFilterStatus['dibatalkan'] = 'Dibatalkan';
		$arrFilterStatus['dalam_proses'] = 'Dalam Proses';
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		
		$arrStatusPenyelenggaraan = $CI->learning_wallet_model->status_penyelenggaraan();
		$arrTglPengajuan = $CI->learning_wallet_model->getTanggalKonfig('pengajuan');
		$arrTglApproval = $CI->learning_wallet_model->getTanggalKonfig('approval');
		$arrKodeStatus = $CI->learning_wallet_model->getDaftarStatusPengajuan();
		
		$get = $this->input->get();
		if(!empty($get)) {
			$cstatus = $get['cstatus'];
		}
		
		$tmp_tb = date('Y-m');
		$tgl_n = date("d");
		
		$dharga_total = 0;
		$i = 0;
		$ui = '';
		$data = $CI->learning_wallet_model->getDaftarPelatihanDiajukan($tahun_terpilih,$member_id,$cstatus);
		foreach($data as $key => $val) {
			$i++;
			
			$cl_harga = '';
			if($val['kode_status_current']<0) {
				$val['harga'] = 0;
				$cl_harga = 'd-none';
			}
			
			$dharga_total += $val['harga'];
			
			$url_berkas = $CI->learning_wallet_model->get_url_berkas_approval($val['tahun'],$val['berkas'],true);
			
			$group_pengajuan = $CI->group_model->get_group_name($val['id_group']);
			
			$catatan_approval = '';
			if($val['is_final_sdm']=="1" && !empty($val['catatan_approval'])) $catatan_approval = '<div>catatan dari verifikator: '.$val['catatan_approval'].'</div>';
			
			// bisa dibatalkan karyawan?
			$ui_batal = '';
			if($val['tb_request']==$tmp_tb && $tgl_n>=$arrTglPengajuan['mulai'] && $tgl_n<=$arrTglPengajuan['selesai'] && $val['is_final_sdm']=="0") {
				$ui_batal = ', <a class="btn btn-sm btn-info" href="'.base_url('learning/wallet/belanja_detail/'.$tahun_terpilih.'/'.$val['id_program']).'">batalkan</a>';
			}
			
			// status pelatihan
			$status_pelatihan = $arrStatusPenyelenggaraan[$val['status_penyelenggaraan']];
			if(!empty($val['catatan_penyelenggaraan'])) $status_pelatihan .= ', '.$val['catatan_penyelenggaraan'];
			
			$ui .=
				'<tr>
					<td class="text-white lw_bg_hijau" colspan="2">
						#'.$i.' '.$val['nama'].'
					</td>
				 </tr>
				 <tr>
					<td>Tgl Pelatihan</td>
					<td>'.$CI->function_api->date_indo($val['tgl_mulai']).' sd '.$CI->function_api->date_indo($val['tgl_selesai']).'</td>
				 </tr>
				 <tr class="'.$cl_harga.'">
					<td>Harga</td>
					<td>'.$CI->learning_wallet_model->reformatHarga($val['harga']).'</td>
				 </tr>
				 <tr class="'.$cl_harga.'">
					<td>JPL</td>
					<td>'.$val['jumlah_jam'].'</td>
				 </tr>
				 <tr>
					<td>Alasan Pengajuan</td>
					<td>'.$url_berkas.'<br/>'.$val['alasan_request'].'</td>
				 </tr>
				 <tr>
					<td>Tgl Pengajuan</td>
					<td>'.$CI->function_api->date_indo($val['tgl_request'],"datetime").''.$ui_batal.'</td>
				 </tr>
				 <tr>
					<td>Group Pengajuan</td>
					<td>'.$group_pengajuan.'</td>
				 </tr>
				 <tr>
					<td>Status Pengajuan</td>
					<td><span style="color:'.$arrKodeStatus[$val['kode_status_current']]['warna'].'">
							<div>'.$arrKodeStatus[$val['kode_status_current']]['label'].' ('.$CI->function_api->date_indo($val['tgl_update_status']).')</div>
							'.$catatan_approval.'
						</span></td>
				 </tr>
				 <tr>
					<td>Status Pelatihan</td>
					<td>'.$status_pelatihan.'</td>
				 </tr>';
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_my.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Pelatihan Saya Tahun <?=$tahun_terpilih?></h2>
			</div>
		</div>
	</div>
	
	<div class="p-2">
		<div class="mb-2 pl-1 small border border-primary rounded">
			<b>Catatan</b>:<br/>
			<ul class="lw_li_line_height_sm">
				<!--
				<li>Pelatihan yang telah dipesan dapat dibatalkan oleh karyawan maksimal tanggal <?=$arrTglPengajuan['selesai']?> setiap bulannya.</li>
				<li>Peninjauan pemesanan pelatihan dilakukan oleh bagian SDM pada tanggal <?=$arrTglApproval['mulai'].' sd '.$arrTglApproval['selesai']?> setiap bulannya.</li>
				-->
				<li>Pelatihan yang sedang berlangsung dapat dilihat melalui menu Learning Room > Class Room > My Classroom <a href="<?=base_url('learning/class_room/my_classroom')?>">(klik disini)</a></li>
				<li>Pelatihan yang sudah selesai dapat dilihat melalui menu Learning Room > Individual Report <a href="<?=base_url('learning/individual_report')?>">(klik disini)</a></li>
			</ul>
		</div>
		
		<div class="mb-2 p-2 pt-0 border border-success rounded">
			<div class="section-title p-0 mt-1 mb-1">Pencarian</div>
			<div>
				<form action="<?=base_url('learning/wallet/my/'.$tahun_terpilih)?>" id="form" method="get" class="form-horizontal">
					<div class="form-group mb-1">
						<label for="cstatus" class="mb-0">Status Pengajuan</label>
						<select class="form-control" id="cstatus" name="cstatus">
						  <option></option>
							<?php
							foreach($arrFilterStatus as $key => $val) {
								$seld = ($cstatus==$key)? "selected" : "";
								$uiT = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
								echo $uiT;
							}
							?>
						</select>
					</div>
					
					<div class="text-right">
						<button type="submit" id="submitConfirm" class="btn btn-info">Cari</button>
					</div>
				</form>
			</div>
		</div>
		
		<div class="card">
			<div class="card-body m-0 p-0">
				<table class="table table-sm table-bordered">
					<tbody>
						<?=$ui;?>
					</tbody>
					<tfoot>
						<tr class="font-weight-bold">
							<td class="text-right">Total</td>
							<td><?=$CI->learning_wallet_model->reformatHarga($dharga_total)?></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	
	<div class="mb-2">&nbsp;</div>

	<?php
	}
	?>
    </div>
</div>