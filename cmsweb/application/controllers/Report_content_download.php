<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_content_download extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'report_content_download_model',
        ));

    }


    function index(){

        has_access('reportkontendownload.view');

        $data['content_download'] = $this->report_content_download_model->count_content_download();

        //print_r($data['news']);

        $data['page_name']          = 'Laporan Konten Terbanyak Dilihat';
        $data['page_sub_name']      = 'List Konten';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'report_content_download/report_content_download_list_view';
        $this->load->view('main_view',$data);
    }


}