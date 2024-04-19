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
                $attributes = array('method'=>'get','autocomplete'=>"off");
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
							<label>Kategori</label>
							<select class="form-control kt-input" name="kat_nilai">
								<option value="0"></option>
								<?php
								foreach($arrKatNilai as $key => $val) {
									$seld = ($request['kat_nilai']==$key)? 'selected' : '';
									echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
								}
								?>
							</select>
						</div>
						
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
				
				<?php
				if(!empty($cxr)) {
					echo '
						<div class="alert alert-warning">
							Periksa kembali data peserta untuk kelas di bawah ini, apabila tidak termasuk yang dievaluasi sebaiknya tahun evaluasi dikosongkan:<br/>'.$cxr.'
						</div>';
				}
				?>
				
				<div class="kt-portlet">
					 <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <?=$judul_chart?>
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
						<?=$subjudul_chart?>
					</div>
				</div>
				
				<div class="kt-portlet">
                    <div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart0"></div>
						</figure>
					</div>
				</div>

                <div class="kt-portlet">
                    <div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart1"></div>
						</figure>
					</div>
				</div>
				
				<div class="kt-portlet">
                    <div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart2"></div>
						</figure>
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
										<li>klik pada chart untuk menyaring data</li>
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

function filterDataTable(id_group, range) {
	var kword = '_'+id_group+'_'+range;
	if(id_group=="" && range=="") kword = "";
	
	datatable.search('').columns().search('').draw();
	if(kword=="") return ;
	
	if(id_group=="all") {
		datatable.columns(5).search(kword).draw();
	} else {
		datatable.columns(6).search(kword).draw();
	}
};

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
		order: [[2, 'asc']],
		buttons: [
            { extend: 'csvHtml5', text: 'CSV (dotcomma)', title: 'evaluasi_lv3_cv_dc', fieldBoundary: '', fieldSeparator: ';', exportOptions: { columns: ':visible' } },
			{ extend: 'csvHtml5', text: 'CSV (comma)', title: 'evaluasi_lv3_cv_c', fieldBoundary: '', fieldSeparator: ',', exportOptions: { columns: ':visible' } },
        ],
		data: [<?=$jsonDT?>],
        columns: [
			{ data: 'no', title: 'No.', width: 40, type: 'num' },
			// { data: 'group_name', title: 'Nama Perusahaan' },
			// { data: 'member_name', title: 'Nama / NIK Karyawan', render: function ( data, type, row, meta ) { return row.member_name+'<br/>['+row.member_nip+']'; }},
			{ data: 'cr_name', title: 'Nama Pelatihan', render: function ( data, type, row, meta ) { return row.cr_name+' ['+row.cr_id+']'; }},
			{ data: 'jumlah_karyawan', title: 'Peserta', type: 'num' },
			{ data: 'dnilai', title: 'Nilai', type: 'num', textAlign: 'right', render: function ( data, type, row, meta ) { if ( type === 'display' || type === 'filter' ) { return '<a href="<?=site_url('dashboard/laporan_evaluasi_lv3?kat_nilai='.$request['kat_nilai'].'&tahun_evaluasi='.$request['tahun_evaluasi'].'&group_id='.$request['group_id'].'&cr_id=')?>'+row.cr_id+'&bottom=1" target="_blank">'+data+'</a>'; } return data; } },
			{ data: 'desc', title: 'Profil' },
			{ data: 'kat_chart_all', title: 'kat_chart_all', visible: false },
			{ data: 'kat_chart', title: 'kat_chart', visible: false }
        ],
    });
	
	Highcharts.setOptions({
		lang: {
			thousandsSep: '.'
		}
	});
	
	// chart 0
	Highcharts.chart('chart0', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie',
			events: {
				click: function () {
					filterDataTable('','');
				}
			}
		},
		title: {
			text: '<?=$arrKatNilai[$request['kat_nilai']]?>'
		},
		legend: {
			reversed: true
		},
		tooltip: {
			pointFormat: '{series.name}: {point.y} kelas ({point.percentage:.1f}%)'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: { enabled: true, format: '{y} kelas ({percentage:.1f}%)' },
				showInLegend: true
			}
		},
		series: [{
			name: 'persentase', data: [<?=$chart0_series?>],
			point: {
				events: {
					click: function () {
						filterDataTable('all',this.range);
					}
				}
			}
		}]
	});
	
	// chart 1
	objData = {};
	objData[0] = {
		"group_list": [<?=$chart1_group_list?>],
		"id_group_list":[<?=$chart1_id_group_list?>]
	};
	Highcharts.chart('chart1', {
		chart: {
			type: 'bar',
			height: <?=$chart_height?>,
			events: {
				click: function () {
					filterDataTable('','');
				}
			}
		},
		title: {
			text: '<?=$arrKatNilai[$request['kat_nilai']]?> (Persentase Peserta)'
		},
		xAxis: {
			categories: objData[0].group_list
		},
		yAxis: {
			min: 0,
			max: 100,
			tickInterval: 1,
			title: {
				text: 'Jumlah Karyawan'
			}
		},
		legend: {
			reversed: true
		},
		plotOptions: {
			series: {
				stacking: 'normal',
				cursor: 'pointer',
				point: {
					events: {
						click: function () {
							// filterDataTable(objData[0].id_group_list[this.x],this.series.options.range);
							filterDataTable(objData[0].id_group_list[this.x],'0');
						}
					}
				}
			}
		},
		series: [<?=$chart1_series?>]
	});
	
	// chart 2
	objData = {};
	objData[0] = {
		"group_list": [<?=$chart2_group_list?>],
		"id_group_list":[<?=$chart2_id_group_list?>]
	};
	Highcharts.chart('chart2', {
		chart: {
			type: 'bar',
			height: <?=$chart_height?>,
			events: {
				click: function () {
					filterDataTable('','');
				}
			}
		},
		title: {
			text: '<?=$arrKatNilai[$request['kat_nilai']]?> (Jumlah Peserta)'
		},
		xAxis: {
			categories: objData[0].group_list
		},
		yAxis: {
			min: 0,
			tickInterval: 1,
			title: {
				text: 'Jumlah Karyawan'
			}
		},
		legend: {
			reversed: true
		},
		plotOptions: {
			series: {
				stacking: 'normal',
				cursor: 'pointer',
				point: {
					events: {
						click: function () {
							// filterDataTable(objData[0].id_group_list[this.x],this.series.options.range);
							filterDataTable(objData[0].id_group_list[this.x],'0');
						}
					}
				}
			}
		},
		series: [<?=$chart2_series?>]
	});
	
	<? if($request['bottom']=="1") { ?>
	$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	<? } ?>
	
	
	/*
	// generate excel
	$('#downloadlink').html("loading ...");
	var html = "<head><meta charset='utf-8'></head><body>"+$("#datac").html()+"</body>";
	html = html.replace(/<th/g, "<th style='border: 1px solid black;display:block;' ");
	html = html.replace(/<td/g, "<td style='border: 1px solid black;display:block;' ");
	html = html.replace(/<a\b[^>]*>/ig,"").replace(/<\/a>/ig, ""); // remove link
	var isOK = generateFile('#downloadlink',html);
	if(isOK==true) {
		$('#downloadlink').html("download file");
		$('#downloadlink').show();
	} else {
		$('#downloadlink').html("gagal regenerate file!");
		$('#downloadlink').show();
	}
	*/
});
</script>