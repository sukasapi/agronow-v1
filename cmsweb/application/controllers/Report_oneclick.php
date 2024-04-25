<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_oneclick extends CI_Controller {
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

    function index(){
       $cr=$this->uri->segment(2);
       if(isset($cr) && $cr!=""){
        $classroom = $this->get_classroom($cr);
        if(count((array)$classroom) > 0){
            $dtkelas=$classroom;
            $dtpeserta=array();
            $dtevaluasi=array();
          
            $data['kelas']=$dtkelas;
            $data['peserta']=$dtpeserta;
            $data['evaluasi']=$dtevaluasi;

            $data['page_sub_name']="Laporan Kelas";
            $data['page_name']      = 'Laporan';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'report/v_oneclick';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            echo "Alert !! data kelas tidak ditemukan. Back to previous";
            exit;
        }
       
        
       }else{
        echo "back to previous page";
        exit;
       }
       
    }

    function get_classroom($classroom_id){
        $get_classroom = $this->classroom_model->getclasswallet($classroom_id);
        if ($get_classroom==FALSE){
            redirect(404);
        }else{
            return $get_classroom;
        }
    }

}

?>