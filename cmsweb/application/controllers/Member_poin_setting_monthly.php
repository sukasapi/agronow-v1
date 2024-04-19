<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_setting_monthly extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_poin_setting_model',
            'member_poin_setting_monthly_model',
        ));
        $this->section_id = 2;
    }

    function get_member_poin_setting($mps_id){
        $get_mps = $this->member_poin_setting_model->get($mps_id);
        if ($get_mps==FALSE){
            redirect(404);
        }else{
            return $get_mps;
        }
    }

    function get_member_poin_setting_monthly($mps_monthly_id){
        $get_mps_monthly = $this->member_poin_setting_monthly_model->get($mps_monthly_id);
        if ($get_mps_monthly==FALSE){
            redirect(404);
        }else{
            return $get_mps_monthly;
        }
    }

    function create($mps_id){
        $mps = $this->get_member_poin_setting($mps_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mps_id', 'MPS ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['mps'] = $mps;
            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Poin';
            $data['page_sub_name']  = 'Tambah Rule Bulanan';

            $data['form_action']    = site_url('member_poin_setting_monthly/create/').$mps_id;
            $data['page']           = 'member_poin_setting_monthly/member_poin_setting_monthly_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data = array(
                'mps_id'     => $post['mps_id'],

                'mps_monthly_percent_min'       => $post['mps_monthly_percent_min'],
                'mps_monthly_percent_max'    => $post['mps_monthly_percent_max'],
                'mps_monthly_poin'    => $post['mps_monthly_poin'],
            );

            $insert = $this->member_poin_setting_monthly_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$mps_id,'Tambah','Config Poin Bulanan');
                $url_return = site_url('member_poin_setting/detail/').$post['mps_id'];
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($mps_monthly_id){
        $mps_monthly = $this->get_member_poin_setting_monthly($mps_monthly_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mps_monthly_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['mps_monthly']   = $mps_monthly;
            $data['request']        = $mps_monthly;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Poin';
            $data['page_sub_name']  = 'Edit';

            $data['form_action']    = site_url('member_poin_setting_monthly/edit/').$mps_monthly_id;
            $data['page']           = 'member_poin_setting_monthly/member_poin_setting_monthly_form_edit_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data = array(
                'mps_monthly_id' => $post['mps_monthly_id'],
                'mps_id'         => $post['mps_id'],

                'mps_monthly_percent_min'  => $post['mps_monthly_percent_min'],
                'mps_monthly_percent_max'  => $post['mps_monthly_percent_max'],
                'mps_monthly_poin'         => $post['mps_monthly_poin'],
            );

            $update = $this->member_poin_setting_monthly_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$mps_monthly_id,'Edit','Config Poin Bulanan');
                $url_return = site_url('member_poin_setting/detail/').$mps_monthly['mps_id'];
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function delete($mps_monthly_id=NULL){
        $mps_monthly = $this->get_member_poin_setting_monthly($mps_monthly_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_poin_setting_monthly');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mps_monthly_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['mps_monthly']         = $mps_monthly;
            $data['request']            = $mps_monthly;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_poin_setting_monthly/delete').'/'.$mps_monthly_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Config Poin";
            $data['page_sub_name']      = 'Hapus Config Poin Bulanan';
            $data['page']               = 'member_poin_setting_monthly/member_poin_setting_monthly_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_poin_setting_monthly_model->delete($mps_monthly_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$mps_monthly_id,'Hapus','Config Poin Bulanan');
                $msg        = $mps_monthly['mps_monthly_id']." telah dihapus.";
                $url_return = site_url('member_poin_setting/detail/'.$mps_monthly['mps_id']);
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_poin_setting/detail/'.$mps_monthly['mps_id']);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}