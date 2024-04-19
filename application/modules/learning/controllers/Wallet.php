<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends MX_Controller {
    public $title = 'AgroWallet';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['learning_wallet_model','member_level_karyawan_model','group_model', 'member_model']);
    }
	
	public function index(){
		header('location:'.base_url('learning/wallet/beranda'));
		exit;
	}

    public function beranda($tahun=NULL){
		$this->page = 'wallet/home';
		
		if($tahun<2023) $tahun = "2023";
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		// anulir all request s.d bulan lalu yg tidak selesai difollow up
		/* $expired_time_request = date("Y-m-t",strtotime("last month"))."23:59:59";
		$sql = "select count(id) as jumlah from _learning_wallet_pengajuan where status='aktif' and (kode_status_current>0 and kode_status_current!='40') and tgl_request<='".$expired_time_request."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if($row[0]['jumlah']>0) {
			$sql = "update _learning_wallet_pengajuan set kode_status_current='-50', is_final_sdm='1', is_final_sevp='1' tgl_update_status=now() where status='aktif' and (kode_status_current>0 and kode_status_current!='40') and tgl_request<='".$expired_time_request."' ";
			$res = $this->db->query($sql);
		} */
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$arrTahun = $this->learning_wallet_model->getOpsiTahun();
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['arrTahun'] = $arrTahun;
		$this->data['back_url'] = base_url('learning');
		$this->generate_layout();
	}
	
	public function my($tahun=NULL){
		$this->page = 'wallet/my';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/beranda/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function belanja($tahun=NULL,$page=NULL){
		$this->page = 'wallet/belanja';
		
		$page = (int) $page;
		if($page<1) $page = 1;
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['page'] = $page;
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/beranda/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function belanja_detail($tahun=NULL,$id_pelatihan=NULL){
		$this->page = 'wallet/belanja_detail';
		
		$id_pelatihan = (int) $id_pelatihan;
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$params = $_SESSION['lw_belanja_url_params'];
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['id_pelatihan'] = $id_pelatihan;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/belanja/'.$tahun_terpilih.$params);
		$this->generate_layout();
	}
	
	public function belanja_batal($tahun=NULL,$id_pelatihan=NULL){
		$id_pelatihan = (int) $id_pelatihan;
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$member_id = $this->session->userdata('member_id');
		
		$arrPengajuan = $this->learning_wallet_model->getDetailPengajuanPelatihan($tahun_terpilih,$member_id,$id_pelatihan);
		$id_pengajuan = $arrPengajuan['id'];
		$berkas = $arrPengajuan['berkas'];
		
		if($id_pengajuan>0) {
			$sql = "update _learning_wallet_pengajuan set status='dihapus' where id='".$id_pengajuan."' ";
			$res = $this->db->query($sql);
			
			// hapus berkas jika ada
			if(isset($berkas)) {
				$folder = getCodeFolder($id_pengajuan);
				$dfile = FCPATH.AGROWALLET_MEDIA_APPROVAL_PATH.$folder.'/'.$berkas;
				unlink($dfile);
			}
		}
		
		redirect(base_url('learning/wallet/belanja_detail/'.$tahun_terpilih.'/'.$id_pelatihan));
		exit;
	}
	
	public function usulan($tahun=NULL){
		$this->page = 'wallet/usulan';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/beranda/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function approval($tahun=NULL){
		$this->page = 'wallet/approval';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/beranda/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function approval_download($tahun,$id_pelatihan){
		echo 'dalam pengembangan';
		
		exit;
	}
	
	public function approval_detail($tahun,$id_pelatihan){
		$this->page = 'wallet/approval_detail';
		
		$id_pelatihan = (int) $id_pelatihan;
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['id_pelatihan'] = $id_pelatihan;
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/approval/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function kelola_dana($tahun=NULL){
		$this->page = 'wallet/kelola_dana';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/approval/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function kelola_dana_level($tahun=NULL){
		$this->page = 'wallet/kelola_dana_level';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/kelola_dana/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function kelola_dana_topup($tahun=NULL){
		$this->page = 'wallet/kelola_dana_topup';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/kelola_dana/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function dashboard($tahun=NULL){
		$this->page = 'wallet/dashboard';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/approval/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	public function dashboard_realisasi($tahun=NULL){
		$this->page = 'wallet/dashboard_realisasi';
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$this->data['tahun_terpilih'] = $tahun_terpilih;
		$this->data['arrK'] = $arrK;
		$this->data['back_url'] = base_url('learning/wallet/approval/'.$tahun_terpilih);
		$this->generate_layout();
	}
	
	function ajax_member(){
		if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$group_id = $this->session->userdata('group_id');
		
		$get = $this->input->get();
		
		$term = $get['term'];
		$i = 0;
		$arr = array();
		$sql = "select member_id, member_name, member_nip from _member where group_id='".$group_id."' and member_status='active' and (member_name like '%".$term."%' or member_nip like '%".$term."%') order by member_name limit 20 ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$arr[$i]['id'] = $val['member_id'];
			$arr[$i]['label'] = '['.$val['member_nip']."] ".$val['member_name'];
			$i++;
		}
		echo json_encode($arr);
		exit;
	}
	
	function ajax_dp($tahun=NULL){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data = array();
		
		$tahun_terpilih = $this->learning_wallet_model->getTahunTerpilih($tahun);
		
		$group_id = $this->session->userdata('group_id');
		
		$addSql = "";
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and (m.member_nip like '%".$keyword."%' or m.member_name like '%".$keyword."%') ";
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
		$sql = "select count(id) as jumlah from _learning_wallet_konfig_group where tahun='".$tahun_terpilih."' and id_group='".$group_id."' and kategori='member_topup' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$recordsTotal = $row[0]['jumlah'];
		
		// hitung jumlah terfilter
		$sqlF =
			"select count(g.id) as jumlah
			 from _learning_wallet_konfig_group g 
				inner join _member m on g.id_member=m.member_id where g.tahun='".$tahun_terpilih."' and g.id_group='".$group_id."' and kategori='member_topup' ".$addSql." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		// get current page data
		$sql =
			"select
				m.member_id, m.member_nip, m.member_name, g.nominal, g.catatan, g.last_update
			 from _learning_wallet_konfig_group g 
				left join _learning_wallet_pengajuan p on g.id_member=p.id_member and p.kode_status_current>0 and p.status='aktif'
				inner join _member m on g.id_member=m.member_id
			 where g.kategori='member_topup' and g.tahun='".$tahun_terpilih."' and g.id_group='".$group_id."'  ".$addSql."
			 group by g.id
			 ".$order_by." limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$row = array();
			$row['member_id']  = $item['member_id'];
			$row['member_nip']  = $item['member_nip'];
			$row['member_name']  = $item['member_name'];
			$row['nominal']  = $item['nominal'];
			$row['catatan']  = $item['catatan'];
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
	
	function ajax_usulan($tahun_terpilih){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$member_id = $this->session->userdata('member_id');
		$group_id = $this->session->userdata('group_id');
		
		$arrK = $this->learning_wallet_model->getDaftarKonfigGroup($group_id,$tahun_terpilih);
		
		$isAdmin = false;
		if($this->session->userdata('member_nip')==$arrK['verifikator_sdm']) {
			$isAdmin = true;
		}
		
		$data = array();
		
		$addSql = "";
		$addSql1 = "";
		
		$post = $this->input->post();
		
		$keyword = $post['search']['value'];
		if(!empty($keyword)) {
			$addSql .= " and (u.judul like '%".$keyword."%' or u.detail like '%".$keyword."%') ";
		}
		
		// is admin?
		if($isAdmin) {
			$addSql1 .= " and u.id_group='".$group_id."' ";
		} else {
			$addSql1 .= " and u.id_member='".$member_id."' ";
		}
		
		$_POST['length'] = (int) $_POST['length'];
		$_POST['start'] = (int) $_POST['start'];
		
		// cek hak akses
		
		$order_by = "";
		if(isset($_POST['order'])) {
			$order_by = " order by ".$_POST['columns'][$_POST['order']['0']['column']]['data']." ".$_POST['order']['0']['dir'];
        }
		
		// hitung jumlah all data
		$sql = "select count(u.id) as jumlah from _learning_wallet_usulan u where 1 ".$addSql1." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$recordsTotal = $row[0]['jumlah'];
		
		// hitung jumlah terfilter
		$sqlF = "select count(u.id) as jumlah from _learning_wallet_usulan u where 1 ".$addSql." ".$addSql1." ";
		$resF = $this->db->query($sqlF);
		$rowF = $resF->result_array();
		$recordsFiltered = $rowF[0]['jumlah'];
		
		$no = $_POST['start'];
		
		// get current page data
		$sql =
			"select
				m.member_name, u.judul, u.detail, u.last_update
			 from _learning_wallet_usulan u, _member m
			 where u.id_member=m.member_id ".$addSql." ".$addSql1."
			 ".$order_by." limit ".$_POST['length']." offset ".$_POST['start']." ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach ($row as $item) {
			$no++;
			
			$row = array();
			$row['no'] = $no;
			$row['id']  = $item['id'];
			$row['member_name']  = $item['member_name'];
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
}