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
	
	<!-- Extra Header -->
	<div class="m-0 p-1 bg-dark">
		<!--<form class="search-form" method="POST" action="<?=base_url('learning/wallet')?>">-->
			<label for="ctahun">Pilih Tahun:</label>
			<select name="ctahun" class="form-control custom-select form-rounded" style="width: 100%">
				<?php
				foreach($arrTahun as $key => $val) {
					$seld = ($tahun_terpilih==$key)? "selected" : "";
					$ui = '<option value="'.$key.'" '.$seld.'>'.$this->title.' '.$val.'</option>';
					echo $ui;
				}
				?>
			</select>
		<!--</form>-->
	</div>
	<!-- * Extra Header -->

	<?php
	if(empty($arrK)) { // data tidak ditemukan
	?>

	<div class="m-2 p-3 alert alert-info">Tidak dapat menampilkan data, PIC entitas tahun <?=$tahun_terpilih?> belum diatur.</div>

	<?php
	} else { // data ditemukan
		$CI =& get_instance();
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$nik_karyawan = $this->session->userdata('member_nip');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		
		/*
		if($member_id=="8684") { // Tim Developer IT
			$nik_karyawan = '3024743';
			
			$sql = "select member_id, member_name, member_nip, id_level_karyawan, group_id from _member where member_nip='".$nik_karyawan."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$member_id = $row[0]['member_id'];
			$nama_karyawan = $row[0]['member_name'];
			$nik_karyawan = $row[0]['member_nip'];
			$id_level_karyawan = $row[0]['id_level_karyawan'];
			$group_id = $row[0]['group_id'];
		}
		//*/
		
		$sql = "select nama from _member_level_karyawan where id='".$id_level_karyawan."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$level_karyawan = $row[0]['nama'];
		
		$saldo_awal = $CI->learning_wallet_model->getSaldoAwal($tahun_terpilih,$member_id,$group_id,$id_level_karyawan);
		$saldo_terpakai = $CI->learning_wallet_model->getSaldoTerpakai($tahun_terpilih,$member_id);
		$saldo_sisa = $saldo_awal-$saldo_terpakai;
		$arrT = $CI->learning_wallet_model->getKonfigurasi('target_jam_pembelajaran');
		$jpl_target = $arrT['target_jam_pembelajaran']['nilai'];
		$jpl_realisasi = $CI->learning_wallet_model->getRealisasiJPL($tahun_terpilih,$member_id);
		
		$arrTglPengajuan = $CI->learning_wallet_model->getTanggalKonfig('pengajuan');
		$arrTglApproval = $CI->learning_wallet_model->getTanggalKonfig('approval');
		
		$menus = [];
		$menus[] = [
			'name' => 'Pelatihan Saya '.$tahun_terpilih,
			'url' => base_url('learning/wallet/my/'.$tahun_terpilih),
			'icon' => PATH_ASSETS.'icon/lw_my.png',
			'desc' => 'daftar pelatihan yang Saya ajukan',
			'warna_judul' => 'black',
			'img_style' => '',
		];

		$menus[] = [
			'name' => 'Belanja Pelatihan '.$tahun_terpilih,
			'url' => base_url('learning/wallet/belanja/'.$tahun_terpilih),
			'icon' => PATH_ASSETS.'icon/lw_belanja.png',
			'desc' => 'daftar pelatihan yang dapat Saya beli',
			'warna_judul' => 'black',
			'img_style' => '',
		];
		
		$menus[] = [
			'name' => 'Usulan Pelatihan',
			'url' => base_url('learning/wallet/usulan/'.$tahun_terpilih),
			'icon' => PATH_ASSETS.'icon/lw_usulan.png',
			'desc' => 'pelatihan yang diinginkan tidak ditemukan? Usulkan di sini',
			'warna_judul' => 'black',
			'img_style' => '',
		];
		
		// menu khusus admin sdm
		if($this->session->userdata('member_nip')==$arrK['verifikator_sdm']) {
			$menus[] = [
				'name' => 'Approval Pelatihan '.$tahun_terpilih,
				'url' => base_url('learning/wallet/approval/'.$tahun_terpilih),
				'icon' => PATH_ASSETS.'icon/lw_approval.png',
				'desc' => 'Persetujuan Pengajuan Pelatihan',
				'warna_judul' => 'black',
				'img_style' => '',
			];
		} else {
			$menus[] = [
				'name' => 'Approval Pelatihan '.$tahun_terpilih,
				'url' => '#',
				'icon' => PATH_ASSETS.'icon/lw_approval.png',
				'desc' => 'Persetujuan Pengajuan Pelatihan',
				'warna_judul' => 'gray',
				'img_style' => 'filter: grayscale(100%);',
			];
		}
	?>
	
	<div class="p-2 lw_bg_hijau">
		<h4 class="lw_text_kuning"><img style="max-height:1em;" src="<?=PATH_ASSETS.'icon/lw_dompet.png'?>"/> <?=$this->title.' tahun '.$tahun_terpilih?></h4>
		<table class="table table-sm text-light">
			<tr>
				<td>Nama / NIK</td>
				<td><?=$nama_karyawan?> / <?=$nik_karyawan?></td>
			</tr>
			<tr>
				<td>Level Karyawan</td>
				<td><?=$level_karyawan?></td>
			</tr>
			<tr>
				<td>Dana Pengembangan</td>
				<td><?=$CI->learning_wallet_model->reformatHarga($saldo_awal)?></td>
			</tr>
			<tr>
				<td>Dana Pengembangan Terpakai</td>
				<td><?=$CI->learning_wallet_model->reformatHarga($saldo_terpakai)?></td>
			</tr>
			<tr>
				<td>Sisa Dana Pengembangan</td>
				<td><?=$CI->learning_wallet_model->reformatHarga($saldo_sisa)?></td>
			</tr>
			<tr>
				<td>Pencapaian Jam Pembelajaran</td>
				<td><?=$jpl_realisasi.'/'.$jpl_target?></td>
			</tr>
			<tr>
				<td>Pemesanan Pelatihan</td>
				<td>tanggal <?=$arrTglPengajuan['mulai'].' sd '.$arrTglPengajuan['selesai']?> setiap bulannya</td>
			</tr>
			<tr>
				<td>Approval Pemesanan oleh SDM</td>
				<td>tanggal <?=$arrTglApproval['mulai'].' sd '.$arrTglApproval['selesai']?> setiap bulannya</td>
			</tr>
		</table>
	</div>
	
	<div class="p-3">
		<?php foreach ($menus as $menu) { ?>
			<div class="card mb-2">
				<a href="<?=$menu['url']?>">
					<div class="d-flex p-1">
						<div class="align-self-center">
							<img src="<?=$menu['icon']?>" alt="image" class="imaged w86" style="<?=$menu['img_style']?>">
						</div>
						<div class="align-self-center ml-1">
							<p class="mb-1" style="color: <?=$menu['warna_judul']?>; font-size: larger;"><b><?= $menu['name'] ?></b></p>
							<p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><?= $menu['desc'] ?></b></p>
						</div>
					</div>
				</a>
			</div>
		<?php } ?>
	</div>

	<?php
	}
	?>
    </div>
</div>

<script type="text/javascript">
    window.onload = function() {
        $('select[name=ctahun]').change(function(){
			var tahun = $(this).find(":selected").val();
			window.location.href = "<?=base_url('learning/wallet/beranda/')?>"+tahun;
		});
    };
</script>