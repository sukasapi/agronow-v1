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
            $filter=" WHERE c.cr_id='".$cr."' AND cm.is_pk='0'";
            $data['peserta']['test'] = $this->classroom_model->get_posttes($filter);
            $data['peserta']['detail'] = $this->classroom_model->get_posttes($filter);
            //tambahan
            $data['peserta']['feedback']=$this->classroom_model->get_feedback($filter);

            //data jumlah peserta,hari, presensi
            $filterjp="WHERE cr_id='".$cr."' AND is_pk='0'";
            $data['daftar_peserta']=$this->ro->get_count_peserta_kelas($filterjp);
            $data['jumlah_peserta']=$this->ro->get_count_presensi($cr);

            //kelulusan
            $flus="WHERE nilai_post_test > 70 AND is_pk='0' AND cr_id='".$cr."'";
            $data['lulus_peserta']=$this->ro->get_jumlah_lulus($flus);//$this->ro->get_count_lulus($filter);

            $filterhr="WHERE cr_id='".$cr."'";
            $data['jumlah_hari']=$this->ro->get_count_hari($filterhr);
            

            //data evaluasi penyelenggaraan
            $dtEval=array();
            $filternps=array("cr_id"=>$cr,"status"=>"1");
            $isnps=$this->ce->cek_setsoal($filternps);
            $result=array();
           
            if(count((array)$isnps) > 0){
                //cek jawaban
                $isjawab=$this->ce->get_jawab(array("cr_id"=>$cr));
                if(count((array)$isjawab) > 0){
                    foreach($isjawab as $j){
                        $filterresult=array("id"=>$j->id,"status"=>"1");
                        $filterresult2=array("nj.id"=>$j->id,"nj.status"=>"1");
                        $score=$this->ce->calc_nps($filterresult);
                        $nilai=$score[0]->nilai;
                        switch($j->jenis){
                            case 'penyelenggaraan':
                                $score=$this->ce->calc_nps($filterresult);
                                $result[$j->jenis]=array("score"=>$nilai,"set_id"=>$j->set_id,"n_kelas"=>$dtkelas['cr_name']);
                                //detail
                                $getjawab[$j->jenis]=$this->ce->get_jawabdetail($filterresult2);

                            break;
                            case 'sarana':
                                $score=$this->ce->calc_nps($filterresult);
                                $result[$j->jenis]=array("score"=>$nilai,"set_id"=>$j->set_id,"n_kelas"=>$dtkelas['cr_name']);
                                $getjawab[$j->jenis]=$this->ce->get_jawabdetail($filterresult2);
                            break;
                            case 'narasumber':
                                $score=$this->ce->calc_nps($filterresult);
                                $pengajar= $this->ce->cek_setsoal(array("id"=>$j->set_id,"status"=>"1"));
                                if(count((array)$pengajar) > 0){
                                    $result[$j->jenis][]=array("score"=>$nilai,"pengajar"=>$pengajar[0]->pengajar,"set_id"=>$j->set_id,"n_kelas"=>$dtkelas['cr_name']);
                                    $getjawab[$j->jenis][]=$this->ce->get_jawabdetail($filterresult2);
                                } else{

                                }
                            break;
                        }
                    }
                }else{

                }
            }

            if(count((Array)$result)>0){
                foreach($result as $jenis=>$data2){
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
                           $fix_score=round($data2['score'],2);
                           $n_kelas=$data2['n_kelas'];
                       }
                       $totalscore[$jenis]=array("nama_kelas"=>$n_kelas,"jenis"=>$jenis,"nilai"=>$fix_score);
                }
            }else{
                $total['sarana']=array();
                $total['penyelenggaraan']=array();
                $total['narasumber']=array();
            }
           
          
            $data['totalevaluasi']=isset($totalscore)?$totalscore:0;;
            $data['detailevaluasi']=isset($getjawab)?$getjawab:array();
         
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
        has_access('learningwallet.pelatihan_view',FALSE);
        $pesan="";
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('classroom');
        }

        $idus=$_SESSION['id'];
        $usdet = $this->get_company($idus);
        $level =$usdet['level'];
        $grup=$usdet['grup'];


        $mulai=isset($_POST['startDate']) && $_POST['startDate']!=""?$_POST['startDate']:date('Y-01-01');
        $selesai=isset($_POST['endDate']) && $_POST['endDate']!=""?$_POST['endDate']:date('Y-12-31');

        switch ($level){
            case '1':
                $filter1 ="WHERE m.member_name <>'Tim Developer IT'
            AND wl.status = 'aktif' AND wl.tanggal >='".$mulai."' AND wl.tanggal<='".$selesai."' ";

            $filter2 ="WHERE m.member_name <>'Tim Developer IT'
            AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' ";
            break;
            case '23':
                $filter1 ="WHERE m.member_name <>'Tim Developer IT'
                AND wl.status = 'aktif' AND wl.tanggal >='".$mulai."' AND wl.tanggal<='".$selesai."' AND g.group_id='".$grup."'";
                
                $filter2 ="WHERE m.member_name <>'Tim Developer IT'
                AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' AND g.group_id='".$grup."' ";
           
            break;
            default:

            break;
        }



        //whislist tracking
       
        $datawhistlist=$this->ro->get_daftar_whislist($filter1);
        $data['whislist']=$datawhistlist;

        //peserta approvall
        $filter2 ="WHERE m.member_name <>'Tim Developer IT'
        AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."'";
        $datatrack=$this->ro->get_approval_peserta($filter2);
        $data['tracking']=$datatrack; 

        //data summary entitas
        $filtergrup =
        $dsum=$this->ro->get_summary_entitas($grup);
        $data['summary']=$dsum;

        ///display
        $data['start']=$mulai;
        $data['end']=$selesai;
        $data['page_sub_name']="Laporan Whislist dan Tracking Approval";
        $data['page_name']      = 'Laporan';
        $data['page'] = 'report/v_whistlist_track';
        $data['submenu'] = 'classroom/classroom_detail_submenu_view';
        $this->load->view('main_view',$data);

    }

    function get_company($iduser=null){
        $datacompany=$this->ro->get_company($iduser);
        $res['level']=$datacompany[0]->user_level_id;
        $res['klien']=$datacompany[0]->id_klien;
        $res['grup']=$datacompany[0]->user_code;
        return $res;
    }

}

?>