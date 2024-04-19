<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'user_level_model',
            'media_model',
            'group_model',
            'access_model',
            'user_level_access_model'
        ));
        $this->section_id = 3;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->user_level_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['user_level_id']       = $item->user_level_id;
            $row['user_level_name']     = $item->user_level_name;
            $row['user_level_status']   = $item->user_level_status;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user_level_model->count_all(),
            "recordsFiltered" => $this->user_level_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_user_level($user_level_id){
        $get_user_level = $this->user_level_model->get($user_level_id);
        if ($get_user_level==FALSE){
            redirect(404);
        }else{
            return $get_user_level;
        }
    }

    function index(){
        has_access('userlevel.view');

        $data['page_name']          = 'Administrator Level';
        $data['page_sub_name']      = 'List Administrator Level';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'user_level/user_level_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('userlevel.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user_level');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_level_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['available_access_menu'] = $this->access_model->get_available_menu();
            $data['access'] = $this->access_model->get_all();
            //print_r($data['available_access_menu']);

            $data['page_name']          = 'Administrator Level';
            $data['page_sub_name']      = 'Tambah Level';

            $data['form_action']    = site_url('user_level/create').'?url_return='.$url_return;
            $data['page']           = 'user_level/user_level_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();


            $created_by = NULL;
            $data = array(
                'user_level_name'  => $post['user_level_name'],
                'user_level_status'  => $post['user_level_status']?$post['user_level_status']:'0',
            );

            $insert = $this->user_level_model->insert($data);
            if ($insert==TRUE) {

                // Insert Level Access
                if (isset($post['access_ids'])){
                    $access_ids = $post['access_ids'];
                    $user_level_access = array();
                    foreach ($access_ids as $v){
                        $user_level_access[] = array(
                            'user_level_id' => $insert,
                            'access_id' => $v
                        );
                    }
                    $this->user_level_access_model->insert_batch($user_level_access);
                }


                create_log($this->section_id,$insert,'Tambah','User Level');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    function edit($user_level_id=NULL){
        has_access('userlevel.edit');

        $user_level = $this->get_user_level($user_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user_level/edit/'.$user_level_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_level_id', 'ID', 'required');
        $this->form_validation->set_rules('user_level_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['available_access_menu'] = $this->access_model->get_available_menu();
            $data['access'] = $this->access_model->get_all();

            $data['current_access_ids'] = array();
            $level_access = $this->user_level_access_model->get_by_level($user_level_id);
            if ($level_access){
                foreach ($level_access as $v) {
                    $data['current_access_ids'][] = $v['access_id'];
                }
            }


            $data['user_level']      = $user_level;
            $data['request']            = $user_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('user_level/edit').'/'.$user_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Administrator Level";
            $data['page_sub_name']      = 'Edit Level';
            $data['page']               = 'user_level/user_level_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'user_level_id'  => $post['user_level_id']==NULL?NULL:$post['user_level_id'],
                'user_level_name'  => $post['user_level_name'],
                'user_level_status'        => $post['user_level_status']?$post['user_level_status']:'0',
            );


            $edit = $this->user_level_model->update($data);

            // Insert Level Access
            $this->user_level_access_model->delete_by_level($post['user_level_id']);
            if (isset($post['access_ids'])){
                $access_ids = $post['access_ids'];
                $user_level_access = array();
                foreach ($access_ids as $v){
                    $user_level_access[] = array(
                        'user_level_id' => $post['user_level_id'],
                        'access_id' => $v
                    );
                }
                $this->user_level_access_model->insert_batch($user_level_access);
            }


            create_log($this->section_id,$user_level_id,'Edit','User Level');
            flash_notif_success(NULL,$url_return);

        }
    }


    function delete($user_level_id=NULL){
        has_access('userlevel.delete');

        $user_level = $this->get_user_level($user_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('user_level');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_level_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['user_level']         = $user_level;
            $data['request']            = $user_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('user_level/delete').'/'.$user_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Member";
            $data['page_sub_name']      = 'Hapus Level';
            $data['page']               = 'user_level/user_level_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->user_level_model->delete($user_level_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$user_level_id,'Hapus','User Level');
                $msg        = $user_level['user_level_name']." telah dihapus.";
                $url_return = site_url('user_level');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('user_level/delete/'.$user_level_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}