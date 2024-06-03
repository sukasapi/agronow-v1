<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);

$jumlM1 = 0;
$jumlM2 = 0;
$jumlM3 = 0;
$jumlM4 = 0;

$uiM1 = '';
$uiM2 = '';
$uiM3 = '';
$uiM4 = '';

$sql =
	"select w.kode, w.nama,w.tgl_mulai
	 from _learning_wallet_classroom as w left join _classroom as c 
	 on w.id=c.id_lw_classroom 
	 where 
		w.status_penyelenggaraan='jalan' and w.tahun='".$tahun_terpilih."' 
		and c.id_lw_classroom is NULL
	 order by w.tgl_mulai";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$jumlM1++;
	
	$uiM1 .=
		'<tr>
			<td>'.$jumlM1.'</td>
			<td>'.$val['kode'].'</td>
			<td>'.$val['nama'].'</td>
			<td>'.$val['tgl_mulai'].'</td>
		 </tr>';
}

$sql =
	"select
		w.kode, w.nama, w.status_penyelenggaraan, c.cr_id, c.cr_name, c.id_petugas,
		w.tgl_mulai as tgl_mulai_agrowallet, DATE_FORMAT(c.cr_date_start, '%Y-%m-%d') as tgl_mulai_agronow
	 from _learning_wallet_classroom as w left join _classroom as c 
	 on w.id=c.id_lw_classroom 
	 where
		c.cr_status='publish' and w.status_penyelenggaraan!='jalan' and w.tahun='".$tahun_terpilih."'
		and w.id=c.id_lw_classroom
	 order by w.kode";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$jumlM2++;
	
	$sqlP = "select user_name from _user where user_id='".$val['id_petugas']."' ";
	$resP = $this->db->query($sqlP);
	$rowP = $resP->result_array();
	
	$uiM2 .=
		'<tr>
			<td>'.$jumlM2.'</td>
			<td>'.$val['kode'].'</td>
			<td>'.$val['nama'].'</td>
			<td>'.$val['tgl_mulai_agrowallet'].'</td>
			<td>'.$val['status_penyelenggaraan'].'</td>
			<td>'.$val['cr_id'].'</td>
			<td>'.$val['cr_name'].'</td>
			<td>'.$val['tgl_mulai_agronow'].'</td>
			<td>'.$rowP['0']['user_name'].'</td>
		 </tr>';
}

$sql =
	"select
		w.kode, w.nama, w.jumlah_jam, c.cr_id, c.cr_name, c.cr_date_detail, c.id_petugas,
		w.tgl_mulai as tgl_mulai_agrowallet, DATE_FORMAT(c.cr_date_start, '%Y-%m-%d') as tgl_mulai_agronow
	 from _learning_wallet_classroom as w left join _classroom as c 
	 on w.id=c.id_lw_classroom 
	 where
		c.cr_status='publish' and w.tahun='".$tahun_terpilih."' 
		and w.id=c.id_lw_classroom and w.jumlah_jam!=c.cr_date_detail
	 order by w.kode";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$jumlM3++;
	
	$sqlP = "select user_name from _user where user_id='".$val['id_petugas']."' ";
	$resP = $this->db->query($sqlP);
	$rowP = $resP->result_array();
	
	$uiM3 .=
		'<tr>
			<td>'.$jumlM3.'</td>
			<td>'.$val['kode'].'</td>
			<td>'.$val['nama'].'</td>
			<td>'.$val['tgl_mulai_agrowallet'].'</td>
			<td>'.$val['jumlah_jam'].'</td>
			<td>'.$val['cr_id'].'</td>
			<td>'.$val['cr_name'].'</td>
			<td>'.$val['tgl_mulai_agronow'].'</td>
			<td>'.$val['cr_date_detail'].'</td>
			<td>'.$rowP['0']['user_name'].'</td>
		 </tr>';
}
		 
$sql =
	"select c.cr_id, c.cr_name, DATE_FORMAT(c.cr_date_end, '%Y-%m-%d') as tgl_selesai, c.id_petugas
	 from _learning_wallet_classroom as w inner join _classroom as c 
	 on w.id=c.id_lw_classroom 
	 where
		c.cr_status='publish' and w.tahun='".$tahun_terpilih."' 
		and w.id=c.id_lw_classroom and qc_member_id='0'
	 order by c.cr_date_end";
$res = $this->db->query($sql);
$row = $res->result_array();
foreach($row as $key => $val) {
	$jumlM4++;
	
	$sqlP = "select user_name from _user where user_id='".$val['id_petugas']."' ";
	$resP = $this->db->query($sqlP);
	$rowP = $resP->result_array();
	
	$pk = '';
	$sql2 =
		"select GROUP_CONCAT(m.member_name separator '<br/>') as pk
		 from _classroom_member c, _member m
		 where c.member_id=m.member_id and c.cr_id='".$val['cr_id']."' and c.is_pk='1' and c.member_status='1'
		 group by c.cr_id ";
	$res2 = $this->db->query($sql2);
	$row2 = $res2->result_array();
	$pk = $row2[0]['pk'];
	
	$css = (empty($pk))? 'bg-warning' : '';
	$durl_qc = str_replace('cmsweb/','',BASE_URL()).'learning/class_room/qc?cr_id='.$val['cr_id'];

	$uiM4 .=
		'<tr class="'.$css.'">
			<td rowspan="2">'.$jumlM4.'</td>
			<td>'.$val['cr_id'].'</td>
			<td>'.$val['cr_name'].'</td>
			<td>'.$val['tgl_selesai'].'</td>
			<td>'.$rowP['0']['user_name'].'</td>
			<td>'.$pk.'</td>
		 </tr>
		 <tr class="'.$css.'">
			<td>URL QC</td>
			<td colspan="4">'.$durl_qc.'</td>
		 </tr>';
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
			
			<div class="col-xl-12">
			
				<!-- FILTER -->
				<div class="col-xl-12">
					<div class="kt-portlet kt-portlet--head-sm">
						<div class="kt-portlet__body" id="body-filter">
							<div class="row">
								<div class="col-xl-12">
									<form class="kt-form">
										<div class="row">
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
				
				<div class="accordion mb-2" id="accordion_data">
					<div class="card">
						<div class="card-header" id="h1">
							<h2 class="mb-0">
								<button class="btn btn-primary btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#m1">
									Pelatihan AgroWallet yang statusnya diselenggarakan tetapi tidak ada data pelatihan di menu classroom AgroNow<br/>
									(<?=$jumlM1?> data)
								</button>
							</h2>
						</div>
						<div id="m1" class="collapse" data-parent="#accordion_data">
							<div class="card-body">
								<div class="table-responsive">
									<div class="alert alert-warning">
									<b>Potensi Sumber Masalah</b>
										<ul>
											<li>admin AgroNow belum membuat kelas di menu classroom AgroNow </li>
											<li>admin AgroNow salah memilih kode AgroWallet (biasanya nama pelatihan sama, tp beda kode)</li>
										</ul>
									</div>
									<table class="table table-bordered table-hover table-sm nowraps">
										<thead>
											<tr>
												<th class="text-center" width="16px">No</th>
												<th class="text-center">Kode AgroWallet</th>
												<th class="text-center">Nama Pelatihan AgroWallet</th>
												<th class="text-center">Tanggal Mulai</th>
											</tr>
										</thead>
										<tbody>
										<?=$uiM1?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="h2">
							<h2 class="mb-0">
								<button class="btn btn-primary btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#m2">
									Pelatihan AgroWallet belum tentu jadi diselenggarakan tetapi sudah ada datanya di classroom AgroNow<br/>
									(<?=$jumlM2?> data)
								</button>
							</h2>
						</div>
						<div id="m2" class="collapse" data-parent="#accordion_data">
							<div class="card-body">
								<div class="table-responsive">
									<div class="alert alert-warning">
										<b>Potensi Sumber Masalah</b>
										<ul>
											<li>admin AgroWallet belum mengubah status pelatihan menjadi diselenggarakan</li>
											<li>admin AgroNow salah memilih kode AgroWallet (biasanya nama pelatihan sama, tp beda kode)</li>
										</ul>
									</div>
									<table class="table table-bordered table-hover table-sm nowraps">
										<thead>
											<tr>
												<th class="text-center" width="16px">No</th>
												<th class="text-center">Kode AgroWallet</th>
												<th class="text-center">Nama Pelatihan AgroWallet</th>
												<th class="text-center">Tanggal Mulai</th>
												<th class="text-center">Status AgroWallet</th>
												<th class="text-center">ID AgroNow</th>
												<th class="text-center">Nama Pelatihan AgroNow</th>
												<th class="text-center">Tanggal Mulai</th>
												<th class="text-center">Pembuat</th>
											</tr>
										</thead>
										<tbody>
										<?=$uiM2?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="h3">
							<h2 class="mb-0">
								<button class="btn btn-primary btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#m3">
									Pelatihan AgroWallet yang JPL-nya beda dengan AgroNow<br/>
									(<?=$jumlM3?> data)
								</button>
							</h2>
						</div>
						<div id="m3" class="collapse" data-parent="#accordion_data">
							<div class="card-body">
								<div class="alert alert-warning">
								<b>Potensi Sumber Masalah</b>
								<ul>
									<li>JPL AgroNow seharusnya sama dengan nilai JPL AgroWallet karena JPL AgroWallet yang ditawarkan ke peserta pelatihan.</li>
									<li>Tetapi ada potensi admin AgroWallet salah mengetikkan nilai JPL AgroWallet sehingga datanya perlu sama2 dicek.</li>
								</ul>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-sm nowraps">
										<thead>
											<tr>
												<th class="text-center" width="16px">No</th>
												<th class="text-center">Kode AgroWallet</th>
												<th class="text-center">Nama Pelatihan AgroWallet</th>
												<th class="text-center">Tanggal Mulai</th>
												<th class="text-center">JPL AgroWallet</th>
												<th class="text-center">ID AgroNow</th>
												<th class="text-center">Nama Pelatihan AgroNow</th>
												<th class="text-center">Tanggal Mulai</th>
												<th class="text-center">JPL AgroNow</th>
												<th class="text-center">Pembuat</th>
											</tr>
										</thead>
										<tbody>
										<?=$uiM3?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="h4">
							<h2 class="mb-0">
								<button class="btn btn-primary btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#m4">
									Pelatihan AgroNow X AgroWallet yang belum dilakukan QC<br/>
									(<?=$jumlM4?> data)
								</button>
							</h2>
						</div>
						<div id="m4" class="collapse" data-parent="#accordion_data">
							<div class="card-body">
								<div class="alert alert-warning">
								<b>Potensi Sumber Masalah</b>
									<ul>
										<li>PIC QC data belum diatur atau belum dilakukan QC data oleh ybs.<br/>catatan: QC data dilakukan oleh PK classroom AgroNow</li>
									</ul>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-sm nowraps">
										<thead>
											<tr>
												<th class="text-center" width="16px">No</th>
												<th class="text-center">ID AgroNow</th>
												<th class="text-center">Nama Pelatihan AgroNow</th>
												<th class="text-center">Tanggal Selesai</th>
												<th class="text-center">Pembuat</th>
												<th class="text-center">PIC QC</th>
											</tr>
										</thead>
										<tbody>
										<?=$uiM4?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
        </div>
    </div>
    <!-- end:: Content -->


</div>