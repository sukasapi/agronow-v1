<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Digital_sop extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'content_model',
            'content_tags_model',
            'media_model',
            'group_model',
            'member_level_model',
            'member_bidang_model',
        ));

        $this->section_id = 36;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->content_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {

            if (in_array($item->group_id,my_groups())){

                $no++;
                $row = array();
                $row['content_id']              = $item->content_id;
                $row['content_create_date']     = $item->content_create_date?parseDateShortReadable($item->content_create_date):NULL;
                $row['content_create_time']     = $item->content_create_date?parseTimeReadable($item->content_create_date):NULL;
                $row['content_publish_date']    = $item->content_publish_date?parseDateShortReadable($item->content_publish_date):NULL;
                $row['content_publish_time']    = $item->content_publish_date?parseTimeReadable($item->content_publish_date):NULL;
                $row['content_name']            = $item->content_name;
                $row['media_value']            = $item->media_value;
                $row['media_type']            = $item->media_type;
                $row['mlevel_id']       = $item->mlevel_id;
                $row['content_hits']    = $item->content_hits;
                $row['group_name']    = $item->group_name;
                $row['content_status']  = $item->content_status;

                $row['picture']  = "";

                $data[] = $row;

            }

        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->content_model->count_all(),
            "recordsFiltered" => $this->content_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function index(){
        has_access('digitalsop.view');

        $group_id = $this->input->get('group_id');
        $data['form_opt_group'][NULL] = NULL;
        $param_query['filter_active'] = '';
        $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_group!=FALSE){
            foreach ($get_group['data'] as $k => $v) {

                if (in_array($v['group_id'], my_groups())){
                    $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                }

            }
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Digital SOP';
        $data['page_sub_name']      = 'List Digital SOP';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'digital_sop/digital_sop_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($content_id=NULL){
        has_access('digitalsop.view');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $data['content']        = $content;

        $group = $this->group_model->get($content['group_id']);
        if ($group){
            $data['content']['group_name'] = $group['group_name'];
        }else{
            $data['content']['group_name'] = '';
        }


        $data['page_name']      = 'Digital SOP';
        $data['page_sub_name']  = $content['content_name'];
        $data['page'] = 'digital_sop/digital_sop_detail_view';
        $data['submenu'] = 'digital_sop/digital_sop_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('digitalsop.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {

                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }

                }
            }

            $get_bidang = $this->member_bidang_model->get_all();
            $data['bidang'] = $get_bidang['data'];

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Digital SOP';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('digital_sop/create');
            $data['page']           = 'digital_sop/digital_sop_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $group_id = $post['group_id'];

            // Proses Bidang Member
            $bidang = NULL;
            if (isset($post['bidang'])){
                if (in_array('all',$post['bidang'])){
                    $bidang = 'all';
                }else{
                    $bidang = implode(',',$post['bidang']);
                }
            }

            // Proses Level Member
            $member_level = NULL;
            if (isset($post['member_level'])){
                if (in_array('all',$post['member_level'])){
                    $member_level = 'all';
                }else{
                    $member_level = implode(',',$post['member_level']);
                }
            }


            // Proses Tags
            $tags = NULL;
            if (isset($post['content_tags'])){
                $tags = implode(',',$post['content_tags']);

                foreach ($post['content_tags'] as $v){
                    $get_tags = $this->content_tags_model->get_by_name($v);
                    if ($get_tags==FALSE){
                        $data_tags = array(
                            'section_id'    => $this->section_id,
                            'tags_name'     => $v,
                            'tags_alias'    => slugify($v),
                        );
                        $this->content_tags_model->insert($data_tags);
                    }
                }

            }else{
                $tags = NULL;
            }


            $content_create_by = user_id();
            $data = array(
                'section_id'            => $this->section_id,
                'member_id'             => 0,
                'group_id'              => $group_id?$group_id:'',
                'mlevel_id'             => $member_level?$member_level:'',
                'content_name'          => $post['content_name'],
                'content_alias'         => slugify($post['content_name']),
                'content_desc'          => $this->input->post('content_desc',FALSE),
                'content_tags'          => $tags?$tags:'',
                'content_source'        => $post['content_source'],
                'content_author'        => $post['content_author'],
                'content_bidang'        => $bidang?$bidang:'',
                'content_seo_title'     => $post['content_seo_title'],
                'content_seo_keyword'   => $post['content_seo_keyword'],
                'content_seo_desc'      => $post['content_seo_desc'],
                'content_status'        => $post['content_status'],
                'content_notif'         => $post['content_notif'],
                'content_publish_date'  => $post['content_publish_date']?parseDateTime($post['content_publish_date']):date("Y-m-d H:i:s"),
                'content_create_date'   => date("Y-m-d H:i:s"),
                'content_create_by'     => $content_create_by,
            );

            //print_r($data);

            $insert = $this->content_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('digital_sop/detail/').$insert;
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

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
                    $data_media = array(
                        'section_id'    => $this->section_id,
                        'data_id'       => $insert,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => 'image',
                        'media_value'   => $filename_system,
                        'media_size'    => formatFileSize($go_upload['file_size']),
                        'media_primary' => '1',
                        'media_status'  => '1',
                        'media_create_date' => date('Y-m-d H:i:s')
                    );

                    $this->load->model('media_model');


                    $insert_media = $this->media_model->insert($data_media);

                    if ($insert_media==FALSE) {
                        //GAGAL INSERT FILE
                        $notif[] = "File Uploaded, but insert data to database failed.";

                    }

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
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

        }
    }

    function edit_content($content_id=NULL){
        has_access('digitalsop.edit');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');
        $this->form_validation->set_rules('content_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_sop/edit_content').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital SOP";
            $data['page_sub_name']      = 'Edit Konten'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_sop/digital_sop_form_edit_content_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // Proses Tags
            $tags = NULL;
            if (isset($post['content_tags'])){
                $tags = implode(',',$post['content_tags']);

                foreach ($post['content_tags'] as $v){
                    $get_tags = $this->content_tags_model->get_by_name($v);
                    if ($get_tags==FALSE){
                        $data_tags = array(
                            'section_id'    => $this->section_id,
                            'tags_name'     => $v,
                            'tags_alias'    => slugify($v),
                        );
                        $this->content_tags_model->insert($data_tags);
                    }
                }

            }else{
                $tags = NULL;
            }

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_name'          => $post['content_name'],
                'content_desc'          => $this->input->post('content_desc',FALSE),
                'content_tags'          => $tags,
                'content_source'        => $post['content_source'],
                'content_author'        => $post['content_author'],

                'content_seo_title'     => $post['content_seo_title'],
                'content_seo_keyword'   => $post['content_seo_keyword'],
                'content_seo_desc'      => $post['content_seo_desc'],
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$content_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_picture($content_id=NULL){
        has_access('digitalsop.edit');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_sop/edit_picture').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital SOP";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_sop/digital_sop_form_edit_picture_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

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
                    $data_media = array(
                        'section_id'    => $this->section_id,
                        'data_id'       => $content['content_id'],
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => 'image',
                        'media_value'   => $filename_system,
                        'media_size'    => formatFileSize($go_upload['file_size']),
                        'media_primary' => '1',
                        'media_status'  => '1',
                        'media_create_date' => date('Y-m-d H:i:s')
                    );

                    $this->load->model('media_model');


                    $insert_media = $this->media_model->insert($data_media);

                    if ($insert_media==FALSE) {
                        //GAGAL INSERT FILE
                        $notif[] = "File Uploaded, but insert data to database failed.";

                    }else{

                        //BERHASIL INSERT FILE
                        $update_media = $this->media_model->update(
                            array(
                                'media_id'      => $content['media_id'],    // Old Media ID
                                'media_primary' => '0',
                                'media_status'  => '0',
                            )
                        );

                        //$media_id = $insert_media;

                        // END INSERT DATA FILE

                    }

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            create_log($this->section_id,$content_id,'Edit',NULL);
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

    function edit_publish($content_id=NULL){
        has_access('digitalsop.edit');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_sop/edit_publish').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital SOP";
            $data['page_sub_name']      = 'Edit Status'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_sop/digital_sop_form_edit_publish_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_status'        => $post['content_status'],
                'content_notif'         => $post['content_notif'],
                'content_publish_date'  => $post['content_publish_date']?parseDateTime($post['content_publish_date']):NULL,
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$content_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_metadata($content_id=NULL){
        has_access('digitalsop.edit');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['form_opt_group'][''] = '-';
            $param_query['filter_active'] = '';
            $get_group = $this->group_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_group!=FALSE){
                foreach ($get_group['data'] as $k => $v) {
                    if (in_array($v['group_id'], my_groups())){
                        $data['form_opt_group'][$v['group_id']] = $v['group_name'];
                    }
                }
            }

            $get_bidang = $this->member_bidang_model->get_all();
            $data['bidang'] = $get_bidang['data'];

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_sop/edit_metadata').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital SOP";
            $data['page_sub_name']      = 'Edit Metadata'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_sop/digital_sop_form_edit_metadata_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // Proses Bidang Member
            $bidang = NULL;
            if (isset($post['bidang'])){
                if (in_array('all',$post['bidang'])){
                    $bidang = 'all';
                }else{
                    $bidang = implode(',',$post['bidang']);
                }
            }

            // Proses Level Member
            $member_level = NULL;
            if (isset($post['member_level'])){
                if (in_array('all',$post['member_level'])){
                    $member_level = 'all';
                }else{
                    $member_level = implode(',',$post['member_level']);
                }
            }

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_bidang'        => $bidang,
                'mlevel_id'             => $member_level,
                'group_id'              => $post['group_id']?$post['group_id']:''
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$content_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($content_id=NULL){
        has_access('digitalsop.delete');

        $content = getContent($content_id,$this->section_id);

        if (!is_my_group($content['group_id'])){
            redirect(404);
        }

        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_sop/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['content']         = $content;
            $data['request']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_sop/delete').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital SOP";
            $data['page_sub_name']      = 'Hapus Digital SOP';
            $data['page']               = 'digital_sop/digital_sop_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->content_model->delete($content_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$content_id,'Hapus',NULL);
                $msg        = $content['content_name']." telah dihapus.";
                $url_return = site_url('digital_sop');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('digital_sop/delete/'.$content_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}