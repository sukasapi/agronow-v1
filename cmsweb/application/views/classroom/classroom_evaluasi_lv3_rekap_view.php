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
                $attributes = array('method'=>'get','autocomplete'=>"off",'id'=>'dform');
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Progress Penilai dan Rekap Data
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <!--<a href="<?php /*echo site_url('classroom/member_add/'.$classroom['cr_id']); */?>" class="btn btn-outline-light btn-sm  btn-icon-md">
                                    <i class="flaticon2-trash"></i> Hapus
                                </a>-->
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
					
						<div class="alert alert-outline-info fade show" role="alert">
							<div class="alert-text">
								Hanya merekap data evaluasi level 3 yang sudah disubmit oleh penilai (progress 100%).
							</div>
						</div>
						
						<input type="hidden" name="rekap" value="1"/>
						
						<?php
						$uiC = '';
						$i = 0;
						foreach($rowC as $key => $val) {
							$i++;
							$arrM = $this->member_model->gets($val['id_dinilai']);
							
							$css = ($val['progress']<100)? 'text-danger' : '';
							
							$uiC .=
								'<tr>
									<td class="'.$css.'">'.$i.'</td>
									<td class="'.$css.'">'.$val['group_name'].'</td>
									<td class="'.$css.'">'.$val['member_nip'].'</td>
									<td class="'.$css.'">'.$val['member_name'].'</td>
									<td class="'.$css.'">'.$val['status_penilai'].'</td>
									<td class="'.$css.'">'.$arrM[0]['member_name'].'</td>
									<td class="'.$css.'">'.$val['progress'].'%</td>
								 </tr>';
						}
						
						$uiC =
							'<table class="table table-sm table-bordered">
								<tr>
									<td style="width:1%">No</td>
									<td>Group</td>
									<td>NIK&nbsp;Penilai</td>
									<td>Nama&nbsp;Penilai</td>
									<td>Status&nbsp;Penilai</td>
									<td>Nama&nbsp;Dinilai</td>
									<td style="width:1%">Progress</td>
								</tr>
								'.$uiC.'
							</table>';
						
						
						echo $uiC;
						?>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="button" id="btRekap" class="btn btn-success pl-5 pr-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">Rekap</button>
                                    <a href="<?php echo site_url('classroom/evaluasi_lv3/'.$classroom['cr_id']); ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET QUESTION -->

                <?php echo form_close(); ?>
				
				<div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Log Rekap Data
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
						<?=$log_rekap?>
                    </div>
                </div>

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
		
		$("#btRekap").click(function(){
			var flag = confirm('Anda yakin ingin melakukan rekap? Proses mungkin akan memakan waktu yg lama.');
			if(flag==false) {
				return ;
			}
			$('#dform').submit();
		});

    });
</script>