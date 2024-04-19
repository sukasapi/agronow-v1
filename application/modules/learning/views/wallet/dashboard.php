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
	} else if(
		$this->session->userdata('member_nip')==$arrK['verifikator_sdm']
	) { // data ditemukan
		$CI =& get_instance();
		
		$info_tambahan_ui = '';
		$cbulan = '';
		$ckat = '';
		$arrMonth = $this->function_api->arrMonths("id");
		array_unshift($arrMonth, "pilih bulan");
		
		$arrKat = array(
			'diselenggarakan'=>'Pelatihan Jadi Diselenggarakan',
			'batal'=>'Pelatihan Batal Diselenggarakan',
			'disetujui'=>'Pengajuan Disetujui',
			'ditolak'=>'Pengajuan Ditolak'
		);
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		
		$ui = '';
		
		$post = $this->input->post();
		if(!empty($post)) {
			$cbulan = (int) $post['cbulan'];
			$ckat = $post['ckat'];
		}
		
		if(empty($ckat)) {
			$ckat = 'diselenggarakan';
		}
		
		if($cbulan>0) {
			$bulan2 = ($cbulan<10)? '0'.$cbulan : $cbulan;
			
			$sql_d1 = '';
			$sql_d2 = '';
			
			if($ckat=="diselenggarakan") {
				$sql_d1 = " and c.status_penyelenggaraan='jalan' ";
				$sql_d2 = " and p.kode_status_current='40' ";
			} else if($ckat=="batal") {
				$sql_d1 = " and c.status_penyelenggaraan='batal' ";
				$sql_d2 = " and p.kode_status_current='-40' ";
			} else if($ckat=="disetujui") {
				$info_tambahan_ui = '<div class="mb-2 alert alert-danger">Penting! Status penyelenggaraan pelatihan di bawah ini belum diputuskan apakah jadi diselenggarakan/tidak.</div>';
				
				$sql_d1 = " and c.status_penyelenggaraan='-' ";
				$sql_d2 = " and p.kode_status_current='40' ";
			} else if($ckat=="ditolak") {
				$sql_d2 = " and p.kode_status_current='-20' ";
			}
			
			// set max length group_concat
			$sql = "SET @@group_concat_max_len = 100000;";
			$res = $this->db->query($sql);
			
			$i = 0;
			$sql =
				"select
					p.id_group, c.nama, c.tgl_mulai, c.tgl_selesai, c.metode, c.keterangan, c.catatan_penyelenggaraan, c.lokasi_offline, c.is_ecolearning,
					group_concat('<li>',m.member_name,' (',m.member_nip,')</li>' order by m.member_name SEPARATOR '') as peserta
				 from _learning_wallet_classroom c, _learning_wallet_pengajuan p, _member m
				 where c.id=p.id_lw_classroom and p.status='aktif' and p.id_member=m.member_id ".$sql_d1." ".$sql_d2." and p.id_group='".$group_id."' and c.tgl_mulai like '".$tahun_terpilih."-".$bulan2."-%'
				 group by c.id
				 order by c.tgl_mulai ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			foreach($row as $key => $val) {
				$i++;
				
				if(!empty($val['keterangan'])) {
					$val['keterangan'] = ', '.$val['keterangan'];
				}
				
				$label_eco = $val['is_ecolearning'];
				if(empty($val['is_ecolearning'])) {
					$label_eco = '-';
				}
				
				$ui .=
					'<tr>
						<td class="text-white lw_bg_hijau" colspan="2">
							#'.$i.' '.$val['nama'].'
						</td>
					 </tr>
					 <tr>
						<td>ecolearning/ecowebinar?</td>
						<td>'.$label_eco.'</td>
					 </tr>
					 <tr>
						<td>Tgl Pelatihan Diselenggarakan</td>
						<td>'.$CI->function_api->date_indo($val['tgl_mulai']).' sd '.$CI->function_api->date_indo($val['tgl_selesai']).'</td>
					 </tr>
					 <tr>
						<td>Metode</td>
						<td>'.$val['metode'].$val['keterangan'].'</td>
					 </tr>
					 <tr>
						<td>Lokasi</td>
						<td>'.$val['lokasi_offline'].'</td>
					 </tr>
					 <tr>
						<td>Peserta</td>
						<td><ol class="m-0 p-0 pl-2">'.$val['peserta'].'</ol></td>
					 </tr>
					 <tr>
						<td>Catatan</td>
						<td>
							'.$val['catatan_penyelenggaraan'].'
						</td>
					 </tr>';
			}
		}
		
		if(empty($ui)) {
			$ui = '<tr><td>Data tidak ditemukan pada bulan terpilih</td></tr>';
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_penyelenggaraan.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Dashboard Penyelenggaraan Pelatihan Tahun <?=$tahun_terpilih?></h2>
			</div>
		</div>
	</div>
	
	<div class="p-2">
		<div class="mb-2 p-2 pt-0 border border-success rounded">
			<div class="section-title p-0 mt-1 mb-1">Pencarian</div>
			<div>
				<form action="<?=base_url('learning/wallet/dashboard/'.$tahun_terpilih)?>" id="form" method="post" class="form-horizontal">
					<div class="form-group mb-1">
						<label for="cbulan" class="mb-0">Bulan Penyelenggaraan Pelatihan</label>
						<select class="form-control" id="cbulan" name="cbulan">
						  <option></option>
							<?php
							foreach($arrMonth as $key => $val) {
								$seld = ($cbulan==$key)? "selected" : "";
								$uiT = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
								echo $uiT;
							}
							?>
						</select>
					</div>
					
					<div class="form-group mb-1">
						<label for="ckat" class="mb-0">Kategori</label>
						<select class="form-control" id="ckat" name="ckat">
						  <option></option>
							<?php
							foreach($arrKat as $key => $val) {
								$seld = ($ckat==$key)? "selected" : "";
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
				<?=$info_tambahan_ui?>
				
				<table class="table table-sm table-bordered">
					<tbody>
						<?=$ui;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<?php
	} else {
		$ui =
			'<div class="pt-2 pl-3 pr-3">
				<div class="alert alert-info">
					Menu ini dikhususkan untuk admin SDM.
				</div>
			 </div>';
		echo $ui;
	}
	?>
    
	<div class="mb-2">&nbsp;</div>
</div>