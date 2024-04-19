<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_poin_dashboard_model',
            'member_poin_level_model',
            'group_model',
        ));

        $this->section_id = 9;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_poin_dashboard_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $current_level = '';
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['member_id']   = $item->member_id;
            $row['member_name'] = $item->member_name;
            $row['member_nip']  = $item->member_nip;
            $row['group_name']  = $item->group_name;

            $poin_level = $this->member_poin_level_model->get_all();

            foreach ($poin_level['data'] as $v){
                if ($item->poin >= $v['mpl_poin_min']){
                    $current_level = $v['mpl_name'];
                }
            }

            $row['level']   = $current_level;
            $current_level = '';

            $row['poin']    = $item->poin;
            $row['saldo']   = $item->saldo;
            $row['year']    = $item->year;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_poin_dashboard_model->count_all(),
            "recordsFiltered" => $this->member_poin_dashboard_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    function index(){
        has_access('memberpoinsaldo.view');

        $group_ids = $this->input->get('group_ids');
        $data['form_opt_group'] = NULL;
        if (!isset($group_ids)){
            $data['form_opt_group'] = NULL;
        }else{
            $get_group = $this->group_model->gets($group_ids);
            if ($get_group!=FALSE){
                foreach ($get_group as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }
        }



        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Member Poin Dashboard';
        $data['page_sub_name']      = 'List Member Poin & Saldo';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_poin_dashboard/member_poin_dashboard_list_view';
        $this->load->view('main_view',$data);
    }


}