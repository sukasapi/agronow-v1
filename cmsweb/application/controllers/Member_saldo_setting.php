<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_saldo_setting extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_saldo_setting_model',
            'member_saldo_model',
            'group_model',
            'member_model'
        ));
        $this->section_id = 2;
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->member_saldo_setting_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['mss_id']      = $item->mss_id;
            $row['group_id']    = $item->group_id;
            $row['group_name']  = $item->group_name;
            $row['mss_saldo']   = parseThousand($item->mss_saldo);
            $row['mss_start']   = parseDateShortReadable($item->mss_start);
            $row['mss_end']     = parseDateShortReadable($item->mss_end);

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member_saldo_setting_model->count_all(),
            "recordsFiltered" => $this->member_saldo_setting_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_member_saldo_setting($member_saldo_setting_id){
        $get_member_saldo_setting = $this->member_saldo_setting_model->get($member_saldo_setting_id);
        if ($get_member_saldo_setting==FALSE){
            redirect(404);
        }else{
            return $get_member_saldo_setting;
        }
    }

    function index(){
        has_access('configsaldo.view');

        $data['page_name']          = 'Config Saldo';
        $data['page_sub_name']      = 'List Config Saldo';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'member_saldo_setting/member_saldo_setting_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('configsaldo.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_id', 'Group', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Saldo';
            $data['page_sub_name']  = 'Tambah Config Saldo';

            $data['form_action']    = site_url('member_saldo_setting/create').'?url_return='.$url_return;
            $data['page']           = 'member_saldo_setting/member_saldo_setting_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'mss_saldo'       => $post['mss_saldo'],
                'mss_start'       => parseDate($post['mss_start']),
                'mss_end'         => parseDate($post['mss_end']),
                'group_id'        => $post['group_id'],
                'mss_create_date' => date('Y-m-d H:i:s'),
            );

            $insert = $this->member_saldo_setting_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah','Config Saldo');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }
        }
    }

    function edit($member_saldo_setting_id=NULL){
        has_access('configsaldo.edit');

        $member_saldo_setting = $this->get_member_saldo_setting($member_saldo_setting_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mss_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['mss']  = $member_saldo_setting;
            $data['request']            = $member_saldo_setting;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_saldo_setting/edit').'/'.$member_saldo_setting_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Config Saldo";
            $data['page_sub_name']      = 'Edit Config Saldo';
            $data['page']               = 'member_saldo_setting/member_saldo_setting_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'mss_id'          => $post['mss_id']==NULL?NULL:$post['mss_id'],
                'mss_saldo'       => $post['mss_saldo'],
                'mss_start'       => parseDate($post['mss_start']),
                'mss_end'         => parseDate($post['mss_end']),
                'group_id'        => $post['group_id'],
                'mss_create_date' => date('Y-m-d H:i:s'),
            );

            $edit = $this->member_saldo_setting_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$member_saldo_setting_id,'Edit','Config Saldo');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }
        }
    }

    function delete($member_saldo_setting_id=NULL){
        has_access('configsaldo.delete');

        $member_saldo_setting = $this->get_member_saldo_setting($member_saldo_setting_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('mss_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['member_saldo_setting']  = $member_saldo_setting;
            $data['request']            = $member_saldo_setting;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('member_saldo_setting/delete').'/'.$member_saldo_setting_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Config Saldo";
            $data['page_sub_name']      = 'Hapus';
            $data['page']               = 'member_saldo_setting/member_saldo_setting_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->member_saldo_setting_model->delete($member_saldo_setting_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$member_saldo_setting_id,'Hapus','Config Saldo');
                $msg        = $member_saldo_setting['mss_name']." telah dihapus.";
                $url_return = site_url('member_saldo_setting');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('member_saldo_setting/delete/'.$member_saldo_setting_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }




    // TOP UP SALDO

    function ajax_get_config_saldo_group($group_id){
        $saldo = 0;
        $get_config = $this->member_saldo_setting_model->get_by_group($group_id);
        if ($get_config){
            $saldo = $get_config[0]['mss_saldo'];
        }
        echo $saldo;
    }

    function ajax_get_config_saldo_member($member_id){
        $member = $this->member_model->get($member_id);
        $saldo = 0;
        $get_config = $this->member_saldo_setting_model->get_by_group($member['group_id']);
        if ($get_config){
            $saldo = $get_config[0]['mss_saldo'];
        }
        echo $saldo;
    }

    function topup_group(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_id', 'Group', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_source'] = array(
                NULL   => '-',
                'Company'  => 'Perusahaan',
                'Reward'   => 'Reward',
            );

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Saldo';
            $data['page_sub_name']  = 'Top Up Saldo Group';

            $data['form_action']    = site_url('member_saldo_setting/topup_group').'?url_return='.$url_return;
            $data['page']           = 'member_saldo_setting/member_saldo_setting_form_topup_group_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $source     = $post['ms_source'];
            $name       = 'Top Up : '.$post['ms_source'];
            $group_id   = $post['group_id'];
            $saldo      = $post['ms_saldo'];

            $get_member = $this->member_model->get_by_group($group_id);
            $data = NULL;
            $count_member = 0;

            if ($saldo > 0){
                if (!$get_member){
                    flash_notif_failed(NULL,$url_return,FALSE);
                }else{
                    foreach ($get_member as $k => $v){

                        if ($source=='Reward'){

                            $year = date('Y');
                            $check_reward_given = $this->member_saldo_model->check_reward_given($v['member_id'],$year);
                            if ($check_reward_given==FALSE){
                                $count_member ++;
                                $data[] = array(
                                    'ms_source' => $source,
                                    'ms_name'   => $name,
                                    'ms_saldo'  => $saldo,
                                    'member_id' => $v['member_id'],
                                    'ms_type'   => 'IN'
                                );
                            }

                        }else if ($source=='Company'){
                            $count_member ++;
                            $data[] = array(
                                'ms_source' => $source,
                                'ms_name'   => $name,
                                'ms_saldo'  => $saldo,
                                'member_id' => $v['member_id'],
                                'ms_type'   => 'IN'
                            );
                        }

                    }

                    if ($data){
                        $this->member_saldo_model->insert_batch($data);
                        $this->member_saldo_model->member_saldo_sync();
                    }

                    create_log($this->section_id,"",'Topup Group','Config Saldo');
                    $msg = 'Top Up Saldo '.$source.' kepada '.$count_member.' Member';
                    flash_notif_success($msg,$url_return);

                }
            }else{
                $msg = 'Tidak dapat diproses. Saldo harus lebih dari 0.';
                flash_notif_warning($msg,$url_return.'/topup_group');
            }





        }
    }

    function topup_member(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('member_id', 'Member', 'required|trim');

        if ($this->form_validation->run() == FALSE){


            $data['form_opt_source'] = array(
                NULL   => '-',
                'Company'  => 'Perusahaan',
                'Reward'   => 'Reward',
            );

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Saldo';
            $data['page_sub_name']  = 'Top Up Saldo Member';

            $data['form_action']    = site_url('member_saldo_setting/topup_member').'?url_return='.$url_return;
            $data['page']           = 'member_saldo_setting/member_saldo_setting_form_topup_member_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $source     = $post['ms_source'];
            $name       = 'Top Up : '.$post['ms_source'];
            $member_id  = $post['member_id'];
            $saldo      = $post['ms_saldo'];

            $get_member = $this->member_model->get($member_id);
            $data = NULL;
            $count_member = 0;

            if ($saldo > 0){
                if (!$get_member){
                    flash_notif_failed(NULL,$url_return,FALSE);
                }else{
                    if ($source=='Reward'){

                        $year = date('Y');
                        $check_reward_given = $this->member_saldo_model->check_reward_given($member_id,$year);
                        if ($check_reward_given==FALSE){
                            $count_member ++;
                            $data[] = array(
                                'ms_source' => $source,
                                'ms_name'   => $name,
                                'ms_saldo'  => $saldo,
                                'member_id' => $member_id,
                                'ms_type'   => 'IN'
                            );
                        }

                    }else if ($source=='Company'){
                        $count_member ++;
                        $data[] = array(
                            'ms_source' => $source,
                            'ms_name'   => $name,
                            'ms_saldo'  => $saldo,
                            'member_id' => $member_id,
                            'ms_type'   => 'IN'
                        );
                    }

                    if ($data){
                        $this->member_saldo_model->insert_batch($data);
                        $this->member_saldo_model->member_saldo_sync();
                    }

                    create_log($this->section_id,"",'Topup Member','Config Saldo');
                    $msg = 'Top Up Saldo '.$source.' kepada '.$count_member.' Member';
                    flash_notif_success($msg,$url_return);

                }
            }else{
                $msg = 'Tidak dapat diproses. Saldo harus lebih dari 0.';
                flash_notif_warning($msg,$url_return.'/topup_group');
            }


        }
    }

    function topup_all(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member_saldo_setting');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('ms_source', 'Source', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_source'] = array(
                NULL   => '-',
                'Company'  => 'Perusahaan',
                'Reward'   => 'Reward',
            );

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Config Saldo';
            $data['page_sub_name']  = 'Top Up Saldo Semua Member';

            $data['form_action']    = site_url('member_saldo_setting/topup_all').'?url_return='.$url_return;
            $data['page']           = 'member_saldo_setting/member_saldo_setting_form_topup_all_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $source     = $post['ms_source'];
            $name       = 'Top Up : '.$post['ms_source'];
            $saldo      = $post['ms_saldo'];

            $get_member = $this->member_model->get_all();
            $data       = NULL;
            $count_member = 0;

            foreach ($get_member['data'] as $k => $v){

                if ($source=='Reward'){

                    $get_config = $this->member_saldo_setting_model->get_by_group($v['group_id']);
                    $saldo = 0;
                    if ($get_config){
                        $saldo = $get_config[0]['mss_saldo'];
                    }

                    if ($saldo > 0){

                        $year = date('Y');
                        $check_reward_given = $this->member_saldo_model->check_reward_given($v['member_id'],$year);
                        if ($check_reward_given==FALSE){
                            $count_member ++;
                            $data[] = array(
                                'ms_source' => $source,
                                'ms_name'   => $name,
                                'ms_saldo'  => $saldo,
                                'member_id' => $v['member_id'],
                                'ms_type'   => 'IN'
                            );
                        }

                    }

                }else if ($source=='Company'){
                    if ($saldo <= 0){
                        $msg = 'Tidak dapat diproses. Saldo harus lebih dari 0.';
                        flash_notif_warning($msg,$url_return.'/topup_all');
                    }

                    $count_member ++;
                    $data[] = array(
                        'ms_source' => $source,
                        'ms_name'   => $name,
                        'ms_saldo'  => $saldo,
                        'member_id' => $v['member_id'],
                        'ms_type'   => 'IN'
                    );
                }

            }

            if ($data){
                $this->member_saldo_model->insert_batch($data);
                $this->member_saldo_model->member_saldo_sync();
            }

            create_log($this->section_id,"",'Topup All','Config Saldo');

            $msg = 'Top Up Saldo '.$source.' kepada '.$count_member.' Member';
            flash_notif_success($msg,$url_return);


        }
    }


    function sync_saldo(){
        $this->member_saldo_model->member_saldo_sync();
        $msg = 'Sync Saldo Member';
        $url_return = site_url('member_saldo_setting');
        create_log($this->section_id,"",'Sync Saldo','Config Saldo');
        flash_notif_success($msg,$url_return);
    }


}