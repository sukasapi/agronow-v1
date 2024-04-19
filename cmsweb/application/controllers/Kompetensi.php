<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'kompetensi_model',
            'kompetensi_member_model',
            'kompetensi_jabatan_model',
            'kompetensi_prasyarat_model',
            'kompetensi_group_model',
            'kompetensi_soal_model',
            'category_model',
            'media_model',
            'group_model',
            'member_model',
            'member_level_model',
        ));
        $select_tree = [];
        $this->section_id = 43;
    }


    function is_valid_level_category_($crs_level=NULL,$cat_id=NULL){

        if ($crs_level==NULL OR $cat_id==NULL){
            echo json_encode([
                'status'    => FALSE,
                'data'      => '',
                'message'   => 'Kategori dan Level tidak boleh kosong!'
            ]);
            exit();
        }

        $req_per_level  = 5;
        $data           = array();
        $status_arr     = array();
        for ($i=$crs_level ;$i > 0; $i--){
            $count_level_cat = $this->kompetensi_soal_model->count_by_level_category($i, $cat_id);
            if (!$count_level_cat){
                $status_arr[] = FALSE;
                $data[] = [
                    'status'    => FALSE,
                    'level'     => $i,
                    'count'     => 0
                ];
            }else{
                if ($count_level_cat['total'] >= $req_per_level){
                    $status_arr[] = TRUE;
                    $data[] = [
                        'status'    => TRUE,
                        'level'     => $i,
                        'count'     => $count_level_cat['total']
                    ];
                }else{
                    $status_arr[] = FALSE;
                    $data[] = [
                        'status'    => FALSE,
                        'level'     => $i,
                        'count'     => $count_level_cat['total']
                    ];
                }
            }
        }

        if (in_array(FALSE,$status_arr)){
            echo json_encode([
                'status'    => FALSE,
                'data'      => $data,
                'message'   => 'Jumlah soal per level tidak memenuhi syarat!'
            ]);
            exit();
        }else{
            echo json_encode([
                'status'    => TRUE,
                'data'      => $data,
                'message'   => 'Pass'
            ]);
            exit();
        }


    }

    function is_year_available($year=NULL){
        $available_year = $this->kompetensi_model->get_available_year();
        $arr_year = array();
        if ($available_year){
            foreach ($available_year as $v){
                $arr_year[] = $v['year'];
            }
        }


        if (in_array($year,$arr_year)){

            echo json_encode([
                'status'    => FALSE,
                'data'      => NULL,
                'message'   => 'sudah ada kompetensi di tahun '.$year.', silakan hapus dulu semua jika ingin duplikat'
            ]);
            exit();

        }else{

            echo json_encode([
                'status'    => TRUE,
                'data'      => NULL,
                'message'   => 'Pass'
            ]);
            exit();

        }

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->kompetensi_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['cr_id']           = $item->cr_id;
            $row['cr_date_start']   = $item->cr_date_start?parseDateShortReadable($item->cr_date_start):NULL;
            $row['cr_date_end']     = $item->cr_date_end?parseDateShortReadable($item->cr_date_end):NULL;
            $row['cr_name']         = $item->cr_name;
            $row['cat_name']        = $item->cat_name;
            $user_count  = $this->kompetensi_member_model->count_by_kompetensi($item->cr_id);
            $row['user_count']       = $user_count?$user_count['total']:0;
            $row['cr_status']       = $item->cr_status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kompetensi_model->count_all(),
            "recordsFiltered" => $this->kompetensi_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_kompetensi($kompetensi_id){
        $get_kompetensi = $this->kompetensi_model->get($kompetensi_id);
        if ($get_kompetensi==FALSE){
            redirect(404);
        }else{
            return $get_kompetensi;
        }
    }

    function index(){

        has_access('kompetensi.view');

        $category_ids = $this->input->get('category_ids');
        $data['form_opt_category'] = NULL;
        if (!isset($category_ids)){
            $data['form_opt_category'] = NULL;
        }else{
            $get_category = $this->category_model->gets($category_ids);
            if ($get_category!=FALSE){
                foreach ($get_category as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }
        }

        $available_year = $this->kompetensi_model->get_available_year();
        $data['available_year'] = $available_year;

        $year_selected = $this->input->get('year')?$this->input->get('year'):'N/A';
        $data['year_selected'] = $year_selected;

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Kompetensi';
        $data['page_sub_name']      = 'List Kompetensi Tahun '.$year_selected;
        $data['is_content_header']  = TRUE;
        $data['page']               = 'kompetensi/kompetensi_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($kompetensi_id=NULL){
        has_access('kompetensi.view');

        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['kompetensi']        = $kompetensi;
        $data['page_name']      = 'Kompetensi';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_detail_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }



    function check_level_category($crs_level){

        $status = $this->input->post('cr_status');

        if ($status != 'publish'){
            return TRUE;
        }

        $cat_id = $this->input->post('cat_id');

        if ($crs_level==NULL OR $cat_id==NULL){
            $this->form_validation->set_message('check_level_category', 'Kategori dan Level tidak boleh kosong');
            return FALSE;
        }

        $req_per_level  = 5;
        $data           = array();
        $status_arr     = array();
        for ($i=$crs_level ;$i > 0; $i--){
            $count_level_cat = $this->kompetensi_soal_model->count_by_level_category($i, $cat_id);
            if (!$count_level_cat){
                $status_arr[] = FALSE;
                $data[] = [
                    'status'    => FALSE,
                    'level'     => $i,
                    'count'     => 0
                ];
            }else{
                if ($count_level_cat['total'] >= $req_per_level){
                    $status_arr[] = TRUE;
                    $data[] = [
                        'status'    => TRUE,
                        'level'     => $i,
                        'count'     => $count_level_cat['total']
                    ];
                }else{
                    $status_arr[] = FALSE;
                    $data[] = [
                        'status'    => FALSE,
                        'level'     => $i,
                        'count'     => $count_level_cat['total']
                    ];
                }
            }
        }

        if (in_array(FALSE,$status_arr)){

            $msg = '';
            foreach ($data as $v){
                if ($v['status']==FALSE){
                    $msg .= 'Jumlah bank soal untuk level '.$v['level'].' pada kategori terpilih hanya ada '.$v['count'].' (minimal '.$req_per_level.' soal).<br>';
                }
            }

            $this->form_validation->set_message('check_level_category', $msg);
            return FALSE;
        }else{
            return TRUE;
        }


    }



    function create(){
        has_access('kompetensi.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('account');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('cr_komp_max_lv', 'Jumlah Level', 'required|trim|callback_check_level_category');



        if ($this->form_validation->run() == FALSE){

            if (validation_errors()){
                $get_category = $this->category_model->get(set_value('cat_id'));
                if ($get_category){
                    $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
                }
            }else{
                $data['form_opt_cat'][NULL] = '-';
            }


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Kompetensi';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('kompetensi/create');
            $data['page']           = 'kompetensi/kompetensi_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_materi'         => $post['cr_materi'],
                'cr_komp_max_lv'    => parseInputNull($post['cr_komp_max_lv']),
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_create_date'   => date("Y-m-d H:i:s"),

                'cr_is_daily'   => $post['cr_is_daily'],
                'cr_status'   => $post['cr_status'],

                'cr_year'   => $post['cr_year'],
            );

            $insert = $this->kompetensi_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('kompetensi/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($kompetensi_id=NULL){
        has_access('kompetensi.edit');

        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi/detail/'.$kompetensi_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('cr_komp_max_lv', 'Jumlah Level', 'required|trim|callback_check_level_category');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($kompetensi['cat_id']);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }

            $data['request']            = $kompetensi;
            $data['kompetensi']            = $kompetensi;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kompetensi/edit').'/'.$kompetensi_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kompetensi";
            $data['page_sub_name']      = 'Edit Kompetensi';
            $data['page']               = 'kompetensi/kompetensi_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'cr_id'  => $post['cr_id']==NULL?NULL:$post['cr_id'],
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_materi'           => $post['cr_materi'],
                'cr_komp_max_lv'           => parseInputNull($post['cr_komp_max_lv']),
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,

                'cr_is_daily'   => $post['cr_is_daily'],
                'cr_status'   => $post['cr_status'],

                'cr_year'   => $post['cr_year'],

            );


            $edit = $this->kompetensi_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$kompetensi_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($kompetensi_id=NULL){
        has_access('kompetensi.delete');

        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi/detail/').$kompetensi_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['kompetensi']         = $kompetensi;
            $data['request']            = $kompetensi;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kompetensi/delete').'/'.$kompetensi_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kompetensi";
            $data['page_sub_name']      = 'Hapus Kompetensi';
            $data['page']               = 'kompetensi/kompetensi_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->kompetensi_model->delete($kompetensi_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$kompetensi_id,'Hapus','');
                $msg        = $kompetensi['cr_name']." telah dihapus.";
                $url_return = site_url('kompetensi');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('kompetensi/delete/'.$kompetensi_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }


    function member($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['member']         = $this->kompetensi_member_model->get_by_kompetensi($kompetensi_id);
        $data['member_count']   = $this->kompetensi_member_model->count_by_kompetensi($kompetensi_id);
        //print_r($data);
        $data['kompetensi']      = $kompetensi;
        $data['page_name']      = 'Kompetensi';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_member_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function member_add($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['kompetensi'] = $kompetensi;
        $data['request']   = $kompetensi;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('kompetensi/member_add/'.$kompetensi_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Kompetensi';
            $data['page_sub_name']  = $kompetensi['cr_name'];
            $data['page'] = 'kompetensi/kompetensi_member_add_view';
            $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $member_ids = $post['member_ids'];
            $kompetensi_id = $post['cr_id'];

            foreach ($member_ids as $v){
                $member_id = $v;
                $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                if ($get_member){
                    // Member Exist Then Skip

                }else{
                    $data = array(
                        'cr_id'     => $kompetensi_id,
                        'member_id' => $member_id,
                    );
                    $insert_member = $this->kompetensi_member_model->insert($data);
                }
            }
            create_log($this->section_id,$kompetensi_id,'Tambah','Member');
            $url_return = site_url('kompetensi/member/').$kompetensi_id;
            flash_notif_success(NULL,$url_return);

        }
    }

    function member_add_picker($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $post = $this->input->post();
        $member_ids = $post['member_ids'];

        foreach ($member_ids as $v){
            $member_id = $v;
            $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
            if ($get_member){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'     => $kompetensi_id,
                    'member_id' => $member_id,
                );
                $this->kompetensi_member_model->insert($data);
            }
        }
        create_log($this->section_id,$kompetensi_id,'Tambah','Member');
        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );
    }

    function member_remove($kompetensi_id=NULL,$crm_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);
        $this->kompetensi_member_model->delete($crm_id);
        create_log($this->section_id,$kompetensi_id,'Hapus','Member');
        redirect(site_url('kompetensi/member/'.$kompetensi_id));
    }


    // MANAGE MEMBER BY JABATAN

    function jabatan($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['jabatan']         = $this->kompetensi_jabatan_model->get_by_kompetensi($kompetensi_id);
        $data['jabatan_count']   = $this->kompetensi_jabatan_model->count_by_kompetensi($kompetensi_id);
        //print_r($data);
        $data['kompetensi']      = $kompetensi;
        $data['page_name']      = 'Kompetensi';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_jabatan_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function jabatan_add_picker($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $post = $this->input->post();
        $jabatan_ids = $post['jabatan_ids'];

        foreach ($jabatan_ids as $v){
            $jabatan_id = $v;
            $get_jabatan = $this->kompetensi_jabatan_model->get_by_kompetensi_jabatan($kompetensi_id,$jabatan_id);
            if ($get_jabatan){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'      => $kompetensi_id,
                    'jabatan_id' => $jabatan_id,
                );
                $add_jabatan = $this->kompetensi_jabatan_model->insert($data);
                if ($add_jabatan){
                    // Add Member By Jabatan
                    $this->add_member_by_jabatan($kompetensi_id,$jabatan_id);
                }

            }


        }
        create_log($this->section_id,$kompetensi_id,'Tambah','Jabatab');

        echo json_encode(
            array('succ'=>sizeof($jabatan_ids))
        );
    }

    function jabatan_remove($kompetensi_id=NULL,$crm_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $kompetensi_jabatan = $this->kompetensi_jabatan_model->get($crm_id);
        $members = $this->member_model->get_by_jabatan($kompetensi_jabatan['jabatan_id']);

        if ($members){
            $member_ids = [];
            foreach ($members as $v){
                $member_ids[] = $v['member_id'];
            }
            $this->kompetensi_member_model->delete_bulk($kompetensi_id,$member_ids);
        }


        $this->kompetensi_jabatan_model->delete($crm_id);
        create_log($this->section_id,$kompetensi_id,'Hapus','Jabatan');
        redirect(site_url('kompetensi/jabatan/'.$kompetensi_id));
    }

    function jabatan_sync_member($kompetensi_id){

        $get_jabatan = $this->kompetensi_jabatan_model->get_by_kompetensi($kompetensi_id);
        if ($get_jabatan){

            foreach ($get_jabatan as $k => $v){
                $jabatan_id = $v['jabatan_id'];
                $this->add_member_by_jabatan($kompetensi_id,$jabatan_id);
            }

        }
        create_log($this->section_id,$kompetensi_id,'Sync','Jabatan Member');
        $url_return = site_url('kompetensi/jabatan/'.$kompetensi_id);
        flash_notif_success("Semua member dalam list jabatan ini telah dimasukan sebagai peserta.",$url_return);

    }

    private function add_member_by_jabatan($kompetensi_id,$jabatan_id){
        $count_member = 0;
        $member_by_jabatan = $this->member_model->get_by_jabatan($jabatan_id);
        if ($member_by_jabatan){
            $count_member = sizeof($member_by_jabatan);

            foreach ($member_by_jabatan as $v){
                $member_id = $v['member_id'];
                $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                if ($get_member){
                    // Member Exist Then Skip

                }else{
                    $data = array(
                        'cr_id'     => $kompetensi_id,
                        'member_id' => $member_id,
                    );
                    $this->kompetensi_member_model->insert($data);
                }
            }

        }
        create_log($this->section_id,$kompetensi_id,'Tambah','Member');
        return $count_member;
    }


    // MANAGE MEMBER BY GROUP

    function group($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['group']         = $this->kompetensi_group_model->get_by_kompetensi($kompetensi_id);
        $data['group_count']   = $this->kompetensi_group_model->count_by_kompetensi($kompetensi_id);
        //print_r($data);
        $data['kompetensi']      = $kompetensi;
        $data['page_name']      = 'Kompetensi';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_group_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function group_add_picker($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $post = $this->input->post();
        $group_ids = $post['group_ids'];

        foreach ($group_ids as $v){
            $group_id = $v;
            $get_group = $this->kompetensi_group_model->get_by_kompetensi_group($kompetensi_id,$group_id);
            if ($get_group){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'      => $kompetensi_id,
                    'group_id' => $group_id,
                );
                $add_group = $this->kompetensi_group_model->insert($data);
                if ($add_group){
                    // Add Member By Group
                    $this->add_member_by_group($kompetensi_id,$group_id);
                }

            }


        }

        create_log($this->section_id,$kompetensi_id,'Tambah','Group');
        echo json_encode(
            array('succ'=>sizeof($group_ids))
        );
    }

    function group_remove($kompetensi_id=NULL,$crm_id=NULL){

        $kompetensi = $this->get_kompetensi($kompetensi_id);

        // Delete Member
        $kompetensi_group = $this->kompetensi_group_model->get($crm_id);
        $members = $this->member_model->get_by_group($kompetensi_group['group_id']);

        if ($members){
            $member_ids = [];
            foreach ($members as $v){
                $member_ids[] = $v['member_id'];
            }
            $this->kompetensi_member_model->delete_bulk($kompetensi_id,$member_ids);
        }


        // Delete Group
        $this->kompetensi_group_model->delete($crm_id);
        create_log($this->section_id,$kompetensi_id,'Hapus','Group');
        redirect(site_url('kompetensi/group/'.$kompetensi_id));
    }

    function group_sync_member($kompetensi_id){

        $get_group = $this->kompetensi_group_model->get_by_kompetensi($kompetensi_id);
        if ($get_group){

            foreach ($get_group as $k => $v){
                $group_id = $v['group_id'];
                $this->add_member_by_group($kompetensi_id,$group_id);
            }

        }
        create_log($this->section_id,$kompetensi_id,'Sync','Group Member');
        $url_return = site_url('kompetensi/group/'.$kompetensi_id);
        flash_notif_success("Semua member dalam list group ini telah dimasukan sebagai peserta.",$url_return);

    }

    private function add_member_by_group($kompetensi_id,$group_id){
        $count_member = 0;
        $member_by_group = $this->member_model->get_by_group($group_id);
        if ($member_by_group){
            $count_member = sizeof($member_by_group);

            foreach ($member_by_group as $v){
                $member_id = $v['member_id'];
                $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                if ($get_member){
                    // Member Exist Then Skip

                }else{
                    $data = array(
                        'cr_id'     => $kompetensi_id,
                        'member_id' => $member_id,
                    );
                    $this->kompetensi_member_model->insert($data);
                }
            }

        }
        create_log($this->section_id,$kompetensi_id,'Tambah','Member Group');
        return $count_member;
    }



    // PRASYARAT

    function prasyarat($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $max_level = $kompetensi['cr_komp_max_lv']?$kompetensi['cr_komp_max_lv']:NULL;

        $data['prasyarat'] = array();
        if ($max_level){

            for ($level = 1; $level <= $max_level; $level++ ){
                $data['prasyarat'][$level] = $this->kompetensi_prasyarat_model->get_by_kompetensi_level($kompetensi_id,$level);
            }

        }


        $data['kompetensi']        = $kompetensi;
        $data['page_name']      = 'Prasyarat';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_prasyarat_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function prasyarat_add_picker($kompetensi_id=NULL,$level=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $post = $this->input->post();
        $cr_ids = $post['cr_ids'];

        foreach ($cr_ids as $v){
            $classroom_id = $v;
            $get_prasyarat = $this->kompetensi_prasyarat_model->get_by_kompetensi_classroom_level($kompetensi_id,$classroom_id,$level);
            if ($get_prasyarat){
                // Prasyarat Exist Then Skip

            }else{
                $data = array(
                    'cr_id'      => $kompetensi_id,
                    'classroom_id' => $classroom_id,
                    'level' => $level,
                );
                $add_prasyarat = $this->kompetensi_prasyarat_model->insert($data);

            }


        }

        create_log($this->section_id,$kompetensi_id,'Tambah','Prasyarat');
        echo json_encode(
            array('succ'=>sizeof($cr_ids))
        );
    }

    function prasyarat_remove($kompetensi_id=NULL,$crm_id=NULL){

        $kompetensi = $this->get_kompetensi($kompetensi_id);
        $this->kompetensi_prasyarat_model->delete($crm_id);
        create_log($this->section_id,$kompetensi_id,'Hapus','Prasyarat');
        redirect(site_url('kompetensi/prasyarat/'.$kompetensi_id));
    }



    // DUPLICATE

    function duplicate_by_kompetensi($kompetensi_id=NULL,$year=NULL,$data_default=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data = array(
            'cr_name'           => $kompetensi['cr_name'],
            'cat_id'            => $kompetensi['cat_id'],
            'cr_desc'           => $kompetensi['cr_desc'],
            'cr_materi'         => $kompetensi['cr_materi'],
            'cr_komp_max_lv'    => parseInputNull($kompetensi['cr_komp_max_lv']),
            'cr_date_start'     => $data_default ? $data_default['cr_date_start'] : (isset($kompetensi['cr_date_start'])?parseDate($kompetensi['cr_date_start']):NULL),
            'cr_date_end'       => $data_default ? $data_default['cr_date_end'] : (isset($kompetensi['cr_date_end'])?parseDate($kompetensi['cr_date_end']):NULL),
            'cr_create_date'    => date("Y-m-d H:i:s"),

            'cr_is_daily'       => $kompetensi['cr_is_daily'],
            'cr_status'         => $kompetensi['cr_status'],

            'cr_year'           => $year,
        );

        $insert = $this->kompetensi_model->insert($data);
        if ($insert==TRUE) {


            $new_kompetensi_id = $insert;

            // Duplicate Jabatan
            $kompetensi_jabatan = $this->kompetensi_jabatan_model->get_by_kompetensi($kompetensi_id);

            if ($kompetensi_jabatan){

                $jabatan_ids = array();
                foreach ($kompetensi_jabatan as $v){
                    $jabatan_ids[] = $v['jabatan_id'];
                }


                foreach ($jabatan_ids as $v){
                    $jabatan_id = $v;
                    $get_jabatan = $this->kompetensi_jabatan_model->get_by_kompetensi_jabatan($new_kompetensi_id,$jabatan_id);
                    if ($get_jabatan){
                        // Member Exist Then Skip

                    }else{
                        $data = array(
                            'cr_id'      => $new_kompetensi_id,
                            'jabatan_id' => $jabatan_id,
                        );
                        $add_jabatan = $this->kompetensi_jabatan_model->insert($data);
                        if ($add_jabatan){
                            // Add Member By Jabatan
                            $this->add_member_by_jabatan($new_kompetensi_id,$jabatan_id);
                        }

                    }


                }

            }


            // Duplicate Group
            $kompetensi_group = $this->kompetensi_group_model->get_by_kompetensi($kompetensi_id);

            if ($kompetensi_group){

                $group_ids = array();
                foreach ($kompetensi_group as $v){
                    $group_ids[] = $v['group_id'];
                }


                foreach ($group_ids as $v){
                    $group_id = $v;
                    $get_group = $this->kompetensi_group_model->get_by_kompetensi_group($new_kompetensi_id,$group_id);
                    if ($get_group){
                        // Member Exist Then Skip

                    }else{
                        $data = array(
                            'cr_id'      => $new_kompetensi_id,
                            'group_id' => $group_id,
                        );
                        $add_group = $this->kompetensi_group_model->insert($data);
                        if ($add_group){
                            // Add Member By Group
                            $this->add_member_by_group($new_kompetensi_id,$group_id);
                        }

                    }


                }

            }


            // Duplicate Member
            $kompetensi_member = $this->kompetensi_member_model->get_by_kompetensi($kompetensi_id);

            if ($kompetensi_member){

                $member_ids = array();
                foreach ($kompetensi_member as $v){
                    $member_ids[] = $v['member_id'];
                }


                foreach ($member_ids as $v){
                    $member_id = $v;
                    $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($new_kompetensi_id,$member_id);
                    if ($get_member){
                        // Member Exist Then Skip

                    }else{
                        $data = array(
                            'cr_id'      => $new_kompetensi_id,
                            'member_id' => $member_id,
                        );
                        $add_member = $this->kompetensi_member_model->insert($data);
                    }


                }

            }


            // Duplicate Prasyarat
            $kompetensi_prasyarat = $this->kompetensi_prasyarat_model->get_by_kompetensi($kompetensi_id);

            if ($kompetensi_prasyarat) {

                foreach ($kompetensi_prasyarat as $v) {

                    $get_prasyarat = $this->kompetensi_prasyarat_model->get_by_kompetensi_classroom_level($new_kompetensi_id,$v['classroom_id'],$v['level']);
                    if ($get_prasyarat){
                        // Prasyarat Exist Then Skip

                    }else{
                        $data = array(
                            'cr_id'      => $new_kompetensi_id,
                            'classroom_id' => $v['classroom_id'],
                            'level' => $v['level'],
                        );
                        $add_prasyarat = $this->kompetensi_prasyarat_model->insert($data);

                    }

                }


            }

            create_log($this->section_id,$kompetensi_id,'Duplicate','Kompetensi');
            return TRUE;

        }else{
            return FALSE;
        }


    }

    function duplicate_by_year($year=NULL){

        if (!$year){
            redirect('404');
        }


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi/?year=').$year;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('year_from', 'Tahun Asal', 'required');
        $this->form_validation->set_rules('year_to', 'Tahun Tujuan', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['year']         = $year;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('kompetensi/duplicate_by_year').'/'.$year;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kompetensi";
            $data['page_sub_name']      = 'Salin Semua Kompetensi di Tahun '.$year;
            $data['page']               = 'kompetensi/kompetensi_form_duplicate_year_view';
            $this->load->view('main_view',$data);
        }else{

            $post = $this->input->post();
            $year_from = $post['year_from'];
            $year_to = $post['year_to'];

            $date_start = isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL;
            $date_end = isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL;

            $available_year = $this->kompetensi_model->get_available_year();
            $arr_year = array();
            if ($available_year){
                foreach ($available_year as $v){
                    $arr_year[] = $v['year'];
                }
            }


            if (in_array($year_to,$arr_year)){
                flash_notif_failed('Sudah ada kompetensi di tahun tujuan silahkan hapus semua',site_url('kompetensi/?year=').$year_from);
            }

            $error_duplicate_times = 0;
            $get_kompetensi_by_year_from = $this->kompetensi_model->get_by_year($year_from);
            if ($get_kompetensi_by_year_from){

                foreach ($get_kompetensi_by_year_from as $v){
                    $kompetensi_id = $v['cr_id'];

                    $data_default = array(
                        'cr_date_start' => $date_start,
                        'cr_date_end'   => $date_end,
                    );

                    $duplicate = $this->duplicate_by_kompetensi($kompetensi_id,$year_to,$data_default);

                    if (!$duplicate){
                        $error_duplicate_times++;
                    }

                }

            }

            if ($error_duplicate_times > 0){
                flash_notif_warning('Terdapat '.$error_duplicate_times.' kompetensi gagal disalin',site_url('kompetensi/?year=').$year_to);
            }else{
                create_log($this->section_id,$kompetensi_id,'Duplicate','Tahun');
                flash_notif_success('Semua kompetensi berhasil disalin',site_url('kompetensi/?year=').$year_to);
            }

        }


    }


    // PROGRESS PESERTA

    function progress_member($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['member']         = $this->kompetensi_member_model->get_by_kompetensi($kompetensi_id);
        $data['member_count']   = $this->kompetensi_member_model->count_by_kompetensi($kompetensi_id);
        //print_r($data);
        $data['kompetensi']      = $kompetensi;
        $data['page_name']      = 'Kompetensi';
        $data['page_sub_name']  = $kompetensi['cr_name'];
        $data['page'] = 'kompetensi/kompetensi_progress_member_view';
        $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
        $this->load->view('main_view',$data);
    }


    function progress_member_excel($kompetensi_id=NULL){

        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['member']         = $this->kompetensi_member_model->get_by_kompetensi($kompetensi_id);
        $data['member_count']   = $this->kompetensi_member_model->count_by_kompetensi($kompetensi_id);
        $data['kompetensi']     = $kompetensi;

        create_log($this->section_id,$kompetensi_id,'Export','Progress Member');

        $file="Progress_member_kompetensi_".$kompetensi['cr_name'].".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        $this->load->view('kompetensi/kompetensi_progress_member_excel_view',$data);
    }



    // PUSH NOTIF

    function notif($kompetensi_id=NULL){
        $kompetensi = $this->get_kompetensi($kompetensi_id);

        $data['kompetensi'] = $kompetensi;
        $data['request']   = $kompetensi;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('kompetensi/notif/').$kompetensi_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['request'] = ['notification'=>''];

            //print_r($data['pretest']);
            $data['form_action']    = site_url('kompetensi/notif/'.$kompetensi_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Kompetensi';
            $data['page_sub_name']  = $kompetensi['cr_name'];
            $data['page'] = 'kompetensi/kompetensi_notif_view';
            $data['submenu'] = 'kompetensi/kompetensi_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $this->load->library(['fcm']);

            $post          = $this->input->post();
            $notification  = $post['notification'];
            $kompetensi_id  = $post['cr_id'];

            // GET MEMBER
            $get_member    = $this->kompetensi_member_model->get_by_kompetensi($kompetensi_id);
            foreach ($get_member as $k => $v){

                // NOTIFIKASI
                $recData    = ['memberId' => $v['member_id']];
                $dtoken     = $this->member_model->select_member_device_token('byMemberId', $recData);
                $tokens     = [];
                foreach ($dtoken as $t){
                    array_push($tokens, $t['device_token']);
                }
                $token = $tokens;

                $this->fcm->setTitle($kompetensi['cr_name']);
                $this->fcm->setBody($notification);

                //$this->fcm->setImage('');

                $result = $this->fcm->sendMultiple($token);
                //print_r($v['member_id'].'<br>');

            }
            create_log($this->section_id,$kompetensi_id,'Kirim Notifikasi','');
            flash_notif_success('Notifikasi terkirim',$url_return);
        }

    }

}