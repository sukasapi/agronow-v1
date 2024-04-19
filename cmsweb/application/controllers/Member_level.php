<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_level extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_level_model'
        ));

        $this->section_id = 9;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_level_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['mlevel_id']       = $item->mlevel_id;
            $row['mlevel_name']     = $item->mlevel_name;
            $row['mlevel_status']   = $item->mlevel_status;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_level_model->count_all(),
            "recordsFiltered" => $this->member_level_model->count_filtered(),
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
        $search_member_level = $this->member_level_model->search($query,75);

        if ($search_member_level!=FALSE) {
            $data_response = array();
            foreach ($search_member_level as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['mlevel_id'];
                $data_response['results'][$k]['text']  = $v['mlevel_name'];
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }

    private function get_member_level($member_level_id){
        $get_member_level = $this->member_level_model->get($member_level_id);
        if ($get_member_level==FALSE){
            redirect(404);
        }else{
            return $get_member_level;
        }
    }

    function index(){
        has_access('memberlevel.view');

        $data['page_name']          = 'Member Level';
        $data['page_sub_name']      = 'List Member Level';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_level/member_level_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('memberlevel.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_level');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mlevel_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Level';
            $data['page_sub_name']      = 'Tambah Level';

            $data['form_action']    = site_url('member_level/create').'?url_return='.$url_return;
            $data['page']           = 'member_level/member_level_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'mlevel_name'  => $post['mlevel_name'],
                'mlevel_status'  => $post['mlevel_status'],
            );

            $insert = $this->member_level_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Member Level');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($member_level_id=NULL){
        has_access('memberlevel.edit');

        $member_level = $this->get_member_level($member_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_level');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mlevel_id', 'ID', 'required');
        $this->form_validation->set_rules('mlevel_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['member_level']      = $member_level;
            $data['request']            = $member_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_level/edit').'/'.$member_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Member";
            $data['page_sub_name']      = 'Edit Level';
            $data['page']               = 'member_level/member_level_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'mlevel_id'  => $post['mlevel_id']==NULL?NULL:$post['mlevel_id'],
                'mlevel_name'  => $post['mlevel_name'],
                'mlevel_status'        => $post['mlevel_status'],
            );


            $edit = $this->member_level_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_level_id,'Edit','Member Level');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($member_level_id=NULL){
        has_access('memberlevel.delete');

        $member_level = $this->get_member_level($member_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_level');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mlevel_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member_level']         = $member_level;
            $data['request']            = $member_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_level/delete').'/'.$member_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Member";
            $data['page_sub_name']      = 'Hapus Level';
            $data['page']               = 'member_level/member_level_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_level_model->delete($member_level_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$member_level_id,'Hapus','Member Level');
                $msg        = $member_level['mlevel_name']." telah dihapus.";
                $url_return = site_url('member_level');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_level/delete/'.$member_level_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}