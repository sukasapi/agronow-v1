<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom_soal extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'classroom_soal_model',
            'category_model',
            'media_model',
            'group_model',
            'member_level_model',
            'user_model',
            'klien_model',
        ));

        $this->section_id = 30;
    }

    function l_modal_ajax(){
        $this->load->view('classroom_soal/classroom_soal_list_picker_modal_view');
    }

    function json(){

        $param_query = [];
        if (my_klien()){
            $param_query['filter_klien'] = my_klien();
        }

        $get_soal = $this->classroom_soal_model->get_all(NULL,NULL,NULL,$param_query);
        foreach ($get_soal['data'] as $k => $v){
            $result['data'][] = array(
                'crs_id'        => $v['crs_id'],
                'crs_question'  => $v['crs_question'],
                'cat_name'      => $v['cat_name'],
            );
        }
        echo json_encode($result);
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->classroom_soal_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['crs_id']           = $item->crs_id;
            $row['crs_create_date']  = $item->crs_create_date?parseDateShortReadable($item->crs_create_date):NULL;
            $row['crs_create_time']  = $item->crs_create_date?parseTimeReadable($item->crs_create_date):NULL;
            $row['crs_question']     = url2image($item->crs_question);
            $row['crs_right']        = $item->crs_right;
            $row['crs_answer1']     = $item->crs_answer1;
            $row['crs_answer2']     = $item->crs_answer2;
            $row['crs_answer3']     = $item->crs_answer3;
            $row['cat_name']         = $item->cat_name;
            $row['crs_status']       = $item->crs_status;
            $row['nama_klien']       = $item->nama_klien;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->classroom_soal_model->count_all(),
            "recordsFiltered" => $this->classroom_soal_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_classroom_soal($classroom_soal_id){
        $get_classroom_soal = $this->classroom_soal_model->get($classroom_soal_id);
        if ($get_classroom_soal==FALSE){
            redirect(404);
        }else{
            return $get_classroom_soal;
        }
    }

    function index(){
        has_access('classroomsoal.view');

        $data['form_opt_klien'][NULL] = NULL;

        $param_query = NULL;
        if (my_klien()){
            $param_query['filter_id'] = my_klien();
        }
        $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_klien!=FALSE){
            foreach ($get_klien['data'] as $k => $v) {
                $data['form_opt_klien'][$v['id']] = $v['nama'];
            }
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Bank Soal Class Room';
        $data['page_sub_name']      = 'List Bank Soal';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'classroom_soal/classroom_soal_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('classroomsoal.create');

        // Tidak Punya Klien
        if (!my_klien()){
            flash_notif_failed('Tidak dapat menambah data. Akun tidak memiliki Klien.',site_url('classroom_soal'));
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom_soal');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('crs_question', 'Question', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Soal Class Room';
            $data['page_sub_name']      = 'Tambah Soal';

            $data['form_action']    = site_url('classroom_soal/create').'?url_return='.$url_return;
            $data['page']           = 'classroom_soal/classroom_soal_form_create_view';

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
                'crs_created_by'    => $created_by,
                'crs_create_date'    => date("Y-m-d H:i:s"),
                'id_klien' => my_klien()
            );

            $insert = $this->classroom_soal_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Soal');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($classroom_soal_id=NULL){
        has_access('classroomsoal.edit');

        $classroom_soal = $this->get_classroom_soal($classroom_soal_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom_soal');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('crs_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($classroom_soal['cat_id'],$this->section_id);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }


            $data['classroom_soal']      = $classroom_soal;
            $data['request']            = $classroom_soal;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('classroom_soal/edit').'/'.$classroom_soal_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Soal Class Room";
            $data['page_sub_name']      = 'Edit Soal';
            $data['page']               = 'classroom_soal/classroom_soal_form_edit_view';
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
            );


            $edit = $this->classroom_soal_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$classroom_soal_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($classroom_soal_id=NULL){
        has_access('classroomsoal.delete');

        $classroom_soal = $this->get_classroom_soal($classroom_soal_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom_soal');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['classroom_soal']         = $classroom_soal;
            $data['request']            = $classroom_soal;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('classroom_soal/delete').'/'.$classroom_soal_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Soal Class Room";
            $data['page_sub_name']      = 'Hapus Soal';
            $data['page']               = 'classroom_soal/classroom_soal_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->classroom_soal_model->delete($classroom_soal_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$classroom_soal_id,'Hapus','Soal');
                $msg        = $classroom_soal['crs_id']." telah dihapus.";
                $url_return = site_url('classroom_soal');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('classroom_soal/delete/'.$classroom_soal_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }

    function sync_klien(){

        // Get All Classroom
        $clssroom_soals = $this->classroom_soal_model->get_all();

        foreach ($clssroom_soals['data'] as $k => $v){
            $classroom_soal_id = $v['crs_id'];
            $user_id = $v['crs_created_by'];

            // Get User
            $user = $this->user_model->get($user_id);
            if($user){
                $klien_id = $user['id_klien'];
                /*if(!$klien_id){
                    // Get Group
                    $group = $this->group_model->get($user['user_code']);
                    if($group){
                        $klien_id = $group['id_klien'];
                    }
                }*/
            }else{
                $klien_id = NULL;
            }


            // Update Classroom
            $data = array(
                'crs_id' => $classroom_soal_id,
                'id_klien' => $klien_id?$klien_id:NULL,
            );
            $update = $this->classroom_soal_model->update($data);

        }
    }

}