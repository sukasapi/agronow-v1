<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url('classroom/evaluasi_lv3_list'); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">

        <?php
        $attributes = array('autocomplete'=>"off");
        echo form_open_multipart($form_action, $attributes);
        ?>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-12">
				<!-- START PORTLET IMPORT -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Upload File
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
					
						<div class="alert alert-outline-info fade show" role="alert">
							<div class="alert-text">
								<ul class="p-0 m-0">
									<li>Pastikan setup penilai selesai dilakukan dan telah diperiksa sebelum jadwal evaluasi dimulai untuk menghindari masalah yang timbul, misal data pengukuran yang menjadi terhapus karena terjadi perubahan NIK penilai yg sebenarnya typo.</li>
									<li>Karyawan dinilai harus berasal dari group karyawan tersebut.</li>
									<li>Template hanya berisi daftar nama karyawan yang akan dinilai dan tidak menyimpan data penilai.<!--karena 1 atasan disalin ke N kelas sesuai dengan jumlah kelas yang diikuti karyawan sehingga menyulitkan ketika trace-back--></li>
									<li>Gunakan google drive jika ingin menyimpan salinan berkas yang diupload.</li>
								</ul>
							</div>
						</div>
						
						<div class="row">
							<div class="col-6">
								<fieldset class="form-group border pl-3 pr-3">
									<legend class="w-auto px-2">Download</legend>
									
									<div class="col-12">
										<label>Group</label>
										<select class="form-control kt-input" name="group_id" id="group_id">
											<option value="0"></option>
											<?php
											foreach($rowG as $key => $val) {
												$seld = ($request['group_id']==$val['group_id'])? 'selected' : '';
												echo '<option value="'.$val['group_id'].'" '.$seld.'>'.$val['group_name'].'</option>';
											}
											?>
										</select>
									</div>
									
									<div class="col-12 mt-3">
										<p class="text-center">
											<a class="btn btn-sm btn-outline-info" id="dl_url" href="">
												<i class="fa fa-download"></i><span id="dl_teks"></span>
											</a>
										</p>
									</div>
								</fieldset>
							</div>
							
							<div class="col-6">
								<fieldset class="form-group border p-3">
									<legend class="w-auto px-2">Upload</legend>
									
									<div class="col-12">
										<label class="mt-3">File (.xlsx) *</label>
										<br>
										<input class="form-control" required type="file" id="files" name="file" accept="application/vnd.ms-excel, application/x-csv, text/x-csv, text/csv, application/csv, application/excel, application/vnd.msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
									</div>
									
									<div class="col-12 mt-3">
										 <button type="submit" class="btn btn-info pl-5 pr-5">Upload</button>
									</div>
									
								</fieldset>
							</div>
						</div>
                    </div>

                    <!--
					<div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-outline-info pl-5 pr-5">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
					-->

                </div>
                <!-- END PORTLET IMPORT -->
				
            </div>


        </div>

        <?php echo form_close(); ?>

    </div>


</div>



<script type="text/javascript">
	function setupUI() {
		var id = $('#group_id option:selected').val();
		var teks = $('#group_id option:selected').text();
		var url = '';
		
		id = parseInt(id);
		if(id>0) {
			teks = 'Unduh Template Peserta <br/>Entitas '+teks;
		} else {
			teks = 'Unduh Template Peserta dari Semua Entitas';
		}
		
		url = '<?=$url_dl?>/'+id;
		
		$('#dl_url').prop("href", url);
		$('#dl_teks').html(teks);
	}

    $(document).ready(function() {
		setupUI();
		$('#group_id').change(function(){
			setupUI();
		});
    });
</script>



<!--end::Page Resources -->