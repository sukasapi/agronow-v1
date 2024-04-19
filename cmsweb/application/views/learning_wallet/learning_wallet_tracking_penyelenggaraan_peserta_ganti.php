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
		<div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("learning_wallet/tracking_penyelenggaraan_detail/".$tahun."/".$id_kelas); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="container">
				<div class="row">
					<div class="col-12">
						<?php
						$attributes = array('autocomplete'=>'off','method'=>'post','id'=>'form_sp');
						echo form_open($form_action, $attributes);
						?>
						<!-- START PORTLET -->
						<div class="kt-portlet">
							<div class="kt-portlet__body">
								
								<div class="form-group">
									<table class="table table-sm">
										<tr>
											<td style="width:25%">Kode Pelatihan</td>
											<td><?=$data_kelas['kode']?></td>
										</tr>
										<tr>
											<td>Nama Pelatihan</td>
											<td><?=$data_kelas['nama']?></td>
										</tr>
										<tr>
											<td>tanggal Pelatihan</td>
											<td><?=parseDateReadable($data_kelas['tgl_mulai']).' sd '.parseDateReadable($data_kelas['tgl_selesai'])?></td>
										</tr>
										<tr>
											<td>Nama Peserta Saat Ini</td>
											<td><?=$nama_peserta?></td>
										</tr>
										<tr>
											<td>Group Peserta Saat Ini</td>
											<td><?=$nama_group?></td>
										</tr>
									</table>
								</div>
								
								<div class="form-group">
									<label>Nama Peserta Pengganti<span class="text-danger">*</span></label>
									<select class="form-control kt-input" name="id_peserta" id="ajax_p"></select>
								</div>
								
							</div>

							<div class="kt-portlet__foot">
								<div class="kt-form__actions kt-form__actions--solid">
									<div class="row">
										<div class="col-lg-12">
											<input class="btn btn-success" type="submit" name="bs" value="Simpan"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- END PORTLET -->

						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
        </div>
    </div>
    <!-- end:: Content -->
</div>

<script>
    jQuery(document).ready(function() {
        $("#ajax_p").select2({
			placeholder: "masukkan nik/nama",
			multiple: false,
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: "<?php echo site_url('member/ajax_search'); ?>",
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
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
		});
    });
</script>