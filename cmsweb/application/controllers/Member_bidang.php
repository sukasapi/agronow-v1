<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_bidang extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_bidang_model'
        ));

        $this->section_id = 9;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_bidang_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['bidang_id']       = $item->bidang_id;
            $row['bidang_name']     = $item->bidang_name;
            $row['bidang_status']   = $item->bidang_status;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_bidang_model->count_all(),
            "recordsFiltered" => $this->member_bidang_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_member_bidang($member_bidang_id){
        $get_member_bidang = $this->member_bidang_model->get($member_bidang_id);
        if ($get_member_bidang==FALSE){
            redirect(404);
        }else{
            return $get_member_bidang;
        }
    }

    function index(){
        has_access('memberbidang.view');

        $data['page_name']          = 'Member Bidang';
        $data['page_sub_name']      = 'List Member Bidang';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_bidang/member_bidang_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('memberbidang.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_bidang');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('bidang_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Bidang';
            $data['page_sub_name']      = 'Tambah Bidang';

            $data['form_action']    = site_url('member_bidang/create').'?url_return='.$url_return;
            $data['page']           = 'member_bidang/member_bidang_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'bidang_name'  => $post['bidang_name'],
                'bidang_status'  => $post['bidang_status'],
            );

            $insert = $this->member_bidang_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Bidang');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($member_bidang_id=NULL){
        has_access('memberbidang.edit');

        $member_bidang = $this->get_member_bidang($member_bidang_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_bidang');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('bidang_id', 'ID', 'required');
        $this->form_validation->set_rules('bidang_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['member_bidang']      = $member_bidang;
            $data['request']            = $member_bidang;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_bidang/edit').'/'.$member_bidang_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Bidang Member";
            $data['page_sub_name']      = 'Edit Bidang';
            $data['page']               = 'member_bidang/member_bidang_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'bidang_id'  => $post['bidang_id']==NULL?NULL:$post['bidang_id'],
                'bidang_name'  => $post['bidang_name'],
                'bidang_status'        => $post['bidang_status'],
            );


            $edit = $this->member_bidang_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_bidang_id,'Edit','Bidang');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($member_bidang_id=NULL){
        has_access('memberbidang.delete');

        $member_bidang = $this->get_member_bidang($member_bidang_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_bidang');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('bidang_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member_bidang']         = $member_bidang;
            $data['request']            = $member_bidang;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_bidang/delete').'/'.$member_bidang_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Bidang Member";
            $data['page_sub_name']      = 'Hapus Bidang';
            $data['page']               = 'member_bidang/member_bidang_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_bidang_model->delete($member_bidang_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$member_bidang_id,'Hapus','Bidang');
                $msg        = $member_bidang['bidang_name']." telah dihapus.";
                $url_return = site_url('member_bidang');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_bidang/delete/'.$member_bidang_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}