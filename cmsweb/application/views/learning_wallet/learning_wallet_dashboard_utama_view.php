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
						$attributes = array('autocomplete'=>'off','method'=>'get');
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
								
								<div class="row">
									<div class="col-6 mb-2">
										<label>Tahun</label>
										<select class="form-control kt-input" name="tahun">
											<option value="0"></option>
											<?php
											foreach($arrTahun as $key => $val) {
												$seld = ($request['tahun']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$key.'</option>';
											}
											?>
										</select>
									</div>
									
									<div class="col-6 mb-2">
										<label>Level Karyawan</label>
										<select class="form-control kt-input" name="id_lv">
											<option value="0"></option>
											<?php
											foreach($arrLv as $key => $val) {
												$seld = ($request['id_lv']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="row">
									<div class="col-12 mb-2">
										<label>Group</label>
										<select class="form-control kt-input" name="id_group">
											<option value="0"></option>
											<?php
											foreach($arrGroup as $key => $val) {
												$seld = ($request['id_group']==$key)? 'selected' : '';
												echo '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
											}
											?>
										</select>
									</div>
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
								<li>Karyawan bisa memesan pelatihan tanggal <?=$arrKonfig['pengajuan_mulai'].' sd '.$arrKonfig['pengajuan_selesai']?> setiap bulannya.</li>
								<li>Bagian SDM bisa menyetujui pelatihan tanggal <?=$arrKonfig['approval_mulai'].' sd '.$arrKonfig['approval_selesai']?> setiap bulannya.</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="kt-portlet">
					<div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart0"></div>
						</figure>
						<div class="text-right"><a target="_blank" class="btn btn-sm btn-primary" href="<?php echo site_url('learning_wallet/dashboard_pengajuan?'.$search_params); ?>">lihat detail</a></div>
					</div>
				</div>
				
				<div class="kt-portlet">
					<div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart1"></div>
						</figure>
						<div class="text-right"><a target="_blank" class="btn btn-sm btn-primary" href="<?php echo site_url('learning_wallet/dashboard_dana?'.$search_params); ?>">lihat detail</a></div>
					</div>
				</div>
				
				<div class="kt-portlet">
					<div class="kt-portlet__body">
						<figure class="highcharts-figure">
							<div id="chart2"></div>
						</figure>
						<div class="text-right"><a target="_blank" class="btn btn-sm btn-primary" href="<?php echo site_url('learning_wallet/dashboard_jam_pelajaran?'.$search_params); ?>">lihat detail</a></div>
					</div>
				</div>
			</div>
			
        </div>
    </div>
    <!-- end:: Content -->

</div>

<script src="<?=base_url('assets/vendors/general/highcharts/code/highcharts.js')?>"></script>
<script src="<?=base_url('assets/vendors/general/highcharts/code/modules/variable-pie.js')?>"></script>

<script>
$(document).ready(function(){
	Highcharts.setOptions({
		lang: {
			thousandsSep: '.'
		}
	});
	
	objData = {};
	
	// chart 0
	objData[0] = {
		"group_list": [<?=$chart0_group_list?>],
		"id_group_list":[<?=$chart0_id_group_list?>]
	};
	Highcharts.chart('chart0', {
		chart: {
			type: 'bar',
			height: <?=$chart_height?>,
			/* events: {
				click: function () {
					filterDataTable('','');
				}
			} */
		},
		title: {
			text: 'Pengajuan Pelatihan'
		},
		xAxis: {
			categories: objData[0].group_list
		},
		yAxis: {
			min: 0,
			max: <?=$chart0_max?>,
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
				dataLabels: { enabled: true, align: 'right', format: '{y}' },
				stacking: 'normal',
				cursor: 'pointer',
				/* point: {
					events: {
						click: function () {
							filterDataTable(objData[0].id_group_list[this.x],this.series.options.range);
						}
					}
				} */
			}
		},
		series: [<?=$chart0_series?>]
	});
	
	// chart 1
	objData[1] = {
		"nominal": [<?=$data_nominal?>]
	};
	Highcharts.chart('chart1', {
		chart: {
			type: 'bar',
			height: <?=$chart_height?>,
			/* events: {
				click: function () {
					filterDataTable('','');
				}
			} */
		},
		title: {
			text: 'Penyerapan Dana Pengembangan (%)'
		},
		xAxis: {
			categories: objData[0].group_list
		},
		yAxis: {
			min: 0,
			max: 100,
			tickInterval: 1,
			title: {
				text: 'Persentase Dana Pengembangan'
			}
		},
		tooltip: {
            formatter: function() {
                return '<b>'+ Highcharts.numberFormat(this.y, 3) +'%</b><br/>'+
					'nominal: Rp. '+ Highcharts.numberFormat(objData[1].nominal[this.point.index][this.series.index],0);
            }
        },
		legend: {
			reversed: true
		},
		plotOptions: {
			series: {
				dataLabels: { enabled: true, align: 'right', format: '{y}%' },
				stacking: 'normal',
				cursor: 'pointer',
				/* point: {
					events: {
						click: function () {
							filterDataTable(objData[0].id_group_list[this.x],this.series.options.range);
						}
					}
				} */
			}
		},
		series: [<?=$chart1_series?>]
	});
	
	// chart 2
	objData[2] = {
		"jpl": [<?=$data_jpl?>]
	};
	Highcharts.chart('chart2', {
		chart: {
			type: 'bar',
			height: <?=$chart_height?>,
			/* events: {
				click: function () {
					filterDataTable('','');
				}
			} */
		},
		title: {
			text: 'Jam Pembelajaran'
		},
		xAxis: {
			categories: objData[0].group_list
		},
		yAxis: {
			min: 0,
			max: 100,
			tickInterval: 1,
			title: {
				text: 'Persentase Jam Pembelajaran'
			}
		},
		tooltip: {
            formatter: function() {
                return '<b>'+ Highcharts.numberFormat(this.y, 3) +'%</b><br/>'+
					'JPL: '+ Highcharts.numberFormat(objData[2].jpl[this.point.index][this.series.index],0);
            }
        },
		legend: {
			reversed: true
		},
		plotOptions: {
			series: {
				dataLabels: { enabled: true, align: 'right', format: '{y}%' },
				stacking: 'normal',
				cursor: 'pointer',
				/* point: {
					events: {
						click: function () {
							filterDataTable(objData[0].id_group_list[this.x],this.series.options.range);
						}
					}
				} */
			}
		},
		series: [<?=$chart2_series?>]
	});
});
</script>