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
    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-xl-12">
			
				<?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <!-- START PORTLET -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Pencarian
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
					
                        <div class="col-12">
							<label>Kelompok Tahun Evaluasi</label>
							<select class="form-control kt-input" name="tahun_evaluasi">
								<option value="0"></option>
								<?php
								foreach($rowT as $key => $val) {
									$seld = ($request['tahun_evaluasi']==$val['tahun_evaluasi'])? 'selected' : '';
									echo '<option value="'.$val['tahun_evaluasi'].'" '.$seld.'>'.$val['tahun_evaluasi'].'</option>';
								}
								?>
							</select>
						</div>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Lihat Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET -->

                <?php echo form_close(); ?>


                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
						
						<!--begin: Datatable -->
                        <div class="table-responsive">
                            <?php
							$juml = count($row);
							$css = '';
							if($juml<=0) {
								echo 'data tidak ditemukan';
							} else {
							?>
							
							<div class="alert alert-outline-info fade show" role="alert">
								<div class="alert-text">
									<ul>
										<li>
											URL untuk memantau seluruh progress peserta:<br/>
											<b><?=$url_progress?></b>
										</li>
										<li>Setelah direkap data dapat dilihat dan diunduh melalui menu <b>Laporan > Class Room - Evaluasi Level 3</b>.</li>
									</ul>
								</div>
							</div>
							
							<div class="text-center mb-4">
								<a class="btn btn-success" href="<?=site_url('classroom/evaluasi_lv3_pairing/th/'.$request['tahun_evaluasi'])?>">Setup Penilai All</a>
								<a id="btRekap" class="btn btn-success" href="<?=site_url('classroom/evaluasi_lv3_do_rekap/th/'.$request['tahun_evaluasi'])?>">Rekap All</a>
							</div>
							
							<table class="table">
								<tr>
									<td style="width:1%">No</td>
									<td style="width:1%">ID</td>
									<td>Nama</td>
									<td style="width:1%">Aksi</td>
									<td>Konfig&nbsp;Pre</td>
									<td>Konfig&nbsp;Modul</td>
									<td>Konfig&nbsp;Post</td>
								</tr>
								
								<?php
								$i = 0;
								foreach($row as $key => $val) {
									$i++;
									
									if($val['status']=="0") $val['cr_name'] .= '&nbsp;<span class="text-danger">(non-active)</span>';
									
									// pre
									$konfig_pre = '';
									if($val['cr_has_pretest']=="1") {
										$val['cr_pretest'] = preg_replace('/[[:cntrl:]]/', '', $val['cr_pretest']);
										$arrT = json_decode($val['cr_pretest'],true);
										if(empty($arrT['Attemp'])) $arrT['Attemp'] = '<span class="text-danger">Unlimited</span>';
										
										$konfig_pre .= 'Kesempatan:&nbsp;'.$arrT['Attemp'].'<br/>SyaratLulus:&nbsp;'.$arrT['ReqPassed'].'';
									} else {
										$konfig_pre .= '<span class="text-danger">Tanpa Pre Test</span>';
									}
									
									// module
									$cr_module = preg_replace('/[[:cntrl:]]/', '', $val['cr_module']);
									$js_modul = json_decode($cr_module,true);
									
									$juml_modul_w_soal = 0;
									foreach($js_modul['Module'] as $key2 => $val2) {
										$es = $val2['Evaluasi']['Status'];
										$es = trim($es);
										if($es=="active") {
											$juml_modul_w_soal++;
										}
									}
									$konfig_modul = ($juml_modul_w_soal>0)? $juml_modul_w_soal.' modul dengan evaluasi' : 'tanpa evaluasi modul';
									
									// post
									$konfig_post = '';
									if($val['cr_has_kompetensi_test']=="1") {
										$val['cr_competency'] = preg_replace('/[[:cntrl:]]/', '', $val['cr_competency']);
										$arrT = json_decode($val['cr_competency'],true);
										if(empty($arrT['Attemp'])) $arrT['Attemp'] = '<span class="text-danger">Unlimited</span>';
										
										$konfig_post .= 'Kesempatan:&nbsp;'.$arrT['Attemp'];
									} else {
										$konfig_post .= '<span class="text-danger">Tanpa CT</span>';
									}
								?>
								
								<tr>
									<td><?=$i?></td>
									<td><?=$val['cr_id']?></td>
									<td><?=$val['cr_name']?></td>
									<td><a href="<?=site_url('classroom/evaluasi_lv3/'.$val['cr_id'])?>" target="_blank">update_data</a></td>
									<td><?=$konfig_pre?></td>
									<td><?=$konfig_modul?></td>
									<td><?=$konfig_post?></td>
								</tr>
								
								<?php } ?>
							</table>
							
							<?php
							}
							?>
                        </div>
                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>

        </div>
    </div>
    <!-- end:: Content -->


</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#btRekap").click(function(e){
			var flag = confirm('Anda yakin ingin melakukan rekap? Proses mungkin akan memakan waktu yg lama.');
			if(flag==false) {
				e.preventDefault();
				return ;
			}
			$('#dform').submit();
		});

    });
</script>


