<?php
$arrG = $this->group_model->get($group_id);
$nama_group = $arrG['group_name'];

$arrD = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$id_lw_classroom));

$juml_setuju = 0;
$juml_tolak = 0;
$pendaftar_setuju = '';
$pendaftar_tolak = '';
$input_ui = '';

$input_ui.=
	'<input type="hidden" name="group_id" value="'.$group_id.'"/>
	 <input type="hidden" name="id_lw_classroom" value="'.$id_lw_classroom.'"/>';

foreach($sheet_data as $key => $val) {
	$member_id = $val['member_id'];
	$id_level_karyawan = $val['id_level_karyawan'];
	
	$temp_ui = 
		'<tr>
			<td>'.$nama_group.'</td>
			<td>'.$val['nik'].'</td>
			<td>'.$val['nama'].'</td>
			<td>'.$arr_lvl[$id_level_karyawan].'</td>
			<td>'.$val['no_wa'].'</td>
			<td>'.$val['error_message'].'</td>
		 </tr>';
	
	if(!empty($member_id) && $member_id>0) {
		$input_ui .=
			'<input type="hidden" name="mid['.$member_id.']" value="'.$member_id.'"/>
			 <input type="hidden" name="level['.$member_id.']" value="'.$id_level_karyawan.'"/>';
	}
	
	if($val['is_error']=="1") {
		$juml_tolak++;
		$pendaftar_tolak .= $temp_ui;
	} else {
		$juml_setuju++;
		$pendaftar_setuju .= $temp_ui;
	}
}

if(!empty($pendaftar_tolak)) {
	$pendaftar_tolak =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
				<td>Level</td>
				<td>WA</td>
				<td>Catatan</td>
			</tr>
			'.$pendaftar_tolak.
		'</table>';
}
if(!empty($pendaftar_setuju)) {
	$pendaftar_setuju =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
				<td>Level</td>
				<td>WA</td>
				<td>Catatan</td>
			</tr>
			'.$pendaftar_setuju.
		'</table>';
}
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>
		<div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("learning_wallet_entitas/approval_massal"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
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
									<b>Catatan</b><br/>
									<ul>
										<li>Periksa kembali data pada halaman ini, apakah sudah sesuai dengan berkas excel?</li>
										<li>Bila data sudah sesuai, silahkan tekan tombol <b>simpan</b> untuk memproses/menyimpan data.</li>
										<li>Hanya karyawan pada tabel <b>Pendaftar Akan Disetujui</b> yang akan disimpan sebagai peserta pelatihan.</li>
									</ul>
								</div>
								
								<h4><?=$arrD['nama']?></h4>
								<table class="table ">
									<tr>
										<td style="width:30%">Kode</td>
										<td><?=$arrD['kode']?></td>
									</tr>
									<tr>
										<td>Tgl Pelatihan Diselenggarakan</td>
										<td><?=parseDateShortReadable($arrD['tgl_selesai']).' sd '.parseDateShortReadable($arrD['tgl_selesai'])?></td>
									</tr>
									<tr>
										<td>Metode</td>
										<td><?=$arrD['metode']?></td>
									</tr>
									<tr>
										<td>Lokasi</td>
										<td><?=$arrD['lokasi_offline']?></td>
									</tr>
									<tr>
										<td>Status Penyelenggaraan</td>
										<td><?=$arrD['status_penyelenggaraan']?></td>
									</tr>
									<tr>
										<td>Catatan</td>
										<td><?=$arrD['catatan_penyelenggaraan']?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-success">Pendaftar Akan Disetujui (<?=$juml_setuju?>)</span><br/><?=$pendaftar_setuju?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-danger">Pendaftar Tidak Dapat Diproses (<?=$juml_tolak?>)</span><br/><?=$pendaftar_tolak?></td>
									</tr>
								</table>
								<hr/>
								
								<?php
								if($juml_setuju<1) {
									echo '<div class="alert alert-warning">tidak dapat melanjutkan proses karena tidak ada pendaftar yang disetujui</div>';
								} else {
									$attributes = array('autocomplete'=>'off','method'=>'post', 'id' => 'dform');
									echo form_open($form_action, $attributes);
									echo $input_ui;
									echo '<button type="submit" class="btn btn-success pl-5 pr-5" id="bsave">Simpan</button>';
									echo form_close();
								}
								?>
								
							</div>
						</div>
					</div>
                </div>
                <!--End::Section-->
            </div>
        </div>
    </div>
    <!-- end:: Content -->
</div>