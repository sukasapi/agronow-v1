<?php
$uiL = '';
$sql = "select * from _member_level_karyawan where status='active' and id_klien='".$id_klien."' order by nama";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$jumlah = '';
	$nilai = '';
	
	// jumlah
	$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='juml_kary_".$key."' ";
	$res2 = $this->db->query($sql2);
	$row2 = $res2->result_array();
	$jumlah = $row2[0]['nilai'];
	if(empty($jumlah)) {
		$jumlah = "";
	}
	
	// nominal
	$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='lv_kary_".$key."' ";
	$res2 = $this->db->query($sql2);
	$row2 = $res2->result_array();
	$nilai = $row2[0]['nilai'];
	if(empty($nilai)) {
		$nilai = "0";
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
					<label for="lv'.$val['id'].'">Nominal per Karyawan</label>
					<input type="text" class="form-control format_harga" id="lv'.$val['id'].'" name="lv['.$val['id'].']" value="'.$nilai.'"/>
				</div>
			</div>
		</div>';
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
                <a href="<?php echo site_url("learning_wallet_entitas/kelola_dana?group_id=".$group_id."&tahun=".$tahun_terpilih); ?>" class="btn kt-subheader__btn-primary">
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
										<li>Kolom <b>jumlah karyawan</b> digunakan untuk menghitung target jam pembelajaran perusahaan (<?=$target_jpl.'&nbsp;jam/karyawan'?>).</li>
										<li>Kolom <b>nominal per karyawan</b> digunakan sebagai rekomendasi alokasi anggaran agrowallet untuk setiap karyawan pada level tersebut.</li>
									</ul>
								</div>
								
								<?php
								$attributes = array('autocomplete'=>'off','method'=>'post', 'id' => 'dform');
								echo form_open($form_action, $attributes);
								?>
									
									<div class="row rounded mb-1 p-1">
										<div class="col-2">Group</div>
										<div class="col-10"><?=$nama_klien?></div>
									</div>
									<div class="row rounded mb-1 p-1">
										<div class="col-2">Tahun</div>
										<div class="col-10"><?=$tahun_terpilih?></div>
									</div>
									
									<?=$uiL?>
									
									<button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
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

<script type="text/javascript">
jQuery(document).ready(function() {
	$(".format_harga").inputmask({ alias : "currency", prefix: 'Rp. ', removeMaskOnSubmit: true });
});
</script>