<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_level extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_poin_level_model'
        ));

        $this->section_id = 2;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_poin_level_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['mpl_id']       = $item->mpl_id;
            $row['mpl_name']     = $item->mpl_name;
            $row['mpl_poin_min']   = parseThousand($item->mpl_poin_min);
            $row['mpl_poin_max']   = parseThousand($item->mpl_poin_max);
            $row['mpl_reward_saldo']   = parseThousand($item->mpl_reward_saldo);


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_poin_level_model->count_all(),
            "recordsFiltered" => $this->member_poin_level_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_member_poin_level($member_poin_level_id){
        $get_member_poin_level = $this->member_poin_level_model->get($member_poin_level_id);
        if ($get_member_poin_level==FALSE){
            redirect(404);
        }else{
            return $get_member_poin_level;
        }
    }

    function index(){
        has_access('configlevelpoin.view');

        $data['page_name']          = 'Level Poin';
        $data['page_sub_name']      = 'List Level Poin';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_poin_level/member_poin_level_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('configlevelpoin.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_poin_level');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('mpl_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Level Poin';
            $data['page_sub_name']      = 'Tambah Level';

            $data['form_action']    = site_url('member_poin_level/create').'?url_return='.$url_return;
            $data['page']           = 'member_poin_level/member_poin_level_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'mpl_name'  => $post['mpl_name'],
                'mpl_poin_min'  => $post['mpl_poin_min'],
                'mpl_poin_max'  => $post['mpl_poin_max'],
                'mpl_reward_saldo'  => $post['mpl_reward_saldo'],
            );

            $insert = $this->member_poin_level_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Level Poin');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($member_poin_level_id=NULL){
        has_access('configlevelpoin.edit');

        $member_poin_level = $this->get_member_poin_level($member_poin_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_poin_level');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mpl_id', 'ID', 'required');
        $this->form_validation->set_rules('mpl_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['member_poin_level']      = $member_poin_level;
            $data['request']            = $member_poin_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_poin_level/edit').'/'.$member_poin_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Poin";
            $data['page_sub_name']      = 'Edit Level';
            $data['page']               = 'member_poin_level/member_poin_level_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'mpl_id'  => $post['mpl_id']==NULL?NULL:$post['mpl_id'],
                'mpl_name'  => $post['mpl_name'],
                'mpl_poin_min'  => $post['mpl_poin_min'],
                'mpl_poin_max'  => $post['mpl_poin_max'],
                'mpl_reward_saldo'  => $post['mpl_reward_saldo'],
            );


            $edit = $this->member_poin_level_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_poin_level_id,'Edit','Level Poin');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($member_poin_level_id=NULL){
        has_access('configlevelpoin.delete');

        $member_poin_level = $this->get_member_poin_level($member_poin_level_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_poin_level');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mpl_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member_poin_level']         = $member_poin_level;
            $data['request']            = $member_poin_level;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_poin_level/delete').'/'.$member_poin_level_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Level Poin";
            $data['page_sub_name']      = 'Hapus Level';
            $data['page']               = 'member_poin_level/member_poin_level_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_poin_level_model->delete($member_poin_level_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$member_poin_level_id,'Hapus','Level Poin');
                $msg        = $member_poin_level['mpl_name']." telah dihapus.";
                $url_return = site_url('member_poin_level');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_poin_level/delete/'.$member_poin_level_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}