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
		
		$add_sql = "";
		$add_sql2 = "";
		$add_sql3 = "";
		$add_sql4 = "";
		
		$group_id = $this->session->userdata('group_id');
		$id_klien = $this->session->userdata('id_klien');
		
		$post = $this->input->post();
		if(!empty($post)) {
			$centitas = (int) $post['centitas'];
			
		}
		
		if($group_id!='34') {
			$centitas = '-1';
		}
		
		// khusus unknown code dan holding bisa download all
		if(empty($centitas)) {
			if($group_id=="15" || $group_id=="34") {
				// do nothing
			} else {
				$centitas = $group_id;
			}
		}
		
		if(!empty($centitas)) {
			$add_sql  .= " and k.id_group='".$centitas."' ";
			$add_sql2 .= " and id_group='".$centitas."' ";
			$add_sql3 .= " and cm.id_group='".$centitas."' ";
			$add_sql4 .= " and p.id_group='".$centitas."'  ";
		}
		
		// holding/unknown code = bisa lihat semua data, else cuma bisa liat entitas sendiri
		if($group_id=="15" || $group_id=="34") {
			// do nothing
		} else {
			$CI->group_model->recData['group_id'] = $group_id;
		}
		
		// pencarian
		$CI->group_model->recData['id_klien'] = $id_klien;
		$arrEntitas = $CI->group_model->select_group('byKlienId_4select');
		
		$arrE2 = array();
		foreach($arrEntitas as $key => $val) {
			$arrE2[$val['group_id']] = $val['group_name'];
		}
		
		$arrT = $CI->learning_wallet_model->getKonfigurasi('target_jam_pembelajaran');
		$jpl_target = $arrT['target_jam_pembelajaran']['nilai'];
		
		$arrLvKaryawan = $CI->member_level_karyawan_model->getAllLevelKaryawan($id_klien);
		
		$arrKaryawan = array();
		$arrNominal = array();
		$arrJPL = array();
		
		foreach($arrLvKaryawan as $key => $val) {
			// jumlah karyawan
			$sql2 = "select k.nilai, k.id_group from _learning_wallet_konfigurasi k, _group g where k.id_group=g.group_id and g.id_klien='".$id_klien."' and k.tahun='".$tahun_terpilih."' and k.kategori='group' ".$add_sql." and k.nama='juml_kary_".$key."' ";
			$res2 = $CI->db->query($sql2);
			$row2 = $res2->result_array();
			foreach($row2 as $key2 => $val2) {
				$dgroup = $val2['id_group'];
				$jumlah_karyawan = $val2['nilai'];
				
				$arrKaryawan['all'] += $jumlah_karyawan;
				$arrKaryawan[$key] += $jumlah_karyawan;
				
				$jpl_target_lv = $jumlah_karyawan * $jpl_target;
				$arrJPL['all']['target'] += $jpl_target_lv;
				$arrJPL[$key]['target'] += $jpl_target_lv;
			
			
				// nominal
				$sql2 = "select nilai from _learning_wallet_konfigurasi where id_group='".$dgroup."' and tahun='".$tahun_terpilih."' and kategori='group' ".$add_sql2." and nama='lv_kary_".$key."' ";
				$res2 = $CI->db->query($sql2);
				$row2 = $res2->result_array();
				$nilai = $row2[0]['nilai'];
				
				$nominal_target_lv = $nilai * $jumlah_karyawan;
				$arrNominal['all']['target'] += $nominal_target_lv;
				$arrNominal[$key]['target'] += $nominal_target_lv;
			}
		}
		
		$ui = '';
		
		// pelatihan agrowallet yg terhubung dengan agronow
		$sql =
			"select 
				cm.crm_id, cm.id_group,
				w.nama as nama_pelatihan, w.harga, w.tgl_mulai, w.tgl_selesai, w.jumlah_jam, w.status_penyelenggaraan,
				m.member_nip, m.member_name, m.id_level_karyawan
			 from _learning_wallet_classroom w, _classroom c, _classroom_member cm, _member m, _group g
			 where 
				g.group_id=cm.id_group and g.id_klien='".$id_klien."' and
				w.id=c.id_lw_classroom and w.is_connect_agronow='1' and w.tahun='".$tahun_terpilih."' and w.status='aktif' and w.status_penyelenggaraan in ('jalan') 
				and c.cr_id=cm.cr_id and cm.member_status='1' and cm.is_pk='0' and cm.member_id=m.member_id ".$add_sql3." 
				and m.member_id not in(6020) ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$did = "CL".$val['crm_id'];
			$status_penyelenggaraan = $val['status_penyelenggaraan'];
			if($status_penyelenggaraan=="-") $status_penyelenggaraan = 'menunggu persetujuan';
			$level_karyawan = $val['id_level_karyawan'];
			
			if(empty($level_karyawan)) $level_karyawan = 'unknown';
			
			$arrNominal['all']['realisasi'] += $val['harga'];
			$arrNominal[$level_karyawan]['realisasi'] += $val['harga'];
			
			$arrJPL['all']['realisasi'] += $val['jumlah_jam'];
			$arrJPL[$level_karyawan]['realisasi'] += $val['jumlah_jam'];
			
			$ui .=
				'<tr>
					<td>'.$arrE2[$val['id_group']].'</td>
					<td>'.$did.'</td>
					<td>'.$val['nama_pelatihan'].'</td>
					<td>'.$val['tgl_mulai'].'</td>
					<td>'.$val['member_name'].'<br/>'.$val['member_nip'].'</td>
					<td>'.$arrLvKaryawan[$level_karyawan].'</td>
					<td>'.$val['harga'].'</td>
					<td>'.$val['jumlah_jam'].'</td>
					<td>'.$status_penyelenggaraan.'</td>
				 </tr>';
		}
		
		// pelatihan yg tidak terhubung dengan agronow
		$sql =
			"select
				p.id, p.id_group,
				w.nama as nama_pelatihan, w.harga, w.tgl_mulai, w.tgl_selesai, w.jumlah_jam, w.status_penyelenggaraan,
				m.member_nip, m.member_name, m.id_level_karyawan
			 from _learning_wallet_classroom w, _learning_wallet_pengajuan p, _member m, _group g
			 where
				g.group_id=p.id_group and g.id_klien='".$id_klien."' and
				w.id=p.id_lw_classroom and w.is_connect_agronow='0' and w.tahun='".$tahun_terpilih."' and w.status='aktif' and w.status_penyelenggaraan in ('jalan') 
				and p.id_member=m.member_id ".$add_sql4."
				and m.member_id not in(6020) ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$did = "AW".$val['id'];
			$status_penyelenggaraan = $val['status_penyelenggaraan'];
			if($status_penyelenggaraan=="-") $status_penyelenggaraan = 'menunggu persetujuan';
			$level_karyawan = $val['id_level_karyawan'];
			
			if(empty($level_karyawan)) $level_karyawan = 'unknown';
			
			$arrNominal['all']['realisasi'] += $val['harga'];
			$arrNominal[$level_karyawan]['realisasi'] += $val['harga'];
			
			$arrJPL['all']['realisasi'] += $val['jumlah_jam'];
			$arrJPL[$level_karyawan]['realisasi'] += $val['jumlah_jam'];
			
			$ui .=
				'<tr>
					<td>'.$arrE2[$val['id_group']].'</td>
					<td>'.$did.'</td>
					<td>'.$val['nama_pelatihan'].'</td>
					<td>'.$val['tgl_mulai'].'</td>
					<td>'.$val['member_name'].'<br/>'.$val['member_nip'].'</td>
					<td>'.$arrLvKaryawan[$level_karyawan].'</td>
					<td>'.$val['harga'].'</td>
					<td>'.$val['jumlah_jam'].'</td>
					<td>'.$status_penyelenggaraan.'</td>
				 </tr>';
		}
		
		$ringkasanUI = '';
		if(empty($ui)) {
			$ui = '<tr><td>Data tidak ditemukan pada tahun terpilih</td></tr>';
		} else {
			$ui_rincian = '';
			
			foreach($arrLvKaryawan as $key => $val) {
				$arrJPL[$key]['realisasi'] = (int) $arrJPL[$key]['realisasi'];
				
				$ui_rincian .=
					'<tr>
						<td>'.$val.'</td>
						<td>'.$arrKaryawan[$key].'</td>
						<td>'.$arrJPL[$key]['target'].'</td>
						<td>'.$arrJPL[$key]['realisasi'].'</td>
						<td>'.$CI->learning_wallet_model->hitungPersen($arrJPL[$key]['target'],$arrJPL[$key]['realisasi'],true,true).'</td>
						<td>'.$CI->learning_wallet_model->reformatHarga($arrNominal[$key]['target']).'</td>
						<td>'.$CI->learning_wallet_model->reformatHarga($arrNominal[$key]['realisasi']).'</td>
						<td>'.$CI->learning_wallet_model->hitungPersen($arrNominal[$key]['target'],$arrNominal[$key]['realisasi'],true,true).'</td>
					 </tr>';
			}
			
			$ringkasanUI =
				'<table class="table table-bordered table-sm">
					<thead>
						<tr>
							<th>Level</th>
							<th>Target Karyawan</th>
							<th>Target JPL</th>
							<th>Realisasi JPL</th>
							<th>% JPL</th>
							<th>Target Nominal</th>
							<th>Realisasi Nominal</th>
							<th>% Nominal</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Semua</td>
							<td>'.$arrKaryawan['all'].'</td>
							<td>'.$arrJPL['all']['target'].'</td>
							<td>'.$arrJPL['all']['realisasi'].'</td>
							<td>'.$CI->learning_wallet_model->hitungPersen($arrJPL['all']['target'],$arrJPL['all']['realisasi'],true,true).'</td>
							<td>'.$CI->learning_wallet_model->reformatHarga($arrNominal['all']['target']).'</td>
							<td>'.$CI->learning_wallet_model->reformatHarga($arrNominal['all']['realisasi']).'</td>
							<td>'.$CI->learning_wallet_model->hitungPersen($arrNominal['all']['target'],$arrNominal['all']['realisasi'],true,true).'</td>
						</tr>
						'.$ui_rincian.'
					</tbody>
				 </table>';
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_realisasi.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Dashboard Realisasi Penyerapan AgroWallet <?=$tahun_terpilih?></h2>
			</div>
		</div>
	</div>
	
	<div class="p-2">
		<div class="mb-2 pl-1 small border border-primary rounded">
			<b>Catatan</b>:<br/>
			<ul class="lw_li_line_height_sm">
				<!--<li>Approval Pelatihan dibuka tanggal 1 sd 31 setiap bulannya.</li>-->
				<li>Realisasi penyerapan AgroWallet diambil dari pelatihan yang sudah diselenggarakan.</li>
				<li>Target JPL per Karyawan: <?=$jpl_target?>.</li>
			</ul>
		</div>
		
		<div class="p-2 pt-0 border border-success rounded">
			<div class="section-title p-0 mt-1 mb-1">Pencarian</div>
			<div>
				<form action="<?=base_url('learning/wallet/dashboard_realisasi/'.$tahun_terpilih)?>" id="form" method="post" class="form-horizontal">
					<div class="form-group mb-1">
						<label for="centitas" class="mb-0">Entitas</label>
						<select class="form-control" id="centitas" name="centitas">
						  <option>Semua Entitas</option>
							<?php
							foreach($arrE2 as $key => $val) {
								$seld = ($centitas==$key)? "selected" : "";
								$uiT = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
								echo $uiT;
							}
							?>
						</select>
					</div>
					
					<div class="text-right">
						<button type="submit" id="submitConfirm" class="btn btn-info">Cari</button>
					</div>
				</form>
			</div>
		</div>
	</div>
		
	<div class="divider pb-2 mb-1 mt-2"></div>
			
	<div class="p-2">
		<h3 class="border-bottom pb-1 mb-1">Ringkasan <?=$arrE2[$centitas]?></h3>
		<?=$ringkasanUI?>
	</div>
	
	<div class="divider pb-2 mb-1 mt-2"></div>
	
	<div class="p-2">
		<div class="row">
			<div class="col-12">
				<h3 class="border-bottom pb-1 mb-1">Detail</h3>
				<!--begin: Datatable -->				
				<table id="dt" class="table table-sm table-bordered" width="100%">
					<thead>
						<tr>
							<th>Entitas</th>
							<th>ID</th>
							<th>Pelatihan</th>
							<th>Tgl Mulai</th>
							<th>Nama</th>
							<th>Level</th>
							<th>Harga</th>
							<th>JPL</th>
							<th>Status Pelatihan</th>
						</tr>
					</thead>
					<tbody>
						<?=$ui;?>
					</tbody>
				</table>
				<!--end: Datatable -->
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
var datatable = null;
window.onload = function() {
	datatable = $('#dt').DataTable({
		responsive: true,
		dom: 'Brftipl',
		order: [[3, 'asc']],
		language: {
			searchPlaceholder: "masukkan pelatihan/nik/nama/level"
		},
		columnDefs: [
			{ "searchable": false, "targets": 0 },
			{ "searchable": true, "targets": 1 },
			{ "searchable": true, "targets": 2 },
			{ "searchable": true, "targets": 3 },
			{ "searchable": true, "targets": 4 },
			{ "searchable": false, "targets": 5 },
			{ "searchable": false, "targets": 6 },
			{ "searchable": false, "targets": 7 },
		],
        buttons: [
			{ extend: 'excel', className: "btn btn-primary", text: 'Download',  filename: 'realisasi_wallet_<?=$centitas?>' }],
	});
	
	$('.dataTables_filter input[type="search"]').css(
		{'width':'350px','display':'inline-block'}
	);
}
</script>