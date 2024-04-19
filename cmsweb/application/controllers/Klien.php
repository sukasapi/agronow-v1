<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Klien extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'klien_model',
        ));

        $this->section_id = 44;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->klien_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['id']      = $item->id;
            $row['nama']    = $item->nama;
            $row['status']  = $item->status;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->klien_model->count_all(),
            "recordsFiltered" => $this->klien_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    private function get_klien($id){
        $get_klien = $this->klien_model->get($id);
        if ($get_klien==FALSE){
            redirect(404);
        }else{
            return $get_klien;
        }
    }

    function ajax_search(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get = $this->input->get();

        $query = isset($get['q'])?$get['q']:NULL;
        $search_klien = $this->klien_model->search($query,75);

        if ($search_klien!=FALSE) {
            $data_response = array();
            foreach ($search_klien as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['id'];
                $data_response['results'][$k]['text']  = $v['nama'];
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }

    function index(){
        has_access('klien.view');

        $data['page_name']          = 'Klien';
        $data['page_sub_name']      = 'List klien';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'klien/klien_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('klien.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('klien');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Klien';
            $data['page_sub_name']      = 'Tambah Klien';

            $data['form_action']    = site_url('klien/create').'?url_return='.$url_return;
            $data['page']           = 'klien/klien_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'nama'  => $post['nama'],
                'status'  => $post['status'],
            );

            $insert = $this->klien_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($id=NULL){
        has_access('klien.edit');

        $klien = $this->get_klien($id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('klien');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'ID', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['klien']      = $klien;
            $data['request']            = $klien;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('klien/edit').'/'.$id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Klien";
            $data['page_sub_name']      = 'Edit Klien';
            $data['page']               = 'klien/klien_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'id'  => $post['id']==NULL?NULL:$post['id'],
                'nama'  => $post['nama'],
                'status'  => $post['status'],
            );


            $edit = $this->klien_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($id=NULL){
        has_access('klien.delete');

        $klien = $this->get_klien($id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('klien');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['klien']         = $klien;
            $data['request']            = $klien;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('klien/delete').'/'.$id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Klien";
            $data['page_sub_name']      = 'Hapus Klien';
            $data['page']               = 'klien/klien_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->klien_model->delete($id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$id,'Hapus','');
                $msg        = $klien['nama']." telah dihapus.";
                $url_return = site_url('klien');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('klien/delete/'.$id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}