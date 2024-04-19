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
		
		// tampilan
		$total = 0;
		
		// total level
		$addJS = '';
		$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='total_dana_pengembangan' ";
		$res2 = $CI->db->query($sql2);
		$row2 = $res2->result_array();
		$total_level = $row2[0]['nilai'];
		if(empty($total_level)) {
			$total_level = 0;
		}
		
		// total topup
		$sql2 = "select sum(nominal) as nilai from _learning_wallet_konfig_group where kategori='member_total_topup' and tahun='".$tahun_terpilih."' and id_group='".$group_id."' ";
		$res2 = $CI->db->query($sql2);
		$row2 = $res2->result_array();
		$total_topup = $row2[0]['nilai'];
		if(empty($total_topup)) {
			$total_topup = 0;
		}
		
		$total = $total_level + $total_topup;
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_kelola_dana.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Kelola Dana Pengembangan Tahun <?=$tahun_terpilih?></h2>
			</div>
		</div>
		
		<?php
		if(!empty($strError)) {
			echo '
				<div class="pt-2 pl-2 pr-2">
					<div class="alert alert-danger">
						<b>Tidak dapat memproses data</b>:<br/>
						<ul>'.$strError.'</ul>
					</div>
				</div>';
		}
		?>
		
		<div class="card mt-2 border border-info">
			<table class="table table-sm">
				<tr>
					<td colspan="2">Anggaran Dana Pengembangan <?=$tahun_terpilih?></td>
				</tr>
				<tr>
					<td>Total Per Level</td>
					<td class="text-right"><?=$CI->learning_wallet_model->reformatHarga($total_level)?></td>
				</tr>
				<tr>
					<td>Total Top Up</td>
					<td class="text-right"><?=$CI->learning_wallet_model->reformatHarga($total_topup)?></td>
				</tr>
				<tr>
					<td>Total</td>
					<td class="text-right"><?=$CI->learning_wallet_model->reformatHarga($total)?></td>
				</tr>
			</table>
		</div>
		
		<div class="card mt-2 border border-info">
			<a href="<?=base_url('learning/wallet/kelola_dana_level/'.$tahun_terpilih)?>">
			<div class="d-flex p-1 position-relative">
				<div class="align-self-center">
					<img src="<?=PATH_ASSETS.'icon/lw_dompet.png'?>" alt="image" class="imaged w86">
				</div>
				<div class="align-self-center ml-1">
					<p class="mb-0" style="color: black; font-size: larger;"><b>Konfigurasi Dana Pengembangan per Level Karyawan</b></p>
				</div>
			</div>
			</a>
		</div>
		
		<div class="card mt-2 border border-info">
			<a href="<?=base_url('learning/wallet/kelola_dana_topup/'.$tahun_terpilih)?>">
			<div class="d-flex p-1 position-relative">
				<div class="align-self-center">
					<img src="<?=PATH_ASSETS.'icon/lw_dompet.png'?>" alt="image" class="imaged w86">
				</div>
				<div class="align-self-center ml-1">
					<p class="mb-0" style="color: black; font-size: larger;"><b>Top Up Dana Pengembangan per Karyawan</b></p>
				</div>
			</div>
			</a>
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