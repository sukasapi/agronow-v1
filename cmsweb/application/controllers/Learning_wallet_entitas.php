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
		
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $group_id = $this->session->userdata('group_id');
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and c.nama like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		$bulan2 = ($bulan<10)? '0'.$bulan : $bulan;
		
		$sql_d0 = '';
		
		if(!empty($group_id)) {
			$sql_d0 = " and m.group_id='".$group_id."' ";
		}
		
		$sql_limit = '';
		if($_POST['length'] != -1) {
			$sql_limit = " limit ".$_POST['length']." offset ".$_POST['start']." ";
		}
		
		$data = array();
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(c.id) as jumlah 
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _member m 
			 where w.id_lw_classroom=c.id and w.status='aktif' and c.status='aktif' and c.tahun='".$tahun_terpilih."' 
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
			"select c.id, c.kode, c.nama, c.tgl_mulai, c.tgl_selesai, count(c.id) as jumlah
			 from _learning_wallet_wishlist w, _learning_wallet_classroom c, _member m 
			 where w.id_lw_classroom=c.id and w.status='aktif' and c.status='aktif' and c.tahun='".$tahun_terpilih."' 
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
			$row['tgl_mulai'] = $item['tgl_mulai'];
			$row['tgl_selesai'] = $item['tgl_selesai'];
			$row['jumlah'] = $item['jumlah'];
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
	
	function l_ajax_konfig_dashboard_penyelenggaraan(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data = array();
		
		$get = $this->input->get();
		$group_id = (int) $get['group_id'];
		$tahun_terpilih = (int) $get['tahun'];
		$bulan = (int) $get['bulan'];
		$kategori = $get['kategori'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $group_id = $this->session->userdata('group_id');
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and c.nama like '%".$keyword."%' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		$bulan2 = ($bulan<10)? '0'.$bulan : $bulan;
		
		$sql_d0 = '';
		$sql_d1 = '';
		$sql_order = '';
		
		if(!empty($group_id)) {
			$sql_d0 = " and g.group_id='".$group_id."' ";
			$sql_order = ", g.group_id ";
		}
		
		if($kategori=="diselenggarakan") {
			$sql_d1 = " and c.status_penyelenggaraan='jalan' ";
		} else if($kategori=="batal") {
			$sql_d1 = " and c.status_penyelenggaraan='batal' ";
		} else if($kategori=="pending") {
			$sql_d1 = " and c.status_penyelenggaraan='-' ";
		}
		
		// cek hak akses
		// is_superadmin()
		
		$data = array();
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(c.id) as jumlah
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p, _member m, _group g
			 where p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id=g.group_id
			 ".$sql_d0." ".$sql_d1." and c.tgl_mulai like '".$tahun_terpilih."-".$bulan2."-%'
			 ".$addSql."
			 group by c.id ".$sql_order." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = count($rowF);
		
		// hitung jumlah all data
		$recordsTotal = $recordsFiltered;
		
		// get current page data
		$sql =
			"select c.id, c.kode, c.nama, c.tgl_mulai, c.tgl_selesai, c.status, count(p.id) as jumlah_pengajuan
			 from _learning_wallet_classroom c, _learning_wallet_pengajuan p, _member m, _group g
			 where p.id_lw_classroom=c.id and p.id_member=m.member_id and m.group_id=g.group_id
			 ".$sql_d0." ".$sql_d1." and c.tgl_mulai like '".$tahun_terpilih."-".$bulan2."-%'
			 ".$addSql."
			 group by c.id ".$sql_order."
			 limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['id'] = $item['id'];
			$row['kode'] = $item['kode'];
			$row['nama_pelatihan'] = $item['nama'];
			$row['tanggal_mulai'] = $item['tgl_mulai'];
			$row['tanggal_selesai'] = $item['tgl_selesai'];
			$row['jumlah_pengajuan'] = $item['jumlah_pengajuan'];
			$row['status'] = $item['status'];
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
	
	function index(){
		redirect(404);
	}

    function kelola_dana(){
        has_access('learningwalletentitas.kelola_dana');
		
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
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
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
        $data['page_sub_name']      = 'Kelola Dana Pengembangan per Level Karyawan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_kelola_dana_list_view';
        $this->load->view('main_view',$data);
    }
	
	function kelola_dana_update(){
		has_access('learningwalletentitas.kelola_dana');
		
		$id_klien = "0";
		$nama_klien = "";
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		$strError = '';
		
		if(empty($data['tahun_terpilih'])) $strError .= '<li>Tahun masih kosong</li>';
		if(empty($data['group_id'])) {
			$strError .= '<li>Group masih kosong</li>';
		} else {
			$arrG = $this->group_model->get($data['group_id']);
			$id_klien = $arrG['id_klien'];
			$nama_klien = $arrG['group_name'];
		}
		$data['id_klien'] = $id_klien;
		$data['nama_klien'] = $nama_klien;
		
		
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
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
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
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
        }
		
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
        $data['page_sub_name']      = 'Wishlist Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_wish_list_view';
        $this->load->view('main_view',$data);
    }
	
	function wishlist_detail(){
		has_access('learningwalletentitas.wishlist');
		
		$get = $this->input->get();
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['group_id'] = (int) $get['group_id'];
		$data['id_pelatihan'] = (int) $get['idc'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		$strError = '';
		if(empty($data['tahun_terpilih'])) {
			$msg = "Tidak dapat memproses data: tahun belum dipilih.";
			$url_return = site_url('learning_wallet_entitas/wishlist');
			flash_notif_warning($msg,$url_return);
			exit;
		}
		
		$data['section_id']     = $this->section_id;
        $data['page_name']          = 'Learning Wallet Entitas';
        $data['page_sub_name']      = 'Detail Wishlist Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_wish_detail_view';
        $this->load->view('main_view',$data);
	}
	
	function dashboard_penyelenggaraan(){
		has_access('learningwalletentitas.dashboard_penyelenggaraan');
		
		$strError = '';
		
		$get = $this->input->get();
		$data['group_id'] = (int) $get['group_id'];
		$data['tahun_terpilih'] = (int) $get['tahun'];
		$data['bulan'] = (int) $get['bulan'];
		$data['kategori'] = $get['kategori'];
		
		// admin entitas?
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
		if(empty($data['bulan'])) $strError .= '<li>Bulan belum dipilih</li>';
		
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
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
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
		if(!empty($this->session->userdata('group_id'))) $data['group_id'] = $this->session->userdata('group_id');
		
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
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
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
				// $url_return = site_url('learning_wallet_entitas/dashboard_penyelenggaraan_detail?group_id='.$group_id.'&tahun='.$tahun.'&idc='.$id_lw_classroom);
				$url_return = site_url('learning_wallet_entitas/approval_massal');
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
	
	function dashboard_realisasi(){
        has_access('learningwalletentitas.dashboard_realisasi');
		
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
		
		$data['form_opt_group'] = array();
		$data['form_opt_group'][''] = '';
        $param_query['filter_active'] = 'active';
		$param_query['sort'] = 'id_klien ASC, group_name ASC';
        $param_query['sort_order'] = '';
		$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if(!has_access_manage_all_member()){
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }else{
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
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
        $data['page_sub_name']      = 'Dashboard Realisasi Penyerapan AgroWallet';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_wallet_entitas/learning_wallet_entitas_summary_realisasi_list_view';
        $this->load->view('main_view',$data);
    }
}