<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_poin_model',
        ));

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_poin_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['mp_id']           = $item->mp_id;
            $row['member_name']         = $item->member_name;
            $row['group_name']          = $item->group_name;
            $row['member_nip']          = $item->member_nip;
            $row['mp_section']        = $item->mp_section;
            $row['mp_poin']        = parseThousand($item->mp_poin);
            $row['mp_name']        = $item->mp_name;

            $row['content_name']= $item->content_name;
            $row['content_id']  = $item->content_id;

            $row['mp_create_date']  = $item->mp_create_date?parseDateShortReadable($item->mp_create_date):NULL;
            $row['mp_create_time']  = $item->mp_create_date?parseTimeReadable($item->mp_create_date):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_poin_model->count_all(),
            "recordsFiltered" => $this->member_poin_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function index(){
        has_access('memberpoin.view');

        $data['page_name']          = 'Riwayat Poin';
        $data['page_sub_name']      = 'List Poin Member';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_poin/member_poin_list_view';
        $this->load->view('main_view',$data);
    }

}