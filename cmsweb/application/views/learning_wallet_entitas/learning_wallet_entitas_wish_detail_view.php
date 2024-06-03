<?php
$arrD = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$id_pelatihan));
if($arrD['status_penyelenggaraan']=="-") {
	$arrD['status_penyelenggaraan'] = "menunggu persetujuan";
}

$juml = 0;
$peminat_ui = '';

$addSql = "";
if(!empty($group_id)) {
	$addSql = " and g.group_id='".$group_id."' ";
}

$sql =
	"select g.group_name, m.member_nip, m.member_name
	 from _learning_wallet_wishlist w, _member m, _group g
	 where w.id_member=m.member_id and m.group_id=g.group_id and w.status='aktif' and w.id_lw_classroom='".$id_pelatihan."' ".$addSql."
	 order by g.group_name";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach ($row as $item) {
	$juml++;
	
	$peminat_ui .=
		'<tr>
			<td>'.$item['group_name'].'</td>
			<td>'.$item['member_nip'].'</td>
			<td>'.$item['member_name'].'</td>
		 </tr>';
}

$peminat_ui =
		'<table class="table table-bordered">
			<tr>
				<td style="width:30%">Group</td>
				<td style="width:10%">NIK</td>
				<td>Nama</td>
			</tr>
			'.$peminat_ui.
		'</table>';

?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>
		<div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("learning_wallet_entitas/wishlist?group_id=".$group_id."&tahun=".$tahun_terpilih); ?>" class="btn kt-subheader__btn-primary">
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
										<td>Harga/Peserta</td>
										<td>Rp. <?=number_format($arrD['harga'],2,',','.')?></td>
									</tr>
									<tr>
										<td colspan="2"><span class="mb-1 badge badge-success">Pendaftar Peminat (<?=$juml?> orang)</span><br/><?=$peminat_ui?></td>
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
</div>