<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'media_model'
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->media_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['media_id']           = $item->media_id;
            $row['section_id']         = $item->section_id;
            $row['data_id']          = $item->data_id;
            $row['media_name']         = $item->media_name;
            $row['media_value']          = $item->media_value;
            $row['media_type']        = $item->media_type;
            $row['media_primary']        = $item->media_primary;
            $row['media_status']        = $item->media_status;
            $row['media_size']        = $item->media_size;

            $row['media_create_date']  = $item->media_create_date?parseDateShortReadable($item->media_create_date):NULL;
            $row['media_create_time']  = $item->media_create_date?parseTimeReadable($item->media_create_date):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->media_model->count_all(),
            "recordsFiltered" => $this->media_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function index(){
        $data['page_name']          = 'Media';
        $data['page_sub_name']      = 'List Media';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'media/media_list_view';
        $this->load->view('main_view',$data);
    }


}