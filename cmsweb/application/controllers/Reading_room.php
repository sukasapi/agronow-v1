<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reading_room extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'content_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));

        $this->section_id = 28;
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
            $row['media_value']            = $item->media_value;
            $row['media_type']            = $item->media_type;
            $row['cat_id']            = $item->cat_id;
            $row['cat_name']            = $item->cat_name;
            $row['mlevel_id']       = $item->mlevel_id;
            $row['content_hits']    = $item->content_hits;
            $row['content_status']  = $item->content_status;

            $get_media_document = $this->media_model->get_by_section_data_type($this->section_id,$item->content_id,"document");
            $row['document_value']  = $get_media_document?$get_media_document['media_value']:NULL;

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

    function index(){
        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Reading Room';
        $data['page_sub_name']      = 'List Reading Room';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'reading_room/reading_room_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($content_id=NULL){
        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $get_media_document = $this->media_model->get_by_section_data_type($this->section_id,$content_id,"document");
        $data['document_value']  = $get_media_document?$get_media_document['media_value']:NULL;

        $data['content']        = $content;
        $data['page_name']      = 'Reading Room';
        $data['page_sub_name']  = $content['content_name'];
        $data['page'] = 'reading_room/reading_room_detail_view';
        $data['submenu'] = 'reading_room/reading_room_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('reading_room');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['bidang'] = getBidang();

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Reading Room';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('reading_room/create');
            $data['page']           = 'reading_room/reading_room_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $group_id = 'all';

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


            $content_create_by = 1;
            $data = array(
                'section_id'            => $this->section_id,
                'member_id'             => 0,
                'group_id'              => $group_id,
                'mlevel_id'             => $member_level,
                'content_name'          => $post['content_name'],
                'content_alias'         => slugify($post['content_name']),
                'content_desc'          => $post['content_desc'],
                'content_tags'          => $tags,
                'content_source'        => $post['content_source'],
                'content_author'        => $post['content_author'],
                'content_bidang'        => $bidang,
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
                $url_return = site_url('reading_room/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }


            // Proses Upload Gambar

        }
    }

    function edit_content($content_id=NULL){
        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('reading_room/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');
        $this->form_validation->set_rules('content_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('reading_room/edit_content').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Reading Room";
            $data['page_sub_name']      = 'Edit Konten'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'reading_room/reading_room_form_edit_content_view';
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
                'content_desc'          => $post['content_desc'],
                'content_tags'          => $tags,
                'content_source'        => $post['content_source'],
                'content_author'        => $post['content_author'],

                'content_seo_title'     => $post['content_seo_title'],
                'content_seo_keyword'   => $post['content_seo_keyword'],
                'content_seo_desc'      => $post['content_seo_desc'],
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_picture($content_id=NULL){}

    function edit_publish($content_id=NULL){
        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('reading_room/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('reading_room/edit_publish').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Reading Room";
            $data['page_sub_name']      = 'Edit Status'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'reading_room/reading_room_form_edit_publish_view';
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
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_metadata($content_id=NULL){
        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('reading_room/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['bidang'] = getBidang();

            $get_member_level = $this->member_level_model->get_all();
            $data['member_level'] = $get_member_level['data'];

            $data['request']            = $content;
            $data['content']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('reading_room/edit_metadata').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Reading Room";
            $data['page_sub_name']      = 'Edit Metadata'.'<br><small>'.$content['content_name'].'</small>';
            $data['page']               = 'reading_room/reading_room_form_edit_metadata_view';
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

            $data = array(
                'content_id'  => $post['content_id']==NULL?NULL:$post['content_id'],
                'content_bidang'        => $bidang,
                'mlevel_id'             => $member_level,
            );


            $edit = $this->content_model->update($data);
            if ($edit==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Tidak Ada Perubahan Data";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($content_id=NULL){
        $content = getContent($content_id,$this->section_id);
        if ($content['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('reading_room/detail/').$content_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('content_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['content']         = $content;
            $data['request']            = $content;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('reading_room/delete').'/'.$content_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Reading Room";
            $data['page_sub_name']      = 'Hapus Reading Room';
            $data['page']               = 'reading_room/reading_room_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->content_model->delete($content_id,TRUE);
            if ($delete==TRUE) {
                $msg        = $content['content_name']." telah dihapus.";
                $url_return = site_url('reading_room');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('reading_room/delete/'.$content_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



}