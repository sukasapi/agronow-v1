<?php
$enable_simpan_ui = true;
$is_done_approval_sdm = true;

$i = 0;
$curr_pelatihan = 0;

$ui = '';
$sql =
	"select
		c.tahun, c.id as id_pelatihan, c.kode, c.nama, c.tgl_mulai, c.tgl_selesai, c.jumlah_jam, c.lokasi_offline,
		p.id as id_pengajuan, p.id_member, p.harga, p.berkas, p.alasan_request, p.catatan_approval, p.kode_status_sdm, p.kode_status_sevp, p.is_final_sdm, p.is_final_sevp,
		m.member_name, m.member_id, m.group_id
	 from _learning_wallet_pengajuan p, _learning_wallet_classroom c, _member m
	 where 
		c.id='".$id_pelatihan."' and p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id='".$group_id."' and c.tahun='".$tahun_terpilih."' and p.status='aktif' and p.is_final_sdm='0'
	 order by c.nama, m.member_name ";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$i++;
	
	$tahun = $val['tahun'];
	$status_proses = false;
	
	$url_berkas = $this->learning_wallet_model->get_url_berkas_approval($val['tahun'],$val['berkas'],true,true);
	
	// blm selesai diapprove?
	if($val['is_final_sdm']!="1") $is_done_approval_sdm = false;
	
	$id_pengajuan = $val['id_pengajuan'];
	$id_pelatihan = $val['id_pelatihan'];
	$dharga = $val['harga'];
	$kode_status_sdm = $val['kode_status_sdm'];
	$kode_status_sevp = $val['kode_status_sevp'];
	$catatan_approval = $val['catatan_approval'];
	
	$dharga_asli = $dharga;
	
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
				<div class="rounded bg-info text-white mb-1 p-2">
				'.$val['kode'].'<br/>
				<b>'.$val['nama'].'</b><br/>
				'.parseDateShortReadable($val['tgl_mulai']).' sd '.parseDateShortReadable($val['tgl_selesai']).'<br/>
				Rp.&nbsp;'.number_format($dharga_asli,2,',','.').'<br/>
				'.$val['jumlah_jam'].' JPL<br/>
				lokasi:&nbsp;'.$val['lokasi_offline'].'<br/>
				</div>
			</td>
		 </tr>
		 <tr>
			<td class="bg-info text-white" style="width:1%">No</td>
			<td class="bg-info text-white">Nama</td>
			<td class="bg-info text-white d-print-none" colspan="2">Status Persetujuan</td>
			<td class="bg-info text-white d-print-none" style="width:1%">&nbsp;</td>
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
	// dimatikan dl karena sumber data nominal ga mesti dari pengajuan
	/* if($group_id=="4") {
		$class_nominal = ($arrNominal[$id_pengajuan]<$dharga_asli)? 'badge-danger' : 'badge-primary';
		$uiNominal =
			'<tr class="d-print-none">
				<td colspan="5">
					<div class="form-group row">
						<label class="col-6 col-form-label" for="nominal_acc">Nominal Disetujui</label>
						<div class="col-6">
							<input type="text" class="form-control format_harga" id="nominal_acc'.$id_pengajuan.'" name="nominal_acc['.$id_pengajuan.']" value="'.$arrNominal[$id_pengajuan].'" autocomplete="off"/>
						</div>
					</div>
				</td>
			</tr>';
	} */
	
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
				<button data-backdrop="static"
					data-remote="'.site_url('learning_wallet_entitas/l_modal_ajax_realisasi_karyawan/'.$val['group_id'].'/'.$val['tahun'].'/'.$val['member_id'].'/'.$id_pelatihan).'" type="button"
					class="btn btn-outline-info btn-sm ml-2" data-toggle="modal"
					data-target="#modal_picker">cek riwayat penggunaan saldo
				</button>
			</td>
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
if($is_done_approval_sdm) {
	$enable_simpan_ui = false;
}
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>
		<div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("learning_wallet_entitas/approval?group_id=".$group_id."&tahun=".$tahun_terpilih); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="mb-0 pb-0 kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-12">
                <!--Begin::Section-->
                <div class="kt-portlet">
					<div class="kt-portlet__body">
						<div class="mt-2 row">
							<div class="col-12">
								<div class="alert alert-info mb-2">
									<b>Panduan</b><br/>
									<ul>
										<li>Gunakan simpan draft apabila data baru diperiksa sebagian/ingin diperiksa kembali.</li>
										<li>Gunakan simpan final apabila semua data telah diperiksa. Data yang sudah disimpan final tidak dapat diubah.</li>
										<li>Catatan akan muncul ke karyawan yang bersangkutan setelah data disimpan final.</li>
									</ul>
								</div>
								
								<?php
								$attributes = array('autocomplete'=>'off','method'=>'post', 'id' => 'dform');
								echo form_open($form_action, $attributes);
								?>
									
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
								
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
                </div>
                <!--End::Section-->
            </div>
        </div>
    </div>
</div>

<!--begin::Modal-->
<div class="modal fade" id="modal_picker" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Riwayat Penggunaan AgroWallet <?=$tahun_terpilih?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->

<script type="text/javascript">
jQuery(document).ready(function() {
	$(".format_harga").inputmask({ alias : "currency", prefix: 'Rp. ', removeMaskOnSubmit: true });
	
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
});
</script>