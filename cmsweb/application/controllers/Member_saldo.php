<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_saldo extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_saldo_model',
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_saldo_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['ms_id']       = $item->ms_id;
            $row['member_name'] = $item->member_name;
            $row['group_name']  = $item->group_name;
            $row['member_nip']  = $item->member_nip;
            $row['ms_type']     = $item->ms_type;
            $row['ms_saldo']    = parseThousand($item->ms_saldo);
            $row['ms_name']     = $item->ms_name;
            $row['ms_source']   = $item->ms_source;
            $row['cr_name']     = $item->cr_name;
            $row['cr_id']       = $item->cr_id;

            $row['ms_create_date']  = $item->ms_create_date?parseDateShortReadable($item->ms_create_date):NULL;
            $row['ms_create_time']  = $item->ms_create_date?parseTimeReadable($item->ms_create_date):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_saldo_model->count_all(),
            "recordsFiltered" => $this->member_saldo_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function index(){
        has_access('membersaldo.view');

        $data['page_name']          = 'Riwayat Saldo';
        $data['page_sub_name']      = 'List Saldo Member';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_saldo/member_saldo_list_view';
        $this->load->view('main_view',$data);
    }



}