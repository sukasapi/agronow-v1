<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
        user_is_login();
        $this->load->model(array(
			'group_model',
			'dashboard_model'
		));
	}

	
	function index(){
	    has_access('dashboard.view');

	    $data['count_member'] = $this->dashboard_model->member_count_all();
        $data['count_member_web'] = $this->dashboard_model->member_count_all('web');
        $data['count_member_android'] = $this->dashboard_model->member_count_all('android');
        $data['count_member_ios'] = $this->dashboard_model->member_count_all('ios');

        $data['count_ads'] = $this->dashboard_model->ads_count_all();

        $data['count_content_download'] = $this->dashboard_model->content_download_count_all();
        $data['count_content_elearning'] = $this->dashboard_model->content_elearning_count_all();

        $data['new_member'] = $this->dashboard_model->get_new_member();
//        print_r($data);

        $data['page_name'] = 'Dashboard';
        $data['page_sub_name'] = 'Ringkasan';
		$data['page'] = 'dashboard/dashboard_view';
        $this->load->view('main_view',$data);
	}

	function excel($channel){
	    $member = $this->dashboard_model->member_get_all($channel);
	    //print_r($member);exit();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'NIP');
        $sheet->setCellValue('D1', 'Group');
        $sheet->setCellValue('E1', 'Jabatan');
        $sheet->setCellValue('F1', 'Level');
        $sheet->setCellValue('G1', 'Email');

        $no = 1;
        $x = 2;
        foreach($member as $row)
        {
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row['member_name']);
            $sheet->setCellValue('C'.$x, (string)$row['member_nip']);
            $sheet->setCellValue('D'.$x, $row['group_name']);
            $sheet->setCellValue('E'.$x, $row['jabatan_name']);
            $sheet->setCellValue('F'.$x, $row['mlevel_name']);
            $sheet->setCellValue('G'.$x, $row['member_email']);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Member '.$channel;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
	
	// laporan
	function laporan_evaluasi_lv3() {
		has_access('laporan.classroom_evaluasilv3_entitas');
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		$id_klien = $this->session->userdata('id_klien');
		
		// judul
		$subjudul = "";
		$judul_chart = "";
		$subjudul_chart = "";
		
		// daftar kategori nilai
		$arrKatNilai = array();
		$arrKatNilai['k'] = 'Knowledge Post Learning Evaluation';
		$arrKatNilai['k_gap'] = 'Knowledge Acqusition';
		$arrKatNilai['s'] = 'Skill Post Learning Evaluation';
		$arrKatNilai['a'] = 'Attitude Post Learning Evaluation';
		$arrKatNilai['b'] = 'Behaviour Post Learning Evaluation';
		$arrKatNilai['na'] = 'Index Efektivitas Pembelajaran';
		
		// group / entitas
		$data['form_opt_group'][''] = 'semua entitas';
		$param_query['filter_active'] = '';
		if($id_klien>0) $param_query['filter_klien'] = $id_klien;
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
		if ($get_group!=FALSE){
			foreach ($get_group['data'] as $k => $v) {
				$data['form_opt_group'][$v['group_id']] = $v['group_name'];
			}
		}
		
		// get distinct tahun evaluasi
		$sqlT = "select distinct(tahun_evaluasi) as tahun_evaluasi from _classroom_evaluasi_lv3_header where status='1' order by tahun_evaluasi";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		
		// params data
		$strError = '';
		$kat_nilai = '';
		$jenis_nilai = '';
		$tahun_evaluasi = '';
		
		$chart2_group_list = '';
		$chart2_id_group_list = '';
		$chart2_series = '';
		
		$jsonDT = '';
		$arrD1 = array();
		$arrD2 = array();
		
		$get = $this->input->get();
		if(!empty($get)) {
			$kat_nilai = $get['kat_nilai'];
			$tahun_evaluasi = $get['tahun_evaluasi'];
			$group_id = $get['group_id'];
			$cr_id = $get['cr_id'];
			$bottom = $get['bottom'];
		}
		if(empty($kat_nilai)) $kat_nilai = 'na';
		if(empty($tahun_evaluasi)) $tahun_evaluasi = date("Y")-1;
		
		if($kat_nilai=="k_gap") {
			$jenis_nilai = 'gap';
			$judul_chart = 'Knowledge Acqusition';
			$subjudul_chart = 'Serapan pembelajaran menggambarkan peningkatan kompetensi yang dihasilkan oleh proses pembelajaran.';
		}
		else {
			$jenis_nilai = 'post';
			$judul_chart = 'Post Learning Evaluation';
			$subjudul_chart = 'Evaluasi perilaku pasca pelaksanaan pelatihan menggambarkan sejauh mana peserta pelatihan menerapkan pengetahuan yang didapatkan dari sebuah pembelajaran dalam bentuk perilaku-perilaku efektif di dalam bekerja. Evaluasi perilaku pasca pelatihan dilakukan dengan menghimpun penilaian dan pengamatan terhadap kemunculan sasaran perubahan perilaku pelatihan di dalam aktivitas kerja sehari-hari peserta.';
		}
		$subjudul = $judul_chart;
		
		// proses data
		if(strlen($strError)<1) {
			$addSql = " and h.tahun_evaluasi='".$tahun_evaluasi."' ";
			
			$arrPie = array();
			$arrPie['1'] = 0;
			$arrPie['2'] = 0;
			$arrPie['3'] = 0;
			$arrPie['4'] = 0;
			$arrPie['5'] = 0;
			
			$addSql = "";
			if(!empty($group_id)) $addSql .= " and g.group_id='".$group_id."' ";
			if(!empty($cr_id)) $addSql .= " and h.cr_id='".$cr_id."' ";
			
			// ambil yg sudah selesai dinilai saja
			$i = 0;
			$sqlU =
				"select c.cr_name, h.tahun_evaluasi, h.cr_id, h.target_peserta, m.group_id, g.group_name, m.member_name, m.member_nip, r.nilai_pre_test, r.nilai_post_test, r.nilai_detail
				 from _classroom c, _classroom_evaluasi_lv3_rekap r, _classroom_evaluasi_lv3_header h, _member m, _group g
				 where c.cr_id=h.cr_id and r.cr_id=h.cr_id and r.member_id=m.member_id and m.group_id=g.group_id and h.tahun_evaluasi='".$tahun_evaluasi."' and g.id_klien='".$id_klien."' and h.status='1' ".$addSql."
				 order by LENGTH(g.group_name), g.group_name, m.member_name ";
			$resU = $this->db->query($sqlU);
			$rowU = $resU->result_array();
			foreach($rowU as $key => $val) {
				$i++;
				$jsonD = json_decode($val['nilai_detail'],true);
				
				$bobot_atasan = $jsonD['bobot_atasan'];
				$bobot_kolega = $jsonD['bobot_kolega'];
				
				$nilai_atasan = 0;
				$nilai_kolega = 0;
				$dnilai = 0;
				
				if($jenis_nilai=="post") {
					if($kat_nilai=="k") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_k_average'];
					} else if($kat_nilai=="s") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_s_average'];
					} else if($kat_nilai=="a") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_a_average'];
					} else if($kat_nilai=="b") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_b_average'];
					} else if($kat_nilai=="na") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai'];
					}
					
					// nilai
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					
					// get range
					$range = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'range');
					$desc = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'desc');
				} else if($jenis_nilai=="gap") {
					if($kat_nilai=="k_gap") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_k_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_k_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					}/*  else if($kat_nilai=="s") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_s_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_s_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="a") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_a_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_a_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="b") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_b_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_b_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="na") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} */
					
					$dnilai = $dnilai_post - $dnilai_pre;
					$dnilai = number_format($dnilai,3);
					
					// get range
					$range = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'range');
					$desc = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'desc');
				}
				
				$kat_chart_all = '_all_'.$range;
				$kat_chart = '_'.$val['group_id'].'_'.$range;
				
				$jsonDT .=
					'{
					"no": "'.$i.'",
					"group_name": "'.$val['group_name'].'",
					"group_id": "'.$val['group_id'].'",
					"member_name": "'.$val['member_name'].'",
					"member_nip": "'.$val['member_nip'].'",
					"cr_name": "'.$val['cr_name'].'",
					"cr_id": "'.$val['cr_id'].'",
					"dnilai": "'.$dnilai.'",
					"desc": "'.$desc.'",
					"kat_chart_all": "'.$kat_chart_all.'",
					"kat_chart": "'.$kat_chart.'",
					}, ';
				
				$arrD1[ $val['group_id'] ] = $val['group_name'];
				
				$arrD2[ $val['group_id'] ][$range]['nilai'] += $dnilai;
				$arrD2[ $val['group_id'] ][$range]['jumlah'] ++;
				
				$arrPie[$range]++;
			}
		}
		
		// chart height
		$chart_height = count($arrD1)*13;
		if($chart_height<460) $chart_height = 460;
		
		// chart
		$chart1_range = array();
		$chart2_range = array();
		foreach($arrD1 as $key => $val) {
			// chart 1
			$chart1_group_list .= "'".$val."',";
			$chart1_id_group_list .= "'".$key."',";
			
			$total = 
				$arrD2[$key]['1']['jumlah'] +
				$arrD2[$key]['2']['jumlah'] +
				$arrD2[$key]['3']['jumlah'] +
				$arrD2[$key]['4']['jumlah'] +
				$arrD2[$key]['5']['jumlah'];
				
			$persen1 = ($total==0)? 0 : number_format($arrD2[$key]['1']['jumlah']/$total,3)*100;
			$persen2 = ($total==0)? 0 : number_format($arrD2[$key]['2']['jumlah']/$total,3)*100;
			$persen3 = ($total==0)? 0 : number_format($arrD2[$key]['3']['jumlah']/$total,3)*100;
			$persen4 = ($total==0)? 0 : number_format($arrD2[$key]['4']['jumlah']/$total,3)*100;
			$persen5 = ($total==0)? 0 : number_format($arrD2[$key]['5']['jumlah']/$total,3)*100;
			
			
			$chart1_range['1'] .= $persen1.",";
			$chart1_range['2'] .= $persen2.",";
			$chart1_range['3'] .= $persen3.",";
			$chart1_range['4'] .= $persen4.",";
			$chart1_range['5'] .= $persen5.",";
			
			// chart 2
			$chart2_group_list .= "'".$val."',";
			$chart2_id_group_list .= "'".$key."',";
			
			$arrD2[$key]['1']['jumlah'] = (int) $arrD2[$key]['1']['jumlah'];
			$arrD2[$key]['2']['jumlah'] = (int) $arrD2[$key]['2']['jumlah'];
			$arrD2[$key]['3']['jumlah'] = (int) $arrD2[$key]['3']['jumlah'];
			$arrD2[$key]['4']['jumlah'] = (int) $arrD2[$key]['4']['jumlah'];
			$arrD2[$key]['5']['jumlah'] = (int) $arrD2[$key]['5']['jumlah'];
			
			$chart2_range['1'] .= $arrD2[$key]['1']['jumlah'].",";
			$chart2_range['2'] .= $arrD2[$key]['2']['jumlah'].",";
			$chart2_range['3'] .= $arrD2[$key]['3']['jumlah'].",";
			$chart2_range['4'] .= $arrD2[$key]['4']['jumlah'].",";
			$chart2_range['5'] .= $arrD2[$key]['5']['jumlah'].",";
		}
		
		$arrM1 = nilaiEvaluasiLv3_Profil('1',$kat_nilai,'master');
		$arrM2 = nilaiEvaluasiLv3_Profil('2',$kat_nilai,'master');
		$arrM3 = nilaiEvaluasiLv3_Profil('3',$kat_nilai,'master');
		$arrM4 = nilaiEvaluasiLv3_Profil('4',$kat_nilai,'master');
		$arrM5 = nilaiEvaluasiLv3_Profil('5',$kat_nilai,'master');
		
		$chart0_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', y: ".$arrPie['1']." },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', y: ".$arrPie['2']." },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', y: ".$arrPie['3']." },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', y: ".$arrPie['4']." },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', y: ".$arrPie['5']." }";
		
		$chart1_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', data: [".$chart1_range['1']."] },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', data: [".$chart1_range['2']."] },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', data: [".$chart1_range['3']."] },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', data: [".$chart1_range['4']."] },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', data: [".$chart1_range['5']."] }";
		
		$chart2_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', data: [".$chart2_range['1']."] },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', data: [".$chart2_range['2']."] },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', data: [".$chart2_range['3']."] },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', data: [".$chart2_range['4']."] },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', data: [".$chart2_range['5']."] }";
		
		$data['chart_height'] = $chart_height;
		$data['arrKatNilai'] = $arrKatNilai;
		$data['arrJenisNilai'] = $arrJenisNilai;
		$data['rowT'] = $rowT;
		$data['jsonDT'] = $jsonDT;
		$data['judul_chart'] = $judul_chart;
		$data['subjudul_chart'] = $subjudul_chart;
		
		$data['chart0_series'] = $chart0_series;
		
		$data['chart1_group_list'] = $chart1_group_list;
		$data['chart1_id_group_list'] = $chart1_id_group_list;
		$data['chart1_series'] = $chart1_series;
		
		$data['chart2_group_list'] = $chart2_group_list;
		$data['chart2_id_group_list'] = $chart2_id_group_list;
		$data['chart2_series'] = $chart2_series;
		
		$data['request']['kat_nilai'] = $kat_nilai;
		$data['request']['jenis_nilai'] = $jenis_nilai;
		$data['request']['tahun_evaluasi'] = $tahun_evaluasi;
		$data['request']['group_id'] = $group_id;
		$data['request']['bottom'] = $bottom;
		
		$data['form_action']    = site_url('dashboard/laporan_evaluasi_lv3');
		$data['page_name'] = 'Laporan Class Room - Evaluasi Level 3';
        $data['page_sub_name'] = 'Partisipant View - '.$arrKatNilai[$kat_nilai];
		$data['page'] = 'dashboard/laporan_evaluasi_lv3';
        $this->load->view('main_view',$data);
	}
	
	function laporan_evaluasi_lv3_cv() {
		has_access('laporan.classroom_evaluasilv3_entitas');
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		$id_klien = $this->session->userdata('id_klien');
		
		// judul
		$subjudul = "";
		$judul_chart = "";
		$subjudul_chart = "";
		
		// daftar kategori nilai
		$arrKatNilai = array();
		$arrKatNilai['k'] = 'Knowledge Post Learning Evaluation';
		$arrKatNilai['k_gap'] = 'Knowledge Acqusition';
		$arrKatNilai['s'] = 'Skill Post Learning Evaluation';
		$arrKatNilai['a'] = 'Attitude Post Learning Evaluation';
		$arrKatNilai['b'] = 'Behaviour Post Learning Evaluation';
		$arrKatNilai['na'] = 'Index Efektivitas Pembelajaran';
		
		// group / entitas
		$data['form_opt_group'][''] = 'semua entitas';
		$param_query['filter_active'] = '';
		if($id_klien>0) $param_query['filter_klien'] = $id_klien;
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
		if ($get_group!=FALSE){
			foreach ($get_group['data'] as $k => $v) {
				$data['form_opt_group'][$v['group_id']] = $v['group_name'];
			}
		}
		
		// daftar kategori view by
		$arrJenisNilai = array();
		$arrJenisNilai['post'] = 'Post Learning Evaluation';
		$arrJenisNilai['gap'] = 'Learning Acqusition';
		
		// get distinct tahun evaluasi
		$sqlT = "select distinct(tahun_evaluasi) as tahun_evaluasi from _classroom_evaluasi_lv3_header where status='1' order by tahun_evaluasi";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		
		// params data
		$strError = '';
		$kat_nilai = '';
		$jenis_nilai = '';
		$tahun_evaluasi = '';
		
		$chart2_group_list = '';
		$chart2_id_group_list = '';
		$chart2_series = '';
		
		$jsonDT = '';
		$arrDT = array();
		$arrD0 = array();
		$arrD1 = array();
		$arrD2 = array();
		
		$get = $this->input->get();
		if(!empty($get)) {
			$kat_nilai = $get['kat_nilai'];
			$tahun_evaluasi = $get['tahun_evaluasi'];
			$group_id = $get['group_id'];
			$cr_id = $get['cr_id'];
			$bottom = $get['bottom'];
		}
		if(empty($kat_nilai)) $kat_nilai = 'na';
		if(empty($tahun_evaluasi)) $tahun_evaluasi = date("Y")-1;
		
		if($kat_nilai=="k_gap") {
			$jenis_nilai = 'gap';
			$judul_chart = 'Knowledge Acqusition';
			$subjudul_chart = 'Serapan pembelajaran menggambarkan peningkatan kompetensi yang dihasilkan oleh proses pembelajaran.';
		}
		else {
			$jenis_nilai = 'post';
			$judul_chart = 'Post Learning Evaluation';
			$subjudul_chart = 'Evaluasi perilaku pasca pelaksanaan pelatihan menggambarkan sejauh mana peserta pelatihan menerapkan pengetahuan yang didapatkan dari sebuah pembelajaran dalam bentuk perilaku-perilaku efektif di dalam bekerja. Evaluasi perilaku pasca pelatihan dilakukan dengan menghimpun penilaian dan pengamatan terhadap kemunculan sasaran perubahan perilaku pelatihan di dalam aktivitas kerja sehari-hari peserta.';
		}
		
		// proses data
		if(strlen($strError)<1) {
			$addSql = " and h.tahun_evaluasi='".$tahun_evaluasi."' ";
			
			$arrPie = array();
			$arrPie['1'] = 0;
			$arrPie['2'] = 0;
			$arrPie['3'] = 0;
			$arrPie['4'] = 0;
			$arrPie['5'] = 0;
			
			$addSql = "";
			if(!empty($group_id)) $addSql .= " and g.group_id='".$group_id."' ";
			if(!empty($cr_id)) $addSql .= " and h.cr_id='".$cr_id."' ";
			
			$i = 0;
			// ambil yg sudah selesai dinilai saja
			$i = 0;
			$sqlU =
				"select c.cr_name, h.tahun_evaluasi, h.cr_id, h.target_peserta, m.group_id, g.group_name, m.member_name, m.member_nip, r.nilai_pre_test, r.nilai_post_test, r.nilai_detail
				 from _classroom c, _classroom_evaluasi_lv3_rekap r, _classroom_evaluasi_lv3_header h, _member m, _group g
				 where c.cr_id=h.cr_id and r.cr_id=h.cr_id and r.member_id=m.member_id and m.group_id=g.group_id and h.tahun_evaluasi='".$tahun_evaluasi."' and g.id_klien='".$id_klien."' and h.status='1' ".$addSql."
				 order by c.cr_name ";
			$resU = $this->db->query($sqlU);
			$rowU = $resU->result_array();
			foreach($rowU as $key => $val) {
				$i++;
				$jsonD = json_decode($val['nilai_detail'],true);
				
				$bobot_atasan = $jsonD['bobot_atasan'];
				$bobot_kolega = $jsonD['bobot_kolega'];
				
				$nilai_atasan = 0;
				$nilai_kolega = 0;
				$dnilai = 0;
				
				if($jenis_nilai=="post") {
					if($kat_nilai=="k") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_k_average'];
					} else if($kat_nilai=="s") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_s_average'];
					} else if($kat_nilai=="a") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_a_average'];
					} else if($kat_nilai=="b") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_b_average'];
					} else if($kat_nilai=="na") {
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai'];
					}
					
					// nilai
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					
					// get range
					$range = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'range');
					// $desc = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'desc');
				} else if($jenis_nilai=="gap") {
					if($kat_nilai=="k_gap") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_k_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_k_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_k_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					}/*  else if($kat_nilai=="s") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_s_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_s_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_s_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="a") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_a_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_a_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_a_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="b") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai_b_average'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_b_average'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_b_average'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} else if($kat_nilai=="na") {
						$nilai_atasan = $jsonD['detail']['pre']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['pre']['kolega']['nilai'];
						$dnilai_pre = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
						
						$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai'];
						$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai'];
						$dnilai_post = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					} */
					
					$dnilai = $dnilai_post - $dnilai_pre;
					$dnilai = number_format($dnilai,3);
					
					// get range
					$range = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'range');
					// $desc = nilaiEvaluasiLv3_Profil($dnilai,$kat_nilai,'desc');
				}
				
				// $kat_chart_all = '';
				// $kat_chart = '_'.$val['cr_id'].'_'.$range;
				
				$arrD1[ $val['cr_id'] ] = $val['cr_name'].' ['.$val['cr_id'].']';
				
				$arrD2[ $val['cr_id'] ][$range]['nilai'] += $dnilai;
				$arrD2[ $val['cr_id'] ][$range]['jumlah'] ++;
				
				// $arrPie[$range]++;
				$arrD0[ $val['cr_id'] ]['no'] = $i;
				$arrD0[ $val['cr_id'] ]['cr_name'] = $val['cr_name'];
				$arrD0[ $val['cr_id'] ]['cr_id'] = $val['cr_id'];
				$arrD0[ $val['cr_id'] ]['range'] = '';
				$arrD0[ $val['cr_id'] ]['desc'] = '';
				$arrD0[ $val['cr_id'] ]['nilai'] += $dnilai;
				$arrD0[ $val['cr_id'] ]['jumlah']++;
			}
			
			// get list kelas yg ga ada data rekapnya; cxr: class no rekap
			$cxr = "";
			$sqlU = "select h.cr_id from _classroom_evaluasi_lv3_rekap r right join _classroom_evaluasi_lv3_header h on r.cr_id=h.cr_id where r.cr_id is null and h.status='1' and h.tahun_evaluasi='".$tahun_evaluasi."' ";
			$resU = $this->db->query($sqlU);
			$rowU = $resU->result_array();
			foreach($rowU as $key => $val) {
				$cxr .= $val['cr_id'].", ";
			}
		}
		
		// chart height
		$chart_height = count($arrD1)*13;
		if($chart_height<200) $chart_height = 200;
		
		// chart
		$chart1_range = array();
		$chart2_range = array();
		foreach($arrD1 as $key => $val) {
			// pie
			$rerata_kelas = ($arrD0[$key]['jumlah']==0)? 0 : number_format($arrD0[$key]['nilai']/$arrD0[$key]['jumlah'],3);
			
			$range = nilaiEvaluasiLv3_Profil($rerata_kelas,$kat_nilai,'range');
			$desc = nilaiEvaluasiLv3_Profil($rerata_kelas,$kat_nilai,'desc');
			
			$arrPie[$range]++;
			$kat_chart_all = '_all_'.$range;
			$kat_chart = '_'.$arrD0[$key]['cr_id'].'_0'; // .$range;
			
			// data table
			$jsonDT .=
					'{
					"no": "'.$arrD0[$key]['no'].'",
					"group_name": "'.$arrD0[$key]['group_name'].'",
					"group_id": "'.$arrD0[$key]['group_id'].'",
					"member_name": "'.$arrD0[$key]['member_name'].'",
					"member_nip": "'.$arrD0[$key]['member_nip'].'",
					"cr_name": "'.$arrD0[$key]['cr_name'].'",
					"cr_id": "'.$arrD0[$key]['cr_id'].'",
					"jumlah_karyawan": "'.$arrD0[$key]['jumlah'].'",
					"dnilai": "'.$rerata_kelas.'",
					"desc": "'.$desc.'",
					"kat_chart_all": "'.$kat_chart_all.'",
					"kat_chart": "'.$kat_chart.'",
					}, ';
			
			// chart 1
			$chart1_group_list .= "'".$val."',";
			$chart1_id_group_list .= "'".$key."',";
			
			$total = 
				$arrD2[$key]['1']['jumlah'] +
				$arrD2[$key]['2']['jumlah'] +
				$arrD2[$key]['3']['jumlah'] +
				$arrD2[$key]['4']['jumlah'] +
				$arrD2[$key]['5']['jumlah'];
				
			$persen1 = ($total==0)? 0 : number_format($arrD2[$key]['1']['jumlah']/$total,3)*100;
			$persen2 = ($total==0)? 0 : number_format($arrD2[$key]['2']['jumlah']/$total,3)*100;
			$persen3 = ($total==0)? 0 : number_format($arrD2[$key]['3']['jumlah']/$total,3)*100;
			$persen4 = ($total==0)? 0 : number_format($arrD2[$key]['4']['jumlah']/$total,3)*100;
			$persen5 = ($total==0)? 0 : number_format($arrD2[$key]['5']['jumlah']/$total,3)*100;
			
			
			$chart1_range['1'] .= $persen1.",";
			$chart1_range['2'] .= $persen2.",";
			$chart1_range['3'] .= $persen3.",";
			$chart1_range['4'] .= $persen4.",";
			$chart1_range['5'] .= $persen5.",";
			
			// chart 2
			$chart2_group_list .= "'".$val."',";
			$chart2_id_group_list .= "'".$key."',";
			
			$arrD2[$key]['1']['jumlah'] = (int) $arrD2[$key]['1']['jumlah'];
			$arrD2[$key]['2']['jumlah'] = (int) $arrD2[$key]['2']['jumlah'];
			$arrD2[$key]['3']['jumlah'] = (int) $arrD2[$key]['3']['jumlah'];
			$arrD2[$key]['4']['jumlah'] = (int) $arrD2[$key]['4']['jumlah'];
			$arrD2[$key]['5']['jumlah'] = (int) $arrD2[$key]['5']['jumlah'];
			
			$chart2_range['1'] .= $arrD2[$key]['1']['jumlah'].",";
			$chart2_range['2'] .= $arrD2[$key]['2']['jumlah'].",";
			$chart2_range['3'] .= $arrD2[$key]['3']['jumlah'].",";
			$chart2_range['4'] .= $arrD2[$key]['4']['jumlah'].",";
			$chart2_range['5'] .= $arrD2[$key]['5']['jumlah'].",";
		}
		
		$arrM1 = nilaiEvaluasiLv3_Profil('1',$kat_nilai,'master');
		$arrM2 = nilaiEvaluasiLv3_Profil('2',$kat_nilai,'master');
		$arrM3 = nilaiEvaluasiLv3_Profil('3',$kat_nilai,'master');
		$arrM4 = nilaiEvaluasiLv3_Profil('4',$kat_nilai,'master');
		$arrM5 = nilaiEvaluasiLv3_Profil('5',$kat_nilai,'master');
		
		$chart0_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', y: ".$arrPie['1']." },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', y: ".$arrPie['2']." },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', y: ".$arrPie['3']." },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', y: ".$arrPie['4']." },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', y: ".$arrPie['5']." }";
		
		$chart1_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', data: [".$chart1_range['1']."] },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', data: [".$chart1_range['2']."] },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', data: [".$chart1_range['3']."] },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', data: [".$chart1_range['4']."] },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', data: [".$chart1_range['5']."] }";
		
		$chart2_series =
			"{ range: '1', name: '".$arrM1['label_range']."', color: '".$arrM1['color']."', data: [".$chart2_range['1']."] },
			 { range: '2', name: '".$arrM2['label_range']."', color: '".$arrM2['color']."', data: [".$chart2_range['2']."] },
			 { range: '3', name: '".$arrM3['label_range']."', color: '".$arrM3['color']."', data: [".$chart2_range['3']."] },
			 { range: '4', name: '".$arrM4['label_range']."', color: '".$arrM4['color']."', data: [".$chart2_range['4']."] },
			 { range: '5', name: '".$arrM5['label_range']."', color: '".$arrM5['color']."', data: [".$chart2_range['5']."] }";
		
		$data['chart_height'] = $chart_height;
		$data['cxr'] = $cxr;
		$data['arrKatNilai'] = $arrKatNilai;
		$data['arrJenisNilai'] = $arrJenisNilai;
		$data['rowT'] = $rowT;
		$data['jsonDT'] = $jsonDT;
		$data['judul_chart'] = $judul_chart;
		$data['subjudul_chart'] = $subjudul_chart;
		
		$data['chart0_series'] = $chart0_series;
		
		$data['chart1_group_list'] = $chart1_group_list;
		$data['chart1_id_group_list'] = $chart1_id_group_list;
		$data['chart1_series'] = $chart1_series;
		
		$data['chart2_group_list'] = $chart2_group_list;
		$data['chart2_id_group_list'] = $chart2_id_group_list;
		$data['chart2_series'] = $chart2_series;
		
		$data['request']['kat_nilai'] = $kat_nilai;
		$data['request']['jenis_nilai'] = $jenis_nilai;
		$data['request']['tahun_evaluasi'] = $tahun_evaluasi;
		$data['request']['group_id'] = $group_id;
		$data['request']['bottom'] = $bottom;
		
		$data['form_action']    = site_url('dashboard/laporan_evaluasi_lv3_cv');
		$data['page_name'] = 'Laporan Class Room - Evaluasi Level 3';
        $data['page_sub_name'] = 'Class View - '.$arrKatNilai[$kat_nilai];
		$data['page'] = 'dashboard/laporan_evaluasi_lv3_class_view';
        $this->load->view('main_view',$data);
	}
	
	function laporan_evaluasi_lv3_av() {
		has_access('laporan.classroom_evaluasilv3_entitas');
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		$id_klien = $this->session->userdata('id_klien');
		
		// judul
		$subjudul = "";
		$judul_chart = "";
		$subjudul_chart = "";
		
		// daftar kategori nilai
		$arrKatNilai = array();
		$arrKatNilai['k'] = 'Knowledge';
		$arrKatNilai['s'] = 'Skill';
		$arrKatNilai['a'] = 'Attitude';
		$arrKatNilai['b'] = 'Behaviour';
		$arrKatNilai['na'] = 'Index Efektivitas Pembelajaran (IEP)';
		
		// group / entitas
		$data['form_opt_group'][''] = 'semua entitas';
		$param_query['filter_active'] = '';
		if($id_klien>0) $param_query['filter_klien'] = $id_klien;
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
		if ($get_group!=FALSE){
			foreach ($get_group['data'] as $k => $v) {
				$data['form_opt_group'][$v['group_id']] = $v['group_name'];
			}
		}
		
		// get distinct tahun evaluasi
		$sqlT = "select distinct(tahun_evaluasi) as tahun_evaluasi from _classroom_evaluasi_lv3_header where status='1' order by tahun_evaluasi";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		
		$post = $this->input->post();
		if(!empty($post)) {
			$kat_nilai = $post['kat_nilai'];
			$tahun_evaluasi = $post['tahun_evaluasi'];
			$group_id = $post['group_id'];
			$jenis_nilai = $post['jenis_nilai'];
		}
		if(empty($kat_nilai)) $kat_nilai = 'na';
		if(empty($jenis_nilai)) $jenis_nilai = 'post';
		if(empty($tahun_evaluasi)) $tahun_evaluasi = date("Y")-1;
		
		// proses data
		if(strlen($strError)<1) {
			$addSql = " and h.tahun_evaluasi='".$tahun_evaluasi."' ";
			
			$addSql = "";
			if(!empty($group_id)) $addSql .= " and g.group_id='".$group_id."' ";
			
			$arrK = array();
			$arrR = array();
			
			// ambil yg sudah selesai dinilai saja
			$i = 0;
			$sqlU =
				"select h.tahun_evaluasi, h.cr_id, h.target_peserta, m.group_id, g.group_name, m.member_name, m.member_nip, r.nilai_pre_test, r.nilai_post_test, r.nilai_detail
				 from _classroom_evaluasi_lv3_rekap r, _classroom_evaluasi_lv3_header h, _member m, _group g
				 where r.cr_id=h.cr_id and r.member_id=m.member_id and m.group_id=g.group_id and h.tahun_evaluasi='".$tahun_evaluasi."' and g.id_klien='".$id_klien."' and h.status='1' ".$addSql."
				 order by LENGTH(g.group_name), g.group_name, m.member_name ";
			$resU = $this->db->query($sqlU);
			$rowU = $resU->result_array();
			foreach($rowU as $key => $val) {
				$i++;
				$jsonD = json_decode($val['nilai_detail'],true);
				
				$cr_id = $val['cr_id'];
				
				$sqlD = "select c.cr_id, c.cr_name, c.cr_has_kompetensi_test, h.daftar_pertanyaan from _classroom c, _classroom_evaluasi_lv3_header h where c.cr_id=h.cr_id and h.status='1' and c.cr_id='".$cr_id."' ";
				$resD = $this->db->query($sqlD);
				$rowD = $resD->result_array();
				
				$cr_has_kompetensi_test = $rowD[0]['cr_has_kompetensi_test'];
				
				$arrDP = json_decode($rowD[0]['daftar_pertanyaan'],true);
				$juml_s = count($arrDP['skill']);
				$juml_a = count($arrDP['attitude']);
				$juml_b = count($arrDP['behaviour']);
				
				$arrK[$cr_id]['cr_id'] = $cr_id;
				$arrK[$cr_id]['cr_name'] = $rowD[0]['cr_name'];
				
				$bobot_atasan = $jsonD['bobot_atasan'];
				$bobot_kolega = $jsonD['bobot_kolega'];
				
				if($cr_has_kompetensi_test=="1") {
					$arrK[$cr_id]['jumlah_k']++;
					$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_k_average'];
					$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_k_average'];
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					$arrK[$cr_id]['nilai_k'] += $dnilai;
					// untuk rerata
					$arrR['k']['jumlah']++;
					$arrR['k']['nilai'] += $dnilai;
				}
				
				if($juml_s>0) {
					$arrK[$cr_id]['jumlah_s']++;
					$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_s_average'];
					$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_s_average'];
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					$arrK[$cr_id]['nilai_s'] += $dnilai;
					// untuk rerata
					$arrR['s']['jumlah']++;
					$arrR['s']['nilai'] += $dnilai;
				}
				
				if($juml_a>0) {
					$arrK[$cr_id]['jumlah_a']++;
					$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_a_average'];
					$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_a_average'];
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					$arrK[$cr_id]['nilai_a'] += $dnilai;
					// untuk rerata
					$arrR['a']['jumlah']++;
					$arrR['a']['nilai'] += $dnilai;
				}
				
				if($juml_b>0) {
					$arrK[$cr_id]['jumlah_b']++;
					$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai_b_average'];
					$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai_b_average'];
					$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
					$dnilai = number_format($dnilai,3);
					$arrK[$cr_id]['nilai_b'] += $dnilai;
					// untuk rerata
					$arrR['b']['jumlah']++;
					$arrR['b']['nilai'] += $dnilai;
				}
				
				$arrK[$cr_id]['jumlah_na']++;
				$nilai_atasan = $jsonD['detail']['post']['atasan']['nilai'];
				$nilai_kolega = $jsonD['detail']['post']['kolega']['nilai'];
				$dnilai = hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega);
				$dnilai = number_format($dnilai,3);
				$arrK[$cr_id]['nilai_na'] += $dnilai;
				
				$arrK[$cr_id]['jumlah_karyawan']++;
				
				// untuk rerata
				$arrR['na']['jumlah']++;
				$arrR['na']['nilai'] += $dnilai;
			}
		}
		
		// rerata tabel detail
		$i = 0;
		foreach($arrK as $key => $val) {
			$i++;
			$arrK[$key]['no'] = $i;
			
			$rerata = ($val['jumlah_k']=='0')? 0 : ($val['nilai_k']/$val['jumlah_k']);
			$rerata = number_format($rerata,3);
			if($rerata=="nan") $rerata = "&nbsp;";
			$arrK[$key]['rerata_k'] = $rerata;
			
			$rerata = ($val['jumlah_s']=='0')? 0 : ($val['nilai_s']/$val['jumlah_s']);
			$rerata = number_format($rerata,3);
			if($rerata=="nan") $rerata = "&nbsp;";
			$arrK[$key]['rerata_s'] = $rerata;
			
			$rerata = ($val['jumlah_a']=='0')? 0 : ($val['nilai_a']/$val['jumlah_a']);
			$rerata = number_format($rerata,3);
			if($rerata=="nan") $rerata = "&nbsp;";
			$arrK[$key]['rerata_a'] = $rerata;
			
			$rerata = ($val['jumlah_b']=='0')? 0 : ($val['nilai_b']/$val['jumlah_b']);
			$rerata = number_format($rerata,3);
			if($rerata=="nan") $rerata = "&nbsp;";
			$arrK[$key]['rerata_b'] = $rerata;
			
			$rerata = ($val['jumlah_na']=='0')? 0 : ($val['nilai_na']/$val['jumlah_na']);
			$rerata = number_format($rerata,3);
			if($rerata=="nan") $rerata = "&nbsp;";
			$arrK[$key]['rerata_na'] = $rerata;
		}
		
		// rerata chart
		$desc_profil = '';
		$chart1_kategori = '';
		$chart1_series = '';
		foreach($arrR as $key => $val) {
			$rerata = ($val['jumlah']=='0')? 0 : ($val['nilai']/$val['jumlah']);
			$rerata = number_format($rerata,3);
			$arrR[$key]['rerata'] = $rerata;
			
			$arrT = nilaiEvaluasiLv3_Profil($rerata,$key,'');
			$color = $arrT['dcolor'];
			$color_txt = $arrT['color_txt'];
			
			$chart1_kategori .= "'".$arrKatNilai[$key]."', ";
			$chart1_series .= "{color: '".$color."', y: ".$rerata." }, ";
			
			if($key=='na') {
				$desc_profil .= '<tr style="background:'.$color.';color:'.$color_txt.'"><td>IEP ('.$arrT['nilai_min'].'&nbsp;sd&nbsp;'.$arrT['nilai_max'].')</td><td>'.$arrT['desc'].'</td></tr>';
			} else {
				$desc_profil .= '<tr style="background:'.$color.';color:'.$color_txt.'"><td>'.$arrKatNilai[$key].' ('.$arrT['nilai_min'].'&nbsp;sd&nbsp;'.$arrT['nilai_max'].')</td><td>'.$arrT['desc'].'</td></tr>';
			}
		}
		
		// tabel detail
		$i = 0;
		$jsonDT = '';
		foreach($arrK as $key => $val) {
			$i++;
			
			$jsonDT .=
				'{
				"no": "'.$i.'",
				"cr_name": "'.$val['cr_name'].'",
				"cr_id": "'.$val['cr_id'].'",
				"jumlah_karyawan": "'.$val['jumlah_karyawan'].'",
				"rerata_k": "'.$val['rerata_k'].'",
				"rerata_s": "'.$val['rerata_s'].'",
				"rerata_a": "'.$val['rerata_a'].'",
				"rerata_b": "'.$val['rerata_b'].'",
				"rerata_na": "'.$val['rerata_na'].'",
				}, ';
		}
		
		$data['rowT'] = $rowT;
		$data['jsonDT'] = $jsonDT;
		
		$data['desc_profil'] = $desc_profil;
		$data['chart1_kategori'] = $chart1_kategori;
		$data['chart1_series'] = $chart1_series;
		
		$data['request']['tahun_evaluasi'] = $tahun_evaluasi;
		$data['request']['group_id'] = $group_id;
		
		$data['form_action']    = site_url('dashboard/laporan_evaluasi_lv3_av');
		$data['page_name'] = 'Laporan Class Room - Evaluasi Level 3';
        $data['page_sub_name'] = 'Aspect View';
		$data['page'] = 'dashboard/laporan_evaluasi_lv3_aspect_view';
        $this->load->view('main_view',$data);
	}
	
	// enroll
	function enroll(){
		// create token
		$url = 'http://enroll.agronow.co.id/sso/agronow/';
		$aplikasi = 'enroll';
		$id_user = $this->session->userdata('id');
		$id_level = $this->session->userdata('user_level_id');
		
		$id_level_allowed = array();
		$id_level_allowed[1] = 1;
		$id_level_allowed[3] = 3;
		
		if(!in_array($id_level,$id_level_allowed)) {
			$ui = 'menu ini khusus untuk:<br/>';
			
			$list = implode(', ',$id_level_allowed);
			$sql = "select user_level_name from _user_level where user_level_id in (".$list.") ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			foreach($row as $key => $val) {
				$ui .= '&raquo; '.$val['user_level_name'].'<br/>';
			}
			
			echo $ui;
		} else {
			// (re)generate token
			$token = rand(100000, 999999);
			$sql = "insert into _mfa set id='".uniqid('MFA')."', id_user='".$id_user."', app_target='".$aplikasi."', token='".$token."', tgl_request=now() on duplicate key update tgl_request=now(), token='".$token."' ";
			$res = $this->db->query($sql);
			
			create_log(3,'0','MFA','generate token');
			
			$url = $url.'?id='.$id_user.'&token='.$token;
			
			redirect($url);
		}
		
		exit;
	}
}