<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotes extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'quotes_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));
        $this->section_id = 20;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->quotes_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['quotes_id']              = $item->quotes_id;
            $row['quotes_text']  = $item->quotes_text;
            $row['quotes_author']  = $item->quotes_author;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->quotes_model->count_all(),
            "recordsFiltered" => $this->quotes_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_quotes($quotes_id){
        $get_quotes = $this->quotes_model->get($quotes_id);
        if ($get_quotes==FALSE){
            redirect(404);
        }else{
            return $get_quotes;
        }
    }

    function index(){
        has_access('quotes.view');

        $data['page_name']          = 'Quotes';
        $data['page_sub_name']      = 'List quotes';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'quotes/quotes_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('quotes.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('quotes');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('quotes_text', 'Quotes', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Quotes';
            $data['page_sub_name']      = 'Tambah Quotes';

            $data['form_action']    = site_url('quotes/create').'?url_return='.$url_return;
            $data['page']           = 'quotes/quotes_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'quotes_text'  => $post['quotes_text'],
                'quotes_author'  => $post['quotes_author'],
                'quotes_create_date'  => date("Y-m-d H:i:s"),
            );

            $insert = $this->quotes_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    // EDIT //
    function edit($quotes_id=NULL){
        has_access('quotes.edit');

        $quotes = $this->get_quotes($quotes_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('quotes');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('quotes_id', 'ID', 'required');
        $this->form_validation->set_rules('quotes_text', 'Quotes', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['quotes']      = $quotes;
            $data['request']            = $quotes;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('quotes/edit').'/'.$quotes_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Quotes";
            $data['page_sub_name']      = 'Edit Quotes';
            $data['page']               = 'quotes/quotes_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'quotes_id'  => $post['quotes_id']==NULL?NULL:$post['quotes_id'],
                'quotes_text'  => $post['quotes_text'],
                'quotes_author'  => $post['quotes_author'],
            );


            $edit = $this->quotes_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$quotes_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($quotes_id=NULL){
        has_access('quotes.delete');

        $quotes = $this->get_quotes($quotes_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('quotes');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['quotes']         = $quotes;
            $data['request']            = $quotes;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('quotes/delete').'/'.$quotes_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Quotes";
            $data['page_sub_name']      = 'Hapus Quotes';
            $data['page']               = 'quotes/quotes_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->quotes_model->delete($quotes_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$quotes_id,'Hapus','');
                $msg        = $quotes['quotes_text']." telah dihapus.";
                $url_return = site_url('quotes');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('quotes/delete/'.$quotes_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}