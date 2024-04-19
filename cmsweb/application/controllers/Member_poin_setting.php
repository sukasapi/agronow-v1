<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_setting extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_poin_setting_model',
            'member_poin_setting_monthly_model',
        ));
        $this->section_id = 2;
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_poin_setting_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['mps_id']           = $item->mps_id;
            $row['mps_start']  = $item->mps_start?parseDateShortReadable($item->mps_start):NULL;
            $row['mps_end']  = $item->mps_end?parseDateShortReadable($item->mps_end):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_poin_setting_model->count_all(),
            "recordsFiltered" => $this->member_poin_setting_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_member_poin_setting($mps_id){
        $get_mps = $this->member_poin_setting_model->get($mps_id);
        if ($get_mps==FALSE){
            redirect(404);
        }else{
            return $get_mps;
        }
    }

    function index(){
        has_access('configpoin.view');

        $data['page_name']          = 'Config Poin';
        $data['page_sub_name']      = 'List Config Poin';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_poin_setting/member_poin_setting_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('configpoin.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mps_start', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('mps_end', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Poin';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('member_poin_setting/create');
            $data['page']           = 'member_poin_setting/member_poin_setting_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data = array(
                'mps_start'     => parseDate($post['mps_start']),
                'mps_end'       => parseDate($post['mps_end']),
                'mps_daily'     => $post['mps_daily'],

                'mps_cr_join'       => $post['mps_cr_join'],
                'mps_cr_grade_a'    => $post['mps_cr_grade_a'],
                'mps_cr_grade_b'    => $post['mps_cr_grade_b'],
                'mps_cr_grade_c'    => $post['mps_cr_grade_c'],
                'mps_cr_grade_d'    => $post['mps_cr_grade_d'],

                'mps_cc_join'       => $post['mps_cc_join'],
                'mps_cc_grade_a'    => $post['mps_cc_grade_a'],
                'mps_cc_grade_b'    => $post['mps_cc_grade_b'],
                'mps_cc_grade_c'    => $post['mps_cc_grade_c'],
                'mps_cc_grade_d'    => $post['mps_cc_grade_d'],

                'mps_ks_approved'   => $post['mps_ks_approved'],
                'mps_ks_reject'     => $post['mps_ks_reject'],
                'mps_ks_liked'      => $post['mps_ks_liked'],

                'mps_create_date'   => date("Y-m-d H:i:s"),
                'mps_create_by'     => 1
            );

            $insert = $this->member_poin_setting_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Config Poin');
                $url_return = site_url('member_poin_setting/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function detail($mps_id=NULL){
        has_access('configpoin.view');

        $mps = $this->get_member_poin_setting($mps_id);

        $mps_monthly = $this->member_poin_setting_monthly_model->get_by_mps($mps_id);
        if (!$mps_monthly){
            $mps_monthly = array();
        }

        //print_r($mps_monthly);

        $data['mps']            = $mps;
        $data['mps_monthly']            = $mps_monthly;
        $data['page_name']      = 'Config Poin';
        $data['page_sub_name']  = '# '.$mps['mps_id'];
        $data['page'] = 'member_poin_setting/member_poin_setting_detail_view';
        $this->load->view('main_view',$data);
    }

    function edit($mps_id){
        has_access('configpoin.edit');

        $mps = $this->get_member_poin_setting($mps_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mps_start', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('mps_end', 'End Date', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['mps']            = $mps;
            $data['request']        = $mps;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Poin';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('member_poin_setting/edit/').$mps_id;
            $data['page']           = 'member_poin_setting/member_poin_setting_form_edit_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data = array(
                'mps_id'     => $post['mps_id'],
                'mps_start'     => parseDate($post['mps_start']),
                'mps_end'       => parseDate($post['mps_end']),
                'mps_daily'     => $post['mps_daily'],

                'mps_cr_join'       => $post['mps_cr_join'],
                'mps_cr_grade_a'    => $post['mps_cr_grade_a'],
                'mps_cr_grade_b'    => $post['mps_cr_grade_b'],
                'mps_cr_grade_c'    => $post['mps_cr_grade_c'],
                'mps_cr_grade_d'    => $post['mps_cr_grade_d'],

                'mps_cc_join'       => $post['mps_cc_join'],
                'mps_cc_grade_a'    => $post['mps_cc_grade_a'],
                'mps_cc_grade_b'    => $post['mps_cc_grade_b'],
                'mps_cc_grade_c'    => $post['mps_cc_grade_c'],
                'mps_cc_grade_d'    => $post['mps_cc_grade_d'],

                'mps_ks_approved'   => $post['mps_ks_approved'],
                'mps_ks_reject'     => $post['mps_ks_reject'],
                'mps_ks_liked'      => $post['mps_ks_liked'],

                'mps_create_date'   => date("Y-m-d H:i:s"),
                'mps_create_by'     => 1
            );

            $update = $this->member_poin_setting_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$mps_id,'Edit','Config Poin');
                $url_return = site_url('member_poin_setting/detail/').$mps['mps_id'];
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function delete($member_poin_setting_id=NULL){
        has_access('configpoin.delete');

        $member_poin_setting = $this->get_member_poin_setting($member_poin_setting_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_poin_setting');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mps_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member_poin_setting']         = $member_poin_setting;
            $data['request']            = $member_poin_setting;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_poin_setting/delete').'/'.$member_poin_setting_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Poin";
            $data['page_sub_name']      = 'Hapus Level';
            $data['page']               = 'member_poin_setting/member_poin_setting_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_poin_setting_model->delete($member_poin_setting_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$member_poin_setting_id,'Hapus','Config Poin');
                $msg        = $member_poin_setting['mps_id']." telah dihapus.";
                $url_return = site_url('member_poin_setting');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_poin_setting/delete/'.$member_poin_setting_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }
}