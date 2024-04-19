<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expert_member extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'member_model',
            'expert_member_model',
            'media_model',
            'group_model',
            'member_level_model',
            'province_model',
            'category_model',
        ));

        $this->section_id = 37;
    }

    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->expert_member_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['em_id']           = $item->em_id;
            $row['em_name']         = $item->em_name;
            $row['em_concern']          = $item->em_concern;
            $row['group_name']         = $item->group_name;
            $row['cat_name']          = $item->cat_name;
            $row['em_status']        = $item->em_status;

            $row['em_create_date']  = $item->em_create_date?parseDateShortReadable($item->em_create_date):NULL;
            $row['em_create_time']  = $item->em_create_date?parseTimeReadable($item->em_create_date):NULL;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->expert_member_model->count_all(),
            "recordsFiltered" => $this->expert_member_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_expert_member($expert_member_id){
        $get_expert_member = $this->expert_member_model->get($expert_member_id);
        if ($get_expert_member==FALSE){
            redirect(404);
        }else{
            return $get_expert_member;
        }
    }

    function index(){
        has_access('expertmember.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Expert Member';
        $data['page_sub_name']      = 'List Expert Member';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'expert_member/expert_member_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($expert_member_id=NULL){
        $expert_member = $this->get_expert_member($expert_member_id);

        $data['expert_member']        = $expert_member;
        $data['page_name']      = 'Expert Member';
        $data['page_sub_name']  = $expert_member['em_name'];
        $data['page'] = 'expert_member/expert_member_detail_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('expertmember.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('em_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('member_password', 'Password', 'required');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_category!=FALSE){
                foreach ($get_category['data'] as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Expert Member';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('expert_member/create');
            $data['page']           = 'expert_member/expert_member_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            //print_r($post);

            $data_member = array(
                'member_name'       => $post['em_name'],
                'member_phone'      => $post['member_phone'],
                'member_email'      => $post['member_email'],
                'member_password'   => md5(trim($post['member_password'])),
                'group_id'          => $post['group_id'],
                'member_status'     => $post['em_status'],
                'member_ceo'        => 0,
                'member_create_date'   => date("Y-m-d H:i:s"),
                'is_expert' => 1,
            );

            $insert_member = $this->member_model->insert($data_member);
            if ($insert_member==TRUE) {

                $member_id = $insert_member;

                // EDUCATION
                $edu_grade = $post['edu_grade'];
                $edu_institution = $post['edu_institution'];
                $edu_year = $post['edu_year'];
                foreach ($edu_grade as $k => $v) {
                    $line_edu_grade =  $edu_grade[$k];
                    $line_edu_institution =  $edu_institution[$k];
                    $line_edu_year =  $edu_year[$k];

                    $extra_education[$k] = array(
                        'education'     => $line_edu_grade,
                        'institution'   => $line_edu_institution,
                        'year'          => $line_edu_year,
                    );
                }


                // WORK EXPERIENCE
                $work_title = $post['work_title'];
                $work_institution = $post['work_institution'];
                $work_year_start = $post['work_year_start'];
                $work_year_end = $post['work_year_end'];
                $work_is_default = $post['work_is_default'];
                foreach ($work_title as $k => $v) {
                    $line_work_title        =  $work_title[$k];
                    $line_work_institution  =  $work_institution[$k];
                    $line_work_year_start   =  $work_year_start[$k];
                    $line_work_year_end     =  $work_year_end[$k];
                    $line_work_is_default   =  $k==$work_is_default?1:NULL;

                    $extra_experience[$k] = array(
                        'title'       => $line_work_title,
                        'institution' => $line_work_institution,
                        'yearStart'   => $line_work_year_start,
                        'yearEnd'     => $line_work_year_end,
                        'isDefault'   => $line_work_is_default,
                    );
                }


                // QUALIFICATION
                $qlf_id = $post['qlf_id'];
                $qlf_score = $post['qlf_score'];
                $qlf_year = $post['qlf_year'];
                foreach ($qlf_id as $k => $v) {
                    $line_qlf_id        =  $qlf_id[$k];
                    $line_qlf_score  =  $qlf_score[$k];
                    $line_qlf_year   =  $qlf_year[$k];

                    $get_cat = $this->category_model->get($line_qlf_id);
                    $line_qlf_title = $get_cat?$get_cat['cat_name']:"";

                    $extra_qualification[$k] = array(
                        'id'       => $line_qlf_id,
                        'title'    => $line_qlf_title,
                        'score'    => $line_qlf_score,
                        'year'     => $line_qlf_year,
                    );
                }

                // DATA EXPERT
                $data_expert_member = array(
                    'em_name'       => $post['em_name'],
                    'group_id'      => $post['group_id'],
                    'cat_id'        => $post['cat_id'],
                    'member_id'     => $member_id,
                    'em_profil'     => $post['em_profil'],
                    'em_concern'       => $post['em_concern'],
                    'em_education'     => json_encode($extra_education, JSON_UNESCAPED_SLASHES),
                    'em_experience'    => json_encode($extra_experience, JSON_UNESCAPED_SLASHES),
                    'em_qualification' => json_encode($extra_qualification, JSON_UNESCAPED_SLASHES),
                    'em_status'        => $post['em_status'],
                    'em_create_date'   => date("Y-m-d H:i:s"),
                );

                $insert_expert_member = $this->expert_member_model->insert($data_expert_member);
                if ($insert_expert_member==TRUE) {
                    create_log($this->section_id,$insert_expert_member,'Tambah','Member');

                    $expert_member_id = $insert_expert_member;

                    $url_return = site_url('expert_member/detail/').$insert_expert_member;

                    // Start Handle File
                    $this->load->library('upload');
                    if (isset($_FILES['file']['name'])){

                        // Config File Name

                        $filename_origin  = $_FILES['file']['name'];
                        $filename_system  = preg_replace('/\s+/', '', 'member_expert_'.uniqid().'_'.$filename_origin);

                        $ext_pos = strrpos($filename_system, '.');
                        if ($ext_pos){
                            $ext = substr($filename_system, $ext_pos);
                            $filename_system = substr($filename_system, 0, $ext_pos);
                            $filename_system = str_replace('.', '_', $filename_system).$ext;
                        }

                        $config['file_name']     = $filename_system;


                        // Config Folder
                        $upload_folder = UPLOAD_FOLDER;
                        $file_folder = 'image';
                        $full_folder = $upload_folder.$file_folder;

                        $config['upload_path'] = $full_folder; //path folder
                        if(!is_dir($full_folder)){
                            mkdir($full_folder,0777);
                        }

                        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                        $config['max_size']      = '5000';

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('file')){

                            $go_upload = $this->upload->data();

                            $update_member = $this->member_model->update(
                                array(
                                    'member_id'      => $member_id,
                                    'member_image'   => $filename_system,
                                )
                            );

                            $update_expert_member = $this->expert_member_model->update(
                                array(
                                    'em_id'      => $expert_member_id,
                                    'em_image'   => $filename_system,
                                )
                            );

                        }else{
                            // GAGAL UPLOAD
                            //$notif[] = $this->upload->display_errors();
                            $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                        }

                    }
                    // End Handle File

                    if (!$notif) {
                        flash_notif_success(NULL,$url_return);
                    }else{
                        $msg = '';
                        foreach ($notif as $k => $v) {
                            $msg .= $v."<br>";
                        }
                        flash_notif_warning($msg,$url_return);
                    }

                }else{
                    flash_notif_failed(NULL,$url_return,FALSE);
                }

            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit_picture($expert_member_id=NULL){
        has_access('expertmember.edit');

        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/').$expert_member_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $expert_member['media_value'] = $expert_member['em_image'];
            $data['request']            = $expert_member;
            $data['expert_member']      = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_picture').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Expert Member";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$expert_member['em_name'].'</small>';
            $data['page']               = 'expert_member/expert_member_form_edit_picture_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name

                $filename_origin  = $_FILES['file']['name'];
                $filename_system  = preg_replace('/\s+/', '', 'expert_member_'.uniqid().'_'.$filename_origin);

                $ext_pos = strrpos($filename_system, '.');
                if ($ext_pos){
                    $ext = substr($filename_system, $ext_pos);
                    $filename_system = substr($filename_system, 0, $ext_pos);
                    $filename_system = str_replace('.', '_', $filename_system).$ext;
                }

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;
                $file_folder = 'image';
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $go_upload = $this->upload->data();

                    $update_member = $this->member_model->update(
                        array(
                            'member_id'      => $expert_member['member_id'],
                            'member_image'   => $filename_system,
                        )
                    );

                    $update_expert_member = $this->expert_member_model->update(
                        array(
                            'em_id'      => $expert_member['em_id'],
                            'em_image'   => $filename_system,
                        )
                    );

                }else{
                    // GAGAL UPLOAD
                    //$notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            create_log($this->section_id,$expert_member_id,'Edit','Member');
            if (!$notif) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = '';
                foreach ($notif as $k => $v) {
                    $msg .= $v."<br>";
                }
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_personal($expert_member_id=NULL){
        has_access('expertmember.edit');
        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/'.$expert_member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_category!=FALSE){
                foreach ($get_category['data'] as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['request']            = $expert_member;
            $data['expert_member']      = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_personal').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Informasi Expert Member';
            $data['page']               = 'expert_member/expert_member_form_edit_personal_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'em_id'  => $post['em_id']==NULL?NULL:$post['em_id'],
                'em_name'       => $post['em_name'],
                'group_id'      => $post['group_id'],
                'cat_id'        => $post['cat_id'],
                'em_profil'     => $post['em_profil'],
                'em_concern'    => $post['em_concern'],
            );

            $member_id = $expert_member['member_id'];
            if ($member_id){
                $data_member = array(
                    'member_id'   => $member_id,
                    'member_name' => $post['em_name'],
                    'group_id'    => $post['group_id']
                );
                $this->member_model->update($data_member);
            }


            $edit = $this->expert_member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$expert_member_id,'Edit','Member');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('expert_member/detail/'.$expert_member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_education($expert_member_id=NULL){
        has_access('expertmember.edit');

        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/'.$expert_member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_category!=FALSE){
                foreach ($get_category['data'] as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['request']            = $expert_member;
            $data['expert_member']      = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_education').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Informasi Expert Member';
            $data['page']               = 'expert_member/expert_member_form_edit_education_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // EDUCATION
            $edu_grade = $post['edu_grade'];
            $edu_institution = $post['edu_institution'];
            $edu_year = $post['edu_year'];
            foreach ($edu_grade as $k => $v) {
                $line_edu_grade =  $edu_grade[$k];
                $line_edu_institution =  $edu_institution[$k];
                $line_edu_year =  $edu_year[$k];

                $extra_education[$k] = array(
                    'education'     => $line_edu_grade,
                    'institution'   => $line_edu_institution,
                    'year'          => $line_edu_year,
                );
            }

            $data = array(
                'em_id'  => $post['em_id']==NULL?NULL:$post['em_id'],
                'em_education'  => json_encode($extra_education, JSON_UNESCAPED_SLASHES),
            );


            $edit = $this->expert_member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$expert_member_id,'Edit','Member');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('expert_member/detail/'.$expert_member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_experience($expert_member_id=NULL){
        has_access('expertmember.edit');

        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/'.$expert_member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_category!=FALSE){
                foreach ($get_category['data'] as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['request']            = $expert_member;
            $data['expert_member']      = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_experience').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Informasi Expert Member';
            $data['page']               = 'expert_member/expert_member_form_edit_experience_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            //print_r($post);exit();
            // WORK EXPERIENCE
            $work_title = $post['work_title'];
            $work_institution = $post['work_institution'];
            $work_year_start = $post['work_year_start'];
            $work_year_end = $post['work_year_end'];
            $work_is_default = $post['work_is_default'];
            foreach ($work_title as $k => $v) {
                $line_work_title        =  $work_title[$k];
                $line_work_institution  =  $work_institution[$k];
                $line_work_year_start   =  $work_year_start[$k];
                $line_work_year_end     =  $work_year_end[$k];
                $line_work_is_default   =  $k==$work_is_default?1:NULL;

                $extra_experience[$k] = array(
                    'title'       => $line_work_title,
                    'institution' => $line_work_institution,
                    'yearStart'   => $line_work_year_start,
                    'yearEnd'     => $line_work_year_end,
                    'isDefault'   => $line_work_is_default,
                );
            }

            $data = array(
                'em_id'  => $post['em_id']==NULL?NULL:$post['em_id'],
                'em_experience'  => json_encode($extra_experience, JSON_UNESCAPED_SLASHES),
            );


            $edit = $this->expert_member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$expert_member_id,'Edit','Member');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('expert_member/detail/'.$expert_member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_qualification($expert_member_id=NULL){
        has_access('expertmember.edit');

        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/'.$expert_member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }
            }

            $data['form_opt_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_category!=FALSE){
                foreach ($get_category['data'] as $k => $v) {
                    $data['form_opt_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['request']            = $expert_member;
            $data['expert_member']      = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_qualification').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Member";
            $data['page_sub_name']      = 'Edit Informasi Expert Member';
            $data['page']               = 'expert_member/expert_member_form_edit_qualification_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // QUALIFICATION
            $qlf_id = $post['qlf_id'];
            $qlf_score = $post['qlf_score'];
            $qlf_year = $post['qlf_year'];
            foreach ($qlf_id as $k => $v) {
                $line_qlf_id        =  $qlf_id[$k];
                $line_qlf_score  =  $qlf_score[$k];
                $line_qlf_year   =  $qlf_year[$k];

                $get_cat = $this->category_model->get($line_qlf_id);
                $line_qlf_title = $get_cat?$get_cat['cat_name']:"";

                $extra_qualification[$k] = array(
                    'id'       => $line_qlf_id,
                    'title'    => $line_qlf_title,
                    'score'    => $line_qlf_score,
                    'year'     => $line_qlf_year,
                );
            }

            $data = array(
                'em_id'  => $post['em_id']==NULL?NULL:$post['em_id'],
                'em_qualification'  => json_encode($extra_qualification, JSON_UNESCAPED_SLASHES),
            );


            $edit = $this->expert_member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$expert_member_id,'Edit','Member');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('expert_member/detail/'.$expert_member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_status($expert_member_id=NULL){
        has_access('expertmember.edit');

        $expert_member = $this->get_expert_member($expert_member_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('expert_member/detail/'.$expert_member_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('em_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $expert_member;
            $data['expert_member']            = $expert_member;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('expert_member/edit_status').'/'.$expert_member_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Expert Member";
            $data['page_sub_name']      = 'Edit Expert Member <br><small>'.$expert_member['em_name'].'</small>';
            $data['page']               = 'expert_member/expert_member_form_edit_status_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'em_id'  => $post['em_id']==NULL?NULL:$post['em_id'],
                'em_status'     => $post['em_status'],
            );

            /*$member_id = $expert_member['member_id'];
            if ($member_id){
                $data_member = array(
                    'member_id'   => $member_id,
                    'member_status' => $post['em_status'],
                );
                $this->member_model->update($data_member);
            }*/


            $edit = $this->expert_member_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$expert_member_id,'Edit','Member');
                flash_notif_success(NULL,$url_return);
            }else{
                $url_return = site_url('expert_member/detail/'.$expert_member_id);
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function member_add_picker(){

        has_access('expertmember.create');

        $post = $this->input->post();
        $member_id = $post['id'];

        $member = $this->member_model->get($member_id);

        if ($member['is_expert']==1){

            echo json_encode(
                array('message'=>"Member yang dipilih telah terdaftar sebagai expert." ,'em_id'=>0)
            );

        }else{

            $data_expert = array(
                'member_id' => $member['member_id'],
                'group_id'  => $member['group_id'],
                'em_name'   => $member['member_name'],
                'em_image'  => $member['member_image'],
                'em_status' => 'active',
                'em_create_date' => date('Y-m-d H:i:s')
            );
            $insert_expert = $this->expert_member_model->insert($data_expert);
            if ($insert_expert){

                $em_id = $insert_expert;
                $data_member_update = array(
                    'member_id' => $member['member_id'],
                    'is_expert' => '1'
                );
                $this->member_model->update($data_member_update);

                create_log($this->section_id,$insert_expert,'Tambah','Member');

                flash_notif_success($member['member_name']." berhasil ditambahkan sebagai expert",NULL,FALSE);

                echo json_encode(
                    array('message'=>"Member berhasil ditambahkan sebagai expert",'em_id'=>$em_id)
                );
            }else{
                echo json_encode(
                    array('message'=>"Member gagal ditambahkan sebagai expert" ,'em_id'=>0)
                );
            }

        }




    }

    function delete($expert_member_id=NULL){}



}