<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expert extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'expert_model',
            'expert_chat_model',
            'category_model',
            'media_model',
            'group_model',
            'member_level_model',
            'klien_model',
        ));
        $select_tree = [];
        $this->section_id = 37;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->expert_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['expert_id']              = $item->expert_id;
            $row['expert_create_date']     = $item->expert_create_date?parseDateShortReadable($item->expert_create_date):NULL;
            $row['expert_create_time']     = $item->expert_create_date?parseTimeReadable($item->expert_create_date):NULL;
            $row['expert_update_date']    = $item->expert_update_date?parseDateShortReadable($item->expert_update_date):NULL;
            $row['expert_update_time']    = $item->expert_update_date?parseTimeReadable($item->expert_update_date):NULL;
            $row['expert_name']            = $item->expert_name;
            $row['member_name']            = $item->member_name;
            $row['group_name']            = $item->group_name;

            $row['em_name']            = $item->em_name;
            $row['em_concern']            = $item->em_concern;

            $user_count  = $this->expert_chat_model->count_member_by_expert($item->expert_id);
            $row['user_count']       = $user_count?$user_count['total']:0;

            $chat_count  = $this->expert_chat_model->count_by_expert($item->expert_id);
            $row['chat_count']    = $chat_count?$chat_count['total']:0;

            $row['nama_klien'] = getKlienByMember($item->member_id, 'render_nama_klien');

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->expert_model->count_all(),
            "recordsFiltered" => $this->expert_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_expert($expert_id){
        $get_expert = $this->expert_model->get($expert_id);
        if ($get_expert==FALSE){
            redirect(404);
        }else{
            return $get_expert;
        }
    }

    function get_expert_chat($expert_chat_id){
        $get_expert_chat = $this->expert_chat_model->get($expert_chat_id);
        if ($get_expert_chat==FALSE){
            redirect(404);
        }else{
            return $get_expert_chat;
        }
    }

    function index(){
        has_access('expertdirectory.view');

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

        $data['page_name']          = 'Expert';
        $data['page_sub_name']      = 'List Expert';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'expert/expert_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('expertdirectory.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('expert_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Expert';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('expert/create');
            $data['page']           = 'expert/expert_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'user_id'       => 0,
                'member_id'     => 0,
                'expert_name'    => $post['expert_name'],
                'expert_alias'   => slugify($post['expert_name']),
                'expert_desc'    => $post['expert_desc'],
                'expert_sticky'  => $post['expert_sticky'],
                'cat_id'        => $post['cat_id'],
                'expert_status'  => "open",
                'expert_create_date'   => date("Y-m-d H:i:s"),
            );

            //print_r($data);

            $insert = $this->expert_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('expert/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($expert_id){
        has_access('expertdirectory.edit');

        $expert = $this->get_expert($expert_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert/detail/'.$expert_id);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('expert_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($expert['cat_id']);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }

            $data['request']            = $expert;
            $data['expert']            = $expert;

            $data['url_return']     = $url_return;
            $data['editable']       = TRUE;

            $data['page_name']      = 'Expert';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('expert/edit/'.$expert_id);
            $data['page']           = 'expert/expert_form_edit_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'expert_id'    => $post['expert_id'],
                'expert_name'    => $post['expert_name'],
                'expert_alias'   => slugify($post['expert_name']),
                'expert_desc'    => $post['expert_desc'],
                'expert_sticky'  => $post['expert_sticky'],
                'cat_id'        => $post['cat_id'],
                'expert_update_date'   => date("Y-m-d H:i:s"),
            );

            //print_r($data);

            $update = $this->expert_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$expert_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function detail($expert_id=NULL){
        has_access('expertdirectory.view');

        $expert = $this->get_expert($expert_id);

        $data['member']         = $this->expert_chat_model->get_member_by_expert($expert_id);
        $data['member_count']   = $this->expert_chat_model->count_member_by_expert($expert_id);

        $data['chat']         = $this->expert_chat_model->get_by_expert($expert_id);
        $data['chat_count']   = $this->expert_chat_model->count_by_expert($expert_id);

        $data['expert']          = $expert;
        $data['page_name']      = 'Expert';
        $data['page_sub_name']  = $expert['expert_name'];
        $data['page'] = 'expert/expert_detail_view';
        $this->load->view('main_view',$data);
    }

    function delete($expert_id=NULL){
        has_access('expertdirectory.delete');

        $expert = $this->get_expert($expert_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('expert_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['expert']         = $expert;
            $data['request']            = $expert;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert/delete').'/'.$expert_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Expert";
            $data['page_sub_name']      = 'Hapus Expert';
            $data['page']               = 'expert/expert_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->expert_model->delete($expert_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$expert_id,'Hapus',NULL);
                $msg        = $expert['expert_name']." telah dihapus.";
                $url_return = site_url('expert');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('expert/delete/'.$expert_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }


    function remove_chat($expert_chat_id){
        has_access('expertdirectory.delete');

        $expert_chat = $this->get_expert_chat($expert_chat_id);
        $expert_id = $expert_chat['expert_id'];

        $delete = $this->expert_chat_model->delete($expert_chat_id,TRUE);
        if ($delete==TRUE) {
            $msg        = "1 pesan telah dihapus.";
            $url_return = site_url('expert/detail/'.$expert_id);
            flash_notif_warning($msg,$url_return);
        }else{
            $msg        = "Pesan gagal dihapus.";
            $url_return = site_url('expert/detail/'.$expert_id);
            flash_notif_failed($msg,$url_return);
        }
    }



}