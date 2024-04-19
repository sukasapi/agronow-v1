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
		
		$strError = '';
		$durl = base_url('learning/wallet/belanja_detail/'.$tahun_terpilih.'/'.$id_pelatihan);
		$durl_batal = base_url('learning/wallet/belanja_batal/'.$tahun_terpilih.'/'.$id_pelatihan);
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		$id_klien = $this->session->userdata('id_klien');
		
		$arrMetode = $CI->learning_wallet_model->getDaftarKategoriMetode();
		
		$saldo_awal = $CI->learning_wallet_model->getSaldoAwal($tahun_terpilih,$member_id,$group_id,$id_level_karyawan);
		$saldo_terpakai = $CI->learning_wallet_model->getSaldoTerpakai($tahun_terpilih,$member_id);
		$saldo_sisa = $saldo_awal-$saldo_terpakai;
		
		$arrTglPengajuan = $CI->learning_wallet_model->getTanggalKonfig('pengajuan');
		
		// filtering data
		$CI->learning_wallet_model->filter_data['id_level_karyawan'] = $id_level_karyawan;
		
		$res = $CI->learning_wallet_model->getDetailPelatihan($tahun_terpilih,$id_pelatihan);
		$data = $res[0];
		$id_penyelenggara = $data['id_penyelenggara'];
		$id_pelatihan = $data['id'];
		$jumlah_jam = $data['jumlah_jam'];
		$harga_pelatihan = $data['harga'];
		$status_penyelenggaraan = $data['status_penyelenggaraan'];
		$ikon = base_url().'media/image_agrowallet/'.$data['berkas'];
		
		$time_mulai = strtotime($data['tgl_mulai'].' 00:00:00');
		$time_now = strtotime(date("Y-m-d")." 00:00:00");
		
		$arrPengajuan = $CI->learning_wallet_model->getDetailPengajuanPelatihan($tahun_terpilih,$member_id,$id_pelatihan);
		$id_pengajuan = $arrPengajuan['id'];
		$kode_status_pengajuan = $arrPengajuan['kode_status_pengajuan'];
		$alasan_request = $arrPengajuan['alasan_request'];
		$no_wa = $arrPengajuan['no_wa'];
		$url_berkas = $CI->learning_wallet_model->get_url_berkas_approval($tahun_terpilih,$arrPengajuan['berkas'],true);
		
		$post = $this->input->post();
		if(!empty($post)) {
			$act = $post['act'];
			$alasan_request = $post['alasan_request'];
			$no_wa = $post['no_wa'];
			
			$strError .= cekFile($_FILES['berkas'],"dok_file","bukti persetujuan dari atasan",true);
			if(empty($alasan_request)) $strError .= '<li>Saudara belum menjelaskan alasan kenapa membutuhkan pelatihan ini.</li>';
			if(empty($no_wa)) { $strError .= '<li>No Whatsapp masih kosong.</li>'; }
			else if(strlen($no_wa)<10) { $strError .= '<li>No Whatsapp invalid.</li>'; }
			
			if(empty($strError)) {
				$sql = "
					insert into _learning_wallet_pengajuan set
						tahun='".$tahun_terpilih."',
						id_group='".$group_id."',
						id_member='".$member_id."', 
						id_lw_classroom='".$id_pelatihan."', 
						id_level_karyawan='".$id_level_karyawan."', 
						harga='".$harga_pelatihan."',
						harga_asli='".$harga_pelatihan."',
						jumlah_jam='".$jumlah_jam."',
						no_wa='".$no_wa."',
						kode_status_current='20',
						alasan_request=".$this->db->escape($alasan_request).", 
						tgl_request=now() ";
				$res = $CI->db->query($sql);
				$did = $CI->db->insert_id();
				
				$namafile = date("Ym")."_".$did."_".uniqid().".pdf";
				
				$folder = $tahun_terpilih;
				$dir = FCPATH.AGROWALLET_MEDIA_APPROVAL_PATH.$folder;
				if(!file_exists($dir)) { mkdir($dir,0777); }
				
				$config['upload_path']          = $dir;
				$config['allowed_types']        = 'pdf';
				$config['file_name']            = $namafile;
				$config['overwrite']			= true;
				// $config['max_size']             = 1024; // 1MB
				$this->load->library('upload', $config);
				$this->upload->do_upload('berkas');
				
				$sql = " update _learning_wallet_pengajuan set berkas='".$namafile."' where id='".$did."' ";
				$res = $CI->db->query($sql);
				
				redirect($durl);
				exit;
			}
		}
		
		// additional badge
		$badgeLokasi = (empty($data['lokasi_offline']))? '' : '<span class="badge badge-warning text-dark"><ion-icon name="navigate-outline"></ion-icon>&nbsp;lokasi:&nbsp;'.$data['lokasi_offline'].'</span>';
		
		$tmp_tb = date('Y-m');
		$tgl_n = date("d");
		
		// cek level karyawan
		$pos = strpos($data['daftar_level_karyawan'],'['.$id_level_karyawan.']');
		if ($pos === false) {
			$info_level_karyawan = $CI->learning_wallet_model->daftar_level_karyawan2label($id_klien,$data['daftar_level_karyawan']);
			$strError .= '<li>Pelatihan ini untuk '.$info_level_karyawan.'.</li>';
		}
		
		$strInfo = '';
		$ui_booking = '';
		if(empty($id_pelatihan)) {
			$strError .= '<li>Pelatihan tidak ditemukan.</li>';
		} else if($id_pengajuan>0) {
			$strInfo = 'Saudara telah memesan pelatihan ini. <br/>Status pesanan dapat dilihat melalui menu <b>Pelatihan Saya</b>.';
			
			if($arrPengajuan['tb_request']==$tmp_tb && $tgl_n>=$arrTglPengajuan['mulai'] && $tgl_n<=$arrTglPengajuan['selesai'] && $arrPengajuan['is_final_sdm']=="0") {
				$ui_booking = '<a class="mt-1 btn btn-outline-danger" href="'.$durl_batal.'" onclick="return confirm(\'Anda yakin ingin membatalkan pelatihan ini?\')">batalkan?</a>';
			}
		} else if($time_mulai<=$time_now) {
			$strError .= "<li>Pelatihan sudah tidak bisa dipesan karena sudah berlalu.</li>";
		} else if($saldo_sisa<$harga_pelatihan) {
			$strError .= '<li>Sisa dana pengembangan ('.$CI->learning_wallet_model->reformatHarga($saldo_sisa).') tidak mencukupi untuk memesan pelatihan ini.</li>';
			$ui_booking = '<div class="small alert alert-danger text-center">Sisa dana pengembangan ('.$CI->learning_wallet_model->reformatHarga($saldo_sisa).') tidak mencukupi untuk memesan pelatihan ini.</div>';
		} else if($status_penyelenggaraan=="batal") {
			$strError .= '<li>Pelatihan tidak dapat dipesan karena batal diselenggarakan.</li>';
		} else {
			if($tgl_n>=$arrTglPengajuan['mulai'] && $tgl_n<=$arrTglPengajuan['selesai']) {
				$ui_booking = '<input type="submit" name="kirim" value="Pesan Sekarang" class="btn btn-success" onclick="return confirm(\'Anda yakin ingin memesan pelatihan ini?\')"/>';
			} else {
				$ui_booking = '<div class="small alert alert-danger text-center">Pemesanan pelatihan dibuka tanggal '.$arrTglPengajuan['mulai'].'-'.$arrTglPengajuan['selesai'].'</div>';
			}
		}
		
		// penyelenggara pelatihan
		$dataPenyelenggara = $CI->learning_wallet_model->getDetailPenyelenggara($id_penyelenggara);
		$cp_ui = $dataPenyelenggara['cp'];
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row">
			<div class="col-3 text-center align-self-center">
				<img src="<?=$ikon?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center lw_bg_hijau rounded pb-1">
				<h3 class="text-white pt-1 pr-1 mb-0"><?=$data['nama']?></h3>
				<div class="text-right mt-2">
					<span class="badge badge-warning text-dark"><ion-icon name="time-outline"></ion-icon>&nbsp;<?=$data['jumlah_jam']?>&nbsp;JPL</span>
					<span class="badge badge-warning text-dark"><ion-icon name="calendar-outline"></ion-icon>&nbsp;<?=$CI->function_api->date_indo($data['tgl_mulai']).' sd '.$CI->function_api->date_indo($data['tgl_selesai']);?></span>
					<span class="badge badge-warning text-dark"><ion-icon name="people-circle-outline"></ion-icon>&nbsp;min peserta: <?=$data['minimal_peserta']?> orang</span>
					<span class="badge badge-warning text-dark"><ion-icon name="desktop-outline"></ion-icon>&nbsp;<?=$arrMetode[$data['metode']]?></span>
					<?=$badgeLokasi?>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	if(!empty($strError)) {
		echo '
			<div class="pt-2 pl-2 pr-2 pb-0">
				<div class="alert alert-danger">
					<b>Tidak dapat memproses data</b>:<br/>
					<ul>'.$strError.'</ul>
				</div>
			</div>';
	}
	if(!empty($strInfo)) {
		$ui =
			'<div id="dtoast" class="toast-box toast-center tap-to-close bg-primary text-white">
				<div class="in">
					<div class="text">'.$strInfo.'</div>
				</div>
			</div>';
		$addJS = "toastbox('dtoast');";
		
		echo $ui;
	}
	?>
	
	<div class="section full pt-2">
		<div class="pt-0">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#deskripsi" role="tab">
                        Deskripsi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#sasaran" role="tab">
                        Sasaran Pembelajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#silabus" role="tab">
                        Silabus
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                <div class="tab-pane fade show active" id="deskripsi" role="tabpanel">
					<div class="section full">
						<div class="p-2 border"><?=$data['deskripsi']?></div>
					</div>
				</div>
				<div class="tab-pane fade show" id="sasaran" role="tabpanel">
					<div class="section full">
						<div class="p-2 border"><?=$data['sasaran_pembelajaran']?></div>
					</div>
				</div>
				<div class="tab-pane fade show" id="silabus" role="tabpanel">
					<div class="section full">
						<div class="p-2 border"><?=$data['silabus']?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="divider pb-2 mb-1"></div>
	
	<div class="mt-1 ml-2 mr-2 mb-2">
		<form action="<?=$durl?>" id="form" method="post" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group boxed">
				<div class="input-wrapper">
					<label>Bukti Persetujuan dari Atasan <span class="text-danger">*</span> <?=$url_berkas?></label>
					<div class="mb-1"><a class="btn btn-sm btn-primary" download href="<?=base_url().'media/template/template_agrowallet_surat_ijin_v2.docx'?>">download template surat izin mengikuti pelatihan</a></div>
					<div class="custom-file-upload">
						<input type="file" id="fileuploadInput" name="berkas" accept=".pdf">
						<label for="fileuploadInput">
							<span>
								<strong>
									<ion-icon name="cloud-upload-outline"></ion-icon>
									<i>
										Tap to Upload<br/><br/>
										File PDF<br/>
										Ukuran maksimal <?=(AGROWALLET_FILESIZE/1024)?> KB
									</i>
								</strong>
							</span>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="alasan_request">Kenapa Saudara membutuhkan pelatihan ini? <span class="text-danger">*</span></label>
				<textarea class="form-control" id="alasan_request" rows="3" name="alasan_request" onkeypress="javascript: if(event.keyCode == 13) event.preventDefault();"><?=$alasan_request?></textarea>
			</div>
			<div class="form-group">
			    <label for="no_wa">Nomor Whatsapp <span class="text-danger">*</span></label>
			    <input type="text" class="form-control" name="no_wa" id="no_wa" value="<?=$no_wa?>"/>
			    <small><i><div id="pesan" style="color:red">* Format nomor menggunakan 0 (08xxxxxx)</i></div></div></small>
			</div> 
			<div class="row">
				<div class="col">
					<div class="text-info font-weight-bold"><?=$CI->learning_wallet_model->reformatHarga($data['harga'])?></div>
				</div>
				<div class="col text-right">
					<input type="hidden" name="act" value="booking"/>
					<?=$ui_booking?>
				</div>
			</div>
		</form>
		
		<div class="mt-2 p-1 small border border-primary rounded">
			<b>Catatan</b>:<br/>
			<ul class="lw_li_line_height_sm">
				<li>Contact person: <?=$cp_ui?>.</li>
				<li>Harga tidak termasuk akomodasi selama pelatihan.</li>
				<!--<li>Pelatihan dapat dipesan pada tanggal <?=$arrTglPengajuan['mulai'].' sd '.$arrTglPengajuan['selesai']?> setiap bulannya.</li>-->
				<li>Pelatihan yang telah dipesan akan ditinjau oleh divisi SDM terlebih dahulu.</li>
				<li>Status pesanan dapat dilihat melalui menu <b>Pelatihan Saya</b>.</li>
			</ul>
		</div>
	</div>
	
	<div class="mb-2">&nbsp;</div>


	<?php
	}
	?>
    </div>
</div>

<script type="text/javascript">
window.onload = function() {
	<?=$addJS?>
};
</script>
<script>
    $( document ).ready(function() {
    
    $('#no_wa').on('input',function(e){
        e.preventDefault();
        var nowa=$(this).val();
        if(nowa.length < 10){
            $("#pesan").html('format nomor wa masih salah');
            //console.log(nowa.length);
        }else{
             //console.log(nowa.length);
             $("#pesan").html('*Konfirmasi akan dikirimkan melalui nomor ini');
             $("#kirim").removeAttr('disabled');
        }
    })
    
});
</script>