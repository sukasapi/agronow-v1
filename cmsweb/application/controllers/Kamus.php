<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kamus extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'kamus_model',
        ));

        $this->section_id = 38;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->kamus_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['kamus_id']              = $item->kamus_id;
            $row['kamus_name']  = $item->kamus_name;
            $row['kamus_desc']  = $item->kamus_desc;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kamus_model->count_all(),
            "recordsFiltered" => $this->kamus_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_kamus($kamus_id){
        $get_kamus = $this->kamus_model->get($kamus_id);
        if ($get_kamus==FALSE){
            redirect(404);
        }else{
            return $get_kamus;
        }
    }

    function index(){
        has_access('kamus.view');

        $data['page_name']          = 'Kamus';
        $data['page_sub_name']      = 'List kamus';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'kamus/kamus_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('kamus.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kamus');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('kamus_name', 'Kamus', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Kamus';
            $data['page_sub_name']      = 'Tambah Kamus';

            $data['form_action']    = site_url('kamus/create').'?url_return='.$url_return;
            $data['page']           = 'kamus/kamus_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'kamus_name'  => $post['kamus_name'],
                'kamus_desc'  => $post['kamus_desc'],
                'kamus_create_date'  => date("Y-m-d H:i:s"),
            );

            $insert = $this->kamus_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    // EDIT //
    function edit($kamus_id=NULL){
        has_access('kamus.edit');

        $kamus = $this->get_kamus($kamus_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kamus');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('kamus_id', 'ID', 'required');
        $this->form_validation->set_rules('kamus_name', 'Kamus', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['kamus']      = $kamus;
            $data['request']            = $kamus;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kamus/edit').'/'.$kamus_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kamus";
            $data['page_sub_name']      = 'Edit Kamus';
            $data['page']               = 'kamus/kamus_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'kamus_id'  => $post['kamus_id']==NULL?NULL:$post['kamus_id'],
                'kamus_name'  => $post['kamus_name'],
                'kamus_desc'  => $post['kamus_desc'],
            );


            $edit = $this->kamus_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$kamus_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($kamus_id=NULL){
        has_access('kamus.delete');

        $kamus = $this->get_kamus($kamus_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kamus');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['kamus']         = $kamus;
            $data['request']            = $kamus;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kamus/delete').'/'.$kamus_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kamus";
            $data['page_sub_name']      = 'Hapus Kamus';
            $data['page']               = 'kamus/kamus_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->kamus_model->delete($kamus_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$kamus_id,'Hapus','');
                $msg        = $kamus['kamus_name']." telah dihapus.";
                $url_return = site_url('kamus');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('kamus/delete/'.$kamus_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}