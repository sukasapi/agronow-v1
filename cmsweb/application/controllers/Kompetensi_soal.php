<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_soal extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'kompetensi_soal_model',
            'category_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));

        $this->section_id = 43;
    }

    function l_modal_ajax(){
        $this->load->view('kompetensi_soal/kompetensi_soal_list_picker_modal_view');
    }

    function json(){
        $get_soal = $this->kompetensi_soal_model->get_all();
        foreach ($get_soal['data'] as $k => $v){
            $result['data'][] = array(
                'crs_id'        => $v['crs_id'],
                'crs_question'  => $v['crs_question'],
                'cat_name'      => $v['cat_name']
            );
        }
        echo json_encode($result);
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->kompetensi_soal_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['crs_id']           = $item->crs_id;
            $row['crs_create_date']  = $item->crs_create_date?parseDateShortReadable($item->crs_create_date):NULL;
            $row['crs_create_time']  = $item->crs_create_date?parseTimeReadable($item->crs_create_date):NULL;
            $row['crs_question']     = $item->crs_question;
            $row['crs_right']        = $item->crs_right;
            $row['crs_answer1']     = $item->crs_answer1;
            $row['crs_answer2']     = $item->crs_answer2;
            $row['crs_answer3']     = $item->crs_answer3;
            $row['cat_name']         = $item->cat_name;
            $row['crs_status']       = $item->crs_status;

            $row['crs_level']        = $item->crs_level;
            $row['crs_durasi_detik'] = $item->crs_durasi_detik?gmdate('H:i:s', $item->crs_durasi_detik):'';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kompetensi_soal_model->count_all(),
            "recordsFiltered" => $this->kompetensi_soal_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_kompetensi_soal($kompetensi_soal_id){
        $get_kompetensi_soal = $this->kompetensi_soal_model->get($kompetensi_soal_id);
        if ($get_kompetensi_soal==FALSE){
            redirect(404);
        }else{
            return $get_kompetensi_soal;
        }
    }

    function index(){
        has_access('kompetensisoal.view');

        $category_ids = $this->input->get('category_ids');
        $data['form_opt_category'] = NULL;
        if (!isset($category_ids)){
            $data['form_opt_category'] = NULL;
        }else{
            $get_category = $this->category_model->gets($category_ids);
            if ($get_category!=FALSE){
                foreach ($get_category as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Bank Soal Kompetensi';
        $data['page_sub_name']      = 'List Bank Soal';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'kompetensi_soal/kompetensi_soal_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('kompetensisoal.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi_soal');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('crs_question', 'Question', 'required|trim');
        $this->form_validation->set_rules('crs_durasi_detik', 'Durasi', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Soal Kompetensi';
            $data['page_sub_name']      = 'Tambah Soal';

            $data['form_action']    = site_url('kompetensi_soal/create').'?url_return='.$url_return;
            $data['page']           = 'kompetensi_soal/kompetensi_soal_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = 1;
            $data = array(
                'crs_question'  => $post['crs_question'],
                'cat_id'        => $post['cat_id'],
                'crs_right'     => $post['crs_right'],
                'crs_answer1'   => $post['crs_answer1'],
                'crs_answer2'   => $post['crs_answer2'],
                'crs_answer3'   => $post['crs_answer3'],
                'crs_status'    => $post['crs_status'],
                'crs_level'    => $post['crs_level'],
                'crs_durasi_detik'    => $post['crs_durasi_detik'],
                'crs_created_by'    => $created_by,
                'crs_create_date'    => date("Y-m-d H:i:s"),
            );

            $insert = $this->kompetensi_soal_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Soal');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    function edit($kompetensi_soal_id=NULL){
        has_access('kompetensisoal.edit');

        $kompetensi_soal = $this->get_kompetensi_soal($kompetensi_soal_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi_soal');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('crs_id', 'ID', 'required');
        $this->form_validation->set_rules('crs_durasi_detik', 'Durasi', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($kompetensi_soal['cat_id'],$this->section_id);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }


            $data['kompetensi_soal']      = $kompetensi_soal;
            $data['request']            = $kompetensi_soal;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kompetensi_soal/edit').'/'.$kompetensi_soal_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Soal Kompetensi";
            $data['page_sub_name']      = 'Edit Soal';
            $data['page']               = 'kompetensi_soal/kompetensi_soal_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'crs_id'     => $post['crs_id'],
                'crs_question'  => $post['crs_question'],
                'cat_id'        => $post['cat_id'],
                'crs_right'     => $post['crs_right'],
                'crs_answer1'   => $post['crs_answer1'],
                'crs_answer2'   => $post['crs_answer2'],
                'crs_answer3'   => $post['crs_answer3'],
                'crs_status'    => $post['crs_status'],
                'crs_level'    => $post['crs_level'],
                'crs_durasi_detik'    => $post['crs_durasi_detik'],
            );


            $edit = $this->kompetensi_soal_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$kompetensi_soal_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function delete($kompetensi_soal_id=NULL){
        has_access('kompetensisoal.delete');

        $kompetensi_soal = $this->get_kompetensi_soal($kompetensi_soal_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi_soal');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['kompetensi_soal']         = $kompetensi_soal;
            $data['request']            = $kompetensi_soal;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kompetensi_soal/delete').'/'.$kompetensi_soal_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Soal Kompetensi";
            $data['page_sub_name']      = 'Hapus Soal';
            $data['page']               = 'kompetensi_soal/kompetensi_soal_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->kompetensi_soal_model->delete($kompetensi_soal_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$kompetensi_soal_id,'Hapus','Soal');
                $msg        = $kompetensi_soal['crs_id']." telah dihapus.";
                $url_return = site_url('kompetensi_soal');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('kompetensi_soal/delete/'.$kompetensi_soal_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}