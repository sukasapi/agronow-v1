<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);

// lanjut?
if(empty($tahun_terpilih)) $tahun_terpilih = date("Y");
if(empty($kategori)) $kategori = "r1";

// get target jpl
$sql = "select nilai from _learning_wallet_konfigurasi where tahun='0' and kategori='umum' and id_group='0' and nama='target_jam_pembelajaran'";
$res = $this->db->query($sql);
$row = $res->result_array();
$jpl_target_satuan = $row[0]['nilai'];

// get level karyawan
$arrLv = array();
$sql = "select id, nama from _member_level_karyawan where id_klien='".$id_klien."' order by nama";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach ($row as $key => $val) {
	$arrLv[ $val['id'] ] = $val['nama'];
}

$ui = '';
foreach($form_opt_group as $key => $val) {
	$group_id = $key;
	$nama_entitas = $val;
	
	// target
	$jumlah_karyawan = 0;
	$all_target_jpl = 0;
	$all_target_nominal = 0;
	$all_serapan_jpl = 0;
	$all_serapan_nominal = 0;
	
	$arrT = array();
	foreach($arrLv as $keyLv => $valLv) {
		$id_lv = $keyLv;
		
		$arrT[$id_lv]['nama_lv'] = $valLv;
		
		// target - jumlah
		$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='juml_kary_".$id_lv."' ";
		$res2 = $this->db->query($sql2);
		$row2 = $res2->result_array();
		$jumlah = $row2[0]['nilai'];
		if(empty($jumlah)) {
			$jumlah = 0;
		}
		$arrT[$id_lv]['karyawan'] = $jumlah;
		
		$arrT[$id_lv]['target_jpl'] = $jumlah*$jpl_target_satuan;
		
		// target - nominal
		$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='lv_kary_".$id_lv."' ";
		$res2 = $this->db->query($sql2);
		$row2 = $res2->result_array();
		$nilai = $row2[0]['nilai'];
		if(empty($nilai)) {
			$nilai = 0;
		}
		$arrT[$id_lv]['target_nominal'] = $jumlah*$nilai;
		
		// realisasi
		$sql2 = "select sum(jpl_rencana) as jren, sum(jpl_realisasi) as jrea, sum(nominal_rencana) as nren, sum(nominal_realisasi) as nrea from _learning_wallet_serapan where tahun='".$tahun_terpilih."' and id_group='".$group_id."' and id_level_karyawan='".$id_lv."' ";
		$res2 = $this->db->query($sql2);
		$row2 = $res2->result_array();
		$jren = (empty($row2[0]['jren']))? 0 : $row2[0]['jren'];
		$jrea = (empty($row2[0]['jrea']))? 0 : $row2[0]['jrea'];
		$nren = (empty($row2[0]['nren']))? 0 : $row2[0]['nren'];
		$nrea = (empty($row2[0]['nrea']))? 0 : $row2[0]['nrea'];
		
		$serapan_jpl = 0;
		$serapan_nominal = 0;
		if($kategori=="r1") {
			$serapan_jpl = $jrea;
			$serapan_nominal = $nrea;
		} else if($kategori=="r2") {
			$serapan_jpl = $jren+$jrea;
			$serapan_nominal = $nren+$nrea;
		}
		$arrT[$id_lv]['serapan_jpl'] = $serapan_jpl;
		$arrT[$id_lv]['serapan_nominal'] = $serapan_nominal;
		
		$persen_jpl = (empty($arrT[$id_lv]['target_jpl']))? 100 : ($serapan_jpl/$arrT[$id_lv]['target_jpl'])*100;
		$persen_nominal = (empty($arrT[$id_lv]['target_nominal']))? 0 : ($serapan_nominal/$arrT[$id_lv]['target_nominal'])*100;
		$arrT[$id_lv]['persen_jpl'] = $persen_jpl;
		$arrT[$id_lv]['persen_nominal'] = $persen_nominal;
		
		// all
		$jumlah_karyawan += $arrT[$id_lv]['karyawan'];
		$all_target_jpl += $arrT[$id_lv]['target_jpl'];
		$all_target_nominal += $arrT[$id_lv]['target_nominal'];
		$all_serapan_jpl += $arrT[$id_lv]['serapan_jpl'];
		$all_serapan_nominal += $arrT[$id_lv]['serapan_nominal'];
	}
	
	$all_persen_jpl = (empty($all_target_jpl))? 100 : ($all_serapan_jpl/$all_target_jpl)*100;
	$all_persen_nominal = (empty($all_target_nominal))? 0 : ($all_serapan_nominal/$all_target_nominal)*100;
	
	$ui .=
		'<tr>
			<td>'.$nama_entitas.'</td>
			<td>all</td>
			<td>'.$jumlah_karyawan.'</td>
			<td>'.$all_target_jpl.'</td>
			<td>'.number_format($all_target_nominal,2,',','.').'</td>
			<td>'.$all_serapan_jpl.'</td>
			<td>'.number_format($all_serapan_nominal,2,',','.').'</td>
			<td>'.number_format($all_persen_jpl,2,',','.').'</td>
			<td>'.number_format($all_persen_nominal,2,',','.').'</td>
		 </tr>';
		 
	foreach($arrT as $keyT => $valT) {
			$target_jpl = $valT['target_jpl'];
			$target_nominal = $valT['target_nominal'];
			$serapan_jpl = $valT['serapan_jpl'];
			$serapan_nominal = $valT['serapan_nominal'];
			$persen_jpl = $valT['persen_jpl'];
			$persen_nominal = $valT['persen_nominal'];
			
			$ui .=
				'<tr>
					<td>'.$nama_entitas.'</td>
					<td>'.$valT['nama_lv'].'</td>
					<td>'.$valT['karyawan'].'</td>
					<td>'.$target_jpl.'</td>
					<td>'.number_format($target_nominal,2,',','.').'</td>
					<td>'.$serapan_jpl.'</td>
					<td>'.number_format($serapan_nominal,2,',','.').'</td>
					<td>'.number_format($persen_jpl,2,',','.').'</td>
					<td>'.number_format($persen_nominal,2,',','.').'</td>
				 </tr>';
		
	}
}
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        </div>
    </div>
    <!-- end:: Subheader -->
	
    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>
			
			<!-- FILTER -->
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--head-sm">
					<div class="kt-portlet__body" id="body-filter">
                        <div class="row">
                            <div class="col-xl-12">
                                <form class="kt-form">
                                    <div class="row">
										<div class="col-12 col-lg-5">
                                            <label>Sumber Data</label>
                                            <?php
                                            $selected_value = $kategori!=NULL ? $kategori : '';

                                            $attr = 'class="form-control" id="kategori" ';
                                            echo form_dropdown('kategori', $form_opt_kategori, $selected_value, $attr);

                                            ?>
                                        </div>
										<div class="col-12 col-lg-5">
                                            <label>Tahun</label>
                                            <?php
                                            $selected_value = $tahun_terpilih!=NULL ? $tahun_terpilih : '';

                                            $attr = 'class="form-control" id="tahun" ';
                                            echo form_dropdown('tahun', $form_opt_tahun, $selected_value, $attr);

                                            ?>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label></label>
                                            <button type="submit" class="form-control btn btn-info btn-sm mt-2"><i class="la la-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
            <div class="col-xl-12">
                <div class="kt-portlet">
					<div class="kt-portlet__body">
						<div class="alert alert-info">
							catatan:<br/>
							<ul>
								<li>Sifat data: by rekap.</li>
								<li>Data rencana: serapan nominal/jpl yang belum diverifikasi oleh pengelola kelas.</li>
								<li>Data realisasi: serapan nominal/jpl yang sudah diverifikasi oleh pengelola kelas.</li>
							</ul>
						</div>
					
						<table class="table table-sm table-bordered">
							<tr>
								<td rowspan="2">Entitas</td>
								<td rowspan="2">Level</td>
								<td rowspan="2">Karyawan</td>
								<td colspan="2">Target</td>
								<td colspan="2">Serapan</td>
								<td colspan="2">Persentase</td>
							</tr>
							<tr>
								<td>JPL</td>
								<td>Nominal</td>
								<td>JPL</td>
								<td>Nominal</td>
								<td>JPL</td>
								<td>Nominal</td>
							</tr>
							<?=$ui?>
						</table>
					</div>
				</div>
            </div>

        </div>
    </div>
    <!-- end:: Content -->


</div>