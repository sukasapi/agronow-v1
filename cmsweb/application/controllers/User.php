<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'user_model',
            'media_model',
            'group_model',
            'user_level_model',
            'province_model',
            'klien_model',
        ));

        $this->section_id = 3;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->user_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['user_id']           = $item->user_id;
            $row['user_name']         = $item->user_name;
            $row['user_level_name']   = $item->user_level_name;
            $row['user_email']        = $item->user_email;
            $row['user_status']       = $item->user_status;
            $row['group_name']        = $item->group_name?$item->group_name:'';
            $row['klien_nama']        = $item->klien_nama?$item->klien_nama:'';

            $row['user_create_date']  = $item->user_create_date?parseDateShortReadable($item->user_create_date):NULL;
            $row['user_create_time']  = $item->user_create_date?parseTimeReadable($item->user_create_date):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user_model->count_all(),
            "recordsFiltered" => $this->user_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function check_email($email){
        if ($this->user_model->get_by_email(trim($email))==FALSE){
            return TRUE;
        }else{
            $this->form_validation->set_message('check_email', 'Email '. $email .' telah terdaftar');
            return FALSE;
        }
    }

    function check_email_except($email){
        $current_email = $this->input->post('current_user_email');
        if ($this->user_model->get_by_email(trim($email),trim($current_email))==FALSE){
            return TRUE;
        }else{
            $this->form_validation->set_message('check_email_except', 'Email '. $email .' telah terdaftar');
            return FALSE;
        }
    }

    function get_user($user_id){
        $get_user = $this->user_model->get($user_id);
        if ($get_user==FALSE){
            redirect(404);
        }else{
            return $get_user;
        }
    }

    function index(){
        has_access('user.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Administrator';
        $data['page_sub_name']      = 'List Administrator';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'user/user_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($user_id=NULL){
        has_access('user.view');

        $user = $this->get_user($user_id);

        $data['user']        = $user;
        $data['page_name']      = 'Administrator';
        $data['page_sub_name']  = $user['user_name'];
        $data['page'] = 'user/user_detail_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('user.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required');
        $this->form_validation->set_rules('user_password_confirm', 'Confirm Password', 'required|matches[user_password]');
        $this->form_validation->set_rules('user_email', 'Email', 'required|trim|callback_check_email');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_klien'][''] = '-';
            $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,NULL);
            if ($get_klien!=FALSE){
                foreach ($get_klien['data'] as $k => $v) {
                    $data['form_opt_klien'][$v['id']] = $v['nama'];
                }
            }

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = 'active';
			$param_query['sort'] = '_group.group_name';
			$param_query['sort_order'] = 'asc';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
			
			// reset sort
			$param_query['sort'] = '';
			$param_query['sort_order'] = '';

            $data['form_opt_level'][''] = '-';
            $param_query['filter_active'] = '';
            $get_level = $this->user_level_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_level!=FALSE){
                foreach ($get_level['data'] as $k => $v) {
                    $data['form_opt_level'][$v['user_level_id']] = $v['user_level_name'];
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


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Administrator';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('user/create');
            $data['page']           = 'user/user_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'user_name'       => $post['user_name'],
                'user_email'       => trim($post['user_email']),
                'user_username'       => $post['user_email'],
                'user_password'   => md5(md5(trim($post['user_password']))),
                'user_code'          => $post['group_id']?$post['group_id']:'',
                'user_level_id'         => $post['user_level_id'],
                'user_status'     => $post['user_status'],
                'user_create_date'   => date("Y-m-d H:i:s"),
                'user_birth'    => '1971-01-01',
                'id_klien'      => $post['id_klien'],
            );

            $insert = $this->user_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('user/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    function edit($user_id){
        has_access('user.edit');

        $user = $this->get_user($user_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user/detail/'.$user_id);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('user_email', 'Email', 'trim|callback_check_email_except');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_klien'][''] = '-';
            $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,NULL);
            if ($get_klien!=FALSE){
                foreach ($get_klien['data'] as $k => $v) {
                    $data['form_opt_klien'][$v['id']] = $v['nama'];
                }
            }


            $data['form_opt_group'][''] = '-';
            $param_query['filter_klien'] = $user['id_klien'];
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_level'][''] = '-';
            $param_query['filter_active'] = '';
            $get_level = $this->user_level_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_level!=FALSE){
                foreach ($get_level['data'] as $k => $v) {
                    $data['form_opt_level'][$v['user_level_id']] = $v['user_level_name'];
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


            $data['user']     = $user;
            $data['request']     = $user;

            $data['url_return']     = $url_return;
            $data['editable']       = TRUE;

            $data['page_name']      = 'Administrator';
            $data['page_sub_name']  = 'Edit';

            $data['form_action']    = site_url('user/edit/'.$user_id);
            $data['page']           = 'user/user_form_edit_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data = array(
                'user_id'        => $post['user_id'],
                'user_name'        => $post['user_name'],
                'user_email'       => trim($post['user_email']),
                'user_username'    => $post['user_email'],
                'user_code'        => $post['group_id']?$post['group_id']:'',
                'user_level_id'    => $post['user_level_id'],
                'user_birth'    => '1971-01-01',
                'id_klien'      => parseInputNull($post['id_klien']),
            );

            $update = $this->user_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$user_id,'Edit','');
                $url_return = site_url('user/detail/').$post['user_id'];
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function edit_status($user_id=NULL){
        has_access('user.edit');

        $user = $this->get_user($user_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user/detail/').$user_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $user;
            $data['user']            = $user;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('user/edit_status').'/'.$user_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Administrator";
            $data['page_sub_name']      = 'Edit Status'.'<br><small>'.$user['user_name'].'</small>';
            $data['page']               = 'user/user_form_edit_status_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'user_id'  => $post['user_id']==NULL?NULL:$post['user_id'],
                'user_status'        => $post['user_status'],
            );


            $edit = $this->user_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$user_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function edit_password($user_id=NULL){
        has_access('user.edit');

        $user = $this->get_user($user_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user/detail/'.$user_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_id', 'ID', 'required');
        $this->form_validation->set_rules('user_password', 'Password', 'required');
        $this->form_validation->set_rules('user_password_confirm', 'Confirm Password', 'required|matches[user_password]');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $user;
            $data['user']            = $user;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('user/edit_password').'/'.$user_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Member <br><small>'.$user['user_name'].'</small>';
            $data['page']               = 'user/user_form_edit_password_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'user_id'  => $post['user_id']==NULL?NULL:$post['user_id'],
                'user_password'   => md5(md5(trim($post['user_password']))),
            );


            $edit = $this->user_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$user_id,'Edit','Password');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('user/detail/'.$user_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function delete($user_id=NULL){
        has_access('user.delete');

        $user = $this->get_user($user_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user/detail/').$user_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['user']         = $user;
            $data['request']            = $user;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('user/delete').'/'.$user_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Administrator";
            $data['page_sub_name']      = 'Hapus Administrator';
            $data['page']               = 'user/user_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->user_model->delete($user_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$user_id,'Hapus','');
                $msg        = $user['user_name']." telah dihapus.";
                $url_return = site_url('user');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('user/delete/'.$user_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}