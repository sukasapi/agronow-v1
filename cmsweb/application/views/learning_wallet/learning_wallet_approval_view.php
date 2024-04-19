<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>
    </div>
    <!-- end:: Subheader -->

    <div class="mb-0 pb-0 kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-12">
                <!--Begin::Section-->
                <div class="kt-portlet">
					<div class="kt-portlet__body">
						<div class="row mt-1">
							<div class="col-7 border border-primary p-3">
								<div class="alert alert-info">
									Masukkan NIK Karyawan yang bertugas untuk melakukan approval dan setup dana pengembangan.
								</div>
							
								<?php
								$attributes = array('autocomplete'=>"off","id"=>"dform1");
								echo form_open($form_action_update, $attributes);
								?>
								
								<?php
								foreach($arrKonfig as $key => $val) {
									$ui = '';
									$ui.= '<div class="form-group">';
									$ui.= '<label>'.$val['nama_group'].'</label>';
									$ui.= '<input type="text" class="form-control" id="input'.$key.'" name="konfig['.$key.']" value="'.$val['nik'].'"/>';
									$ui.= '</div>';
									
									echo $ui;
								}
								?>
								
								<input type="hidden" name="act" value="update"/>
								<button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
								
								<?php echo form_close(); ?>
							</div>
							<div class="col-5 border border-primary p-3">
								<div class="font-weight-bold">Daftar Nama PIC:</div>
								<?php
								$ui = '';
								foreach($arrKonfig as $key => $val) {
									if(!empty($val['nama_karyawan'])) {
										$ui .= '<li>'.$val['nama_group'].': '.$val['nama_karyawan'].' ('.$val['nik'].')</li>';
									}
								}
								if(!empty($ui)) $ui = '<ul>'.$ui.'</ul>';
								echo $ui;
								?>
							</div>
						</div>
					</div>
                </div>
                <!--End::Section-->
            </div>
        </div>
    </div>
</div>