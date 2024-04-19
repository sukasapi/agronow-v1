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

            <div class="col-xl-12">
			
				<?php
                $attributes = array('autocomplete'=>"off");
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
					
						<div class="col-12 mb-2">
							<label>Kelompok Tahun Evaluasi</label>
							<select class="form-control kt-input" name="tahun_evaluasi">
								<option value="0"></option>
								<?php
								foreach($rowT as $key => $val) {
									$seld = ($request['tahun_evaluasi']==$val['tahun_evaluasi'])? 'selected' : '';
									echo '<option value="'.$val['tahun_evaluasi'].'" '.$seld.'>'.$val['tahun_evaluasi'].'</option>';
								}
								?>
							</select>
						</div>
						
						<div class="col-12 mb-2">
							<label>Group</label>
							<?php
							$attr = 'id="group_id" class="form-control kt-input"';
							echo form_dropdown('group_id', $form_opt_group, $request['group_id'], $attr);
							?>
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
				
				<div class="container">
					<div class="row">
						<div class="col-6">
							<div class="kt-portlet">
								<div class="kt-portlet__body">
									<figure class="highcharts-figure">
										<div id="chart1"></div>
									</figure>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="kt-portlet">
								<div class="kt-portlet__body">
									<h4>Profil Efektivitas Pembelajaran</h4>
									<table class="table table-sm mt-4">
										<?=$desc_profil?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="kt-portlet">
					<div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title clearfix">
                                Pencarian
                            </h3>
                        </div>
                    </div>
					
					<div class="kt-portlet__body">
						<div class="mb-2 clearfix">
							<div class="float-left">
								<div class="border border-info p-3 rounded">
									<i class="fa-solid fa-circle-exclamation"></i>&nbsp;informasi:<br/>
									<ul>
										<li>klik pada header tabel untuk mengurutkan data</li>
									</ul>
								</div>
							</div>
							<div class="float-right">
								<a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="unduh(';')">Unduh CSV (dotcomma)</a>
								<a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="unduh(',')">Unduh CSV (comma)</a>
							</div>
						</div>
						
						<!--begin: Datatable-->
						<table id="dt" class="table table-striped table-bordered" width="100%"></table>
						<!--end: Datatable-->
					</div>
                </div>
            </div>

        </div>
    </div>
    <!-- end:: Content -->

</div>

<script src="<?=base_url('assets/vendors/general/highcharts/code/highcharts.js')?>"></script>
<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/variable-pie.js')?>"></script>

<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/exporting.js')?>"></script>
<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/offline-exporting.js')?>"></script>

<script>
var datatable = null;

// searching
// datatable.columns(0).search('100').draw();

function unduh(format) {
	if(format==";") {
		datatable.button(0).trigger();
	} else if(format==",") {
		datatable.button(1).trigger();
	}
}
	
$(document).ready(function(){
	datatable = $('#dt').DataTable({
		dom: 'tpli',
		lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
		order: [[1, 'asc']],
		buttons: [
            { extend: 'csvHtml5', text: 'CSV (dotcomma)', title: 'evaluasi_lv3_av_dc', fieldBoundary: '', fieldSeparator: ';', exportOptions: { columns: ':visible' } },
			{ extend: 'csvHtml5', text: 'CSV (comma)', title: 'evaluasi_lv3_av_c', fieldBoundary: '', fieldSeparator: ',', exportOptions: { columns: ':visible' } },
        ],
		data: [<?=$jsonDT?>],
        columns: [
			{ data: 'no', title: 'No.', type: 'num' },
            { data: 'cr_name', title: 'Class Room', render: function ( data, type, row, meta ) { return row.cr_name+' ['+row.cr_id+']'; } },
			{ data: 'jumlah_karyawan', title: 'Peserta', type: 'num' },
			{ data: 'rerata_k',  title: 'Knowledge',   type: 'num', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai=k&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
			{ data: 'rerata_s',  title: 'Skill',       type: 'num', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai=s&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
			{ data: 'rerata_a',  title: 'Attitude',    type: 'num', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai=a&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
            { data: 'rerata_b',  title: 'Behaviour',   type: 'num', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai=b&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
			{ data: 'rerata_na', title: 'IEP', type: 'num', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai=na&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
        ],
    });
	
	Highcharts.setOptions({
		lang: {
			thousandsSep: '.'
		}
	});
	
	// chart 1
	Highcharts.chart('chart1', {
		chart: {
			type: 'bar',
			height: 360,
		},
		title: {
			text: 'Aspect View - Rerata'
		},
		xAxis: {
			categories: [<?=$chart1_kategori?>]
		},
		yAxis: {
			min: 0,
			// max: 100,
			tickInterval: 1,
			title: {
				text: 'Rerata'
			}
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			series: {
				stacking: 'normal',
				cursor: 'default',
				dataLabels: { enabled: true, align: 'right', format: '{y}%' },
			}
		},
		series: [{
			name: 'Rerata',
			data: [<?=$chart1_series?>]
		}]
	});
});
</script>