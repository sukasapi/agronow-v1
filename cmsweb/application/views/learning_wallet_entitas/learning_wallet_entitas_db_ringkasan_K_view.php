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
									<?php /*
									<div class="row">
                                        <div class="col-12 col-lg-5">
                                            <label>Level Karyawan</label>
                                            <?php
                                            $selected_value = $id_level_karyawan!=NULL ? $id_level_karyawan : '';

                                            $attr = 'class="form-control" id="id_level_karyawan"';
                                            echo form_dropdown('id_level_karyawan', $form_opt_level_karyawan, $selected_value, $attr);

                                            ?>
                                        </div>
                                        <div class="col-12 col-lg-5">
                                            &nbsp;
                                        </div>
                                    </div>
									*/ ?>
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
						<div class="alert alert-info">
							catatan:<br/>
							<ul>
								<li>Sifat data: by rekap.</li>
								<li>Data rencana: serapan nominal/jpl yang belum diverifikasi oleh pengelola kelas.</li>
								<li>Data realisasi: serapan nominal/jpl yang sudah diverifikasi oleh pengelola kelas.</li>
								<li>Penjelasan lebih lanjut mengenai data rencana dan realisasi dapat dilihat <button data-backdrop="static" type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal_picker2">di sini</button></li>
								<li>Untuk mengunduh semua data, pilih opsi show 'All' terlebih dahulu</li>
							</ul>
						</div>
						
						<div class="row" id="dload">
							<img class="img-fluid" src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
						</div>
					
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
										<th class="text-center">Entitas</th>
                                        <th class="text-center">NIK Karyawan</th>
                                        <th class="text-center">Nama Karyawan</th>
										<th class="text-center">Level Karyawan</th>
										<th class="text-center">JPL Target</th>
										<th class="text-center">JPL Rencana</th>
										<th class="text-center">JPL Realisasi</th>
										<th class="text-center">JPL Total</th>
										<th class="text-center">Nominal Alokasi</th>
										<th class="text-center">Nominal Rencana</th>
										<th class="text-center">Nominal Realisasi</th>
										<th class="text-center">Nominal Total</th>
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

        </div>
    </div>
    <!-- end:: Content -->

<!--begin::Modal-->
<div class="modal fade" id="modal_picker" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Realisasi Penyerapan AgroWallet <?=$tahun?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_picker2" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Infografis Data Rencana dan Realisasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<img class="img-fluid" src="<?php echo base_url('assets/media/misc/agrowallet_rencana_realisasi.jpeg');?>"/>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->

</div>

<script>
	$('#dload').hide();
	
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
				dom: 'Bftlpi',
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('learning_wallet_entitas/l_ajax_realisasi').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
					searchPlaceholder: "masukkan nama karyawan",
                    "infoFiltered": ""
                },
				aLengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				buttons: [
					{
						extend: 'excelHtml5',
						header: true,
						className: 'mt-1 ml-1 btn btn-success', 
						text: 'Unduh File Excel', 
						filename: 'data_wishlist_peminat', 
						exportOptions: { columns: ':visible' },
						action: function(e, dt, node, config) {
							var that = this;
							$('#dload').show();
							
							setTimeout(function() {
								$.fn.DataTable.ext.buttons.excelHtml5.action.call(that, e, dt, node, config);
								$('#dload').hide();
							}, 1000);
						}
					},
				],
                order: [[ 1, "asc" ]],
                columns: [
                    {data: 'no', sortable: false},
					{data: 'nama_group', sortable: false},
                    {data: 'nik_karyawan', sortable: false},
                    {data: 'nama_karyawan', sortable: false},
					{data: 'level_karyawan', sortable: false},
					{data: 'jpl_target', sortable: false, visible: false},
					{data: 'jpl_rencana', sortable: false},
					{data: 'jpl_realisasi', sortable: false},
					{data: 'jpl_total', sortable: false},
					{data: 'nominal_target', sortable: false, visible: false},
					{data: 'nominal_rencana', sortable: false},
					{data: 'nominal_realisasi', sortable: false},
					{data: 'nominal_total', sortable: false},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        orderable: false,
                        render: function(data, type, full, meta) {
							var durl = "<?=site_url('learning_wallet_entitas/l_modal_ajax_realisasi_karyawan/')?>"+full['id_group']+"/<?=$tahun_terpilih?>/"+full['id_member']+"/0";
							var ui = '<button data-backdrop="static" data-remote="'+durl+'" type="button" class="btn btn-outline-info btn-sm ml-2" data-toggle="modal" data-target="#modal_picker">'+full['nama_karyawan']+'</button>';
							return ui;
                        },
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