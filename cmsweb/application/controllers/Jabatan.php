<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jabatan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'jabatan_model',
            'group_model',
        ));

        $this->section_id = 9;
    }

    function l_modal_ajax(){
        $this->load->view('jabatan/jabatan_list_picker_modal_view');
    }

    function json(){
        $get_jabatan = $this->jabatan_model->get_all();
        foreach ($get_jabatan['data'] as $k => $v){
            $result['data'][] = array(
                'jabatan_id'      => $v['jabatan_id'],
                'jabatan_name'    => $v['jabatan_name'],
                'group_name'    => $v['group_name'],
            );
        }
        echo json_encode($result);
    }

    function ajax_get_by_group($group_id){
        $jabatan = $this->jabatan_model->get_by_group($group_id);
        $result = array();
        if ($jabatan){

            foreach ($jabatan as $v){
                $result[$v['jabatan_id']] = $v['jabatan_name'];
            }

        }
        echo json_encode($result);
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->jabatan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['jabatan_id']      = $item->jabatan_id;
            $row['jabatan_name']    = $item->jabatan_name;
            $row['group_id']        = $item->group_id;
            $row['group_name']      = $item->group_name;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->jabatan_model->count_all(),
            "recordsFiltered" => $this->jabatan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_jabatan($jabatan_id){
        $get_jabatan = $this->jabatan_model->get($jabatan_id);
        if ($get_jabatan==FALSE){
            redirect(404);
        }else{
            return $get_jabatan;
        }
    }

    function index(){
        has_access('memberjabatan.view');

        $data['page_name']          = 'Jabatan';
        $data['page_sub_name']      = 'List Jabatan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'jabatan/jabatan_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('memberjabatan.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('jabatan');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('jabatan_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Jabatan';
            $data['page_sub_name']      = 'Tambah Jabatan';

            $data['form_action']    = site_url('jabatan/create').'?url_return='.$url_return;
            $data['page']           = 'jabatan/jabatan_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'jabatan_name'  => $post['jabatan_name'],
                'group_id'      => $post['group_id'],
                'jabatan_create_date'   => date('Y-m-d H:i:s')
            );

            $insert = $this->jabatan_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Jabatan');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($jabatan_id=NULL){
        has_access('memberjabatan.edit');

        $jabatan = $this->get_jabatan($jabatan_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('jabatan');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('jabatan_id', 'ID', 'required');
        $this->form_validation->set_rules('jabatan_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }


            $data['jabatan']      = $jabatan;
            $data['request']            = $jabatan;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('jabatan/edit').'/'.$jabatan_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Jabatan";
            $data['page_sub_name']      = 'Edit Jabatan';
            $data['page']               = 'jabatan/jabatan_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'jabatan_id'  => $post['jabatan_id']==NULL?NULL:$post['jabatan_id'],
                'jabatan_name'  => $post['jabatan_name'],
                'group_id'        => $post['group_id'],
            );


            $edit = $this->jabatan_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$jabatan_id,'Edit','Jabatan');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($jabatan_id=NULL){
        has_access('memberjabatan.delete');

        $jabatan = $this->get_jabatan($jabatan_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('jabatan');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('jabatan_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['jabatan']         = $jabatan;
            $data['request']            = $jabatan;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('jabatan/delete').'/'.$jabatan_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Jabatan";
            $data['page_sub_name']      = 'Hapus Jabatan';
            $data['page']               = 'jabatan/jabatan_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->jabatan_model->delete($jabatan_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$jabatan_id,'Hapus','Jabatan');
                $msg        = $jabatan['jabatan_name']." telah dihapus.";
                $url_return = site_url('jabatan');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('jabatan/delete/'.$jabatan_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}