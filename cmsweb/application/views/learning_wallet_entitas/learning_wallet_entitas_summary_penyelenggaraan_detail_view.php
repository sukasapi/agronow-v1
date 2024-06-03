<?php
$arrK = $this->learning_wallet_model->status_penyelenggaraan();
$arrD = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$id_pelatihan));

$juml_setuju = 0;
$pendaftar_setuju = '';
$juml_wait = 0;
$pendaftar_wait = '';
$juml_tolak = 0;
$pendaftar_tolak = '';

$addSql = "";

// admin entitas?
$session_group_id = $this->session->userdata('group_id');
$arrG = $this->group_model->get_child_company($group_id,"self_child");
$juml_group = count($arrG);
if($juml_group<1) {
	if(empty($session_group_id)) $group_id = -1;
	else $group_id = $session_group_id;
} else if($juml_group==1) {
	$group_id = $arrG[0]['group_id'];
} else {
	$arrT = $this->group_model->gets($session_group_id);
	$silsilah = $arrT[0]['silsilah'];
	
	$addSql .= " and g.silsilah like '".$silsilah."%' ";
}

if(!empty($group_id)) {
	$addSql = " and g.group_id='".$group_id."' ";
}

$sql =
	"select g.group_name, m.member_nip, m.member_name, m.member_phone, p.harga, p.no_wa, p.kode_status_current
	 from _learning_wallet_pengajuan p, _member m, _group g
	 where p.id_lw_classroom='".$id_pelatihan."' and p.id_member=m.member_id and m.group_id=g.group_id and p.status='aktif' ".$addSql;
$res = $this->db->query($sql);
$row = $res->result_array();
foreach ($row as $item) {
	$temp =
		'<tr>
			<td>'.$item['group_name'].'</td>
			<td>'.$item['member_nip'].'</td>
			<td>'.$item['member_name'].'</td>
			<td>'.$item['member_phone'].'</td>
		 </tr>';
		 
	if($item['kode_status_current']==40) {
		$juml_setuju++;
		$pendaftar_setuju .= $temp;
	} else if($item['kode_status_current']==20) {
		$juml_wait++;
		$pendaftar_wait .= $temp;
	} else if($item['kode_status_current']<0) {
		$juml_tolak++;
		$pendaftar_tolak .= $temp;
	}
}

if(!empty($pendaftar_setuju)) {
	$pendaftar_setuju =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
				<td>WA</td>
			</tr>
			'.$pendaftar_setuju.
		'</table>';
} else {
	$pendaftar_setuju = '-';
}
if(!empty($pendaftar_wait)) {
	$pendaftar_wait =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
				<td>WA</td>
			</tr>
			'.$pendaftar_wait.
		'</table>';
} else {
	$pendaftar_wait = '-';
}
if(!empty($pendaftar_tolak)) {
	$pendaftar_tolak =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
				<td>WA</td>
			</tr>
			'.$pendaftar_tolak.
		'</table>';
} else {
	$pendaftar_tolak = '-';
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
                <a href="<?php echo site_url("learning_wallet_entitas/dashboard_penyelenggaraan?group_id=".$group_id."&tahun=".$tahun_terpilih."&bulan=".$bulan."&kategori=".$kategori); ?>" class="btn kt-subheader__btn-primary">
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
										<td><?=$arrK[$arrD['status_penyelenggaraan']]?></td>
									</tr>
									<tr>
										<td>Catatan</td>
										<td><?=$arrD['catatan_penyelenggaraan']?></td>
									</tr>
									<tr>
										<td>Harga/Peserta</td>
										<td>Rp. <?=number_format($arrD['harga'],2,',','.')?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-success">Pendaftar Disetujui (<?=$juml_setuju?> orang)</span><br/><?=$pendaftar_setuju?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-warning">Pendaftar Menunggu Persetujuan (<?=$juml_wait?> orang)</span><br/><?=$pendaftar_wait?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-danger">Pendaftar Ditolak (<?=$juml_tolak?> orang)</span><br/><?=$pendaftar_tolak?></td>
									</tr>
								</table>
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