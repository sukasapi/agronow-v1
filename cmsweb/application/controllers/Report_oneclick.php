<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $getjawab=array();
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

            //absensi
            $data['absen']=$this->classroom_attendance_model->get_by_classroom($cr);

            //data evaluasi penyelenggaraan
          /*  $dtEval=array();
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
            */

            ///evaluasi 
            $dtevaluasi=$this->get_evaluasi_result($cr);
            $reseval=array();
            foreach($dtevaluasi as $key=>$val){
                $scoretotal=0;
                foreach($val as $key2=>$val2){
                    
                    //avg score
                    $score=array_sum($val2['jawab'])/count((array)$val2['jawab']);
                    //echo array_sum($val2['jawab']);
                    //print_r($val2['jawab']);
                    $scoretotal+=$score;
                    $reseval[$key][]=array("soal"=>$key2,"score"=>$score);
                }
                $rata=ROUND($scoretotal/count((Array)$val),2);
                $reseval[$key]['total']=$rata;
            }          
           
            $data['NPS']=$reseval;
            
          
           // $data['totalevaluasi']=isset($totalscore)?$totalscore:0;;
            //$data['detailevaluasi']=isset($getjawab)?$getjawab:array();
         
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


        $entitasdisp=$this->get_subcomp();


        $mulai=isset($_POST['startDate']) && $_POST['startDate']!=""?$_POST['startDate']:date('Y-01-01');
        $selesai=isset($_POST['endDate']) && $_POST['endDate']!=""?$_POST['endDate']:date('Y-12-31');

        switch ($level){
            case '1':
                $filter1 ="WHERE m.member_name <>'Tim Developer IT'
                AND wl.status IN ('aktif','dihapus') AND wl.tanggal >='".$mulai."' AND wl.tanggal<='".$selesai."' 
                AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")";

                $filter2 ="WHERE m.member_name <>'Tim Developer IT'
                AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")";

                $filter3 = " WHERE wl.id_member IS NULL AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")" ;
            break;
            case '13':
                $filter1 ="WHERE m.member_name <>'Tim Developer IT'
                AND wl.status IN ('aktif','dihapus') AND wl.tanggal >='".$mulai."' AND wl.tanggal<='".$selesai."' 
                AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")";

                $filter2 ="WHERE m.member_name <>'Tim Developer IT'
                AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")";

                $filter3 = " WHERE wl.id_member IS NULL AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3) AND g.group_id IN (".$entitasdisp.")" ;
            break;
            case '23':
             
                $filter1 ="WHERE m.member_name <>'Tim Developer IT'
                AND wl.status IN ('aktif','dihapus') AND wl.tanggal >='".$mulai."' AND wl.tanggal<='".$selesai."' AND g.group_id IN (".$entitasdisp.")
                AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3)";
                
                $filter2 ="WHERE m.member_name <>'Tim Developer IT'
                AND wc.tgl_mulai >='".$mulai."' AND wc.tgl_mulai<='".$selesai."' AND g.group_id IN (".$entitasdisp.") 
                AND m.member_status='active'  AND m.id_level_karyawan IN (1,2,3)";

                $filter3 = " WHERE g.group_id IN (".$entitasdisp.") AND wl.id_member IS NULL AND m.member_status='active' AND m.id_level_karyawan IN (1,2,3)";
            break;
            default:

            break;
        }
        
        //whislist tracking
        $datawhistlist=$this->ro->get_daftar_whislist($filter1);
        $data['whislist']=$datawhistlist;

        //peserta approvall
        $datatrack=$this->ro->get_approval_peserta($filter2);
        $data['tracking']=$datatrack; 

        //data summary entitas
        $dsum=$this->ro->get_summary_entitas($entitasdisp);
        $data['summary']=$dsum;

        //data NON whislist member
      
        $dnw =$this->ro->get_nonwishlist_member($filter3);
        $data['nowishlist']=$dnw;
        $data['printnw']= $level;//$filter3;//$this->ro->get_nonwishlist_member2($filter3);
        
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

    function get_subcomp(){
        $res="";
        $data= $this->group_model->get_child_company("","self_child");
        $juml_group = count($data);
        if(count((array)$data) ==1){
           $res= $this->session->userdata('group_id');
        }else{
            foreach($data as $d){
                $res.=$d['group_id'].",";
            }
        }
        $res=rtrim($res,",");
        return $res;
    }


    function test(){
        $cr="867";
        $classroom = $this->get_classroom($cr);
        $getjawab=array();
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

            //absensi
            $data['absen']=$this->classroom_attendance_model->get_by_classroom($cr);

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
           

            ///evaluasi 
            $dtevaluasi=$this->get_evaluasi_result($cr);
            $reseval=array();
            foreach($dtevaluasi as $key=>$val){
                foreach($val as $key2=>$val2){
                    
                    //avg score
                    $score=array_sum($val2['jawab'])/count((array)$val2['jawab']);
                    //echo array_sum($val2['jawab']);
                    //print_r($val2['jawab']);
                    $reseval[$key][]=array("soal"=>$key2,"score"=>$score);
                }
            }            

           foreach($reseval as $key=>$val){
                echo $key;
                echo "<br>";
                $scoretotal=0;
                foreach($val as $v){
                    echo $v['soal']." | ".$v['score'];
                    $scoretotal +=$v['score'];
                    echo "<br>";
                }
                $rata=ROUND($scoretotal/count((Array)$val),2);
                echo "total score :".$scoretotal." / jumlah data:" .count((array)$val)." =".$rata."<br>";
            
           }

    
          
            $data['totalevaluasi']=isset($totalscore)?$totalscore:0;;
            $data['detailevaluasi']=isset($getjawab)?$getjawab:array();
         
            $data['page_sub_name']="Laporan Kelas";
            $data['page_name']      = 'Laporan';
            $data['page_sub_name']  = $classroom['cr_name'];
            $data['page'] = 'report/v_oneclick';
            $data['submenu'] = 'classroom/classroom_detail_submenu_view';
            //$this->load->view('main_view',$data);
        }else{
            echo "Alert !! data kelas tidak ditemukan. Back to previous";
            exit;
        }
    }

    function convertexcel(){
        
       // Create new Spreadsheet object
            $tgl=date('d-m-y');
            $creator=$_SESSION['name'];
            $title="AGRONOW - ONE Click Report";
            $subject="One CLick Report";
            $desc="Dokumen report kelas ".$tgl;
            $keywords="one click report, excel";

               //create spreadsheet object
                $spreadsheet = new Spreadsheet();

                $spreadsheet->getProperties()->setCreator($creator)
                ->setLastModifiedBy($creator)
                ->setTitle($title)
                ->setSubject($subject)
                ->setDescription($desc)
                ->setKeywords($keywords);
            
                // Add some data
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Hello');
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('URL Added');

                //create sheet
                $spreadsheet->createSheet();

                // Add some data
                $spreadsheet->setActiveSheetIndex(1)
                ->setCellValue('A2', 'world!');

                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('URL Removed');


                  // Redirect output to a client’s web browser (Xls)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=""');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        exit;
    }

    function get_evaluasi_result($cr){
        if(isset($cr)){
            $res=array();
            $sql="SELECT * from _nps_set_soal WHERE cr_id='".$cr."'";
            $exe1=$this->db->query($sql)->result();
            foreach($exe1 as $s1){
                $setsoal=explode(",",$s1->setsoal);
                
                $sqljawab="SELECT jawab from _nps_jawab WHERE set_id='".$s1->id."'";
                $exe3=$this->db->query($sqljawab)->result();
                if($exe3){
                    $jawab=$exe3[0]->jawab;
                    $ljawab=explode(",",$jawab);
                    $idx=0;
                    foreach($setsoal as $ss){
                        $sqlsoal="SELECT soal from _nps_soal WHERE id='".$ss."'";
                        $exe2=$this->db->query($sqlsoal)->result();
        
                    
                        
                        foreach($exe2 as $s2){
                            $res[$s1->jenis][$s2->soal]['jawab'][]=$ljawab[$idx];
                            //echo $ss."-".$s2->soal."idx=".$idx." jawab :".$ljawab[$idx]."<br>";
                            //echo $ss."-".$s2->soal." jawab :".$exe3[$idx]->jawab."<br>";
                           
                        }
                        $idx++;
                    }
                }
              
               
            }
            return $res;
        }else{
            return array();
        }
    }
}

?>