<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_activity extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'user_activity_model',

            'user_activity_read_ceo_notes_model',
            'user_activity_read_bod_share_model',
            'group_model'
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->user_activity_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['user_activity_id']       = $item->user_activity_id;

            $row['user_activity_create_date']       = parseDateShortReadable($item->user_activity_create_date).'<br>'.parseTimeReadable($item->user_activity_create_date);
            $row['user_name']       = $item->user_name;
            $row['user_activity_type']       = $item->user_activity_type;
            //$data_id = $item->data_id?'<br><small>Data ID: '.$item->data_id.'</small>':'';
            $row['section_name']       = $item->section_name;
            $row['data_id']       = $item->data_id?$item->data_id:'';
            $row['user_activity_desc']       = $item->user_activity_desc;
            $row['ip_address']       = $item->ip_address;



            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user_activity_model->count_all(),
            "recordsFiltered" => $this->user_activity_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_user_activity($user_activity_id){
        $get_user_activity = $this->user_activity_model->get($user_activity_id);
        if ($get_user_activity==FALSE){
            redirect(404);
        }else{
            return $get_user_activity;
        }
    }

    function index(){
        has_access('useractivity.view');

        $data['page_name']          = 'Administrator';
        $data['page_sub_name']      = 'Log Aktivitas';
        $data['is_content_header']  = TRUE;
        $data['submenu']            = 'user_activity/user_activity_submenu_view';
        $data['page']               = 'user_activity/user_activity_list_view';
        $this->load->view('main_view',$data);
    }


    function read_ceo_notes(){
        has_access('useractivity.view');

        // GET CEO NOTES
        $contents = $this->user_activity_read_ceo_notes_model->get_content_by_section();
        $groups = $this->group_model->get_all();

        $counter = $this->user_activity_read_ceo_notes_model->get_counter_by_content_group();
        $content_hits = array();
        foreach ($counter as $k => $v){
            $content_hits[$v['content_id'].$v['group_id']] = $v;
        }

        $data['content'] = $contents;
        $data['group'] = $groups['data'];
        $data['content_hits'] = $content_hits;

        // Start JSON Datatable
        $table_json = array();
        foreach ($contents as $k => $v){

            foreach ($groups['data'] as $i => $j){
                $index = $v['content_id'].$j['group_id'];
                if (isset($content_hits[$index])){
                    $total_view =  $content_hits[$index]['total_view'];
                }else{
                    $total_view = "0";
                }

                $content_name = "<a href='".site_url('ceo_notes/detail/').$v['content_id']."' target='_blank' >". $v['content_name']."</a><br><small>Penulis: ".$v['content_author']."</small>";
                $table_json[] = [$k+1, $content_name, $j['group_name'], $total_view];
            }

        }
        $data['table_json'] = json_encode($table_json);
        // End JSON Datatable

        $data['page_name']          = 'Aktivitas Baca CEO Notes';
        $data['page_sub_name']      = 'List Total Baca Konten CEO Notes';
        $data['is_content_header']  = TRUE;
        $data['submenu']            = 'user_activity/user_activity_submenu_view';
        $data['page']               = 'user_activity/user_activity_read_ceo_notes_list_view';
        $this->load->view('main_view',$data);
    }


    function read_bod_share(){
        has_access('useractivity.view');

        // GET BOD SHARE
        $contents = $this->user_activity_read_bod_share_model->get_content_by_section();
        $groups = $this->group_model->get_all();

        $counter = $this->user_activity_read_bod_share_model->get_counter_by_content_group();
        $content_hits = array();
        foreach ($counter as $k => $v){
            $content_hits[$v['content_id'].$v['group_id']] = $v;
        }

        $data['content'] = $contents;
        $data['group'] = $groups['data'];
        $data['content_hits'] = $content_hits;

        // Start JSON Datatable
        $table_json = array();
        foreach ($contents as $k => $v){

            foreach ($groups['data'] as $i => $j){
                $index = $v['content_id'].$j['group_id'];
                if (isset($content_hits[$index])){
                    $total_view =  $content_hits[$index]['total_view'];
                }else{
                    $total_view = "0";
                }

                $content_name = "<a href='".site_url('bod_share/detail/').$v['content_id']."' target='_blank' >". $v['content_name']."</a><br><small>Penulis: ".$v['content_author']."</small>";
                $table_json[] = [$k+1, $content_name, $j['group_name'], $total_view];
            }

        }
        $data['table_json'] = json_encode($table_json);
        // End JSON Datatable

        $data['page_name']          = 'Aktivitas Baca BOD Share';
        $data['page_sub_name']      = 'List Total Baca Konten BOD Share';
        $data['is_content_header']  = TRUE;
        $data['submenu']            = 'user_activity/user_activity_submenu_view';
        $data['page']               = 'user_activity/user_activity_read_bod_share_list_view';
        $this->load->view('main_view',$data);
    }





}