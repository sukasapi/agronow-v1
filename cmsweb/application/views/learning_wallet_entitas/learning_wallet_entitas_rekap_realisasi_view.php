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
					$update_teks = '<div class="text-small text-danger mt-1">* pilih entitas terlebih dahulu untuk merekap data</div>';
					if(!empty($group_id)) {
						$update_css = 'btn-info';
						$update_url = site_url("learning_wallet_entitas/do_rekap_realisasi?group_id=".$group_id.'&tahun='.$tahun_terpilih);
						$update_teks = '';
					}
					?>
                    <div class="kt-portlet__body" id="body-filter">
                        <div class="row">
                            <div class="col-xl-12 text-center">
                                <a href="<?=$update_url?>" class="btn <?=$update_css?> kt-margin-l-10" onclick="return confirm('Anda yakin merekap data, proses mungkin membutuhkan waktu yang lama?')">
									Rekap Penggunaan AgroWallet <?=$form_opt_group[$group_id]?> Tahun <?=$tahun_terpilih?>
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
                                        <th class="text-center">Last Update</th>
                                    </tr>
                                </thead>
								<tbody>
								<?php
								$total_all = 0;
								$i = 0;
								$uiL = '';
								
								$sql = "select * from _group where group_status='active' and id_klien='".$id_klien."' order by group_name";
								$res = $this->db->query($sql);
								$row = $res->result_array();
								foreach($row as $key => $val) {
									$i++;
									$last_update = '';
									
									$sql2 = "select last_update from _learning_wallet_serapan where tahun='".$tahun_terpilih."' and id_group='".$val['group_id']."' limit 1";
									$res2 = $this->db->query($sql2);
									$row2 = $res2->result_array();
									
									$uiL .=
										'<tr>
											<td>'.$i.'</td>
											<td>'.$val['group_name'].'</td>
											<td>'.$row2[0]['last_update'].'</td>
										 </tr>';
								}
								
								echo $uiL;
								?>
								</tbody>
                            </table>
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