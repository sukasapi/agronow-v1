<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member"); ?>" class="btn kt-subheader__btn-primary">
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
			
            <div class="col-6">
				<?php
				$this->load->view('flash_notif_view');
				$this->load->view('validation_notif_view');
				?>
				
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Form Upload File
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
						
					
						<label class="mt-3">Group *</label>
						<?php
						if (validation_errors()) {$val = set_value('group_id');}else{$val = $this->session->userdata('group_id');}

						$attr = 'id="group_id" class="form-control" required';
						echo form_dropdown('group_id', $form_opt_group, $val, $attr);
						?>

						<label class="mt-3">File (.xlsx) *</label>
						<input class="form-control" required type="file" id="files" name="file" accept="application/vnd.ms-excel, application/x-csv, text/x-csv, text/csv, application/csv, application/excel, application/vnd.msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
	                </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" name="preview" class="btn btn-outline-info pl-5 pr-5">Preview</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>
			
			<div class="col-6">
				<div class="border border-primary p-2 rounded bg-white">
					catatan:<br/>
					<ul class="mb-0">
						<li>gunakan menu ini hanya untuk import member baru yang jumlahnya tidak terlalu banyak (max 2000 member/excel)</li>
						<li>menambah member baru/update data nama dan level: kosongkan kolom nik_lama, isi kolom nik_username</li>
						<li>update nik member: isi kolom nik_lama dan kolom nik_username</li>
						<li>password member baru akan disamakan dengan nik_username</li>
					</ul>
					<br>
					<p><a class="btn btn-sm btn-outline-info" href="<?= base_url('statics/template/template_import_member_v2.xlsx') ?>" download target="_blank"><i class="fa fa-download"></i>Download Template</a></p>
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

    });
</script>



<!--end::Page Resources -->