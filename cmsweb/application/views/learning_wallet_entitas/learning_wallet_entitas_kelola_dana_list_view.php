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
                                            <label>Group</label>
                                            <?php
                                            $selected_value = $group_id!=NULL ? $group_id : '';

                                            $attr = 'class="form-control" id="group_id"';
                                            echo form_dropdown('group_id', $form_opt_group, $selected_value, $attr);

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
                <div class="kt-portlet kt-portlet--head-sm">
					
					<?
					$update_css = 'btn-secondary';
					$update_url = 'javascript:void(0)';
					$update_teks = '<div class="text-small text-danger mt-1">* pilih entitas terlebih dahulu untuk mengupdate data</div>';
					if(!empty($group_id)) {
						$update_css = 'btn-info';
						$update_url = site_url("learning_wallet_entitas/kelola_dana_update?group_id=".$group_id.'&tahun='.$tahun_terpilih);
						$update_teks = '';
					}
					?>
                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">
                            <div class="col-xl-12 text-center">
                                <a href="<?=$update_url?>" class="btn <?=$update_css?> kt-margin-l-10">
									Update Dana Pengembangan <?=$form_opt_group[$group_id]?> Tahun <?=$tahun_terpilih?>
								</a>
								<?=$update_teks?>
                            </div>
                        </div>
                    </div>
					
                </div>
            </div>
			
            <div class="col-xl-12">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Level Karyawan</th>
										<th class="text-center">Jumlah Karyawan</th>
										<th class="text-center">Nominal per Karyawan (Rp.)</th>
										<th class="text-center">Nominal Total (Rp.)</th>
                                    </tr>
                                </thead>
								<tbody>
								<?php
								$total_all = 0;
								$i = 0;
								$uiL = '';
								
								if(empty($group_id)) {
									foreach($arr_group as $key => $val) {
										$i++;
										$jumlah = '';
										$nilai = '';
										
										// jumlah
										$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$val['group_id']."' and nama='total_jumlah_karyawan' ";
										$res2 = $this->db->query($sql2);
										$row2 = $res2->result_array();
										$jumlah = $row2[0]['nilai'];
										if(empty($jumlah)) {
											$jumlah = 0;
										}
										
										// nominal
										$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$val['group_id']."' and nama='total_dana_pengembangan' ";
										$res2 = $this->db->query($sql2);
										$row2 = $res2->result_array();
										$nilai = $row2[0]['nilai'];
										
										$total_all += $nilai;
										
										$uiL .=
											'<tr>
												<td>'.$i.'</td>
												<td>'.$val['group_name'].'</td>
												<td>all</td>
												<td>'.$jumlah.'</td>
												<td>-</td>
												<td>'.number_format($nilai,2,',','.').'</td>
											 </tr>';
									}
								} else {
									$sql = "select * from _member_level_karyawan where status='active' and id_klien='".$id_klien."' order by nama";
									$res = $this->db->query($sql);
									$row = $res->result_array();
									foreach($row as $key => $val) {
										$i++;
										$jumlah = '';
										$nilai = '';
										
										// jumlah
										$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='juml_kary_".$key."' ";
										$res2 = $this->db->query($sql2);
										$row2 = $res2->result_array();
										$jumlah = $row2[0]['nilai'];
										if(empty($jumlah)) {
											$jumlah = 0;
										}
										
										// nominal
										$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun_terpilih."' and kategori='group' and id_group='".$group_id."' and nama='lv_kary_".$key."' ";
										$res2 = $this->db->query($sql2);
										$row2 = $res2->result_array();
										$nilai = $row2[0]['nilai'];
										
										$total_a = $jumlah * $nilai;
										$total_all += $total_a;
										
										$uiL .=
											'<tr>
												<td>'.$i.'</td>
												<td>'.$form_opt_group[$group_id].'</td>
												<td>'.$val['nama'].'</td>
												<td>'.$jumlah.'</td>
												<td>'.number_format($nilai,2,',','.').'</td>
												<td>'.number_format($total_a,2,',','.').'</td>
											 </tr>';
									}
								}
								echo $uiL;
								?>
								</tbody>
                            </table>
                        </div>
						<h4 class="text-center m-4"><span class="border border-primary rounded p-2">Total: Rp. <?=number_format($total_all,2,',','.')?></span></h4>
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