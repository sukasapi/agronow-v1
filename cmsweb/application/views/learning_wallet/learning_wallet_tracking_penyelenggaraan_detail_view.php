<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
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
                <a href="<?php echo site_url("learning_wallet/tracking_penyelenggaraan"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="container">
				<div class="row">
					<div class="col-12">
						<?php
						$attributes = array('autocomplete'=>'off','method'=>'post','id'=>'form_sp');
						echo form_open($form_action, $attributes);
						?>
						<!-- START PORTLET -->
						<div class="kt-portlet">
							<div class="kt-portlet__body">
								
								<div class="form-group">
									<table class="table table-sm">
										<tr>
											<td style="width:25%">Kode Pelatihan</td>
											<td> <?=$data_kelas['kode']?></td>
										</tr>
										<tr>
											<td>Nama Pelatihan</td>
											<td><?=$data_kelas['nama']?><input type="hidden" id="nama_kelas" value='<?=$data_kelas['nama']?>'></td>
										</tr>
										<tr>
											<td>tanggal Pelatihan</td>
											<td><?=parseDateReadable($data_kelas['tgl_mulai']).' sd '.parseDateReadable($data_kelas['tgl_selesai'])?>
												<input type="hidden" id="tgl_kelas" value='<?=parseDateReadable($data_kelas['tgl_mulai'])?>'>
											</td>
										</tr>
										<tr>
											<td>Kuota Minimal</td>
											<td><?=$data_kelas['minimal_peserta']?></td>
										</tr>
										<tr>
											<td>Jumlah Disetujui</td>
											<td><?=$jumlah_disetujui?></td>
										</tr>
										<tr>
											<td>Jumlah Menunggu Persetujuan</td>
											<td><?=$jumlah_menunggu?></td>
										</tr>
										<tr>
											<td>Kuota Tercapai?</td>
											<td><?=$tercapai?></td>
										</tr>
									</table>
								</div>
								
								<div class="form-group">
									<label>Status Penyelenggaraan <span class="text-danger">*</span></label>
									<select class="form-control kt-input" name="sp">
										<option value="0"></option>
										<?php
										foreach($arrSP as $key => $val) {
											$seld = ($request['sp']==$key)? 'selected' : '';
											echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
										}
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="catatan">Catatan</label>
									<textarea class="form-control" id="catatan" name="catatan" rows="3"><?=$request['catatan']?></textarea>
								</div>
								
							</div>

							<div class="kt-portlet__foot">
								<div class="kt-form__actions kt-form__actions--solid">
									<div class="row">
										<div class="col-lg-12">
											<?=$btn_simpan?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- END PORTLET -->

						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="kt-portlet">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Daftar Peserta
									</h3>
								</div>
							</div>
							<div class="kt-portlet__body">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab">Menunggu Persetujuan / Dibatalkan Sistem</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="disetujui-tab" data-toggle="tab" href="#disetujui" role="tab">Disetujui</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="status_lain-tab" data-toggle="tab" href="#status_lain" role="tab">Status Lainnya</a>
									</li>
								</ul>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active" id="waiting" role="tabpanel">
										<?php
										$attributes = array('autocomplete'=>'off','method'=>'post','id'=>'form_usp');
										echo form_open($form_action_usp, $attributes);
										?>
										<div class="table-responsive">
											<table class="table table-sm table-bordered" id="tbmenunggu">
												<thead>
													<tr>
														<th>No</th>
														<th>Group</th>
														<th>NIK Karyawan</th>
														<th>Nama Karyawan</th>
														<th>Whatsapp</th>
														<th>Status Persetujuan</th>
													</tr>
												</thead>
												<tbody>
													<?=$peserta_ui?>
												</tbody>
												<?=$usp_simpan_ui?>
											</table>
										</div>
										<?php echo form_close(); ?>
									</div>
									<div class="tab-pane fade" id="disetujui" role="tabpanel">
										<div class="table-responsive">
												<table class="table table-sm table-bordered" id="tbsetuju">
													<thead>
														<tr>
															<th>No</th>
															<th>Group</th>
															<th>NIK Karyawan</th>
															<th>Nama Karyawan</th>
															<th>Whatsapp</th>
															<th>Aksi</th>
														</tr>
													</thead>
													<tbody>
														<?=$peserta_disetujui_ui?>
													</tbody>
												</table>
										</div>
									</div>
									<div class="tab-pane fade" id="status_lain" role="tabpanel">
										<div class="table-responsive">
											<table class="table table-sm table-bordered" id="tblain">
												<thead>
													<tr>
														<th>No</th>
														<th>Group</th>
														<th>NIK Karyawan</th>
														<th>Nama Karyawan</th>
														<th> Whatsapp</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<?=$peserta_status_lain_ui?>
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
			
        </div>
    </div>
    <!-- end:: Content -->
</div>

<script>
function batal(id_pengajuan,nik) {
	var flag = confirm('Anda yakin ingin membatalkan keikutsertaan peserta dengan nik '+nik+'? Data tidak dapat dikoreksi kembali');
	if(flag==false) {
		// do nothing
	} else {
		window.location.href = '<?=$url_batal?>/'+id_pengajuan;
	}
}

function ganti(id_pengajuan,nik) {
	window.location.href = '<?=$url_ganti?>/'+id_pengajuan;
}

function konfirm(ele) {
	var flag = confirm("Anda yakin ingin menyimpan data?");
	if(flag==false) return ;
	else $("#"+ele).submit();
}
</script>

<script>
	$(document).ready(function () {
		var judul= $("#nama_kelas").val() +" \n tanggal mulai :" + $("#tgl_kelas").val();
		$("#tbsetuju").DataTable({
			dom: 'Bfrtlpi',
			buttons: [
				{
                extend: 'excel',
				title: judul
            	},
				{
                extend: 'pdf',
				title: judul
            },

			]
		});
		$("#tbmenunggu").DataTable({
			dom: 'Bfrtlpi',
			buttons: [
				{
                extend: 'excel',
				title: judul
            	},
				{
                extend: 'pdf',
				title: judul
            },

			]
		});
		$("#tblain").DataTable({
			dom: 'Bfrtlpi',
			buttons: [
				{
                extend: 'excel',
				title: judul
            	},
				{
                extend: 'pdf',
				title: judul
            },

			]
		});
	})
</script>