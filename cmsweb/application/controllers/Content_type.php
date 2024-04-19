<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_type extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'content_type_model'
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->content_type_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['content_type_id']       = $item->content_type_id;
            $row['content_type_name']     = $item->content_type_name;
            $row['content_type_status']   = $item->content_type_status;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->content_type_model->count_all(),
            "recordsFiltered" => $this->content_type_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_content_type($content_type_id){
        $get_content_type = $this->content_type_model->get($content_type_id);
        if ($get_content_type==FALSE){
            redirect(404);
        }else{
            return $get_content_type;
        }
    }

    function index(){
        $data['page_name']          = 'Jenis Konten';
        $data['page_sub_name']      = 'List Jenis Konten';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'content_type/content_type_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('content_type');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_type_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Jenis Konten';
            $data['page_sub_name']      = 'Tambah Jenis Konten';

            $data['form_action']    = site_url('content_type/create').'?url_return='.$url_return;
            $data['page']           = 'content_type/content_type_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'content_type_name'  => $post['content_type_name'],
                'content_type_status'  => $post['content_type_status'],
            );

            $insert = $this->content_type_model->insert($data);
            if ($insert==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($content_type_id=NULL){
        $content_type = $this->get_content_type($content_type_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('content_type');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_type_id', 'ID', 'required');
        $this->form_validation->set_rules('content_type_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['content_type']      = $content_type;
            $data['request']            = $content_type;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('content_type/edit').'/'.$content_type_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Jenis Konten";
            $data['page_sub_name']      = 'Edit Jenis Konten';
            $data['page']               = 'content_type/content_type_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'content_type_id'  => $post['content_type_id']==NULL?NULL:$post['content_type_id'],
                'content_type_name'  => $post['content_type_name'],
                'content_type_status'        => $post['content_type_status'],
            );


            $edit = $this->content_type_model->update($data);
            if ($edit==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($content_type_id=NULL){
        $content_type = $this->get_content_type($content_type_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('content_type');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_type_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['content_type']         = $content_type;
            $data['request']            = $content_type;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('content_type/delete').'/'.$content_type_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Jenis Konten";
            $data['page_sub_name']      = 'Hapus Jenis Konten';
            $data['page']               = 'content_type/content_type_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->content_type_model->delete($content_type_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                $msg        = $content_type['content_type_name']." telah dihapus.";
                $url_return = site_url('content_type');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('content_type/delete/'.$content_type_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}