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
		
		$strError = '';
		$enable_simpan_ui = true;
		$status_verifikator = '';
		if($this->session->userdata('member_nip')==$arrK['verifikator_sdm']) {
			$status_verifikator = 'SDM';
		}
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		
		$arrBulan = $CI->function_api->arrMonths('id');
		
		$arrTglApproval = $CI->learning_wallet_model->getTanggalKonfig('approval');
		
		$tahun_aju = date('Y');
		$bulan_aju = date('m');
		$tmp_tb = $tahun_aju.'-'.$bulan_aju;
		$tgl_n = date("d");
		
		$tb_aju = $arrBulan[$bulan_aju-1].' '.$tahun_aju;
		
		if($arrK['total_dana_pengembangan']<=0) {
			$strError .= "<li>Anggaran dana pengembangan tahun ".$tahun_terpilih." belum diatur. Klik tombol <b>Kelola Dana Pengembangan</b> untuk mengatur dana pengembangan karyawan.</li>";
		}
		
		// approval dibuka?
		if($tgl_n>=$arrTglApproval['mulai'] && $tgl_n<=$arrTglApproval['selesai']) {
			$enable_simpan_ui = true;
		} else {
			$strError .= "<li>Approval Pelatihan dibuka tanggal ".$arrTglApproval['mulai']." sd ".$arrTglApproval['selesai']." setiap bulannya.</li>";
			$enable_simpan_ui = false;
		}
		
		// daftar kelas yg perlu diapprove
		$i = 0;
		$ui = '';
		$sql =
			"select c.id as id_pelatihan, c.nama, c.tgl_mulai, c.tgl_selesai, count(p.id) as jumlah
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c, _member m
			 where p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id='".$group_id."' and p.tahun='".$tahun_terpilih."' and c.status='aktif' and p.status='aktif' and p.is_final_sdm='0'
			 group by c.id
			 order by c.tgl_mulai ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			// $ui .= '<li><a href="'.base_url('/learning/wallet/approval_detail/'.$tahun_terpilih.'/'.$val['id_pelatihan']).'">'.$val['nama'].' ('.$val['jumlah'].' pengajuan)</a></li>';
			$i++;
			$ui .=
				'<tr>
					<td>'.$i.'</td>
					<td>
						'.$val['nama'].'<br/>
						'.$CI->function_api->date_indo($val['tgl_mulai']).' sd '.$CI->function_api->date_indo($val['tgl_selesai']).'
					</td>
					<td>'.$val['jumlah'].' orang</td>
					<td><a href="'.base_url('/learning/wallet/approval_detail/'.$tahun_terpilih.'/'.$val['id_pelatihan']).'">tindaklanjuti</a></td>
				 </tr>';
		}
		
		if(empty($ui)) {
			$ui = 'Tidak ada data yang perlu ditindaklanjuti.';
		} else {
			// $ui = '<ul class="listview link-listview"><li class="divider-title">Daftar pengajuan pelatihan yang perlu ditindaklanjuti:</li>'.$ui.'</ul>';
			$ui =
				'<table class="table table-sm table-bordered">
					<thead>
						<tr class="lw_bg_hijau">
							<th class="text-white" style="width:1%">No</th>
							<th class="text-white">Nama Pelatihan</th>
							<th class="text-white">Jumlah Pengajuan</th>
							<th class="text-white">Aksi</th>
						</tr>
					</thead>
					<tbody>
						'.$ui.'
					</tbody>
				 </table>';
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_approval.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Approval Pelatihan <?=$tahun_terpilih?></h2>
			</div>
		</div>
		
		<?php
		if(!empty($strError)) {
			echo '
				<div class="pt-2 pl-2 pr-2">
					<div class="alert alert-danger">
						<b>Peringatan</b>:<br/>
						<ul>'.$strError.'</ul>
					</div>
				</div>';
		}
		?>
		
		<div class="row mt-2">
			<div class="col-md-12 mb-1">
				<div class="card bg-primary">
					<a href="<?=base_url('learning/wallet/kelola_dana/'.$tahun_terpilih)?>">
					<div class="d-flex p-1 position-relative">
						<div class="align-self-center">
							<img src="<?=PATH_ASSETS.'icon/lw_kelola_dana.png'?>" alt="image" class="imaged w32">
						</div>
						<div class="align-self-center ml-1">
							<p class="mb-0" style="color: white; font-size: larger;"><b>Kelola Dana Pengembangan <?=$tahun_terpilih?></b></p>
						</div>
					</div>
					</a>
				</div>
			</div>
			<div class="col-md-12 mb-1">
				<div class="card bg-primary">
					<a href="<?=base_url('learning/wallet/dashboard_realisasi/'.$tahun_terpilih)?>">
					<div class="d-flex p-1 position-relative">
						<div class="align-self-center">
							<img src="<?=PATH_ASSETS.'icon/lw_realisasi.png'?>" alt="image" class="imaged w32">
						</div>
						<div class="align-self-center ml-1">
							<p class="mb-0" style="color: white; font-size: larger;"><b>Dashboard Realisasi Penyerapan AgroWallet <?=$tahun_terpilih?></b></p>
						</div>
					</div>
					</a>
				</div>
			</div>
			<div class="col-md-12 mb-1">
				<div class="card bg-primary">
					<a href="<?=base_url('learning/wallet/dashboard/'.$tahun_terpilih)?>">
					<div class="d-flex p-1 position-relative">
						<div class="align-self-center">
							<img src="<?=PATH_ASSETS.'icon/lw_penyelenggaraan.png'?>" alt="image" class="imaged w32">
						</div>
						<div class="align-self-center ml-1">
							<p class="mb-0" style="color: white; font-size: larger;"><b>Dashboard Penyelenggaraan <?=$tahun_terpilih?></b></p>
						</div>
					</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="divider pb-2 mt-2 mb-1"></div>
	
	<div class="p-2">
		<h3 class="border-bottom pb-1 mb-1">Approval Pelatihan yang Diajukan Bulan <?=$tb_aju?> (<?=$status_verifikator?>)</h3>
	
		<div class="mb-2 pl-1 small border border-primary rounded">
			<b>Catatan</b>:<br/>
			<ul class="lw_li_line_height_sm">
				<!--<li>Approval Pelatihan dibuka tanggal <?=$arrTglApproval['mulai'].' sd '.$arrTglApproval['selesai']?> setiap bulannya.</li>-->
				<li>Klik tombol <b>tindaklanjuti</b> untuk melakukan persetujuan/penolakan pengajuan pelatihan.</li>
			</ul>
		</div>
	
		<?=$ui?>
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

<script type="text/javascript">
window.onload = function() {
	$('#ss').click(function(){
		$('#act').val('ss');
		$('#dform').submit();
	});
	$('#sf').click(function(){
		var flag = confirm('Anda yakin ingin menyimpan final? Setelah disimpan final, data tidak dapat dikoreksi lagi.');
		if(flag==false) {
			return ;
		}
		$('#act').val('sf');
		$('#dform').submit();
	});
};
</script>