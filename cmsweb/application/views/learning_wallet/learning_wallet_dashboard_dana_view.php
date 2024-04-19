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

            <div class="container">
				<div class="row">
					<div class="col-8">
						<?php
						$attributes = array('autocomplete'=>'off','method'=>'post');
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
								
<div class="form-group">
	<label for="exampleFormControlSelect1">Tahun</label>
	<select class="form-control" id="exampleFormControlSelect1">
	  <option> </option>
	  <option selected>2022</option>
	  <option>2023</option>
	</select>
</div>
<div class="row">
	<div class="col-12 mb-2">
		<label>Level Karyawan</label>
		<select class="form-control kt-input" name="sp">
			<option value="0"></option>
			<option value="1">BOD-1</option>
			<option value="2">BOD-2</option>
			<option value="3">BOD-3</option>
			<option value="4">BOD-4</option>
		</select>
	</div>
</div>
<div class="form-group">
	<label for="exampleFormControlSelect1">Group</label>
	<select class="form-control" id="exampleFormControlSelect1">
	  <option> </option>
	  <option>PTPN I</option>
	  <option>PTPN II</option>
	</select>
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
					</div>
					<div class="col-4">
						<div class="bg-white rounded border border-info p-2">
							<b>Informasi</b>:<br/>
							<ul>
								<li>???</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
        </div>
		
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<table>
					<tr>
						<td>Nama Entitas</td>
						<td>Total Dana Pengembangan</td>
						<td>Dana Pengembangan Terserap</td>
						<td>Persentase Serapan</td>
					</tr>
					<tr>
						<td>PTPN I</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>XXX</td>
					</tr>
					<tr>
						<td>PTPN II</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>XXX</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<table>
					<tr>
						<td>Level Karyawan</td>
						<td>Total Dana Pengembangan</td>
						<td>Dana Pengembangan Terserap</td>
						<td>Persentase Serapan</td>
					</tr>
					<tr>
						<td>BOD-1</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>XXX</td>
					</tr>
					<tr>
						<td>BOD-2</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>Rp. XXX.XXXX.XXXX</td>
						<td>XXX</td>
					</tr>
				</table>
			</div>
		</div>
		
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
						<thead>
							<tr>
								<th class="text-center" width="16px">No</th>
								<th class="text-center">Group</th>
								<th class="text-center">Level Karyawan</th>
								<th class="text-center">NIK</th>
								<th class="text-center">Nama Karyawan</th>
								<th class="text-center">Dana Pengembangan</th>
								<th class="text-center">Terserap</th>
								<th class="text-center">Persentase</th>
							</tr>
						</thead>
					</table>
				</div>
				<!--end: Datatable -->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
		
    </div>
    <!-- end:: Content -->

</div>

<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
                // scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('learning_wallet/l_ajax_dana').$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 1, "asc" ]],

                columns: [
                    {data: 'id_member', orderable: false, visible: false},
                    {data: 'group_name', orderable: false},
					{data: 'level_member', orderable: false},
                    {data: 'nik_member', orderable: false},
					{data: 'nama_member', orderable: false},
					{data: 'dana_total', orderable: false},
					{data: 'dana_terpakai', orderable: false},
					{data: 'persentase', orderable: false},
                ],
                columnDefs: [
                    {
                        targets: -6,
                        className: 'text-center'
                    },
                ],
            });
        };

        return {
            //main function to initiate the module
            init: function() {
                initTable1();
            },
        };

    }();

    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    });
</script>