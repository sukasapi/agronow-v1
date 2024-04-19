<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelatihan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'pelatihan_model',
            'pelatihan_member_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));

        $this->load->library('ciqrcode');

        $this->section_id = 26;
    }


    private function generate_qr($data){
        $params['data'] = $data;
        $params['level'] = 'H';
        $params['size'] = 10;

        $filename = 'qr-pelatihan-'.uniqid().'.png';
        $params['savename'] = UPLOAD_FOLDER.'image/'.$filename;
        $this->ciqrcode->generate($params);

        return $filename;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->pelatihan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['pelatihan_id']              = $item->pelatihan_id;
            $row['pelatihan_date_start']     = $item->pelatihan_date_start?parseDateShortReadable($item->pelatihan_date_start):NULL;
            $row['pelatihan_time_start']     = $item->pelatihan_date_start?parseTimeReadable($item->pelatihan_date_start):NULL;
            $row['pelatihan_date_end']    = $item->pelatihan_date_end?parseDateShortReadable($item->pelatihan_date_end):NULL;
            $row['pelatihan_time_end']    = $item->pelatihan_date_end?parseTimeReadable($item->pelatihan_date_end):NULL;
            $row['pelatihan_name']            = $item->pelatihan_name;
            $row['pelatihan_qrcode']            = $item->pelatihan_qrcode;
            $row['pelatihan_location']            = $item->pelatihan_location;
            $row['status']       = "";

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->pelatihan_model->count_all(),
            "recordsFiltered" => $this->pelatihan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_pelatihan($pelatihan_id){
        $get_pelatihan = $this->pelatihan_model->get($pelatihan_id);
        if ($get_pelatihan==FALSE){
            redirect(404);
        }else{
            return $get_pelatihan;
        }
    }

    function index(){
        has_access('pelatihan.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Pelatihan';
        $data['page_sub_name']      = 'List Pelatihan';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'pelatihan/pelatihan_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($pelatihan_id=NULL){
        has_access('pelatihan.view');

        $pelatihan = $this->get_pelatihan($pelatihan_id);

        $data['member']         = $this->pelatihan_member_model->get_by_pelatihan($pelatihan_id);
        $data['member_count']   = $this->pelatihan_member_model->count_by_pelatihan($pelatihan_id);

        //print_r($data);

        $data['pelatihan']      = $pelatihan;
        $data['page_name']      = 'Pelatihan';
        $data['page_sub_name']  = $pelatihan['pelatihan_name'];
        $data['page'] = 'pelatihan/pelatihan_detail_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('pelatihan.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('account');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pelatihan_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Pelatihan';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('pelatihan/create');
            $data['page']           = 'pelatihan/pelatihan_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            // JADWAL
            $sch_date = $post['date'];
            $sch_time_start = $post['time_start'];
            $sch_time_end = $post['time_end'];
            foreach ($sch_date as $k => $v) {
                $line_date        =  parseDate($sch_date[$k]);
                $line_time_start  =  $sch_time_start[$k];
                $line_time_end    =  $sch_time_end[$k];

                $pelatihan_detail[$line_date] = $line_time_start.'-'.$line_time_end;
            }

            $data = array(
                'pelatihan_name'          => $post['pelatihan_name'],
                'pelatihan_desc'          => $post['pelatihan_desc'],
                'pelatihan_location'      => $post['pelatihan_location'],
                'pelatihan_date_start'    => parseDate(reset($sch_date)).' '.reset($sch_time_start),
                'pelatihan_date_end'      => parseDate(end($sch_date)).' '.end($sch_time_end),
                'pelatihan_date_detail'   => json_encode($pelatihan_detail, JSON_UNESCAPED_SLASHES),
                'pelatihan_create_date'   => date("Y-m-d H:i:s"),
            );

            $insert = $this->pelatihan_model->insert($data);
            if ($insert==TRUE) {

                $data_qr = array(
                    'id'    => $insert,
                    'Name'  => $post['pelatihan_name']
                );
                $qr_code_filename = $this->generate_qr(json_encode($data_qr,JSON_UNESCAPED_SLASHES));

                $data_update = array(
                    'pelatihan_id'      => $insert,
                    'pelatihan_qrcode'  => $qr_code_filename,
                );
                $this->pelatihan_model->update($data_update);

                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('pelatihan/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return);
            }

        }
    }

    function edit($pelatihan_id=NULL){
        has_access('pelatihan.edit');

        $pelatihan = $this->get_pelatihan($pelatihan_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('pelatihan/detail/').$pelatihan_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pelatihan_id', 'ID', 'required');
        $this->form_validation->set_rules('pelatihan_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $pelatihan;
            $data['pelatihan']            = $pelatihan;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('pelatihan/edit').'/'.$pelatihan_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Pelatihan";
            $data['page_sub_name']      = 'Edit Pelatihan';
            $data['page']               = 'pelatihan/pelatihan_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'pelatihan_id'  => $post['pelatihan_id']==NULL?NULL:$post['pelatihan_id'],
                'pelatihan_name'          => $post['pelatihan_name'],
                'pelatihan_desc'          => $post['pelatihan_desc'],
                'pelatihan_location'      => $post['pelatihan_location'],
            );


            $edit = $this->pelatihan_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$pelatihan_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_warning('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function edit_schedule($pelatihan_id=NULL){
        has_access('pelatihan.edit');

        $pelatihan = $this->get_pelatihan($pelatihan_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('pelatihan/detail/'.$pelatihan_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pelatihan_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $pelatihan;
            $data['pelatihan']      = $pelatihan;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('pelatihan/edit_schedule').'/'.$pelatihan_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Pelatihan";
            $data['page_sub_name']      = 'Edit Jadwal Pelatihan';
            $data['page']               = 'pelatihan/pelatihan_form_edit_schedule_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // JADWAL
            $sch_date = $post['date'];
            $sch_time_start = $post['time_start'];
            $sch_time_end = $post['time_end'];
            foreach ($sch_date as $k => $v) {
                $line_date        =  parseDate($sch_date[$k]);
                $line_time_start  =  $sch_time_start[$k];
                $line_time_end    =  $sch_time_end[$k];

                $pelatihan_detail[$line_date] = $line_time_start.'-'.$line_time_end;
            }

            $data = array(
                'pelatihan_id'            => $post['pelatihan_id']==NULL?NULL:$post['pelatihan_id'],
                'pelatihan_date_start'    => parseDate(reset($sch_date)).' '.reset($sch_time_start),
                'pelatihan_date_end'      => parseDate(end($sch_date)).' '.end($sch_time_end),
                'pelatihan_date_detail'   => json_encode($pelatihan_detail, JSON_UNESCAPED_SLASHES),
            );


            $edit = $this->pelatihan_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$pelatihan_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('pelatihan/detail/'.$pelatihan_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($pelatihan_id=NULL){
        has_access('pelatihan.delete');

        $pelatihan = $this->get_pelatihan($pelatihan_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('pelatihan/detail/').$pelatihan_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pelatihan_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['pelatihan']         = $pelatihan;
            $data['request']            = $pelatihan;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('pelatihan/delete').'/'.$pelatihan_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Pelatihan";
            $data['page_sub_name']      = 'Hapus Pelatihan';
            $data['page']               = 'pelatihan/pelatihan_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->pelatihan_model->delete($pelatihan_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$pelatihan_id,'Hapus','');
                $msg        = $pelatihan['pelatihan_name']." telah dihapus.";
                $url_return = site_url('pelatihan');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('pelatihan/delete/'.$pelatihan_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }

    function member_add_picker($pelatihan_id=NULL){
        $pelatihan = $this->get_pelatihan($pelatihan_id);

        $post = $this->input->post();
        $member_ids = $post['member_ids'];

        foreach ($member_ids as $v){
            $member_id = $v;
            $get_member = $this->pelatihan_member_model->get_by_pelatihan_member($pelatihan_id,$member_id);
            if ($get_member){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'pelatihan_id'     => $pelatihan_id,
                    'member_id' => $member_id,
                    'pm_channel'    => 'cms',
                    'pm_create_date'    => date('Y-m-d H:i:s')
                );
                $this->pelatihan_member_model->insert($data);
            }
        }
        create_log($this->section_id,$pelatihan_id,'Tambah','Member');
        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );
    }

    function member_remove($pelatihan_id=NULL,$pm_id=NULL){
        $pelatihan = $this->get_pelatihan($pelatihan_id);
        $this->pelatihan_member_model->delete($pm_id);
        create_log($this->section_id,$pelatihan_id,'Hapus','Member');
        flash_notif_success('Berhasil dihapus',NULL,FALSE);
        redirect(site_url('pelatihan/detail/'.$pelatihan_id));
    }


}