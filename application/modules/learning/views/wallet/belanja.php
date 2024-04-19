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
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$id_klien = $this->session->userdata('id_klien');
		
		$sql = "select nama from _member_level_karyawan where id='".$id_level_karyawan."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$level_karyawan = $row[0]['nama'];
		
		$arrSekolah = $CI->learning_wallet_model->getDaftarSekolah();
		$arrMetode = $CI->learning_wallet_model->getDaftarKategoriMetode();
		// $arrLevelKaryawan = $CI->learning_wallet_model->getDaftarLevelKaryawan($id_klien);
		
		// filtering data
		$clevelkaryawan = $id_level_karyawan;
		
		$get = $this->input->get();
		if(!empty($get)) {
			$cnama_pelatihan = $get['cnama_pelatihan'];
			$csekolah = $get['csekolah'];
			$cmetode = $get['cmetode'];
			$charga_max = (int) $get['charga_max'];
			$ceco = (int) $get['ceco'];
			$call_lv = (int) $get['call_lv'];
			// $clevelkaryawan = $get['clevelkaryawan'];
		}
		if(!empty($cnama_pelatihan)) $CI->learning_wallet_model->filter_data['nama_pelatihan'] = $cnama_pelatihan;
		if(!empty($csekolah)) $CI->learning_wallet_model->filter_data['id_sekolah'] = $csekolah;
		if(!empty($cmetode)) $CI->learning_wallet_model->filter_data['metode'] = $cmetode;
		if(!empty($charga_max)) $CI->learning_wallet_model->filter_data['harga_max'] = $charga_max;
		if(!empty($ceco)) $CI->learning_wallet_model->filter_data['cari_ecolearning'] = $ceco;
		if(!empty($call_lv)) $CI->learning_wallet_model->filter_data['show_all_lv'] = $call_lv;
		if(!empty($clevelkaryawan)) $CI->learning_wallet_model->filter_data['id_level_karyawan'] = $clevelkaryawan;
		$params = "?cnama_pelatihan=".$cnama_pelatihan."&csekolah=".$csekolah."&cmetode=".$cmetode."&charga_max=".$charga_max."&ceco=".$ceco."&call_lv=".$call_lv;
		
		if(!empty($charga_max)) {
			$addJS .= " reformatNilai('charga_max','".$charga_max."','Rp.&nbsp;'); ";
		}
		
		$_SESSION['lw_belanja_url_params'] = '/'.$page.$params;
		
		$jumlah_pelatihan = $CI->learning_wallet_model->getJumlahPelatihan($tahun_terpilih);
		$perPage = 20;
		$max_page = ceil($jumlah_pelatihan / $perPage);
		
		if(!empty($get) && !empty($cnama_pelatihan) && $page=="1") {
			$detail = date("Y-m-d H:i:s").'::'.$jumlah_pelatihan.'; ';
			$sql = "insert into _search_log set app='learning_wallet', kata_kunci='".$cnama_pelatihan."', jumlah_hit='1', tanggal=now(), detail='".$detail."' on duplicate key update jumlah_hit=jumlah_hit+1, tanggal=now(), detail=concat(detail,'".$detail."') ";
			$this->db->query($sql);
		}
		
		$CI->learning_wallet_model->page = $page;
		$CI->learning_wallet_model->perPage = $perPage;
		$data = $CI->learning_wallet_model->getPelatihan($tahun_terpilih);
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_belanja.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Belanja Pelatihan Tahun <?=$tahun_terpilih?></h2>
			</div>
		</div>
	</div>
	
	<!-- Modal Form -->
	<div class="modal hide fade modalbox" id="searchModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-success">
					<h5 class="modal-title text-white">Pencarian</h5>
					<a class="btn btn-sm bg-white border" href="#" id="btnCari_close">Tutup</a>
				</div>
				<div class="modal-body">
					<form action="<?=base_url('learning/wallet/belanja/'.$tahun_terpilih)?>" id="form" method="get" class="form-horizontal">
						<div class="form-group mb-1">
							<label for="cnama_pelatihan" class="mb-0">Nama Pelatihan</label>
							<input type="text" class="form-control" id="cnama_pelatihan" name="cnama_pelatihan" value="<?=$cnama_pelatihan?>">
						</div>
					
						<div class="form-group mb-1">
							<label for="csekolah" class="mb-0">Tema Pelatihan / School</label>
							<select class="form-control" id="csekolah" name="csekolah">
							  <option></option>
								<?php
								foreach($arrSekolah as $key => $val) {
									$seld = ($csekolah==$key)? "selected" : "";
									$ui = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
									echo $ui;
								}
								?>
							</select>
						</div>
						
						<div class="form-group mb-1">
							<label for="cmetode" class="mb-0">Metode</label>
							<select class="form-control" id="cmetode" name="cmetode">
								<option></option>
								<?php
								foreach($arrMetode as $key => $val) {
									$seld = ($cmetode==$key)? "selected" : "";
									$ui = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
									echo $ui;
								}
								?>
							</select>
						</div>
						
						<div class="form-group mb-1">
							<label for="charga_max" class="mb-0">Harga Maksimal (Rp)&nbsp;<span class="badge badge-primary" id="label_charga_max"></span></label>
							<input type="text" class="form-control format_harga" id="charga_max" name="charga_max" value="<?=$charga_max?>">
							<small class="font-italic">masukkan angka tanpa format, misal <b>1200000</b> untuk satu juta dua ratus ribu rupiah</small>
						</div>
						
						<div class="form-check mb-1">
							<input type="checkbox" class="form-check-input" id="ceco" name="ceco" value="1" <?=($ceco=="1")?"checked":""?>>
							<label class="form-check-label" for="ceco">hanya tampilkan pelatihan hemat biaya saja (EcoLearning/EcoWebinar)</label>
						</div>
						
						<div class="form-check mb-1">
							<input type="checkbox" class="form-check-input" id="call_lv" name="call_lv" value="1" <?=($call_lv=="1")?"checked":""?>>
							<label class="form-check-label" for="call_lv">tampilkan semua pelatihan (abaikan level karyawan)</label>
						</div>
						
						<div class="text-right">
							<button type="submit" id="submitConfirm" class="btn btn-info">Cari</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- * Modal Form -->
		
	<div class="p-2 pt-0">
		<div class="section-title p-0 mt-1 mb-1">
			<h5 class="badge badge-success">Level Anda: <?=$level_karyawan?></h5>
			<button type="button" class="btn btn-success btn-sm" id="btnCari"><ion-icon name="search-circle-outline"></ion-icon> Pencarian</button>
		</div>
		<div class="mb-2">
			<?php 
			if(empty($data)) {
				echo 'data tidak ditemukan';
			}else {
				foreach($data as $key => $val) {
				/*				
				$arrPengajuan = $CI->learning_wallet_model->getDetailPengajuanPelatihan($tahun_terpilih,$member_id,$val['id']);
				$id_pengajuan = $arrPengajuan['id'];
				
				// ui kl pelatihan sudah dipesan
				if($id_pengajuan>0) {
					$dstyle = 'bg-secondary';
					$dharga = 'telah&nbsp;dipesan';
				} else {
					$dstyle = 'bg-info';
					$dharga = $CI->learning_wallet_model->reformatHarga($val['harga']);
				}
				*/
				
				$ikon = $val['berkas']!=""?base_url().'media/image_agrowallet/'.$val['berkas']:base_url().'media/image_agrowallet/default.png';
				
				$dstyle = 'bg-info';
				$dharga = $CI->learning_wallet_model->reformatHarga($val['harga']);
				
				// level karyawan
				$info_level_karyawan = $CI->learning_wallet_model->daftar_level_karyawan2label($id_klien,$val['daftar_level_karyawan']);
				$badgeLvKaryawan = '<span class="badge badge-primary"><ion-icon name="people-circle-outline"></ion-icon>'.$info_level_karyawan.'</span>';
				
				// lokasi
				$badgeLokasi = (empty($val['lokasi_offline']))? '' : '<span class="badge badge-primary"><ion-icon name="navigate-outline"></ion-icon>&nbsp;lokasi:&nbsp;'.$val['lokasi_offline'].'</span>';
			?>
				<div class="card mb-4 border border-info">
					<a href="<?=base_url('learning/wallet/belanja_detail/'.$tahun_terpilih.'/'.$val['id'])?>">
					<div class="d-flex p-1 position-relative">
						<div class="align-self-center">
							<img src="<?=$ikon?>" alt="image" class="imaged w86">
						</div>
						<div class="align-self-center ml-1">
							<p class="mb-0" style="color: black; font-size: larger;"><b><?=$val['nama']?></b></p>
							<div class="mt-0 mb-2">
								<?=$badgeLvKaryawan?>
								<span class="badge badge-primary"><ion-icon name="time-outline"></ion-icon>&nbsp;<?=$val['jumlah_jam']?>&nbsp;JPL</span>
								<span class="badge badge-primary"><ion-icon name="desktop-outline"></ion-icon>&nbsp;<?=$val['metode']?></span>
								<span class="badge badge-primary"><ion-icon name="calendar-outline"></ion-icon>&nbsp;<?=$CI->function_api->date_indo($val['tgl_mulai']).' sd '.$CI->function_api->date_indo($val['tgl_selesai']);?></span>
								<span class="badge badge-primary"><ion-icon name="people-circle-outline"></ion-icon>&nbsp;min peserta: <?=$val['minimal_peserta']?> orang</span>
								<?=$badgeLokasi?>
							</div>
							
							<span style="position:absolute;right:-0.35em;bottom:-1.2em;" class="lw_rounded-top-left lw_rounded-bottom-right <?=$dstyle?> text-right p-1"><?=$dharga?></span>
						</div>
					</div>
					</a>
				</div>
			<?php
				}
				
				$ui = '';
				$prevUI = '<li class="page-item"><a class="page-link" href="'.base_url("learning/wallet/belanja/".$tahun_terpilih."/".($page-1)).''.$params.'">Sebelumnya</a></li>';
				$nextUI = '<li class="page-item"><a class="page-link" href="'.base_url("learning/wallet/belanja/".$tahun_terpilih."/".($page+1)).''.$params.'">Selanjutnya</a></li>';
				
				if($page=="1") {
					$prevUI = '';
				}
				if($page==$max_page) {
					$nextUI = '';
				}
				
				$ui =
					'<div class="pt-2 pb-2">
						<nav>
							<ul class="pagination pagination-rounded">
								'.$prevUI.'
								'.$nextUI.'
							</ul>
						</nav>
					</div>';
				echo $ui;
			} ?>
		</div>
	</div>

	<?php
	}
	?>
    </div>
</div>

<script>
function reformatNilai(ele,nilai,prefix) {
	var harga = Number(nilai).toLocaleString('id');
	$('#label_'+ele).html(prefix+harga);
}

window.onload = function() {
	<?=$addJS?>
	
	$('#btnCari').click(function(){
		$('#searchModal').modal('show');
	});
	$('#btnCari_close').click(function(){
		$('#searchModal').modal('hide');
	});
	
	$('.format_harga').keyup(function(){
		var id = $(this).attr('id');
		reformatNilai(id,$(this).val(),'Rp.&nbsp;');
	});
};
</script>