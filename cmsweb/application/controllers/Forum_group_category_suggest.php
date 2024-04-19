<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_group_category_suggest extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'forum_group_category_suggest_model',
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->forum_group_category_suggest_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row['fs_id']    = $item->fs_id;
            $row['fs_name']  = $item->fs_name;
            $row['fs_create_date']     = $item->fs_create_date?parseDateShortReadable($item->fs_create_date):NULL;
            $row['fs_create_time']     = $item->fs_create_date?parseTimeReadable($item->fs_create_date):NULL;
            $row['member_name']  = $item->member_name;
            $row['group_name']  = $item->group_name;


            $data[] = $row;

        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->forum_group_category_suggest_model->count_all(),
            "recordsFiltered" => $this->forum_group_category_suggest_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    function index(){
        has_access('forumgroupcatsuggest.view');

        $data['page_name']          = 'Usulan Kategori Forum Group';
        $data['page_sub_name']      = 'List Usulan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'forum_group_category_suggest/forum_group_category_suggest_list_view';
        $this->load->view('main_view',$data);
    }


}