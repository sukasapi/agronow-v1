<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Learning_wallet_entitas extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'group_model',
			'learning_wallet_model',
			'member_model',
        ));
		
		$this->section_id = 45;
		
		// set max length group_concat
		$sql = "SET @@group_concat_max_len = 100000;";
		$res = $this->db->query($sql);
    }
	
	function l_ajax_wishlist(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$sql_d0 = '';
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		
		// admin entitas?
		$session_group_id = $this->session->userdata('group_id');
		$arrG = $this->group_model->get_child_company($group_id,"self_child");
		$juml_group = count($arrG);
		if($juml_group<1) {
			if(empty($session_group_id)) $group_id = -1;
			else $group_id = $session_group_id;
		} else if($juml_group==1) {
			$group_id = $arrG[0]['group_id'];
		} else {
			$arrT = $this->group_model->gets($session_group_id);
			$silsilah = $arrT[0]['silsilah'];
			
			$sql_d0 .= " and g.silsilah like '".$silsilah."%' ";
		}
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and c.nama like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		if(!empty($group_id)) {
			$sql_d0 = " and m.group_id='".$group_id."' ";
		}
		
		$sql_limit = '';
		if($_POST['length'] != -1) {
			$sql_limit = " limit ".$_POST['length']." offset ".$_POST['start']." ";
		}
		
		$data = array();
		
		$sql_order = "";
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select c.id
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _member m, _group g, _member_level_karyawan l
			 where w.id_lw_classroom=c.id and w.status='aktif' and c.status='aktif' and c.tahun='".$tahun_terpilih."' 
			 and m.group_id=g.group_id and m.id_level_karyawan=l.id
			 and m.member_id=w.id_member ".$sql_d0." ".$addSql."
			 group by w.id_lw_classroom 
			 ".$sql_order." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select c.id, c.kode, c.nama, c.lokasi_offline, c.tgl_mulai, c.tgl_selesai, count(c.id) as jumlah, c.minimal_peserta
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _member m, _group g, _member_level_karyawan l
			 where w.id_lw_classroom=c.id and w.status='aktif' and c.status='aktif' and c.tahun='".$tahun_terpilih."' 
			 and m.group_id=g.group_id and m.id_level_karyawan=l.id
			 and m.member_id=w.id_member ".$sql_d0." ".$addSql."
			 group by c.id 
			 ".$order_by."
			 ".$sql_limit." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['id'] = $item['id'];
			$row['kode'] = $item['kode'];
			$row['nama_pelatihan'] = $item['nama'];
			$row['lokasi'] = $item['lokasi_offline'];
			$row['tgl_mulai'] = $item['tgl_mulai'];
			$row['tgl_selesai'] = $item['tgl_selesai'];
			$row['jumlah'] = $item['jumlah'];
			$row['minimal_peserta'] = $item['minimal_peserta'];
			$row['aksi'] = '';
			
			$data[] = $row;
        }
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
        //output to json format
        echo json_encode($output);
    }
	
	function l_ajax_wishlist_peminat(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$sql_d0 = '';
		$addSql = '';
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		$id_lw_classroom = (int) $get['idc'];
		
		// admin entitas?
		$session_group_id = $this->session->userdata('group_id');
		$arrG = $this->group_model->get_child_company($group_id,"self_child");
		$juml_group = count($arrG);
		if($juml_group<1) {
			if(empty($session_group_id)) $group_id = -1;
			else $group_id = $session_group_id;
		} else if($juml_group==1) {
			$group_id = $arrG[0]['group_id'];
		} else {
			$arrT = $this->group_model->gets($session_group_id);
			$silsilah = $arrT[0]['silsilah'];
			
			$sql_d0 .= " and g.silsilah like '".$silsilah."%' ";
		}
		
		$post = $this->input->post();
		
		// kolom wajib diisi sudah terpenuhi semua?
		if(empty($tahun_terpilih) && empty($id_lw_classroom)) $addSql .= " and 1=2 ";
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and c.nama like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		if(!empty($group_id)) {
			$sql_d0 = " and m.group_id='".$group_id."' ";
		}
		
		if(!empty($tahun_terpilih)) $addSql .= " and c.tahun='".$tahun_terpilih."' ";
		if(!empty($id_lw_classroom)) $addSql .= " and c.id='".$id_lw_classroom."' ";
		
		$sql_limit = '';
		if($_POST['length'] != -1) {
			$sql_limit = " limit ".$_POST['length']." offset ".$_POST['start']." ";
		}
		
		$no = $_POST['start'];
		
		$data = array();
		
		$sql_order = "";
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select c.id
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _group g, _member m, _member_level_karyawan l
			 where w.id_lw_classroom=c.id and w.status='aktif' and c.status='aktif' 
			 and w.id_member=m.member_id and m.group_id=g.group_id and w.id_lw_classroom=c.id and m.id_level_karyawan=l.id
				and w.status='aktif' and c.status='aktif' ".$sql_d0." ".$addSql."
			 ".$sql_order." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select w.id as idw, g.group_name as entitas, m.member_nip, m.member_name, c.kode, c.nama, c.lokasi_offline, c.tgl_mulai, c.tgl_selesai, c.harga, l.nama as level_karyawan
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _group g, _member m, _member_level_karyawan l
			 where 
				w.id_member=m.member_id and m.group_id=g.group_id and w.id_lw_classroom=c.id and m.id_level_karyawan=l.id
				and w.status='aktif' and c.status='aktif' ".$sql_d0." ".$addSql."
			 ".$order_by."
			 ".$sql_limit." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$no++;
			
			$row = array();
			$row['no'] = $no;
			$row['entitas'] = $item['entitas'];
			$row['nik_karyawan'] = $item['member_nip'];
			$row['nama_karyawan'] = $item['member_name'];
			$row['level_karyawan'] = $item['level_karyawan'];
			$row['kode'] = $item['kode'];
			$row['nama_kelas'] = $item['nama'];
			$row['lokasi'] = $item['lokasi_offline'];
			$row['tgl_mulai'] = $item['tgl_mulai'];
			$row['tgl_selesai'] = $item['tgl_selesai'];
			$row['harga'] = $item['harga'];
			
			$data[] = $row;
        }
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
        //output to json format
        echo json_encode($output);
    }
	
	function l_ajax_konfig_dashboard_penyelenggaraan(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		// status penyelenggaraan
		$arrK = $this->learning_wallet_model->status_penyelenggaraan();
		
		$sql_d0 = '';
		$sql_d1 = '';
		$sql_order = '';
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		$bulan = (int) $get['bulan'];
		$id_lw_classroom = (int) $get['id_lw_classroom'];
		$kategori = $get['kategori'];
		
		// admin entitas?
		$session_group_id = $this->session->userdata('group_id');
		$arrG = $this->group_model->get_child_company($group_id,"self_child");
		$juml_group = count($arrG);
		if($juml_group<1) {
			if(empty($session_group_id)) $group_id = -1;
			else $group_id = $session_group_id;
		} else if($juml_group==1) {
			$group_id = $arrG[0]['group_id'];
		} else {
			$arrT = $this->group_model->gets($session_group_id);
			$silsilah = $arrT[0]['silsilah'];
			
			$sql_d0 .= " and g.silsilah like '".$silsilah."%' ";
		}
		
		$post = $this->input->post();
		
		// kolom wajib diisi sudah terpenuhi semua?
		if(empty($bulan) && empty($id_lw_classroom)) $addSql .= " and 1=2 ";
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and c.nama like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		if(!empty($bulan)) {
			$bulan2 = ($bulan<10)? '0'.$bulan : $bulan;
			$sql_d1 .= " and c.tgl_mulai like '".$tahun_terpilih."-".$bulan2."-%' ";
		}
		
		if(!empty($group_id)) {
			$sql_d0 .= " and g.group_id='".$group_id."' ";
			$sql_order .= ", g.group_id ";
		}
		
		if($kategori=="diselenggarakan") {
			$sql_d1 .= " and c.status_penyelenggaraan='jalan' ";
		} else if($kategori=="batal") {
			$sql_d1 .= " and c.status_penyelenggaraan='batal' ";
		} else if($kategori=="pending") {
			$sql_d1 .= " and c.status_penyelenggaraan='-' ";
		}
		
		if(!empty($id_lw_classroom)) {
			$sql_d1 .= " and c.id='".$id_lw_classroom."' ";
		}
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(c.id) as jumlah
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p, _member m, _group g
			 where p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id=g.group_id and c.status='aktif' and p.status='aktif'
			 ".$sql_d0." ".$sql_d1." 
			 ".$addSql."
			 group by c.id ".$sql_order." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select 
				c.id, c.kode, c.nama, c.tgl_mulai, c.tgl_selesai, c.status_penyelenggaraan, 
				sum(if(p.kode_status_current>0 && p.kode_status_current<40,1,0)) as jumlah_pengajuan_pending, 
				sum(if(p.kode_status_current=40,1,0)) as jumlah_pengajuan_disetujui, 
				c.minimal_peserta
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p, _member m, _group g
			 where p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id=g.group_id and c.status='aktif' and p.status='aktif'
			 ".$sql_d0." ".$sql_d1." 
			 ".$addSql."
			 group by c.id ".$sql_order."
			 ".$order_by."
			 limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['id'] = $item['id'];
			$row['kode'] = $item['kode'];
			$row['nama_pelatihan'] = $item['nama'];
			$row['tgl_mulai'] = $item['tgl_mulai'];
			$row['tgl_selesai'] = $item['tgl_selesai'];
			$row['jumlah_pengajuan_pending'] = $item['jumlah_pengajuan_pending'];
			$row['jumlah_pengajuan_disetujui'] = $item['jumlah_pengajuan_disetujui'];
			$row['minimal_peserta'] = $item['minimal_peserta'];
			$row['status'] = $arrK[$item['status_penyelenggaraan']];
			$row['aksi'] = '';
			
			$data[] = $row;
        }
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
        //output to json format
        echo json_encode($output);
    }
	
	function l_modal_ajax_realisasi_karyawan($id_entitas,$tahun,$id_member,$id_highlight){
		$id_entitas = (int) $id_entitas;
		$tahun = (int) $tahun;
		$id_member = (int) $id_member;
		$id_highlight = (int) $id_highlight;
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $id_entitas = $this->session->userdata('group_id');
		
		$arrData = $this->learning_wallet_model->get_detail_realisasi($id_entitas,$id_member,$tahun,$id_highlight,true);
		
		$ui = 
			'<div class="alert alert-info">
				catatan:<br/>
				<ul>
					<li>Sifat data: real-time.</li>
					<li>Data rencana: serapan nominal/jpl yang belum diverifikasi oleh pengelola kelas.</li>
					<li>Data realisasi: serapan nominal/jpl yang sudah diverifikasi oleh pengelola kelas.</li>
					<li>Persetujuan dilakukan oleh Admin Entitas.</li>
					<li>Verifikasi dilakukan oleh Pengelola Pelatihan.</li>
				</ul>
			 </div>
			 <div class="row">
				<div class="col-6">
					<table class="table table-sm">
						<tr>
							<td>Entitas</td>
							<td>'.$arrData['nama_entitas'].'</td>
						</tr>
						<tr>
							<td>NIK</td>
							<td>'.$arrData['nik_karyawan'].'</td>
						</tr>
						<tr>
							<td>Nama</td>
							<td>'.$arrData['nama_karyawan'].'</td>
						</tr>
						<tr>
							<td>Level</td>
							<td>'.$arrData['level_karyawan'].'</td>
						</tr>
					 </table>
				</div>
				<div class="col-6">
					<table class="table table-sm table-bordered">
						<tr>
							<td>&nbsp;</td>
							<td>Target/Alokasi</td>
							<td>Rencana</td>
							<td>Realisasi</td>
							<td>Total</td>
						</tr>
						<tr>
							<td>JPL</td>
							<td>'.$arrData['jpl_target'].'</td>
							<td>'.$arrData['jpl_proyeksi'].'</td>
							<td>'.$arrData['jpl_realisasi'].'</td>
							<td>'.$arrData['jpl_total'].'</td>
						</tr>
						<tr>
							<td>Rp.</td>
							<td>'.number_format($arrData['nominal_target'],2,',','.').'</td>
							<td>'.number_format($arrData['nominal_proyeksi'],2,',','.').'</td>
							<td>'.number_format($arrData['nominal_realisasi'],2,',','.').'</td>
							<td>'.number_format($arrData['nominal_total'],2,',','.').'</td>
						</tr>
					 </table>
				</div>
			 </div>
			 <b>Daftar Penggunaan Saldo AgroWallet</b><br/>
			 <table class="table table-sm table-bordered">'.$arrData['table'].'</table>';
        echo $ui;
    }
	
	function l_ajax_realisasi(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$sql_d0 = "";
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		$id_level_karyawan = (int) $get['id_level_karyawan'];
		
		// admin entitas?
		$session_group_id = $this->session->userdata('group_id');
		$arrG = $this->group_model->get_child_company($group_id,"self_child");
		$juml_group = count($arrG);
		if($juml_group<1) {
			if(empty($session_group_id)) $group_id = -1;
			else $group_id = $session_group_id;
		} else if($juml_group==1) {
			$group_id = $arrG[0]['group_id'];
		} else {
			$arrT = $this->group_model->gets($session_group_id);
			$silsilah = $arrT[0]['silsilah'];
			
			$sql_d0 .= " and g.silsilah like '".$silsilah."%' ";
		}
		
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$sql_d0 .= " and m.member_name like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		if(!empty($group_id)) $sql_d0 .= " and s.id_group='".$group_id."' ";
		if(!empty($id_level_karyawan)) $sql_d0 .= " and s.id_level_karyawan='".$id_level_karyawan."' ";
		
		$sql_limit = '';
		if($_POST['length'] != -1) {
			$sql_limit = " limit ".$_POST['length']." offset ".$_POST['start']." ";
		}
		
		$no = $_POST['start'];
		
		$data = array();
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select s.id
			 from _learning_wallet_serapan s, _member m, _group g
			 where s.id_member=m.member_id and s.id_group=g.group_id and s.tahun='".$tahun_terpilih."'
			 ".$sql_d0." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select m.member_nip, m.member_name, s.*
			 from _learning_wallet_serapan s, _member m, _group g
			 where s.id_member=m.member_id and s.id_group=g.group_id and s.tahun='".$tahun_terpilih."'
			 ".$sql_d0."
			 ".$order_by."
			 ".$sql_limit." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$no++;
			
			$row = array();
			$row['no'] = $no;
			$row['id_member'] = $item['id_member'];
			$row['id_group'] = $item['id_group'];
			$row['nama_group'] = $item['nama_group'];
			$row['nik_karyawan'] = $item['member_nip'];
			$row['nama_karyawan'] = $item['member_name'];
			$row['level_karyawan'] = $item['nama_level_karyawan'];
			$row['jpl_target'] = $item['jpl_target'];
			$row['nominal_target'] = $item['nominal_target'];
			$row['jpl_rencana'] = $item['jpl_rencana'];
			$row['nominal_rencana'] = $item['nominal_rencana'];
			$row['jpl_realisasi'] = $item['jpl_realisasi'];
			$row['nominal_realisasi'] = $item['nominal_realisasi'];
			$row['jpl_total'] = $item['jpl_total'];
			$row['nominal_total'] = $item['nominal_total'];
			
			$data[] = $row;
        }
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
        //output to json format
        echo json_encode($output);
    }
	
	function l_ajax_db_rincian(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$sql_d0 = '';
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		$cnapeln = $this->input->get('cnapeln', true);
		
		// admin entitas?
		$session_group_id = $this->session->userdata('group_id');
		$arrG = $this->group_model->get_child_company($group_id,"self_child");
		$juml_group = count($arrG);
		if($juml_group<1) {
			if(empty($session_group_id)) $group_id = -1;
			else $group_id = $session_group_id;
		} else if($juml_group==1) {
			$group_id = $arrG[0]['group_id'];
		} else {
			$arrT = $this->group_model->gets($session_group_id);
			$silsilah = $arrT[0]['silsilah'];
			
			$sql_d0 .= " and g.silsilah like '".$silsilah."%' ";
		}
		
		$post = $this->input->post();
		
		// kolom wajib diisi sudah terpenuhi semua?
		if(empty($tahun_terpilih)) $tahun_terpilih = date("Y");
		
		if(!empty($tahun_terpilih)) $sql_d0 .= " and w.tahun='".$tahun_terpilih."' ";
		if(!empty($cnapeln)) $sql_d0 .= " and c.cr_name like '%".$cnapeln."%' ";
		
		/* $keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$sql_d0 .= " and c.nama like '%".$keyword."%' ";
		} */
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		if(!empty($group_id)) {
			$sql_d0 .= " and g.group_id='".$group_id."' ";
		}
		
		$sql_limit = '';
		if($_POST['length'] != -1) {
			$sql_limit = " limit ".$_POST['length']." offset ".$_POST['start']." ";
		}
		
		$no = $_POST['start'];
		
		$data = array();
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select c.cr_id
			 from _classroom c, _classroom_member cm, _learning_wallet_classroom w, _member m, _group g
			 where
				c.cr_id=cm.cr_id and w.id=c.id_lw_classroom and 
				c.qc_member_id>0 and c.cr_status='publish' and w.status='aktif' and cm.member_status='1' and 
				cm.is_pk='0' and cm.id_group=g.group_id and cm.member_id=m.member_id ".$sql_d0." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select
				c.cr_name as nama_pelatihan_agronow, w.nama as nama_pelatihan_agrowallet, w.lokasi_offline,
				g.group_name, m.member_nip, m.member_name, w.tgl_mulai, w.tgl_selesai, w.harga, w.jumlah_jam
			 from _classroom c, _classroom_member cm, _learning_wallet_classroom w, _member m, _group g
			 where
				c.cr_id=cm.cr_id and w.id=c.id_lw_classroom and 
				c.qc_member_id>0 and c.cr_status='publish' and w.status='aktif' and cm.member_status='1' and 
				cm.is_pk='0' and cm.id_group=g.group_id and cm.member_id=m.member_id ".$sql_d0."
			 ".$order_by."
			 ".$sql_limit." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$no++;
			
			$row = array();
			$row['no'] = $no;
			$row['group_name'] = $item['group_name'];
			$row['nama_pelatihan_agronow'] = $item['nama_pelatihan_agronow'];
			$row['nama_pelatihan_agrowallet'] = $item['nama_pelatihan_agrowallet'];
			$row['lokasi_offline'] = $item['lokasi_offline'];
			$row['tgl_mulai'] = $item['tgl_mulai'];
			$row['tgl_selesai'] = $item['tgl_selesai'];
			$row['nik_karyawan'] = $item['member_nip'];
			$row['nama_karyawan'] = $item['member_name'];
			$row['nominal'] = $item['harga'];
			$row['jumlah_jam'] = $item['jumlah_jam'];
			
			$data[] = $row;
        }
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
        //output to json format
        echo json_encode($output);
    }
	
	function index(){
		redirect(404);
	}

    function kelola_dana(){
        has_access('learningwalletentitas.kelola_dana');
		
		$is_user_group_found = false;
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// admin entitas?
		$data['arr_group'] = $this->group_model->get_child_company("","self_child");
		$juml_group = count($data['arr_group']);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($data['arr_group'] as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
			
			// selected group_id ada di daftar group?
			if($data['group_id']==$valG['group_id']) $is_user_group_found = true;
		}
		if($is_user_group_found==false) $data['group_id'] = "0";
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Kelola Dana Pengembangan per Level Karyawan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_kelola_dana_list_view';
        $this->load->view('main_view',$data);
    }
	
	function kelola_dana_update(){
		has_access('learningwalletentitas.kelola_dana');
		
		$strError = '';
		$id_klien = "0";
		$nama_klien = "";
		$silsilah_depth = "0";
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// get session data
		$session_group_id = $this->session->userdata('group_id');
		
		// admin entitas?
		$data['arr_group'] = $this->group_model->get_child_company($data['group_id'],"self_child");
		$juml_group = count($data['arr_group']);
		if($juml_group!=1) {
			$strError .= '<li>Group masih kosong</li>';
		} else {
			$id_klien = $data['arr_group'][0]['id_klien'];
			$nama_klien = $data['arr_group'][0]['group_name'];
			$silsilah = $data['arr_group'][0]['silsilah'];
			$silsilah_depth = substr_count($silsilah, '.');
		}
		$data['id_klien'] = $id_klien;
		$data['nama_klien'] = $nama_klien;
		
		if($silsilah_depth>=3 && $data['arr_group'][0]['group_id']==$session_group_id) {
			$parent_code = '';
			$arrT = explode('.',$silsilah);
			$j = $silsilah_depth-1;
			for($i=0;$i<$j;$i++) {
				$parent_code .= $arrT[$i].'.';
			}
			if (empty($parent_code)) {
				$strError .= '<li>Parent group code tidak dikenal: '.$parent_code.'</li>';
			} else {
				$arrT = $this->group_model->get_company_by_silsilah($parent_code);
				$strError .= '<li>Update data hanya bisa dilakukan oleh admin '.$arrT['group_name'].'.</li>';
			}
		}
		if(empty($data['tahun_terpilih'])) $strError .= '<li>Tahun masih kosong</li>';
		
		$arrKonfig = array();
		$sql = "select group_id, group_name from _group where id_klien='".$id_klien."' order by length(group_name), group_name";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$arrKonfig[ $item['group_id'] ]['id'] = $item['group_id'];
			$arrKonfig[ $item['group_id'] ]['nama_group'] = $item['group_name'];
			$arrKonfig[ $item['group_id'] ]['nik'] = '';
			$arrKonfig[ $item['group_id'] ]['nama_karyawan'] = '';
			
			$sql =
				"select m.member_name, k.nilai 
				 from _member m, _learning_wallet_konfigurasi k
				 where m.member_nip=k.nilai and m.group_id=k.id_group and k.id_group='".$item['group_id']."' and k.tahun='0' and k.kategori='group' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			if(isset($row[0])) {
				$arrKonfig[ $item['group_id'] ]['nik'] = $row[0]['nilai'];
				$arrKonfig[ $item['group_id'] ]['nama_karyawan'] = $row[0]['member_name'];
			}
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$total = 0;
			$total_kary = 0;
			$arrJuml = $post['juml'];
			$arrLv = $post['lv'];
			
			if(strlen($strError)<=0) {
				$kueri = "delete from _learning_wallet_konfigurasi where tahun='".$data['tahun_terpilih']."' and kategori='group' and id_group='".$data['group_id']."' ";
				$this->db->query($kueri);
				
				$total = 0;
				foreach($arrLv as $key => $val) {
					$nilai = (int) $val;
					$juml = (int) $arrJuml[$key];
					
					$total += ($nilai * $juml);
					$total_kary += $juml;
					
					$did = uniqid('KONF',true);
					$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$data['tahun_terpilih']."', kategori='group', id_group='".$data['group_id']."', nama='juml_kary_".$key."', nilai='".$juml."', catatan='jumlah karyawan' ";
					$this->db->query($kueri);
					
					$did = uniqid('KONF',true);
					$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$data['tahun_terpilih']."', kategori='group', id_group='".$data['group_id']."', nama='lv_kary_".$key."', nilai='".$nilai."', catatan='dana pengembangan' ";
					$this->db->query($kueri);
				}
				
				$did = uniqid('KONF',true);
				$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$data['tahun_terpilih']."', kategori='group', id_group='".$data['group_id']."', nama='total_dana_pengembangan', nilai='".$total."', catatan='total dana pengembangan' ";
				$this->db->query($kueri);
				
				$did = uniqid('KONF',true);
				$kueri = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$data['tahun_terpilih']."', kategori='group', id_group='".$data['group_id']."', nama='total_jumlah_karyawan', nilai='".$total_kary."', catatan='total jumlah karyawan' ";
				$this->db->query($kueri);
				
				// log
				create_log($this->section_id,0,'Konfig Dana Pengembangan','');
				$msg        = "data berhasil disimpan";
                $url_return = site_url('learning_wallet_entitas/kelola_dana?group_id='.$data['group_id'].'&tahun='.$data['tahun_terpilih']);
                flash_notif_success($msg,$url_return);
				exit;
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['arrKonfig'] = $arrKonfig;
		
		$data['form_action']		= site_url('learning_wallet_entitas/kelola_dana_update?group_id='.$data['group_id'].'&tahun='.$data['tahun_terpilih']);

        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Kelola Dana Pengembangan per Level Karyawan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_kelola_dana_update_view';
        $this->load->view('main_view',$data);
	}
	
	function approval(){
		has_access('learningwalletentitas.approval');
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		if(!empty($this->session->userdata('group_id'))) {
			$get_group = $this->group_model->get_child_company($data['group_id'],'self');
		} else {
			$get_group = $this->group_model->get_child_company('','self_child');
		}
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Approval Pelatihan yang Diajukan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_approval_list_view';
        $this->load->view('main_view',$data);
    }
	
	function approval_detail($tahun_terpilih,$group_id,$id_pelatihan){
		has_access('learningwalletentitas.approval');
		
		$data['tahun_terpilih'] = (int) $tahun_terpilih;
		$data['group_id'] = (int) $group_id;
		$data['id_pelatihan'] = (int) $id_pelatihan;
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		$strError = '';
		if(empty($group_id)) {
			$msg = "Tidak dapat memproses data: group belum dipilih.";
			$url_return = site_url('learning_wallet_entitas/approval');
			flash_notif_warning($msg,$url_return);
			exit;
		}
		
		// get detail pelatihan
		$data_pelatihan = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$data['id_pelatihan']));
		$dharga_asli = $data_pelatihan['harga'];
		
		$post = $this->input->post();
		if(!empty($post)) {
			$act = $post['act'];
			$arrStatus = $post['status'];
			$arrCatatan = $post['catatan_approval'];
			
			// $arrNominal = $post['nominal_acc'];
			// khusus N4, cek harga dl
			// dimatikan dl karena sumber data nominal ga mesti dari pengajuan
			/* if($group_id=="4") {
				$jumlX = 0;
				foreach($arrNominal as $key => $val) {
					if($val<$dharga_asli) $jumlX++;
				}
				if($jumlX>0) $strError .= '<li>Ada '.$jumlX.' pengajuan yang nominal disetujui di bawah harga jual.</li>';
			} */
			
			if($act=="sf") {
				$jumlX = 0;
				foreach($arrCatatan as $key => $val) {
					if(empty($arrStatus[$key])) $jumlX++;
				}
				if($jumlX>0) $strError .= '<li>Ada '.$jumlX.' pengajuan ada yang belum diperiksa.</li>';
			}
			
			if(empty($strError)) {
				$this->db->trans_start();
				foreach($arrCatatan as $key => $val) {
					$key = (int) $key;
					$nilai = (int) $arrStatus[$key];
					// $harga = floatval($arrNominal[$key]);
					$catatan = $val;
					
					if(empty($key)) continue;
					
					$addSql = "";
					
					$kode = "0";
					$kode_sdm = "0";
					$kode_sevp = "0";
					
					$kode_sdm = $nilai;
					$kode_sevp = $nilai;
					
					if($act=="sf") {
						$kode = ($nilai=="1")? "40" : "-20";
						$addSql .= ", is_final_sdm='1', is_final_sevp='1' ";
					}
					else {
						$kode = "20";
						$addSql .= ", is_final_sdm='0', is_final_sevp='0' ";
					}
					
					$addSql .= ", kode_status_sdm='".$kode_sdm."', id_verifikator_sdm='".$member_id."', tgl_update_sdm=now() ";
					$addSql .= ", kode_status_sevp='".$kode_sevp."', id_verifikator_sevp='".$member_id."', tgl_update_sevp=now() ";
					
					// simpan final? update tgl update-nya
					if($act=="sf") $addSql .= ", tgl_update_status=now() ";
					
					// khusus N4, bisa update harga
					// dimatikan dl karena sumber data nominal ga mesti dari pengajuan
					/* if($group_id=="4") {
						$addSql .= ", harga='".$harga."' ";
					} */
					
					$kueri = "update _learning_wallet_pengajuan set id_group='".$group_id."', kode_status_current='".$kode."', catatan_approval='".$catatan."' ".$addSql." where id='".$key."' ";
					$this->db->query($kueri);
				}
				$this->db->trans_complete();
				
				if($this->db->trans_status()===false) {
					$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
				} else {
					// log
					create_log($this->section_id,0,'Approval','');
					$msg        = "data berhasil disimpan";
					$url_return = site_url('learning_wallet_entitas/approval?group_id='.$data['group_id'].'&tahun='.$data['tahun_terpilih']);
					flash_notif_success($msg,$url_return);
					exit;
				}
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Detail Approval Pelatihan yang Diajukan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_approval_update_view';
        $this->load->view('main_view',$data);
	}
	
	function wishlist(){
        has_access('learningwalletentitas.wishlist');
		
		$strError = '';
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// admin entitas?
		$data['arr_group'] = $this->group_model->get_child_company("","self_child");
		$juml_group = count($data['arr_group']);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $strError .= '<li>Tahun belum dipilih</li>';
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($data['arr_group'] as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
			
			// selected group_id ada di daftar group?
			if($data['group_id']==$valG['group_id']) $is_user_group_found = true;
		}
		if($is_user_group_found==false) $data['group_id'] = "0";
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		$data['form_opt_tahun'][''] = '';
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Wishlist by Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_wish_list_view';
        $this->load->view('main_view',$data);
    }
	
	function wishlist_peminat(){
		has_access('learningwalletentitas.wishlist');
		
		$strError = '';
		
		$get = $this->input->get();
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['group_id'] = (int) $get['group_id'];
		$data['id_lw_classroom'] = (int) $get['idc'];
		
		// admin entitas?
		$data['arr_group'] = $this->group_model->get_child_company("","self_child");
		$juml_group = count($data['arr_group']);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($data['arr_group'] as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
			
			// selected group_id ada di daftar group?
			if($data['group_id']==$valG['group_id']) $is_user_group_found = true;
		}
		if($is_user_group_found==false) $data['group_id'] = "0";
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		$data['form_opt_tahun'][''] = '';
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		if(empty($data['tahun_terpilih']) && empty($data['id_pelatihan'])) $strError .= '<li>Tahun/kode AgroWallet belum dipilih</li>';
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Wishlist by Peminat';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_wish_peminat_view';
        $this->load->view('main_view',$data);
	}
	
	function dashboard_penyelenggaraan(){
		has_access('learningwalletentitas.dashboard_penyelenggaraan');
		
		$strError = '';
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['bulan'] = (int) $get['bulan'];
		$data['id_lw_classroom'] = (int) $get['id_lw_classroom'];
		$data['kategori'] = $get['kategori'];
		
		// admin entitas?
		$get_group = $this->group_model->get_child_company("","self_child");
		$juml_group = count($get_group);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['bulan']) && empty($data['id_lw_classroom'])) $strError .= '<li>Bulan/Kode AgroWallet belum dipilih</li>';
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		// $get_group = $this->group_model->get_child_company($data['group_id'],'self_child');
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// bulan
		$arrMonth = arrayMonth();
		array_unshift($arrMonth, "");
		$data['form_opt_bulan'] = $arrMonth;
		
		// kategori
		$data['form_opt_kategori'] = array(
			''=>'',
			'diselenggarakan'=>'Pelatihan Jadi Diselenggarakan',
			'batal'=>'Pelatihan Batal Diselenggarakan',
			'pending'=>'Pelatihan Belum Diputuskan',
		);
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Dashboard Penyelenggaraan Pelatihan yang Diajukan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_summary_penyelenggaraan_list_view';
        $this->load->view('main_view',$data);
    }
	
	function dashboard_penyelenggaraan_detail(){
        has_access('learningwalletentitas.dashboard_penyelenggaraan');
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['bulan'] = (int) $get['bulan'];
		$data['kategori'] = $get['kategori'];
		$data['id_pelatihan'] = (int) $get['idc'];
		
		// admin entitas?
		// if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		$data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Detail Dashboard Penyelenggaraan Pelatihan yang Diajukan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_summary_penyelenggaraan_detail_view';
        $this->load->view('main_view',$data);
    }
	
	function approval_massal(){
		has_access('learningwalletentitas.approval_massal');
		
		$data['group_id'] = '';
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		$get_group = $this->group_model->get_child_company($data['group_id'],'self');
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		$data['form_action']        = site_url('learning_wallet_entitas/approval_import');
		$data['section_id']     	= $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Pendaftaran Pelatihan Secara Massal';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_approval_massal_view';
        $this->load->view('main_view',$data);
    }
	
	function approval_import() {
		has_access('learningwalletentitas.approval_massal');
		
		$post = $this->input->post();
		if(empty($post)) {
			redirect(404);
		}
		
		$strError = '';
		
		// allowed file type
		$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		
		$group_id = (int) $post['group_id'];
		$id_lw_classroom = (int) $post['id_lw_classroom'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $group_id = $this->session->userdata('group_id');
		
		if(empty($group_id)) $strError .= '<li>Group belum dipilih</li>';
		if(empty($id_lw_classroom)) $strError .= '<li>Kode AgroWallet belum dipilih</li>';
		if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
			// do nothing
		} else {
			$strError .= '<li>Berkas belum diupload/tidak sesuai dengan ketentuan</li>';
		}
		
		// ada error?
		if(strlen($strError)>0) {
			// do nothing,, yet
		} else {
			if(empty($data['group_id'])) {
				$id_klien = $this->session->userdata('id_klien');
			} else {
				$arrG = $this->group_model->get($data['group_id']);
				$id_klien = $arrG['id_klien'];
			}
			// level karyawan
			$arr_lvl = $this->member_model->getLevelKaryawanKlien($id_klien);
			
			$arr_file = explode('.', $_FILES['file']['name']);
			$extension = end($arr_file);

			if('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$reader->setReadDataOnly(true);

			$spreadsheet = $reader->load($_FILES['file']['tmp_name']);

			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			if ($sheetData){
				foreach($sheetData as $key => $val) {
					if($key=="0") continue; // header
					
					$is_error = 0;
					$error_message = '';
					$member_id = '';
					
					$nik = $val['0'];
					$nama = $val['1'];
					
					if(empty($nik)) {
						$is_error = 1;
						$error_message .= 'Error: nik_karyawan kosong.<br>';
					} else {
						// NIK username apakah sudah ada yg punya?
						$is_exist_group_nip = $this->member_model->get_by_group_nip($group_id,trim($nik));
						if ($is_exist_group_nip){
							$member_id = $is_exist_group_nip['member_id'];
							$nama = $is_exist_group_nip['member_name'];
							$no_wa = $is_exist_group_nip['member_phone'];
							$id_level_karyawan = $is_exist_group_nip['id_level_karyawan'];
						} else {
							$is_error = 1;
							$error_message .= 'nik_karyawan tidak ditemukan, mohon ditambahkan melalui menu member terlebih dahulu.<br>';
						}
					}
					
					$data['sheet_data'][] = array(
						'nik'			=> $nik,
						'nama'			=> $nama,
						'member_id' => $member_id,
						'id_level_karyawan' => $id_level_karyawan,
						'no_wa' => $no_wa,
						'is_error'  => $is_error,
						'error_message' => $error_message
					);
				}
			} else {
				$strError .= '<li>Isi berkas tidak sesuai dengan ketentuan.</li>';
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$msg = "<ul>".$strError."</ul>";
			$url_return = site_url('learning_wallet_entitas/approval_massal');
			flash_notif_warning($msg,$url_return);
			exit;
		}
		
		$data['arr_lvl'] = $arr_lvl;
		$data['group_id'] = $group_id;
		$data['id_lw_classroom'] = $id_lw_classroom;
		
		$data['form_action']        = site_url('learning_wallet_entitas/approval_do_import');
		$data['section_id']     	= $this->section_id;
		$data['page_name']          = 'Learning Wallet Entitas';
		$data['page_sub_name']      = 'Preview Pendaftaran Pelatihan Secara Massal';
		$data['is_content_header']  = TRUE;
		$data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_approval_massal_preview';
		$this->load->view('main_view',$data);
	}
	
	function approval_do_import() {
		has_access('learningwalletentitas.approval_massal');
		
		$post = $this->input->post();
		if(empty($post)) {
			redirect(404);
		}
		
		$strError = '';
		
		$id_user = $this->session->userdata('id');
		
		$group_id = (int) $post['group_id'];
		$id_lw_classroom = (int) $post['id_lw_classroom'];
		$arrMID = $post['mid'];
		$arrLvl = $post['level'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $group_id = $this->session->userdata('group_id');
		
		if(empty($group_id)) $strError .= '<li>Group belum dipilih</li>';
		if(empty($id_lw_classroom)) $strError .= '<li>Kode AgroWallet belum dipilih</li>';
		
		// ada error?
		if(strlen($strError)>0) {
			// do nothing
		} else {
			$arrD = $this->learning_wallet_model->getDetailPelatihan('detail',array('id'=>$id_lw_classroom));
			$tahun = $arrD['tahun'];
			$harga = $arrD['harga'];
			$jumlah_jam = $arrD['jumlah_jam'];
			
			$this->db->trans_start();
			foreach($arrMID as $key => $val) {
				$member_id = (int) $arrMID[$key];
				$id_level_karyawan = (int) $arrLvl[$key];
				
				if(empty($member_id)) continue;
				
				$kueri =
					"insert into _learning_wallet_pengajuan set
						id_group='".$group_id."', 
						id_member='".$member_id."', 
						id_lw_classroom='".$id_lw_classroom."', 
						id_level_karyawan='".$id_level_karyawan."', 
						tahun='".$tahun."', 
						harga='".$harga."', 
						harga_asli='".$harga."', 
						jumlah_jam='".$jumlah_jam."', 
						kode_status_current='40', 
						kode_status_sdm='1', 
						kode_status_sevp='1', 
						id_verifikator_sdm='".$id_user."',
						id_verifikator_sevp='".$id_user."',
						tgl_request=now(),
						tgl_update_status=now(),
						status='aktif',
						is_final_sdm='1',
						is_final_sevp='1'
					 on duplicate key update
						id_group='".$group_id."', 
						id_member='".$member_id."', 
						id_lw_classroom='".$id_lw_classroom."', 
						id_level_karyawan='".$id_level_karyawan."', 
						tahun='".$tahun."', 
						harga='".$harga."', 
						harga_asli='".$harga."', 
						jumlah_jam='".$jumlah_jam."', 
						kode_status_current='40', 
						kode_status_sdm='1', 
						kode_status_sevp='1', 
						id_verifikator_sdm='".$id_user."',
						id_verifikator_sevp='".$id_user."',
						tgl_update_status=now(),
						status='aktif',
						is_final_sdm='1',
						is_final_sevp='1' ";
				$this->db->query($kueri);
			}
			$this->db->trans_complete();
			
			if($this->db->trans_status()===false) {
				$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
			} else {
				// log
				create_log($this->section_id,0,'Daftar Massal','');
				$msg        = "data berhasil disimpan";
				$url_return = site_url('learning_wallet_entitas/dashboard_penyelenggaraan_detail?group_id='.$group_id.'&tahun='.$tahun.'&idc='.$id_lw_classroom);
				flash_notif_success($msg,$url_return);
				exit;
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$msg = "<ul>".$strError."</ul>";
			$url_return = site_url('learning_wallet_entitas/approval_massal');
			flash_notif_warning($msg,$url_return);
			exit;
		}
	}
	
	function rekap_realisasi() {
		has_access('learningwalletentitas.rekap_realisasi');
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		if(!empty($this->session->userdata('group_id'))) {
			$get_group = $this->group_model->get_child_company($data['group_id'],'self');
		} else {
			$get_group = $this->group_model->get_child_company('','self_child');
		}
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Rekap Penggunaan AgroWallet';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_rekap_realisasi_view';
        $this->load->view('main_view',$data);
	}
	
	function do_rekap_realisasi() {
		has_access('learningwalletentitas.rekap_realisasi');
		
		$strError = '';
		
		$id_user = $this->session->userdata('id');
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun = (int) $get['tahun'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $group_id = $this->session->userdata('group_id');
		
		if(empty($group_id)) $strError .= '<li>Group belum dipilih</li>';
		if(empty($tahun)) $strError .= '<li>Tahun belum dipilih</li>';
		
		// ada error?
		if(strlen($strError)>0) {
			// do nothing
		} else {
			$arrK = array();
			
			// yg udah pernah ngajuin sapa aj?
			$sqlF =
				"select distinct p.id_member, m.id_level_karyawan
				 from _learning_wallet_pengajuan p, _member m 
				 where p.id_member=m.member_id and p.tahun='".$tahun."' and m.group_id='".$group_id."' and p.status='aktif' ";
			$resF = $this->db->query($sqlF);
			$rowF = $resF->result_array();
			foreach($rowF as $keyF => $valF) {
				$arrK[ $valF['id_member'] ] = $valF['id_level_karyawan'];
			}
			// yg ikut pelatihan sapa aj?
			$sqlF =
				"select distinct cm.member_id, m.id_level_karyawan
				 from _classroom c, _classroom_member cm, _member m, _learning_wallet_classroom w
				 where 
					c.cr_id=cm.cr_id and cm.member_id=m.member_id and c.id_lw_classroom=w.id and
					cm.id_group='".$group_id."' and w.tahun='".$tahun."' and
					c.cr_status='publish' and cm.member_status='1' and cm.is_pk='0' and w.status='aktif' ";
			$resF = $this->db->query($sqlF);
			$rowF = $resF->result_array();
			foreach($rowF as $keyF => $valF) {
				$arrK[ $valF['member_id'] ] = $valF['id_level_karyawan'];
			}
			
			$this->db->trans_start();
			$kueri = "delete from _learning_wallet_serapan where tahun='".$tahun."' and id_group='".$group_id."' ";
			$this->db->query($kueri);
			
			$last_update = date('Y-m-d H:i:s');
			
			// get data entitas
			$sql = "select group_name, id_klien from _group where group_id='".$group_id."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$nama_group = $row[0]['group_name'];
			$id_klien = $row[0]['id_klien'];
			
			// get target jpl
			$sql = "select nilai from _learning_wallet_konfigurasi where tahun='0' and kategori='umum' and id_group='0' and nama='target_jam_pembelajaran'";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$jpl_target = $row[0]['nilai'];
			
			// get level karyawan
			$arrLv = array();
			$sql = "select id, nama from _member_level_karyawan where id_klien='".$id_klien."' and status='active' order by nama";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			foreach ($row as $key => $val) {
				$id_lv = $key;
				$nama_lv = $val['nama'];
				
				// nominal
				$sql2 = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun."' and kategori='group' and id_group='".$group_id."' and nama='lv_kary_".$id_lv."' ";
				$res2 = $this->db->query($sql2);
				$row2 = $res2->result_array();
				$nominal = $row2[0]['nilai'];
				if(empty($nominal)) {
					$nominal = 0;
				}
				
				$arrLv[ $id_lv ]['id'] = $nama_lv;
				$arrLv[ $id_lv ]['nominal'] = $nominal;
			}
			
			foreach($arrK as $keyK => $valK) {
				$id_member = $keyK;
				$id_level_karyawan = $valK;
				
				$arrData = $this->learning_wallet_model->get_detail_realisasi($group_id,$id_member,$tahun,0,false);
				
				$nama_level_karyawan = $arrLv[$id_level_karyawan]['id'];
				$nominal_target = $arrLv[$id_level_karyawan]['nominal'];
				
				$nominal_total = $arrData['nominal_total'];
				$nominal_realisasi = $arrData['nominal_realisasi'];
				$nominal_rencana = $arrData['nominal_proyeksi'];
				$jpl_total = $arrData['jpl_total'];
				$jpl_realisasi = $arrData['jpl_realisasi'];
				$jpl_rencana = $arrData['jpl_proyeksi'];
				
				$kueri = 
					"insert into _learning_wallet_serapan
					 set
						id='".uniqid("AW")."',
						tahun='".$tahun."',
						id_member='".$id_member."',
						id_group='".$group_id."',
						id_level_karyawan='".$id_level_karyawan."',
						nama_group='".$nama_group."',
						nama_level_karyawan='".$nama_level_karyawan."',
						nominal_target='".$nominal_target."',
						nominal_total='".$nominal_total."',
						nominal_realisasi='".$nominal_realisasi."',
						nominal_rencana='".$nominal_rencana."',
						jpl_target='".$jpl_target."',
						jpl_total='".$jpl_total."',
						jpl_realisasi='".$jpl_realisasi."',
						jpl_rencana='".$jpl_rencana."',
						last_update='".$last_update."'
					";
				$this->db->query($kueri);
			}
			$this->db->trans_complete();
			
			if($this->db->trans_status()===false) {
				$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
			} else {
				// log
				create_log($this->section_id,0,'Rekap AgroWallet '.$tahun.'','');
				$msg        = "data berhasil disimpan";
				$url_return = site_url('learning_wallet_entitas/rekap_realisasi?group_id='.$group_id.'&tahun='.$tahun);
				flash_notif_success($msg,$url_return);
				exit;
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$msg = "<ul>".$strError."</ul>";
			$url_return = site_url('learning_wallet_entitas/rekap_realisasi?group_id='.$group_id.'&tahun='.$tahun.'');
			flash_notif_warning($msg,$url_return);
			exit;
		}
	}
	
	function dashboard_ringkasan(){
        has_access('learningwalletentitas.dashboard_ringkasan');
		
		$strError = '';
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['id_level_karyawan'] = (int) $get['id_level_karyawan'];
		
		// admin entitas?
		$get_group = $this->group_model->get_child_company("","self_child");
		$juml_group = count($get_group);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $strError .= '<li>Tahun belum dipilih</li>';
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		// $get_group = $this->group_model->get_child_company($data['group_id'],'self_child');
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		$data['form_opt_tahun'][''] = '';
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		// get level karyawan
		$data['form_opt_level_karyawan'] = array();
		$data['form_opt_level_karyawan'][''] = '';
		$sql = "select id, nama from _member_level_karyawan where id_klien='".$id_klien."' and status='active' order by nama";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $key => $val) {
			$data['form_opt_level_karyawan'][ $val['id'] ] = $val['nama'];
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat memproses data</b>:<br/><ul>'.$strError.'</ul>');
		}

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Ringkasan Penggunaan AgroWallet (Karyawan)';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_db_ringkasan_K_view';
        $this->load->view('main_view',$data);
    }
	
	function dashboard_ringkasan_entitas(){
		has_access('learningwalletentitas.dashboard_ringkasan_entitas');
		
		$get = $this->input->get();
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['kategori'] = $this->input->get('kategori',true);
		
		$data['group_id'] = '';
		
		// admin entitas?
		$get_group = $this->group_model->get_child_company("","self_child");
		$juml_group = count($get_group);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		// $get_group = $this->group_model->get_child_company($data['group_id'],'self_child');
		$data['form_opt_group'] = array();
		// $data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		// kategori
		$data['form_opt_kategori'] = array();
		$data['form_opt_kategori']['r2'] = 'rencana + realisasi';
		$data['form_opt_kategori']['r1'] = 'realisasi';
		
		$data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Ringkasan penggunaan AgroWallet (Entitas)';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_db_ringkasan_E_view';
        $this->load->view('main_view',$data);
    }
	
	function dashboard_rincian_realisasi() {
		has_access('learningwalletentitas.dashboard_rincian');
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['cnapeln'] = $this->input->get('cnapeln', true);
		
		// admin entitas?
		$get_group = $this->group_model->get_child_company("","self_child");
		$juml_group = count($get_group);
		if($juml_group=="1") $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		if(empty($data['group_id'])) {
			$id_klien = $this->session->userdata('id_klien');
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
		}
		$data['id_klien'] = $id_klien;
		
		// $get_group = $this->group_model->get_child_company($data['group_id'],'self_child');
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
		foreach($get_group as $keyG => $valG) {
			$data['form_opt_group'][$valG['group_id']] = $valG['group_name'];
        }
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		$data['section_id']     = $this->section_id;
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Rincian Realisasi AgroWallet';
		$data['is_content_header']  = TRUE;
		$data['page'] = 'learning_wallet_entitas/learning_wallet_entitas_db_rincian_view';
        $this->load->view('main_view',$data);
	}
}