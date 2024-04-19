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
			'learning_wallet_model',
			'group_model',
        ));
       
        $this->load->model('Project_assignment_model','pa');
        $this->load->model('Classroom_evaluasi_model','ce');
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
            //tambah PIC
            $row['cr_pic']  = $item->cr_pic;
            $row['user_name']  = $item->user_name;

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

        if(has_access('classroom.view',FALSE) OR has_access('classroom.view.own',FALSE)){

        }else{
            redirect(404);
        }

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Class Room';
        $data['page_sub_name']      = 'List Class Room';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'classroom/classroom_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        if(!has_access('classroom.view',FALSE)){

            if ( has_access('classroom.view.own',FALSE)){

                if(user_id() != $classroom['id_petugas']){
                    redirect(404);
                }

            }else{
                redirect(404);
            }
        }



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

        $pin=PinGenerator(8);
        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc',NULL,TRUE);
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

             // 31012024
             $data['pin'] =$pin;
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
			
			if($post['cr_kelola']=="luar_app") {
				$post['cr_has_certificate'] = 0;
				$post['cr_has_prelearning'] = 0;
				$post['cr_has_pretest'] = 0;
				$post['cr_has_kompetensi_test'] = 0;
				$post['cr_show_nilai'] = 0;
				$post['cr_modul_harus_urut'] = 0;
				$post['cr_has_learning_point'] = 0;
				$post['cr_has_knowledge_management'] = 0;
				$post['cr_has_project_assignment'] = 0;
			}
			
			$data = array(
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
				'id_lw_classroom'   => (int) $post['id_lw_classroom'],
				'id_superapp_manpro'   => isset($post['id_superapp_manpro'])?(int) $post['id_superapp_manpro']:"", //update 07022024,,
				'kode_superapp_manpro' => $post['kode_superapp_manpro'],
				'cr_kelola'         => $post['cr_kelola'],
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
                //tambah PIC 
                'cr_pic'=>$post['cr_pic'],
                //End PIC
                  // tambah PIN
                'cr_pin'=>isset($post['cr_pin']) && $post['cr_pin']!="" ?$post['cr_pin']:$pin,
                'cr_kode'=>isset($post['cr_pin']) && $post['cr_pin']!="" ?md5($post['cr_pin']):md5($pin),
                  //End PPIN
                'cr_has_prelearning' => $post['cr_has_prelearning'],
                'cr_has_pretest' => $post['cr_has_pretest'],
                'cr_has_kompetensi_test' => $post['cr_has_kompetensi_test'],
                'cr_show_nilai' => $post['cr_show_nilai'],
                'cr_modul_harus_urut' => $post['cr_modul_harus_urut'],
                'cr_has_learning_point' => $post['cr_has_learning_point'],
                'cr_has_knowledge_management' => $post['cr_has_knowledge_management'],
				'cr_has_project_assignment' => $post['cr_has_project_assignment'],
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
                 
                
                /* EVALUASI NPS */
                // 13042022 - KDW
                //Jika ada diklat superapp maka update di diklat superapp
                $id_manpro=isset($post['id_superapp_manpro'])?(int) $post['id_superapp_manpro']:""; //update 07022024

                $id_agronow= $insert;

                if(!empty($id_manpro) || $id_manpro!=""){   //update 07022024
                     $url=URL_SIP_PROJECT;
                     $data=array("app"=>"postman","key"=>"dpostman","act"=>"link_diklat","agro_kelas"=>$id_agronow,"superapp_diklat"=>$id_manpro);
                     $options = array(
                                    "http"=> array(
                                    "method"=>"POST",
                                    "header"=>"Content-Type: application/x-www-form-urlencoded",
                                    "content"=>http_build_query($data)
                                    )
                                );
                    $response=file_get_contents($url,false,stream_context_create($options));
                    $result=json_decode($response);
                }

                /// Create: 18.01.2024
                /// Auth : KDW
                ///evaluasi kelas
                if(isset($post['cr_kelola']) && $post['cr_kelola']!=""){
                    if($post['cr_kelola']!="dalam_app"){
                        $jenis ="eksternal";
                    }else{
                        $jenis ="internal";
                    }
                }else{
                    $jenis ="internal";
                }
                 $pesan="";
                if(isset($post['ev_penyelenggaraan']) && $post['ev_penyelenggaraan'] > 0){
                    //getsoal penyelenggaraan
                   
                    $setsoal=$this->ce->set_soalbyType("penyelenggaraan",$jenis);
                    $inputevaluasi=array('cr_id'=> $insert,'jenis'=>'penyelenggaraan','setsoal'=>$setsoal,'tipe'=>$jenis);
                    $addevaluasi=$this->ce->add_setsoal($inputevaluasi);
                    if($addevaluasi){
                        $pesan.="kelas dengan evaluasi penyelenggaraan terbuat<br/>";
                        $posteval=true;
                    }else{
                        $pesan.="evaluasi penyelenggaraan gagal terbuat<br/>";
                        $posteval=false;
                    }
                }else{

                }

                if(isset($post['ev_sarana']) && $post['ev_sarana'] >0){
                    //getsoal penyelenggaraan
                    $setsoal=$this->ce->set_soalbyType("sarana","internal");
                    $inputevaluasi=array('cr_id'=> $insert,'jenis'=>'sarana','setsoal'=>$setsoal,'tipe'=>$jenis);
                    $addevaluasi=$this->ce->add_setsoal($inputevaluasi);
                    if($addevaluasi){
                        $posteval=true;
                        $pesan.="kelas dengan evaluasi sarana terbuat<br/>";
                    }else{
                        $pesan.="evaluasi sarana gagal terbuat<br/>";
                        $posteval=false;
                    }
                }else{

                }

                if(isset($post['ev_narsum']) && $post['ev_narsum'] >0){
                    //getsoal penyelenggaraan
                    $setsoal=$this->ce->set_soalbyType("narasumber",$jenis);
                    foreach($post['narsum'] as $n){
                        $inputevaluasi=array('cr_id'=> $insert,'jenis'=>'narasumber','pengajar'=>$n,'setsoal'=>$setsoal,'tipe'=>$jenis);
                        $addevaluasi=$this->ce->add_setsoal($inputevaluasi);
                        if($addevaluasi){
                            $pesan.="kelas dengan evaluasi narasumber ".$n." terbuat<br/>";
                            $posteval=true;
                        }else{
                            $pesan.="evaluasi narasumber ".$n." gagal terbuat<br/>";
                            $posteval=false;
                        }
                    }
                }else{

                }

                //07022023
                //tambahan untuk evaluasi external
                //batal
               /* if(isset($post['ev_external']) && $post['ev_external'] > 0){
                     //getsoal penyelenggaraan
                     $setsoal=$this->ce->set_soalbyType("external","external");
                     $inputevaluasi=array('cr_id'=> $insert,'jenis'=>'external','setsoal'=>$setsoal);
                     $addevaluasi=$this->ce->add_setsoal($inputevaluasi);
                     if($addevaluasi){
                         $pesan.="kelas dengan evaluasi untuk external telah terbuat<br/>";
                         $posteval=true;
                     }else{
                         $pesan.="evaluasi sarana gagal terbuat<br/>";
                         $posteval=false;
                     }
                }else{

                }*/
                /* END EVALUASI NPS */

                $url_return = site_url('classroom/detail/').$insert;
                if($posteval==false){
                    flash_notif_success($pesan,$url_return);
                }else{
                    flash_notif_success(NULL,$url_return);
                }
            }else{
                flash_notif_failed(NULL,$url_return);
            }

        }
    }

    function edit($classroom_id=NULL){
        
        $classroom = $this->get_classroom($classroom_id);

        if (!is_classroom_editable($classroom_id)){
            redirect(404);
        }


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
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc',NULL,TRUE);
            $cat_selected = $classroom['cat_id'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

             /* Start Evaluasi - 18012024 */
             $evaluasi = $this->ce->cek_setsoal(array("cr_id"=>$classroom_id,"status"=>"1"));
             $data['evaluasi']=$evaluasi;
         /* End Evaluasi*/

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
            $pin=isset($post['cr_pin']) && $post['cr_pin']!="" ?$post['cr_pin']:pinGenerator(8);
			
			if($post['cr_kelola']=="luar_app") {
				$post['cr_has_certificate'] = 0;
				$post['cr_has_prelearning'] = 0;
				$post['cr_has_pretest'] = 0;
				$post['cr_has_kompetensi_test'] = 0;
				$post['cr_show_nilai'] = 0;
				$post['cr_modul_harus_urut'] = 0;
				$post['cr_has_learning_point'] = 0;
				$post['cr_has_knowledge_management'] = 0;
				$post['cr_has_project_assignment'] = 0;
                ///07022024
                $post['ev_penyelenggaraan']=0;
                $post['ev_sarana']=0;
                $post['ev_narsum']=0;
			}

            $data = array(
                'cr_id'  => $post['cr_id']==NULL?NULL:$post['cr_id'],
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
				'id_lw_classroom'   => (int) $post['id_lw_classroom'],
				'id_superapp_manpro'   => (int) $post['id_superapp_manpro'],
				'kode_superapp_manpro' => $post['kode_superapp_manpro'],
				'cr_kelola'         => $post['cr_kelola'],
                'cr_desc'           => $post['cr_desc'],
                'cr_type'           => $post['cr_type'],
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_time_start'     => isset($post['cr_time_start'])?$post['cr_time_start']:NULL,
                'cr_time_end'       => isset($post['cr_time_end'])?$post['cr_time_end']:NULL,
                'cr_date_detail'    => $post['cr_date_detail'],
                'cr_price'           => parseInputNull($post['cr_price']),
                'cr_has_certificate' => $post['cr_has_certificate'],
                //tambahan pic
                'cr_pic'=>$post['cr_pic'],
                //end PIC
                 // tambah PIN
                'cr_pin'=>$pin,
                'cr_kode'=>md5($pin),
                 //End PPIN
                'cr_has_prelearning' => $post['cr_has_prelearning'],
                'cr_has_pretest' => $post['cr_has_pretest'],
                'cr_has_kompetensi_test' => $post['cr_has_kompetensi_test'],
                'cr_show_nilai' => $post['cr_show_nilai'],
                'cr_modul_harus_urut' => $post['cr_modul_harus_urut'],
                'cr_has_learning_point' => $post['cr_has_learning_point'],
                'cr_has_knowledge_management' => $post['cr_has_knowledge_management'],
				'cr_has_project_assignment' => $post['cr_has_project_assignment'],

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

            //07022024
            $posteval=true;
            $pesan ="";
            if ($edit==TRUE) {
                // 13042022 - KDW
               //Jika ada diklat superapp maka update di diklat superapp
               $id_manpro=(int) $post['id_superapp_manpro'];
               $id_agronow= $post['cr_id'];
                    $url=URL_SIP_PROJECT;
                    $data=array("app"=>"postman","key"=>"dpostman","act"=>"link_diklat","agro_kelas"=>$id_agronow,"superapp_diklat"=>$id_manpro);
                    $options = array(
                                   "http"=> array(
                                   "method"=>"POST",
                                   "header"=>"Content-Type: application/x-www-form-urlencoded",
                                   "content"=>http_build_query($data)
                                   )
                               );
                   $response=file_get_contents($url,false,stream_context_create($options));
                   $result=json_decode($response);
               ////// END diklat superapp    

                /// Create: 18.01.2024
                /// Auth : KDW
                ///evaluasi kelas
                $jenis=$post['cr_kelola']=="dalam_app"?"internal":"eksternal";
                $cr_id=$post['cr_id'];
                $msgeval="";
                if(isset($post['ev_penyelenggaraan']) && $post['ev_penyelenggaraan'] > 0){
                    //update status set_soal
                    $setsoal=$this->ce->set_soalbyType("penyelenggaraan",$jenis);
                    $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"penyelenggaraan","status"=>"1","setsoal"=>$setsoal,"tipe"=>$jenis);
                    $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                    if($updateevaluasi){
                       $pesan.="kelas dengan evaluasi untuk external telah terupdate<br/>";
                       $posteval=true;
                   }else{
                       $pesan.="evaluasi sarana gagal terbuat<br/>";
                       $posteval=false;
                   }
                    $msgeval.=" penyelenggaraan  teredit<br>";
                }else{
                    //update status set_soal
                    $setsoal=$this->ce->set_soalbyType("penyelenggaraan",$jenis);
                    $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"penyelenggaraan","status"=>"0","setsoal"=>$setsoal,"tipe"=>$jenis);
                    $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                } 

                if(isset($post['ev_sarana']) && $post['ev_sarana'] > 0){
                    //update status set_soal
                    $setsoal=$this->ce->set_soalbyType("sarana",$jenis);
                    $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"sarana","status"=>"1","setsoal"=>$setsoal,"tipe"=>$jenis);
                    $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                    $msgeval.=" sarana teredit<br>";
                    if($updateevaluasi){
                       $pesan.="kelas dengan evaluasi untuk external telah terupdate<br/>";
                       $posteval=true;
                   }else{
                       $pesan.="evaluasi sarana gagal terbuat<br/>";
                       $posteval=false;
                   }
                }else{
                     //update status set_soal
                     $setsoal=$this->ce->set_soalbyType("sarana",$jenis);
                     $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"sarana","status"=>"0","setsoal"=>$setsoal,"tipe"=>$jenis);
                     $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                }
                 
                
                if(isset($post['ev_narsum']) && $post['ev_narsum'] > 0){
                    foreach($post['narsum'] as $n){
                       $setsoal=$this->ce->set_soalbyType("narasumber",$jenis);
                       $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"narasumber","status"=>"1","pengajar"=>$n,"setsoal"=>$setsoal,"tipe"=>$jenis); 
                       $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                       if($updateevaluasi){
                           $pesan.="kelas dengan evaluasi untuk external telah terupdate<br/>";
                           $posteval=true;
                       }else{
                           $pesan.="evaluasi sarana gagal terbuat<br/>";
                           $posteval=false;
                       }
                       $msgeval.=" narsum ".$n." teredit - evaluasi ".$updateevaluasi."<br>";
                    }
                }else{
                   foreach($post['narsum'] as $n){
                       $setsoal=$this->ce->set_soalbyType("narasumber",$jenis);
                       $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"narasumber","status"=>"0","pengajar"=>$n,"setsoal"=>$setsoal,"tipe"=>$jenis); 
                       $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                       if($updateevaluasi){
                           $pesan.="kelas dengan evaluasi untuk external telah terupdate<br/>";
                           $posteval=true;
                       }else{
                           $pesan.="evaluasi sarana gagal terbuat<br/>";
                           $posteval=false;
                       }
                       $msgeval.=" narsum ".$n." teredit - evaluasi ".$updateevaluasi."<br>";
                    }
                }

               //07022023
               //tambahan untuk evaluasi external
               //BATAL
               /*
               if(isset($post['ev_external']) && $post['ev_external'] > 0){
                   //getsoal penyelenggaraan
                   $setsoal=$this->ce->set_soalbyType("external","external");
                   $inputevaluasi=array('cr_id'=> $cr_id,'jenis'=>'external','status'=>'1','setsoal'=>$setsoal);
                   $updateevaluasi=$this->ce->edit_setsoal($inputevaluasi);
                   if($updateevaluasi){
                       $pesan.="kelas dengan evaluasi untuk external telah terbuat<br/>";
                       $posteval=true;
                   }else{
                       $pesan.="evaluasi sarana gagal terbuat<br/>";
                       $posteval=false;
                   }
               }else{
                       //update status set_soal
                       $setsoal=$this->ce->set_soalbyType("external",$jenis);
                       $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"external","status"=>"0","setsoal"=>$setsoal);
                       $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
               }
               */
               create_log($this->section_id,$classroom_id,'Edit',NULL);
               if($posteval==TRUE){
                   flash_notif_success(NULL,$url_return);
               }else{
                   flash_notif_success($pesan,$url_return);
               }
              
           }else{
                // 13042022 - KDW
                /// Create: 07.02.2024 
                /// Auth : KDW
                ///evaluasi kelas jika 

                //12.02.2024 --> dalam_app = internal, lainnya eksternal
                $jenis=$post['cr_kelola']=="dalam_app"?"internal":"eksternal";

                $cr_id=$post['cr_id'];
                $msgeval="";
                if(isset($post['ev_penyelenggaraan']) && $post['ev_penyelenggaraan'] > 0){
                    //update status set_soal
                    $setsoal=$this->ce->set_soalbyType("penyelenggaraan",$jenis);
                    $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"penyelenggaraan","status"=>"1","setsoal"=>$setsoal,"tipe"=>$jenis);
                    $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                    $msgeval.=" penyelenggaraan  teredit<br>";
                }else{
                   //update status set_soal
                   $setsoal=$this->ce->set_soalbyType("penyelenggaraan",$jenis);
                   $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"penyelenggaraan","status"=>"0","setsoal"=>$setsoal,"tipe"=>$jenis);
                   $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                } 

                if(isset($post['ev_sarana']) && $post['ev_sarana'] > 0){
                    //update status set_soal
                    $setsoal=$this->ce->set_soalbyType("sarana",$jenis);
                    $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"sarana","status"=>"1","setsoal"=>$setsoal,"tipe"=>$jenis);
                    $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                    $msgeval.=" sarana teredit<br>";
                }else{
                   $setsoal=$this->ce->set_soalbyType("sarana",$jenis);
                   $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"sarana","status"=>"0","setsoal"=>$setsoal,"tipe"=>$jenis);
                   $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                }
                 
                
                if(isset($post['ev_narsum']) && $post['ev_narsum'] > 0){
                    foreach($post['narsum'] as $n){
                       $setsoal=$this->ce->set_soalbyType("narasumber",$jenis);
                       $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"narasumber","status"=>"1","pengajar"=>$n,"setsoal"=>$setsoal,"tipe"=>$jenis); 
                       $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
                       $msgeval.=" narsum ".$n." teredit - evaluasi ".$updateevaluasi."<br>";
                    }
                }else{
                  
                }

               //07022023
               //tambahan untuk evaluasi external
               //batal
               /*
               if(isset($post['ev_external']) && $post['ev_external'] > 0){
                   //getsoal penyelenggaraan
                   $setsoal=$this->ce->set_soalbyType("external","external");
                   $inputevaluasi=array('cr_id'=> $cr_id,'jenis'=>'external',"status"=>"1",'setsoal'=>$setsoal);
                   $updateevaluasi=$this->ce->edit_setsoal($inputevaluasi);
                   if($updateevaluasi){
                       $pesan.="kelas dengan evaluasi untuk external telah terbuat<br/>";
                       $posteval=true;
                   }else{
                       $pesan.="evaluasi sarana gagal terbuat<br/>";
                       $posteval=false;
                   }
               }else{
                       //update status set_soal
                       $setsoal=$this->ce->set_soalbyType("external",$jenis);
                       $dataupdate=array("cr_id"=>$cr_id,"jenis"=>"external","status"=>"0","setsoal"=>$setsoal);
                       $updateevaluasi=$this->ce->edit_setsoal($dataupdate);
               }
               */
                   flash_notif_warning(NULL,$url_return);
             
              
           }

        }
    
    }

    function delete($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        if(!has_access('classroom.delete',FALSE)){
            if ( has_access('classroom.delete.own',FALSE)){

                if(user_id() != $classroom['id_petugas']){
                    redirect(404);
                }
            }else{
                redirect(404);
            }
        }

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
            unset($data['user_name']);
            $data['cat_id'] = $data['cat_id']?$data['cat_id']:'';
            $data['id_petugas'] = user_id();
			
			// koneksi ke superapp dan agrowallet ga ikut diduplikasi
			$data['id_lw_classroom'] = '';
			$data['id_superapp_manpro'] = '';
			$data['kode_superapp_manpro'] = '';
			
			$insert = $this->classroom_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$classroom_id,'Duplicate',NULL);
                $msg        = $classroom['cr_name']." berhasil diduplikasi.";
                $url_return = site_url('classroom/detail/').$insert;
                flash_notif_success($msg,$url_return);
            }else{
                $msg        = "Data gagal diduplikasi.";
                $url_return = site_url('classroom/duplicate/'.$classroom_id);
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
			
			// rename url utub
			if($post['Type']=="youtube") {
				$media = str_replace("/watch?v=","/embed/",$media);
			}

            $materi = array(
                'Section'   => 'classroom',
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentId'  => NULL,
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
				'Status'     => 'active',
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

    function prelearning_materi_edit($classroom_id=NULL,$materi_id){
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
                'ContentName'    => NULL,
                'Type'   => NULL,
                'Media'     => NULL,
            );

            if ($classroom['cr_prelearning']){
                $cr_prelearning = $classroom['cr_prelearning'];
                $json_cr_prelearning = preg_replace('/[[:cntrl:]]/', '', $cr_prelearning);
                $extra_prelearning = json_decode($json_cr_prelearning,TRUE);
            }

            $data['request']   = $extra_prelearning['Materi'][$materi_id];

            //print_r($data['prelearning']);
            $data['form_action']    = site_url('classroom/prelearning_materi_edit/'.$classroom_id.'/'.$materi_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Class Room';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'classroom/classroom_prelearning_materi_edit_view';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
            $arr_data = json_decode($json_raw,TRUE);

            $media = isset($post['Media'])?$post['Media'] : $arr_data['Materi'][$materi_id]['Media'];
			
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
			
			// rename url utub
			if($post['Type']=="youtube") {
				$media = str_replace("/watch?v=","/embed/",$media);
			}

            $materi = array(
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
				'Status'  => $post['Status'],
            );
            $arr_data['Materi'][$materi_id]  = $materi;

            //print_r($arr_data);exit();
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_prelearning' => $json_data,
            );

            $update = $this->classroom_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$classroom_id,'Edit','Materi Prelearning');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function prelearning_materi_update_tree($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/prelearning/').$classroom_id.'#sortable';
        }

        if ($classroom['cr_prelearning']){
            $cr_prelearning = $classroom['cr_prelearning'];
            $json_cr_prelearning = preg_replace('/[[:cntrl:]]/', '', $cr_prelearning);
            $extra_prelearning = json_decode($json_cr_prelearning,TRUE);
        }


        $materi   = $extra_prelearning['Materi'];
        $materi_update = array();

        $post   = $this->input->post();
        $order = json_decode($post['order'],TRUE);

        foreach ($order as $k => $v){
            //$materi_update[$v] = $materi[$v];
            $materi_update[] = $materi[$v];
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_prelearning']);
        $arr_data = json_decode($json_raw,TRUE);

        //print_r($materi_update);exit();
        $arr_data['Materi']  = $materi_update;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $classroom_id,
            'cr_prelearning' => $json_data,
        );

        $update = $this->classroom_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$classroom_id,'Edit','Materi Prelearning - Urutan');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
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
		echo 'no longer used';
		exit;
		
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
                //add module assignment 21022023
                'Assignment'  => $post['is_assignment'],
                'InfoAssignment'=>$post['infoassignment']
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
            if($post['is_assignment']=="ya"){
                $arr_data['Module'][$index]['Assignment']="ya";
            }else{
                $arr_data['Module'][$index]['Assignment']=""    ;
            }
            $arr_data['Module'][$index]['InfoAssignment']=$post['infoassignment'];

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
			
			// rename url utub
			if($post['Type']=="youtube") {
				$media = str_replace("/watch?v=","/embed/",$media);
			}

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
			
			// rename url utub
			if($post['Type']=="youtube") {
				$media = str_replace("/watch?v=","/embed/",$media);
			}

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
		
		// kesempatan test
		$this->form_validation->set_message('attempt_err_message', 'Opsi Kesempatan Test Tanpa Batas hanya bisa dipilih jika grade C dan grade D diisi nol.');
		$this->form_validation->set_rules(
			'Attemp', 'Kesempatan Test',
			array(
				'required',
				array(
					'attempt_err_message',
					function($val) {
						$isOK = false;
						$gradeC = (int) $this->input->post('GradeC');
						$gradeD = (int) $this->input->post('GradeD');
						
						if($val=="1") {
							$isOK = true;
						} else if($val=="N") {
							if($gradeC=='0' && $gradeD=='0') $isOK = true;
							else $isOK = false;
						}
						
						return $isOK;
					}
				)
			)
		);
		
        if ($this->form_validation->run() == FALSE){
            $extra_competency = array(
                'ctStart'    => NULL,
                'ctEnd'      => NULL,
				'cr_time_start' => NULL,
                'cr_time_end' 	=> NULL,
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
            $arr_data['TimeLimit']  =  $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];

            $arr_data['Attemp']     =  $post['Attemp'];
            $arr_data['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['ReqPassed']  =  NULL; // Dihilangkan
            $arr_data['Random']     =  $post['Random'];
            $arr_data['GradeA']     =  $post['GradeA'];
            $arr_data['GradeB']     =  $post['GradeB'];
            $arr_data['GradeC']     =  $post['GradeC'];
            $arr_data['GradeD']     =  $post['GradeD'];

            $arr_data['ctStart']    =  parseDate($post['ctStart']);
            $arr_data['ctEnd']      =  parseDate($post['ctEnd']);
			$arr_data['cr_time_start'] = $post['cr_time_start'];
			$arr_data['cr_time_end']   = $post['cr_time_end'];
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
						'id_group' => $this->member_model->getGroupKaryawan($member_id),
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
					'id_group' => $this->member_model->getGroupKaryawan($member_id),
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
        // $classroom = $this->get_classroom($classroom_id);
        // $this->classroom_member_model->delete($crm_id);
		
		// data jgn dihapus, tp cr_id-nya aj dikosongkan
		
		$classroom_id = (int) $classroom_id;
		$crm_id = (int) $crm_id;
		
		$arrJS = array();
		$arrJS['date_deleted'] = date("Y-m-d H:i:s");
		$arrJS['original_cr_id'] = $classroom_id;
		$jsn = json_encode($arrJS);
		
		$sql = "update _classroom_member set cr_id='0', cr_info=concat('".$jsn."||',cr_info) where crm_id='".$crm_id."' and cr_id='".$classroom_id."' ";
		$this->db->query($sql);
		
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
					'id_group' => $this->member_model->getGroupKaryawan($member_id),
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

        //tambahan modul assignment
        $modulecondition=array("classroom_id"=>$classroom_id);
        $data['module_assignment']=$this->classroom_model->get_module_assignment($modulecondition);
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
        $col_length_mm = 1; // Materi Modul

        $col_length_lp = 2; // Learning Point
        $col_length_eva = 4; // Evaluasi
        $col_length_fb = 3; // Feedback

        $col_length_nilai = 1;  // Nilai Modul
        $col_length_progress = 1; // Progress Modul
        $col_length_tgl_mulai = 1; // Tgl Mulai Modul
        $col_length_tgl_selesai = 1; // Tgl Selesai Modul
        $module_no = 1;
        foreach ($module as $k => $v){

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_mm-1].'2');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Materi Modul '.$module_no);

            $cur_alpha = $cur_alpha + $col_length_mm;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_lp-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Learning Point Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Isi');

            $cur_alpha = $cur_alpha + $col_length_lp;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_eva-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Evaluasi Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Jml Soal');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Benar');
            $sheet->setCellValue($alpha[$cur_alpha+3].'2', 'Salah');

            $cur_alpha = $cur_alpha + $col_length_eva;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_fb-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Feedback Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Nilai');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Komentar');

            $cur_alpha = $cur_alpha + $col_length_fb;



            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_nilai-1].'2');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Nilai Modul '.$module_no);

            $cur_alpha = $cur_alpha + $col_length_nilai;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_progress-1].'2');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Progress Modul '.$module_no);

            $cur_alpha = $cur_alpha + $col_length_progress;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_tgl_mulai-1].'2');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Tgl Mulai Modul '.$module_no);

            $cur_alpha = $cur_alpha + $col_length_tgl_mulai;


            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_tgl_selesai-1].'2');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Tgl Selesai Modul '.$module_no);

            $cur_alpha = $cur_alpha + $col_length_tgl_selesai;

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
                $crm_step_json = $row['crm_step'];

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


                $EvaScoreArr = array();
                if ($module){
                    foreach ($module as $k => $v){
						// Materi
                        $result_materi_modul = "";
                        $materi_modul = classroomProgressModuleMateri($crm_step_json, $classroom['cr_id'], $k);
                        if ($materi_modul){
                            foreach ($materi_modul as $i => $j){
                                $result_materi_modul .= "Materi ".$j['MateriNo']." : ".$j['MateriName']."\n(".$j['MateriRead'].")\n\n";
                            }
                        }

                        $sheet->setCellValue($alpha[$cur_alpha].$x, $result_materi_modul );
                        $sheet->getStyle($alpha[$cur_alpha].$x)->getAlignment()->setWrapText(true);

                        // Learning Point
                        $sheet->setCellValue($alpha[$cur_alpha+1].$x, isset($result['MP'][$k]['LearningPoint']['tanggal']) ? ($result['MP'][$k]['LearningPoint']['tanggal'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['LearningPoint']['tanggal'])) : '' ) :''  );
                        $sheet->setCellValue($alpha[$cur_alpha+2].$x, isset($result['MP'][$k]['LearningPoint']['isi']) ? $result['MP'][$k]['LearningPoint']['isi'] : '' );

                        // Evaluasi
                        $sheet->setCellValue($alpha[$cur_alpha+3].$x, isset($result['MP'][$k]['EvaDate']) ? ($result['MP'][$k]['EvaDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['EvaDate'])) : '' ) :'');

                        if (isset($result['MP'][$k]['EvaScore']) AND $result['MP'][$k]['EvaScore']){
                            $EvaScore = explode('-',$result['MP'][$k]['EvaScore']);
                        }else{
                            $EvaScore = array('','','','');
                        }

                        $sheet->setCellValue($alpha[$cur_alpha+4].$x, $EvaScore[1] );
                        $sheet->setCellValue($alpha[$cur_alpha+5].$x, $EvaScore[2] );
                        $sheet->setCellValue($alpha[$cur_alpha+6].$x, $EvaScore[3] );

                        // Feedback
                        $sheet->setCellValue($alpha[$cur_alpha+7].$x, isset($result['MP'][$k]['FbDate']) ? ($result['MP'][$k]['FbDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['FbDate'])) : '' ) :''  );
                        $sheet->setCellValue($alpha[$cur_alpha+8].$x, isset($result['MP'][$k]['FbDesc']) ? $result['MP'][$k]['FbDesc'] : '' );
                        $sheet->setCellValue($alpha[$cur_alpha+9].$x, '' );

                        // Nilai
                        $nilai_modul = ($EvaScore[2] AND $EvaScore[1]) ? ($EvaScore[2]/$EvaScore[1]*100) : '';
                        $sheet->setCellValue($alpha[$cur_alpha+10].$x, $nilai_modul );
						
                        if($v['Evaluasi']['Status']=="active") {
							$EvaScoreArr[$k] = $nilai_modul ? $nilai_modul : 0;
						}
						
                        // Progress
                        $progress_percent = classroomProgressModulePercent($crm_step_json, $k,$classroom['cr_has_learning_point']);
                        $progress_percent_modul = $progress_percent!=0 ? parseThousand($progress_percent).'%' : '';
                        $sheet->setCellValue($alpha[$cur_alpha+11].$x, $progress_percent_modul );

                        // Tgl Mulai
                        $tgl_mulai_modul = isset($result['MP'][$k]['date_access_start']) ? ($result['MP'][$k]['date_access_start'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['date_access_start'])) : '' ) :'';
                        $sheet->setCellValue($alpha[$cur_alpha+12].$x, $tgl_mulai_modul );

                        // Tgl Selesai
                        $tgl_selesai_modul = isset($result['MP'][$k]['date_access_end']) ? ($result['MP'][$k]['date_access_end'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['date_access_end'])) : '' ) :'';
                        $sheet->setCellValue($alpha[$cur_alpha+13].$x, $tgl_selesai_modul );

                        $cur_alpha = $cur_alpha + 14;

                    }
                }


                $sheet->setCellValue($alpha[$cur_alpha].$x, isset($result['CT']['ctDate']) ? ($result['CT']['ctDate'] ? date('d/m/Y H:i',strtotime($result['CT']['ctDate'])) : '' ) :'');
                $sheet->setCellValue($alpha[$cur_alpha+1].$x, $ctScore[1] );
                $sheet->setCellValue($alpha[$cur_alpha+2].$x, $ctScore[2] );
                $sheet->setCellValue($alpha[$cur_alpha+3].$x, $ctScore[3] );

                $sheet->setCellValue($alpha[$cur_alpha+4].$x, isset($result['RESULT']) ? $result['RESULT'] : '' );

                if ($classroom['cr_has_kompetensi_test']){
                    $endScore = $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '';
                }else{
                    $endScore = $EvaScoreArr ? number_format((array_sum($EvaScoreArr)/count($EvaScoreArr)),1,',','.') : '';
                }
                $sheet->setCellValue($alpha[$cur_alpha+5].$x, ($endScore!='0,0' ? $endScore : '') );

                $sheet->setCellValue($alpha[$cur_alpha+6].$x, $content_text);
                if ($content_url){
                    $sheet->getCell($alpha[$cur_alpha+6].$x)->getHyperlink()->setUrl($content_url);
                }

                $last_cell = $alpha[$cur_alpha+6].$x;

                $x++;


            }

        }


        create_log($this->section_id,$classroom_id,'Export','Progress Member');

        // All collumn autosize
        foreach(range('A','ZZ') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }


        // Set Alignment
        $style = array();
        $style ['alignment']=array();
        $style ['alignment']['vertical'] = "top";
        $style ['alignment']['horizontal'] = "left";
        $sheet->getStyle ( 'A1:'.$last_cell )->applyFromArray ($style);

        // Set Border
        $sheet->getStyle ( 'A1:'.$last_cell )->getBorders()->getAllBorders()->setBorderStyle("thin");


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

    /* -- evaluasi level 3 start -- */
	
	function evaluasi_lv3($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }

        // $this->load->library('form_validation');
        // $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');
		
		$mode = "";
		$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
		$res = $this->db->query($sql);
		$num = $res->num_rows();
		if($num<=0) {
			$mode = "add";
			
			$tahun_evaluasi = date("Y");
			$deskripsi_pelatihan = '';
			$target_peserta = '';
			$tujuan_pelatihan = '';
			$jangka_waktu_evaluasi_jumlah = '';
			$jangka_waktu_evaluasi_satuan = '';
			$tanggal_mulai = '';
			$tanggal_selesai = '';
			$bobot_soal_ksa = '';
			$bobot_soal_b = '';
			$bobot_atasan = '';
			$bobot_kolega = '';
			$daftar_pertanyaan = array();
			$status = '1';
			$simpan_final_enabled = '1';
		} else {
			$mode = "edit";
			
			$row = $res->result_array();
			
			$tahun_evaluasi = $row[0]['tahun_evaluasi'];
			$deskripsi_pelatihan = $row[0]['deskripsi_pelatihan'];
			$target_peserta = $row[0]['target_peserta'];
			$tujuan_pelatihan = $row[0]['tujuan_pelatihan'];
			$jangka_waktu_evaluasi_jumlah = $row[0]['jangka_waktu_evaluasi_jumlah'];
			$jangka_waktu_evaluasi_satuan = $row[0]['jangka_waktu_evaluasi_satuan'];
			$tanggal_mulai = date("Y-m-d H:i", strtotime($row[0]['tanggal_mulai']));
			$tanggal_selesai = date("Y-m-d H:i", strtotime($row[0]['tanggal_selesai']));
			$bobot_soal_ksa = $row[0]['bobot_soal_ksa'];
			$bobot_soal_b = $row[0]['bobot_soal_b'];
			$bobot_atasan = $row[0]['bobot_atasan'];
			$bobot_kolega = $row[0]['bobot_kolega'];
			$daftar_pertanyaan = json_decode($row[0]['daftar_pertanyaan'],true);
			$status = $row[0]['status'];
			$simpan_final_enabled = $row[0]['simpan_final_enabled'];
		}
		
		$strError = '';
		
		$post = $this->input->post();
		if(!empty($post)) {
			$tahun_evaluasi = $post['tahun_evaluasi'];
			$deskripsi_pelatihan = $post['deskripsi_pelatihan'];
			$target_peserta = $post['target_peserta'];
			$tujuan_pelatihan = $post['tujuan_pelatihan'];
			$jangka_waktu_evaluasi_jumlah = $post['jangka_waktu_evaluasi_jumlah'];
			$jangka_waktu_evaluasi_satuan = $post['jangka_waktu_evaluasi_satuan'];
			$tanggal_mulai = $post['tanggal_mulai'];
			$tanggal_selesai = $post['tanggal_selesai'];
			$bobot_soal_ksa = (int) $post['bobot_soal_ksa'];
			$bobot_soal_b = (int) $post['bobot_soal_b'];
			$bobot_atasan = (int) $post['bobot_atasan'];
			$bobot_kolega = (int) $post['bobot_kolega'];
			$status = (int) $post['status'];
			$simpan_final_enabled = (int) $post['simpan_final_enabled'];
			
			$total_bobot_soal = $bobot_soal_ksa + $bobot_soal_b;
			$total_bobot_penilai = $bobot_atasan + $bobot_kolega;
			
			if(empty($tahun_evaluasi)) $strError .= '<li>Tahun Evaluasi masih kosong.</li>';
			if(empty($deskripsi_pelatihan)) $strError .= '<li>Deskripsi Pelatihan masih kosong.</li>';
			if(empty($target_peserta)) $strError .= '<li>Target Peserta masih kosong.</li>';
			if(empty($tujuan_pelatihan)) $strError .= '<li>Tujuan Pelatihan masih kosong.</li>';
			if(empty($jangka_waktu_evaluasi_jumlah)) $strError .= '<li>Jangka Penilaian Evaluasi Perilaku Pasca Pelatihan masih kosong.</li>';
			if(empty($jangka_waktu_evaluasi_satuan)) $strError .= '<li>Satuan Jangka Penilaian Evaluasi Perilaku Pasca Pelatihan masih kosong.</li>';
			if(empty($tanggal_mulai)) $strError .= '<li>Tanggal Mulai masih kosong.</li>';
			if(empty($tanggal_selesai)) $strError .= '<li>Tanggal Selesai masih kosong.</li>';
			if($total_bobot_soal!=100) $strError .= '<li>Total bobot pertanyaan harus 100%.</li>';
			if($total_bobot_penilai!=100) $strError .= '<li>Total bobot penilai harus 100%.</li>';
			
			// ada error?
			if(strlen($strError)<=0) {
				// escape string dl
				$tahun_evaluasi = $this->db->escape_str($tahun_evaluasi);
				$deskripsi_pelatihan = $this->db->escape_str($deskripsi_pelatihan);
				$target_peserta = $this->db->escape_str($target_peserta);
				$tujuan_pelatihan = $this->db->escape_str($tujuan_pelatihan);
				$jangka_waktu_evaluasi_jumlah = $this->db->escape_str($jangka_waktu_evaluasi_jumlah);
				$jangka_waktu_evaluasi_satuan = $this->db->escape_str($jangka_waktu_evaluasi_satuan);
				$tanggal_mulai = $this->db->escape_str($tanggal_mulai);
				$tanggal_selesai = $this->db->escape_str($tanggal_selesai);
				$bobot_soal_ksa = $this->db->escape_str($bobot_soal_ksa);
				$bobot_soal_b = $this->db->escape_str($bobot_soal_b);
				$bobot_atasan = $this->db->escape_str($bobot_atasan);
				$bobot_kolega = $this->db->escape_str($bobot_kolega);
				$status = $this->db->escape_str($status);
				$simpan_final_enabled = $this->db->escape_str($simpan_final_enabled);
				
				$sql =
					"insert into _classroom_evaluasi_lv3_header set	
						cr_id='".$classroom_id."',
						tahun_evaluasi='".$tahun_evaluasi."',
						deskripsi_pelatihan='".$deskripsi_pelatihan."',
						target_peserta='".$target_peserta."',
						tujuan_pelatihan='".$tujuan_pelatihan."',
						jangka_waktu_evaluasi_jumlah='".$jangka_waktu_evaluasi_jumlah."',
						jangka_waktu_evaluasi_satuan='".$jangka_waktu_evaluasi_satuan."',
						tanggal_mulai='".$tanggal_mulai."',
						tanggal_selesai='".$tanggal_selesai."',
						bobot_soal_ksa='".$bobot_soal_ksa."',
						bobot_soal_b='".$bobot_soal_b."',
						bobot_atasan='".$bobot_atasan."',
						bobot_kolega='".$bobot_kolega."',
						status='".$status."',
						simpan_final_enabled='".$simpan_final_enabled."'
					 on duplicate key update
						tahun_evaluasi='".$tahun_evaluasi."',
						deskripsi_pelatihan='".$deskripsi_pelatihan."',
						target_peserta='".$target_peserta."',
						tujuan_pelatihan='".$tujuan_pelatihan."',
						jangka_waktu_evaluasi_jumlah='".$jangka_waktu_evaluasi_jumlah."',
						jangka_waktu_evaluasi_satuan='".$jangka_waktu_evaluasi_satuan."',
						tanggal_mulai='".$tanggal_mulai."',
						tanggal_selesai='".$tanggal_selesai."',
						bobot_soal_ksa='".$bobot_soal_ksa."',
						bobot_soal_b='".$bobot_soal_b."',
						bobot_atasan='".$bobot_atasan."',
						bobot_kolega='".$bobot_kolega."',
						status='".$status."',
						simpan_final_enabled='".$simpan_final_enabled."'
					 ";
				$res = $this->db->query($sql);
				
				create_log($this->section_id,$classroom_id,'Edit','Evaluasi Pelatihan Lv.3');
                flash_notif_success(NULL,$url_return);
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['request']['tahun_evaluasi'] = $tahun_evaluasi;
		$data['request']['deskripsi_pelatihan'] = $deskripsi_pelatihan;
		$data['request']['target_peserta'] = $target_peserta;
		$data['request']['tujuan_pelatihan'] = $tujuan_pelatihan;
		$data['request']['jangka_waktu_evaluasi_jumlah'] = $jangka_waktu_evaluasi_jumlah;
		$data['request']['jangka_waktu_evaluasi_satuan'] = $jangka_waktu_evaluasi_satuan;
		$data['request']['tanggal_mulai'] = $tanggal_mulai;
		$data['request']['tanggal_selesai'] = $tanggal_selesai;
		$data['request']['bobot_soal_ksa'] = $bobot_soal_ksa;
		$data['request']['bobot_soal_b'] = $bobot_soal_b;
		$data['request']['bobot_atasan'] = $bobot_atasan;
		$data['request']['bobot_kolega'] = $bobot_kolega;
		$data['request']['status'] = $status;
		$data['request']['simpan_final_enabled'] = $simpan_final_enabled;
		
		$data['daftar_pertanyaan'] = $daftar_pertanyaan;
		
		$data['seld_minggu'] = ($jangka_waktu_evaluasi_satuan=="minggu")? ' selected="selected" ' : '';
		$data['seld_bulan'] = ($jangka_waktu_evaluasi_satuan=="bulan")? ' selected="selected" ' : '';
		$data['seld_simpan_final_1'] = ($simpan_final_enabled=="1")? ' checked="checked" ' : '';
		$data['seld_simpan_final_0'] = ($simpan_final_enabled=="0")? ' checked="checked" ' : '';
		$data['seld_status_1'] = ($status=="1")? ' checked="checked" ' : '';
		$data['seld_status_0'] = ($status=="0")? ' checked="checked" ' : '';
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3/'.$classroom_id);
		$data['editable']      = TRUE;
		$data['page_name']      = 'Class Room';
		$data['page_sub_name']  = $classroom['cr_name'];
		$data['page'] = 'classroom/classroom_evaluasi_lv3_view';
		$data['submenu'] = 'classroom/classroom_detail_submenu_view';
		$this->load->view('main_view',$data);
    }
	
	function evaluasi_lv3_pertanyaan_update($classroom_id=NULL,$pertanyaan_kategori=NULL,$index=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }
		
		$judul = "";
		$mode = "";
		$strError = '';
		
		// header evaluasi_lv3 sudah ada?
		$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
		$res = $this->db->query($sql);
		$num = $res->num_rows();
		if($num<=0) {
			$strError .= '<li>Masukkan data deskripsi evaluasi pelatihan lv.3 terlebih dahulu.</li>';
		} else {
			$row = $res->result_array();
			
			$daftar_pertanyaan = $row[0]['daftar_pertanyaan'];
			$json_pertanyaan = json_decode($daftar_pertanyaan,true);
		}
		
		if(!isset($index)) {
			$judul = "Tambah";
			$mode = "add";
			
			$pertanyaan = '';
			$kategori = '';
		} else {
			$judul = "Update";
			$mode = "edit";
			
			$pertanyaan = $json_pertanyaan[$pertanyaan_kategori][$index];
			$kategori = $pertanyaan_kategori;
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$pertanyaan = $post['pertanyaan'];
			$kategori = $post['kategori'];
			
			if($mode=="edit") {
				$kategori = $pertanyaan_kategori;
			}
			
			if(empty($pertanyaan)) $strError .= '<li>Pertanyaan masih kosong.</li>';
			if(empty($kategori)) $strError .= '<li>Kategori masih kosong.</li>';
			
			// ada error?
			if(strlen($strError)<=0) {
				// escape string dl
				$json_raw = preg_replace('/[[:cntrl:]]/', '', $daftar_pertanyaan);
				$arr_data = json_decode($json_raw,TRUE);
				
				if($mode=="add") {
					$arr_data[$kategori][] = $pertanyaan;
				} else if($mode=="edit") {
					$arr_data[$kategori][$index] = $pertanyaan;
				}
				
				$json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);
				
				$json_data = $this->db->escape_str($json_data);
				$sql =
					"update _classroom_evaluasi_lv3_header set	
						daftar_pertanyaan='".$json_data."'
					 where cr_id='".$classroom_id."' ";
				$res = $this->db->query($sql);
				
				create_log($this->section_id,$classroom_id,'Edit','Evaluasi Pelatihan Lv.3 - Pertanyaan');
                flash_notif_success(NULL,$url_return);
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['request']['pertanyaan'] = $pertanyaan;
		$data['request']['kategori'] = $kategori;
		
		$data['judul'] = $judul;
		$data['mode'] = $mode;
		$data['seld_skill'] = ($kategori=="skill")? ' selected="selected" ' : '';
		$data['seld_attitude'] = ($kategori=="attitude")? ' selected="selected" ' : '';
		$data['seld_behaviour'] = ($kategori=="behaviour")? ' selected="selected" ' : '';
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_pertanyaan_update/'.$classroom_id.'/'.$pertanyaan_kategori.'/'.$index);
		$data['editable']      = TRUE;
		$data['page_name']      = 'Classroom';
		$data['page_sub_name']  = $judul.' Pertanyaan Evaluasi Pelatihan Lv.3'.'<br><small>'.$classroom['cr_name'].'</small>';
		$data['page'] = 'classroom/classroom_evaluasi_lv3_pertanyaan_update_view';
		$data['submenu'] = 'classroom/classroom_detail_submenu_view';
		$this->load->view('main_view',$data);
    }

    function evaluasi_lv3_pertanyaan_delete($classroom_id=NULL,$pertanyaan_kategori=NULL,$index=NULL){
        $classroom = $this->get_classroom($classroom_id);
		
		$url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }
        
		// header evaluasi_lv3 sudah ada?
		$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
		$res = $this->db->query($sql);
		$num = $res->num_rows();
		if($num<=0) {
			$msg = 'Data tidak ditemukan.';
			flash_notif_warning($msg,$url_return);
		} else {
			$row = $res->result_array();
			
			$daftar_pertanyaan = $row[0]['daftar_pertanyaan'];
			$json_pertanyaan = json_decode($daftar_pertanyaan,true);
			
			$pertanyaan = $json_pertanyaan[$pertanyaan_kategori][$index];
			$kategori = $pertanyaan_kategori;
			
			unset($json_pertanyaan[$kategori][$index]);
			
			$json_data = json_encode($json_pertanyaan, JSON_UNESCAPED_SLASHES);
			
			$json_data = $this->db->escape_str($json_data);
			$sql =
				"update _classroom_evaluasi_lv3_header set	
					daftar_pertanyaan='".$json_data."'
				 where cr_id='".$classroom_id."' ";
			$res = $this->db->query($sql);
			
			create_log($this->section_id,$classroom_id,'Hapus','Evaluasi Pelatihan Lv.3 - Pertanyaan');
			flash_notif_success('Data berhasil dihapus',NULL,FALSE);
			redirect(site_url('classroom/evaluasi_lv3/').$classroom_id);
		}
    }
	
	function evaluasi_lv3_peserta($classroom_id=NULL){
		
		$classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }
		
		// get peserta classroom
		$ui = '';
		$sql =
			"select m.member_id, m.member_name, m.member_nip, g.group_name, cm.crm_step 
			 from _classroom_member cm, _member m, _group g
			 where cm.member_id=m.member_id and m.member_status='active' and cm.is_pk='0' and cm.member_status='1' and cr_id='".$classroom_id."' and g.group_id=m.group_id
			 order by g.group_name, m.member_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		$data['row'] = $row;
		
		$strError = "";
		if(isset($_SESSION['err_message'])) {
			$strError = $_SESSION['err_message'];
			unset($_SESSION['err_message']);
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_peserta/'.$classroom_id);
		$data['editable']      = false;
		$data['page_name']      = 'Class Room';
		$data['page_sub_name']  = $classroom['cr_name'];
		$data['page'] = 'classroom/classroom_evaluasi_lv3_peserta_view';
		$data['submenu'] = 'classroom/classroom_detail_submenu_view';
		$this->load->view('main_view',$data);
	}
	
	function evaluasi_lv3_add_picker($classroom_id=NULL){
		
		$classroom = $this->get_classroom($classroom_id);
		
		$post = $this->input->post();
        $member_ids = $post['member_ids'];
		$kat = $post['kat'];
		
		$arrK = explode('_',$kat);
		$status_penilai = $arrK['0'];
		$id_dinilai = (int) $arrK['1'];

        foreach ($member_ids as $v){
            $id_penilai = (int) $v;
			
			$did = uniqid('EVPAIR');
			
			$sql =
				"insert into _classroom_evaluasi_lv3_pairing set
					id='".$did."',
					cr_id='".$classroom_id."',
					id_penilai='".$id_penilai."',
					status_penilai='".$status_penilai."',
					id_dinilai='".$id_dinilai."'
				 on duplicate key update
					id_penilai='".$id_penilai."' ";
			$res = $this->db->query($sql);
			
			create_log($this->section_id,$classroom_id,'Tambah','Evaluasi Pelatihan lv.3 - Penilai Dinilai ['.$id_penilai.']['.$id_dinilai.']');
        }
		
        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );
	}
	
	function evaluasi_lv3_remove_picker($classroom_id=NULL,$id_pairing=NULL){
        $classroom = $this->get_classroom($classroom_id);
		
		if(!empty($id_pairing)) {
			$sql = "delete from _classroom_evaluasi_lv3_pairing where cr_id='".$classroom_id."' and id='".$id_pairing."' ";
			$res = $this->db->query($sql);
		}
		
        create_log($this->section_id,$classroom_id,'Hapus','Evaluasi Pelatihan Lv.3 - Penilai Dinilai');
        redirect(site_url('classroom/evaluasi_lv3_peserta/'.$classroom_id));
    }
	
	function evaluasi_lv3_sync_atasan_aghris($classroom_id=NULL,$req_member_id=NULL){
		// no longer used, matikan
		exit;
		/*
		ini_set('max_execution_time', '3600'); // 60 menit
		$classroom = $this->get_classroom($classroom_id);
		$req_member_id = (int) $req_member_id;

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = site_url('classroom/evaluasi_lv3_peserta/').$classroom_id;
        
		$strError = '';
		$strErrorT1 = '';
		$strErrorT2 = '';
		$addSql = '';
		if($req_member_id>0) {
			$addSql .= " and m.member_id='".$req_member_id."' ";
		}
		
		// get peserta classroom
		
		$arrHelper = array();
		$arrHelper[1] = '12220';
		$arrHelper[2] = '12222';
		$arrHelper[3] = '12223';
		$arrHelper[4] = '12224';
		$arrHelper[5] = '12225';
		$arrHelper[6] = '12226';
		$arrHelper[7] = '12227';
		$arrHelper[8] = '12228';
		$arrHelper[9] = '12229';
		$arrHelper[10] = '12230';
		$arrHelper[11] = '12231';
		$arrHelper[12] = '12232';
		$arrHelper[13] = '12233';
		$arrHelper[14] = '12234';
		$arrHelper[15] = '12235';
		
		
		/*-/ temp helper, digunakan untuk sync massal atasan karyawan -- start (part 1 of 2)
		ini_set('max_execution_time', '0');
		
		$tahun_eva = 2021;
		$sqlT = " select h.cr_id, c.cr_name from _classroom_evaluasi_lv3_header h, _classroom c where h.cr_id=c.cr_id and h.tahun_evaluasi='".$tahun_eva."' ";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		foreach($rowT as $keyT => $valT) {
			$classroom_id = $valT['cr_id'];
			$classroom_name = $valT['cr_name'];
			$strError = '';
			$strErrorT1 = '';
			$strErrorT2 = '';
		// temp helper, digunakan untuk sync massal atasan karyawan -- end (part 1 of 2)
		// *-/
		
			$ui = '';
			$sql =
				"select m.member_id, m.member_name, m.member_nip, g.group_name, m.group_id
				 from _classroom_member cm, _member m, _group g
				 where g.group_id=m.group_id and g.aghris_company_code!='' and cm.member_id=m.member_id and m.member_status='active' and cr_id='".$classroom_id."' ".$addSql."
				 order by m.member_name ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			foreach($row as $key => $val) {
				$id_atasan = 0;
				$member_id = $val['member_id'];
				$member_nip = $val['member_nip'];
				$member_name = $val['member_name'];
				$group_name = $val['group_name'];
				$group_id = $val['group_id'];
				$nip_atasan = '';
				
				$resultA = aghris_search_by_nik($val['member_nip']);
				if(!empty($resultA[0]['nik_sap_atasan'])) $nip_atasan = $resultA[0]['nik_sap_atasan'];
				if(!empty($nip_atasan)) {
					$data_atasan = $this->member_model->get_by_nip($nip_atasan);
					// atasan ditemukan?
					if(!empty($data_atasan)) {
						$id_atasan = $data_atasan['member_id'];
					} else {
						// tambahkan ke agronow
						$result = aghris_search_by_nik($nip_atasan);
						
						if(!empty($result)) {					
							$group_id = "";
							if(is_null($result[0]['company_code'])) {
								$group_id = "0";
							} else {
								$sqlC = "select group_id from _group where aghris_company_code like '%[".trim($result[0]['company_code'])."]%' ";
								$resC = $this->db->query($sqlC);
								$rowC = $resC->result_array();
								$group_id = @$rowC[0]['group_id'];
							}
							if(empty($group_id)) $group_id = 0;
							
							$jenis_kelamin = '';
							if(is_null($result[0]['jenis_kelamin'])) {
								$jenis_kelamin = '';
							} else {
								$jenis_kelamin = (strtolower($jenis_kelamin)=="male")?'Pria':'Wanita';
							}
							
							// Sync Member
							$data_member = array(
								'group_id'          => $group_id,
								'jabatan_id'        => null,
								'mlevel_id'         => 6,
								'member_name'       => $result[0]['employee_name'],
								'member_nip'        => $result[0]['nik_sap'],
								'member_token'      => is_null($result[0]['token'])?'':$result[0]['token'],
								'member_jabatan'    => is_null($result[0]['job_descr'])?'':$result[0]['job_descr'],
								'member_email'      => is_null($result[0]['email'])?'':$result[0]['email'],
								'member_kel_jabatan'=> is_null($result[0]['position_descr'])?'':$result[0]['position_descr'],
								'member_image'      => is_null($result[0]['employee_foto'])?'':$result[0]['employee_foto'],
								'member_unit_kerja' => is_null($result[0]['personnel_area_descr'])?'':$result[0]['personnel_area_descr'],
								'member_gender'     => $jenis_kelamin,
								'member_birth_place' => is_null($result[0]['birth_place'])?'':$result[0]['birth_place'],
								'member_birth_date' => is_null($result[0]['birth_date'])?'':$result[0]['birth_date'],
								'member_phone'      => is_null($result[0]['phone'])?'':$result[0]['phone'],
								'member_address'    => is_null($result[0]['address'])?'':$result[0]['address'],
								'member_city'       => is_null($result[0]['city'])?'':$result[0]['city'],
								'member_province'   => is_null($result[0]['province'])?'':$result[0]['province'],
								'member_postcode'   => is_null($result[0]['postcode'])?'':$result[0]['postcode'],
								'member_ceo'        => '0',
								'member_create_date'=> is_null($result[0]['create_date'])?'':$result[0]['create_date']
							);
						
							// Insert
							$data_member['member_password'] = md5(trim($data_member['member_nip']));
							$data_member['member_status']   = 'active';
							$data_member['member_poin']     = 0;
							$data_member['member_saldo']    = 0;
							$id_atasan = $this->member_model->insert($data_member);
						}
					}
				}
				
				// gunakan akun helper jika atasan tidak ditemukan
				if(empty($id_atasan)) {
					$id_atasan = $arrHelper[$group_id];
				}
				
				// id atasan ditemukan?
				if($id_atasan>0) {
					$id_penilai = $id_atasan;
					$status_penilai = 'atasan';
					$id_dinilai = $member_id;
					
					$did = uniqid('EVPAIR');
				
					$sql =
						"insert into _classroom_evaluasi_lv3_pairing set
							id='".$did."',
							cr_id='".$classroom_id."',
							id_penilai='".$id_penilai."',
							status_penilai='".$status_penilai."',
							id_dinilai='".$id_dinilai."'
						 on duplicate key update
							id_penilai='".$id_penilai."' ";
					$res = $this->db->query($sql);
					
					create_log($this->section_id,$classroom_id,'Tambah','Evaluasi Pelatihan lv.3 - Penilai Dinilai ['.$id_penilai.']['.$id_dinilai.']');
				} else {
					$st =
						'<tr>
							<td>'.$group_name.'</td>
							<td>'.$member_nip.'</td>
							<td>'.$member_name.'</td>
							<td>'.$classroom['cr_name'].'</td>
							<td>'.$nip_atasan.'</td>
						 </tr>';
					if(empty($nip_atasan)) $strErrorT1 .= $st;
					else $strErrorT2 .= $st;
				}
			}
			
		/*-/ temp helper, digunakan untuk sync massal atasan karyawan -- start (part 2 of 2)
			$strError = $strErrorT1.$strErrorT2;
			if(strlen($strError)>0) {
				echo 'Nama Kelas: '.$classroom_name.'<br/>';
				echo '<ul>'.$strError.'</ul>';
				echo '<hr/>';
			}
		}
		exit;
		// temp helper, digunakan untuk sync massal atasan karyawan -- end (part 2 of 2)
		// *-/
		
		$strError = $strErrorT1.$strErrorT2;
		if(strlen($strError)>0) {
			$_SESSION['err_message'] =
				'<li>Kelas '.$classroom['cr_name'].' ('.$classroom['cr_id'].')</li>
				 <li>
					<table class="table table-sm">
					<tr>
						<td>Group</td>
						<td>NIK</td>
						<td>Nama</td>
						<td>Classroom</td>
						<td>NIK Atasan</td>
					</tr>
					'.$strError.'
					</table>
				 </li>';
			redirect($url_return);
		} else {
			flash_notif_success(NULL,$url_return);
		}
		*/
	}
	
	function evaluasi_lv3_salin($classroom_id=NULL){

        $classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }
		
		$strError = '';
		
		$id_disalin = "";
		
		// header evaluasi_lv3 sudah ada?
		$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
		$res = $this->db->query($sql);
		$num = $res->num_rows();
		if($num>0) {
			$strError .= '<li>Tidak dapat menyalin data evaluasi pelatihan lv.3 dari kelas lain ke kelas ini karena kelas ini sudah ada datanya.</li>';
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			$id_disalin = (int) $post['id_disalin'];
			
			if($id_disalin==$classroom_id) $id_disalin = "";
			
			if(empty($id_disalin)) {
				$strError .= '<li>ID Class Room masih kosong.</li>';
			} else {
				$sqlC = "select * from _classroom_evaluasi_lv3_header where cr_id='".$id_disalin."' ";
				$resC = $this->db->query($sqlC);
				$num = $resC->num_rows();
				if($num<=0) {
					$strError .= '<li>ID Class Room '.$id_disalin.' tidak memiliki data yang dapat disalin.</li>';
				}
			}
			
			// ada error?
			if(strlen($strError)<=0) {
				$row = $resC->result_array();
				$tahun_evaluasi = $this->db->escape_str($row[0]['tahun_evaluasi']);
				$deskripsi_pelatihan = $this->db->escape_str($row[0]['deskripsi_pelatihan']);
				$target_peserta = $this->db->escape_str($row[0]['target_peserta']);
				$tujuan_pelatihan = $this->db->escape_str($row[0]['tujuan_pelatihan']);
				$jangka_waktu_evaluasi_jumlah = $this->db->escape_str($row[0]['jangka_waktu_evaluasi_jumlah']);
				$jangka_waktu_evaluasi_satuan = $this->db->escape_str($row[0]['jangka_waktu_evaluasi_satuan']);
				$tanggal_mulai = $this->db->escape_str($row[0]['tanggal_mulai']);
				$tanggal_selesai = $this->db->escape_str($row[0]['tanggal_selesai']);
				$bobot_soal_ksa = $this->db->escape_str($row[0]['bobot_soal_ksa']);
				$bobot_soal_b = $this->db->escape_str($row[0]['bobot_soal_b']);
				$bobot_atasan = $this->db->escape_str($row[0]['bobot_atasan']);
				$bobot_kolega = $this->db->escape_str($row[0]['bobot_kolega']);
				$daftar_pertanyaan = $this->db->escape_str($row[0]['daftar_pertanyaan']);
				
				$sql =
					"insert into _classroom_evaluasi_lv3_header set	
						cr_id='".$classroom_id."',
						tahun_evaluasi='".$tahun_evaluasi."',
						deskripsi_pelatihan='".$deskripsi_pelatihan."',
						target_peserta='".$target_peserta."',
						tujuan_pelatihan='".$tujuan_pelatihan."',
						jangka_waktu_evaluasi_jumlah='".$jangka_waktu_evaluasi_jumlah."',
						jangka_waktu_evaluasi_satuan='".$jangka_waktu_evaluasi_satuan."',
						tanggal_mulai='".$tanggal_mulai."',
						tanggal_selesai='".$tanggal_selesai."',
						bobot_soal_ksa='".$bobot_soal_ksa."',
						bobot_soal_b='".$bobot_soal_b."',
						bobot_atasan='".$bobot_atasan."',
						bobot_kolega='".$bobot_kolega."',
						daftar_pertanyaan='".$daftar_pertanyaan."'
					 on duplicate key update
						tahun_evaluasi='".$tahun_evaluasi."',
						deskripsi_pelatihan='".$deskripsi_pelatihan."',
						target_peserta='".$target_peserta."',
						tujuan_pelatihan='".$tujuan_pelatihan."',
						jangka_waktu_evaluasi_jumlah='".$jangka_waktu_evaluasi_jumlah."',
						jangka_waktu_evaluasi_satuan='".$jangka_waktu_evaluasi_satuan."',
						tanggal_mulai='".$tanggal_mulai."',
						tanggal_selesai='".$tanggal_selesai."',
						bobot_soal_ksa='".$bobot_soal_ksa."',
						bobot_soal_b='".$bobot_soal_b."',
						bobot_atasan='".$bobot_atasan."',
						bobot_kolega='".$bobot_kolega."',
						daftar_pertanyaan='".$daftar_pertanyaan."'
					 ";
				$res = $this->db->query($sql);
				
				create_log($this->section_id,$classroom_id,'Salin','Evaluasi Pelatihan Lv.3');
                flash_notif_success(NULL,$url_return);
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['request']['id_disalin'] = $id_disalin;
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_salin/'.$classroom_id);
		$data['editable']      = TRUE;
		$data['page_name']      = 'Classroom';
		$data['page_sub_name']  = 'Salin Evaluasi Pelatihan Lv.3'.'<br><small>'.$classroom['cr_name'].'</small>';
		$data['page'] = 'classroom/classroom_evaluasi_lv3_salin_view';
		$data['submenu'] = 'classroom/classroom_detail_submenu_view';
		$this->load->view('main_view',$data);
    }
	
	function evaluasi_lv3_rekap($classroom_id=NULL){
		$classroom = $this->get_classroom($classroom_id);

        $data['classroom'] = $classroom;
        $data['request']   = $classroom;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom/evaluasi_lv3/').$classroom_id;
        }
		
		// header evaluasi_lv3 sudah ada?
		$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$daftar_pertanyaan = json_decode($row[0]['daftar_pertanyaan'],true);
		$bobot_soal_ksa = $row[0]['bobot_soal_ksa'];
		$bobot_soal_b = $row[0]['bobot_soal_b'];
		$bobot_atasan = $row[0]['bobot_atasan'];
		$bobot_kolega = $row[0]['bobot_kolega'];
		$log_rekap = $row[0]['log_rekap'];
		
		// progress penilai seperti apa?
		$sqlC =
			"select
				m.member_nip, m.member_name, 
				g.group_name, 
				p.status_penilai, p.id_dinilai, p.progress 
			 from _classroom_evaluasi_lv3_pairing p, _member m, _group g 
			 where p.cr_id='".$classroom_id."' and p.id_penilai=m.member_id and g.group_id=m.group_id
			 order by p.progress, g.group_name, m.member_name";
		$resC = $this->db->query($sqlC);
		$rowC = $resC->result_array();
		
		// ada error?
		$strError = "";
		if(isset($_SESSION['err_message'])) {
			$strError = $_SESSION['err_message'];
			unset($_SESSION['err_message']);
		}
		
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		$data['log_rekap'] = $log_rekap;
		$data['rowC'] = $rowC;
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_do_rekap/cr/'.$classroom_id);
		$data['editable']      = TRUE;
		$data['page_name']      = 'Classroom';
		$data['page_sub_name']  = 'Progress dan Rekap Evaluasi Pelatihan Lv.3'.'<br><small>'.$classroom['cr_name'].'</small>';
		$data['page'] = 'classroom/classroom_evaluasi_lv3_rekap_view';
		$data['submenu'] = 'classroom/classroom_detail_submenu_view';
		$this->load->view('main_view',$data);
    }
	
	function evaluasi_lv3_do_rekap($kat=NULL,$did=NULL){
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// $did = (int) $did;
		
		$strError = '';
		$url_return = '';
		$addSql = '';
		
		if($kat=="cr") {
			$url_return = site_url('classroom/evaluasi_lv3/').$did;
			$addSql = " and cr_id='".$did."' ";
		} else if($kat=="th") {
			$url_return = site_url('classroom/evaluasi_lv3_list/').$did;
			$addSql = " and tahun_evaluasi='".$did."' ";
		}
		
		$this->db->trans_start();
		
		// sql utama
		$sqlU = "select cr_id from _classroom_evaluasi_lv3_header where status='1' ".$addSql." ";
		$resU = $this->db->query($sqlU);
		$rowU = $resU->result_array();
		foreach($rowU as $keyU => $valU) {
			$classroom_id = $valU['cr_id'];
			
			// ambil info bobot evaluasi_lv3
			$sql = "select * from _classroom_evaluasi_lv3_header where cr_id='".$classroom_id."' ";
			$res = $this->db->query($sql);
			$row = $res->result_array();
			$bobot_soal_ksa = $row[0]['bobot_soal_ksa'];
			$bobot_soal_b = $row[0]['bobot_soal_b'];
			$bobot_atasan = $row[0]['bobot_atasan'];
			$bobot_kolega = $row[0]['bobot_kolega'];
				
			// ambil yg sudah selesai dinilai
			$sqlC = "select id_dinilai from _classroom_evaluasi_lv3_pairing where cr_id='".$classroom_id."' and progress='100' group by id_dinilai ";
			$resC = $this->db->query($sqlC);
			$rowC = $resC->result_array();
			foreach($rowC as $key => $val) {
				$id_dinilai = $val['id_dinilai'];
				
				$arrA = array();
				$arrD = array();
				$arrD['id_dinilai'] = $id_dinilai;
				
				// get detail jawaban penilai
				$sqlC2 = "select status_penilai, jawaban from _classroom_evaluasi_lv3_pairing where cr_id='".$classroom_id."' and id_dinilai='".$val['id_dinilai']."' and progress='100' ";
				$resC2 = $this->db->query($sqlC2);
				$rowC2 = $resC2->result_array();
				foreach($rowC2 as $key2 => $val2) {
					$status_penilai = $val2['status_penilai'];
					
					$arrJ = @json_decode($val2['jawaban'],true);
					if(is_array($arrJ)) { // jawaban ditemukan
						$arrD['juml_'.$status_penilai]++;
						foreach($arrJ['nilai_kategori'] as $key3 => $val3) {
							foreach($val3 as $key4 => $val4) {
								$arrA[$key3][$key4] = 1;
								$arrD['detail'][$key3][$status_penilai]['nilai_'.$key4] += $val4;
								$arrD['detail'][$key3][$status_penilai]['juml_'.$key4]++;
							}
						}
					}
				}
				
				foreach($arrA as $key2 => $val2) {
					$arrA[$key2]['juml_kat_ksa'] = $val2['knowledge'] + $val2['skill'] + $val2['attitude'];
					$arrA[$key2]['juml_kat_b'] = $val2['behaviour'];
				}
				
				// proporsikan bobot-nya
				$arrD['bobot_atasan'] = ($arrD['juml_atasan']>0)? $bobot_atasan : "-";
				$arrD['bobot_kolega'] = ($arrD['juml_kolega']>0)? $bobot_kolega : "-";
				$arrD['bobot_akhir_penilai'] = $arrD['bobot_atasan'] + $arrD['bobot_kolega'];
				
				$arrD['bobot_soal_ksa_pre'] = ($arrA['pre']['juml_kat_ksa']>0)? $bobot_soal_ksa : "-";
				$arrD['bobot_soal_b_pre'] = ($arrA['pre']['juml_kat_b']>0)? $bobot_soal_b : "-";
				$arrD['bobot_akhir_soal_pre'] = $arrD['bobot_soal_ksa_pre'] + $arrD['bobot_soal_b_pre'];
				
				$arrD['bobot_soal_ksa_post'] = ($arrA['post']['juml_kat_ksa']>0)? $bobot_soal_ksa : "-";
				$arrD['bobot_soal_b_post'] = ($arrA['post']['juml_kat_b']>0)? $bobot_soal_b : "-";
				$arrD['bobot_akhir_soal_post'] = $arrD['bobot_soal_ksa_post'] + $arrD['bobot_soal_b_post'];
				
				$bobot_akhir_penilai = $arrD['bobot_akhir_penilai'];
				foreach($arrD['detail'] as $key2 => $val2) {
					$nilai_akhir = 0;
					
					foreach($val2 as $key3 => $val3) {
						$juml_ksa = $arrA[$key2]['juml_kat_ksa'];
						$juml_b = $arrA[$key2]['juml_kat_b'];
						$bobot_penilai = $arrD['bobot_'.$key3];
						$bobot_soal_ksa = $arrD['bobot_soal_ksa_'.$key2];
						$bobot_soal_b = $arrD['bobot_soal_b_'.$key2];
						$bobot_akhir_soal = $bobot_soal_ksa + $bobot_soal_b;
						
						$nilai_k_average = ($val3['juml_knowledge']==0)? 0 : ($val3['nilai_knowledge']/$val3['juml_knowledge']);
						$nilai_s_average = ($val3['juml_skill']==0)? 0 : ($val3['nilai_skill']/$val3['juml_skill']);
						$nilai_a_average = ($val3['juml_attitude']==0)? 0 : ($val3['nilai_attitude']/$val3['juml_attitude']);
						
						$nilai_ksa_average = ($juml_ksa==0)? 0 : ($nilai_k_average+$nilai_s_average+$nilai_a_average)/$juml_ksa;
						$nilai_b_average = ($val3['juml_behaviour']==0)? 0 : ($val3['nilai_behaviour']/$val3['juml_behaviour']);
						
						$nilai_ksa_x_bobot = ($bobot_akhir_soal==0)? 0 : ($bobot_soal_ksa/$bobot_akhir_soal)*$nilai_ksa_average;
						$nilai_b_x_bobot = ($bobot_akhir_soal==0)? 0 : ($bobot_soal_b/$bobot_akhir_soal)*$nilai_b_average;
						
						$nilai = $nilai_ksa_x_bobot + $nilai_b_x_bobot;
						$nilai_x_bobot = ($bobot_akhir_penilai==0)? 0 : ($bobot_penilai/$bobot_akhir_penilai)*$nilai;
						
						$nilai_ksa_average = number_format($nilai_ksa_average,3);
						$nilai_ksa_x_bobot = number_format($nilai_ksa_x_bobot,3);
						$nilai_b_average = number_format($nilai_b_average,3);
						$nilai_b_x_bobot = number_format($nilai_b_x_bobot,3);
						$nilai = number_format($nilai,3);
						$nilai_x_bobot = number_format($nilai_x_bobot,3);
						
						if(!isset($val3['juml_knowledge'])) $nilai_k_average = "-";
						if(!isset($val3['juml_skill'])) $nilai_s_average = "-";
						if(!isset($val3['juml_attitude'])) $nilai_a_average = "-";
						if(!isset($val3['juml_behaviour'])) $nilai_b_average = "-";
						
						$arrD['detail'][$key2][$key3]['nilai_k_average'] = $nilai_k_average;
						$arrD['detail'][$key2][$key3]['nilai_s_average'] = $nilai_s_average;
						$arrD['detail'][$key2][$key3]['nilai_a_average'] = $nilai_a_average;
						$arrD['detail'][$key2][$key3]['nilai_ksa_average'] = $nilai_ksa_average;
						$arrD['detail'][$key2][$key3]['nilai_ksa_x_bobot'] = $nilai_ksa_x_bobot;
						$arrD['detail'][$key2][$key3]['nilai_b_average'] = $nilai_b_average;
						$arrD['detail'][$key2][$key3]['nilai_b_x_bobot'] = $nilai_b_x_bobot;
						$arrD['detail'][$key2][$key3]['nilai'] = $nilai;
						$arrD['detail'][$key2][$key3]['nilai_x_bobot'] = $nilai_x_bobot;
						
						$nilai_akhir += $nilai_x_bobot;
					}
					$arrD['detail'][$key2]['nilai_akhir'] = $nilai_akhir;
				}
				
				$arrD['nilai_pre'] = $arrD['detail']['pre']['nilai_akhir'];
				$arrD['nilai_post'] = $arrD['detail']['post']['nilai_akhir'];
				
				$nilai_detail = json_encode($arrD);
				
				// simpan data group-nya
				$group_id = '';
				$sqlC3 = "select group_id from _member where member_id='".$id_dinilai."' ";
				$resC3 = $this->db->query($sqlC3);
				$rowC3 = $resC3->result_array();
				$group_id = $rowC3[0]['group_id'];
				
				$sql =
					"insert into _classroom_evaluasi_lv3_rekap set
						cr_id='".$classroom_id."',
						group_id='".$group_id."',
						member_id='".$id_dinilai."',
						nilai_pre_test='".$arrD['nilai_pre']."',
						nilai_post_test='".$arrD['nilai_post']."',
						nilai_detail='".$nilai_detail."'
					 on duplicate key update
						group_id='".$group_id."',
						nilai_pre_test='".$arrD['nilai_pre']."',
						nilai_post_test='".$arrD['nilai_post']."',
						nilai_detail='".$nilai_detail."' ";
				$res = $this->db->query($sql);
			}
			
			$do_log_rekap = date("Y-m-d H:i:s").', rekap data ('.$kat.')<br/>';
			$sql = "update _classroom_evaluasi_lv3_header set log_rekap=concat(log_rekap,'".$do_log_rekap."') where cr_id='".$classroom_id."' ";
			$res = $this->db->query($sql);
		}
		
		$this->db->trans_complete();
		
		// update log tgl rekap
		create_log($this->section_id,0,'Rekap','Evaluasi Pelatihan Lv.3 ['.$kat.']['.$did.']');
		
		if ($this->db->trans_status() === FALSE) {
			create_log($this->section_id,0,'Gagal Rekap','Evaluasi Pelatihan Lv.3 ['.$kat.']['.$did.']');
			$strError .= '<li>Gagal menyimpan data, silahkan coba lagi</li>';
		}
		
		if(strlen($strError)>0) {
			$_SESSION['err_message'] = $strError;
			redirect($url_return);
		} else {
			flash_notif_success(NULL,$url_return);
		}
	}
	
	function evaluasi_lv3_do_export($kat=NULL,$did=NULL){
		// set maximum execution time in seconds
		set_time_limit(18000); // 5 jam
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// $did = (int) $did;
		
		$strError = '';
		$url_return = '';
		$addSql = '';
		$judul = '';
		$nama_file = '';
		
		if($kat=="cr") {
			$url_return = site_url('classroom/evaluasi_lv3/').$did;
			$addSql = " and h.cr_id='".$did."' ";
			
			$classroom = $this->get_classroom($did);
			$judul = 'Evaluasi Level 3 '.$classroom['cr_name'];
			$nama_file = "data_evaluasi_lv3_cr_".$did;
		} else if($kat=="th") {
			$url_return = site_url('classroom/evaluasi_lv3_list/').$did;
			$addSql = " and h.tahun_evaluasi='".$did."' ";
			$judul = 'Evaluasi Level 3 Tahun '.$did;
			$nama_file = "data_evaluasi_lv3_th_".$did;
		}
		
		$ui = '';
		$i = 0;
		$sqlU =
			"select h.tahun_evaluasi, h.cr_id, h.target_peserta, m.group_id, g.group_name, m.member_name, m.member_nip, r.nilai_pre_test, r.nilai_post_test, r.nilai_detail
			 from _classroom_evaluasi_lv3_rekap r, _classroom_evaluasi_lv3_header h, _member m, _group g
			 where r.cr_id=h.cr_id and r.member_id=m.member_id and m.group_id=g.group_id ".$addSql." and h.status='1' 
			 order by g.group_name, h.cr_id, m.member_name ";
		$resU = $this->db->query($sqlU);
		$rowU = $resU->result_array();
		foreach($rowU as $key => $val) {
			$i++;
			
			$sqlD = "select cr_name from _classroom where cr_id='".$val['cr_id']."' ";
			$resD = $this->db->query($sqlD);
			$rowD = $resD->result_array();
			
			$nilai_detail = json_decode($val['nilai_detail'],true);
			
			
			$ui .=
				'<tr>
					<td>'.$i.'</td>
					<td>'.$val['group_name'].'</td>
					<td>'.$rowD[0]['cr_name'].' ['.$val['cr_id'].']'.'</td>
					<td>'.$val['target_peserta'].'</td>
					<td class="text">'.$val['member_nip'].'</td>
					<td>'.$val['member_name'].'</td>
					<td class="text">'.$val['nilai_pre_test'].'</td>
					<td class="text">'.$val['nilai_post_test'].'</td>
					
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_k_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_s_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_a_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_ksa_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_ksa_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_b_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_b_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['atasan']['nilai_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_k_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_s_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_a_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_ksa_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_ksa_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_b_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_b_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['pre']['kolega']['nilai_x_bobot'].'</td>
					
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_k_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_s_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_a_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_ksa_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_ksa_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_b_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_b_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['atasan']['nilai_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_k_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_s_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_a_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_ksa_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_ksa_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_b_average'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_b_x_bobot'].'</td>
					<td class="text">'.$nilai_detail['detail']['post']['kolega']['nilai_x_bobot'].'</td>
					
					<td class="text">'.$nilai_detail['bobot_atasan'].'</td>
					<td class="text">'.$nilai_detail['bobot_kolega'].'</td>
					<td class="text">'.$nilai_detail['bobot_soal_ksa_pre'].'</td>
					<td class="text">'.$nilai_detail['bobot_soal_b_pre'].'</td>
					<td class="text">'.$nilai_detail['bobot_soal_ksa_post'].'</td>
					<td class="text">'.$nilai_detail['bobot_soal_b_post'].'</td>
					
				 </tr>';
		}
		
		$ui =
			'<style>
				table, th, td {border: 1px solid black;border-collapse: collapse;vertical-align: top;text-align: left;}
				.text{mso-number-format:"\@";}
			 </style>
			 <div style="font-weight:bold;">'.$judul.'</div>
			 <table>
				<thead>
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Group</th>
						<th rowspan="2">Class</th>
						<th rowspan="2">Target Peserta</th>
						<th rowspan="2">NIK</th>
						<th rowspan="2">Nama</th>
						<th rowspan="2">Nilai Akhir Sebelum Pelatihan</th>
						<th rowspan="2">Nilai Akhir Pasca Pelatihan</th>
						<th colspan="8">Atasan (Pre)</th>
						<th colspan="8">Kolega (Pre)</th>
						<th colspan="8">Atasan (Post)</th>
						<th colspan="8">Kolega (Post)</th>
						<th colspan="6">Bobot</th>
					</tr>
					<tr>
						<th>knowledge</th>
						<th>rerata skill</th>
						<th>rerata attitude</th>
						<th>rerata ksa</th>
						<th>ksa x bobot</th>
						<th>rerata behaviour</th>
						<th>behaviour x bobot</th>
						<th>nilai x bobot</th>
						<th>knowledge</th>
						<th>rerata skill</th>
						<th>rerata attitude</th>
						<th>rerata ksa</th>
						<th>ksa x bobot</th>
						<th>rerata behaviour</th>
						<th>behaviour x bobot</th>
						<th>nilai x bobot</th>
						<th>knowledge</th>
						<th>rerata skill</th>
						<th>rerata attitude</th>
						<th>rerata ksa</th>
						<th>ksa x bobot</th>
						<th>rerata behaviour</th>
						<th>behaviour x bobot</th>
						<th>nilai x bobot</th>
						<th>knowledge</th>
						<th>rerata skill</th>
						<th>rerata attitude</th>
						<th>rerata ksa</th>
						<th>ksa x bobot</th>
						<th>rerata behaviour</th>
						<th>behaviour x bobot</th>
						<th>nilai x bobot</th>
						<th>atasan</th>
						<th>kolega</th>
						<th>pre_ksa</th>
						<th>pre_behaviour</th>
						<th>post_ksa</th>
						<th>post_behaviour</th>
					</tr>
				</thead>
				<tbody>'.$ui.'</tbody>
			 </table>';
		
		header("Content-type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-disposition: attachment; filename=".$nama_file.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $ui;
		exit;
		
		/*
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		
		$alpha = range('A', 'ZZ');

		// baris 1
		$sheet->mergeCells('A1:AU1');
        $sheet->setCellValue('A1', $judul);
		// baris 2
		$sheet->mergeCells('A2:A3');
		$sheet->setCellValue('A2', 'No');
		$sheet->mergeCells('B2:B3');
		$sheet->setCellValue('B2', 'Group');
		$sheet->mergeCells('C2:C3');
		$sheet->setCellValue('C2', 'Class');
		$sheet->mergeCells('D2:D3');
		$sheet->setCellValue('D2', 'Target Peserta');
		$sheet->mergeCells('E2:E3');
		$sheet->setCellValue('E2', 'NIK');
		$sheet->mergeCells('F2:F3');
		$sheet->setCellValue('F2', 'Nama');
		$sheet->mergeCells('G2:G3');
		$sheet->setCellValue('G2', 'Nilai Akhir Sebelum Pelatihan');
		$sheet->mergeCells('H2:H3');
		$sheet->setCellValue('H2', 'Nilai Akhir Pasca Pelatihan');
		$sheet->mergeCells('I2:P2');
		$sheet->setCellValue('I2', 'Atasan (Pre)');
		$sheet->mergeCells('Q2:X2');
		$sheet->setCellValue('Q2', 'Kolega (Pre)');
		$sheet->mergeCells('Y2:AF2');
		$sheet->setCellValue('Y2', 'Atasan (Post)');
		$sheet->mergeCells('AG2:AN2');
		$sheet->setCellValue('AG2', 'Kolega (Post)');
		$sheet->mergeCells('AP2:AU2');
		$sheet->setCellValue('AP2', 'Bobot');
		// baris 3 - pre
		// $sheet->setCellValue('H3', 'Nilai Akhir');
		$sheet->setCellValue('I3', 'knowledge');
		$sheet->setCellValue('J3', 'rerata skill');
		$sheet->setCellValue('K3', 'rerata attitude');
		$sheet->setCellValue('L3', 'rerata ksa');
		$sheet->setCellValue('M3', 'ksa x bobot');
		$sheet->setCellValue('N3', 'rerata behaviour');
		$sheet->setCellValue('O3', 'behaviour x bobot');
		$sheet->setCellValue('P3', 'nilai x bobot');	
		
		$sheet->setCellValue('Q3', 'knowledge');
		$sheet->setCellValue('R3', 'rerata skill');
		$sheet->setCellValue('S3', 'rerata attitude');
		$sheet->setCellValue('T3', 'rerata ksa');
		$sheet->setCellValue('U3', 'ksa x bobot');
		$sheet->setCellValue('V3', 'rerata behaviour');
		$sheet->setCellValue('W3', 'behaviour x bobot');
		$sheet->setCellValue('X3', 'nilai x bobot');
		// baris 3 - post
		// $sheet->setCellValue('Y3', 'Nilai Akhir');
		$sheet->setCellValue('Y3', 'knowledge');
		$sheet->setCellValue('Z3', 'rerata skill');
		$sheet->setCellValue('AA3', 'rerata attitude');
		$sheet->setCellValue('AB3', 'rerata ksa');
		$sheet->setCellValue('AC3', 'ksa x bobot');
		$sheet->setCellValue('AD3', 'rerata behaviour');
		$sheet->setCellValue('AE3', 'behaviour x bobot');
		$sheet->setCellValue('AF3', 'nilai x bobot');
		
		$sheet->setCellValue('AG3', 'knowledge');
		$sheet->setCellValue('AH3', 'rerata skill');
		$sheet->setCellValue('AI3', 'rerata attitude');
		$sheet->setCellValue('AJ3', 'rerata ksa');
		$sheet->setCellValue('AK3', 'ksa x bobot');
		$sheet->setCellValue('AL3', 'rerata behaviour');
		$sheet->setCellValue('AM3', 'behaviour x bobot');
		$sheet->setCellValue('AN3', 'nilai x bobot');
		// baris 3 - bobot
		$sheet->setCellValue('AP3', 'atasan');
		$sheet->setCellValue('AQ3', 'kolega');
		$sheet->setCellValue('AR3', 'pre_ksa');
		$sheet->setCellValue('AS3', 'pre_behaviour');
		$sheet->setCellValue('AT3', 'post_ksa');
		$sheet->setCellValue('AU3', 'post_behaviour');

		// sql utama
		$i = 0;
		$x = 3;
		$sqlU =
			"select c.cr_id, c.cr_name, h.target_peserta
			 from _classroom_evaluasi_lv3_header h, _classroom c
			 where h.cr_id=c.cr_id and h.status='1' ".$addSql." order by c.cr_name";
		$resU = $this->db->query($sqlU);
		$rowU = $resU->result_array();
		foreach($rowU as $keyU => $valU) {
			$classroom_id = $valU['cr_id'];
			$classroom_name = $valU['cr_name'];
			$target_peserta = $valU['target_peserta'];
			
			$sqlC =
				"select 
					g.group_name,
					m.member_name, m.member_nip,
					r.nilai_pre_test, r.nilai_post_test, r.nilai_detail
				 from _group g, _member m, _classroom_evaluasi_lv3_rekap r
				 where g.group_id=m.group_id and m.member_id=r.member_id and r.cr_id='".$classroom_id."'
				 order by g.group_id, m.member_name";
			$resC = $this->db->query($sqlC);
			$rowC = $resC->result_array();
			foreach($rowC as $keyC => $valC) {
				$i++;
				$x++;
				
				$nilai_detail = json_decode($valC['nilai_detail'],true);
				
				$sheet->setCellValue('A'.$x, $i);
				$sheet->setCellValue('B'.$x, $valC['group_name']);
				$sheet->setCellValue('C'.$x, $classroom_name.' ['.$classroom_id.']');
				$sheet->setCellValue('D'.$x, $target_peserta);
				$sheet->setCellValue('E'.$x, $valC['member_nip']);
				$sheet->setCellValue('F'.$x, $valC['member_name']);
				$sheet->setCellValue('G'.$x, $valC['nilai_pre_test']);
				$sheet->setCellValue('H'.$x, $valC['nilai_post_test']);
				
				// $sheet->setCellValue('H'.$x, $nilai_detail['detail']['pre']['nilai_akhir']);
				$sheet->setCellValue('I'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_k_average']);
				$sheet->setCellValue('J'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_s_average']);
				$sheet->setCellValue('K'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_a_average']);
				$sheet->setCellValue('L'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_ksa_average']);
				$sheet->setCellValue('M'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_ksa_x_bobot']);
				$sheet->setCellValue('N'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_b_average']);
				$sheet->setCellValue('O'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_b_x_bobot']);
				$sheet->setCellValue('P'.$x, $nilai_detail['detail']['pre']['atasan']['nilai_x_bobot']);
				$sheet->setCellValue('Q'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_k_average']);
				$sheet->setCellValue('R'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_s_average']);
				$sheet->setCellValue('S'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_a_average']);
				$sheet->setCellValue('T'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_ksa_average']);
				$sheet->setCellValue('U'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_ksa_x_bobot']);
				$sheet->setCellValue('V'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_b_average']);
				$sheet->setCellValue('W'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_b_x_bobot']);
				$sheet->setCellValue('X'.$x, $nilai_detail['detail']['pre']['kolega']['nilai_x_bobot']);
				
				// $sheet->setCellValue('Y'.$x, $nilai_detail['detail']['post']['nilai_akhir']);
				$sheet->setCellValue('Y'.$x, $nilai_detail['detail']['post']['atasan']['nilai_k_average']);
				$sheet->setCellValue('Z'.$x, $nilai_detail['detail']['post']['atasan']['nilai_s_average']);
				$sheet->setCellValue('AA'.$x, $nilai_detail['detail']['post']['atasan']['nilai_a_average']);
				$sheet->setCellValue('AB'.$x, $nilai_detail['detail']['post']['atasan']['nilai_ksa_average']);
				$sheet->setCellValue('AC'.$x, $nilai_detail['detail']['post']['atasan']['nilai_ksa_x_bobot']);
				$sheet->setCellValue('AD'.$x, $nilai_detail['detail']['post']['atasan']['nilai_b_average']);
				$sheet->setCellValue('AE'.$x, $nilai_detail['detail']['post']['atasan']['nilai_b_x_bobot']);
				$sheet->setCellValue('AF'.$x, $nilai_detail['detail']['post']['atasan']['nilai_x_bobot']);
				$sheet->setCellValue('AG'.$x, $nilai_detail['detail']['post']['kolega']['nilai_k_average']);
				$sheet->setCellValue('AH'.$x, $nilai_detail['detail']['post']['kolega']['nilai_s_average']);
				$sheet->setCellValue('AI'.$x, $nilai_detail['detail']['post']['kolega']['nilai_a_average']);
				$sheet->setCellValue('AJ'.$x, $nilai_detail['detail']['post']['kolega']['nilai_ksa_average']);
				$sheet->setCellValue('AK'.$x, $nilai_detail['detail']['post']['kolega']['nilai_ksa_x_bobot']);
				$sheet->setCellValue('AL'.$x, $nilai_detail['detail']['post']['kolega']['nilai_b_average']);
				$sheet->setCellValue('AM'.$x, $nilai_detail['detail']['post']['kolega']['nilai_b_x_bobot']);
				$sheet->setCellValue('AN'.$x, $nilai_detail['detail']['post']['kolega']['nilai_x_bobot']);
				
				$sheet->setCellValue('AP'.$x, $nilai_detail['bobot_atasan']);
				$sheet->setCellValue('AQ'.$x, $nilai_detail['bobot_kolega']);
				$sheet->setCellValue('AR'.$x, $nilai_detail['bobot_soal_ksa_pre']);
				$sheet->setCellValue('AS'.$x, $nilai_detail['bobot_soal_b_pre']);
				$sheet->setCellValue('AT'.$x, $nilai_detail['bobot_soal_ksa_post']);
				$sheet->setCellValue('AU'.$x, $nilai_detail['bobot_soal_b_post']);
			}
		}
		
		// Set Border
        $sheet->getStyle ('A1:AU'.$x)->getBorders()->getAllBorders()->setBorderStyle("thin");
		
		$writer = new Xlsx($spreadsheet);
        $filename = 'evaluasi_level3_'.$kat.'_'.$did;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
		
		exit;
		*/
	}
	
	function evaluasi_lv3_pairing_dl($kat=NULL,$did=NULL,$gid=NULL){
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// $did = (int) $did;
		$gid = (int) $gid;
		
		$strError = '';
		$url_return = '';
		$addSql = '';
		$judul = '';
		
		// ambil semua group ato tertentu saja?
		if($gid>0) $addSql .= " and m.group_id='".$gid."' ";
		
		if($kat=="cr") {
			$url_return = site_url('classroom/evaluasi_lv3/').$did;
			$addSql .= " and h.cr_id='".$did."' ";
			
			$classroom = $this->get_classroom($did);
			$judul = 'Evaluasi Level 3 '.$classroom['cr_name'];
		} else if($kat=="th") {
			$url_return = site_url('classroom/evaluasi_lv3_list/').$did;
			$addSql .= " and h.tahun_evaluasi='".$did."' ";
			$judul = 'Evaluasi Level 3 Tahun '.$did;
		}
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $alpha = range('A', 'ZZ');

		// baris 1
		$sheet->setCellValue('A1', 'id_group');
		$sheet->setCellValue('B1', 'nama_group');
		$sheet->setCellValue('C1', 'nik_dinilai');
		$sheet->setCellValue('D1', 'nama_dinilai');
		$sheet->setCellValue('E1', 'nik_atasan');
		$sheet->setCellValue('F1', 'nama_atasan');
		// $sheet->setCellValue('G1', 'nik_kolega1');
		// $sheet->setCellValue('H1', 'nama_kolega1');
		// $sheet->setCellValue('I1', 'nik_kolega2');
		// $sheet->setCellValue('J1', 'nama_kolega2');
		
		// sql utama
		$i = 0;
		$x = 1;
		$sqlU =
			"select g.group_id, g.group_name, m.member_nip, m.member_name
			 from _classroom_evaluasi_lv3_header h, _classroom c, _classroom_member cm, _member m, _group g
			 where 
				h.cr_id=c.cr_id ".$addSql." and c.cr_id=cm.cr_id and cm.member_id=m.member_id and cm.member_status='1'
				and m.group_id=g.group_id
				and h.status='1' and m.member_status='active'
			 group by m.member_id
			 order by g.group_name, m.member_name";
		$resU = $this->db->query($sqlU);
		$rowU = $resU->result_array();
		foreach($rowU as $keyU => $valU) {
			$i++;
			$x++;
			
			$sheet->setCellValue('A'.$x, $valU['group_id']);
			$sheet->setCellValue('B'.$x, $valU['group_name']);
			$sheet->setCellValue('C'.$x, $valU['member_nip']);
			$sheet->setCellValue('D'.$x, $valU['member_name']);
		}
		
		// Set Border
        $sheet->getStyle ('A1:F'.$x)->getBorders()->getAllBorders()->setBorderStyle("thin");
		
		$writer = new Xlsx($spreadsheet);
        $filename = 'peserta_evaluasi_level3_'.$kat.'_'.$did.'_'.$gid;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
		
		exit;
	}
	
	function evaluasi_lv3_pairing($kat=NULL,$did=NULL){
		// matikan error reporting tipe notice dan warning
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		
		// $did = (int) $did;
		
		$strError = '';
		$strInfo = '';
		$url_return = '';
		$addSql = '';
		$judul = '';
		
		$group_id = '';
		
		// get semua group
		$sqlG = "select group_id, group_name from _group where group_status='active' order by group_name ";
		$resG = $this->db->query($sqlG);
		$rowG = $resG->result_array();
		
		if($kat=="cr") {
			$url_return = site_url('classroom/evaluasi_lv3/').$did;
			$addSql = " and h.cr_id='".$did."' ";
			
			$classroom = $this->get_classroom($did);
			$judul = 'Evaluasi Level 3 '.$classroom['cr_name'];
		} else if($kat=="th") {
			$url_return = site_url('classroom/evaluasi_lv3_pairing/').$kat.'/'.$did;
			$addSql = " and h.tahun_evaluasi='".$did."' ";
			$judul = 'Evaluasi Level 3 Tahun '.$did;
		}
		
		$post = $this->input->post();
		if(!empty($post)) {
			// $id_group = (int) $post['group_id'];
			// if(empty($id_group)) $strError .= '<li>Group masih kosong.</li>';
			
			// ada error?
			if(strlen($strError)<=0) {
				$arr_file = explode('.', $_FILES['file']['name']);
                $extension = end($arr_file);

				if('csv' == $extension) {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
				} else {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				}
				$reader->setReadDataOnly(true);

				$spreadsheet = $reader->load($_FILES['file']['tmp_name']);

				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				if ($sheetData){
					$strInfo .= '<li>Mulai mengolah berkas '.$_FILES['file']['name'].'.</li>';
					
					$this->db->trans_start();
					
					$baris = 0;
					foreach($sheetData as $keyData => $valData) {
						$baris++;
						
						// baris 1 diabaikan
						if($baris==1) continue;
						
						$id_group		= $valData[0];
						$nama_group		= $valData[1];
						$nik_dinilai	= $valData[2];
						$nama_dinilai 	= $valData[3];
						$nik_atasan 	= $valData[4];
						$nama_atasan 	= $valData[5];
						$nik_kolega1 	= $valData[6];
						$nama_kolega1 	= $valData[7];
						$nik_kolega2 	= $valData[8];
						$nama_kolega2 	= $valData[9];
						
						if(empty($id_group) || empty($nik_dinilai) || empty($nik_atasan)) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, id_group/nik_dinilai/nik_atasan masih kosong.</li>';
							continue;
						}
						if($nik_dinilai==$nik_atasan) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, atasan tidak boleh diri sendiri.</li>';
							continue;
						}
						if($nik_dinilai==$nik_kolega1) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega 1 tidak boleh diri sendiri.</li>';
							continue;
						}
						if($nik_dinilai==$nik_kolega2) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega 2 tidak boleh diri sendiri.</li>';
							continue;
						}
						if($nik_atasan==$nik_kolega1) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega 1 tidak boleh atasan.</li>';
							continue;
						}
						if($nik_atasan==$nik_kolega2) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega 2 tidak boleh atasan.</li>';
							continue;
						}
						if(!empty($nik_kolega1) && $nik_kolega1==$nik_kolega2) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega 2 tidak boleh sama dengan kolega 1.</li>';
							continue;
						}
						
						$id_dinilai = "";
						$id_atasan = "";
						$id_kolega1 = "";
						$id_kolega2 = "";
						
						// cek nik dinilai
						if(!empty($nik_dinilai)) {
							$sql = "select member_id from _member where member_nip='".$nik_dinilai."' and group_id='".$id_group."' and member_status='active' ";
							$res = $this->db->query($sql);
							$row = $res->result_array();
							$juml = count($row);
							if($juml<1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, member dengan NIK '.$nik_dinilai.' tidak ditemukan.</li>';
								continue;
							} else if($juml>1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, member dengan NIK '.$nik_dinilai.' ada '.$juml.' akun.</li>';
								continue;
							} else {
								$id_dinilai = $row[0]['member_id'];
							}
						}
						
						// cek nik atasan
						if(!empty($nik_atasan)) {
							$sql = "select member_id from _member where member_nip='".$nik_atasan."' and member_status='active' ";
							$res = $this->db->query($sql);
							$row = $res->result_array();
							$juml = count($row);
							if($juml<1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, atasan dengan NIK '.$nik_atasan.' tidak ditemukan.</li>';
								continue;
							} else if($juml>1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, atasan dengan NIK '.$nik_atasan.' ada '.$juml.' akun.</li>';
								continue;
							} else {
								$id_atasan = $row[0]['member_id'];
							}
						}
						
						/* 
						** untuk saat ini kolega ga dipake
						// cek nik kolega1
						if(!empty($nik_kolega1)) {
							$sql = "select member_id from _member where member_nip='".$nik_kolega1."' and member_status='active' ";
							$res = $this->db->query($sql);
							$row = $res->result_array();
							$juml = count($row);
							if($juml<1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega dengan NIK '.$nik_kolega1.' tidak ditemukan.</li>';
								continue;
							} else if($juml>1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega dengan NIK '.$nik_kolega1.' ada '.$juml.' akun.</li>';
								continue;
							} else {
								$id_kolega1 = $row[0]['member_id'];
							}
						}
						
						// cek nik kolega2
						if(!empty($nik_kolega2)) {
							$sql = "select member_id from _member where member_nip='".$nik_kolega2."' and member_status='active' ";
							$res = $this->db->query($sql);
							$row = $res->result_array();
							$juml = count($row);
							if($juml<1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega dengan NIK '.$nik_kolega2.' tidak ditemukan.</li>';
								continue;
							} else if($juml>1) {
								$strInfo .= '<li>Baris '.$baris.' diabaikan, kolega dengan NIK '.$nik_kolega2.' ada '.$juml.' akun.</li>';
								continue;
							} else {
								$id_kolega2 = $row[0]['member_id'];
							}
						}
						*/
						
						// proses yg OK
						$arrP = array();
						if(!empty($id_atasan)) $arrP['atasan'] = $id_atasan;
						
						/*
						if(!empty($id_atasan)) $arrP[$id_atasan] = 'atasan';
						if(!empty($id_kolega1)) $arrP[$id_kolega1] = 'kolega';
						if(!empty($id_kolega2)) $arrP[$id_kolega2] = 'kolega';
						*/
						
						// ybs ikut kelas apa aj?
						$sql =
							"select h.cr_id
							 from _classroom_member cm, _classroom_evaluasi_lv3_header h
							 where h.cr_id=cm.cr_id and h.status='1' and cm.member_status='1' ".$addSql." and cm.member_id='".$id_dinilai."' ";
						$res = $this->db->query($sql);
						$row = $res->result_array();
						$juml_pelatihan = 0;
						foreach($row as $key => $val) {
							$juml_pelatihan++;
							
							// ambil data yg udah di-pairing
							/* $arrD = array();
							$sqlD =
								"select id, id_penilai, id_dinilai, status_penilai
								 from _classroom_evaluasi_lv3_pairing
								 where cr_id='".$val['cr_id']."' and id_dinilai='".$id_dinilai."' and status_penilai in ('atasan','kolega') ";
							$resD = $this->db->query($sqlD);
							$rowD = $resD->result_array();
							foreach($rowD as $keyD => $valD) {
								// $arrD[$valD['id_penilai'].'-'.$valD['status_penilai']] = $valD['id'];
								$arrD[$valD['status_penilai']] = $valD['id'];
							} */
							
							// create/update data
							$pair_id = uniqid('EVPAIR');
							foreach($arrP as $keyP => $valP) {
								// unset($arrD[$keyP.'-'.$valP]);
								
								$sqlP =
									"insert into _classroom_evaluasi_lv3_pairing set
										id='".$pair_id."',
										cr_id='".$val['cr_id']."',
										id_penilai='".$valP."',
										status_penilai='".$keyP."',
										id_dinilai='".$id_dinilai."'
									 on duplicate key update
										id_penilai='".$valP."' ";
								$resP = $this->db->query($sqlP);
							}
							// delete pairing yg sudah tidak digunakan
							/* foreach($arrD as $keyP => $valP) {
								$sqlP = "delete from _classroom_evaluasi_lv3_pairing where id='".$valP."' ";
								$resP = $this->db->query($sqlP);
							} */
						}
						if(empty($juml_pelatihan)) {
							$strInfo .= '<li>Baris '.$baris.' diabaikan, pelatihan tidak ditemukan.</li>';
							continue;
						}
					}
					
					$this->db->trans_complete();
		
					// update log tgl rekap
					create_log($this->section_id,0,'Setup Penilai','Evaluasi Pelatihan Lv.3 ['.$kat.']['.$did.']');
					
					if ($this->db->trans_status() === FALSE) {
						create_log($this->section_id,0,'Gagal Setup Penilai','Evaluasi Pelatihan Lv.3 ['.$kat.']['.$did.']');
						$strError .= '<li>Gagal menyimpan data, silahkan coba lagi</li>';
					}
					
					if(strlen($strError)>0) {
						// do nothing
					} else {
						$strInfo .= '<li>Selesai mengolah data.</li>';
					}
				}
			}
		}
		
		// ada error?
		if(strlen($strError)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'warning');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<b>Tidak dapat menyimpan data</b>:<br/><ul>'.$strError.'</ul>');
		}
		
		// ada info?
		if(strlen($strInfo)>0) {
			$this->session->set_flashdata('flash_msg', true);
			$this->session->set_flashdata('flash_msg_type', 'info');
			$this->session->set_flashdata('flash_msg_status', '0');
			$this->session->set_flashdata('flash_msg_text', '<ul>'.$strInfo.'</ul>');
		}
		
		$data['judul'] = $judul;
		$data['rowG'] = $rowG;
		$data['url_dl'] = site_url('classroom/evaluasi_lv3_pairing_dl/'.$kat.'/'.$did);
		
		$data['request']['group_id'] = $group_id;
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_pairing/'.$kat.'/'.$did);
		$data['page_name']      = 'Class Room';
		$data['page_sub_name']  = 'Setup Penilai '.$judul;
		$data['page'] = 'classroom/classroom_evaluasi_lv3_import_penilai_view';
		$this->load->view('main_view',$data);
	}
	
	function evaluasi_lv3_list($tahun_evaluasi=NULL) {
		// $tahun_evaluasi = (int) $tahun_evaluasi;
		
		$post = $this->input->post();
		if(!empty($post)) {
			$tahun_evaluasi = $post['tahun_evaluasi'];
		}
		if(empty($tahun_evaluasi)) $tahun_evaluasi = date("Y")-1;
		
		// get distinct tahun evaluasi
		$sqlT = "select distinct(tahun_evaluasi) as tahun_evaluasi from _classroom_evaluasi_lv3_header where status='1' order by tahun_evaluasi";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		
		// get classroom yang ada evaluasi pelatihan level 3
		$sql =
			"select 
				c.cr_id, c.cr_name, c.cr_date_start, c.cr_date_end, c.cr_module,
				c.cr_pretest, c.cr_competency, c.cr_has_pretest, c.cr_has_kompetensi_test,
				h.tanggal_mulai, h.tanggal_selesai, h.status
			 from _classroom_evaluasi_lv3_header h, _classroom c 
			 where h.cr_id=c.cr_id and h.tahun_evaluasi='".$tahun_evaluasi."' 
			 order by h.status desc, c.cr_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		$data['rowT'] = $rowT;
		$data['row'] = $row;
		$data['url_progress'] = site_url('classroom_scan/progress_evaluasi_lv3/'.$tahun_evaluasi);
		
		$data['request']['tahun_evaluasi'] = $tahun_evaluasi;
		
		$data['form_action']    = site_url('classroom/evaluasi_lv3_list');
		$data['editable']      = false;
		$data['page_name']      = 'Class Room';
		$data['page_sub_name']  = 'Daftar Classroom yang Memiliki Evaluasi Level 3';
		$data['page'] = 'classroom/classroom_evaluasi_lv3_list_view';
		$this->load->view('main_view',$data);
	}
	
	/* -- evaluasi level 3 end -- */
	
	/* -- project assignment start -- */
	function project_assignment($classroom_id=NULL){
        $classroom = $this->get_classroom($classroom_id);
        //$data_pa=$this->pa->getpa_all();
        $filter=array("p.cr_id"=>$classroom['cr_id']);
        $data_pa=$this->pa->getpa_parameter($filter);
       // print_r($classroom['cr_id']);
       // exit;
        $data['classroom']      = $classroom; 
        $data['project_assignment']=$data_pa;
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = $classroom['cr_name'];
        $data['page'] = 'classroom/classroom_project_assignment';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);
    }
	/* -- project assignment end -- */
	
    
 /*-- project folder download --*/
 
 function download_project_assignment(){
    $this->load->library('zip');
    $crid=$this->uri->segment(3);
    //DATA ZIP
    $filtermodul=array('p.cr_id'=>$crid);
    //$filtermodul=array('p.cr_id'=>'451');
    $namakelas="";
    // $filtermodul=array('cmm.classroom_id'=>$crid);
    $project=$this->pa->getpa_parameter($filtermodul);
    ///ZIP & Download
    $namakelas="";
    foreach($project as $p){
        if($p->pa_file!=""){
            $namakelas=$p->cr_name;
            $fpath=FCPATH."media/project_assignment/".$p->pa_file;
            if(file_exists($fpath)){
                $this->zip->read_file($fpath);
            }else{
    
            }
        }else{
            
        }
       
    }
    $namezip=$namakelas."-".time().".zip";
    $this->zip->download($namezip);
    redirect($_SERVER['HTTP_REFERER']);
   
}

/*-- End Fodler download --*/
    
    
    
     /*-- Modul folder download --*/
     function download_module_assignment(){
        $this->load->library('zip');
    
        //DATA ZIP
        $filtermodul=array('cmm.classroom_id'=>$_GET['cr']);
       // $filtermodul=array('cmm.classroom_id'=>$crid);
        $modul=$this->classroom_model->get_module_assignment2($filtermodul);
        ///ZIP & Download
      
        foreach($modul as $m){
            $file=$m->file_assignment;
            $path= "../media/module_assignment/".$m->file_assignment;
            if($file==""){
                
            }else{
            // $filepath=$dir.$file;
                if (file_exists($path)) {
                    $fileurut=explode("/",$m->file_assignment);
                    $filename=$m->cr_name."-".$m->member_name."-".$fileurut[1];
                    $this->zip->add_data($filename, $file);
                } else {
                }
            }
           
       }
        $filename = "Module_assignment Kelas ".$modul[0]->cr_name."-".date('d-m-Y H:i:s').".zip";
        $this->zip->download($filename);        
    }
    /*-- End Fodler download --*/
     /*-- Add PK to Class --*/
     function upd_pk(){
        $datakode=explode("-",$_POST['kode']);

       $filter=array("cr_id"=>$datakode[0],"member_id"=>$datakode[1]);
         $dt_up=array("is_pk"=>$datakode[2]);
        $update_pk=$this->classroom_model->update_pk($filter,$dt_up);
        create_log($this->section_id,$update_pk,'update',"update status member: ".$datakode[1]." kelas:".$datakode[0]."pk='".$datakode[2]."'");
        $res=$update_pk;
        echo json_encode($res);
    }
    /*-- End add PK to Class --*/
     /*-- UPdate MEMBER_STATUS CLASS --*/
     // Auth : KDW
     // Date : 05.09.2023
     function upd_mstat(){
      
        $kode=explode("-",$_POST['kode']);
         $aksi=$kode[0];
         $cr_id=$kode[2];
         $member_id=$kode[1];
 
         $filter=array("cr_id"=>$kode[2],"member_id"=>$kode[1]);
         if($aksi=="batal"){
             $dt_up=array("member_status"=>"0");
         }else{
             $dt_up=array("member_status"=>"1");
         }
         $update_mstat=$this->classroom_member_model->update_member_class($filter,$dt_up);
         
             $des="Update status_member aksi='".$aksi."' , kelas='".$cr_id."', member='".$member_id."'";
             create_log($this->section_id,$update_mstat,"update",$des);
 
         $res=$update_mstat;
         echo json_encode($res);
    }
       /*-- END MEMBER KELAS UPDATE --*/
         /*-- API update kelas --*/
        function update_diklat_superapp(){
                $url=URL_SIP_PROJECT;
               $data=array("app"=>"postman","key"=>"dpostman","act"=>"link_diklat","agro_kelas"=>"2","superapp_diklat"=>"4");
                $options = array(
                            "http"=> array(
                                "method"=>"POST",
                                "header"=>"Content-Type: application/x-www-form-urlencoded",
                                "content"=>http_build_query($data)
                            )
                );
                $response=file_get_contents($url,false,stream_context_create($options));
                $result=json_decode($response);
                echo $result->code;
        }
    
        /*-- END API update Kelas --*/

        function tesquery(){
            $query1 = "SELECT cr_id,member_id,crm_step FROM _classroom_member 
            
                      ";
            $query2 = "SELECT cr_id,member_id,crm_step FROM _classroom_member 
                      WHERE cr_id IN ('630','632','635','636','637','640','641','643','644','646','647','648','649','651','652','653','654','655','656','658','659','660','661','662','663','664','665','666','667','668','669','670','671','672','673','674','675','676','677','678','679','680','681','682','683','684','685','687','688','689','690','691','692','693','694')
                      AND crm_step <>''";
            $query3 ="SELECT m.member_name,m.member_nip, g.group_name,c.cr_name,c.cr_id,cm.crm_step,c.cr_date_start as tanggal_kelas, g.group_name  FROM _classroom_member as cm 
                      LEFT JOIN _classroom as c ON c.cr_id=cm.cr_id
                      LEFT JOIN _member as m ON m.member_id=cm.member_id
                      LEFT JOIN _group as g ON g.group_id=m.group_id
                      where c.cr_create_date > '2023-11-01 00:00:00' 
                      AND c.cr_create_date <='2023-12-31 24:00:00'";
            $exe = $this->db->query($query3);
		    $row = $exe->result_array();
           //print_r($row);
           foreach ($row as $r){
                $crm_step_json = $r['crm_step'];
                $result = json_decode($r['crm_step'],TRUE);

                if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){

                    $ctScore = explode('-',$result['CT']['ctScore']);

                }else{

                    $ctScore = array('','','','');

                }
                $endScore = $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '';
                echo $r['cr_id'].";".$r['member_nip'].";".$r['member_name'].";".$r['group_name'].";".$r['cr_name'].";".date('d-m-Y',strtotime($r['tanggal_kelas'])).";".$ctScore[1].";".$ctScore[2].";".$endScore."<br>";
            
            } /*	*/
        }

   //----------------------//
    // Test Result Function //
    // Auth : KDW           //
    // Date : 24012023      //
    //----------------------//

    function test_result(){
        has_access('classroom.view');
        $pesan="";
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        }
        if($_POST){
            $mulai=isset($_POST['startDate']) ||$_POST['startDate']!=""?$_POST['startDate']:date();
            $selesai=isset($_POST['endDate']) || $_POST['endDate']!=""?$_POST['endDate']:$_POST['startDate'];
            $filter= "where cm.is_pk='0' AND c.cr_date_start >= '".$mulai." 00:00:00' 
                      AND c.cr_date_start <='".$selesai." 00:00:00'";
            $data['peserta'] = $this->classroom_model->get_posttes($filter);
            $data['kelas'] =$this->classroom_model->get_classresult($filter);
            $data['start']=$_POST['startDate'];
            $data['end']=$_POST['endDate'];
        }else{
            $filter="";
            $data['peserta'] = $this->classroom_model->get_posttes($filter);
            $data['kelas'] =$this->classroom_model->get_classresult($filter);
            $data['start']="";
            $data['end']="";
        }

        $data['page_name']          = "Class Room";
        $data['page_sub_name']      = 'Laporan Ujian';
        $data['page']               = 'classroom/classroom_test_result';
        $this->load->view('main_view',$data);
      
    }


     /*-- Evaluasi Kelas --*/
     // Auth : KDW
     // startDate : 10.01.2024

     function daftar_evaluasi(){
        $data=$this->ce->getall_evaluasi();
        $data['evaluasi']=$data;
        $data['tipe']=array('penyelenggaraan','sarana','narasumber','rekomendasi');
        $data['jenis']=array('internal','eksternal');
        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Class Room Evaluasi';
        $data['page_sub_name']      = 'Daftar Pertanyaan Evaluasi Kelas';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'classroom_evaluasi/v_evaluasi';
        $this->load->view('main_view',$data);
     }

     function add_evaluasi(){
        if(isset($_POST)){
            $data=$this->ce->add_evaluasi($_POST);
            if($data > 0){
                $respon = array("msg"=>"ada posting","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"data soal dan kategori sudah terpakai","stat"=>"false","data"=>$data);
            }
          
        }else{
            $respon = array("msg"=>"tidak ada posting","stat"=>"false","data"=>array());
              
        }
        
        echo json_encode($respon);
        exit;
     }

     function get_evaluasi(){
        $evaluasi=$_GET['evaluasi'];
        if(isset($_GET['evaluasi'])){
            $filter=array('id'=>$evaluasi);
            $data=$this->ce->get_evaluasi($filter);
            if(count((array)$data) > 0){
                $respon = array("msg"=>"tidak ada evaluasi","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"evaluasi tidak ditemukan","stat"=>"false","data"=>array());
            }
        }else{
            $respon = array("msg"=>"Evaluasi tidak ada","stat"=>"false","data"=>array());
        }
     
        echo json_encode($respon);
        exit;
     }

     function edit_evaluasi(){
        if(isset($_POST)){
            $data=$this->ce->edit_evaluasi($_POST);
            if($data > 0){
                $respon = array("msg"=>"data berhasil diubah","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"data soal dan kategori sudah terpakai","stat"=>"false","data"=>$data);
            }
          
        }else{
            $respon = array("msg"=>"tidak ada posting","stat"=>"false","data"=>array());
              
        }

        echo json_encode($respon);
        exit;
     }

     function hapus_evaluasi(){
        if(isset($_POST)){
            $data=$this->ce->hapus_evaluasi($_POST);
            if($data > 0){
                $respon = array("msg"=>"data berhasil diubah","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"data soal dan kategori sudah terpakai","stat"=>"false","data"=>$data);
            }
        }else{
            $respon = array("msg"=>"tidak ada posting","stat"=>"false","data"=>array());
              
        }

        echo json_encode($respon);
        exit;
     }

     function hapus_setsoal(){
        if(isset($_POST)){
            $data=$this->ce->hapus_setsoal($_POST);
            if($data > 0){
                $respon = array("msg"=>"data berhasil diubah","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"data soal dan kategori sudah terpakai","stat"=>"false","data"=>$data);
            }
        }else{
            $respon = array("msg"=>"tidak ada posting","stat"=>"false","data"=>array());
              
        }

        echo json_encode($respon);
        exit;
     }

     function hapus_setsoal_param(){
        if(isset($_POST)){
            $data=$this->ce->hapus_setsoalbyParam($_POST);
            if($data > 0){
                $respon = array("msg"=>"data berhasil diubah","stat"=>"true","data"=>$data);
            }else{
                $respon = array("msg"=>"data soal dan kategori sudah terpakai","stat"=>"false","data"=>$data);
            }
        }else{
            $respon = array("msg"=>"tidak ada posting","stat"=>"false","data"=>array());
              
        }

        echo json_encode($respon);
        exit;
     }

     function tes_setsoal(){
        $jenis="internal";
        $tipe="sarana";
        $data=$this->ce->set_soalbyType($tipe,$jenis);

        print_r($data);
        exit;
     }

     function npsreport(){
        has_access('classroom.view');
        $pesan="";
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        } 
        $dataresult=array();
        if(isset($_POST) && count((array)$_POST) > 0){
            $mulai =$_POST['startDate'];
            $selesai =$_POST['endDate'];
            $filter= "c.cr_date_start >= '".$mulai." 00:00:00' 
            AND c.cr_date_start <='".$selesai." 00:00:00'";
            $datakelas =$this->classroom_model->get_kelas($filter);
            $result=array();
            foreach($datakelas as $kelas){
                $filternps=array("cr_id"=>$kelas->cr_id,"status"=>"1");
                $isnps=$this->ce->cek_setsoal($filternps);
                if(count((array)$isnps) > 0){
                    //cek jawaban
                    $isjawab=$this->ce->get_jawab(array("cr_id"=>$kelas->cr_id));
                    
                    if(count((array)$isjawab) > 0){
                        foreach($isjawab as $j){
                           
                            $filterresult=array("id"=>$j->id,"status"=>"1");
                            $score=$this->ce->calc_nps($filterresult);
                            $nilai=$score[0]->nilai;
                            /////
                            switch($j->jenis){
                                case 'penyelenggaraan':
                                    $score=$this->ce->calc_nps($filterresult);
                                    $result[$j->cr_id][$j->jenis]=array("score"=>$nilai,"set_id"=>$j->set_id,"n_kelas"=>$kelas->cr_name);
                                break;
                                case 'sarana':
                                    $score=$this->ce->calc_nps($filterresult);
                                    $result[$j->cr_id][$j->jenis]=array("score"=>$nilai,"set_id"=>$j->set_id,"n_kelas"=>$kelas->cr_name);
                                break;
                                case 'narasumber':
                                    $score=$this->ce->calc_nps($filterresult);
                                    $pengajar= $this->ce->cek_setsoal(array("id"=>$j->set_id,"status"=>"1"));
                                    $result[$j->cr_id][$j->jenis][]=array("score"=>$nilai,"pengajar"=>$pengajar[0]->pengajar,"set_id"=>$j->set_id,"n_kelas"=>$kelas->cr_name);
                                break;
                            }
                        }
                    }else{
    
                    }
                                
                }else{
    
                }
    
               
              
            }
        
            foreach($result as $kelas=>$data){
                foreach($data as $jenis=>$data2){
                 if($jenis=="narasumber"){
                        $c=0;
                        $s=0;
                        $n_kelas="";
                        foreach($data2 as $dn){
                            $c++;
                            $s+=$dn['score'];
                            $n_kelas=$dn['n_kelas'];
                        }
                        $fix_score=round($s/$c,2);
                    }else{
                        $fix_score=$data2['score'];
                        $n_kelas=$data2['n_kelas'];
                       
                    }
                    $dataresult[$n_kelas][$jenis]=array("kelas_id"=>$kelas,"nama_kelas"=>$n_kelas,"jenis"=>$jenis,"nilai"=>$fix_score);
                }
                
    
               
            } 
            $data['start']=$mulai;
            $data['end']=$selesai;
            unset($_POST);
        }else{
            $data['start']=date('Y-m-d');
            $data['end']=date('Y-m-d');
         
        }
        $data['page_name']          = "Class Room";
        $data['page_sub_name']      = 'Laporan NPS Kelas';
        $data['page']               = 'classroom/classroom_nps_report_view';
        $data['result']               = $dataresult;
        $this->load->view('main_view',$data);
    }

    /* END  NPS EVALUASI */

}