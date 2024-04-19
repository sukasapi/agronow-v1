<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'inbox_model',
            'member_model',
            'user_model',
        ));

        $this->section_id = 41;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->inbox_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['inbox_id']              = $item->inbox_id;
            $row['inbox_create_date']     = $item->inbox_create_date?parseDateShortReadable($item->inbox_create_date):NULL;
            $row['inbox_create_time']     = $item->inbox_create_date?parseTimeReadable($item->inbox_create_date):NULL;
            $row['inbox_update_date']     = $item->inbox_update_date?parseDateShortReadable($item->inbox_update_date):NULL;
            $row['inbox_update_time']     = $item->inbox_update_date?parseTimeReadable($item->inbox_update_date):NULL;
            $row['inbox_title']           = $item->inbox_title;
            $row['inbox_desc']            = $item->inbox_desc;
            $row['inbox_read_member']     = $item->inbox_read_member;
            $row['inbox_read_admin']      = $item->inbox_read_admin;
            $row['total_message']         = $item->total_message;

            if ($item->inbox_from == 'member'){
                $get_member = $this->member_model->get($item->inbox_from_id);
                $row['sender_name']           = isset($get_member['member_name'])?$get_member['member_name']:'';
                $row['sender_group_name']     = isset($get_member['group_name'])?$get_member['group_name']:'';
            }else {
                $get_user = $this->user_model->get($item->inbox_from_id);
                $row['sender_name']           = isset($get_user['user_name'])?$get_user['user_name']:'';
                $row['sender_group_name']     = isset($get_user['group_name'])?$get_user['group_name']:'';
            }


            $row['picture']               = "";


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->inbox_model->count_all(),
            "recordsFiltered" => $this->inbox_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_inbox($inbox_id){
        $get_inbox = $this->inbox_model->get($inbox_id);
        if ($get_inbox==FALSE){
            redirect(404);
        }else{
            return $get_inbox;
        }
    }

    function get_chat($inbox_id){
        $get_inbox = $this->inbox_model->gets($inbox_id);
        if ($get_inbox==FALSE){
            redirect(404);
        }else{
            return $get_inbox;
        }
    }

    function index(){
        has_access('inbox.view');

        $data['page_name']          = 'Inbox';
        $data['page_sub_name']      = 'List Inbox';
        $data['is_inbox_header']  = TRUE;
        $data['page']               = 'inbox/inbox_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($inbox_id=NULL){
        has_access('inbox.view');

        $chat = $this->get_chat($inbox_id);
        foreach ($chat as $k=>$item) {
            if ($item['inbox_from'] == 'member'){
                $get_member = $this->member_model->get($item['inbox_from_id']);
                $chat[$k]['sender_name']           = isset($get_member['member_name'])?$get_member['member_name']:'';
                $chat[$k]['sender_group_name']     = isset($get_member['group_name'])?$get_member['group_name']:'';
            }else {
                $get_user = $this->user_model->get($item['inbox_from_id']);
                $chat[$k]['sender_name']           = isset($get_user['user_name'])?$get_user['user_name']:'';
                $chat[$k]['sender_group_name']     = isset($get_user['group_name'])?$get_user['group_name']:'';
            }
        }

        //print_r($chat);

        $data['chat']        = $chat;
        $data['page_name']      = 'Inbox';
        $data['page_sub_name']  = 'Detail';
        $data['page'] = 'inbox/inbox_detail_view';
        $this->load->view('main_view',$data);
    }

    function chat_log($inbox_id){
        has_access('inbox.view');

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $chat = $this->get_chat($inbox_id);
        foreach ($chat as $k=>$item) {
            if ($item['inbox_from'] == 'member'){
                $get_member = $this->member_model->get($item['inbox_from_id']);
                $chat[$k]['sender_name']           = isset($get_member['member_name'])?$get_member['member_name']:'';
                $chat[$k]['sender_group_name']     = isset($get_member['group_name'])?$get_member['group_name']:'';
            }else {
                $get_user = $this->user_model->get($item['inbox_from_id']);
                $chat[$k]['sender_name']           = isset($get_user['user_name'])?$get_user['user_name']:'';
                $chat[$k]['sender_group_name']     = isset($get_user['group_name'])?$get_user['group_name']:'';
            }
        }

        $data['chat']        = $chat;
        $data['inbox']      = ['inbox_id'=>$inbox_id];
        $this->load->view('inbox/inbox_ajax_chat_log_view',$data);

    }

    function reply($inbox_id){
        has_access('inbox.reply');

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get_inbox = $this->inbox_model->gets($inbox_id);
        if ($get_inbox==FALSE){
            return FALSE;
        }

        $post = $this->input->post();

        $data = array(
            'parent_id'     => $inbox_id,
            'inbox_from'    => 'admin',
            'inbox_from_id' => 1,
            'inbox_title'   => $get_inbox[0]['inbox_title'],
            'inbox_desc'    => $post['message'],
            'inbox_create_date'   => date("Y-m-d H:i:s"),
            'inbox_update_date'   => date("Y-m-d H:i:s"),
        );


        $insert = $this->inbox_model->insert($data);
        if ($insert==TRUE) {
            create_log($this->section_id,$insert,'Tambah',NULL);

            return TRUE;
        }else{
            return FALSE;
        }

    }

    function remove_message($inbox_id,$redirect_id){
        has_access('inbox.delete');

        $delete = $this->inbox_model->delete($inbox_id);
        if ($delete==TRUE) {
            create_log($this->section_id,$inbox_id,'Hapus','');
            flash_notif_success('Pesan berhasil dihapus',site_url('inbox/detail/').$redirect_id);
        }else{
            flash_notif_warning('Pesan gagal dihapus',site_url('inbox/detail/').$redirect_id);
        }
    }




}