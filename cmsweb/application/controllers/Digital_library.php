<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Digital_library extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'category_model',
            'content_model',
            'content_tags_model',
            'content_type_model',
            'media_model',
            'group_model',
            'member_level_model',
            'member_bidang_model',
            'klien_model',
        ));
        $select_tree = [];
        $this->section_id = 35;
    }

    function l_modal_ajax(){
        $this->load->view('digital_library/digital_library_list_picker_modal_view');
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->content_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['content_id']              = $item->content_id;
            $row['content_create_date']     = $item->content_create_date?parseDateShortReadable($item->content_create_date):NULL;
            $row['content_create_time']     = $item->content_create_date?parseTimeReadable($item->content_create_date):NULL;
            $row['content_publish_date']    = $item->content_publish_date?parseDateShortReadable($item->content_publish_date):NULL;
            $row['content_publish_time']    = $item->content_publish_date?parseTimeReadable($item->content_publish_date):NULL;
            $row['content_name']            = $item->content_name;
            $row['content_author']            = $item->content_author;
            $row['cat_id']            = $item->cat_id;
            $row['cat_name']            = $item->cat_name;
            $row['media_value']            = isset($item->media_value)?$item->media_value:'';
            $row['media_type']            = isset($item->media_type)?$item->media_type:'';
            $row['mlevel_id']       = $item->mlevel_id;
            $row['content_hits']    = $item->content_hits;
            $row['content_status']  = $item->content_status;


            $row['content_type_id']  = $item->content_type_id;
            $row['content_type_name']  = $item->content_type_name;

            $media_type = NULL;
            if ($item->content_type_id==1){
                $media_type = 'document';
                $base_url = URL_MEDIA_DOCUMENT;
            } elseif ($item->content_type_id==2){
                $media_type = 'document';
                $base_url = URL_MEDIA_DOCUMENT;
            } elseif ($item->content_type_id==3){
                $media_type = 'video';
                $base_url = URL_MEDIA_VIDEO;
            } elseif ($item->content_type_id==4){
                $media_type = 'audio';
                $base_url = URL_MEDIA_AUDIO;
            }

            $get_media_document = $this->media_model->get_by_section_data_type($this->section_id,$item->content_id,$media_type);
            $row['document_value']  = $get_media_document?$base_url.$get_media_document['media_value']:NULL;

            $row['nama_klien'] = getKlienBySectionData($this->section_id,$item->content_id,'content', 'render_nama_klien');

            $data[] = $row;
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

    private function printTree($tree, $r = 0, $p = null, $cat_selected) {
        if($tree){

            foreach ($tree as $i => $t) {
                if ($cat_selected==$t['cat_id']){
                    $is_selected = 'selected';
                }else{
                    $is_selected = '';
                }
                $dash = ($t['cat_parent'] == 0) ? '' : str_repeat('- ', $r) .' ';

                $this->select_tree[] = '<option value="'.$t['cat_id'].'" '.$is_selected.'>'.$dash.$t['cat_name'].'</option>';
                //printf("\t<option value='%d' %s>%s%s</option>\n", $t['cat_id'],$is_selected, $dash, $t['cat_name']);
                if ($t['cat_parent'] == $p) {
                    // reset $r
                    $r = 0;
                }
                if (isset($t['child'])) {
                    $this->printTree($t['child'], $r+1, $t['cat_parent'], $cat_selected);
                }
            }

        }
    }

    function index(){
        has_access('digitallibrary.view');

        $data['form_opt_klien'][NULL] = NULL;

        $param_query = NULL;
        if (my_klien()){
            $param_query['filter_id'] = my_klien();
        }
        $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_klien!=FALSE){
            foreach ($get_klien['data'] as $k => $v) {
                $data['form_opt_klien'][$v['id']] = $v['nama'];
            }
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Digital Library';
        $data['page_sub_name']      = 'List Digital Library';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'digital_library/digital_library_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($content_id=NULL){
        has_access('digitallibrary.view');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }



        $get_media_document = $this->media_model->get_by_section_data_file_only($this->section_id,$content['content_id']);

        $media_type = isset($get_media_document['media_type'])?$get_media_document['media_type']:NULL;
        if ($media_type == 'document'){
            $base_url = URL_MEDIA_DOCUMENT;
        } elseif ($media_type == 'video'){
            $base_url = URL_MEDIA_VIDEO;
        } elseif ($media_type == 'audio'){
            $base_url = URL_MEDIA_AUDIO;
        }



        $data['document_value']  = $get_media_document?$base_url.$get_media_document['media_value']:NULL;
        $data['document_name']  = $get_media_document?$get_media_document['media_name']:NULL;

        $data['content']        = $content;
        $data['content']['klien']   = getKlienBySectionData($this->section_id,$content_id,'content', 'render_nama_klien');

        $data['page_name']      = 'Digital Library';
        $data['page_sub_name']  = $content['content_name'];
        $data['page'] = 'digital_library/digital_library_detail_view';
        $data['submenu'] = 'digital_library/digital_library_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('digitallibrary.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_name', 'Judul', 'required|trim');
        if (!my_klien()){
            $this->form_validation->set_rules('klien[]', 'Klien', 'required|trim');
        }


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_content_type'][''] = '-';
            $param_query['filter_active'] = '';
            $get_content_type = $this->content_type_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_content_type!=FALSE){
                foreach ($get_content_type['data'] as $k => $v) {
                    $data['form_opt_content_type'][$v['content_type_id']] = $v['content_type_name'];
                }
            }

            $get_bidang = $this->member_bidang_model->get_all();
            $data['bidang'] = $get_bidang['data'];

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['klien'] = getKlienAll();

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc');
            $cat_selected = NULL;

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Digital Library';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('digital_library/create');
            $data['page']           = 'digital_library/digital_library_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $group_id = 'all';

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

                'content_type_id'       => $post['content_type_id']?$post['content_type_id']:'',

                'cat_id'      => $post['cat_id'],
            );

            //print_r($data);

            $insert = $this->content_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $content_id = $insert;
                $url_return = site_url('digital_library/detail/').$insert;

                // Proses Klien
                if (my_klien()){
                    insertKlienBySectionData($this->section_id, $insert, my_klien(),'content');
                }else{
                    // Superadmin
                    if (isset($post['klien'])){
                        $kliens = $post['klien'];
                        if ($kliens){
                            foreach ($kliens as $id_klien){
                                if (is_numeric($id_klien) && $id_klien > 0){
                                    insertKlienBySectionData($this->section_id, $insert, $id_klien,'content');
                                }
                            }
                        }
                    }
                }

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
                    //$notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            // Start Handle Document
            $this->load->library('upload');
            if (isset($_FILES['document']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['document']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;

                $ext = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
                $ext_map_type = [
                    'image'  => ['jpg', 'jpeg', 'png'],  //image
                    'document'  => ['pdf', 'doc', 'ppt' , 'xls', 'docx', 'pptx' , 'xlsx'],  //document
                    'video'     => ['mp4', 'flv', 'mov'],   // video
                    'audio'     => ['mp3', 'acc']   // audio
                ];

                foreach ($ext_map_type as $k => $v){

                    if (in_array($ext,$v)){
                        $media_type = $k;
                    }

                }


                $file_folder = $media_type;
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'pdf|mp4|mp3';
                $config['max_size']      = '500000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('document')){

                    $go_upload = $this->upload->data();
                    $data_media = array(
                        'section_id'    => $this->section_id,
                        'data_id'       => $insert,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => 'download',
                        'media_type'    => $media_type,
                        'media_value'   => $filename_system,
                        'media_size'    => formatFileSize($go_upload['file_size']),
                        'media_primary' => '0',
                        'media_status'  => '1',
                        'media_create_date' => date('Y-m-d H:i:s')
                    );

                    $this->load->model('media_model');


                    $insert_media = $this->media_model->insert($data_media);

                    if ($insert_media==FALSE) {
                        //GAGAL INSERT FILE
                        $notif[] = "File Uploaded, but insert data to database failed.";

                    }



                    // UPDATE CONTENT TYPE
                    $ext_map_type = [
                        '2'  => ['pdf', 'doc', 'ppt' , 'xls', 'docx', 'pptx' , 'xlsx'],  //document
                        '3'     => ['mp4', 'flv', 'mov'],   // video
                        '4'     => ['mp3', 'acc']   // audio
                    ];

                    foreach ($ext_map_type as $k => $v){

                        if (in_array(str_replace('.','',$go_upload['file_ext']),$v)){
                            $content_type_id = $k;
                        }

                    }


                    $data_update_content = [
                        'content_id'        => $content_id,
                        'content_type_id'   => $content_type_id
                    ];
                    $this->content_model->update($data_update_content);


                }else{
                    // GAGAL UPLOAD
                    //$notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle Document

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
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');
        $this->form_validation->set_rules('content_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc');
            $cat_selected = $content['cat_id'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/


            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_content').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Konten'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_content_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // Proses Tags
            $tags = '';
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
                $tags = '';
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

                'cat_id'      => $post['cat_id'],
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
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_picture').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_picture_view';
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

    function edit_document($content_id=NULL){
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_document').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Media'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_document_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // Start Handle Document
            $this->load->library('upload');
            if (isset($_FILES['document']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['document']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;


                $ext = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
                $ext_map_type = [
                    'image'  => ['jpg', 'jpeg', 'png'],  //image
                    'document'  => ['pdf', 'doc', 'ppt' , 'xls', 'docx', 'pptx' , 'xlsx'],  //document
                    'video'     => ['mp4', 'flv', 'mov'],   // video
                    'audio'     => ['mp3', 'acc']   // audio
                ];

                foreach ($ext_map_type as $k => $v){

                    if (in_array($ext,$v)){
                        $media_type = $k;
                    }

                }

                $file_folder = $media_type;
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'pdf|ppt|pptx|doc|docx|xls|xlsx|mp4|mp3|webm';
                $config['max_size']      = '50000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('document')){

                    $go_upload = $this->upload->data();


                    $data_media = array(
                        'section_id'    => $this->section_id,
                        'data_id'       => $content['content_id'],
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => 'download',
                        'media_type'    => $media_type,
                        'media_value'   => $filename_system,
                        'media_size'    => formatFileSize($go_upload['file_size']),
                        'media_primary' => '0',
                        'media_status'  => '1',
                        'media_create_date' => date('Y-m-d H:i:s')
                    );

                    $this->load->model('media_model');


                    $insert_media = $this->media_model->insert($data_media);

                    if ($insert_media==FALSE) {
                        //GAGAL INSERT FILE
                        $notif[] = "File Uploaded, but insert data to database failed.";

                    } else{

                        // UPDATE CONTENT TYPE
                        $ext_map_type = [
                            '2'  => ['pdf', 'doc', 'ppt' , 'xls', 'docx', 'pptx' , 'xlsx'],  //document
                            '3'     => ['mp4', 'flv', 'mov'],   // video
                            '4'     => ['mp3', 'acc']   // audio
                        ];

                        foreach ($ext_map_type as $k => $v){

                            if (in_array(str_replace('.','',$go_upload['file_ext']),$v)){
                                $content_type_id = $k;
                            }

                        }


                        $data_update_content = [
                            'content_id'        => $content_id,
                            'content_type_id'   => $content_type_id
                        ];
                        $this->content_model->update($data_update_content);


                    }



                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle Document
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
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_publish').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Status'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_publish_view';
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

    function edit_content_type($content_id=NULL){
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_content_type'][''] = '-';
            $param_query['filter_active'] = '';
            $get_content_type = $this->content_type_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_content_type!=FALSE){
                foreach ($get_content_type['data'] as $k => $v) {
                    $data['form_opt_content_type'][$v['content_type_id']] = $v['content_type_name'];
                }
            }

            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_content_type').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Jenis Konten'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_content_type_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_type_id'        => $post['content_type_id'],
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
        has_access('digitallibrary.edit');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');
        if (!my_klien()){
            $this->form_validation->set_rules('klien[]', 'Klien', 'required|trim');
        }

        if ($this->form_validation->run() == FALSE){
            $get_bidang = $this->member_bidang_model->get_all();
            $data['bidang'] = $get_bidang['data'];

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['klien'] = getKlienAll();

            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/edit_metadata').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Edit Metadata'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'digital_library/digital_library_form_edit_metadata_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            // Proses Bidang Member
            $bidang = '';
            if (isset($post['bidang'])){
                if (in_array('all',$post['bidang'])){
                    $bidang = 'all';
                }else{
                    $bidang = implode(',',$post['bidang']);
                }
            }

            // Proses Level Member
            $member_level = '';
            if (isset($post['member_level'])){
                if (in_array('all',$post['member_level'])){
                    $member_level = 'all';
                }else{
                    $member_level = implode(',',$post['member_level']);
                }
            }

            // Proses Klien
            // Superadmin
            if (!my_klien()){
                if (isset($post['klien'])){

                    $kliens = $post['klien'];
                    foreach ($kliens as $k => $v){
                        if ($v <= 0){
                            unset($kliens[$k]);
                        }
                    }

                }else{
                    $kliens = NULL;
                }
                updateKlienBySectionData($this->section_id,$content_id,$kliens,'content');
            }

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_bidang'        => $bidang,
                'mlevel_id'             => $member_level,
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$content_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Data berhasil disimpan.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($content_id=NULL){
        has_access('digitallibrary.delete');

        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('digital_library/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['content']         = $content;
            $data['request']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('digital_library/delete').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Digital Library";
            $data['page_sub_name']      = 'Hapus Digital Library';
            $data['page']               = 'digital_library/digital_library_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->content_model->delete($content_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$content_id,'Hapus',NULL);
                $msg        = $content['content_name']." telah dihapus.";
                $url_return = site_url('digital_library');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('digital_library/delete/'.$content_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}