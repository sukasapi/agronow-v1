<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'group_model',
            'media_model',
            'klien_model',
        ));

        $this->section_id = 16;

    }

    function l_modal_ajax(){
        $this->load->view('group/group_list_picker_modal_view');
    }

    function json(){
        $get_group = $this->group_model->get_all();
        foreach ($get_group['data'] as $k => $v){
            $result['data'][] = array(
                'group_id'      => $v['group_id'],
                'group_name'    => $v['group_name'],
            );
        }
        echo json_encode($result);
    }


    function ajax_get_by_klien($klien_id=0){
        $group = $this->group_model->get_by_klien($klien_id);
        $result = array();
        if ($group){

            foreach ($group as $v){
                $result[$v['group_id']] = $v['group_name'];
            }

        }
        echo json_encode($result);
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->group_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['group_id']              = $item->group_id;
            $row['group_name']  = $item->group_name;
			$row['silsilah']  = $item->silsilah;
            $row['group_portal']  = $item->group_portal;
            $row['group_status']  = $item->group_status;
            $row['group_has_level']  = $item->group_has_level;
            $row['total_member']  = $item->total_member;
            $row['aghris_company_code']  = $item->aghris_company_code;
            $row['klien_nama']  = $item->klien_nama;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->group_model->count_all(),
            "recordsFiltered" => $this->group_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    private function get_group($group_id){
        $get_group = $this->group_model->get($group_id);
        if ($get_group==FALSE){
            redirect(404);
        }else{
            return $get_group;
        }
    }

    function ajax_search(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get = $this->input->get();

        $query = isset($get['q'])?$get['q']:NULL;
        $search_group = $this->group_model->search($query,75);

        if ($search_group!=FALSE) {
            $data_response = array();
            foreach ($search_group as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['group_id'];
                $data_response['results'][$k]['text']  = $v['group_name'];
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }

    function index(){
        has_access('group.view');

        $data['page_name']          = 'Group';
        $data['page_sub_name']      = 'List group';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'group/group_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('group.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('group');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_klien'][''] = '-';
            $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,NULL);
            if ($get_klien!=FALSE){
                foreach ($get_klien['data'] as $k => $v) {
                    $data['form_opt_klien'][$v['id']] = $v['nama'];
                }
            }

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Group';
            $data['page_sub_name']      = 'Tambah Group';

            $data['form_action']    = site_url('group/create').'?url_return='.$url_return;
            $data['page']           = 'group/group_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'group_name'  => $post['group_name'],
				'silsilah'  => $post['silsilah'],
                'group_alias'  => slugify($post['group_name']),
                'group_status'  => $post['group_status'],
                'group_portal'  => $post['group_portal']?$post['group_portal']:'0',
                'group_has_level'  => $post['group_has_level']?$post['group_has_level']:'0',
                'aghris_company_code'  => parseInputNull($post['aghris_company_code']),
                'id_klien'  => $post['id_klien'],
            );

            $insert = $this->group_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($group_id=NULL){
        has_access('group.edit');

        $group = $this->get_group($group_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('group');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('group_id', 'ID', 'required');
        $this->form_validation->set_rules('group_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_klien'][''] = '-';
            $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,NULL);
            if ($get_klien!=FALSE){
                foreach ($get_klien['data'] as $k => $v) {
                    $data['form_opt_klien'][$v['id']] = $v['nama'];
                }
            }

            $data['group']      = $group;
            $data['request']            = $group;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('group/edit').'/'.$group_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Group";
            $data['page_sub_name']      = 'Edit Group';
            $data['page']               = 'group/group_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'group_id'  => $post['group_id']==NULL?NULL:$post['group_id'],
                'group_name'  => $post['group_name'],
				'silsilah'  => $post['silsilah'],
                'group_status'  => $post['group_status'],
                'group_portal'  => $post['group_portal']?$post['group_portal']:'0',
                'group_has_level'  => $post['group_has_level']?$post['group_has_level']:'0',
                'aghris_company_code'  => parseInputNull($post['aghris_company_code']),
                'id_klien'  => $post['id_klien'],
            );


            $edit = $this->group_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$group_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($group_id=NULL){
        has_access('group.delete');

        $group = $this->get_group($group_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('group');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('group_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['group']         = $group;
            $data['request']            = $group;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('group/delete').'/'.$group_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Group";
            $data['page_sub_name']      = 'Hapus Group';
            $data['page']               = 'group/group_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->group_model->delete($group_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$group_id,'Hapus','');
                $msg        = $group['group_name']." telah dihapus.";
                $url_return = site_url('group');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('group/delete/'.$group_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}