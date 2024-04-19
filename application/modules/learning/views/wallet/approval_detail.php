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
		
		// approval dibuka?
		if($tgl_n>=$arrTglApproval['mulai'] && $tgl_n<=$arrTglApproval['selesai']) {
			$enable_simpan_ui = true;
		} else {
			$strError .= "<li>Approval Pelatihan dibuka tanggal ".$arrTglApproval['mulai']." sd ".$arrTglApproval['selesai']." setiap bulannya.</li>";
			$enable_simpan_ui = false;
		}
		
		/*
		$arrInfo = $CI->learning_wallet_model->getInfoPengajuanEntitas($tahun_terpilih,$group_id,$tmp_tb,$id_pelatihan);
		$dharga_disetujui = $arrInfo['disetujui'];
		$dharga_menunggu = $arrInfo['menunggu'];
		$dharga_total = $arrInfo['total'];
		*/
		
		$res = $CI->learning_wallet_model->getDetailPelatihan($tahun_terpilih,$id_pelatihan);
		$dharga_asli = $res[0]['harga'];
		
		$arrStatus = array();
		$arrCatatan = array();
		$arrNominal = array();
		
		$post = $this->input->post();
		if(!empty($post)) {
			$act = $post['act'];
			$arrStatus = $post['status'];
			$arrCatatan = $post['catatan_approval'];
			$arrNominal = $post['nominal_acc'];
			
			// khusus N4, cek harga dl
			if($group_id=="4") {
				$jumlX = 0;
				foreach($arrNominal as $key => $val) {
					if($val<$dharga_asli) $jumlX++;
				}
				if($jumlX>0) $strError .= '<li>Ada '.$jumlX.' pengajuan yang nominal disetujui di bawah harga jual.</li>';
			}
			
			if($act=="sf") {
				$jumlX = 0;
				foreach($arrCatatan as $key => $val) {
					if(empty($arrStatus[$key])) $jumlX++;
				}
				if($jumlX>0) $strError .= '<li>Ada '.$jumlX.' pengajuan ada yang belum diperiksa.</li>';
			}
			
			if(empty($strError)) {
				$CI->db->trans_start();
				foreach($arrCatatan as $key => $val) {
					$key = (int) $key;
					$nilai = (int) $arrStatus[$key];
					$harga = floatval($arrNominal[$key]);
					$catatan = $val;
					
					if(empty($key)) continue;
					
					$addSql = "";
					
					$kode = "0";
					$kode_sdm = "0";
					$kode_sevp = "0";
					if($status_verifikator=="SDM") {
						$kode_sdm = $nilai;
						$kode_sevp = $nilai;
						
						if($act=="sf") {
							$kode = ($nilai=="1")? "40" : "-20";
							$addSql .= ", is_final_sdm='1', is_final_sevp='1' ";
						}
						else {
							$kode = "20";
							$addSql .= ", is_final_sdm='0', is_final_sevp='0' ";
						}
						
						$addSql .= ", kode_status_sdm='".$kode_sdm."', id_verifikator_sdm='".$member_id."', tgl_update_sdm=now() ";
						$addSql .= ", kode_status_sevp='".$kode_sevp."', id_verifikator_sevp='".$member_id."', tgl_update_sevp=now() ";
					}
					
					// simpan final? update tgl update-nya
					if($act=="sf") $addSql .= ", tgl_update_status=now() ";
					
					// khusus N4, bisa update harga
					if($group_id=="4") {
						$addSql .= ", harga='".$harga."' ";
					}
					
					$kueri = "update _learning_wallet_pengajuan set id_group='".$group_id."', kode_status_current='".$kode."', catatan_approval='".$catatan."' ".$addSql." where id='".$key."' ";
					$CI->db->query($kueri);
				}
				$CI->db->trans_complete();
				
				if($CI->db->trans_status()===false) {
					$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
				} else {
					redirect(base_url('learning/wallet/approval_detail/'.$tahun_terpilih.'/'.$id_pelatihan));
					exit;
				}
			}
		}
		
		$is_done_approval_sdm = true;
		$i = 0;
		$curr_pelatihan = 0;
		$ui = '';
		$sql =
			"select
				c.tahun, c.id as id_pelatihan, c.nama, c.tgl_mulai, c.tgl_selesai, c.jumlah_jam, c.lokasi_offline,
				p.id as id_pengajuan, p.id_member, p.harga, p.berkas, p.alasan_request, p.catatan_approval, p.kode_status_sdm, p.kode_status_sevp, p.is_final_sdm, p.is_final_sevp,
				m.member_name
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c, _member m
			 where c.id='".$id_pelatihan."' and p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id='".$group_id."' and p.tahun='".$tahun_terpilih."' and p.status='aktif' and p.is_final_sdm='0'
			 order by c.nama, m.member_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$i++;
			
			$status_proses = false;
			
			$url_berkas = $CI->learning_wallet_model->get_url_berkas_approval($val['tahun'],$val['berkas'],true,true);
			
			// untuk ui tombol simpan
			if($status_verifikator=="SDM") {
				// blm selesai diapprove?
				if($val['is_final_sdm']!="1") $is_done_approval_sdm = false;
			}
			
			$id_pengajuan = $val['id_pengajuan'];
			$id_pelatihan = $val['id_pelatihan'];
			$dharga = $val['harga'];
			$kode_status_sdm = $val['kode_status_sdm'];
			$kode_status_sevp = $val['kode_status_sevp'];
			$catatan_approval = $val['catatan_approval'];
			
			// data kode status
			if(empty($post)) {
				$arrStatus[$id_pengajuan] = $kode_status_sdm;
				$arrCatatan[$id_pengajuan] = $catatan_approval;
				$arrNominal[$id_pengajuan] = $dharga;
			}
			
			if($curr_pelatihan!=$id_pelatihan) {
				$curr_pelatihan = $id_pelatihan;
				$i = 1;
				$ui .=
				'<tr>
					<td colspan="5">
						<div class="rounded lw_bg_hijau text-white mb-1 p-1">
						'.$val['nama'].'<br/>
						'.$CI->function_api->date_indo($val['tgl_mulai']).' sd '.$CI->function_api->date_indo($val['tgl_selesai']).'<br/>
						'.$CI->learning_wallet_model->reformatHarga($dharga_asli).'<br/>
						'.$val['jumlah_jam'].' JPL<br/>
						lokasi:&nbsp;'.$val['lokasi_offline'].'<br/>
						</div>
					</td>
				 </tr>
				 <tr>
					<td class="lw_bg_hijau text-white" style="width:1%">No</td>
					<td class="lw_bg_hijau text-white">Nama</td>
					<td class="lw_bg_hijau text-white d-print-none" colspan="2">Status Persetujuan</td>
					<td class="lw_bg_hijau text-white d-print-none" style="width:1%">&nbsp;</td>
				 </tr>
				 ';
			}
			
			$seldA = '';
			$seldX = '';
			$seldY = '';
			if($arrStatus[$id_pengajuan]=="-1") {
				$seldX = " checked ";
				$status_proses = true;
			} else if($arrStatus[$id_pengajuan]=="1") {
				$seldY = " checked ";
				$status_proses = true;
			}
			
			// ui
			if($status_proses==true) {
				$dcss = 'bg-success';
			} else {
				$seldA = " checked ";
				$dcss = 'bg-danger';
			}
			
			$uiNominal = '';
			
			// khusus N4, bisa ngubah nominal yg di-acc (harus lbh besar dari nilai asli
			if($group_id=="4") {
				$class_nominal = ($arrNominal[$id_pengajuan]<$dharga_asli)? 'badge-danger' : 'badge-primary';
				$uiNominal =
					'<tr class="d-print-none">
						<td colspan="5">
							<div class="form-group row">
								<label class="col-6 col-form-label" for="nominal_acc">Nominal Disetujui<span class="text-danger">*</span>&nbsp;<span class="badge '.$class_nominal.'" id="label_nominal_acc'.$id_pengajuan.'">'.$CI->learning_wallet_model->reformatHarga($arrNominal[$id_pengajuan]).'</span></label>
								<div class="col-6">
									<input type="text" class="form-control format_harga" id="nominal_acc'.$id_pengajuan.'" name="nominal_acc['.$id_pengajuan.']" value="'.$arrNominal[$id_pengajuan].'" autocomplete="off"/>
								</div>
							</div>
						</td>
					</tr>';
			}
			
			$ui .=
				'<tr>
					<td class="border-top border-info" rowspan="3">'.$i.'</td>
					<td class="border-top border-info">'.$val['member_name'].'</td>
					<td class="border-top border-info d-print-none"><input type="radio" name="status['.$id_pengajuan.']" id="statusX'.$id_pengajuan.'" '.$seldX.' value="-1"><label class="text-danger" for="statusX'.$id_pengajuan.'">&nbsp;tolak</label></td>
					<td class="border-top border-info d-print-none"><input type="radio" name="status['.$id_pengajuan.']" id="statusY'.$id_pengajuan.'" '.$seldY.' value="1" ><label class="text-primary" for="statusY'.$id_pengajuan.'">&nbsp;setujui</label></td>
					<td class="border-top border-info d-print-none '.$dcss.'">&nbsp;</td>
				 </tr>
				 <tr>
					<td colspan="5">
						alasan pengajuan: '.$url_berkas.'<br/>'.$val['alasan_request'].'
					</td>
				 </tr>
				 '.$uiNominal.'
				 <tr class="d-print-none">
					<td colspan="5">
						catatan:<br/>
						<textarea class="form-control" id="catatan_approval'.$id_pengajuan.'" rows="3" name="catatan_approval['.$id_pengajuan.']" onkeypress="javascript: if(event.keyCode == 13) event.preventDefault();">'.$arrCatatan[$id_pengajuan].'</textarea>
					</td>
				 </tr>';
		}
		
		// SDM udah selesai verifikasi?
		if($status_verifikator=="SDM" && $is_done_approval_sdm) {
			$enable_simpan_ui = false;
		}
		
		/*
		$dpersen_disetujui = ($dharga_disetujui/$dharga_total) * 100;
		$dpersen_disetujui = number_format($dpersen_disetujui,2);
		$dpersen_sisa = 100 - $dpersen_disetujui;
		*/
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_approval.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Approval Pelatihan yang Diajukan<br/>Bulan <?=$tb_aju?> (<?=$status_verifikator?>)</h2>
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
		
		<div class="mt-2 pl-1 small border border-primary rounded d-print-none">
			<b>Catatan</b>:<br/>
			<ul class="lw_li_line_height_sm">
				<!--<li>Approval Pelatihan dibuka tanggal <?=$arrTglApproval['mulai'].' sd '.$arrTglApproval['selesai']?> setiap bulannya.</li>-->
				<li>Gunakan simpan draft apabila data baru diperiksa sebagian/ingin diperiksa kembali.</li>
				<li>Gunakan simpan final apabila semua data telah diperiksa. Data yang sudah disimpan final tidak dapat diubah.</li>
				<li>Catatan akan muncul ke karyawan yang bersangkutan setelah data disimpan final.</li>
			</ul>
		</div>
	</div>
	
	<div class="p-2 pt-0">
		<form id="dform" action="<?=base_url('learning/wallet/approval_detail/'.$tahun_terpilih.'/'.$id_pelatihan)?>" id="form" method="post" class="form-horizontal">
			<table class="table table-sm">
				<tbody>
					<?=$ui?>
				</tbody>
			</table>
			
			<?php if($enable_simpan_ui) { ?>
			<input type="hidden" id="act" name="act" value=""/>
			<div class="mt-2 row d-print-none">
				<div class="col text-left">
					<input class="btn btn-warning" type="button" id="ss" name="ss" value="Simpan Draft"/>
				</div>
				<div class="col text-right">
					<input class="btn btn-success" type="button" id="sf" name="sf" value="Simpan Final"/>
				</div>
			</div>
			<?php } ?>
		</form>
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
	
	if(nilai<<?=$dharga_asli?>) {
		$('#label_'+ele).removeClass('badge-primary');
		$('#label_'+ele).addClass('badge-danger');
	} else {
		$('#label_'+ele).removeClass('badge-danger');
		$('#label_'+ele).addClass('badge-primary');
	}
}

window.onload = function() {
	$('.format_harga').keyup(function(){
		var id = $(this).attr('id');
		reformatNilai(id,$(this).val(),'Rp.&nbsp;');
	});
	
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