<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_classroom extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'classroom_model',
            'classroom_evaluasi_model',
            'classroom_attendance_model'
        ));

    }

    public function index(){
        echo "test";
        die();
    }

    public function test_result(){
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

    public function detail_kelas(){
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
            $data['start']=$_POST['startDate'];
            $data['end']=$_POST['endDate'];
        }else{
            $filter="";
            $data['start']="";
            $data['end']="";
        }

        $kelas = "tes";

        echo $kelas;



        exit;
    }
}


?>