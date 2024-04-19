<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_ads extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'report_ads_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->report_ads_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['ads_id']              = $item->ads_id;
            $row['ads_create_date']     = $item->ads_create_date?parseDateShortReadable($item->ads_create_date):NULL;
            $row['ads_create_time']     = $item->ads_create_date?parseTimeReadable($item->ads_create_date):NULL;
            $row['ads_date_start']      = $item->ads_start?parseDateShortReadable($item->ads_start):NULL;
            $row['ads_time_start']      = $item->ads_start?parseTimeReadable($item->ads_start):NULL;
            $row['ads_date_end']        = $item->ads_end?parseDateShortReadable($item->ads_end):NULL;
            $row['ads_time_end']        = $item->ads_end?parseTimeReadable($item->ads_end):NULL;
            $row['ads_sponsor']         = $item->ads_sponsor;
            $row['ads_link']            = prep_url($item->ads_link);
            $row['ads_status']          = $item->ads_status;
            $row['ads_position']        = $item->ads_position;
            $row['ads_image']           = $item->ads_image;

            $row['total']    = $item->total?$item->total:0;
            $row['web']      = $item->web?$item->web:0;
            $row['android']  = $item->android?$item->android:0;
            $row['ios']      = $item->ios?$item->ios:0;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->report_ads_model->count_all(),
            "recordsFiltered" => $this->report_ads_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function index(){
        has_access('reportads.view');

        $data['page_name']          = 'Laporan Iklan';
        $data['page_sub_name']      = 'List Iklan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'report_ads/report_ads_list_view';
        $this->load->view('main_view',$data);
    }


}