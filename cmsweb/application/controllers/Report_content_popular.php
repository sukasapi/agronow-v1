<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_content_popular extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'report_content_popular_model',
        ));

    }


    function index(){
        has_access('reportkontenpopuler.view');

        $limit = 10;
        $param_query['sort'] = 'content_hits';
        $param_query['sort_order'] = 'DESC';

        // News
        $param_query['filter_section'] = 12;
        $data['news'] = $this->report_content_popular_model->get_all(NULL,$limit,NULL,$param_query);

        // Article
        $param_query['filter_section'] = 13;
        $data['article'] = $this->report_content_popular_model->get_all(NULL,$limit,NULL,$param_query);

        // Knowledge Sharing
        $param_query['filter_section'] = 31;
        $data['knowledge_sharing'] = $this->report_content_popular_model->get_all(NULL,$limit,NULL,$param_query);


        //print_r($data['news']);

        $data['page_name']          = 'Laporan Konten Terbanyak Dilihat';
        $data['page_sub_name']      = 'List Konten';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'report_content_popular/report_content_popular_list_view';
        $this->load->view('main_view',$data);
    }


}