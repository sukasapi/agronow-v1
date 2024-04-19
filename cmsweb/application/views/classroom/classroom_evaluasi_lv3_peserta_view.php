<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url('classroom/evaluasi_lv3/'.$classroom['cr_id']); ?>" class="btn kt-subheader__btn-primary">
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

                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Daftar Peserta
                            </h3>
                        </div>
						<!--
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a id="btnSyncAghris" href="#" class="m-1 btn btn-outline-brand btn-bold btn-sm">
                                   Ambil Atasan dari AGHRIS
                                </a>
                            </div>
                        </div>
						-->
                    </div>

                    <div class="kt-portlet__body">
					
						<div class="alert alert-outline-info fade show" role="alert">
							<div class="alert-text">
								<ul class="p-0 m-0">
									<!--<li>Klik tombol <b>Ambil Atasan dari AGHRIS</b> untuk mengambil data atasan langsung peserta pelatihan yang datanya terhubung dengan AGHRIS.</li>-->
									<li>Tekan Ctrl+F kemudian ketik <b>(akosong)</b> untuk mencari nama yang belum memiliki penilai atasan.</li>
									<!--<li>Tekan Ctrl+F kemudian ketik <b>(kkosong)</b> untuk mencari nama yang belum memiliki penilai kolega.</li>-->
									<li>Atasan<!--/Kolega--> tidak ditemukan? <a class="ml-2 btn btn-sm btn-primary" target="_blank" href="<?=site_url('member/')?>">Tambahkan</a></li>
									<li>Atasan kosong: <span id="akosong"></span> data.</li>
									<!--<li>Kolega kosong: <span id="kkosong"></span> data.</li>-->
									<li>Ingin update data penilai secara massal? Gunakan fitur <b>Setup Penilai All</b> yang ada di halaman <?=site_url('classroom/evaluasi_lv3_list')?></li>
								</ul>
							</div>
						</div>

                        <?php
						$juml = count($row);
						$akosong = 0;
						$kkosong = 0;
						$css = '';
						if($juml<=0) {
							$css = 'd-none';
							echo 'data peserta tidak ditemukan';
						} else {
							$css = (is_classroom_editable($classroom['cr_id'])?'':'d-none');
						?>
							<table class="table">
								<tr>
									<td style="width:1%">No</td>
									<td>Nama</td>
									<td>Grade</td>
								</tr>
								
								<?php
								$i = 0;
								foreach($row as $key => $val) {
									$i++;
				
									$jsonCrmStep = preg_replace('/[[:cntrl:]]/', '', $val['crm_step']);
									$crm_step = json_decode($jsonCrmStep,TRUE);
									
									$atasan = '';
									$kolega = '';
									
									$label_atasan = '';
									
									// ada atasan/kolega?
									
									$url_remove = site_url('classroom/evaluasi_lv3_remove_picker/'.$classroom['cr_id']);
									
									$sqlC =
										"select p.id, p.status_penilai, m.member_name, m.member_nip
										 from _classroom_evaluasi_lv3_pairing p, _member m
										 where p.id_penilai=m.member_id and p.cr_id='".$classroom['cr_id']."' and p.id_dinilai='".$val['member_id']."'
										 order by p.status_penilai, m.member_name ";
									$resC = $this->db->query($sqlC);
									$rowC = $resC->result_array();
									foreach($rowC as $keyC => $valC) {
										$status_penilai = $valC['status_penilai'];
										
										$ui_member = '<a title="Remove" href="'.$url_remove.'/'.$valC['id'].'" class="btn text-danger '.(is_classroom_editable($classroom['cr_id'])?'':'d-none').'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash"></i></a> '.$valC['member_name'].' ['.$valC['member_nip'].']<br/>';
										
										if($status_penilai=="atasan") $atasan .= $ui_member;
										else if($status_penilai=="kolega") $kolega .= $ui_member;
									}
									
									$juml_empty = 0;
									if(empty($atasan)) {
										// $atasan = '(akosong) <a href="'.site_url('classroom/evaluasi_lv3_sync_atasan_aghris/'.$classroom['cr_id'].'/'.$val['member_id']).'">(ambil dari aghris)</a>';
										$atasan = '(akosong)';
										$juml_empty++;
										$akosong++;
										$label_atasan = 'tambah';
									} else {
										$label_atasan = 'update';
									}
									if(empty($kolega)) {
										$kolega = '(kkosong)';
										$juml_empty++;
										$kkosong++;
									}
									
									$css_tr = 'table-info';
									if($juml_empty==1) $css_tr = 'table-warning';
									else if($juml_empty==2) $css_tr = 'table-danger';
								?>
									<tr class="<?=$css_tr?>">
										<td><?=$i?></td>
										<td>
											<?=$val['member_name'].' ['.$val['member_nip'].']<br/>'.$val['group_name']?>
										</td>
										<td><?=@$crm_step['RESULT']?></td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="row">
												<div class="col-6">Atasan:</div>
												<div class="col-6 text-right">
													<a href="#" data-backdrop="static" class="btn_pairing <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>"
														data-remote="<?=site_url('member/l_modal_ajax')?>"
														data-toggle="modal"
														data-target="#atasan_<?=$val['member_id']?>"><?=$label_atasan?>
													</a>
												</div>
											</div>
											<div><?=$atasan?></div>
											
											<div class="modal fade" id="atasan_<?=$val['member_id']?>" tabindex="-1" role="dialog"
												 aria-labelledby="atasan<?=$val['member_id']?>PickerModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="atasan<?=$val['member_id']?>PickerModalLabel">Member</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															</button>
														</div>
														<div class="modal-body">
															<p class="text-center">
																<img src="<?=base_url('assets/media/preloader/set2/64/Preloader_4.gif')?>"/>
															</p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
															</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<!--
									<tr>
										<td colspan="3">
											<div class="row">
												<div class="col-6">Kolega:</div>
												<div class="col-6 text-right">
													<a href="#" data-backdrop="static" class="btn_pairing <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>"
														data-remote="<?=site_url('member/l_modal_ajax')?>"
														data-toggle="modal"
														data-target="#kolega_<?=$val['member_id']?>">tambah
													</a>
												</div>
											</div>
											<div><?=$kolega?></div>
											
											<div class="modal fade" id="kolega_<?=$val['member_id']?>" tabindex="-1" role="dialog"
												 aria-labelledby="kolega<?=$val['member_id']?>PickerModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="kolega<?=$val['member_id']?>PickerModalLabel">Member</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															</button>
														</div>
														<div class="modal-body">
															<p class="text-center">
																<img src="<?=base_url('assets/media/preloader/set2/64/Preloader_4.gif')?>"/>
															</p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
															</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									-->
								<?php } ?>
							</table>						
						<?php } ?>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5 <?=$css?>">Simpan</button>
                                    <a href="<?php echo site_url('classroom/evaluasi_lv3/'.$classroom['cr_id']); ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET QUESTION -->

                <?php echo form_close(); ?>

            </div>


        </div>
    </div>

</div>





<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
		$('#akosong').html('<?=$akosong."/".$juml?>');
		$('#kkosong').html('<?=$kkosong."/".$juml?>');
		
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

<script>
	var cur_peserta = '';
	$(document).ready(function() {
		$('.btn_pairing').click(function(){
			cur_peserta = $(this).attr('data-target').replace("#","");
		});
		
		$('#btnSyncAghris').click(function(event){
			event.preventDefault();
			var flag = confirm('Anda yakin ingin mengambil data dari Aghris?');
			if(flag==false) {
				return ;
			} else if(flag==true) {
				window.location.href = "<?= site_url('classroom/evaluasi_lv3_sync_atasan_aghris/'.$classroom['cr_id']) ?>";
			}
		});
	});	
	
    function addSelectedItem(selectedItems)
    {
	    // console.log(selectedItems);
        var ids = [];
        $.each(selectedItems, function( index, value ) {
            ids.push(value.member_id);
        });

        $.post("<?= site_url('classroom/evaluasi_lv3_add_picker/'.$classroom['cr_id']); ?>", {member_ids: ids,kat: cur_peserta}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' penilai.');
            location.reload();
        });
    }
</script>