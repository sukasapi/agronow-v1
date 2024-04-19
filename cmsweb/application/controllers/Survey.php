<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Survey extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'survey_model',
            'survey_member_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));

        $this->section_id = 39;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->survey_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['survey_id']              = $item->survey_id;
            $row['survey_create_date']     = $item->survey_create_date?parseDateShortReadable($item->survey_create_date):NULL;
            $row['survey_create_time']     = $item->survey_create_date?parseTimeReadable($item->survey_create_date):NULL;
            $row['survey_date_start']    = $item->survey_date_start?parseDateShortReadable($item->survey_date_start):NULL;
            $row['survey_time_start']    = $item->survey_date_start?parseTimeReadable($item->survey_date_start):NULL;
            $row['survey_date_end']    = $item->survey_date_end?parseDateShortReadable($item->survey_date_end):NULL;
            $row['survey_time_end']    = $item->survey_date_end?parseTimeReadable($item->survey_date_end):NULL;
            $row['survey_name']            = $item->survey_name;
            $row['user_count']       = "";
            $row['survey_status']  = $item->survey_status;

            $row['picture']  = "";

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->survey_model->count_all(),
            "recordsFiltered" => $this->survey_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function survey_detail_modal_ajax($sm_id){
        $data['survey_member'] = $this->survey_member_model->get($sm_id);
        $data['survey'] = $this->survey_model->get($data['survey_member']['survey_id']);
        $this->load->view('survey_member/survey_member_detail_modal_view',$data);
    }

    private function get_survey($survey_id){
        $get_survey = $this->survey_model->get($survey_id);
        if ($get_survey==FALSE){
            redirect(404);
        }else{
            return $get_survey;
        }
    }

    function index(){
        has_access('survey.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Survey';
        $data['page_sub_name']      = 'List survey';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'survey/survey_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($survey_id=NULL){
        has_access('survey.view');
        $survey = $this->get_survey($survey_id);

        $data['survey']        = $survey;
        $data['page_name']      = 'Survey';
        $data['page_sub_name']  = $survey['survey_name'];
        $data['page'] = 'survey/survey_detail_view';
        $data['submenu'] = 'survey/survey_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('survey.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('survey_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Survey';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('survey/create');
            $data['page']           = 'survey/survey_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = 1;

            $data = array(
                'survey_name'           => $post['survey_name'],
                'survey_desc'           => $post['survey_desc'],
                'survey_status'           => $post['survey_status'],
                'survey_date_start'     => isset($post['survey_date_start'])?parseDate($post['survey_date_start']):NULL,
                'survey_date_end'       => isset($post['survey_date_end'])?parseDate($post['survey_date_end']):NULL,
                'survey_create_date'   => date("Y-m-d H:i:s"),
                'survey_created_by'   => $created_by,
            );

            $extra_survey_data = array();
            $data['survey_data'] = json_encode($extra_survey_data);

            $insert = $this->survey_model->insert($data);


            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $survey_id = $insert;

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

                        $update_survey = $this->survey_model->update(
                            array(
                                'survey_id'      => $survey_id,
                                'survey_image'   => $filename_system,
                            )
                        );

                    }else{
                        // GAGAL UPLOAD
                        $notif[] = $this->upload->display_errors();
                        $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                    }

                }
                // End Handle File


                $url_return = site_url('survey/detail/').$insert;

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

        }
    }

    function edit($survey_id=NULL){
        has_access('survey.edit');

        $survey = $this->get_survey($survey_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey/detail/'.$survey_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('survey_id', 'ID', 'required');
        $this->form_validation->set_rules('survey_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $survey;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('survey/edit').'/'.$survey_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Survey";
            $data['page_sub_name']      = 'Edit Survey';
            $data['page']               = 'survey/survey_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'survey_id'  => $post['survey_id']==NULL?NULL:$post['survey_id'],
                'survey_name'           => $post['survey_name'],
                'survey_desc'           => $post['survey_desc'],
                'survey_status'           => $post['survey_status'],
                'survey_date_start'     => isset($post['survey_date_start'])?parseDate($post['survey_date_start']):NULL,
                'survey_date_end'       => isset($post['survey_date_end'])?parseDate($post['survey_date_end']):NULL,
            );


            $edit = $this->survey_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$survey_id,'Edit','');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_picture($survey_id=NULL){
        has_access('survey.edit');

        $survey = $this->get_survey($survey_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey/detail/').$survey_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('survey_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $survey['media_value'] = $survey['survey_image'];
            $data['request']            = $survey;
            $data['survey']            = $survey;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('survey/edit_picture').'/'.$survey_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Ads";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$survey['survey_name'].'</small>';
            $data['page']               = 'survey/survey_form_edit_picture_view';
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

                    $update_survey = $this->survey_model->update(
                        array(
                            'survey_id'      => $survey['survey_id'],
                            'survey_image'   => $filename_system,
                        )
                    );

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            create_log($this->section_id,$survey_id,'Edit','');
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


    function export($survey_id=NULL){
        has_access('survey.view');

        $survey = $this->get_survey($survey_id);
        $survey_member = $this->survey_member_model->get_by_survey($survey_id);

        $json_raw = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
        $question = json_decode($json_raw,TRUE);


        //print_r($result);exit();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $alpha = range('A', 'ZZ');


        $sheet->setCellValue($alpha[0].'1', 'No');
        $sheet->setCellValue($alpha[1].'1', 'Nama Partisipan');
        $sheet->setCellValue($alpha[2].'1', 'NIP');
        $sheet->setCellValue($alpha[3].'1', 'Group');

        foreach(range($alpha[0],$alpha[3]) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $col_header = 4;
        foreach ($question as $k => $v){
            $sheet->setCellValue($alpha[$col_header].'1', $v['Question']);

            $sheet->getColumnDimension($alpha[$col_header])->setWidth(50);
            $col_header++;
        }

        $sheet->getStyle('A1:'.$alpha[$col_header].'999')->getAlignment()->setWrapText(true);


        $no = 1;
        $x = 2;
        foreach($survey_member as $row)
        {
            $sheet->setCellValue($alpha[0].$x, $no++);
            $sheet->setCellValue($alpha[1].$x, $row['member_name']);
            $sheet->setCellValue($alpha[2].$x, (string)$row['member_nip']);
            $sheet->setCellValue($alpha[3].$x, $row['group_name']);


            $col_answer = 4;
            foreach ($question as $i => $j){
                $coordinate = $alpha[$col_answer].$x;

                $json_sm_raw = preg_replace('/[[:cntrl:]]/', '', $row['sm_data']);
                $sm_data = json_decode($json_sm_raw,TRUE);

                $answer = $sm_data['Q'.$i];

                if ($answer){

                    if ($j['Model']=='multiple-choice'){

                        if ($j['Type']=='text'){
                            $answer_result = $j['ChoiceText'][$answer];
                            $sheet->setCellValue($coordinate, $answer_result);
                        } else if ($j['Type']=='image'){

                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            $drawing->setPath(UPLOAD_FOLDER.'image/'.$j['ChoiceImage'][$answer]); // put your path and image here
                            $drawing->setHeight(50);
                            $drawing->setCoordinates($coordinate);
                            $drawing->setWorksheet($spreadsheet->getActiveSheet());

                            $sheet->getRowDimension($x)->setRowHeight(50);

                            //$answer_result = '<img src="'.URL_MEDIA_IMAGE.$j['ChoiceImage'][$answer].'" width="96px">';
                        } else if ($j['Type']=='text-image'){
                            /*$answer_result = '<img src="'.URL_MEDIA_IMAGE.$j['ChoiceImage'][$answer].'" width="96px">';
                            $answer_result .= '<br>';*/
                            $answer_result = $j['ChoiceText'][$answer];
                            $sheet->setCellValue($coordinate, $answer_result);

                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            $drawing->setPath(UPLOAD_FOLDER.'image/'.$j['ChoiceImage'][$answer]); // put your path and image here
                            $drawing->setHeight(50);
                            $drawing->setCoordinates($coordinate);
                            $drawing->setWorksheet($spreadsheet->getActiveSheet());

                            $sheet->getRowDimension($x)->setRowHeight(70);
                        }

                    }else{
                        $answer_result = $answer;
                        $sheet->setCellValue($coordinate, $answer_result);
                    }

                }




                //$sheet->setCellValue($coordinate, $answer_result);
                $col_answer++;
            }


            $x++;
        }

        create_log($this->section_id,$survey_id,'Export','');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Survey - '.$survey['survey_name'].' '.date('d F Y H_i_s');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    // Question
    function question($survey_id=NULL){
        has_access('survey.view');

        $survey = $this->get_survey($survey_id);

        $extra_question = array(
            'Question'  => NULL,
            'Model'     => NULL,    // multiple-choice OR essay
            'Type'      => NULL,    // text OR image OR text-image
            'ChoiceText'    => array(),
            'ChoiceImage'   => array(),
        );

        $arr_data = array();

        $data['survey']        = $survey;

        if ($survey['survey_data']){
            $json_raw = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
            $arr_data = json_decode($json_raw,TRUE);
        }

        $data['request']       = $arr_data;
        //print_r($data);

        $data['editable']      = TRUE;
        $data['page_name']      = 'Survey';
        $data['page_sub_name']  = $survey['survey_name'];
        $data['page'] = 'survey/survey_question_view';
        $data['submenu'] = 'survey/survey_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function question_update_tree($survey_id=NULL){
        has_access('survey.edit');

        $survey = $this->get_survey($survey_id);
        if ($survey['survey_data']){
            $json_survey_data = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
            $extra_data = json_decode($json_survey_data,TRUE);
        }

        $order = $this->input->post('order');
        $arrOrder = json_decode($order,TRUE);

        $updated_data = array();

        foreach ($arrOrder as $k => $v){
            $updated_data[] = $extra_data[$v];
        }

        $extra_data =  $updated_data;
        $json_data = json_encode($extra_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'survey_id' => $survey_id,
            'survey_data' => $json_data,
        );
        $this->survey_model->update($data);



        $survey_member = $this->survey_member_model->get_by_survey($survey_id);
        if ($survey_member){
            foreach ($survey_member as $i => $j){
                $sm_id = $j['sm_id'];
                $sm_data = json_decode($j['sm_data'],TRUE);

                foreach ($arrOrder as $k => $v){
                    $new_sm_data['Q'.$k] = $sm_data['Q'.$v];
                }

                $data_survey_member = array(
                    'sm_id' => $sm_id,
                    'sm_data'=> json_encode($new_sm_data,JSON_UNESCAPED_SLASHES)
                );
                $this->survey_member_model->update($data_survey_member);

            }
        }



        create_log($this->section_id,$survey_id,'Edit','Urutan Pertanyaan');
        flash_notif_success('Data berhasil diubah',NULL,FALSE);
        redirect(site_url('survey/question/').$survey_id);
    }

    function question_add($survey_id=NULL){
        has_access('survey.create');

        $survey = $this->get_survey($survey_id);

        $data['survey']    = $survey;
        $data['request']   = $survey;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey/question/').$survey_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('survey_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Question'  => NULL,
                'Model'     => NULL,    // multiple-choice OR essay
                'Type'      => NULL,    // text OR image OR text-image
                'ChoiceText'    => array(),
                'ChoiceImage'   => array(),
            );

            $data['request']   = $extra_question;

            //print_r($data['question']);
            $data['form_action']    = site_url('survey/question_add/'.$survey_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Survey';
            $data['page_sub_name']  = 'Tambah Pertanyaan'.'<br><small>'.$survey['survey_name'].'</small>';
            $data['page'] = 'survey/survey_question_form_add_view';
            $data['submenu'] = 'survey/survey_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $data_question = array(
                'Question'  => $post['Question'],
                'Model'     => $post['Model'],    // multiple-choice OR essay
                'Type'      => $post['Type'],    // text OR image OR text-image
                'ChoiceText'    => $post['ChoiceText'],
                'ChoiceImage'   => array(),
            );

            $count = count($_FILES['ChoiceImage']['name']);
            for($i=0;$i<$count;$i++){

                if(!empty($_FILES['ChoiceImage']['name'][$i])){

                    $_FILES['file']['name'] = $_FILES['ChoiceImage']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['ChoiceImage']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['ChoiceImage']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['ChoiceImage']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['ChoiceImage']['size'][$i];

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

                        $config['allowed_types'] = 'jpeg|jpg|png|gif';
                        $config['max_size']      = '5000';

                        //print_r($config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('file')){
                            $data_question['ChoiceImage'][] = $filename_system;
                        }


                    }
                    // End Handle File

                }
            }


            $json_raw = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data[] = $data_question;

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);


            $data = array(
                'survey_id'     => $post['survey_id'],
                'survey_data' => $json_data,
            );

            $update = $this->survey_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$survey_id,'Tambah','Pertanyaan');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function question_edit($survey_id=NULL,$index){
        has_access('survey.edit');

        $survey = $this->get_survey($survey_id);

        $data['survey'] = $survey;
        $data['request']   = $survey;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey/question/').$survey_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('survey_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Question'  => NULL,
                'Model'     => NULL,    // multiple-choice OR essay
                'Type'      => NULL,    // text OR image OR text-image
                'ChoiceText'    => array(),
                'ChoiceImage'   => array(),
            );

            if ($survey['survey_data']){
                $survey_data = $survey['survey_data'];
                $json_survey_data = preg_replace('/[[:cntrl:]]/', '', $survey_data);
                $extra_question = json_decode($json_survey_data,TRUE);
            }

            $data['request']   = $extra_question[$index];

            //print_r($data['question']);
            $data['form_action']    = site_url('survey/question_edit/'.$survey_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Survey';
            $data['page_sub_name']  = 'Edit Pertanyaan'.'<br><small>'.$survey['survey_name'].'</small>';
            $data['page'] = 'survey/survey_question_form_edit_view';
            $data['submenu'] = 'survey/survey_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data[$index]['Question'] = $post['Question'];
            $arr_data[$index]['Model'] = $post['Model'];
            $arr_data[$index]['Type'] = $post['Type'];
            $arr_data[$index]['ChoiceText'] = $post['ChoiceText'];


            $count = count($_FILES['ChoiceImage']['name']);
            for($i=0;$i<$count;$i++){

                if(!empty($_FILES['ChoiceImage']['name'][$i])){

                    $_FILES['file']['name'] = $_FILES['ChoiceImage']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['ChoiceImage']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['ChoiceImage']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['ChoiceImage']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['ChoiceImage']['size'][$i];

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

                        $config['allowed_types'] = 'jpeg|jpg|png|gif';
                        $config['max_size']      = '5000';

                        //print_r($config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('file')){
                            $arr_data[$index]['ChoiceImage'][$i] = $filename_system;
                        }


                    }
                    // End Handle File

                }
            }


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'survey_id'     => $post['survey_id'],
                'survey_data' => $json_data,
            );

            $update = $this->survey_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$survey_id,'Edit','Pertanyaan');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function question_delete($survey_id=NULL,$index){
        has_access('survey.delete');

        $survey = $this->get_survey($survey_id);
        if ($survey['survey_data']){
            $json_survey_data = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
            $extra_data = json_decode($json_survey_data,TRUE);
        }

        $existing_data = $extra_data;
        unset($existing_data[$index]);

        //print_r($existing_materi);exit();

        $extra_data =  $existing_data;
        $json_data = json_encode($extra_data, JSON_UNESCAPED_SLASHES);

        //print_r($extra_data);

        $data = array(
            'survey_id' => $survey_id,
            'survey_data' => $json_data,
        );
        //print_r($data);exit();
        $this->survey_model->update($data);
        create_log($this->section_id,$survey_id,'Hapus','Pertanyaan');
        flash_notif_success('Data berhasil dihapus',NULL,FALSE);
        redirect(site_url('survey/question/').$survey_id);
    }

    // Member
    function member($survey_id=NULL){
        has_access('survey.view');

        $survey = $this->get_survey($survey_id);

        $data['member']         = $this->survey_member_model->get_by_survey($survey_id);
        $data['member_count']   = $this->survey_member_model->count_by_survey($survey_id);
        //print_r($data);
        $data['survey']      = $survey;
        $data['page_name']      = 'Survey';
        $data['page_sub_name']  = $survey['survey_name'];
        $data['page'] = 'survey/survey_member_view';
        $data['submenu'] = 'survey/survey_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function delete($survey_id=NULL){
        has_access('survey.delete');

        $survey = $this->get_survey($survey_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('survey/detail/').$survey_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('survey_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['survey']         = $survey;
            $data['request']            = $survey;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('survey/delete').'/'.$survey_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Survey";
            $data['page_sub_name']      = 'Hapus Survey';
            $data['page']               = 'survey/survey_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->survey_model->delete($survey_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$survey_id,'Hapus','');
                $msg        = $survey['survey_name']." telah dihapus.";
                $url_return = site_url('survey');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('survey/delete/'.$survey_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }





}