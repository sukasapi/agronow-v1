<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url('classroom/detail/'.$classroom['cr_id']); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Evaluasi Pelatihan Level 3<br/><small>ID: <?=$classroom['cr_id']?></small>
                            </h3>
                        </div>
						
						<div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
								<div class="<?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
									<a href="<?= site_url('classroom/evaluasi_lv3_salin/'.$classroom['cr_id']) ?>" class="m-1 btn btn-outline-brand btn-bold btn-sm">
									   Salin Dari Kelas Lain
									</a>
									
									<a href="<?= site_url('classroom/evaluasi_lv3_peserta/'.$classroom['cr_id']) ?>" class="m-1 btn btn-outline-brand btn-bold btn-sm">
									   Setup Penilai
									</a>
									<br/>
									
									<a href="<?= site_url('classroom/evaluasi_lv3_rekap/'.$classroom['cr_id']) ?>" class="m-1 btn btn-outline-brand btn-bold btn-sm">
									   Progress dan Rekap
									</a>
									
									<a href="<?= site_url('classroom/evaluasi_lv3_do_export/cr/'.$classroom['cr_id']) ?>" class="m-1 btn btn-outline-brand btn-bold btn-sm">
									   Export Hasil
									</a>
								</div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
							<div class="col-12">
                                <label>Kelompok Tahun Evaluasi *</label>
                                <input type="text" class="form-control" placeholder="" required name="tahun_evaluasi" value="<?=$request['tahun_evaluasi']?>">
                            </div>
						
                            <div class="col-12 mt-3">
                                <label>Deskripsi Pelatihan *</label>
                                <textarea name="deskripsi_pelatihan" style="min-height: 15em" required class="form-control"><?=$request['deskripsi_pelatihan']?></textarea>
                            </div>
							
							<div class="col-12">
								<label class="mt-3">Target Peserta *</label>
								<input type="text" class="form-control" placeholder="" required name="target_peserta" value="<?=$request['target_peserta']?>">
							</div>
							
							<div class="col-12">
                                <label class="mt-3">Tujuan Pelatihan *</label>
                                <textarea name="tujuan_pelatihan" style="min-height: 15em" required class="form-control"><?=$request['tujuan_pelatihan']?></textarea>
                            </div>
							
							<div class="col-12 mt-3">
							<div class="row">
								<div class="col-4">
									<label>Jangka Penilaian Evaluasi Perilaku Pasca Pelatihan *</label>
								</div>
								<div class="col-2">
									<input type="text" class="form-control" placeholder="" required name="jangka_waktu_evaluasi_jumlah" value="<?=$request['jangka_waktu_evaluasi_jumlah']?>">
								</div>
								<div class="col-3">
									<select name="jangka_waktu_evaluasi_satuan" required class="form-control">
										<option value="minggu" <?=$seld_minggu?>>Minggu</option>
										<option value="bulan" <?=$seld_bulan?>>Bulan</option>
									</select>
								</div>
							</div>
							</div>
							
							<div class="col-12 mt-3">
							<div class="row">
								<div class="col-6">
									<label>Tanggal Mulai *</label>
									<input type="text" class="form-control date-time-picker" placeholder="" required name="tanggal_mulai" value="<?=$request['tanggal_mulai']?>">
								</div>
								<div class="col-6">
									<label>Tanggal Selesai *</label>
									<input type="text" class="form-control date-time-picker" placeholder="" required name="tanggal_selesai" value="<?=$request['tanggal_selesai']?>">
								</div>
							</div>
							</div>
							
							<div class="col-12 mt-3">
							<div class="row">
								<div class="col-12">
									<label>Bobot Pertanyaan *</label>
								</div>
								<div class="col-6">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" >KSA</span>
										</div>
										<input type="number" class="form-control" placeholder="" name="bobot_soal_ksa" value="<?=$request['bobot_soal_ksa']?>">
										<div class="input-group-prepend">
											<span class="input-group-text" >%</span>
										</div>
									</div>
									<small class="form-text text-muted">
										Diisi dengan angka. KSA = Knowledge, Skill, Attitude
									</small>
								</div>
								
								<div class="col-6">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" >Behaviour</span>
										</div>
										<input type="number" class="form-control" placeholder="" name="bobot_soal_b" value="<?=$request['bobot_soal_b']?>">
										<div class="input-group-prepend">
											<span class="input-group-text" >%</span>
										</div>
									</div>
									<small class="form-text text-muted">
										Diisi dengan angka.
									</small>
								</div>
							</div>
							</div>
							
							<div class="col-12 mt-3">
							<div class="row">
								<div class="col-12">
									<label>Bobot Penilai *</label>
								</div>
								<div class="col-6">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" >Atasan</span>
										</div>
										<input type="number" class="form-control" placeholder="" name="bobot_atasan" value="<?=$request['bobot_atasan']?>">
										<div class="input-group-prepend">
											<span class="input-group-text" >%</span>
										</div>
									</div>
									<small class="form-text text-muted">
										Diisi dengan angka.
									</small>
								</div>
								
								<div class="col-6">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" >Kolega</span>
										</div>
										<input type="number" class="form-control" placeholder="" name="bobot_kolega" value="<?=$request['bobot_kolega']?>">
										<div class="input-group-prepend">
											<span class="input-group-text" >%</span>
										</div>
									</div>
									<small class="form-text text-muted">
										Diisi dengan angka.
									</small>
								</div>
							</div>
							</div>
							
							<div class="col-12">
                                <label class="mt-4">Aktifkan Pertanyaan Pasca Pelatihan dan Tombol Simpan Final?</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="simpan_final_enabled" <?=$seld_simpan_final_1?> value="1"> Iya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="simpan_final_enabled" <?=$seld_simpan_final_0?> value="0" > Tidak
                                        <span></span>
                                    </label>
                                </div>
                            </div>
							
							<div class="col-12">
                                <label class="mt-4">Status *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" <?=$seld_status_1?> value="1"> Active
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" <?=$seld_status_0?> value="0" > Non-Active
                                        <span></span>
                                    </label>
                                </div>
                            </div>
							
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/detail/'.$classroom['cr_id']); ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->


                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Pertanyaan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('classroom/evaluasi_lv3_pertanyaan_update/'.$classroom['cr_id']); ?>" class="btn btn-outline-info btn-sm  btn-icon-md <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">


                        <table class="table">
                            <thead>
                            <tr>
								<th width="1%">No</th>
                                <th>Pertanyaan</th>
                                <th width="120px"></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							$ui = '';
							if(!is_array($daftar_pertanyaan)) $daftar_pertanyaan = array();
							foreach($daftar_pertanyaan as $key => $val) {
								$ui .=
									'<tr>
										<td colspan="3"><b>'.strtoupper($key).'</b></td>
									 </tr>';
								
								$j = 0;
								foreach($val as $key2 => $val2)  {
									$j++;
									
									$aksiUI = '';
									if(is_classroom_editable($classroom['cr_id'])) {
										$aksiUI =
											'<a href="'.site_url('classroom/evaluasi_lv3_pertanyaan_update/'.$classroom['cr_id']).'/'.$key.'/'.$key2.'" class="btn btn-outline-info btn-sm ">
												Edit
											</a>

											<a href="'.site_url('classroom/evaluasi_lv3_pertanyaan_delete/'.$classroom['cr_id']).'/'.$key.'/'.$key2.'" class="btn btn-outline-danger btn-sm btn-icon ml-2" onclick="return confirm(\'Anda yakin menghapus Pertanyaan?\')" title="Hapus">
												<i class="fa fa-trash-alt"></i>
											</a>';
									}
									
									$ui .=
										'<tr>
											<td>'.$j.'</td>
											<td>'.nl2br($val2).'</td>
											<td>
												'.$aksiUI.'
											</td>
										 </tr>';
								}
							}
							echo $ui;
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END PORTLET QUESTION -->


            </div>


        </div>
    </div>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.date-time-picker').datetimepicker({
			format: "yyyy-mm-dd hh:ii",
			language: 'id',
			todayBtn: true,
			todayHighlight:true,
			autoclose: true
		});
	});
</script>


<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>