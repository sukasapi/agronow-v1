<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Classroom extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'classroom_model',
            'classroom_member_model',
            'classroom_attendance_model',
            'category_model',
            'classroom_soal_model',
            'member_model',
            'jabatan_model',
            'media_model',
            'member_level_model',
        ));
        $this->load->helper('classroom_helper');
        $select_tree = [];
        $this->section_id = 30;
    }


    function l_modal_ajax(){
        $is_price = $this->input->get('is_price');
        $data['is_price'] = $is_price;
        $this->load->view('classroom/classroom_list_picker_modal_view',$data);
    }

    function json(){
        $is_price = $this->input->get('is_price');
        $param_query['is_price'] = $is_price;
        $get_classroom = $this->classroom_model->get_all(NULL,NULL,NULL,$param_query);
        foreach ($get_classroom['data'] as $k => $v){
            $result['data'][] = array(
                'cr_id'      => $v['cr_id'],
                'cr_name'    => $v['cr_name'],
                'cr_type'    => $v['cr_type'],
                'cat_name'    => $v['cat_name']?$v['cat_name']:NULL,
                'cr_price'    => $v['cr_price'],
            );
        }
        echo json_encode($result);
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->classroom_model->get_datatables();
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
            $row['cr_date_detail']  = $item->cr_date_detail;
            $row['cr_price']  = $item->cr_price;

            $user_count  = $this->classroom_member_model->count_by_classroom($item->cr_id);
            $row['user_count']       = $user_count?$user_count['total']:0;

            $now = date('Y-m-d');
            if ($now >= $item->cr_date_start && $now <= $item->cr_date_end){
                $row['cr_status']       = "Progress";
            }else{
                $row['cr_status']       = "Outgoing";
            }


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->classroom_model->count_all(),
            "recordsFiltered" => $this->classroom_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_classroom($classroom_id){
        $get_classroom = $this->classroom_model->get($classroom_id);
        if ($get_classroom==FALSE){
            redirect(404);
        }else{
            return $get_classroom;
        }
    }

    private function printTree($tree, $r = 0, $p = null, $cat_selected) {
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

    function index(){
        has_access('classroom.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Class Room';
        $data['page_sub_name']      = 'List Class Room';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'classroom/classroom_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($classroom_id=NULL){
        has_access('classroom.view');

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'];
        $data['page'] = 'classroom/classroom_detail_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('classroom.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
            $get_cat_parent = $this->input->get('parent');
            if ($this->input->get('parent')){
                $cat_selected = $get_cat_parent;
            }else{
                $cat_selected = NULL;
            }

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('classroom/create');
            $data['page']           = 'classroom/classroom_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_type'           => $post['cr_type'],
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_time_start'     => isset($post['cr_time_start'])?$post['cr_time_start']:NULL,
                'cr_time_end'       => isset($post['cr_time_end'])?$post['cr_time_end']:NULL,
                'cr_date_detail'    => $post['cr_date_detail'],
                'cr_price'           => parseInputNull($post['cr_price']),
                'cr_has_certificate'           => $post['cr_has_certificate'],
                'cr_create_date'   => date("Y-m-d H:i:s"),

                'cr_has_prelearning' => $post['cr_has_prelearning'],
                'cr_has_pretest' => $post['cr_has_pretest'],
                'cr_has_kompetensi_test' => $post['cr_has_kompetensi_test'],
                'cr_show_nilai' => $post['cr_show_nilai'],
                'cr_modul_harus_urut' => $post['cr_modul_harus_urut'],
                'cr_has_learning_point' => $post['cr_has_learning_point'],
                'cr_has_knowledge_management' => $post['cr_has_knowledge_management'],
                'id_petugas' => user_id(),
            );

            $extra_cr_prelearning = array(
                'Desc'      => NULL,
                'Alert'     => NULL,
                'Materi'    => array(),
            );
            $data['cr_prelearning'] = json_encode($extra_cr_prelearning);

            $extra_cr_pretest = array(
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'  => NULL,
                'Status'  => NULL,
                'GradeA'  => NULL,
                'GradeB'  => NULL,
                'GradeC'  => NULL,
                'Question'  => NULL,
            );
            $data['cr_pretest'] = json_encode($extra_cr_pretest);

            $extra_cr_module = array(
                'Desc'    => NULL,
                'Module'  => array(),
            );
            $data['cr_module'] = json_encode($extra_cr_module);

            $extra_cr_competency  = array(
                'ctStart'  => NULL,
                'ctEnd'    => NULL,
                'Desc'     => NULL,
                'TimeLimit' => NULL,
                'Attemp'    => NULL,
                'QuePerPage'=> NULL,
                'ReqPassed' => NULL,
                'Random'  => NULL,
                'Status'  => NULL,
                'GradeA'  => NULL,
                'GradeB'  => NULL,
                'GradeC'  => NULL,
                'Question'  => NULL,
            );
            $data['cr_competency'] = json_encode($extra_cr_competency);

            $extra_cr_certificate = array(
                'Logo'  => NULL,
                'Signature'  => NULL,
                'Text1'  => NULL,
                'Text2'  => NULL,
                'Text3'  => NULL,
                'Text4'  => NULL,
                'Text5'  => NULL,
                'Text6'  => NULL,
                'serType'  => NULL,
                'noStart'  => NULL,
                'noEnd'  => NULL,
                'noUsed' => NULL,
                'serCode'=> NULL,
            );
            $data['cr_certificate'] = json_encode($extra_cr_certificate);

            $extra_cr_feedback = array(
                'Desc'   => NULL,
                'Status' => NULL,
                'Type'   => array(),
                'Question'  => array(),
            );
            $data['cr_feedback'] = json_encode($extra_cr_feedback);

            $insert = $this->classroom_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                
                // New Sub Category
                $data_cat = array(
                    'cat_name'    => $post['cr_name'],
                    'cat_parent'  => $post['cat_id']?$post['cat_id']:'0',
                    'section_id'  => $this->section_id,
                    'cat_status'  => '1',
                );

                //$insert_cat = $this->category_model->insert($data_cat);

                $url_return = site_url('classroom/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return);
            }

        }
    }

    function edit($classroom_id=NULL){
        has_access('classroom.edit');

        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/detail/'.$classroom_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($classroom['cat_id']);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
            $cat_selected = $classroom['cat_id'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['request']            = $classroom;
            $data['classroom']            = $classroom;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('classroom/edit').'/'.$classroom_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Class Room";
            $data['page_sub_name']      = 'Edit Class Room';
            $data['page']               = 'classroom/classroom_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'cr_id'  => $post['cr_id']==NULL?NULL:$post['cr_id'],
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_type'           => $post['cr_type'],
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_time_start'     => isset($post['cr_time_start'])?$post['cr_time_start']:NULL,
                'cr_time_end'       => isset($post['cr_time_end'])?$post['cr_time_end']:NULL,
                'cr_date_detail'    => $post['cr_date_detail'],
                'cr_price'           => parseInputNull($post['cr_price']),
                'cr_has_certificate' => $post['cr_has_certificate'],

                'cr_has_prelearning' => $post['cr_has_prelearning'],
                'cr_has_pretest' => $post['cr_has_pretest'],
                'cr_has_kompetensi_test' => $post['cr_has_kompetensi_test'],
                'cr_show_nilai' => $post['cr_show_nilai'],
                'cr_modul_harus_urut' => $post['cr_modul_harus_urut'],
                'cr_has_learning_point' => $post['cr_has_learning_point'],
                'cr_has_knowledge_management' => $post['cr_has_knowledge_management'],
            );


            // Add New Learning Point Setting
            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            if ($arr_data && isset($arr_data['Module'])){
                if ($arr_data['Module']){
                    foreach ($arr_data['Module'] as $k => $v){

                        if(!isset($arr_data['Module'][$k]['LearningPoint'])){
                            $arr_data['Module'][$k]['LearningPoint'] = array(
                                'Status'  => 'non-active',
                            );
                        }

                    }
                }
            }

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);
            $cr_module = $json_data;

            $data['cr_module'] = $cr_module;



            $edit = $this->classroom_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($classroom_id=NULL){
        has_access('classroom.delete');

        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/detail/').$classroom_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['classroom']         = $classroom;
            $data['request']            = $classroom;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('classroom/delete').'/'.$classroom_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Class Room";
            $data['page_sub_name']      = 'Hapus Class Room';
            $data['page']               = 'classroom/classroom_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->classroom_model->delete($classroom_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$classroom_id,'Hapus',NULL);
                $msg        = $classroom['cr_name']." telah dihapus.";
                $url_return = site_url('classroom');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('classroom/delete/'.$classroom_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }

    function duplicate($classroom_id=NULL){
        has_access('classroom.create');

        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/detail/').$classroom_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['classroom']         = $classroom;
            $data['request']            = $classroom;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('classroom/duplicate').'/'.$classroom_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Class Room";
            $data['page_sub_name']      = 'Duplikasi Class Room';
            $data['page']               = 'classroom/classroom_form_duplicate_view';
            $this->load->view('main_view',$data);
        }else{

            $data = $classroom;
            $data['cr_name']    = 'Copy - '.$classroom['cr_name'];
            unset($data['cr_id']);
            unset($data['cat_name']);
            $data['cat_id'] = $data['cat_id']?$data['cat_id']:'';

            $insert = $this->classroom_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$classroom_id,'Duplicate',NULL);
                $msg        = $classroom['cr_name']." berhasil diduplikasi.";
                $url_return = site_url('classroom/detail/').$insert;
                flash_notif_success($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('classroom/delete/'.$classroom_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }


    // Pre Learning
    function prelearning($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/prelearning/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $extra_prelearning = array(
                'Desc'       => NULL,
                'Alert'  => NULL,
                'Materi'     => NULL,
            );

            if ($classroom['cr_prelearning']){
                $cr_prelearning = $classroom['cr_prelearning'];
                $json_cr_prelearning = preg_replace('/[[:cntrl:]]/', '', $cr_prelearning);
                $extra_prelearning = json_decode($json_cr_prelearning,TRUE);
            }

            $data['request']   = $extra_prelearning;

            //print_r($data['prelearning']);
            $data['form_action']    = site_url('classroom/prelearning/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_prelearning_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);


        }else{

            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc'] = $post['Desc'];
            $arr_data['Alert'] = $post['Alert'];
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_prelearning' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Pre Learning');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function prelearning_materi_add($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/prelearning/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_materi = array(
                'Section'       => NULL,
                'ContentId'     => NULL,
                'ContentName'   => NULL,
                'Type'          => NULL,
                'Media'         => NULL,
            );

            if ($classroom['cr_prelearning']){
                $cr_prelearning = $classroom['cr_prelearning'];
                $json_cr_prelearning = preg_replace('/[[:cntrl:]]/', '', $cr_prelearning);
                $extra_prelearning = json_decode($json_cr_prelearning,TRUE);
            }

            $data['request']   = $extra_materi;

            //print_r($data['prelearning']);
            $data['form_action']    = site_url('classroom/prelearning_materi_add/'.$classroom_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_prelearning_materi_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
            $arr_data = json_decode($json_raw,TRUE);

            $media = isset($post['Media'])?$post['Media']:'';

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;

                $file_folder = $post['Type']=='document'?'document':'video';
                $full_folder = $upload_folder.$file_folder;


                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                if ($post['Type']=='document'){
                    $config['allowed_types'] = 'xlsx|xls|doc|docx|ppt|pptx|txt|pdf';
                }else{
                    $config['allowed_types'] = 'mpeg|mpg|mp4|mpe|qt|mov|avi';
                }

                $config['max_size']      = '50000';

                //print_r($config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $media = $filename_system;

                    $go_upload = $this->upload->data();
                    $data_media = array(
                        'section_id'    => 30,
                        'data_id'       => 0,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => $post['Type']=='document'?'document':'video',
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
                    //$notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

                //print_r($notif);

            }
            // End Handle File

            $materi = array(
                'Section'   => 'classroom',
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentId'  => NULL,
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
            );
            $arr_data['Materi'][]  = $materi;

            //print_r($arr_data);exit();
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_prelearning' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Materi Pre Learning');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }


    // Ajax Post
    function prelearning_materi_add_digital_library($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $post   = $this->input->post();
        $content_id = $post['id'];

        $this->load->model('content_model');
        $content = $this->content_model->get($content_id,35);
        if (!$content){
            echo json_encode(['status'=>400,'message'=>'Konten tidak ditemukan']);exit();
        }


        $get_media_document = $this->media_model->get_by_section_data_file_only(35,$content_id);
        //print_r($get_media_document);
        $type   = $get_media_document?$get_media_document['media_type']:NULL;
        $media  = $get_media_document?$get_media_document['media_value']:NULL;

        if (empty($media)){
            echo json_encode(['status'=>400,'message'=>'Media tidak ditemukan']);exit();
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
        $arr_data = json_decode($json_raw,TRUE);


        $materi = array(
            'Section'       => 'classroom',
            'Type'          => $type,
            'ContentId'     => $content['content_id'],
            'ContentName'   => $content['content_name'],
            'Media'         => $media,
        );
        $arr_data['Materi'][]  = $materi;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_prelearning' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Tambah','Materi Pre Learning');
            echo json_encode(['status'=>200,'message'=>'Materi berhasil ditambahkan']);exit();
        }else{
            echo json_encode(['status'=>400,'message'=>'Materi gagal ditambahkan']);exit();
        }
    }

    function prelearning_materi_delete($classroom_id=NULL,$materi_id){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_prelearning']){
            $json_cr_prelearning = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
            $extra_prelearning = json_decode($json_cr_prelearning,TRUE);
        }

        $existing_materi = $extra_prelearning['Materi'];
        unset($existing_materi[$materi_id]);

        //print_r($existing_materi);exit();

        $extra_prelearning['Materi'] =  $existing_materi;
        $json_data = json_encode($extra_prelearning, JSON_UNESCAPED_SLASHES);

        //print_r($extra_prelearning);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_prelearning' => $json_data,
        );
        //print_r($data);exit();
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Materi Pre Learning');

        redirect(site_url('classroom/prelearning/').$classroom_id);
    }


    // Pre Test
    function pretest($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/pretest/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_pretest = array(
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'  => NULL,
                'Status'  => NULL,
                'GradeA'  => NULL,
                'GradeB'  => NULL,
                'GradeC'  => NULL,
                'Question'  => NULL,
            );

            if ($classroom['cr_pretest']){
                $cr_pretest = $classroom['cr_pretest'];
                $json_cr_pretest = preg_replace('/[[:cntrl:]]/', '', $cr_pretest);
                $extra_pretest = json_decode($json_cr_pretest,TRUE);
            }

            $data['request']   = $extra_pretest;

            $get_soal = NULL;
            $param_query['filter_status'] = 'publish';
            $param_query['filter_ids'] = array();
            if (isset($extra_pretest['Question'])){
                if (!empty($extra_pretest['Question'])){
                    $param_query['filter_ids'] = explode(',',$extra_pretest['Question']);
                }
            }

            if (empty($param_query['filter_ids'])){
                $get_soal = NULL;
            }else{
                $get_soal = $this->classroom_soal_model->get_all(NULL,NULL,NULL,$param_query);
                if ($get_soal){
                    $get_soal = $get_soal['data'];
                }
            }

            $data['soal'] = $get_soal;

            //print_r($data['pretest']);
            $data['form_action']    = site_url('classroom/pretest/'.$classroom_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_pretest_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_pretest']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];
            $arr_data['TimeLimit']  = $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];

            $arr_data['Attemp']     =  $post['Attemp'];
            $arr_data['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['ReqPassed']  =  $post['ReqPassed'];
            $arr_data['Random']     =  $post['Random'];
            $arr_data['Status']     =  "";


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_pretest' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Pre Test');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function pretest_add_soal($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_pretest']){
            $json_cr_pretest = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_pretest']);
            $extra_pretest = json_decode($json_cr_pretest,TRUE);
        }

        $existing_question = explode(',',$extra_pretest['Question']);

        $post = $this->input->post();
        $new_question = $post['crs_ids'];

        $question = array_unique (array_merge ($existing_question, $new_question));

        $extra_pretest['Question'] =  implode(',',$question);
        $json_data = json_encode($extra_pretest, JSON_UNESCAPED_SLASHES);

        //print_r($extra_pretest);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_pretest' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Tambah','Soal Pre Test');

        echo json_encode(
            array('succ'=>sizeof($new_question))
        );
    }

    function pretest_remove_soal($classroom_id=NULL,$question_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_pretest']){
            $json_cr_pretest = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_pretest']);
            $extra_pretest = json_decode($json_cr_pretest,TRUE);
        }

        $existing_question = explode(',',$extra_pretest['Question']);

        if (($key = array_search($question_id, $existing_question)) !== false) {
            unset($existing_question[$key]);
        }


        $extra_pretest['Question'] =  implode(',',$existing_question);
        $json_data = json_encode($extra_pretest, JSON_UNESCAPED_SLASHES);

        //print_r($extra_pretest);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_pretest' => $json_data,
        );
        $this->classroom_model->update($data);

        create_log($this->section_id,$classroom_id,'Hapus','Soal Pre Test');

        redirect(site_url('classroom/pretest/'.$classroom_id.'#soal'));
    }


    // Pengumuman
    function pengumuman($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/pengumuman/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('classroom/pengumuman/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_pengumuman_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_lp' => $post['Desc'],
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Pengumuman');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }


    // Rencana
    function rencana($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/rencana/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('classroom/rencana/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_rencana_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);


        }else{

            $post = $this->input->post();

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_rp' => $post['Desc'],
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Rencana');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }


    // Modul
    function module($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'Desc'       => NULL,
                'Module'  => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_module_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);


            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }



    }

    function module_update_tree($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }

        if ($classroom['cr_module']){
            $cr_module = $classroom['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        //print_r($extra_module);exit();

        $module_update = array();

        $post   = $this->input->post();
        $order = json_decode($post['order'],TRUE);

        foreach ($order as $k => $v){
            //$module_update[$v] = $extra_module['Module'][$v];

            // Reset Array
            $module_update[] = $extra_module['Module'][$v];
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        //print_r($materi_update);exit();
        $arr_data['Module']  = $module_update;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_module' => $json_data,
        );

        $update = $this->classroom_model->update($data);


        // Update Urutan Hasil Modul yang telah disubmit
        $member = $this->classroom_member_model->get_by_classroom($classroom_id);
        if ($member){
            foreach ($member as $k => $v){

                if ($v['crm_step']){

                    $jsonCrmStep = preg_replace('/[[:cntrl:]]/', '', $v['crm_step']);
                    $crm_step = json_decode($jsonCrmStep,TRUE);

                    if (isset($crm_step['MP'])){
                        $current_mp = $crm_step['MP'];

                        $updated_mp = array();
                        foreach ($order as $i => $j){

                            $updated_mp[$j] = $current_mp[$j];

                        }

                        $crm_step['MP'] = $updated_mp;

                        $json_data_crm_step = json_encode($crm_step, JSON_UNESCAPED_SLASHES);
                        $data_member = array(
                            'crm_id'    => $v['crm_id'],
                            'crm_step'  => $json_data_crm_step
                        );
                        //print_r($data_member);
                        $this->classroom_member_model->update($data_member);

                    }

                }

            }
        }
        //exit();



        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Edit','Modul - Urutan');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }

    }

    function module_add($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'ModuleName'    => NULL,
                'ModuleLinkZoom'    => NULL,
                'ModuleStart'  => NULL,
                'ModuleEnd'  => NULL,
            );

            $data['request']   = $extra_module;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_add/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_module_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();
            $new_module = array(
                'ModuleName'    => $post['ModuleName'],
                'ModuleLinkZoom'    => $post['ModuleLinkZoom'],
                'ModuleStart'   => parseDate($post['ModuleStart']),
                'ModuleEnd'     => parseDate($post['ModuleEnd']),
                'Materi'        => array(),
                'Quiz'          => array(),
                'Evaluasi'      => array(
                    'Desc' => NULL,
                    'TimeLimit'  => NULL,
                    'Attemp'     => NULL,
                    'QuePerPage' => NULL,
                    'ReqPassed'  => NULL,
                    'Random'  => NULL,
                    'Status'  => 'non-active',
                    'GradeA'  => NULL,
                    'GradeB'  => NULL,
                    'GradeC'  => NULL,
                    'Question'=> NULL,
                ),
                'Feedback'      => array(
                    'Status'  => 'non-active',
                    'Desc'    => NULL,
                    'Type'    => array(),
                    'Question'=> array(),
                ),
                'LearningPoint'      => array(
                    'Status'  => 'non-active',
                ),
            );

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            if (!isset($arr_data['Desc'])){
                $arr_data['Desc'] = '';
            }

            $arr_data['Module'][] = $new_module;

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_edit($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'ModuleName'    => NULL,
                'ModuleLinkZoom'    => NULL,
                'ModuleStart'   => NULL,
                'ModuleEnd'     => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index];

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_edit/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_module_edit_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['ModuleName']  = $post['ModuleName'];
            $arr_data['Module'][$index]['ModuleLinkZoom']  = $post['ModuleLinkZoom'];
            $arr_data['Module'][$index]['ModuleStart'] = parseDate($post['ModuleStart']);
            $arr_data['Module'][$index]['ModuleEnd']   = parseDate($post['ModuleEnd']);


            // Add New Learning Point Setting
            if(!isset($arr_data['Module'][$index]['LearningPoint'])){
                $arr_data['Module'][$index]['LearningPoint'] = array(
                    'Status'  => 'non-active',
                );
            }


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_delete($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }

        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        unset($arr_data['Module'][$index]);

        // Reset Array
        $arr_data_module_new = array();
        if(isset($arr_data['Module'])){
            foreach ($arr_data['Module'] as $k => $v){
                $arr_data_module_new[] = $v;
            }
        }
        $arr_data['Module'] = $arr_data_module_new;


        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_module' => $json_data,
        );

        $update = $this->classroom_model->update($data);


        // Hapus Hasil Modul yang telah disubmit
       /* $member = $this->classroom_member_model->get_by_classroom($classroom_id);
        if ($member){
            foreach ($member as $k => $v){

                if ($v['crm_step']){

                    $jsonCrmStep = preg_replace('/[[:cntrl:]]/', '', $v['crm_step']);
                    $crm_step = json_decode($jsonCrmStep,TRUE);

                    if (isset($crm_step['MP'])){
                        $current_mp = $crm_step['MP'];

                        unset($crm_step['MP'][$index]);

                        $json_data_crm_step = json_encode($crm_step, JSON_UNESCAPED_SLASHES);
                        $data_member = array(
                            'crm_id'    => $v['crm_id'],
                            'crm_step'  => $json_data_crm_step
                        );
                        //print_r($data_member);
                        $this->classroom_member_model->update($data_member);

                    }

                }

            }
        }*/



        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Hapus','Modul');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }
    }


    // Modul Materi
    function module_materi($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module/').$classroom_id;
        }


        if ($classroom['cr_module']){
            $cr_module = $classroom['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $data['request']   = $extra_module['Module'][$index];
        $data['module_id'] = $index;

        //print_r($data['module']);
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
        $data['page'] = 'classroom/classroom_module_materi_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function module_materi_update_tree($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_materi/').$classroom_id.'/'.$index;
        }

        if ($classroom['cr_module']){
            $cr_module = $classroom['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }


        $materi   = $extra_module['Module'][$index]['Materi'];
        $materi_update = array();

        $post   = $this->input->post();
        $order = json_decode($post['order'],TRUE);

        foreach ($order as $k => $v){
            //$materi_update[$v] = $materi[$v];
            $materi_update[] = $materi[$v];
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        //print_r($materi_update);exit();
        $arr_data['Module'][$index]['Materi']  = $materi_update;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_module' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Edit','Materi Modul - Urutan');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }

    }

    function module_materi_add($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_materi/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_materi = array(
                'ContentName'    => NULL,
                'Type'   => NULL,
                'Media'     => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_materi;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_materi_add/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_materi_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $media = isset($post['Media'])?$post['Media']:'';

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;

                $file_folder = $post['Type']=='document'?'document':'video';
                $full_folder = $upload_folder.$file_folder;


                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                if ($post['Type']=='document'){
                    $config['allowed_types'] = 'xlsx|xls|doc|docx|ppt|pptx|txt|pdf';
                }else{
                    $config['allowed_types'] = 'mpeg|mpg|mp4|mpe|qt|mov|avi';
                }

                $config['max_size']      = '50000';

                //print_r($config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $media = $filename_system;

                    $go_upload = $this->upload->data();
                    $data_media = array(
                        'section_id'    => 30,
                        'data_id'       => 0,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => $post['Type']=='document'?'document':'video',
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
                    //$notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

                //print_r($notif);

            }
            // End Handle File

            $materi = array(
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
                'Status'     => 'active',
            );
            $arr_data['Module'][$index]['Materi'][]  = $materi;

            //print_r($arr_data);exit();
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Materi Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_materi_add_digital_library($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);

        $post   = $this->input->post();
        $content_id = $post['id'];

        $this->load->model('content_model');
        $content = $this->content_model->get($content_id,35);
        if (!$content){
            echo json_encode(['status'=>400,'message'=>'Konten tidak ditemukan']);exit();
        }


        $get_media_document = $this->media_model->get_by_section_data_file_only(35,$content_id);
        //print_r($get_media_document);
        $type   = $get_media_document?$get_media_document['media_type']:NULL;
        $media  = $get_media_document?$get_media_document['media_value']:NULL;

        if (empty($media)){
            echo json_encode(['status'=>400,'message'=>'Media tidak ditemukan']);exit();
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        $materi = array(
            'Type'          => $type,
            'ContentId'     => $content['content_id'],
            'ContentName'   => $content['content_name'],
            'Media'         => $media,
            'Status'     => 'active',
        );
        $arr_data['Module'][$index]['Materi'][]  = $materi;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_module' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Tambah','Materi Modul');
            echo json_encode(['status'=>200,'message'=>'Materi berhasil ditambahkan']);exit();
        }else{
            echo json_encode(['status'=>400,'message'=>'Materi gagal ditambahkan']);exit();
        }

    }

    function module_materi_edit($classroom_id=NULL,$index,$materi_id){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_materi/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_materi = array(
                'ContentName'    => NULL,
                'Type'   => NULL,
                'Media'     => NULL,
                'Status'     => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index]['Materi'][$materi_id];

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_materi_edit/'.$classroom_id.'/'.$index.'/'.$materi_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_materi_edit_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $media = isset($post['Media'])?$post['Media'] : $arr_data['Module'][$index]['Materi'][$materi_id]['Media'];

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;

                $file_folder = $post['Type']=='document'?'document':'video';
                $full_folder = $upload_folder.$file_folder;


                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                if ($post['Type']=='document'){
                    $config['allowed_types'] = 'xlsx|xls|doc|docx|ppt|pptx|txt|pdf';
                }else{
                    $config['allowed_types'] = 'mpeg|mpg|mp4|mpe|qt|mov|avi';
                }

                $config['max_size']      = '50000';

                //print_r($config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $media = $filename_system;

                    $go_upload = $this->upload->data();
                    $data_media = array(
                        'section_id'    => 30,
                        'data_id'       => 0,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => $post['Type']=='document'?'document':'video',
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
                    //$notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

                //print_r($notif);

            }
            // End Handle File

            $materi = array(
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
                'Status'  => $post['Status'],
            );
            $arr_data['Module'][$index]['Materi'][$materi_id]  = $materi;

            //print_r($arr_data);exit();
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Materi Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_materi_delete($classroom_id=NULL,$index,$materi_id){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_materi = $extra_module['Module'][$index]['Materi'];
        unset($existing_materi[$materi_id]);

        //print_r($existing_materi);exit();

        $existing_materi_new = array();
        if(isset($existing_materi)){
            foreach ($existing_materi as $k => $v){
                $existing_materi_new[] = $v;
            }
        }

        $extra_module['Module'][$index]['Materi'] =  $existing_materi_new;
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_module' => $json_data,
        );
        //print_r($data);exit();
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Materi Modul');
        redirect(site_url('classroom/module_materi/').$classroom_id.'/'.$index);
    }


    // Modul Evaluasi
    function module_evaluasi($classroom_id=NULL,$index){
        //$index = $index;
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_evaluasi/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_pretest = array(
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'     => NULL,
                'Status'     => NULL,
                'GradeA'     => NULL,
                'GradeB'     => NULL,
                'GradeC'     => NULL,
                'Question'   => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index]['Evaluasi'];

            $get_soal = NULL;
            $param_query['filter_status'] = 'publish';
            $param_query['filter_ids'] = array();
            if (isset($extra_module['Module'][$index]['Evaluasi']['Question'])){
                if (!empty($extra_module['Module'][$index]['Evaluasi']['Question'])){
                    $param_query['filter_ids'] = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);
                }
            }

            if (empty($param_query['filter_ids'])){
                $get_soal = NULL;
            }else{
                $get_soal = $this->classroom_soal_model->get_all(NULL,NULL,NULL,$param_query);
                if ($get_soal){
                    $get_soal = $get_soal['data'];
                }
            }

            $data['soal'] = $get_soal;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_evaluasi/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_evaluasi_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Evaluasi']['Desc']       =  $post['Desc'];
            $arr_data['Module'][$index]['Evaluasi']['TimeLimit']  = $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];
            $arr_data['Module'][$index]['Evaluasi']['Attemp']     =  $post['Attemp'];
            $arr_data['Module'][$index]['Evaluasi']['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['Module'][$index]['Evaluasi']['ReqPassed']  =  $post['ReqPassed'];
            $arr_data['Module'][$index]['Evaluasi']['Random']     =  $post['Random'];
            $arr_data['Module'][$index]['Evaluasi']['Status']     =  $post['Status'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Evaluasi Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_evaluasi_add_soal($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_question = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);

        $post = $this->input->post();
        $new_question = $post['crs_ids'];

        $question = array_unique (array_merge ($existing_question, $new_question));

        $extra_module['Module'][$index]['Evaluasi']['Question'] =  implode(',',$question);
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);exit();

        $data = array(
            'cr_id' => $classroom_id,
            'cr_module' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Tambah','Soal Evaluasi Modul');
        echo json_encode(
            array('succ'=>sizeof($new_question))
        );
    }

    function module_evaluasi_remove_soal($classroom_id=NULL,$index,$question_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_question = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);

        if (($key = array_search($question_id, $existing_question)) !== false) {
            unset($existing_question[$key]);
        }


        $extra_module['Module'][$index]['Evaluasi']['Question'] =  implode(',',$existing_question);
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_module' => $json_data,
        );
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Soal Evaluasi Modul');
        redirect(site_url('classroom/module_evaluasi/').$classroom_id.'/'.$index.'#soal');
    }

    // Modul Learning Point
    function module_learningpoint($classroom_id=NULL,$index){
        //$index = $index;
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_learningpoint/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'Status'   => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = isset($extra_module['Module'][$index]['LearningPoint']) ? $extra_module['Module'][$index]['LearningPoint'] : array('Status' => NULL,);
            $data['module_id']   = $index;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_learningpoint/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_learningpoint_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['LearningPoint']['Status']     =  $post['Status'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Learning Point');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    // Modul Feedback
    function module_feedback($classroom_id=NULL,$index){
        //$index = $index;
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_feedback/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'Desc'     => NULL,
                'Status'   => NULL,
                'Type'     => [],
                'Question' => [],
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index]['Feedback'];
            $data['module_id']   = $index;

            //print_r($data['module']);
            $data['form_action']    = site_url('classroom/module_feedback/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_feedback_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Desc']       =  $post['Desc'];
            $arr_data['Module'][$index]['Feedback']['Status']     =  $post['Status'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Feedback');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_feedback_add($classroom_id=NULL,$index){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_feedback/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Type'      => NULL,
                'Question'  => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_question;
            $data['module_id']   = $index;

            //print_r($data['question']);
            $data['form_action']    = site_url('classroom/module_feedback_add/'.$classroom_id.'/'.$index);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Clsssroom';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_feedback_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Type'][] = $post['Type'];
            $arr_data['Module'][$index]['Feedback']['Question'][] = $post['Question'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'       => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Pertanyaan Feedback Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function module_feedback_edit($classroom_id=NULL,$index,$question_id){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/module_feedback/').$classroom_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Question'  => NULL,
                'Type'     => NULL,
            );

            if ($classroom['cr_module']){
                $cr_module = $classroom['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']        = $extra_module['Module'][$index]['Feedback'];
            $data['module_id']      = $index;
            $data['question_id']    = $question_id;

            //print_r($data['question']);
            $data['form_action']    = site_url('classroom/module_feedback_edit/'.$classroom_id.'/'.$index.'/'.$question_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Classroom';
            $data['page_sub_name']  = $classroom['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'classroom/classroom_module_feedback_edit_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Question'][$question_id] = $post['Question'];
            $arr_data['Module'][$index]['Feedback']['Type'][$question_id] = $post['Type'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'         => $post['cr_id'],
                'cr_module'   => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Pertanyaan Feedback Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function module_feedback_delete($classroom_id=NULL,$index,$question_id){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $extra_data = json_decode($json_cr_module,TRUE);
        }

        $existing_data = $extra_data;
        unset($existing_data['Module'][$index]['Feedback']['Question'][$question_id]);
        unset($existing_data['Module'][$index]['Feedback']['Type'][$question_id]);

        //print_r($existing_data);exit();

        $extra_data =  $existing_data;
        $json_data = json_encode($extra_data, JSON_UNESCAPED_SLASHES);

        //print_r($extra_data);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_module' => $json_data,
        );
        //print_r($data);exit();
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Pertanyaan Feedback Modul');
        flash_notif_success('Data berhasil dihapus',NULL,FALSE);
        redirect(site_url('classroom/module_feedback/').$classroom_id.'/'.$index);
    }


    // Kompetensi
    function competency($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/competency/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_competency = array(
                'ctStart'    => NULL,
                'ctEnd'      => NULL,
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'     => NULL,
                'Status'     => NULL,
                'GradeA'     => NULL,
                'GradeB'     => NULL,
                'GradeC'     => NULL,
                'GradeD'     => NULL,
                'Question'   => NULL,
            );

            if ($classroom['cr_competency']){
                $cr_competency = $classroom['cr_competency'];
                $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $cr_competency);
                $extra_competency = json_decode($json_cr_competency,TRUE);
            }

            $data['request']   = $extra_competency;

            $get_soal = NULL;
            $param_query['filter_status'] = 'publish';
            $param_query['filter_ids'] = array();
            if (isset($extra_competency['Question'])){
                if (!empty($extra_competency['Question'])){
                    $param_query['filter_ids'] = explode(',',$extra_competency['Question']);
                }
            }

            if (empty($param_query['filter_ids'])){
                $get_soal = NULL;
            }else{
                $get_soal = $this->classroom_soal_model->get_all(NULL,NULL,NULL,$param_query);
                if ($get_soal){
                    $get_soal = $get_soal['data'];
                }
            }

            $data['soal'] = $get_soal;

            //print_r($data['request']);
            $data['form_action']    = site_url('classroom/competency/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_competency_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_competency']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];
            $arr_data['TimeLimit']  = $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];

            $arr_data['Attemp']     =  NULL; // Dihilangkan
            $arr_data['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['ReqPassed']  =  NULL; // Dihilangkan
            $arr_data['Random']     =  $post['Random'];
            $arr_data['GradeA']     =  $post['GradeA'];
            $arr_data['GradeB']     =  $post['GradeB'];
            $arr_data['GradeC']     =  $post['GradeC'];
            $arr_data['GradeD']     =  $post['GradeD'];

            $arr_data['ctStart']    =  parseDate($post['ctStart']);
            $arr_data['ctEnd']      =  parseDate($post['ctEnd']);
            $arr_data['Status']     =  "";


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_competency' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Kompetensi');

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }


    }

    function competency_add_soal($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_competency']){
            $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_competency']);
            $extra_competency = json_decode($json_cr_competency,TRUE);
        }

        $existing_question = explode(',',$extra_competency['Question']);

        $post = $this->input->post();
        $new_question = $post['crs_ids'];

        $question = array_unique (array_merge ($existing_question, $new_question));

        $extra_competency['Question'] =  implode(',',$question);
        $json_data = json_encode($extra_competency, JSON_UNESCAPED_SLASHES);

        //print_r($extra_competency);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_competency' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Tambah','Soal Kompetensi');
        echo json_encode(
            array('succ'=>sizeof($new_question))
        );
    }

    function competency_remove_soal($classroom_id=NULL,$question_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_competency']){
            $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_competency']);
            $extra_competency = json_decode($json_cr_competency,TRUE);
        }

        $existing_question = explode(',',$extra_competency['Question']);

        if (($key = array_search($question_id, $existing_question)) !== false) {
            unset($existing_question[$key]);
        }


        $extra_competency['Question'] =  implode(',',$existing_question);
        $json_data = json_encode($extra_competency, JSON_UNESCAPED_SLASHES);

        //print_r($extra_competency);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_competency' => $json_data,
        );
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Soal Kompetensi');
        redirect(site_url('classroom/competency/'.$classroom_id.'#soal'));
    }


    // Sertifikat
    function certificate($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/certificate/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_certificate = array(
                'Logo'      => NULL,
                'Signature' => NULL,
                'Text1'     => NULL,
                'Text2'     => NULL,
                'Text3'     => NULL,
                'Text4'     => NULL,
                'Text5'     => NULL,
                'Text6'     => NULL,
                'serType'   => NULL,
                'noStart'   => NULL,
                'noEnd'     => NULL,
                'noUsed'    => NULL,
                'serCode'   => NULL,
            );

            if ($classroom['cr_certificate']){
                $cr_certificate = $classroom['cr_certificate'];
                $json_cr_certificate = preg_replace('/[[:cntrl:]]/', '', $cr_certificate);
                $extra_certificate = json_decode($json_cr_certificate,TRUE);
            }

            $data['request']   = $extra_certificate;

            //print_r($data['request']);
            $data['form_action']    = site_url('classroom/certificate/'.$classroom_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page']    = 'classroom/classroom_certificate_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_certificate']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Text1']       =  $post['Text1'];
            $arr_data['Text2']       =  $post['Text2'];
            $arr_data['Text3']       =  $post['Text3'];
            $arr_data['Text4']       =  $post['Text4'];
            $arr_data['Text5']       =  $post['Text5'];
            $arr_data['Text6']       =  $post['Text6'];


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['Logo']['name'])){

                $_FILES['file']['name'] = $_FILES['Logo']['name'];
                $_FILES['file']['type'] = $_FILES['Logo']['type'];
                $_FILES['file']['tmp_name'] = $_FILES['Logo']['tmp_name'];
                $_FILES['file']['error'] = $_FILES['Logo']['error'];
                $_FILES['file']['size'] = $_FILES['Logo']['size'];

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

                $config['allowed_types'] = 'png';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){
                    $arr_data['Logo']       =  $filename_system;
                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['Signature']['name'])){

                $_FILES['file']['name'] = $_FILES['Signature']['name'];
                $_FILES['file']['type'] = $_FILES['Signature']['type'];
                $_FILES['file']['tmp_name'] = $_FILES['Signature']['tmp_name'];
                $_FILES['file']['error'] = $_FILES['Signature']['error'];
                $_FILES['file']['size'] = $_FILES['Signature']['size'];

                // Config File Name
                $filename_origin  = $_FILES['Signature']['name'];
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

                $config['allowed_types'] = 'png';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){
                    $arr_data['Signature']       =  $filename_system;
                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_certificate' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Sertifikat');

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }



    }


    // Feedback
    function feedback($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom']        = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/feedback/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_feedback = array(
                'Desc'      => NULL,
                'Status'    => NULL,
                'Type'      => NULL,
                'Question'  => NULL,
            );

            if ($classroom['cr_feedback']){
                $cr_feedback = $classroom['cr_feedback'];
                $json_cr_feedback = preg_replace('/[[:cntrl:]]/', '', $cr_feedback);
                $extra_feedback = json_decode($json_cr_feedback,TRUE);
            }

            $data['request']   = $extra_feedback;

            $data['form_action']   = site_url('classroom/feedback/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']     = 'Class Room';
            $data['page_sub_name'] = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_feedback_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_feedback']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_feedback' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Feedback');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }


    }

    function feedback_add($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/feedback/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Type'      => NULL,
                'Question'  => NULL,
            );

            $data['request']   = $extra_question;

            //print_r($data['question']);
            $data['form_action']    = site_url('classroom/feedback_add/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Clsssroom';
            $data['page_sub_name']  = 'Tambah Feedback'.'<br><small>'.$classroom['cr_name'].'</small>';
            $data['page'] = 'classroom/classroom_feedback_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_feedback']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Type'][] = $post['Type'];
            $arr_data['Question'][] = $post['Question'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'       => $post['cr_id'],
                'cr_feedback' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Tambah','Pertanyaan Feedback');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function feedback_edit($classroom_id=NULL,$index){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/feedback/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Question'  => NULL,
                'Type'     => NULL,
            );

            if ($classroom['cr_feedback']){
                $cr_feedback = $classroom['cr_feedback'];
                $json_cr_feedback = preg_replace('/[[:cntrl:]]/', '', $cr_feedback);
                $extra_question = json_decode($json_cr_feedback,TRUE);
            }

            $data['request']   = $extra_question;
            $data['index']     = $index;

            //print_r($data['question']);
            $data['form_action']    = site_url('classroom/feedback_edit/'.$classroom_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'classroom';
            $data['page_sub_name']  = 'Edit Pertanyaan'.'<br><small>'.$classroom['cr_name'].'</small>';
            $data['page'] = 'classroom/classroom_feedback_edit_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_feedback']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Question'][$index] = $post['Question'];
            $arr_data['Type'][$index] = $post['Type'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'         => $post['cr_id'],
                'cr_feedback'   => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Pertanyaan Feedback');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function feedback_delete($classroom_id=NULL,$index){
        $classroom = $this->get_classroom($classroom_id);
        if ($classroom['cr_feedback']){
            $json_cr_feedback = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_feedback']);
            $extra_data = json_decode($json_cr_feedback,TRUE);
        }

        $existing_data = $extra_data;
        unset($existing_data['Question'][$index]);
        unset($existing_data['Type'][$index]);

        //print_r($existing_materi);exit();

        $extra_data =  $existing_data;
        $json_data = json_encode($extra_data, JSON_UNESCAPED_SLASHES);

        //print_r($extra_data);

        $data = array(
            'cr_id' => $classroom_id,
            'cr_feedback' => $json_data,
        );
        //print_r($data);exit();
        $this->classroom_model->update($data);
        create_log($this->section_id,$classroom_id,'Hapus','Pertanyaan Feedback');
        flash_notif_success('Data berhasil dihapus',NULL,FALSE);
        redirect(site_url('classroom/feedback/').$classroom_id);
    }

    function feedback_export($classroom_id=NULL){


        $classroom = $this->get_classroom($classroom_id);

        $questions = array();
        $column_feedback_length = 0;
        if ($classroom['cr_feedback']){
            $cr_feedback = $classroom['cr_feedback'];
            $json_cr_feedback = preg_replace('/[[:cntrl:]]/', '', $cr_feedback);
            $extra_question = json_decode($json_cr_feedback,TRUE);

            if (isset($extra_question['Question'])){
                $questions = $extra_question['Question'];
                $column_feedback_length = sizeof($extra_question['Question'])>0 ? sizeof($extra_question['Question'])-1 : 0;
            }
        }

        //print_r($extra_question);exit();

        $member = $this->classroom_member_model->get_by_classroom($classroom_id);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $alpha = range('A', 'ZZ');

        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'No');

        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('B1', 'Nama');

        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('C1', 'NIP');

        $sheet->mergeCells('D1:D2');
        $sheet->setCellValue('D1', 'Group');

        $sheet->mergeCells('E1:'.$alpha[4+$column_feedback_length].'1');
        $sheet->setCellValue('E1', 'Feedback');

        $col_fb_index = 4;
        foreach ($questions as $v){
            $sheet->setCellValue($alpha[$col_fb_index].'2', $v);
            $col_fb_index++;
        }


        $no = 1;
        $x = 3;

        if ($member){

            foreach($member as $row) {

                $member_crm_fb = preg_replace('/[[:cntrl:]]/', '', $row['crm_fb']);
                $result = json_decode($member_crm_fb,TRUE);


                $sheet->setCellValue('A'.$x, $no++);
                $sheet->setCellValue('B'.$x, $row['member_name']);
                $sheet->setCellValue('C'.$x, (string)$row['member_nip']);
                $sheet->setCellValue('D'.$x, $row['group_name']);

                if ($result){
                    $col_fb_index_val = 4;
                    foreach ($result as $v){
                        $sheet->setCellValue($alpha[$col_fb_index_val].$x, $v );
                        $col_fb_index_val++;
                    }
                }


                $x++;
            }

        }


        create_log($this->section_id,$classroom_id,'Export','Feedback');
        $writer = new Xlsx($spreadsheet);
        $filename = 'Feedback_classroom_'.slugify($classroom['cr_name']);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

    }


    // Anggota

    function member_modal_ajax($classroom_id){
        $classroom = $this->get_classroom($classroom_id);
        $data['classroom'] = $classroom;
        $this->load->view('classroom/classroom_member_list_picker_modal_view',$data);
    }

    function member_json($classroom_id){
        $classroom = $this->get_classroom($classroom_id);
        $get_member         = $this->classroom_member_model->get_by_classroom($classroom_id);

        foreach ($get_member as $k => $v){
            $result['data'][] = array(
                'member_id'        => $v['member_id'],
                'member_name'        => $v['member_name'],
                'group_name'        => $v['group_name'],
                'member_nip'      => $v['member_nip']
            );
        }
        echo json_encode($result);
    }

    function member($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['member']         = $this->classroom_member_model->get_by_classroom($classroom_id);
        $data['member_count']   = $this->classroom_member_model->count_by_classroom($classroom_id);
        //print_r($data);
        $data['classroom']      = $classroom;
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'];
        $data['page'] = 'classroom/classroom_member_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function member_add($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('classroom/member_add/'.$classroom_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_member_add_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $member_ids = $post['member_ids'];
            $classroom_id = $post['cr_id'];

            foreach ($member_ids as $v){
                $member_id = $v;
                $get_member = $this->classroom_member_model->get_by_classroom_member($classroom_id,$member_id);
                if ($get_member){
                    // Member Exist Then Skip

                }else{
                    $data = array(
                        'cr_id'     => $classroom_id,
                        'member_id' => $member_id,
                    );
                    $insert_member = $this->classroom_member_model->insert($data);
                }
            }
            create_log($this->section_id,$classroom_id,'Tambah','Member');
            $url_return = site_url('classroom/member/').$classroom_id;
            flash_notif_success(NULL,$url_return);

        }
    }

    function member_add_picker($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $post = $this->input->post();
        $member_ids = $post['member_ids'];

        foreach ($member_ids as $v){
            $member_id = $v;
            $get_member = $this->classroom_member_model->get_by_classroom_member($classroom_id,$member_id);
            if ($get_member){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'     => $classroom_id,
                    'member_id' => $member_id,
                );
                $this->classroom_member_model->insert($data);
                create_log($this->section_id,$classroom_id,'Tambah','Member');
            }
        }

        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );
    }

    function member_remove($classroom_id=NULL,$crm_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        $this->classroom_member_model->delete($crm_id);
        create_log($this->section_id,$classroom_id,'Hapus','Member');
        redirect(site_url('classroom/member/'.$classroom_id));
    }


    function member_aghris_search($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        $data['classroom'] = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('member');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('search_by', 'Cari berdasarkan', 'required|trim');
        $this->form_validation->set_rules('keyword', 'Kata kunci', 'required|trim');

        $data['form_opt_search_by'] = array(
            'nik'       => 'NIK',
            'name'      => 'Nama',
            'jabatan'   => 'Jabatan',
            'nohp'      => 'No.Handphone',
        );

        if ($this->form_validation->run() == FALSE){


            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Class Room <br><small>'.$classroom['cr_name'].'</small>';
            $data['page_sub_name']  = 'Cari & Tambah Member dari Aghris';

            $data['form_action']    = site_url('classroom/member_aghris_search/'.$classroom_id);
            $data['page']           = 'classroom/classroom_member_form_aghris_search_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $search_by = $post['search_by'];
            $keyword   = $post['keyword'];

            $result = array();
            if ($search_by=='nik'){
                $result = aghris_search_by_nik($keyword);
            }else if ($search_by=='name'){
                $result = aghris_search_by_name($keyword);
            }else if ($search_by=='jabatan'){
                $result = aghris_search_by_jabatan($keyword);
            }else if ($search_by=='nohp'){
                $result = aghris_search_by_nohp($keyword);
            }

            $data['search_by']  = $search_by;
            $data['keyword']    = $keyword;
            $data['result']     = $result;


            $data['page_name']      = 'Class Room <br><small>'.$classroom['cr_name'].'</small>';
            $data['page_sub_name']  = 'Cari & Tambah Member dari Aghris';

            $data['form_action']    = site_url('classroom/member_aghris_search/'.$classroom_id);
            $data['page']           = 'classroom/classroom_member_form_aghris_search_view';

            $this->load->view('main_view',$data);

        }
    }

    function member_aghris_sync($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $post = $this->input->post();

        $update_existing_data = isset($post['update_existing_data']) ? $post['update_existing_data'] : NULL;
        $raw_data_json = parseInputNull($post['raw_data_json']);

        $data_notif = array();
        $data_member_ids = array();
        if ($raw_data_json){
            $data_raw = json_decode($raw_data_json, TRUE);

            $data_member = array();
            foreach ($data_raw as $k => $v){

                // Sync Jabatan
                $get_jabatan = $this->jabatan_model->get_by_code($data_raw[$k][7]);
                if ($get_jabatan){
                    $jabatan_id = $get_jabatan['jabatan_id'];
                }else{
                    $data_jabatan = [
                        'jabatan_name'  => $data_raw[$k][3]?$data_raw[$k][3]:'-',
                        'jabatan_code'  => $data_raw[$k][7],
                        'jabatan_level' => 9
                    ];
                    $jabatan_id =  $this->jabatan_model->insert($data_jabatan);
                }


                // Sync Member
                $data_member = array(
                    'group_id'          => $data_raw[$k][5],
                    'jabatan_id'        => $jabatan_id,
                    'mlevel_id'         => $get_jabatan?$get_jabatan['jabatan_level']:6,
                    'member_name'       => $data_raw[$k][0],
                    'member_nip'        => $data_raw[$k][2],
                    'member_token'      => $data_raw[$k][8],
                    'member_jabatan'    => is_null($data_raw[$k][3])?'':$data_raw[$k][3],
                    'member_email'      => $data_raw[$k][20],
                    'member_kel_jabatan'=> is_null($data_raw[$k][21])?'':$data_raw[$k][21],
                    'member_image'      => $data_raw[$k][9]=='#' ? '' : $data_raw[$k][9],
                    'member_unit_kerja' => is_null($data_raw[$k][10])?'':$data_raw[$k][10],
                    'member_gender'     => ($data_raw[$k][11]=='1')?'Pria':'Wanita',
                    'member_birth_place' => $data_raw[$k][12],
                    'member_birth_date' => $data_raw[$k][13],
                    'member_phone'      => $data_raw[$k][4],
                    'member_address'    => is_null($data_raw[$k][14])?'':$data_raw[$k][10],
                    'member_city'       => is_null($data_raw[$k][15])?'':$data_raw[$k][15],
                    'member_province'   => is_null($data_raw[$k][16])?'':$data_raw[$k][16],
                    'member_postcode'   => is_null($data_raw[$k][17])?'':$data_raw[$k][17],
                    'member_ceo'        => $data_raw[$k][18],
                    'member_create_date'=> $data_raw[$k][19]
                );


                // Find Existing by NIP
                $get_member_by_nip = $this->member_model->get_by_nip($data_member['member_nip']);
                if ($get_member_by_nip){

                    if ($update_existing_data){
                        $data_member['member_id'] = $get_member_by_nip['member_id'];
                        unset($data_member['member_create_date']);
                        $this->member_model->update($data_member);

                        $data_notif[] = array(
                            'member_id'   => $get_member_by_nip['member_id'],
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message' => 'Data member di-update. Member ditambahkan sebagai peserta.',
                        );
                    }else{
                        $data_notif[] = array(
                            'member_id'   => $get_member_by_nip['member_id'],
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message' => 'Update dilewati. Member ditambahkan sebagai peserta.'
                        );
                    }

                    $data_member_ids[] =  $get_member_by_nip['member_id'];

                }else{
                    // Insert
                    $data_member['member_password'] = md5(trim($data_member['member_nip']));
                    $data_member['member_status']   = 'active';
                    $data_member['member_poin']     = 0;
                    $data_member['member_saldo']    = 0;
                    $create_member = $this->member_model->insert($data_member);
                    if ($create_member){
                        $data_notif[] = array(
                            'member_id'   => $create_member,
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'success',
                            'status_message' => 'Member baru berhasil dibuat. Member ditambahkan sebagai peserta.'
                        );

                        $data_member_ids[] =  $create_member;
                    }else{
                        $data_notif[] = array(
                            'member_id'   => "-",
                            'member_name' => $data_member['member_name'],
                            'member_nip'  => $data_member['member_nip'],
                            'status_code' => 'failed',
                            'status_message' => 'Gagal membuat member baru'
                        );
                    }
                }



            }

            //print_r($data_member);

        }else{
            $data_notif[] = array();
        }

        /* Start :: Add Member to Classroom Member*/
        $member_ids = $data_member_ids;
        $classroom_id = $classroom_id;

        foreach ($member_ids as $v){
            $member_id = $v;
            $get_member = $this->classroom_member_model->get_by_classroom_member($classroom_id,$member_id);
            if ($get_member){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'     => $classroom_id,
                    'member_id' => $member_id,
                );
                $insert_member = $this->classroom_member_model->insert($data);
            }
        }
        create_log($this->section_id,$classroom_id,'Tambah','Member');
        /* End :: Add Member to Classroom Member*/

        $data['notif'] = $data_notif;

        $data['classroom'] = $classroom;

        $data['page_name']      = 'Class Room <br><small>'.$classroom['cr_name'].'</small>';
        $data['page_sub_name']  = 'Cari & Tambah Member dari Aghris';

        $data['page']           = 'classroom/classroom_member_notif_aghris_sync_view';

        $this->load->view('main_view',$data);


    }



    // PROGRESS PESERTA
    function progress_member($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['member']         = $this->classroom_member_model->get_by_classroom($classroom_id);
        $data['member_count']   = $this->classroom_member_model->count_by_classroom($classroom_id);
        //print_r($data);
        $data['classroom']      = $classroom;
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'];
        $data['page'] = 'classroom/classroom_progress_member_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function progress_member_excel($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $member = $this->classroom_member_model->get_by_classroom($classroom_id);
        $member_count = $this->classroom_member_model->count_by_classroom($classroom_id);
        //print_r($member);exit();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $alpha = $this->createColumnsArray('ZZ');

        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'No');

        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('B1', 'Nama');

        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('C1', 'NIP');

        $sheet->mergeCells('D1:D2');
        $sheet->setCellValue('D1', 'Group');

        $sheet->mergeCells('E1:H1');
        $sheet->setCellValue('E1', 'Pre Test');

        $sheet->setCellValue('E2', 'Tanggal');
        $sheet->setCellValue('F2', 'Jml Soal');
        $sheet->setCellValue('G2', 'Benar');
        $sheet->setCellValue('H2', 'Salah');


        $module = array();
        if($classroom['cr_module']){

            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $cr_module = json_decode($json_cr_module,TRUE);

            if (isset($cr_module['Module'])){
                $module = $cr_module['Module'];
            }
        }

        $cur_alpha = 8;
        $col_length_eva = 4;
        $col_length_lp = 2;
        $col_length_fb = 3;
        $module_no = 1;
        foreach ($module as $k => $v){

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_eva-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Evaluasi Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Jml Soal');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Benar');
            $sheet->setCellValue($alpha[$cur_alpha+3].'2', 'Salah');

            $cur_alpha = $cur_alpha + $col_length_eva;

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_lp-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Learning Point Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Isi');

            $cur_alpha = $cur_alpha + $col_length_lp;

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_fb-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Feedback Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Nilai');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Komentar');

            $cur_alpha = $cur_alpha + $col_length_fb;

            $module_no++;
        }

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+3].'1');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Competency Test');

        $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
        $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Jml Soal');
        $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Benar');
        $sheet->setCellValue($alpha[$cur_alpha+3].'2', 'Salah');


        $cur_alpha = $cur_alpha + 4;

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha].'2');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Grade');

        $cur_alpha = $cur_alpha + 1;

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha].'2');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Nilai Akhir');

        $cur_alpha = $cur_alpha + 1;

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha].'2');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Knowledge Management');


        $no = 1;
        $x = 3;


        if ($member){

            foreach($member as $row) {

                // Knowledge Management
                $content = getContentNoRedirect($row['content_id'],31);
                if($content){
                    $content_text = $content['content_name'];
                    $content_url = site_url('knowledge_sharing/detail/').$content['content_id'];
                }else{
                    $content_text = '';
                    $content_url = '';
                }

                $result = json_decode($row['crm_step'],TRUE);

                if (isset($result['PT']['ptScore']) AND $result['PT']['ptScore']){
                    $ptScore = explode('-',$result['PT']['ptScore']);
                }else{
                    $ptScore = array('','','','');
                }

                if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){
                    $ctScore = explode('-',$result['CT']['ctScore']);
                }else{
                    $ctScore = array('','','','');
                }


                $sheet->setCellValue('A'.$x, $no++);
                $sheet->setCellValue('B'.$x, $row['member_name']);
                $sheet->setCellValue('C'.$x, (string)$row['member_nip']);
                $sheet->setCellValue('D'.$x, $row['group_name']);

                $sheet->setCellValue('E'.$x, isset($result['PT']['ptDate']) ? ($result['PT']['ptDate'] ? date('d/m/Y H:i',strtotime($result['PT']['ptDate'])) : '' ) :'');
                $sheet->setCellValue('F'.$x, $ptScore[1] );
                $sheet->setCellValue('G'.$x, $ptScore[2] );
                $sheet->setCellValue('H'.$x, $ptScore[3] );

                $cur_alpha = 8;
                $module = array();
                if($classroom['cr_module']){

                    $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
                    $cr_module = json_decode($json_cr_module,TRUE);

                    if (isset($cr_module['Module'])){
                        $module = $cr_module['Module'];
                    }
                }



                if ($module){
                    foreach ($module as $k => $v){
                        $sheet->setCellValue($alpha[$cur_alpha].$x, isset($result['MP'][$k]['EvaDate']) ? ($result['MP'][$k]['EvaDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['EvaDate'])) : '' ) :'');

                        if (isset($result['MP'][$k]['EvaScore']) AND $result['MP'][$k]['EvaScore']){
                            $EvaScore = explode('-',$result['MP'][$k]['EvaScore']);
                        }else{
                            $EvaScore = array('','','','');
                        }

                        $sheet->setCellValue($alpha[$cur_alpha+1].$x, $EvaScore[1] );
                        $sheet->setCellValue($alpha[$cur_alpha+2].$x, $EvaScore[2] );
                        $sheet->setCellValue($alpha[$cur_alpha+3].$x, $EvaScore[3] );

                        $sheet->setCellValue($alpha[$cur_alpha+4].$x, isset($result['MP'][$k]['LearningPoint']['tanggal']) ? ($result['MP'][$k]['LearningPoint']['tanggal'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['LearningPoint']['tanggal'])) : '' ) :''  );
                        $sheet->setCellValue($alpha[$cur_alpha+5].$x, isset($result['MP'][$k]['LearningPoint']['isi']) ? $result['MP'][$k]['LearningPoint']['isi'] : '' );

                        $sheet->setCellValue($alpha[$cur_alpha+6].$x, isset($result['MP'][$k]['FbDate']) ? ($result['MP'][$k]['FbDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['FbDate'])) : '' ) :''  );
                        $sheet->setCellValue($alpha[$cur_alpha+7].$x, isset($result['MP'][$k]['FbDesc']) ? $result['MP'][$k]['FbDesc'] : '' );
                        $sheet->setCellValue($alpha[$cur_alpha+8].$x, '' );

                        $cur_alpha = $cur_alpha + 9;

                    }
                }


                $sheet->setCellValue($alpha[$cur_alpha].$x, isset($result['CT']['ctDate']) ? ($result['CT']['ctDate'] ? date('d/m/Y H:i',strtotime($result['CT']['ctDate'])) : '' ) :'');
                $sheet->setCellValue($alpha[$cur_alpha+1].$x, $ctScore[1] );
                $sheet->setCellValue($alpha[$cur_alpha+2].$x, $ctScore[2] );
                $sheet->setCellValue($alpha[$cur_alpha+3].$x, $ctScore[3] );

                $sheet->setCellValue($alpha[$cur_alpha+4].$x, isset($result['RESULT']) ? $result['RESULT'] : '' );
                $sheet->setCellValue($alpha[$cur_alpha+5].$x, $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '' );

                $sheet->setCellValue($alpha[$cur_alpha+6].$x, $content_text);
                if ($content_url){
                    $sheet->getCell($alpha[$cur_alpha+6].$x)->getHyperlink()->setUrl($content_url);
                }


                $x++;
            }

        }


        create_log($this->section_id,$classroom_id,'Export','Progress Member');
        $writer = new Xlsx($spreadsheet);
        $filename = 'Progress_member_classroom_'.slugify($classroom['cr_name']);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    private function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }



    // Absensi
    function attendance($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $filter = $this->input->get('filter');
        $data['member']         = $this->classroom_attendance_model->get_by_classroom($classroom_id,$filter);

        //print_r($data);
        $data['classroom']      = $classroom;
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'].'<br><small>'.parseDateShortReadable($classroom['cr_date_start']).' - '.parseDateShortReadable($classroom['cr_date_end']).'</small>';
        $data['page'] = 'classroom/classroom_attendance_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function attendance_add_picker($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $post = $this->input->post();
        $member_ids = $post['member_ids'];

        foreach ($member_ids as $v){
            $member_id = $v;
            $data = array(
                'cr_id'     => $classroom_id,
                'member_id' => $member_id,
                'cra_channel'        => 'cms',
                'cra_create_date'    => date('Y-m-d H:i:s')
            );
            $this->classroom_attendance_model->insert($data);
            create_log($this->section_id,$classroom_id,'Tambah','Kehadiran');
        }

        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );

    }

    function attendance_remove($classroom_id=NULL,$cra_id){

        $classroom = $this->get_classroom($classroom_id);
        $this->classroom_attendance_model->delete($cra_id);
        create_log($this->section_id,$classroom_id,'Hapus','Kehadiran');
        flash_notif_success('Berhasil dihapus',NULL,FALSE);
        redirect(site_url('classroom/attendance/'.$classroom_id));

    }

    function attendance_scan(){
        //print_r($data);

        $data['member']         = $this->classroom_attendance_model->get_all(10);

        $data['form_action']      = site_url('classroom/attendance_scan_input_ajax');

        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = 'Scan Barcode';
        $data['page'] = 'classroom/classroom_attendance_scan_view';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function attendance_scan_input_ajax(){

        // Cek Valid
        $barcode_json_b64 = $this->input->post('barcode');
        $barcode_json = base64_decode($barcode_json_b64);
        $barcode = json_decode($barcode_json,TRUE);
        if (!isset($barcode['cr_id']) OR !isset($barcode['member_id'])){
            echo json_encode(['status'    => 400, 'message'   => 'Invalid']);
            exit();
        }

        $member_id = $barcode['member_id'];
        $cr_id = $barcode['cr_id'];

        // Cek Member
        $get_member = $this->member_model->get($member_id);
        if (!$get_member){
            echo json_encode(['status'    => 400, 'message'   => 'Member Not Found']);
            exit();
        }

        // Cek Classroom
        $get_classroom = $this->classroom_model->get($cr_id);
        if (!$get_classroom){
            echo json_encode(['status'    => 400, 'message'   => 'Classroom Not Found']);
            exit();
        }

        // Cek Classroom Member
        $get_classroom_member = $this->classroom_member_model->get_by_classroom_member($cr_id,$member_id);
        if (!$get_classroom_member){
            echo json_encode(['status'    => 400, 'message'   => 'Member Not Found in Classroom']);
            exit();
        }

        // Cek Sesi
        $cr_session = [
            [
                'id'    => 1,
                'start' => '00:00',
                'end'   => '13:00'
            ],
            [
                'id'    => 2,
                'start' => '13:01',
                'end'   => '23:59'
            ]
        ];

        $date_now    = date('Y-m-d');
        $time_now    = date('H:i');

        foreach ($cr_session as $k => $v){
            if (strtotime($time_now) >= strtotime($v['start']) && strtotime($time_now) <= strtotime($v['end'])){
                $session_index = $k;
            }
        }

        $attend_today = array();
        $get_attendance = $this->classroom_attendance_model->get_by_classroom_member($cr_id,$member_id);
        if ($get_attendance){
            foreach ($get_attendance as $v){
                // Get Attend Today
                if ($date_now==date('Y-m-d',strtotime($v['cra_create_date']))){
                    $attend_today[] = $v['cra_create_date'];
                }
            }

            foreach ($attend_today as $v){
                // Cek sudah absen di sesi ini
                $sess_time_start = $cr_session[$session_index]['start'];
                $sess_time_end   = $cr_session[$session_index]['end'];
                if (strtotime($v) >= strtotime($sess_time_start) && strtotime($v) <= strtotime($sess_time_end)){
                    echo json_encode(['status'    => 400, 'message'   => 'Member already attend in this session']);
                    exit();
                }
            }
        }



        $data = array(
            'cr_id' => $cr_id,
            'member_id' => $member_id,
            'cra_channel' => 'cms',
            'cra_create_date' => date('Y-m-d H:i:s')
        );

        $insert_attendance = $this->classroom_attendance_model->insert($data);
        if ($insert_attendance){
            $cra = $this->classroom_attendance_model->get($insert_attendance);
            echo json_encode([
                'status'    => 200,
                'message'   => 'Success',
                'data'      => $cra
            ]);
            exit();
        }else{
            echo json_encode([
                'status'    => 400, 'message'   => 'DB Error',
            ]);
            exit();
        }

    }

    function attendance_json(){
        $get_member = $this->classroom_attendance_model->get_all(10);

        $result = [];
        if ($get_member){
            foreach ($get_member as $k => $v){
                $result['data'][] = array(
                    'member_id'       => $v['member_id'],
                    'member_name'     => $v['member_name'],
                    'group_name'      => $v['group_name'],
                    'member_nip'      => $v['member_nip'],
                    'cr_name'         => $v['cr_name'],
                    'cra_create_date' => parseDateShortReadable($v['cra_create_date']).', '.parseTimeReadable($v['cra_create_date'])
                );
            }
        }

        echo json_encode($result);
    }


    // Push Notif
    function notif($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/notif/').$classroom_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['request'] = ['notification'=>''];

            //print_r($data['pretest']);
            $data['form_action']    = site_url('classroom/notif/'.$classroom_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_notif_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $this->load->library(['fcm']);

            $post          = $this->input->post();
            $notification  = $post['notification'];
            $classroom_id  = $post['cr_id'];

            // GET MEMBER
            $get_member    = $this->classroom_member_model->get_by_classroom($classroom_id);
            foreach ($get_member as $k => $v){

                // NOTIFIKASI
                $recData    = ['memberId' => $v['member_id']];
                $dtoken     = $this->member_model->select_member_device_token('byMemberId', $recData);
                $tokens     = [];
                foreach ($dtoken as $t){
                    array_push($tokens, $t['device_token']);
                }
                $token = $tokens;

                $this->fcm->setTitle($classroom['cr_name']);
                $this->fcm->setBody($notification);

                //$this->fcm->setImage('');

                $result = $this->fcm->sendMultiple($token);
                //print_r($v['member_id']);

            }
            create_log($this->section_id,$classroom_id,'Kirim Notifikasi','');
            flash_notif_success('Notifikasi terkirim',$url_return);
        }

    }


}