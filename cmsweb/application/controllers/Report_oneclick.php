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
        $this->load->model('Report_oneclick_model','ro');
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
            $data['evaluasi']=$dtevaluasi;

            //data peserta
            $filter=" WHERE c.cr_id='".$cr."'";
            $data['peserta']['test'] = $this->classroom_model->get_posttes($filter);
            $data['peserta']['detail'] = $this->classroom_model->get_posttes($filter);
            //tambahan
            $data['peserta']['feedback']=$this->classroom_model->get_feedback($filter);

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


    function get_peserta($filter){
        $get_peserta =array();

        return $get_peserta();
    }



   // whislist dan tracking persetujuan peserta kelas
   // auth :KDW
   // date : 30.04.2024

    function tracking_whislist(){
        has_access('classroom.view');
        $pesan="";
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        }

        //whislist tracking
        $mulai=isset($_POST['startDate']) && $_POST['startDate']!=""?$_POST['startDate']:date('Y-01-01');
        $selesai=isset($_POST['endDate']) && $_POST['endDate']!=""?$_POST['endDate']:date('Y-12-31');
        $filter1 ="WHERE m.member_name <>'Tim Developer IT'
            AND wl.status = 'aktif' AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' ";
        $datawhistlist=$this->ro->get_daftar_whislist($filter1);
        $data['whislist']=$datawhistlist;

        //peserta approvall
        $filter2 ="WHERE m.member_name <>'Tim Developer IT'
        AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' ";
        $datatrack=$this->ro->get_approval_peserta($filter2);
        $data['tracking']=$datatrack;
        

        ///display
        $data['start']=$mulai;
        $data['end']=$selesai;
        $data['page_sub_name']="Laporan Whislist dan Tracking Approval";
        $data['page_name']      = 'Laporan';
        $data['page'] = 'report/v_whistlist_track';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);

    }

}

?>