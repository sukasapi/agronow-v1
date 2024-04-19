<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Member extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_model',
            'media_model',
            'group_model',
            'jabatan_model',
            'member_level_model',
            'kompetensi_jabatan_model',
            'kompetensi_member_model',
            'province_model',
            'expert_member_model',
			'learning_wallet_model'
        ));

        $this->section_id = 9;
    }

    function l_modal_ajax(){
        $this->load->view('member/member_list_picker_modal_view');
    }

    function l_single_modal_ajax(){
        $this->load->view('member/member_list_picker_single_modal_view');
    }

    function json(){
		if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
        // $get_member = $this->member_model->get_all();
		$get_member = $this->member_model->get_for_picker();
        foreach ($get_member['data'] as $k => $v){
            //get whatsapp
			
			/*
			**
			** catatan iskhaq: kueri ini sementara dimatikan karena bikin lamban
			** sebagai penggantinya, data diambil dari tabel _member
			**
			
            $idmlw=$v['member_id'];
            $get_wa=$this->learning_wallet_model->get_noWA($idmlw);
            
            if(isset($get_wa[0])){
                $no_wa= $get_wa[0]->no_wa;
            }else{
                $no_wa="";
            }
			*/

            $result['data'][] = array(
                'member_id'        => $v['member_id'],
                'member_name'        => $v['member_name'],
                'group_name'        => $v['group_name'],
                'member_nip'      => $v['member_nip'],
                'no_wa'         =>$v['member_phone']
            );
        }
       echo json_encode($result);
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['member_id']           = $item->member_id;
            $row['member_name']         = $item->member_name;
            $row['group_name']          = $item->group_name;
            $row['nama_level_karyawan'] = $item->nama_level_karyawan;
            $row['member_nip']          = $item->member_nip;
            $row['member_email']        = $item->member_email;
            $row['member_phone']        = $item->member_phone;
            $row['member_status']       = $item->member_status;
            $row['member_ceo']          = $item->member_ceo;

            $row['is_expert']         = $item->is_expert;

            $row['member_saldo']        = $item->member_saldo;
            $row['member_poin']         = $item->member_poin;

            $row['member_create_date']  = $item->member_create_date?parseDateShortReadable($item->member_create_date):NULL;
            $row['member_create_time']  = $item->member_create_date?parseTimeReadable($item->member_create_date):NULL;

            $row['nama_klien']          = $item->nama_klien;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_model->count_all(),
            "recordsFiltered" => $this->member_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function ajax_search(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get = $this->input->get();

        $query = isset($get['q'])?$get['q']:NULL;
        $search_member = $this->member_model->search($query,30);

        if ($search_member!=FALSE) {
            $data_response = array();
            foreach ($search_member as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['member_id'];
                $data_response['results'][$k]['text']  = '['.$v['member_nip'].'] '.$v['member_name'].' ('.$v['group_name'].')';
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }
	
	function ajax_get_level_karyawan_by_group($group_id){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$group_id = (int) $group_id;

        $arrK = array();
		$sql = "select k.id, k.nama from _group g, _member_level_karyawan k where g.id_klien=k.id_klien and g.group_id='".$group_id."' and k.status='active' order by k.nama ";
		$res = $this->db->query($sql);
		$arr = $res->result_array();
		
        if ($arr){
            $result = json_encode($arr);
        }else{
            $result = NULL;
        }

        echo $result;
    }

    function ajax_get_by_group_nip($group_id,$nip){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get_member = $this->member_model->get_by_group_nip($group_id,$nip);

        if ($get_member){
            $result = json_encode($get_member);
        }else{
            $result = NULL;
        }

        echo $result;
    }

    function ajax_get_by_group_nip_except($group_id,$nip,$nip_except){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get_member = $this->member_model->get_by_group_nip_except($group_id,$nip,$nip_except);

        if ($get_member){
            $result = json_encode($get_member);
        }else{
            $result = NULL;
        }

        echo $result;
    }

    function get_member($member_id){
        $get_member = $this->member_model->get($member_id);
        if ($get_member==FALSE){
            redirect(404);
        }else{
            return $get_member;
        }
    }

    function index(){

        if(!has_access_manage_all_member()){
            if (has_access('member.redirect_external',FALSE)){
                redirect(site_url('member/external'));
            }else{
                has_access('member.view');
            }
        }


        $data['form_opt_group'] = NULL;
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

        $level_ids = $this->input->get('level_ids');
        $data['form_opt_level'] = NULL;
        if (!isset($level_ids)){
            $data['form_opt_level'] = NULL;
        }else{
            $get_level = $this->member_level_model->gets($level_ids);
            if ($get_level!=FALSE){
                foreach ($get_level as $k => $v) {
                    $data['form_opt_level'][$v['mlevel_id']] = $v['mlevel_name'];
                }
            }
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Member';
        $data['page_sub_name']      = 'List Member';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member/member_list_view';
        $this->load->view('main_view',$data);
    }

    function external(){
        has_access('member.redirect_external');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Member';
        $data['page_sub_name']      = 'External';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member/member_external_view';
        $this->load->view('main_view',$data);
    }

    function detail($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.view');
        }


        $member = $this->get_member($member_id);

        $expert_member = $this->expert_member_model->get_by_member($member_id);
        $data['expert_member'] = $expert_member;

        $data['member']        = $member;
        $data['page_name']      = 'Member';
        $data['page_sub_name']  = $member['member_name'];
        $data['page'] = 'member/member_detail_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        if (!has_access_manage_all_member()){
            has_access('member.create');
        }


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('member_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('member_password', 'Password', 'required');
        $this->form_validation->set_rules('member_password_confirm', 'Confirm Password', 'required|matches[member_password]');
		
		if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = 'active';
			$param_query['filter_non_aghris_only'] = '1';
			$param_query['sort'] = 'id_klien ASC, group_name ASC';
			$param_query['sort_order'] = '';
			$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {

                    if (!has_access_manage_all_member()){
                        if (in_array($v['group_id'], my_groups())){
                            $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                        }
                    }else{
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }


                }
            }
			
			$data['form_opt_level_karyawan'][''] = '-';

            $data['form_opt_level'][''] = '-';
			$param_query['filter_active'] = '';
			$param_query['sort'] = '';
			$param_query['sort_order'] = '';
            $get_level = $this->member_level_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_level!=FALSE){
                foreach ($get_level['data'] as $k => $v) {
                    $data['form_opt_level'][$v['mlevel_id']] = $v['mlevel_name'];
                }
            }

            $data['form_opt_bidang'][''] = '-';
            $param_query['filter_active'] = '';
            $get_bidang = getBidang();
            if ($get_bidang!=FALSE){
                foreach ($get_bidang as $k => $v) {
                    $data['form_opt_bidang'][$k] = $v;
                }
            }

            $data['form_opt_province'][''] = '-';
            $param_query['filter_active'] = '';
            $get_province = $this->province_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_province!=FALSE){
                foreach ($get_province['data'] as $k => $v) {
                    $data['form_opt_province'][$v['city_province']] = $v['city_province'];
                }
            }


            $data['form_opt_jabatan'][''] = '-';
            /*$param_query['filter_active'] = '';
            $get_jabatan = $this->jabatan_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_jabatan!=FALSE){
                foreach ($get_jabatan['data'] as $k => $v) {
                    $data['form_opt_jabatan'][$v['jabatan_id']] = $v['jabatan_name'].' ('.$v['group_name'].')';
                }
            }*/


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Member';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('member/create');
            $data['page']           = 'member/member_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);
			
			$post['id_level_karyawan'] = $this->member_model->checkLevelKaryawan($post['group_id'],$post['id_level_karyawan']);

            $data = array(
                'member_name'       => $post['member_name'],
                'member_phone'      => $post['member_phone'],
                'member_province'   => $post['member_province'],
                'member_city'       => $post['member_city'],
                'member_address'    => $post['member_address'],
                'member_nip'        => $post['member_nip'],
                'member_email'        => parseInputNull($post['member_email']),
                'member_password'   => md5(trim($post['member_password'])),
                'group_id'          => $post['group_id'],
                'mlevel_id'         => $post['mlevel_id'],
                'jabatan_id'        => parseInputNull($post['jabatan_id']),
                'member_desc'       => $post['member_desc'],
                'member_status'     => $post['member_status'],
                'member_ceo'        => $post['member_ceo'],
                'member_create_date'   => date("Y-m-d H:i:s"),
				'id_level_karyawan' => $post['id_level_karyawan'],
            );

            $insert = $this->member_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $member_id = $insert;

                // ON INSERT MEMBER INSERT KOMPETENSI MEMBER
                if ($post['jabatan_id']){
                    $kompetensi = $this->kompetensi_jabatan_model->get_by_jabatan($post['jabatan_id']);
                    if ($kompetensi){
                        foreach ($kompetensi as $k => $v){

                            $kompetensi_id = $v['cr_id'];
                            $member_id = $insert;
                            $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                            if ($get_member){
                                // Member Exist Then Skip

                            }else{
                                $data_kompetensi_member = array(
                                    'cr_id'     => $kompetensi_id,
                                    'member_id' => $member_id,
                                );
                                $this->kompetensi_member_model->insert($data_kompetensi_member);
                            }

                        }
                    }
                }


                $notif = array();

                // Start Handle File
                $this->load->library('upload');
                if (isset($_FILES['file']['name'])){

                    // Config File Name

                    $filename_origin  = $_FILES['file']['name'];
                    $filename_system  = preg_replace('/\s+/', '', 'member_'.uniqid().'_'.$filename_origin);

                    $ext_pos = strrpos($filename_system, '.');
                    if ($ext_pos){
                        $ext = substr($filename_system, $ext_pos);
                        $filename_system = substr($filename_system, 0, $ext_pos);
                        $filename_system = str_replace('.', '_', $filename_system).$ext;
                    }

                    $config['file_name']     = $filename_system;


                    // Config Folder
                    $upload_folder = UPLOAD_FOLDER;
                    $file_folder = 'image';
                    $full_folder = $upload_folder.$file_folder;

                    $config['upload_path'] = $full_folder; //path folder
                    if(!is_dir($full_folder)){
                        mkdir($full_folder,0777);
                    }

                    $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                    $config['max_size']      = '5000';

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')){

                        $go_upload = $this->upload->data();

                        $update_member = $this->member_model->update(
                            array(
                                'member_id'      => $member_id,
                                'member_image'   => $filename_system,
                            )
                        );

                    }else{
                        // GAGAL UPLOAD
                        //$notif[] = $this->upload->display_errors();
                        //$notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                    }

                }
                // End Handle File


                $url_return = site_url('member/detail/').$insert;

                if (!$notif) {
                    flash_notif_success(NULL,$url_return);
                }else{
                    $msg = '';
                    foreach ($notif as $k => $v) {
                        $msg .= $v."<br>";
                    }
                    flash_notif_warning($msg,$url_return);
                }


            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit_personal($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.edit');
        }


        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/'.$member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_province'][''] = '-';
            $param_query['filter_active'] = '';
            $get_province = $this->province_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_province!=FALSE){
                foreach ($get_province['data'] as $k => $v) {
                    $data['form_opt_province'][$v['city_province']] = $v['city_province'];
                }
            }

            $data['request']            = $member;
            $data['member']            = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_personal').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member';
            $data['page']               = 'member/member_form_edit_personal_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'member_id'  => $post['member_id']==NULL?NULL:$post['member_id'],
                'member_name'       => $post['member_name'],
                'member_phone'      => $post['member_phone'],
                'member_province'   => $post['member_province'],
                'member_city'       => $post['member_city'],
                'member_address'    => $post['member_address'],
            );


            $edit = $this->member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('member/detail/'.$member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_access($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.edit');
        }


        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/'.$member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
			$data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = 'active';
			$param_query['sort'] = 'id_klien ASC, group_name ASC';
			$param_query['sort_order'] = '';
			$get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {

                    if (!has_access_manage_all_member()){
                        if (in_array($v['group_id'], my_groups())){
                            $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                        }
                    }else{
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }


                }
            }

            $data['form_opt_jabatan'][''] = '-';
            $param_query['filter_group'] = $member['group_id'];
            $get_jabatan = $this->jabatan_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_jabatan!=FALSE){
                foreach ($get_jabatan['data'] as $k => $v) {
                    $data['form_opt_jabatan'][$v['jabatan_id']] = $v['jabatan_name'].' ('.$v['group_name'].')';
                }
            }
			
			$data['form_opt_level_karyawan'][''] = '-';
			$sql = "select k.* from _group g, _member_level_karyawan k where g.id_klien=k.id_klien and g.group_id='".$member['group_id']."' and k.status='active' order by k.nama ";
			$res = $this->db->query($sql);
			$arr = $res->result_array();
			foreach($arr as $key => $val) {
				$data['form_opt_level_karyawan'][$val['id']] = $val['nama'];
			}

            $data['form_opt_level'][''] = '-';
            $param_query['filter_active'] = '';
			$param_query['sort'] = '';
			$param_query['sort_order'] = '';
            $get_level = $this->member_level_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_level!=FALSE){
                foreach ($get_level['data'] as $k => $v) {
                    $data['form_opt_level'][$v['mlevel_id']] = $v['mlevel_name'];
                }
            }

            $data['form_opt_bidang'][''] = '-';
            $param_query['filter_active'] = '';
            $get_bidang = getBidang();
            if ($get_bidang!=FALSE){
                foreach ($get_bidang as $k => $v) {
                    $data['form_opt_bidang'][$k] = $v;
                }
            }

            $data['request']            = $member;
            $data['member']             = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_access').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member <br><small>'.$member['member_name'].'</small>';
            $data['page']               = 'member/member_form_edit_access_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
			
			$post['id_level_karyawan'] = $this->member_model->checkLevelKaryawan($post['group_id'],$post['id_level_karyawan']);

            $data = array(
                'member_id'  => $post['member_id']==NULL?NULL:$post['member_id'],
                'member_nip'        => $post['member_nip'],
                'member_email'      => parseInputNull($post['member_email']),
                'group_id'          => $post['group_id'],
                'jabatan_id'        => $post['jabatan_id'],
                'mlevel_id'         => $post['mlevel_id'],
                'member_desc'       => $post['member_desc'],
				'id_level_karyawan' => $post['id_level_karyawan'],
            );

            // ON UPDATE INSERT KOMPETENSI MEMBER
            if ($post['jabatan_id']){
                $kompetensi = $this->kompetensi_jabatan_model->get_by_jabatan($post['jabatan_id']);
                if ($kompetensi){
                    foreach ($kompetensi as $k => $v){

                        $kompetensi_id = $v['cr_id'];
                        $member_id = $post['member_id'];
                        $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                        if ($get_member){
                            // Member Exist Then Skip

                        }else{
                            $data_kompetensi_member = array(
                                'cr_id'     => $kompetensi_id,
                                'member_id' => $member_id,
                            );
                            $this->kompetensi_member_model->insert($data_kompetensi_member);
                        }

                    }
                }
            }

            $edit = $this->member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_id,'Edit','Akses');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('member/detail/'.$member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_picture($member_id=NULL){
        has_access('member.edit');

        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/').$member_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $member['media_value'] = $member['member_image'];
            $data['request']            = $member;
            $data['member']            = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_picture').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$member['member_name'].'</small>';
            $data['page']               = 'member/member_form_edit_picture_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name

                $filename_origin  = $_FILES['file']['name'];
                $filename_system  = preg_replace('/\s+/', '', 'member_'.uniqid().'_'.$filename_origin);

                $ext_pos = strrpos($filename_system, '.');
                if ($ext_pos){
                    $ext = substr($filename_system, $ext_pos);
                    $filename_system = substr($filename_system, 0, $ext_pos);
                    $filename_system = str_replace('.', '_', $filename_system).$ext;
                }

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;
                $file_folder = 'image';
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $go_upload = $this->upload->data();

                    $update_member = $this->member_model->update(
                        array(
                            'member_id'      => $member['member_id'],
                            'member_image'   => $filename_system,
                        )
                    );

                }else{
                    // GAGAL UPLOAD
                    //$notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            create_log($this->section_id,$member_id,'Edit','');
            if (!$notif) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = '';
                foreach ($notif as $k => $v) {
                    $msg .= $v."<br>";
                }
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    // UNUSED
    /*function edit_jabatan($member_id=NULL){
        has_access('member.edit');

        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/'.$member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_jabatan'][''] = '-';
            $param_query['filter_active'] = '';
            $get_jabatan = $this->jabatan_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_jabatan!=FALSE){
                foreach ($get_jabatan['data'] as $k => $v) {
                    $data['form_opt_jabatan'][$v['jabatan_id']] = $v['jabatan_name'].' ('.$v['group_name'].')';
                }
            }

            $data['request']            = $member;
            $data['member']             = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_jabatan').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member <br><small>'.$member['member_name'].'</small>';
            $data['page']               = 'member/member_form_edit_jabatan_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'member_id'  => $post['member_id']==NULL?NULL:$post['member_id'],
                'jabatan_id'        => $post['jabatan_id'],
            );


            // ON UPDATE INSERT KOMPETENSI MEMBER
            if ($post['jabatan_id']){
                $kompetensi = $this->kompetensi_jabatan_model->get_by_jabatan($post['jabatan_id']);
                if ($kompetensi){
                    foreach ($kompetensi as $k => $v){

                        $kompetensi_id = $v['cr_id'];
                        $member_id = $post['member_id'];
                        $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                        if ($get_member){
                            // Member Exist Then Skip

                        }else{
                            $data_kompetensi_member = array(
                                'cr_id'     => $kompetensi_id,
                                'member_id' => $member_id,
                            );
                            $this->kompetensi_member_model->insert($data_kompetensi_member);
                        }

                    }
                }
            }


            $edit = $this->member_model->update($data);
            if ($edit==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('member/detail/'.$member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }*/


    function edit_password($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.edit');
        }


        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/'.$member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');
        $this->form_validation->set_rules('member_password', 'Password', 'required');
        $this->form_validation->set_rules('member_password_confirm', 'Confirm Password', 'required|matches[member_password]');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $member;
            $data['member']            = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_password').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member <br><small>'.$member['member_name'].'</small>';
            $data['page']               = 'member/member_form_edit_password_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'member_id'  => $post['member_id']==NULL?NULL:$post['member_id'],
                'member_password'   => md5(trim($post['member_password'])),
            );


            $edit = $this->member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_id,'Edit','Password');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('member/detail/'.$member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_status($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.edit');
        }


        $member = $this->get_member($member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/'.$member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $member;
            $data['member']            = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/edit_status').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member <br><small>'.$member['member_name'].'</small>';
            $data['page']               = 'member/member_form_edit_status_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'member_id'  => $post['member_id']==NULL?NULL:$post['member_id'],
                'member_status'     => $post['member_status'],
                'member_ceo'        => $post['member_ceo'],
            );


            $edit = $this->member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('member/detail/'.$member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function add_as_expert($member_id=NULL){

        if (!has_access_manage_all_member()){
            has_access('member.edit');
        }


        $member = $this->get_member($member_id);


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/detail/').$member_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member']         = $member;
            $data['request']            = $member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/add_as_expert').'/'.$member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Jadikan Sebagai Expert';
            $data['page']               = 'member/member_form_add_as_expert_view';
            $this->load->view('main_view',$data);
        }else{

            $data_expert = array(
                'member_id' => $member['member_id'],
                'group_id'  => $member['group_id'],
                'em_name'   => $member['member_name'],
                'em_image'  => $member['member_image'],
                'em_status' => 'active',
                'em_create_date' => date('Y-m-d H:i:s')
            );
            $insert_expert = $this->expert_member_model->insert($data_expert);
            if ($insert_expert){

                $em_id = $insert_expert;
                $data_member_update = array(
                    'member_id' => $member['member_id'],
                    'is_expert' => '1'
                );
                $this->member_model->update($data_member_update);

                create_log($this->section_id,$member_id,'Edit','Expert');
                $msg        = $member['member_name']." telah dijadikan sebagai expert.";
                $url_return = site_url('expert_member/detail/'.$em_id);
                flash_notif_success($msg,$url_return);

            }else{
                $msg        = "Data gagal diubah.";
                $url_return = site_url('member/add_as_expert/'.$member_id);
                flash_notif_failed($msg,$url_return);
            }



        }
    }


    // IMPORT EXCEL
    function import(){
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        has_access('member.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member/import/');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('group_id', 'Group ID', 'required');


        $data['form_opt_group'][''] = '-';
        $param_query['filter_active'] = 'active';
		$param_query['filter_non_aghris_only'] = '1';
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

        if ($this->form_validation->run() == FALSE){

            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/import');
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Import';
            $data['page']               = 'member/member_form_import_view';
            $this->load->view('main_view',$data);
        }else{
			$arrNIK_found = array();

            $post = $this->input->post();
			$group_id = $post['group_id'];

            $data['request']['group_id'] = $group_id;
            $data['sheet_data'] = array();

            if(isset($_POST['preview'])) {
                $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
					// level karyawan
					$arrK = array();
					$sql = "select k.* from _group g, _member_level_karyawan k where g.id_klien=k.id_klien and g.group_id='".$group_id."' and k.status='active' order by k.nama ";
					$res = $this->db->query($sql);
					$arr = $res->result_array();
					foreach($arr as $key => $val) {
						$arrK[ $val['nama'] ] = $val['id'];
					}
					
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
						for($i = 1;$i < count($sheetData);$i++) {

							$member_id = "";
                            $is_error = 0;
                            $error_message = NULL;
							
							$level_karyawan = $sheetData[$i]['3'];
							$nik_lama = $sheetData[$i]['4'];
							$nik_username = $sheetData[$i]['5'];
							
							// level ditemukan?
							if(!isset($arrK['BOD-'.$level_karyawan])) {
								$is_error = 1;
								$error_message .= 'Error: Level tidak dikenal.<br>';
							}
							
							if(empty($nik_lama)) {
								if(empty($nik_username)) {
									$is_error = 1;
									$error_message .= 'Error: NIK Username kosong.<br>';
								} else {
									// NIK username apakah sudah ada yg punya?
									$is_exist_group_nip = $this->member_model->get_by_group_nip($group_id,trim($nik_username));
									if ($is_exist_group_nip){
										$member_id = $is_exist_group_nip['member_id'];
										$error_message .= 'NIK Username ditemukan, data akan diupdate.<br>';
									} else {
										$error_message .= 'NIK Username tidak ditemukan, data akan ditambah.<br>';
									}
								}
							} else {
								$is_exist_group_nip = $this->member_model->get_by_group_nip($group_id,trim($nik_lama));
								$member_id = $is_exist_group_nip['member_id'];
								if (empty($member_id)){
									$is_error = 1;
									$error_message .= 'Error: NIK lama tidak ditemukan.<br>';
								} else {
									if(empty($nik_username)) {
										$is_error = 1;
										$error_message .= 'Error: NIK Username masih kosong.<br>';
									} else {
										// NIK username apakah sudah ada yg punya?
										if($nik_lama==$nik_username) {
											$error_message .= 'NIK Lama = NIK Username, data akan diupdate.<br>';
										} else {
											$is_exist_group_nip = $this->member_model->get_by_group_nip($group_id,trim($nik_username));
											if ($is_exist_group_nip){
												$is_error = 1;
												$error_message .= 'Error: NIK Username sudah ada di data member. Tidak dapat mengupdate NIK lama ke NIK baru.<br>';
											} else {
												$error_message .= 'NIK Lama ditemukan, NIK Username tidak ditemukan, NIK akan diupdate ke NIK baru.<br>';
											}
										}
									}
								}
							}
							
							if(!empty($member_id)) {
								if(isset($arrNIK_found[$member_id])) {
									$is_error = 1;
									$error_message .= 'Error Update: baris diabaikan dikarenakan proses yg terjadi pada baris '.$arrNIK_found[$member_id].'. Data pada baris ini (baris '.$i.') silahkan diupload ulang menggunakan excel terpisah.<br>';
								} else {
									$arrNIK_found[$member_id] = $i;
								}
							}

                            $data['sheet_data'][] = array(
                                'nama'      => $sheetData[$i]['2'],
                                'level'     => $sheetData[$i]['3'],
								'nik_lama'       => $nik_lama,
								'nik_username'   => $nik_username,
                                'no_telp'   => $sheetData[$i]['6'],
                                'email'     => $sheetData[$i]['7'],
								'member_id' => $member_id,
                                'is_error'  => $is_error,
                                'error_message' => $error_message
                            );


                        }
                    }


                }

            }
			
			$data['url_return']         = $url_return;
            $data['form_action']        = site_url('member/import_post');
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Import';
            $data['page']  = 'member/member_form_import_preview_view';
            $this->load->view('main_view',$data);

        }


    }

    function import_post(){
        $post = $this->input->post();

        if ($post){
			$juml_kolom_xls = 7;
			$juml_variabel_non_array = 14; // dilebihin dari yg seharusnya
			$max_allowed = ini_get('max_input_vars');
			$juml_variabel = (count($post['member_name']) * $juml_kolom_xls) + $juml_variabel_non_array;
			
			// jumlah variabel post lbh dari settingan php?
			if($juml_variabel>$max_allowed) {
				flash_notif_warning('Import member gagal diproses (max '.$max_allowed.' baris/excel).',site_url('member?group_ids[]='.$group_id));
				exit;
			}
			
			$arrK = array();
			$group_id = (int) $post['group_id'];
			
			// level karyawan
			$sql = "select k.* from _group g, _member_level_karyawan k where g.id_klien=k.id_klien and g.group_id='".$group_id."' and k.status='active' order by k.nama ";
			$res = $this->db->query($sql);
			$arr = $res->result_array();
			foreach($arr as $key => $val) {
				$arrK[ $val['nama'] ] = $val['id'];
			}
			
			foreach ($post['member_name'] as $k => $v) {
				if(isset($arrK['BOD-'.$post['member_level'][$k]])) {
					$id_level_karyawan = $arrK['BOD-'.$post['member_level'][$k]];
				} else {
					$id_level_karyawan = 0;
				}
				
				if($post['member_id'][$k]>0) { // update data
					$data = array(
						'member_id'         => $post['member_id'][$k],
						'member_name'       => $post['member_name'][$k],
						'member_phone'      => $post['member_phone'][$k],
						'member_email'      => $post['member_email'][$k],
						// 'member_password'   => md5(trim($post['member_nik_username'][$k])),
						'mlevel_id'         => $post['member_level'][$k],
						'id_level_karyawan' => $id_level_karyawan,
					);
					
					// update nik?
					if(!empty($post['member_nik_username'][$k])) {
						$data['member_nip'] = $post['member_nik_username'][$k];
						$data['nip_pre_sap'] = $post['member_nik_lama'][$k];
						$data['nip_sap'] = $post['member_nik_username'][$k];
					}
				
					$this->member_model->update($data);
				} else { // tambah data
					$data = array(
						'member_name'       => $post['member_name'][$k],
						'member_phone'      => $post['member_phone'][$k],
						'member_nip'        => $post['member_nik_username'][$k],
						'member_email'      => $post['member_email'][$k],
						'member_password'   => md5(trim($post['member_nik_username'][$k])),
						'group_id'          => $post['group_id'],
						'mlevel_id'         => $post['member_level'][$k],
						'id_level_karyawan' => $id_level_karyawan,
						'jabatan_id'        => NULL,
						'member_status'     => 'active',
						'member_create_date'   => date("Y-m-d H:i:s"),
					);
					
					$this->member_model->insert($data);
				}
            }
        }

        flash_notif_success('Import member telah selesai diproses.',site_url('member?group_ids[]='.$group_id));

    }


    // AGHRIS

    function aghris_search(){

        if (!has_access_manage_all_member()){
            has_access('member.searchaghris');
        }


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('search_by', 'Cari berdasarkan', 'required|trim');
        $this->form_validation->set_rules('keyword', 'Kata kunci', 'required|trim');

        $data['form_opt_search_by'] = array(
            'nik'       => 'NIK',
            'name'      => 'Nama',
            'jabatan'   => 'Jabatan',
            'nohp'      => 'No.Handphone',
        );

        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Member';
            $data['page_sub_name']  = 'Cari di Aghris';

            $data['form_action']    = site_url('member/aghris_search');
            $data['page']           = 'member/member_form_aghris_search_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $search_by = $post['search_by'];
            $keyword   = $post['keyword'];

            $result = array();
            if ($search_by=='nik'){
                $result = aghris_search_by_nik($keyword);
            }else if ($search_by=='name'){
                $result = aghris_search_by_name($keyword);
            }else if ($search_by=='jabatan'){
                $result = aghris_search_by_jabatan($keyword);
            }else if ($search_by=='nohp'){
                $result = aghris_search_by_nohp($keyword);
            }

            $data['search_by']  = $search_by;
            $data['keyword']    = $keyword;
            $data['result']     = $result;


            $data['page_name']      = 'Member';
            $data['page_sub_name']  = 'Cari di Aghris';

            $data['form_action']    = site_url('member/aghris_search');
            $data['page']           = 'member/member_form_aghris_search_view';

            $this->load->view('main_view',$data);

        }
    }

    function aghris_sync(){

        $post = $this->input->post();

        $update_existing_data = isset($post['update_existing_data']) ? $post['update_existing_data'] : NULL;
        $raw_data_json = parseInputNull($post['raw_data_json']);
		
		// level karyawan
		$group_id = '1';
		$arrK = array();
		$sql = "select k.* from _group g, _member_level_karyawan k where g.id_klien=k.id_klien and g.group_id='".$group_id."' and k.status='active' order by k.nama ";
		$res = $this->db->query($sql);
		$arr = $res->result_array();
		foreach($arr as $key => $val) {
			$arrK[ $val['nama'] ] = $val['id'];
		}

        $data_notif = array();
        if ($raw_data_json){
            $data_raw = json_decode($raw_data_json, TRUE);

            $data_member = array();
            foreach ($data_raw as $k => $v){

                // Sync Jabatan
                $get_jabatan = $this->jabatan_model->get_by_code($data_raw[$k][7]);
                if ($get_jabatan){
                    $jabatan_id = $get_jabatan['jabatan_id'];
                }else{
                    $data_jabatan = [
                        'jabatan_name'  => $data_raw[$k][3]?$data_raw[$k][3]:'-',
                        'jabatan_code'  => $data_raw[$k][7],
                        'jabatan_level' => 9
                    ];
                    $jabatan_id =  $this->jabatan_model->insert($data_jabatan);
                }
				
				// level karyawan
				if(isset($arrK['BOD-'.$data_raw[$k][22]])) {
					$id_level_karyawan = $arrK['BOD-'.$data_raw[$k][22]];
				} else {
					$id_level_karyawan = 0;
				}
				
                // Sync Member
                $default_group_id = 34;
                $data_member = array(
                    'group_id'          => !empty($data_raw[$k][5]) ? $data_raw[$k][5] : $default_group_id,
                    'jabatan_id'        => $jabatan_id,
                    'mlevel_id'         => $get_jabatan?$get_jabatan['jabatan_level']:6,
                    'member_name'       => $data_raw[$k][0],
                    'member_nip'        => $data_raw[$k][2],
                    'member_token'      => $data_raw[$k][8],
                    'member_jabatan'    => is_null($data_raw[$k][3])?'':$data_raw[$k][3],
                    'member_email'      => $data_raw[$k][20],
                    'member_kel_jabatan'=> is_null($data_raw[$k][21])?'':$data_raw[$k][21],
                    'member_image'      => trim($data_raw[$k][9])==='#' ? '' : $data_raw[$k][9],
                    'member_unit_kerja' => is_null($data_raw[$k][10])?'':$data_raw[$k][10],
                    'member_gender'     => $data_raw[$k][11]=='Male'?'Pria':'Wanita',
                    'member_birth_place' => $data_raw[$k][12],
                    'member_birth_date' => $data_raw[$k][13],
                    'member_phone'      => $data_raw[$k][4],
                    'member_address'    => is_null($data_raw[$k][14])?'':$data_raw[$k][10],
                    'member_city'       => is_null($data_raw[$k][15])?'':$data_raw[$k][15],
                    'member_province'   => is_null($data_raw[$k][16])?'':$data_raw[$k][16],
                    'member_postcode'   => is_null($data_raw[$k][17])?'':$data_raw[$k][17],
                    'member_ceo'        => $data_raw[$k][18],
                    'member_create_date'=> $data_raw[$k][19],
					'id_level_karyawan'=> $id_level_karyawan,
                );

                // Processing member_image

                if($data_raw[$k][9]=='#' || !$data_raw[$k][9] ){
                    $member_image = '';
                }else{
                    $url = $data_raw[$k][9];

                    $filename_system = $url;
                    $filename_system_new = 'member_aghris_'.uniqid();
                    $ext_pos = strrpos($filename_system, '.');
                    if ($ext_pos){
                        $ext = substr($filename_system, $ext_pos);
                        $filename_system = substr($filename_system, 0, $ext_pos);
                        $filename_system = str_replace('.', '_', $filename_system_new).$ext;
                    }

                    $img = UPLOAD_FOLDER.'image/'.$filename_system;
                    $put_content = file_put_contents($img, file_get_contents($url));

                    if ($put_content){
                        $member_image = $filename_system;
                    }else{
                        $member_image = $data_raw[$k][9];
                    }

                }
                $data_member['member_image'] = $member_image;



                // Find Existing by NIP
                $get_member_by_nip = $this->member_model->get_by_nip($data_member['member_nip']);
                if ($get_member_by_nip){

                    if ($update_existing_data){
                        $data_member['member_id'] = $get_member_by_nip['member_id'];
                        unset($data_member['member_create_date']);
                        $this->member_model->update($data_member);

                        $data_notif[] = array(
                            'member_id'   => $get_member_by_nip['member_id'],
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message'      => 'Data member di-update',
                        );
                    }else{
                        $data_notif[] = array(
                            'member_id'   => $get_member_by_nip['member_id'],
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message'      => 'Update dilewati'
                        );
                    }



                }else{
                    // Insert
                    $data_member['member_password'] = md5(trim($data_member['member_nip']));
                    $data_member['member_status']   = 'active';
                    $data_member['member_poin']     = 0;
                    $data_member['member_saldo']    = 0;
                    $create_member = $this->member_model->insert($data_member);
                    if ($create_member){
                        $data_notif[] = array(
                            'member_id'   => $create_member,
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message'      => 'Member baru berhasil dibuat'
                        );
                    }else{
                        $data_notif[] = array(
                            'member_id'   => "-",
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'failed',
                            'status_message'      => 'Gagal membuat member baru'
                        );
                    }
                }



            }

            //print_r($data_member);

        }else{
            $data_notif[] = array();
        }

        /*print_r($data_notif);
        exit();*/

        $data['notif'] = $data_notif;

        $data['page_name']      = 'Member';
        $data['page_sub_name']  = 'Cari di Aghris';

        $data['page']           = 'member/member_notif_aghris_sync_view';

        $this->load->view('main_view',$data);


    }


    function delete($member_id=NULL){}

}