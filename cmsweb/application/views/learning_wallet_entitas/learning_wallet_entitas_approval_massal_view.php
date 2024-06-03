<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

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
						
						<div class="row">
							<label class="mt-3">Group *</label>
							<?php
							if (validation_errors()) {$val = set_value('group_id');}else{$val = $this->session->userdata('group_id');}

							$attr = 'id="group_id" class="form-control" required';
							echo form_dropdown('group_id', $form_opt_group, $val, $attr);
							?>
						</div>
						<div class="row">
                            <label>Kode AgroWallet</label>
							<select class="form-control kt-input" name="id_lw_classroom" id="ajax_lw"></select>
                        </div>
						<div class="row">
							<label class="mt-3">File (.xlsx) *</label>
							<input class="form-control" required type="file" id="files" name="file" accept="application/vnd.ms-excel, application/x-csv, text/x-csv, text/csv, application/csv, application/excel, application/vnd.msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
						</div>
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
						<li>Gunakan menu ini untuk mendaftarkan peserta pelatihan secara massal pada satu pelatihan yang sama.</li>
						<li>Peserta yang didaftarkan melalui menu ini otomatis masuk ke dalam daftar peserta yang disetujui tanpa perlu melalui proses approval (auto-approved). Termasuk juga bila ada peserta yang sebelumnya ditolak, maka statusnya akan menjadi disetujui.</li>
					</ul>
					<br>
					<p><a class="btn btn-sm btn-outline-info" href="<?= base_url('statics/template/template_import_agrowallet.xlsx') ?>" download target="_blank"><i class="fa fa-download"></i>Download Template</a></p>
				</div>
				<div class="mt-2 border border-primary p-2 rounded bg-white" id="detail_lw_classroom"></div>
			</div>
			
        </div>
		

    </div>


</div>

<script>
var kode_wallet = '';
$("#detail_lw_classroom").html('kode agrowallet masih kosong');
</script>

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
		
		// ajax learning wallet
		$("#ajax_lw").select2({
			placeholder: "...",
			multiple: false,
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: "<?php echo site_url('learning_wallet/ajax_search_agrowallet_pelatihan'); ?>",
				dataType: "json",
				delay: 50,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.results,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: 0
			},
			templateSelection: function (repo) {
				if (repo.kode === undefined) {
					return 'masukkan kode/nama agrowallet';
				}
				
				var data = 
					'<table class="mt-1 table table-sm table-bordered">'+
					'<tr><td style="width:20%">status</td><td>'+repo.status_penyelenggaraan+'</td></tr>'+
					'<tr><td>kode</td><td>'+repo.kode+'</td></tr>'+
					'<tr><td>nama</td><td>'+repo.nama+'</td></tr>'+
					'<tr><td>jpl</td><td>'+repo.jumlah_jam+'</td></tr>'+
					'<tr><td>tgl</td><td>'+repo.tgl_mulai+' sd '+repo.tgl_selesai+'</td></tr>'+
					'<tr><td>catatan</td><td>'+repo.catatan_penyelenggaraan+'</td></tr>'+
					'</table>';
				
				$('#detail_lw_classroom').html(data);
				  
				return repo.kode;
			},
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
		});
		// clear select2 value
		$("#ajax_lw").on("select2:unselecting", function(e) {
			$('#detail_lw_classroom').html("");
			kode_wallet = '';
			$("#detail_lw_classroom").html('kode agrowallet masih kosong');
		});
		// get selected kode wallet
		$("#ajax_lw").on("select2:select", function (e) {
			kode_wallet = $(e.currentTarget).val();
		});

    });
</script>



<!--end::Page Resources -->