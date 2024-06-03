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

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
										<th class="text-center">Kode</th>
                                        <th class="text-center">Nama Pelatihan</th>
                                        <th class="text-center">Jumlah Pengajuan</th>
										<th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
								<tbody>
								<?php
								// daftar kelas yg perlu diapprove
								$addSql = "";
								if(!empty($group_id)) {
									$addSql .= " and m.group_id='".$group_id."' ";
								}
								
								$i = 0;
								$uiL = '';
								$sql =
									"select c.id as id_pelatihan, c.kode, c.nama, c.tgl_mulai, c.tgl_selesai, count(p.id) as jumlah,
									 GROUP_CONCAT(DISTINCT(g.group_name)) AS group_list
									 from _learning_wallet_pengajuan p, _learning_wallet_classroom c, _member m, _group g
									 where
										p.id_lw_classroom=c.id and p.id_member=m.member_id and p.tahun='".$tahun_terpilih."' and c.status='aktif' and p.status='aktif' and p.is_final_sdm='0'
										and g.group_id=m.group_id
										".$addSql."
									 group by c.id
									 order by c.tgl_mulai ";
								$res = $this->db->query($sql);
								$row = $res->result_array();
								foreach($row as $key => $val) {
									$i++;
									$uiL .=
										'<tr>
											<td>'.$i.'</td>
											<td>'.$val['kode'].'</td>
											<td>
												'.$val['nama'].'<br/>
												'.parseDateShortReadable($val['tgl_mulai']).' sd '.parseDateShortReadable($val['tgl_selesai']).'
											</td>
											<td>'.$val['jumlah'].' orang</td>
											<td><a href="'.base_url('/learning_wallet_entitas/approval_detail/'.$tahun_terpilih.'/'.$group_id.'/'.$val['id_pelatihan']).'">tindaklanjuti</a></td>
										 </tr>
										 <tr>
											<td colspan="5">pendaftar: '.$val['group_list'].'</td>
										 </tr>';
								}
								
								if(empty($uiL)) {
									$uiL = '<tr><td colspan="4" class="text-center">Tidak ada data yang perlu ditindaklanjuti.</td></tr>';
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