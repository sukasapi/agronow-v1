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
		$addJS = '';
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		
		$arrT = $CI->learning_wallet_model->getKonfigurasi('target_jam_pembelajaran');
		$target_jpl = $arrT['target_jam_pembelajaran']['nilai'];
		
		$post = $this->input->post();
		if(!empty($post)) {
			$total = 0;
			$arrJuml = $post['juml'];
			$arrLv = $post['lv'];
			
			if(strlen($strError)<=0) {
				$kueri = "delete from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' ";
				$CI->db->query($kueri);
				
				$total = 0;
				foreach($arrLv as $key => $val) {
					$nilai = (int) $val;
					$juml = (int) $arrJuml[$key];
					
					$total += ($nilai * $juml);
					
					$did = uniqid('KONF',true);
					$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$tahun_terpilih."', kategori='group', id_group='".$group_id."', nama='juml_kary_".$key."', nilai='".$juml."', catatan='jumlah karyawan' ";
					$CI->db->query($kueri);
					
					$did = uniqid('KONF',true);
					$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$tahun_terpilih."', kategori='group', id_group='".$group_id."', nama='lv_kary_".$key."', nilai='".$nilai."', catatan='dana pengembangan' ";
					$CI->db->query($kueri);
				}
				
				$did = uniqid('KONF',true);
				$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$tahun_terpilih."', kategori='group', id_group='".$group_id."', nama='total_dana_pengembangan', nilai='".$total."', catatan='total dana pengembangan' ";
				$CI->db->query($kueri);
				
				$this->session->set_flashdata('str_info', 'Informasi: Data berhasil disimpan.');
				
				redirect(base_url('learning/wallet/kelola_dana_level/'.$tahun_terpilih));
				exit;
			}
		}
		
		$uiL = '';
		$sql = "select * from _member_level_karyawan where status='active' order by nama";
		$res = $CI->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$jumlah = '';
			$nilai = '';
			
			// jumlah
			$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='juml_kary_".$key."' ";
			$res2 = $CI->db->query($sql2);
			$row2 = $res2->result_array();
			$jumlah = $row2[0]['nilai'];
			if(empty($jumlah)) {
				$jumlah = "";
			}
			
			// nominal
			$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='lv_kary_".$key."' ";
			$res2 = $CI->db->query($sql2);
			$row2 = $res2->result_array();
			$nilai = $row2[0]['nilai'];
			if(empty($nilai)) {
				$nilai = "";
			} else {
				$addJS .= " reformatNilai('lv".$key."','".$nilai."','Rp.&nbsp;'); ";
			}
			
			$uiL .=
				'
				<div class="row rounded border border-primary mb-1 p-1">
					<div class="col-12 font-weight-bold">'.$val['nama'].'</div>
					<div class="col-6">
						<div class="form-group">
							<label for="juml'.$val['id'].'">Jumlah Karyawan</label>
							<input type="text" class="form-control" id="juml'.$val['id'].'" name="juml['.$val['id'].']" value="'.$jumlah.'" onkeypress="return event.charCode >= 48 && event.charCode <= 57"/>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="lv'.$val['id'].'">Nominal per Karyawan &nbsp;<span class="badge badge-primary" id="label_lv'.$val['id'].'"></span></label>
							<input type="text" class="form-control format_harga" id="lv'.$val['id'].'" name="lv['.$val['id'].']" value="'.$nilai.'"/>
						</div>
					</div>
				</div>';
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_kelola_dana.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Kelola Dana Pengembangan Tahun <?=$tahun_terpilih?> per Level Karyawan</h2>
			</div>
		</div>
		
		<?php
		if($this->session->flashdata('str_info')) {
			echo '
				<div class="pt-2 pl-2 pr-2">
					<div class="alert alert-primary">
						'.$this->session->flashdata('str_info').'
					</div>
				</div>';
		}
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
		
		<div class="mt-2 row">
			<div class="col-12">
				<div class="alert alert-info mb-2">
					<b>Panduan</b><br/>
					<ul>
						<li>Kolom <b>jumlah karyawan</b> digunakan untuk menghitung target jam pembelajaran perusahaan (<?=$target_jpl.'&nbsp;jam/karyawan'?>).</li>
						<li>Kolom <b>nominal per karyawan</b> digunakan untuk memberikan dana awal kepada setiap karyawan pada level tersebut.</li>
						<li>Kolom <b>nominal per karyawan</b> diisi dengan angka tanpa format apapun (misal <b>4000000</b> untuk empat juta rupiah)</li>
					</ul>
				</div>
				
				<form action="<?=base_url('learning/wallet/kelola_dana_level/'.$tahun_terpilih)?>" id="form" method="post" class="form-horizontal">
					<!--
					<div class="mb-1">
						Total Anggaran Dana Pengembangan Tahun <?=$tahun_terpilih?>: <?=$total?>
					</div>
					-->
				
					<?=$uiL?>
					
					<button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
				</form>
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

<script type="text/javascript">
function reformatNilai(ele,nilai,prefix) {
	var harga = Number(nilai).toLocaleString('id');
	$('#label_'+ele).html(prefix+harga);
}
window.onload = function() {
	<?=$addJS?>
	
	$('.format_harga').keyup(function(){
		var id = $(this).attr('id');
		reformatNilai(id,$(this).val(),'Rp.&nbsp;');
	});
};
</script>