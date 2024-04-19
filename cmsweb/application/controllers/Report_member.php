<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_member extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'report_member_model',
        ));

    }


    function index(){
        has_access('reportmember.view');

        $count_member = $this->report_member_model->count();
        foreach ($count_member as $v){
            $result[$v['month'].'/'.$v['year']] = $v['total_member'];
        }
        $data['member'] = $result;

        //print_r($data['member']);

        $data['page_name']          = 'Laporan Member';
        $data['page_sub_name']      = '';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'report_member/report_member_list_view';
        $this->load->view('main_view',$data);
    }


}