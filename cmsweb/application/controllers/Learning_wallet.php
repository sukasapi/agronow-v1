<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Learning_wallet extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'group_model',
			'learning_wallet_model',
			'member_model',
        ));
		
		$this->section_id = 44;
    }
	
	function ajax_search_agrowallet_pelatihan(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data_response = array();
        
		$get = $this->input->get();
        $query = isset($get['q'])?$get['q']:NULL;
		$sql =
			"select * 
			 from _learning_wallet_classroom 
			 where status='aktif' 
			 AND (status_penyelenggaraan ='jalan' OR status_penyelenggaraan='-')
			 AND (kode like '%".$query."%' OR nama like '%".$query."%')
			 order by nama limit 20";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $k => $v ) {
			$data_response['results'][$k]['id']    = $v['id'];
			$data_response['results'][$k]['text']    = '['.$v['kode'].'] '.$v['nama'].' ('.$v['tgl_mulai'].' sd '.$v['tgl_selesai'].')';
            $data_response['results'][$k]['kode']  = $v['kode'];
			$data_response['results'][$k]['nama']  = $v['nama'];
			$data_response['results'][$k]['jumlah_jam']  = $v['jumlah_jam'];
			$data_response['results'][$k]['tgl_mulai']  = $v['tgl_mulai'];
			$data_response['results'][$k]['tgl_selesai']  = $v['tgl_selesai'];
			$data_response['results'][$k]['catatan_penyelenggaraan']  = $v['catatan_penyelenggaraan'];
			$data_response['results'][$k]['status_penyelenggaraan']  = $v['status_penyelenggaraan'];
		}
		
		if(count($data_response)<=0) {
			$response_json = NULL;
		} else {
			$response_json = json_encode($data_response);
		}

        echo $response_json;
    }
	
	function l_ajax_dana(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$arrT = array();
		$arrT[1] = 2023; //date("Y");
		$arrT[2] = $arrT[1] + 1;
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and group_name like '%".$keyword."%' ";
		}
		
		// cek hak akses
		// is_superadmin()
		
		/* // hitung jumlah all data
		$recordsTotal = $this->group_model->count_all();
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(group_id) as jumlah
			 from _group where group_status='active' ".$addSql." 
			 order by group_name";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		// get current page data
		$sql =
			"select * 
			 from _group where group_status='active' ".$addSql." 
			 order by group_name
			 limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
            $row['group_id'] = $item['group_id'];
            $row['group_name']  = $item['group_name'];
			
			// status konfigurasi
			foreach($arrT as $key => $val) {
				$tahun = $val;
				$sql2 = "select count(id) as juml from _learning_wallet_konfigurasi where id_group='".$item['group_id']."' and tahun='".$tahun."' ";
				$res2 = $this->db->query($sql2);
				$row2 = $res2->result_array();
				$row['tahun'.$tahun] = (int) $row2[0]['juml'];
				
				$row['verif_sdm'.$tahun] = '';
				$row['verif_sevp'.$tahun] = '';
				
				$sql2 = "select nama, nilai from _learning_wallet_konfigurasi where id_group='".$item['group_id']."' and tahun='".$tahun."' and nama like 'verifikator_%' order by nama ";
				$res2 = $this->db->query($sql2);
				$row2 = $res2->result_array();
				foreach($row2 as $key2 => $val2) {
					$temp = '';
					$sql3 = "select member_name from _member where member_nip='".$val2['nilai']."' and group_id='".$item['group_id']."' ";
					$res3 = $this->db->query($sql3);
					$row3 = $res3->result_array();
					if(isset($row3[0]['member_name'])) {
						$temp = $row3[0]['member_name'];
					}
					
					if($val2['nama']=="verifikator_sdm") {
						$row['verif_sdm'.$tahun] = $temp;
					} else if($val2['nama']=="verifikator_sevp") {
						$row['verif_sevp'.$tahun] = $temp;
					}
				}
			}
            
			$data[] = $row;
        } */
		
		$recordsTotal = 2;
		$recordsFiltered = 2;
		
		$row1['id_member'] = '1';
		$row1['group_name'] = 'PTPN I';
		$row1['level_member'] = 'BOD-X';
		$row1['nik_member'] = 'XXX';
		$row1['nama_member'] = 'XXX';
		$row1['dana_total'] = 'Rp XXX.XXX.XXX';
		$row1['dana_terpakai'] = 'Rp XXX.XXX.XXX';
		$row1['persentase'] = '50';
		
		$row2['id_member'] = '2';
		$row2['group_name'] = 'PTPN I';
		$row2['level_member'] = 'BOD-X';
		$row2['nik_member'] = 'XXX';
		$row2['nama_member'] = 'XXX';
		$row2['dana_total'] = 'Rp XXX.XXX.XXX';
		$row2['dana_terpakai'] = 'Rp XXX.XXX.XXX';
		$row2['persentase'] = '50';
		
		$data[] = $row1;
		$data[] = $row2;
		
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
		//output to json format
        echo json_encode($output);
    }
	
	function l_ajax_jam_pelajaran(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$arrT = array();
		$arrT[1] = date("Y");
		$arrT[2] = $arrT[1] + 1;
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and group_name like '%".$keyword."%' ";
		}
		
		// cek hak akses
		// is_superadmin()
		
		/* // hitung jumlah all data
		$recordsTotal = $this->group_model->count_all();
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(group_id) as jumlah
			 from _group where group_status='active' ".$addSql." 
			 order by group_name";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		// get current page data
		$sql =
			"select * 
			 from _group where group_status='active' ".$addSql." 
			 order by group_name
			 limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
            $row['group_id'] = $item['group_id'];
            $row['group_name']  = $item['group_name'];
			
			// status konfigurasi
			foreach($arrT as $key => $val) {
				$tahun = $val;
				$sql2 = "select count(id) as juml from _learning_wallet_konfigurasi where id_group='".$item['group_id']."' and tahun='".$tahun."' ";
				$res2 = $this->db->query($sql2);
				$row2 = $res2->result_array();
				$row['tahun'.$tahun] = (int) $row2[0]['juml'];
				
				$row['verif_sdm'.$tahun] = '';
				$row['verif_sevp'.$tahun] = '';
				
				$sql2 = "select nama, nilai from _learning_wallet_konfigurasi where id_group='".$item['group_id']."' and tahun='".$tahun."' and nama like 'verifikator_%' order by nama ";
				$res2 = $this->db->query($sql2);
				$row2 = $res2->result_array();
				foreach($row2 as $key2 => $val2) {
					$temp = '';
					$sql3 = "select member_name from _member where member_nip='".$val2['nilai']."' and group_id='".$item['group_id']."' ";
					$res3 = $this->db->query($sql3);
					$row3 = $res3->result_array();
					if(isset($row3[0]['member_name'])) {
						$temp = $row3[0]['member_name'];
					}
					
					if($val2['nama']=="verifikator_sdm") {
						$row['verif_sdm'.$tahun] = $temp;
					} else if($val2['nama']=="verifikator_sevp") {
						$row['verif_sevp'.$tahun] = $temp;
					}
				}
			}
            
			$data[] = $row;
        } */
		
		$recordsTotal = 2;
		$recordsFiltered = 2;
		
		$row1['id_member'] = '1';
		$row1['group_name'] = 'PTPN I';
		$row1['level_member'] = 'BOD-X';
		$row1['nik_member'] = 'XXX';
		$row1['nama_member'] = 'XXX';
		$row1['jam'] = 'XX';
		
		$row2['id_member'] = '2';
		$row2['group_name'] = 'PTPN I';
		$row2['level_member'] = 'BOD-X';
		$row2['nik_member'] = 'XXX';
		$row2['nama_member'] = 'XXX';
		$row2['jam'] = 'XX';
		
		$data[] = $row1;
		$data[] = $row2;
		
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
		
		//output to json format
        echo json_encode($output);
    }
	
	function l_ajax_konfig_group($tahun=NULL,$group_id=NULL){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data = array();
		
		$tahun = (int) $tahun;
        $group_id = (int) $group_id;
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and m.member_name like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		// cek hak akses
		// is_superadmin()
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah all data
		$sql = "select count(id) as jumlah from _learning_wallet_konfig_group where tahun='".$tahun."' and id_group='".$group_id."' and kategori='member'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$recordsTotal = $row[0]['jumlah'];
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(g.id) as jumlah
			 from _learning_wallet_konfig_group g 
				inner join _member m on g.id_member=m.member_id where g.tahun='".$tahun."' and g.id_group='".$group_id."' ".$addSql." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		// get current page data
		$sql =
			"select
				m.member_id, m.member_nip, m.member_name, g.nominal_awal, 
				sum(p.harga) as nominal_terpakai, 
				(g.nominal_awal - coalesce(sum(p.harga), 0)) as nominal_sisa
			 from _learning_wallet_konfig_group g 
				left join _learning_wallet_pengajuan p on g.id_member=p.id_member and p.kode_status_current>0 and p.status='aktif'
				inner join _member m on g.id_member=m.member_id
			 where g.kategori='member' and g.tahun='".$tahun."' and g.id_group='".$group_id."'  ".$addSql."
			 group by g.id
			 ".$order_by." limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['member_nip']  = $item['member_nip'];
			$row['member_name']  = $item['member_name'];
			$row['nominal_awal']  = $item['nominal_awal'];
			$row['nominal_terpakai']  = $item['nominal_terpakai'];
			$row['nominal_sisa']  = $item['nominal_sisa'];
			
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
	
	function l_ajax_usulan(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data = array();
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and (u.last_update like '".$keyword."-%') ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		// cek hak akses
		// is_superadmin()
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah all data
		$sql = "select count(u.id) as jumlah from _learning_wallet_usulan u, _group g, _member m where u.id_member=m.member_id and u.id_group=g.group_id and u.status='aktif' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$recordsTotal = $row[0]['jumlah'];
		
		// hitung jumlah terfilter
		$sqlF = "select count(u.id) as jumlah from _learning_wallet_usulan u, _group g, _member m where u.id_member=m.member_id and u.id_group=g.group_id and u.status='aktif' ".$addSql." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		// get current page data
		$sql =
			"select
				u.id, m.member_name, g.group_name, u.judul, u.detail, u.last_update
			 from _learning_wallet_usulan u, _group g, _member m
			 where u.id_member=m.member_id and u.id_group=g.group_id and u.status='aktif' ".$addSql."
			 ".$order_by." limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['id']  = $item['id'];
			$row['member_name']  = $item['member_name'];
			$row['group_name']  = $item['group_name'];
			$row['judul']  = $item['judul'];
			$row['detail']  = $item['detail'];
			$row['last_update']  = $item['last_update'];
			
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
	
	function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->learning_wallet_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['id']           = $item->id;
            $row['kode']         = $item->kode;
            $row['nama']          = $item->nama;
			$row['tgl_mulai']     = $item->tgl_mulai;
			$row['tgl_selesai']   = $item->tgl_selesai;
			$row['status']        = $item->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->learning_wallet_model->count_all(),
            "recordsFiltered" => $this->learning_wallet_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
	
	function index(){
		redirect(404);
	}

    function pelatihan(){

        has_access('learningwallet.pelatihan_view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Daftar Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet/learning_wallet_list_view';
        $this->load->view('main_view',$data);
    }
	
	function usulan_pelatihan(){

        has_access('learningwallet.usulan_view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Daftar Usulan Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet/learning_wallet_usulan_view';
        $this->load->view('main_view',$data);
    }
	
	function approval(){
		has_access('learningwallet.konfig_approval');
		
		$strError = '';
		$id_klien = $this->session->userdata('id_klien');
		
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
			$arrPK = $post['konfig'];
			
			// untuk tampilan 
			foreach($arrKonfig as $key => $val) {
				$id_group = $key;
				$nik_sdm = $arrPK[$key];
				$nama_group = $val['nama_group'];
				
				$arrKonfig[$key]['nik'] = $nik_sdm;
				
				if(!empty($nik_sdm)) {
					$sql = "select member_id from _member where member_nip='".$nik_sdm."' and group_id='".$id_group."' ";
					$res = $this->db->query($sql);
					$row = $res->result_array();
					if(empty($row)) {
						$strError .= '<li>NIK Approval SDM ('.$nik_sdm.') tidak ditemukan pada group '.$nama_group.'. Kemungkinan salah group atau ybs belum pernah login ke AgroNow.</li>';
					}
				}
			}
			
			if(strlen($strError)<=0) {
				$this->db->query($sql);
				foreach($arrKonfig as $key => $val) {
					$id_group = $key;
					$nik_sdm = $val['nik'];
					
					$sql = "select id from _learning_wallet_konfigurasi where id_group='".$id_group."' and tahun='0' and kategori='group' ";
					$res = $this->db->query($sql);
					$row = $res->result_array();
					if(empty($row)) { // insert data
						$did = uniqid("KONF",true);
						$sql = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='0', kategori='group', id_group='".$id_group."', nama='verifikator_sdm', nilai='".$nik_sdm."', catatan='verifikator sdm' ";
						$this->db->query($sql);
					} else {
						$did = $row[0]['id'];
						$sql = "update _learning_wallet_konfigurasi set tahun='0', kategori='group', id_group='".$id_group."', nama='verifikator_sdm', nilai='".$nik_sdm."', catatan='verifikator sdm' where id='".$did."' ";
						$this->db->query($sql);
					}
				}
				// log
				create_log($this->section_id,0,'Konfigurasi Approval','');
				$msg        = "data konfigurasi berhasil disimpan";
                $url_return = site_url('learning_wallet/approval/');
                flash_notif_success($msg,$url_return);
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['arrKonfig'] = $arrKonfig;
		
		$data['form_action_update']    = site_url('learning_wallet/approval/');

        $data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Konfigurasi Approval Learning Wallet';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet/learning_wallet_approval_view';
        $this->load->view('main_view',$data);
	}

	/*
    function update($tahun=NULL,$group_id=NULL){
        // has_access('digitalsop.edit');
		
		$strError = '';
		
		$tahun = (int) $tahun;
        $group_id = (int) $group_id;

        if (!is_my_group($group_id)){
            redirect(404);
        }
		
		$get_group = $this->group_model->get($group_id);
		$nama_group = $get_group['group_name'];
		$group_id = $get_group['group_id'];
		$id_klien = $get_group['id_klien'];
		
		if($tahun<1990) $strError .= '<li>Tahun '.$tahun.' tidak dikenal</li>';
		if(empty($group_id)) $strError .= '<li>Group tidak dikenal</li>';
		
		$arrKonfig = array();
		$arrKonfig['verifikator_sdm']['label'] = "NIK Verifikator (SDM)";
		$arrKonfig['verifikator_sdm']['nilai'] = "";
		$arrKonfig['verifikator_sdm']['wajib_isi'] = true;
		$arrKonfig['verifikator_sdm']['format_currency'] = false;
		
		/* $arrKonfig['verifikator_sevp']['label'] = "NIK Verifikator (SEVP)";
		$arrKonfig['verifikator_sevp']['nilai'] = "";
		$arrKonfig['verifikator_sevp']['wajib_isi'] = true;
		$arrKonfig['verifikator_sevp']['format_currency'] = false;
		$arrKonfig['anggaran_entitas']['label'] = "Total Anggaran Setahun: Rp. ";
		$arrKonfig['anggaran_entitas']['nilai'] = "";
		$arrKonfig['anggaran_entitas']['wajib_isi'] = true;
		$arrKonfig['anggaran_entitas']['format_currency'] = true; */
		
		// get all level karyawan
		/* $sql = "select * from _member_level_karyawan where id_klien='".$id_klien."' and status='active' order by nama";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$arrKonfig['plafon_'.$item['id']]['label'] = "Plafon per Karyawan ".$item['nama']." Setahun: Rp. ";
			$arrKonfig['plafon_'.$item['id']]['nilai'] = "";
			$arrKonfig['plafon_'.$item['id']]['wajib_isi'] = false;
			$arrKonfig['plafon_'.$item['id']]['format_currency'] = true;
		} *-/
		
		// get current data dari DB
		foreach($arrKonfig as $key => $val) {
			$sql = "select nilai from _learning_wallet_konfigurasi where tahun='".$tahun."' and id_group='".$group_id."' and nama='".$key."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			if(isset($row[0]['nilai'])) {
				$arrKonfig[$key]['nilai'] = $row[0]['nilai'];
			} else {
				$arrKonfig[$key]['nilai'] = "";
			}
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$arrPK = $post['konfig'];
			
			// untuk tampilan 
			foreach($arrKonfig as $key => $val) {
				$nilai = $arrPK[$key];
				if($val['format_currency']==true) $nilai = (int) $nilai;
				
				$arrKonfig[$key]['nilai'] = $nilai;
			}
			
			$nik_sdm = $arrPK['verifikator_sdm'];
			// $nik_sevp = $arrPK['verifikator_sevp'];
			// $total_anggaran = $arrPK['anggaran_entitas'];
			
			if(empty($nik_sdm)) {
				$strError .= '<li>Verifikator SDM masih kosong</li>';
			} else {
				$sql = "select member_id from _member where member_nip='".$nik_sdm."' and group_id='".$group_id."' ";
				$res = $this->db->query($sql);
				$row = $res->result_array();
				if(empty($row)) {
					$strError .= '<li>NIK Verifikator SDM tidak ditemukan pada group '.$nama_group.'. Kemungkinan belum pernah login ke AgroNow.</li>';
				}
			}
			
			/* if(empty($nik_sevp)) {
				$strError .= '<li>Verifikator SEVP masih kosong</li>';
			} else {
				$sql = "select member_id from _member where member_nip='".$nik_sevp."' and group_id='".$group_id."' ";
				$res = $this->db->query($sql);
				$row = $res->result_array();
				if(empty($row)) {
					$strError .= '<li>NIK Verifikator SEVP tidak ditemukan pada group '.$nama_group.'. Kemungkinan belum pernah login ke AgroNow.</li>';
				}
			}
			if(!empty($nik_sdm) && !empty($nik_sevp) && $nik_sdm==$nik_sevp) {
				$strError .= '<li>Verifikator SDM dan SEVP tidak boleh sama.</li>';
			}
			if($total_anggaran<=0) $strError .= '<li>Total anggaran masih kosong</li>'; *-/
			
			if(strlen($strError)<=0) {
				$sql = "delete from _learning_wallet_konfigurasi where tahun='".$tahun."' and id_group='".$group_id."' ";
				$this->db->query($sql);
				foreach($arrKonfig as $key => $val) {
					$did = uniqid("KONF",true);
					$sql = "insert into _learning_wallet_konfigurasi set id='".$did."', tahun='".$tahun."', kategori='group', id_group='".$group_id."', nama='".$key."', nilai='".$val['nilai']."', catatan='".$val['label']."' ";
					$this->db->query($sql);
				}
				// log
				create_log($this->section_id,0,'Konfigurasi','[tahun:'.$tahun.'][group:'.$group_id.']');
				$msg        = "data konfigurasi berhasil disimpan";
                $url_return = site_url('learning_wallet/update/'.$tahun.'/'.$group_id);
                flash_notif_success($msg,$url_return);
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['arrKonfig'] = $arrKonfig;
		$data['tahun'] = $tahun;
		$data['group_id'] = $group_id;
		$data['nama_group'] = $nama_group;
		$data['form_action_update']    = site_url('learning_wallet/update/'.$tahun.'/'.$group_id);
		$data['form_action_upload']    = site_url('learning_wallet/upload/'.$tahun.'/'.$group_id);
		
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Update Konfigurasi Learning Wallet Tahun '.$tahun;
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet/learning_wallet_update_view';
        $this->load->view('main_view',$data);
    }
	*/
	
	/* function upload($tahun=NULL,$group_id=NULL){
        // has_access('digitalsop.edit');
		
		$strError = '';
		$strWarning = '';
		
		$tahun = (int) $tahun;
        $group_id = (int) $group_id;
		
		if (!is_my_group($group_id)){
            redirect(404);
        }
		
		// get data entitas
		$get_group = $this->group_model->get($group_id);
		$group_id = $get_group['group_id'];
		
		if($tahun<1990) $strError .= '<li>Tahun '.$tahun.' tidak dikenal.</li>';
		if(empty($group_id)) $strError .= '<li>Group tidak dikenal.</li>';
		
		$arrData = array();
		$post = $this->input->post();
		if(!empty($post)) {
			$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

			if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
				// olah filenya
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
					$baris = 0;
					$juml_data = count($sheetData);
					for($i=0;$i<$juml_data;$i++) {
						$baris++;
						if($baris=="1") {
							continue;
						}
						
						$nik = trim($sheetData[$i]['0']);
						$nama = trim($sheetData[$i]['1']);
						$nominal = (int) $sheetData[$i]['2'];
						
						if(empty($nik)) {
							$strWarning .= '<li>Baris '.$baris.' diabaikan, kolom nik masih kosong</li>';
							continue;
						}
						if(empty($nama)) {
							$strWarning .= '<li>Baris '.$baris.' diabaikan, kolom nama masih kosong</li>';
							continue;
						}
						if($nominal<=0) {
							$strWarning .= '<li>Baris '.$baris.' diabaikan, kolom nominal masih kosong</li>';
							continue;
						}
						
						$sql = "select member_id from _member where group_id='".$group_id."' and member_nip='".$nik."' and member_status='active' ";
						$res = $this->db->query($sql);
						$row = $res->result_array();
						if(empty($row)){
							$strWarning .= '<li>Baris '.$baris.' diabaikan, karyawan dengan NIK <b>'.$nik.'</b> tidak ditemukan.</li>';
							continue;
						}
						
						$arrData[ $row[0]['member_id'] ] = $nominal;
					}
				}
			} else {
				$strError .= '<li>Berkas belum diupload.</li>';
			}
		}
		
		// olah data
		if(strlen($strError)<=0) {
			$nominal_total = 0;
			$this->db->trans_start();
			$kueri = "delete from _learning_wallet_konfig_group where id_group='".$group_id."' and tahun='".$tahun."' ";
			$this->db->query($kueri);
			foreach($arrData as $key => $val) {
				$nominal_total += $val;
				
				$did = uniqid("LW",true);
				$kueri = "insert into _learning_wallet_konfig_group set id='".$did."', id_group='".$group_id."', tahun='".$tahun."', id_member='".$key."', nominal_awal='".$val."', kategori='member' ";
				$this->db->query($kueri);
			}
			
			// hitung nominal keseluruhan
			$did = uniqid("LW",true);
			$kueri = "insert into _learning_wallet_konfig_group set id='".$did."', id_group='".$group_id."', tahun='".$tahun."', id_member='0', nominal_awal='".$nominal_total."', kategori='dana_awal_entitas' ";
			$this->db->query($kueri);
			
			$this->db->trans_complete();
			if($this->db->trans_status()===false) {
				$strError .= "<li>Silahkan coba lagi beberapa saat lagi.</li>";
			} else {
				
			}
		}
		
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		} else {
			if(strlen($strWarning)>0) {
				$info = 'Data berhasil disimpan dengan catatan:<br/><ul>'.$strWarning.'</ul>';
			} else {
				$info = 'Data berhasil disimpan.';
			}
			
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'info');
			$this->session->set_flashdata('flash_msg_status', '1');
			$this->session->set_flashdata('flash_msg_text', $info);
		}
		
		redirect(base_url('learning_wallet/update/'.$tahun.'/'.$group_id));
		exit;
	}
	
	function download_template_wallet($tahun=NULL,$group_id=NULL){
        // has_access('digitalsop.edit');
		
		$strError = '';
		
		$tahun = (int) $tahun;
        $group_id = (int) $group_id;

        if (!is_my_group($group_id)){
            redirect(404);
        }
		
		// get data entitas
		$get_group = $this->group_model->get($group_id);
		$group_name = $get_group['group_name'];
		$group_id = $get_group['group_id'];
		
		// get data
		$sql =
			"select m.member_nip, m.member_name, g.nominal_awal
			 from _learning_wallet_konfig_group g, _member m
			 where g.id_member=m.member_id and g.kategori='member' and g.id_group='".$group_id."' and g.tahun='".$tahun."'
			 order by m.member_name";
		$res = $this->db->query($sql);
		$row = $res->result_array();
						
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'nik');
		$sheet->setCellValue('B1', 'nama');
		$sheet->setCellValue('C1', 'nominal_tanpa_format');
		
		$i = 2;
		foreach($row as $key => $val) {
			$sheet->setCellValue('A'.$i, $val['member_nip']);
			$sheet->setCellValue('B'.$i, $val['member_name']);
			$sheet->setCellValue('C'.$i, $val['nominal_awal']);
			
			$i++;
		}
		
		$writer = new Xlsx($spreadsheet);
        $filename = 'learning_wallet_'.$tahun.'_'.slugify($group_name);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
		
		exit;
	} */
	
	function dashboard_utama() {
		has_access('learningwallet.dashboard');
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		$addSql = '';
		$addSql2 = '';
		$addSql3 = '';
		$addSql4 = '';
		$addSql5 = '';
		$id_klien = $this->session->userdata('id_klien');
		
		// bulan
		$arrBulan = arrayMonth();
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$arrTahun = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$arrTahun[$i] = $i;
		}
		
		// level karyawan
		$arrLv = $this->member_model->getLevelKaryawanKlien($id_klien);
		
		// arr konfig untuk informasi
		$arrKonfig = array();
		$arrKonfig['pengajuan_mulai'] = '';
		$arrKonfig['pengajuan_selesai'] = '';
		$arrKonfig['approval_mulai'] = '';
		$arrKonfig['approval_selesai'] = '';
		$arrKonfig['target_jam_pembelajaran'] = '';
		foreach($arrKonfig as $key => $val) {
			$sql = "select nilai from _learning_wallet_konfigurasi where nama='".$key."' and kategori='umum' and tahun='0' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$arrKonfig[$key] = $row[0]['nilai'];
		}
		
		$get = $this->input->get();
		if(!empty($get)) {
			$tahun = (int) $get['tahun'];
			$id_lv = (int) $get['id_lv'];
			$id_group = (int) $get['id_group'];
		}
		if(empty($tahun)) $tahun = date("Y");
		
		// pencarian
		if(!empty($tahun)) $addSql .= " and p.tahun='".$tahun."' ";
		if(!empty($id_lv)) {
			$addSql .= " and p.id_level_karyawan='".$id_lv."' ";
			$addSql3 .= " and nama='juml_kary_".$id_lv."' ";
			$addSql4 .= " and nama='lv_kary_".$id_lv."' ";
			$addSql5 .= " and id_level_karyawan='".$id_lv."' ";
		}
		if(!empty($id_group)) {
			$addSql .= " and p.id_group='".$id_group."' ";
			$addSql2 .= " and group_id='".$id_group."' ";
		}
		$search_params = "tahun=".$tahun."&id_lv=".$id_lv."&id_group=".$id_group;
		
		$jumlah_perusahaan = 0;
		$chart0_max = 0;
		$arrGroup = array();
		
		// get all group
		$sqlT = "select group_id, group_name from _group where id_klien='".$id_klien."' order by length(group_name), group_name";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		foreach ($rowT as $itemT) {
			$dgroup_id = $itemT['group_id'];
			$dgroup_nama = $itemT['group_name'];
			
			$arrGroup[$dgroup_id] = $dgroup_nama;
		}
		
		// get data
		$sqlT = "select group_id, group_name from _group where id_klien='".$id_klien."' ".$addSql2." order by length(group_name), group_name";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		foreach ($rowT as $itemT) {
			$dgroup_id = $itemT['group_id'];
			$dgroup_nama = $itemT['group_name'];
			
			// jumlah perusahaan yg ditampilkan di chart
			$jumlah_perusahaan++;
			
			$chart0_group_list .= "'".$dgroup_nama."',";
			$chart0_id_group_list .= "'".$dgroup_id."',";
			
			$juml_disetujui = 0;
			$juml_dibatalkan = 0;
			$juml_menunggu_persetujuan = 0;
			$juml_ditolak = 0;
			$nominal_terserap = 0;
			$nominal_total = 0;
			$jpl_terserap = 0;
			
			// chart pengajuan pelatihan
			$sql =
				"select 
					sum(case when p.kode_status_current=40 then 1 else 0 end) as disetujui,
					sum(case when p.kode_status_current=-40 or p.kode_status_current=-50 or p.kode_status_current=-60 then 1 else 0 end) as dibatalkan,
					sum(case when p.kode_status_current>0 and p.kode_status_current<30 then 1 else 0 end) as menunggu_persetujuan,
					sum(case when p.kode_status_current=-20 then 1 else 0 end) as ditolak,
					sum(case when p.kode_status_current=40 then p.harga else 0 end) as nominal_terserap,
					sum(case when p.kode_status_current=40 then p.jumlah_jam else 0 end) as jpl_terserap
				from _learning_wallet_pengajuan p
				where p.status='aktif' and p.id_group='".$dgroup_id."' ".$addSql." ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$val = $row[0];
			
			$juml_disetujui = $val['disetujui'];
			$juml_dibatalkan = $val['dibatalkan'];
			$juml_menunggu_persetujuan = $val['menunggu_persetujuan'];
			$juml_ditolak = $val['ditolak'];
			$nominal_terserap = $val['nominal_terserap'];
			$jpl_terserap = $val['jpl_terserap'];
			
			if(empty($juml_disetujui)) $juml_disetujui = 0;
			if(empty($juml_dibatalkan)) $juml_dibatalkan = 0;
			if(empty($juml_menunggu_persetujuan)) $juml_menunggu_persetujuan = 0;
			if(empty($juml_ditolak)) $juml_ditolak = 0;
			if(empty($nominal_terserap)) $nominal_terserap = 0;
			if(empty($jpl_terserap)) $jpl_terserap = 0;
			
			// jumlah karyawan
			$sql2 = "select sum(nilai) as juml_karyawan from _learning_wallet_konfigurasi where tahun='".$tahun."' and id_group='".$dgroup_id."' and catatan='jumlah karyawan' ".$addSql3." ";
			$res2 = $this->db->query($sql2);
			$row2 = $res2->result_array();
			$val2 = $row2[0];
			$juml_karyawan = $val2['juml_karyawan'];
			
			// total dana pengembangan entitas - level
			$sql2 = "select sum(nilai) as nilai from _learning_wallet_konfigurasi where tahun='".$tahun."' and kategori='group' and id_group='".$dgroup_id."' and catatan='dana pengembangan' ".$addSql4." ";
			$res2 = $this->db->query($sql2);
			$row2 = $res2->result_array();
			$val2 = $row2[0];
			$nominal_level = $val2['nilai'] * $juml_karyawan;
			if(empty($nominal_level)) $nominal_level = 0;
			
			// total dana pengembangan entitas - top up
			$sql2 = "select sum(nominal) as nilai from _learning_wallet_konfig_group where kategori='member_total_topup' and tahun='".$tahun."' and id_group='".$dgroup_id."' ".$addSql5." ";
			$res2 = $this->db->query($sql2);
			$row2 = $res2->result_array();
			$val2 = $row2[0];
			$nominal_topup = $val2['nilai'];
			if(empty($nominal_topup)) $nominal_topup = 0;
			
			$nominal_anggaran = $nominal_level + $nominal_topup;
			$nominal_anggaran_sisa = $nominal_anggaran - $nominal_terserap;
			
			$persen_terserap = (empty($nominal_anggaran))? 0 : ($nominal_terserap/$nominal_anggaran)*100;
			$persen_terserap = number_format($persen_terserap,3);
			$persen_sisa = (empty($nominal_anggaran))? 0 : ($nominal_anggaran_sisa/$nominal_anggaran)*100;
			$persen_sisa = number_format($persen_sisa,3);
			
			// jpl
			$jpl_target = $juml_karyawan * $arrKonfig['target_jam_pembelajaran'];
			$jpl_sisa = $jpl_target - $jpl_terserap;
			
			$persen_jpl_target = ($jpl_target==0)? 0 : 100;
			$persen_jpl_terserap = (empty($jpl_target))? 0 : ($jpl_terserap/$jpl_target)*100;
			$persen_jpl_terserap = number_format($persen_jpl_terserap,3);
			$persen_jpl_sisa_target = (empty($jpl_target))? 0 : ($jpl_sisa/$jpl_target)*100;
			$persen_jpl_sisa_target = number_format($persen_jpl_sisa_target,3);
			
			$persen_jpl_sisa_target = $persen_jpl_target - $persen_jpl_terserap;
			
			// data ori
			$data_nominal .= "[".$nominal_anggaran_sisa.",".$nominal_terserap."],";
			$data_jpl .= "[".$jpl_sisa.",".$jpl_terserap."],";
			
			// data chart
			$chart0_data['disetujui'] .= $juml_disetujui.",";
			$chart0_data['dibatalkan'] .= $juml_dibatalkan.",";
			$chart0_data['menunggu_persetujuan'] .= $juml_menunggu_persetujuan.",";
			$chart0_data['ditolak'] .= $juml_ditolak.",";
			
			$chart1_data['anggaran_sisa'] .= $persen_sisa.",";
			$chart1_data['terserap'] .= $persen_terserap.",";
			
			$chart2_data['jpl_sisa_target'] .= $persen_jpl_sisa_target.",";
			$chart2_data['jpl_realisasi'] .= $persen_jpl_terserap.",";
			
			// untuk batas tertinggi chart 0
			$temp_total = $juml_disetujui + $juml_dibatalkan + $juml_menunggu_persetujuan + $juml_ditolak;
			if($temp_total>$chart0_max) $chart0_max = $temp_total;
		}
		
		$chart0_series =
			"{ range: '1', name: 'Dibatalkan', color: '#0071BC', data: [".$chart0_data['dibatalkan']."] },
			 { range: '2', name: 'Menunggu Persetujuan', color: '#FFC000', data: [".$chart0_data['menunggu_persetujuan']."] },
			 { range: '3', name: 'Ditolak', color: '#E63928', data: [".$chart0_data['ditolak']."] },
			 { range: '4', name: 'Disetujui', color: '#2CB34C', data: [".$chart0_data['disetujui']."] }, ";
		$chart1_series =
			"{ range: '1', name: 'Ketersediaan Dana', color: '#FFC000', data: [".$chart1_data['anggaran_sisa']."] },
			 { range: '2', name: 'Dana Terserap', color: '#0071BC', data: [".$chart1_data['terserap']."] }, ";
		$chart2_series =
			"{ range: '1', name: 'Sisa Target jam Pembelajaran', color: '#FFC000', data: [".$chart2_data['jpl_sisa_target']."] },
			 { range: '2', name: 'Realisasi Jam Pembelajaran', color: '#0071BC', data: [".$chart2_data['jpl_realisasi']."] }, ";
		
		$data['request']['bulan'] = $bulan;
		$data['request']['tahun'] = $tahun;
		$data['request']['id_lv'] = $id_lv;
		$data['request']['id_group'] = $id_group;
		
		$chart_height = $jumlah_perusahaan*22;
		if($chart_height<200) $chart_height = 200;
		$data['chart_height'] = $chart_height;
		$data['chart0_max'] = $chart0_max;
		
		$data['data_nominal'] = $data_nominal;
		$data['data_jpl'] = $data_jpl;
		
		$data['chart0_group_list'] = $chart0_group_list;
		$data['chart0_id_group_list'] = $chart0_id_group_list;
		$data['chart0_series'] = $chart0_series;
		$data['chart1_series'] = $chart1_series;
		$data['chart2_series'] = $chart2_series;
		
		$data['arrBulan'] = $arrBulan;
		$data['arrTahun'] = $arrTahun;
		$data['arrLv'] = $arrLv;
		$data['arrGroup'] = $arrGroup;
		$data['arrKonfig'] = $arrKonfig;
		$data['search_params'] = $search_params;
		
		$data['form_action']    = site_url('learning_wallet/dashboard_utama');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Dashboard Utama ';
		$data['page'] = 'learning_wallet/learning_wallet_dashboard_utama_view';
        $this->load->view('main_view',$data);
	}
	
	function dashboard_pengajuan() {
		has_access('learningwallet.dashboard');
		
		redirect(404);
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// bulan
		$arrBulan = arrayMonth();
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$arrTahun = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$arrTahun[$i] = $i;
		}
		
		// arr konfig untuk informasi
		$arrKonfig = array();
		$arrKonfig['pengajuan_mulai'] = '';
		$arrKonfig['pengajuan_selesai'] = '';
		$arrKonfig['approval_mulai'] = '';
		$arrKonfig['approval_selesai'] = '';
		foreach($arrKonfig as $key => $val) {
			$sql = "select nilai from _learning_wallet_konfigurasi where nama='".$key."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$arrKonfig[$key] = $row[0]['nilai'];
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$bulan = (int) $post['bulan'];
			$tahun = (int) $post['tahun'];
		}
		
		if(empty($bulan)) $bulan = date("m");
		if(empty($tahun)) $tahun = date("Y");
		
		$dbulan = "";
		if(!empty($bulan)) {
			$dbulan = ($bulan<10)? '0'.$bulan : $bulan;
			$dbulan = '-'.$dbulan;
		}
		$tb = $tahun.$dbulan;
		
		$arrG = array();
		$jsonDT = '';
		$i = 0;
		$sql =
			"select
				c.id, c.nama, c.tgl_mulai, c.durasi_hari, c.minimal_peserta,
				sum(if(p.is_final_sdm='1' and p.kode_status_current='40', 1, 0)) as juml_approve, 
				sum(if(p.is_final_sdm='0',1,0)) as juml_waiting,
				group_concat(if(p.is_final_sdm='0', p.id_group, NULL)) as list_group
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p
			 where c.id=p.id_lw_classroom and c.status='aktif' and p.tgl_request like '".$tb."-%'
			 group by c.id
			 order by c.nama ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$i++;
			
			$arrT = explode(',',$val['list_group']);
			foreach($arrT as $keyT => $valT) {
				if($valT>0) {
					$id_group = $valT;
					$arrG[$id_group]['id'] = $id_group;
					$arrG[$id_group]['nama'] = '';
					$arrG[$id_group]['jumlah']++;
				}
			}
			
			/* $jsonDT .=
				'{
				"no": "'.$i.'",
				"id": "'.$val['id'].'",
				"nama": "'.$val['nama'].'",
				"tgl_mulai": "'.$val['tgl_mulai'].'",
				"juml_minimal": "'.$val['minimal_peserta'].'",
				"juml_approve": "'.$val['juml_approve'].'",
				"juml_waiting": "'.$val['juml_waiting'].'",
				}, '; */
		}
		
		$sql = "select group_id, group_name from _group where id_klien='1' order by length(group_name), group_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$jsonDT .=
				'{
					"no": "'.$val['group_id'].'",
					"id": "'.$val['group_id'].'",
					"nama": "'.$val['group_name'].'",
					"juml_ok": "'.rand(0,20).'",
					"juml_xok": "'.rand(0,20).'",
					"juml_waiting": "'.rand(0,20).'",
					}, ';
		}
		
		foreach($arrG as $key => $val) {
			$sql = "select group_name from _group where group_id='".$key."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			
			$arrG[$key]['nama'] = $row[0]['group_name'];
		}
		
		$data['request']['bulan'] = $bulan;
		$data['request']['tahun'] = $tahun;
		
		$data['jsonDT'] = $jsonDT;
		$data['arrBulan'] = $arrBulan;
		$data['arrTahun'] = $arrTahun;
		$data['arrKonfig'] = $arrKonfig;
		$data['arrG'] = $arrG;
		
		$data['form_action']    = site_url('learning_wallet/dashboard_pengajuan');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Tracking Pengajuan Pelatihan';
		$data['page'] = 'learning_wallet/learning_wallet_dashboard_pengajuan_view';
        $this->load->view('main_view',$data);
	}
	
	function tracking_penyelenggaraan() {
		has_access('learningwallet.tracking_penyelenggaraan_view');
		
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// bulan
		$arrBulan = arrayMonth();
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$arrTahun = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$arrTahun[$i] = $i;
		}
		
		// status penyelenggaraan
		$arrSP = $this->learning_wallet_model->status_penyelenggaraan();
		
		// arr konfig untuk informasi
		$arrKonfig = array();
		$arrKonfig['pengajuan_mulai'] = '';
		$arrKonfig['pengajuan_selesai'] = '';
		$arrKonfig['approval_mulai'] = '';
		$arrKonfig['approval_selesai'] = '';
		foreach($arrKonfig as $key => $val) {
			$sql = "select nilai from _learning_wallet_konfigurasi where nama='".$key."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$arrKonfig[$key] = $row[0]['nilai'];
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$kode = $post['kode'];
			$bulan = (int) $post['bulan'];
			$tahun = (int) $post['tahun'];
			$sp = $post['sp'];
			$kategori = $post['kategori'];
		}
		
		// if(empty($bulan)) $bulan = date("m");
		if(empty($tahun)) $tahun = date("Y");
		if(empty($kategori)) $kategori = 'pelaksanaan';
		if(empty($sp)) $sp = '-';
		
		$dbulan = "";
		if(!empty($bulan)) {
			$dbulan = ($bulan<10)? '0'.$bulan : $bulan;
			$dbulan = '-'.$dbulan;
		}
		$tb = $tahun.$dbulan;
		
		$addSql = "";
		if(!empty($kode)) {
			$addSql .= " and c.kode like '%".$kode."%' ";
		}
		if(!empty($sp)) {
			$addSql .= " and c.status_penyelenggaraan='".$sp."' ";
		}
		if($kategori=="pengajuan") {
			$addSql .= " and p.tgl_request like '".$tb."-%' ";
		} else if($kategori=="pelaksanaan") {
			$addSql .= " and c.tgl_mulai like '".$tb."-%' ";
		}
		
		$arrG = array();
		$jsonDT = '';
		$i = 0;
		$sql =
			"select
				c.id, c.kode, c.nama, c.tgl_mulai, c.durasi_hari, c.minimal_peserta, c.status_penyelenggaraan,c.pic,
				sum(if(p.is_final_sdm='1' and p.kode_status_current in ('40'), 1, 0)) as juml_approve, 
				sum(if(p.is_final_sdm='1' and p.kode_status_current not in ('40'), 1, 0)) as juml_disapprove, 
				sum(if(p.is_final_sdm='0',1,0)) as juml_waiting,
				group_concat(if(p.is_final_sdm='0', p.id_group, NULL)) as list_group
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p
			 where c.id=p.id_lw_classroom and c.status='aktif' and p.status='aktif' ".$addSql."
			 group by c.id
			 order by c.tgl_mulai, c.nama ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$i++;
			
			$tercapai = ($val['juml_approve']>=$val['minimal_peserta'])? 'iya' : 'tidak';
			
			$arrT = explode(',',$val['list_group']);
			foreach($arrT as $keyT => $valT) {
				if($valT>0) {
					$id_group = $valT;
					$arrG[$id_group]['id'] = $id_group;
					$arrG[$id_group]['nama'] = '';
					$arrG[$id_group]['jumlah']++;
				}
			}
			
			$jsonDT .=
				'{
				"no": "'.$i.'",
				"id": "'.$val['id'].'",
				"kode": "'.$val['kode'].'",
				"nama": "'.$val['nama'].'",
				"tgl_mulai": "'.$val['tgl_mulai'].'",
				"juml_minimal": "'.$val['minimal_peserta'].'",
				"juml_approve": "'.$val['juml_approve'].'",
				"juml_waiting": "'.$val['juml_waiting'].'",
				"juml_disapprove": "'.$val['juml_disapprove'].'",
				"tercapai": "'.$tercapai.'",
				"status": "'.$arrSP[ $val['status_penyelenggaraan'] ].'",
				"pic": "'.$val['pic'].'",
				}, ';
		}
		
		foreach($arrG as $key => $val) {
			$sql = "select group_name from _group where group_id='".$key."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			
			$arrG[$key]['nama'] = $row[0]['group_name'];
		}
		
		$data['request']['kode'] = $kode;
		$data['request']['bulan'] = $bulan;
		$data['request']['tahun'] = $tahun;
		$data['request']['sp'] = $sp;
		$data['request']['kategori'] = $kategori;
		
		$data['jsonDT'] = $jsonDT;
		// $data['sql']=$sql;
		$data['arrBulan'] = $arrBulan;
		$data['arrTahun'] = $arrTahun;
		$data['arrSP'] = $arrSP;
		$data['arrKonfig'] = $arrKonfig;
		$data['arrKategori'] = $arrKategori;
		$data['arrG'] = $arrG;
		
		$data['form_action']    = site_url('learning_wallet/tracking_penyelenggaraan');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Tracking Penyelenggaraan Pelatihan';
		$data['page'] = 'learning_wallet/learning_wallet_tracking_penyelenggaraan_view';
        $this->load->view('main_view',$data);
	}
	
	function tracking_penyelenggaraan_detail($tahun,$id_kelas) {
		has_access('learningwallet.tracking_penyelenggaraan_update');
		
		$strError = "";
		
		$tahun = (int) $tahun;
		$id_kelas = (int) $id_kelas;
		
		$arrStatusPengajuan = $this->learning_wallet_model->getDaftarStatusPengajuan();
		$arrSP = $this->learning_wallet_model->status_penyelenggaraan();
		
		$post = $this->input->post();
		if(!empty($post)) {
			$sp = $post['sp'];
			$catatan = $post['catatan'];
			
			if(empty($sp)) $strError .= '<li>Status penyelenggaraan masih kosong.</li>';
			
			if(empty($strError)) {
				$sql = "update _learning_wallet_classroom set status_penyelenggaraan='".$sp."', catatan_penyelenggaraan=".$this->db->escape($catatan)." where id='".$id_kelas."' and tahun='".$tahun."' ";
				$this->db->query($sql);
				
				if($sp=="batal") {
					$sql = "update _learning_wallet_pengajuan set kode_status_current='-40', is_final_sdm='1', is_final_sevp='1' where kode_status_current in ('40','20','-40') and id_lw_classroom='".$id_kelas."' ";
					$this->db->query($sql);
				}
				
				// log
				create_log($this->section_id,$id_kelas,'update status penyelenggaraan','');
				$msg        = "data berhasil disimpan";
                $url_return = site_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas);
                flash_notif_success($msg,$url_return);
			}
		}
		
		// detail kelas
		$sql = "select * from _learning_wallet_classroom where id='".$id_kelas."' and tahun='".$tahun."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$data_kelas = $row[0];
		
		// detail karyawan yg memesan
		$i1 = 0;
		$i2 = 0;
		$i3 = 0;
		$peserta_temp = '';
		$peserta_ui = '';
		$peserta_disetujui_ui = '';
		$peserta_status_lain_ui = '';
		$jumlah_disetujui = 0;
		$jumlah_menunggu = 0;
		$sql =
			"select g.group_name, m.member_id, m.member_name, m.member_nip, p.kode_status_current, p.id as id_pengajuan, p.is_final_sdm,p.no_wa
			 from _learning_wallet_pengajuan p, _member m, _group g
			 where p.id_member=m.member_id and m.group_id=g.group_id and p.id_lw_classroom='".$id_kelas."' and p.tahun='".$tahun."' and p.status='aktif'
			 order by length(g.group_name), g.group_name";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			if($val['is_final_sdm']=="0") {
				$jumlah_menunggu++;
			} else {
				if($val['kode_status_current']=="40") $jumlah_disetujui++;
			}
			//get no wa
			if(isset($val['no_wa']) || $val['no_wa'] !='' ){
				$no_wa = $val['no_wa'];
			}else{
				$no_wa ='-';
			}
			
			// munculkan form update approval?
			$dForm = '';
			if($val['is_final_sdm']=="0") {
				$dForm =
					'<select class="form-control kt-input" name="sp['.$val['id_pengajuan'].']">
						<option value="0"></option>
						<option value="40">'.$arrStatusPengajuan['40']['label'].'</option>
						<option value="-20">'.$arrStatusPengajuan['-20']['label'].'</option>
					 </select>
					 <input type="hidden" name="nama['.$val['id_pengajuan'].']" value="'.$val['member_name'].'"/>';
				
				$i1++;
				$peserta_ui .=
					'<tr>
						<td>'.$i1.'</td>
						<td>'.$val['group_name'].'</td>
						<td>'.$val['member_nip'].'</td>
						<td>'.$val['member_name'].'</td>
						<td>'.$no_wa.'</td>
						<td style="color:'.$arrStatusPengajuan[$val['kode_status_current']]['warna'].'">
							'.$dForm.'
						</td>
					 </tr>';
			} else {
				if($val['kode_status_current']=="40") {
					$i2++;
					
					$aksiUI =  '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="batal('.$val['id_pengajuan'].',\''.$val['member_nip'].'\')">batalkan</a>&nbsp;';
					// ganti peserta di-takedown
					// <a class="btn btn-sm btn-info" href="javascript:void(0)" onclick="ganti('.$val['id_pengajuan'].',\''.$val['member_nip'].'\')">ganti peserta</a>
					
					$peserta_disetujui_ui .= 
						'<tr>
							<td>'.$i2.'</td>
							<td>'.$val['group_name'].'</td>
							<td>'.$val['member_nip'].'</td>
							<td>'.$val['member_name'].'</td>
							<td>'.$no_wa.'</td>
							<td>'.$aksiUI.'</td>
						 </tr>';
				} else {
					$i3++;
					$peserta_status_lain_ui .=
						'<tr>
							<td>'.$i3.'</td>
							<td>'.$val['group_name'].'</td>
							<td>'.$val['member_nip'].'</td>
							<td>'.$val['member_name'].'</td>
							<td>'.$no_wa.'</td>
							<td style="color:'.$arrStatusPengajuan[$val['kode_status_current']]['warna'].'">
								'.$arrStatusPengajuan[$val['kode_status_current']]['label'].'
							</td>
						 </tr>';
				}
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$btn_simpan = '<button type="button" class="btn btn-success pl-5 pr-5" onclick="konfirm(\'form_sp\')">Simpan</button>';
		if($jumlah_menunggu>0) {
			// do nothing
			// $btn_simpan = '<div class="text-danger">tombol simpan tidak muncul karena ada '.$jumlah_menunggu.' karyawan yang statusnya masih menunggu persetujuan.</div>';
		} else {
			if($data_kelas['status_penyelenggaraan']!="-") $btn_simpan = "&nbsp;";
		}
		
		$data['request']['sp'] = $data_kelas['status_penyelenggaraan'];
		$data['request']['catatan'] = $data_kelas['catatan_penyelenggaraan'];
		
		$data['btn_simpan'] = $btn_simpan;
		$data['peserta_ui'] = $peserta_ui;
		$data['peserta_disetujui_ui'] = $peserta_disetujui_ui;
		$data['peserta_status_lain_ui'] = $peserta_status_lain_ui;
		$data['jumlah_disetujui'] = $jumlah_disetujui;
		$data['jumlah_menunggu'] = $jumlah_menunggu;
		$data['tercapai'] = ($jumlah_disetujui>=$data_kelas['minimal_peserta'])? 'iya' : 'tidak';
		
		$data['arrSP'] = $arrSP;
		$data['data_kelas'] = $data_kelas;
		
		// usp: update status approval peserta
		$usp_url = '';
		$usp_simpan_ui = '';
		if($jumlah_menunggu>0) {
			$usp_url = site_url('learning_wallet/tracking_penyelenggaraan_update_status_peserta/'.$tahun.'/'.$id_kelas);
			$usp_simpan_ui =
				'<tfoot>
					<tr>
						<td colspan="5">
							<button type="button" class="btn btn-success pl-5 pr-5" onclick="konfirm(\'form_usp\')">Simpan</button>
						</td>
					</tr>
				 </tfoot>';
		}
		$data['usp_simpan_ui'] = $usp_simpan_ui;
		$data['form_action_usp'] = $usp_url;
		
		$data['url_batal'] = site_url('learning_wallet/tracking_penyelenggaraan_peserta_batal/'.$tahun.'/'.$id_kelas);
		$data['url_ganti'] = site_url('learning_wallet/tracking_penyelenggaraan_peserta_ganti/'.$tahun.'/'.$id_kelas);
		
		$data['form_action']    = site_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas);
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Tracking Penyelenggaraan Pelatihan ';
		$data['page'] = 'learning_wallet/learning_wallet_tracking_penyelenggaraan_detail_view';
        $this->load->view('main_view',$data);
	}
	
	function tracking_penyelenggaraan_update_status_peserta($tahun,$id_kelas) {
		has_access('learningwallet.tracking_penyelenggaraan_update');
		
		$strError = "";
		
		$tahun = (int) $tahun;
		$id_kelas = (int) $id_kelas;
		
		$post = $this->input->post();
		if(!empty($post)) {
			$arrSP = $post['sp'];
			$arrNama = $post['nama'];
			
			/*
			foreach($arrSP as $key => $val) {
				if(empty($val)) $strError .= '<li>Status persetujuan '.$arrNama[$key].' masih kosong.</li>';
			}
			*/
			
			if(empty($strError)) {
				$this->db->trans_start();
				foreach($arrSP as $key => $val) {
					if(empty($val)) continue;
					
					$id_pengajuan = (int) $key;
					$val = (int) $val;
					$sql = "update _learning_wallet_pengajuan set kode_status_current='".$val."', tgl_update_status=now(), is_final_sdm='1', is_final_sevp='1' where id='".$id_pengajuan."' and id_lw_classroom='".$id_kelas."' ";
					$this->db->query($sql);
				}
				$this->db->trans_complete();
				if($this->db->trans_status()===false) {
					$strError .= "<li>Silahkan coba lagi beberapa saat lagi.</li>";
				} else {
					create_log($this->section_id,$id_kelas,'update status persetujuan ','tracking penyelenggaraan');
					$msg        = "data berhasil disimpan";
					$url_return = site_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas);
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
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		redirect(base_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas));
		exit;
	}
	
	function tracking_penyelenggaraan_peserta_batal($tahun,$id_kelas,$id_pengajuan) {
		has_access('learningwallet.tracking_penyelenggaraan_update');
		
		$strError = "";
		
		$tahun = (int) $tahun;
		$id_kelas = (int) $id_kelas;
		$id_pengajuan = (int) $id_pengajuan;
		
		$sql = "update _learning_wallet_pengajuan set kode_status_current='-60' where id_lw_classroom='".$id_kelas."' and tahun='".$tahun."' and id='".$id_pengajuan."' ";
		$this->db->query($sql);
		
		// log
		create_log($this->section_id,$id_kelas,'peserta batal','id_pengajuan '.$id_pengajuan);
		
		$msg        = "peserta berhasil dihapus";
		$url_return = site_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas);
		flash_notif_success($msg,$url_return);
	}
	
	function tracking_penyelenggaraan_peserta_ganti($tahun,$id_kelas,$id_pengajuan) {
		has_access('learningwallet.tracking_penyelenggaraan_update');
		
		$strError = "";
		$switch_to = "";
		
		$tahun = (int) $tahun;
		$id_kelas = (int) $id_kelas;
		$id_pengajuan = (int) $id_pengajuan;
		
		$nama_peserta = '';
		
		$sql = "select m.member_id, m.member_nip, m.member_name, p.id_lw_classroom, p.id_group from _member m, _learning_wallet_pengajuan p where p.id_member=m.member_id and p.id='".$id_pengajuan."'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$id_classroom = $row[0]['id_lw_classroom'];
		$nama_peserta = "[".$row[0]['member_nip']."] ".$row[0]['member_name'];
		$id_peserta_lama = $row[0]['member_id'];
		$id_group_lama = $row[0]['id_group'];
		
		$arr_group = $this->group_model->get($id_group_lama);
		
		$post = $this->input->post();
		if(!empty($post)) {
			$id_peserta = (int) @$post['id_peserta'];
			
			if(empty($id_peserta)) {
				$strError .= '<li>Peserta pengganti masih kosong.</li>';
			} else {
				if($id_peserta_lama==$id_peserta) {
					$strError .= '<li>Peserta pengganti harus berbeda dengan peserta saat ini.</li>';
				} else {
					// apakah peserta pernah daftar pelatihan?
					$sqlC = "select id, id_member, kode_status_current from _learning_wallet_pengajuan where id_lw_classroom='".$id_classroom."' and status='aktif' and id_member='".$id_peserta."' ";
					$resC = $this->db->query($sqlC);
					$rowC = $resC->result_array();
					$idC = @$rowC[0]['id'];
					if(!empty($idC)) {
						if($rowC[0]['kode_status_current']=="40") {
							$strError .= '<li>Tidak dapat mengganti peserta karena peserta pengganti jg mengikuti pelatihan ini.</li>';
						} else {
							$switch_to = $idC;
						}
					}
					
					// pengecekan terakhir, pengganti rekan satu entitas apa bukan?
					if(empty($strError)) {
						$sqlC = "select group_id from _member where member_id='".$id_peserta."' ";
						$resC = $this->db->query($sqlC);
						$rowC = $resC->result_array();
						if($rowC[0]['group_id']!=$id_group_lama) {
							$strError .= '<li>Peserta pengganti harus berasal dari entitas yg sama.</li>';
						}
					}
				}
			}
			
			if(empty($strError)) {
				if(!empty($switch_to)) { // peserta pengganti = peserta yg ikutan daftar tp ga jadi berangkat
					// peserta pengganti
					$sql = "update _learning_wallet_pengajuan set kode_status_current='40' where id='".$switch_to."' ";
					$this->db->query($sql);
					
					// peserta yg diganti
					$sql = "update _learning_wallet_pengajuan set kode_status_current='-60' where id='".$id_pengajuan."' ";
					$this->db->query($sql);
				} else {
					$sql = "update _learning_wallet_pengajuan set id_member='".$id_peserta."' where id='".$id_pengajuan."' ";
					$this->db->query($sql);
				}
				
				// log
				create_log($this->section_id,$id_kelas,'peserta ganti','dari '.$id_peserta_lama.' ke '.$id_peserta);
				$msg        = "data berhasil disimpan";
                $url_return = site_url('learning_wallet/tracking_penyelenggaraan_detail/'.$tahun.'/'.$id_kelas);
                flash_notif_success($msg,$url_return);
			}
		}
		
		// detail kelas
		$sql = "select * from _learning_wallet_classroom where id='".$id_kelas."' and tahun='".$tahun."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$data_kelas = $row[0];
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['data_kelas'] = $data_kelas;
		$data['tahun'] = $tahun;
		$data['id_kelas'] = $id_kelas;
		$data['nama_peserta'] = $nama_peserta;
		$data['nama_group'] = $arr_group['group_name'];
		
		$data['form_action']    = site_url('learning_wallet/tracking_penyelenggaraan_peserta_ganti/'.$tahun.'/'.$id_kelas.'/'.$id_pengajuan);
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Ganti Peserta Learning Wallet ';
		$data['page'] = 'learning_wallet/learning_wallet_tracking_penyelenggaraan_peserta_ganti';
        $this->load->view('main_view',$data);
	}
	
	function dashboard_dana() {
		has_access('learningwallet.dashboard');
		
		redirect(404);
		
		$data['form_action']    = site_url('learning_wallet/dashboard_dana');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Dashboard Dana Pengembangan ';
		$data['page'] = 'learning_wallet/learning_wallet_dashboard_dana_view';
        $this->load->view('main_view',$data);
	}
	
	function dashboard_jam_pelajaran() {
		has_access('learningwallet.dashboard');
		
		redirect(404);
		
		$data['form_action']    = site_url('learning_wallet/dashboard_jam_pelajaran');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Dashboard Jam Pembelajaran ';
		$data['page'] = 'learning_wallet/learning_wallet_dashboard_jam_pelajaran_view';
        $this->load->view('main_view',$data);
	}
	
	/* function monitoring() {
		// has_access('learningwallet.dashboard');
		
		$get = $this->input->get();
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		if(empty($data['tahun_terpilih'])) $data['tahun_terpilih'] = date('Y');
		
		// list tahun
		$tahun_awal = 2023;
		$tahun_depan = date('Y')+1;
		$data['form_opt_tahun'] = array();
		for($i=$tahun_awal;$i<=$tahun_depan;$i++) {
			$data['form_opt_tahun'][$i] = $i;
		}
		
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Monitoring Data Pelatihan ';
		$data['page'] = 'learning_wallet/learning_wallet_monitoring_view';
        $this->load->view('main_view',$data);
	} */
	
	//author 	: KDW
	//date 		: 12.05.2023
	//function  : tambah kelas agrowallet

	function tambah_kelas_agrowallet(){
		has_access('learningwallet.tambah_kelas_agrowallet');
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Pendaftaran Kelas Agrowallet ';
		$data['page'] = 'learning_wallet/learning_wallet_form_add';

		//data klien : sementara set 1
		$data['klien']=$this->learning_wallet_model->get_klien();
		// data sekolah
		$data['sekolah']=$this->learning_wallet_model->get_sekolah();
		//data penyelenggara : sementara set 1
		$data['penyelenggara']=$this->learning_wallet_model->get_penyelenggara();
		$data['metode']=array("online","offline","blended offline","blended online");
		$data['kategori']=array("ecowebinar","ecolearning","reguler");

        $this->load->view('main_view',$data);
	}

	function add_kelas(){
		//has_access('learningwallet.tambah_kelas_agrowallet');
		$id_penyelenggara=$_POST['penyelenggara'];
		$id_klien=$_POST['klien'];
		$tahun=$_POST['tahun'];
		$id_sekolah=$_POST['sekolah'];
		$kode=$_POST['kodekelas'];;
		$nama=$_POST['namakelas'];
		$metode=$_POST['metode'];
		$durasi_hari=$_POST['hari'];
		$jumlah_jam=$_POST['jam'];
		$daftar_level_karyawan=$_POST['level'];
		$catatan_level_peserta=$_POST['catatanlevel'];
		$tgl_mulai=date('Y-m-d',strtotime($_POST['mulai']));
		$tgl_selesai=date('Y-m-d',strtotime($_POST['selesai']));
		$harga=$_POST['harga'];
		$minimal_peserta=$_POST['minimal'];
		$lokasi_offline=$_POST['lokasi'];
		$keterangan=$_POST['keterangan'];
		$deskripsi=$_POST['deskripsi'];
		$silabus=$_POST['silabus'];
		$sasaran_pembelajaran=$_POST['sasaran'];
		$kata_kunci=$_POST['tag'];
		$status="aktif";
		$is_ecolearning=isset($_POST['eco']);
		$status_penyelengaraan="-";
		$catatan_penyelenggaraan=$_POST['caper'];
		$pic=$_POST['pic'];
		$add_by=$_SESSION['id'];
		switch($_POST['kategori']){
			case 'ecowebinar':
				$berkas="ecowebinar.png";
			break;
			case 'ecolearning':
				$berkas="ecolearning.png";
			break;
			case 'reguler':
				$berkas="default.png";
			break;
			default:
				$berkas="default.png";
			break;
		}
		//$pic=$_POST['pic'];

		$datapost=array(
			"id_penyelenggara"=>$id_penyelenggara,
			"id_klien"=>$id_klien,
			"tahun"=>$tahun,
			"id_sekolah"=>$id_sekolah,
			"kode"=>$kode,
			"berkas"=>$berkas,
			"nama"=>$nama,
			"metode"=>$metode,
			"durasi_hari"=>$durasi_hari,
			"jumlah_jam"=>$jumlah_jam,
			"daftar_level_karyawan"=>$daftar_level_karyawan,
			"catatan_level_peserta"=>$catatan_level_peserta,
			"tgl_mulai"=>$tgl_mulai,
			"tgl_selesai"=>$tgl_selesai,
			"harga"=>$harga,
			"minimal_peserta"=>$minimal_peserta,
			"lokasi_offline"=>$lokasi_offline,
			"keterangan"=>$keterangan,
			"deskripsi"=>$deskripsi,
			"silabus"=>$silabus,
			"sasaran_pembelajaran"=>$sasaran_pembelajaran,
			"kata_kunci"=>$kata_kunci,
			"status"=>$status,
			"is_ecolearning"=>$is_ecolearning,
			"status_penyelenggaraan"=>$status_penyelengaraan,
			"catatan_penyelenggaraan"=>$catatan_penyelenggaraan,
			"pic"=>$pic,
			"insert_by"=>$add_by
			
		);
		// tambah data kelas
		$create=$this->learning_wallet_model->create_kelas($datapost);
		//ambil id tertambah untuk nama file.
		$idbaru=$create;

		$upcover="";

		if($idbaru > 0 ){
			$result=array(
				"status"=>"ok",
				"data"=>$idbaru,
				"pesan"=>"kelas telah dibuat");
			//// non aktif, sistem ganti tidka ada upload lagi 10.10.2023
			/*$upcover=$this->learning_wallet_model->upload_cover($_FILES,$idbaru);
			if($upcover =="ok"){
				$result=array(
					"status"=>"ok",
					"data"=>$upcover,
					"pesan"=>"kelas telah dibuat");
			}else{
				$result=array(
					"status"=>"gagal",
					"data"=>$upcover,
					"pesan"=>"Upload cover gagal dilakukan.");
			}*/
			
		}else{
			$result=array(
				"status"=>"gagal",
				"data"=>$idbaru,
				"pesan"=>"kelas gagal dibuat");
		}/**/
		// upload file 

		///cek jika kode sudah ada maka tidak bisa input
		//$res=$datapost;
		$res=$result;
		echo json_encode($res);
	}

	//author 	: KDW
	//date 		: 23.05.2023
	//function  : import kelas xlsx agrowallet
	function import_kelas(){
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Import Kelas baru';

		$data['page'] = 'learning_wallet/learning_wallet_import_class';
		$this->load->view('main_view',$data);
	}

	function uploadexcel_lw(){
		$dir=FCPATH."/media/fileimport/";
        if(!is_dir($dir)) mkdir($dir, 0777, TRUE);
		$extension=pathinfo($_FILES['filex']['name'], PATHINFO_EXTENSION);
		$file_name = "import kelas wallet ".date('d-m-Y His');
		$config['upload_path']          = $dir;
		$config['allowed_types']        = 'xlsx|csv';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 5120; // 1MB
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
		$pesan="";
		$count=0;
		if (!$this->upload->do_upload('filex')) {
			$res = array('error' => $this->upload->display_errors());
		} else {
		// baca file
			$upload_data = $this->upload->data();
			if('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($upload_data['full_path']);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$i=0;
			foreach($sheetData as $sd){
				if($i > 0){
					$datapost=array(
						"id_penyelenggara"=>$sd[0],
						"id_klien"=>$sd[1],
						"tahun"=>$sd[2],
						"id_sekolah"=>$sd[3],
						"kode"=>$sd[4],
						"berkas"=>$sd[5],
						"nama"=>$sd[6],
						"metode"=>$sd[7],
						"durasi_hari"=>$sd[8],
						"jumlah_jam"=>$sd[9],
						"daftar_level_karyawan"=>$sd[10],
						"catatan_level_peserta"=>$sd[11],
						"tgl_mulai"=>$sd[12],
						"tgl_selesai"=>$sd[13],
						"harga"=>$sd[14],
						"akomodasi"=>$sd[15],
						"minimal_peserta"=>$sd[16],
						"lokasi_offline"=>$sd[17],
						"keterangan"=>$sd[18],
						"deskripsi"=>$sd[19],
						"silabus"=>$sd[20],
						"sasaran_pembelajaran"=>$sd[21],
						"kata_kunci"=>$sd[22],
						"status"=>$sd[23],
						"is_ecolearning"=>$sd[24],
						"status_penyelenggaraan"=>$sd[25],
						"catatan_penyelenggaraan"=>$sd[26],
						"pic"=>$sd[27]
						
					);
					$create=$this->learning_wallet_model->create_kelas($datapost);
					if($create){
						$count++;
						$pesan.="kelas ".$sd[4]." berhasil ditambahkan<br/>";
					}else{
						$e = $this->db->error();
						$pesan.=$e['message'];
					}
				}else{
					
				}
				
				$i++;
			}
			$res=array("stat"=>"ok","pesan"=>$pesan);
		
		}

		$result=$res;
		echo json_encode($result);
	}

	//author 	: KDW
	//date 		: 24.05.2023
	//function  : editkelas kelas agrowallet

	function edit_kelas_agrowallet(){
		$kelas_id=$this->uri->segment(3);
		if(isset($kelas_id) || $kelas_id!=""){
			$param=array("lwc.id"=>$kelas_id);
			$datakelas=$this->learning_wallet_model->get_kelas_lw($param);
			// get kelas
			
		}else{

			echo "kembali ke menu list pelatihan";
		}
		
	
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Pendaftaran Kelas Agrowallet ';
		$data['page'] = 'learning_wallet/learning_wallet_form_edit';

		//data klien : sementara set 1
		$data['klien']=$this->learning_wallet_model->get_klien();
		// data sekolah
		$data['sekolah']=$this->learning_wallet_model->get_sekolah();
		//data metode : sementara isi 1
		$data['metode']=array("online","offline","blended offline","blended online");
		//data penyelenggaraan
		$data['status_penyelenggaraan']=array("-","jalan","batal");
		//data penyelenggara : sementara set 1
		$data['penyelenggara']=$this->learning_wallet_model->get_penyelenggara();
		// data kelas 
		$data['kelas']=$datakelas;
		//data kategori kelas
		$data['kategori']=array("ecowebinar","ecolearning","reguler");
		//data status
		$data['status']=array("aktif","dihapus");
        $this->load->view('main_view',$data);
	}

	function edit_kelas(){
		//has_access('learningwallet.tambah_kelas_agrowallet');
		$idkelas=$_POST['kelas'];
		$id_penyelenggara=$_POST['penyelenggara'];
		$id_klien=$_POST['klien'];
		$tahun=$_POST['tahun'];
		$id_sekolah=$_POST['sekolah'];
		$kode=$_POST['kodekelas'];;
		switch($_POST['kategori']){
			case 'ecowebinar':
				$berkas="ecowebinar.png";
			break;
			case 'ecolearning':
				$berkas="ecolearning.png";
			break;
			case 'reguler':
				$berkas="default.png";
			break;
			default:
				$berkas="default.png";
			break;
		}
		$nama=$_POST['namakelas'];
		$metode=$_POST['metode'];
		$durasi_hari=$_POST['hari'];
		$jumlah_jam=$_POST['jam'];
		$daftar_level_karyawan=$_POST['level'];
		$catatan_level_peserta=$_POST['catatanlevel'];
		$tgl_mulai=date('Y-m-d',strtotime($_POST['mulai']));
		$tgl_selesai=date('Y-m-d',strtotime($_POST['selesai']));
		$harga=$_POST['harga'];
		$minimal_peserta=$_POST['minimal'];
		$lokasi_offline=$_POST['lokasi'];
		$keterangan=$_POST['keterangan'];
		$deskripsi=$_POST['deskripsi'];
		$silabus=$_POST['silabus'];
		$sasaran_pembelajaran=$_POST['sasaran'];
		$kata_kunci=$_POST['tag'];
		$status="aktif";
		$is_ecolearning=isset($_POST['eco']);
		//$status_penyelengaraan=$_POST['status_penyelenggaraan'];
		$catatan_penyelenggaraan=$_POST['caper'];
		$pic=$_POST['pic'];
		$status=isset($_POST['status'])&&$_POST['status']=="aktif"?"aktif":"dihapus";

		$datapost=array(
			"id_penyelenggara"=>$id_penyelenggara,
			"id_klien"=>$id_klien,
			"tahun"=>$tahun,
			"id_sekolah"=>$id_sekolah,
			"kode"=>$kode,
			"berkas"=>$berkas,
			"nama"=>$nama,
			"metode"=>$metode,
			"durasi_hari"=>$durasi_hari,
			"jumlah_jam"=>$jumlah_jam,
			"daftar_level_karyawan"=>$daftar_level_karyawan,
			"catatan_level_peserta"=>$catatan_level_peserta,
			"tgl_mulai"=>$tgl_mulai,
			"tgl_selesai"=>$tgl_selesai,
			"harga"=>$harga,
			"minimal_peserta"=>$minimal_peserta,
			"lokasi_offline"=>$lokasi_offline,
			"keterangan"=>$keterangan,
			"deskripsi"=>$deskripsi,
			"silabus"=>$silabus,
			"sasaran_pembelajaran"=>$sasaran_pembelajaran,
			"kata_kunci"=>$kata_kunci,
			"status"=>$status,
			"is_ecolearning"=>$is_ecolearning,
			"catatan_penyelenggaraan"=>$catatan_penyelenggaraan,
			"pic"=>$pic,
			"status"=>$status
			
		);
		// edit data kelas
		$where=array("id"=>$idkelas);
		$update=$this->learning_wallet_model->edit_kelas($datapost,$where);
		//ambil id tertambah untuk nama file.
		$idedit=$update;

		$upcover="";
		if($idedit > 0 ){
			$result=array(
				"status"=>"ok",
				"data"=>$idedit,
				"pesan"=>"kelas telah dibuat");
			/*$upcover=$this->learning_wallet_model->upload_cover($_FILES,$idedit);
			if($upcover =="ok"){
				$result=array(
					"status"=>"ok",
					"data"=>$upcover,
					"pesan"=>"kelas telah dibuat");
			}else{
				$result=array(
					"status"=>"gagal",
					"data"=>$upcover,
					"pesan"=>"Cover tidak terupdate");
			}*/
			
		}else{
			$result=array(
				"status"=>"gagal",
				"data"=>$idedit,
				"pesan"=>"kelas gagal dibuat");
		}
		// upload file 

		///cek jika kode sudah ada maka tidak bisa input
		//$res=$datapost;
		$res=$result;
		echo json_encode($res);
	}

	//author 	: KDW
	//date 		: 25.05.2023
	//function  : Saldo Agrowallet

	function saldo_view(){
		$data['page_name']          = 'Learning Wallet';
        $data['page_sub_name']      = 'Saldo Peserta Kelas';

		$saldo=array();
		/*$data_saldo=$this->learning_wallet_model->saldo_pengajuan();
		foreach ($data_saldo as $ds){
			$wheremodal=array("id_member"=>$ds->idm);
			$dtmodal=$this->learning_wallet_model->saldo_modal($wheremodal);
			$idm=$ds->idm;
			$nama=$ds->nama;
			$nip=$ds->nip;
			$entitas=$ds->entitas;
			$modal=count((array)$dtmodal) > 0  || isset($dtmodal[0]->saldo) || $dtmodal[0]->saldo!="" ? $dtmodal[0]->saldo:"0";
			$pengajuan = count((array)$data_saldo) > 0  || isset($dtmodal[0]->saldo) || $dtmodal[0]->saldo!="" ? $ds->pengajuan:"0";;
			
			$saldo[]=array(
							"idm"=>$idm,
							"nama"=>$nama,
							"nip"=>$nip,
							"entitas"=>$entitas,
							"modal"=>$modal,
							"pengajuan"=>$pengajuan);
							
		}*/
		$datamember=$this->learning_wallet_model->getmember();
		foreach($datamember as $m){
			$idm=$m['member_id'];
			$nama=$m['member_name'];
			$nip=$m['member_nip'];
			$entitas=$m['group_name'];
		
			//daftar modal/topup
			$modal=0;
			$datamodal=$this->learning_wallet_model->modal_list(array("id_member"=>$idm,"kategori"=>"member_topup"));
			$modal=isset($datamodal[0]->saldo) || !empty($datamodal[0]->saldo)?$datamodal[0]->saldo:"0";
		
			//daftar pengajuan dengan status 40
			$datapengajuan=$this->learning_wallet_model->pengajuan_list(array("id_member"=>$idm,"kode_status_current"=>"40"));
			$pengajuan=isset($datapengajuan[0]->pengajuan) || !empty($datapengajuan[0]->pengajuan)?$datapengajuan[0]->pengajuan:"0";
			
			$saldo[]=array(
				"idm"=>$idm,
				"nama"=>$nama,
				"nip"=>$nip,
				"entitas"=>$entitas,
				"modal"=>$modal,
				"pengajuan"=>$pengajuan);
			
		}	

		$data['saldo']=$saldo;
		$data['page'] = 'learning_wallet/learning_wallet_saldo';
		$this->load->view('main_view',$data);
	}

	//author 	: KDW
	//date 		: 06.06.2023
	//function  : testing query

	function tesq(){
		$datamember=$this->learning_wallet_model->getmember();
		foreach($datamember as $m){
			$idm=$m['member_id'];
			$nama=$m['member_name'];
			$nip=$m['member_nip'];
			$entitas=$m['group_name'];
		
			//daftar modal/topup
			$modal=0;
			$datamodal=$this->learning_wallet_model->modal_list(array("id_member"=>$idm,"kategori"=>"member_total_topup"));
			$modal=isset($datamodal[0]->saldo) || !empty($datamodal[0]->saldo)?$datamodal[0]->saldo:"0";
		
			//daftar pengajuan dengan status 40
			$datapengajuan=$this->learning_wallet_model->pengajuan_list(array("id_member"=>$idm,"kode_status_current"=>"40"));
			$pengajuan=isset($datapengajuan[0]->pengajuan) || !empty($datapengajuan[0]->pengajuan)?$datapengajuan[0]->pengajuan:"0";
			
			$saldo[]=array(
				"idm"=>$idm,
				"nama"=>$nama,
				"nip"=>$nip,
				"entitas"=>$entitas,
				"modal"=>$modal,
				"pengajuan"=>$pengajuan);
			
		}	

	}
}