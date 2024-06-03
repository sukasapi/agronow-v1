<?php
$arrK = $this->learning_wallet_model->status_penyelenggaraan();
$arrD_lw = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$id_lw_classroom));
?>

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
											<label>Kode AgroWallet</label>
											<select class="form-control kt-input" name="idc" id="ajax_lw"></select>
										</div>
                                        <div class="col-12 col-lg-2">
                                            <label></label>
                                            <button type="submit" class="form-control btn btn-info btn-sm mt-2"><i class="la la-filter"></i> Filter</button>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-12 col-lg-5">
											<label>Tahun</label>
                                            <?php
                                            $selected_value = $tahun_terpilih!=NULL ? $tahun_terpilih : '';

                                            $attr = 'class="form-control" id="tahun" ';
                                            echo form_dropdown('tahun', $form_opt_tahun, $selected_value, $attr);

                                            ?>
                                        </div>
										<div class="col-12 col-lg-5">
											<div id="detail_lw_classroom">
												<?php
												if(!empty($arrD_lw)) {
												?>
													<table class="mt-1 table table-sm table-bordered">
														<tr>
															<td>kode</td><td><?=$arrD_lw['kode']?></td>
														</tr>
														<tr>
															<td>nama</td><td><?=$arrD_lw['nama']?></td>
														</tr>
													</table>
												<?php } ?>
											</div>
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
						<div class="alert alert-info">
							<b>catatan</b>:<br/>
							<ul>
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
                                        <th class="text-center">NIK</th>
										<th class="text-center">Nama</th>
										<th class="text-center">Level</th>
										<th class="text-center">Kode Kelas</th>
										<th class="text-center">Nama Kelas</th>
										<th class="text-center">Lokasi Offline</th>
                                        <th class="text-center">Tanggal Mulai</th>
										<th class="text-center">Tanggal Selesai</th>
										<th class="text-center">Harga</th>
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
</div>

<script>
var kode_wallet = '<?=$arrD_lw['id']?>';
</script>

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
                    url  : '<?php echo site_url('learning_wallet_entitas/l_ajax_wishlist_peminat').'?'.$_SERVER['QUERY_STRING']; ?>',
                    type : "POST"
                },
                language: {
					searchPlaceholder: "masukkan nama pelatihan",
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
					{data: 'entitas', sortable: false},
                    {data: 'nik_karyawan', sortable: false},
                    {data: 'nama_karyawan', sortable: false},
					{data: 'level_karyawan', sortable: false},
					{data: 'kode', sortable: false},
					{data: 'nama_kelas', sortable: false},
					{data: 'lokasi', sortable: false},
					{data: 'tgl_mulai', sortable: false},
					{data: 'tgl_selesai', sortable: false},
					{data: 'harga', sortable: false},
                ],
                /* columnDefs: [
                    {
                        targets: 7,
                        orderable: false,
                        render: function(data, type, full, meta) {
							return '<a href="<?php echo site_url('learning_wallet_entitas/dashboard_penyelenggaraan_detail?group_id='.$group_id.'&tahun='.$tahun_terpilih.'&bulan='.$bulan.'&kategori='.$kategori); ?>&idc='+full['id']+'">detail</a>';
                        },
                    },
                ], */
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
					'<tr><td>kode</td><td>'+repo.kode+'</td></tr>'+
					'<tr><td>nama</td><td>'+repo.nama+'</td></tr>'+
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
		});
		// get selected kode wallet
		$("#ajax_lw").on("select2:select", function (e) {
			kode_wallet = $(e.currentTarget).val();
		});
		// 
		var option = new Option(":::", "<?=$id_lw_classroom?>", true, true);
		$("#ajax_lw").append(option).trigger('change');
    });
</script>