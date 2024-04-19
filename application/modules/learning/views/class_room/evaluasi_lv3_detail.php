<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    table.prelearningtable{
        font-size: 14px;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
		<div class="p-3">
			<?php
			$juml = count($row);
			if($juml<=0) {
				echo '<div class="text-center">Data tidak ditemukan / Akses telah ditutup.</div>';
			} else {
				function genSelectUI($kat,$jenis_pertanyaan,$seld,$id_soal,$enable_opsi_jawaban,$tgl_selesai_pelatihan) {
					// opsi jawaban
					$arrOJ = array();
					$arrOJ['skill'][0] = '';
					$arrOJ['skill'][1] = 'belum berkembang';
					$arrOJ['skill'][2] = 'mulai (tahap awal) berkembang';
					$arrOJ['skill'][3] = 'dalam proses perkembangan';
					$arrOJ['skill'][4] = 'berkembang sesuai harapan';
					$arrOJ['skill'][5] = 'berkembang dengan sangat baik';
					
					$arrOJ['attitude'][0] = '';
					$arrOJ['attitude'][1] = 'sangat kurang';
					$arrOJ['attitude'][2] = 'kurang';
					$arrOJ['attitude'][3] = 'cukup';
					$arrOJ['attitude'][4] = 'baik';
					$arrOJ['attitude'][5] = 'sangat baik';
					
					$arrOJ['behaviour'][0] = '';
					$arrOJ['behaviour'][1] = 'tidak menunjukkan perilaku';
					$arrOJ['behaviour'][2] = 'menunjukkan perilaku saat diinstruksikan/diingatkan';
					$arrOJ['behaviour'][3] = 'muncul minimal atau masih memerlukan bimbingan';
					$arrOJ['behaviour'][4] = 'muncul dengan konsisten dan mandiri';
					$arrOJ['behaviour'][5] = 'menunjukkan perilaku penuh komitmen bahkan pada kondisi sulit';
					
					$ui = '';
					$seld = (int) $seld;
					$arr_opsi = $arrOJ[$jenis_pertanyaan];
					$prefix_ele = "jaw_".$jenis_pertanyaan."_".$kat;
					
					$label = ($kat=="pre")? 'Sebelum Pelatihan' : 'Pasca Pelatihan';
					
					$opsi_jawaban = '';
					foreach($arr_opsi as $key => $val) {
						$attr = ($seld==$key)? 'selected' : '';
						$opsi_jawaban .= '<option '.$attr.' value="'.$key.'">'.$val.'</option>';
					}
					
					if($enable_opsi_jawaban) {
						$opsi_jawaban =
							'<select class="form-control" name="jaw_'.$kat.'['.$jenis_pertanyaan.']['.$id_soal.']" id="'.$prefix_ele.$id_soal.'" value="1">
								'.$opsi_jawaban.'
							</select>';
					} else {
						$opsi_jawaban = '<br/>opsi jawaban tidak muncul karena pelatihan baru selesai pada tanggal '.$tgl_selesai_pelatihan;
					}
					
					$ui .=
						'<div class="form-group">
								<label for="'.$prefix_ele.$id_soal.'">'.$label.'</label>
								'.$opsi_jawaban.'
							</div>';
					
					return $ui;
				}
				
				$nama_pelatihan = $row[0]['cr_name'];
				$deskripsi_pelatihan = nl2br($row[0]['deskripsi_pelatihan']);
				$tujuan_pelatihan = nl2br($row[0]['tujuan_pelatihan']);
				$tgl_pelatihan = $this->function_api->date_indo($row[0]['cr_date_start'],'').' sd '.$this->function_api->date_indo($row[0]['cr_date_end'],'');
				$tgl_evaluasi = $this->function_api->date_indo($row[0]['tanggal_mulai'],'datetime').' sd '.$this->function_api->date_indo($row[0]['tanggal_selesai'],'datetime');
				
				// detail penilai dan dinilai
				$this->member_model->recData['memberId']= $row[0]['id_penilai'];
				$detailPenilai = $this->member_model->select_member("byId");
				$nama_penilai = $detailPenilai['member_name'];
				
				$this->member_model->recData['memberId']= $row[0]['id_dinilai'];
				$detailDinilai = $this->member_model->select_member("byId");
				$nama_dinilai = $detailDinilai['member_name'];
				
				$status_penilai = ucwords($row[0]['status_penilai']);
				
				$ui_pertanyaan = '';
				$ui_tombol = '';
				
				$pertanyaanUI = array();
				$pertanyaanUI['skill'] = '';
				$pertanyaanUI['attitude'] = '';
				$pertanyaanUI['behaviour'] = '';
				
				// detail pertanyaan
				$i = 0;
				$daftar_pertanyaan = json_decode($row[0]['daftar_pertanyaan'],true);
				// var_dump($daftar_pertanyaan);exit;
				foreach($daftar_pertanyaan as $key => $val) {
					foreach($val as $key2 => $val2) {
						$i++;
						
						$seld_pre = @$jaw_pre[$key][$key2];
						$seld_post = @$jaw_post[$key][$key2];
						
						$enable_opsi_jawaban_post = ($btn_final_enabled)? true : false;
						
						$opsi_jawaban_pre = genSelectUI('pre',$key,$seld_pre,$key2,true,'');
						$opsi_jawaban_post = genSelectUI('post',$key,$seld_post,$key2,$enable_opsi_jawaban_post,$this->function_api->date_indo($row[0]['cr_date_end'],''));
						
						$css = ($seld_pre<=0 || $seld_post<=0)? 'bg-danger' : 'bg-success';
						
						$pertanyaanUI[$key] .=
							'<div class="card mb-2">
								<div class="card-body p-1 m-0 '.$css.'">
									<div class="float-left">'.$i.'. '.nl2br($val2).'</div>
									<div class="d-none float-right badge badge-light">'.$key.'</div>
								</div>
								<div class="card-footer">
									'.$opsi_jawaban_pre.'
									'.$opsi_jawaban_post.'
								</div>
							</div>';
					}
				}
				
				foreach($pertanyaanUI as $key => $val) {
					if(strlen($val)<=0) {
						$pertanyaanUI[$key] =
							'<div class="mb-2 alert border border-secondary text-justify">
								Tidak ada pertanyaan untuk kategori '.strtolower($key).'.
							</div>';
					}
				}
				
				$ui_tombol = '<input class="float-left btn btn-warning" type="button" id="ss" name="ss" value="Simpan Draft"/>';
				if($btn_final_enabled) {
					$ui_tombol .= '<input class="float-right btn btn-primary" type="button" id="sf" name="sf" value="Submit"/>';
				}
				
				if($row[0]['progress']>=100) $ui_tombol = '<div class="text-center">evaluasi sudah disubmit</div>';
			?>
			
			<form action="" id="dform" method="post" class="form-horizontal">
			
			<?php
			if(strlen($strError)>0) {
			?>
			<div class="mb-2 alert alert-danger">Tidak dapat menyimpan data:<br/><ul><?=$strError?></ul></div>
			<?php
			}
			?>
			
			<div class="mb-2 alert border border-primary text-justify">
				Saudara diminta mengisi survei untuk mengevaluasi apakah terjadi perubahan perilaku (muncul perilaku kerja yang diharapkan) pada <b><?=$nama_dinilai?></b> pasca mengikuti program pelatihan <b><?=$nama_pelatihan?></b> yang diselenggarakan pada tanggal <?=$tgl_pelatihan?>.
			</div>
			
			<div class="mb-2 alert alert-primary">
				Panduan penilaian:<br/>
				<ul>
					<li>Perhatikan tanggal pelaksanaan evaluasi. Evaluasi hanya dapat dilakukan sesuai tanggal yang telah ditentukan.</li>
					<li>Setiap pertanyaan terdiri dari dua kolom penilaian: sebelum pelatihan dan pasca pelatihan.</li>
					<li>Aitem pertanyaan yang belum dijawab berwarna merah, aitem pertanyaan yang sudah dijawab berwarna hijau.</li>
					<li>Klik tombol <b>Simpan Draft</b> apabila Saudara masih ingin mengupdate jawaban.</li>
					<li>Klik tombol <b>Submit</b> apabila Saudara sudah yakin dengan jawaban yang dipilih. Data yang sudah disubmit tidak dapat diperbaiki (simpan final).</li>
					<li>Opsi jawaban untuk <b>Pasca Pelatihan</b> dan tombol <b>Submit</b> akan muncul satu hari setelah pelatihan selesai dilakukan.</li>
				</ul>
			</div>
			
			<table class="mb-2 table table-bordered mt-2 prelearningtable">
				<tbody>
					<tr>
						<td>Nama Penilai</td>
						<td>
							<?=$nama_penilai?>
						</td>
					</tr>
					<tr>
						<td>Status Penilai</td>
						<td>
							<?=$status_penilai?>
						</td>
					</tr>
					<tr>
						<td>Nama Dinilai</td>
						<td>
							<?=$nama_dinilai?>
						</td>
					</tr>
					<tr>
						<td>Tanggal Evaluasi</td>
						<td>
							<?=$tgl_evaluasi?>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class="border border-secondary rounded p-1 mb-2">
				<b>Deskripsi pelatihan</b>:<br/>
				<?=$deskripsi_pelatihan?>
			</div>
			
			<div class="border border-secondary rounded p-1 mb-2">
				<b>Tujuan pelatihan</b>:<br/>
				<?=$tujuan_pelatihan?>
			</div>
			
			<div class="pt-0">
				<div class="border border-secondary rounded mb-2">
					<div class="text-center text-primary mt-1 mb-1">pilih kategori dibawah ini terlebih dahulu</div>
					<ul class="nav nav-pills justify-content-center mb-1" role="tablist">
						<li class="nav-item">
							<a class="mb-1 mr-1 border border-primary nav-link" data-toggle="tab" href="#skill" role="tab">
								SKILL
							</a>
						</li>
						<li class="nav-item">
							<a class="mb-1 mr-1 border border-primary nav-link" data-toggle="tab" href="#attitude" role="tab">
								ATTITUDE
							</a>
						</li>
						<li class="nav-item">
							<a class="mb-1 mr-1 border border-primary nav-link" data-toggle="tab" href="#behaviour" role="tab">
								BEHAVIOUR
							</a>
						</li>
						<li class="nav-item">
							<a class="mb-1 mr-1 border border-primary nav-link" data-toggle="tab" href="#saran" role="tab">
								SARAN
							</a>
						</li>
					</ul>
				</div>
				
				<div class="tab-content">
					<div class="tab-pane fade show" id="skill" role="tabpanel"><?=$pertanyaanUI['skill']?></div>
					<div class="tab-pane fade show" id="attitude" role="tabpanel"><?=$pertanyaanUI['attitude']?></div>
					<div class="tab-pane fade show" id="behaviour" role="tabpanel"><?=$pertanyaanUI['behaviour']?></div>
					<div class="tab-pane fade show" id="saran" role="tabpanel">
						<div class="mb-2 alert border border-secondary text-justify">
							<label>saran/masukkan untuk <?=$nama_dinilai?></label>
							<textarea class="form-control" name="saran" rows="6" required><?=$saran?></textarea>
						</div>
					</div>
				</div>
			</div>
			
			<div class="card">
				<div class="card-footer">
					<input type="hidden" id="act" name="act" value=""/>
					<?=$ui_tombol?>
				</div>
			</div>
			
			</form>
			
			<?php
			}
            ?>
		</div>
    </div>
</div>
<!-- * App Capsule -->