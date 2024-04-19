<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_ceo_notes extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'report_ceo_notes_model',
            'group_model'
        ));

    }


    function index(){
        //has_access('reportceonotes.view');

        // GET CEO NOTES
        $contents = $this->report_ceo_notes_model->get_content_by_section();
        $groups = $this->group_model->get_all();

        $counter = $this->report_ceo_notes_model->get_counter_by_content_group();
        $content_hits = array();
        foreach ($counter as $k => $v){
            $content_hits[$v['content_id'].$v['group_id']] = $v;
        }

        $data['content'] = $contents;
        $data['group'] = $groups['data'];
        $data['content_hits'] = $content_hits;

        $data['page_name']          = 'Laporan Aktivitas Baca CEO Notes';
        $data['page_sub_name']      = 'List Total Baca Konten CEO Notes';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'report_ceo_notes/report_ceo_notes_list_view';
        $this->load->view('main_view',$data);
    }


}