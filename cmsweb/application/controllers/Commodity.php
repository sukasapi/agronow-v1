<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commodity extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();

        $this->load->model(array(
            'commodity_model'
        ));
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->commodity_model->get_last();
        $data = array();
        foreach ($list as $k => $item) {
            $row = array();
            $row['commodity']    = $k;
            $row['price']    = $item[0];
            $row['percent']  = isset($item[2])?$item[2]:NULL;
            $row['unit']  = $item[1];


            $data[] = $row;
        }

        $output = array(
            "recordsTotal" => sizeof($list),
            "recordsFiltered" => sizeof($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_kurs($kurs_id){
        $get_kurs = $this->kurs_model->get($kurs_id);
        if ($get_kurs==FALSE){
            redirect(404);
        }else{
            return $get_kurs;
        }
    }

    function index(){
        has_access('commodity.view');

        $data['page_name']          = 'Commodity';
        $data['page_sub_name']      = 'List Commodity';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'commodity/commodity_list_view';
        $this->load->view('main_view',$data);
    }

    function get_data_cron(){
        file_get_contents("https://agronow.co.id/cronAction/getData");
        flash_notif_success('Data berhasil disimpan',site_url('commodity'));
    }


}