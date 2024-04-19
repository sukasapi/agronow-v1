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
							<label>Group</label>
							<select class="form-control kt-input" name="group_id">
								<option value="0"></option>
								<?php
								foreach($rowG as $key => $val) {
									$seld = ($request['group_id']==$val['group_id'])? 'selected' : '';
									echo '<option value="'.$val['group_id'].'" '.$seld.'>'.$val['group_name'].'</option>';
								}
								?>
							</select>
						</div>
						
						<div class="col-12 mt-2">
							<label>Access Key</label>
							<input type="text" name="key" class="form-control kt-input" id="key" value="<?=$request['key']?>" autocomplete="false">
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
								if($request['group_id']<1 || empty($request['key'])) {
									echo 'Pilih group dan access key terlebih dahulu.';
								} else if($request['access_key_ok']==false) {
									echo 'access key tidak dikenal.';
								} else {
									echo 'Semua karyawan telah selesai melakukan evaluasi pelatihan level 3.';
								}
							} else {
								if($is_show_all=="true") echo '<div>Total Data: '.$total_data.'</div>';
							?>
							
							<table class="table">
								<tr>
									<td style="width:1%">No</td>
									<td>Group</td>
									<td>NIK&nbsp;Penilai</td>
									<td>Nama&nbsp;Penilai</td>
									<td>Status&nbsp;Penilai</td>
									<td>NIK&nbsp;Dinilai</td>
									<td>Nama&nbsp;Dinilai</td>
									<td>Nama&nbsp;Kelas</td>
									<td>Tgl&nbsp;Selesai&nbsp;Evaluasi</td>
									<td style="width:1%">Progress</td>
								</tr>
								
								<?php
								$i = 0;
								foreach($row as $key => $val) {
									$i++;
									$arrP = $this->member_model->gets($val['id_penilai']);
									$arrD = $this->member_model->gets($val['id_dinilai']);
								?>
								
								<tr>
									<td><?=$i?></td>
									<td><?=$val['group_name']?></td>
									<td><?=$arrP[0]['member_nip']?></td>
									<td><?=$arrP[0]['member_name']?></td>
									<td><?=$val['status_penilai']?></td>
									<td><?=$arrD[0]['member_nip']?></td>
									<td><?=$arrD[0]['member_name']?></td>
									<td><?=$val['cr_name']?></td>
									<td><?=$val['tanggal_selesai']?></td>
									<td><?=$val['progress']?>%</td>
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


