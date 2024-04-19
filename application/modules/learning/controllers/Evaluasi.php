<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi extends MX_Controller {
    public $title = 'Evaluasi';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['learning_wallet_model','member_level_karyawan_model','group_model', 'member_model']);
		$this->load->model('Classroom_evaluasi_model','ce');
		$this->load->model('Classroom_model','cl');
    }
	
	private function set_up_common_variable($crId = 0, $memberId = 0){
        if($this->input->get('cr_id') === NULL) redirect(base_url('learning/class_room'));

		$this->data['memberId'] = $memberId;

		$this->classroom_model->recData['crId'] = $crId;
		$this->classroom_model->recData['memberId'] = $memberId;
		$dataCr = $this->classroom_model->select_classroom("activeByMemberId");
		if (!$dataCr) redirect(base_url('learning/class_room'));
		$dataCr['group_name'] = $this->group_model->get_group_name($dataCr['group_id']);
		// validasi member_image
		$dataCr['member_image'] = validate_member_image($dataCr['member_image']);
		// qr code
		$qr_img = '';
		if ($dataCr['cr_type'] == 'inclasstraining'){
		    $qr_data = '{"cr_id":'.$crId.',"member_id":'.$memberId.'}';
		    $qr_data = base64_encode($qr_data);
		    $qr = new QRCode();
            $qr_img = $qr->render($qr_data);
        }
        $dataCr['qr_image'] = $qr_img;
		// qr code

		$this->data['data'] = $dataCr;
		
		// parse it first, because sometimes it return null
		$dataCr['cr_pretest'] = preg_replace("/[[:cntrl:]]/", "", $dataCr['cr_pretest']);
		$dataCr['cr_module'] = preg_replace("/[[:cntrl:]]/", "", $dataCr['cr_module']);
		$dataCr['cr_competency'] = preg_replace("/[[:cntrl:]]/", "", $dataCr['cr_competency']);
		$dataCr['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $dataCr['crm_step']);

		$this->data['dataPt'] = json_decode($dataCr['cr_pretest'],true);
		$this->data['dataMp'] = json_decode($dataCr['cr_module'],true);
		$this->data['dataCt'] = json_decode($dataCr['cr_competency'],true);

		$dataStep = json_decode($dataCr['crm_step'], true);
		$step = $this->set_up_step($dataStep, $dataCr);

		$this->data['dataStep'] = $step;

		if($dataCr['cr_info'] == ''){
			$cr_info = [
				'cr_id' => $dataCr['cr_id'],
				'cr_name' => $dataCr['cr_name'],
				'cat_id' => $dataCr['cat_id'],
				'cr_date_start' => $dataCr['cr_date_start'],
				'cr_date_end' => $dataCr['cr_date_end'],
				'cr_time_start' => $dataCr['cr_time_start'],
				'cr_time_end' => $dataCr['cr_time_end'],
				'cr_price' => $dataCr['cr_price'],
			];

			$this->classroom_model->update_cr_info($dataCr['crm_id'], $cr_info);
		}
	}
	
	public function index(){
		//header('location:'.base_url('learning/wallet/beranda'));
		$urlback="class_room/my_classroom";
		$kelas=$this->uri->segment(4);
		$member=$_SESSION['member_id'];

		$cek_member=$this->ce->check_member($kelas,$member);
		if(count((array)$cek_member)>0){
			
			//tampilkan evaluasi apa saja yang ada;
			$filter=array("status"=>"1","cr_id"=>$kelas);
			$dataevaluasi =$this->ce->get_evaluasibyFilter($filter);
			foreach($dataevaluasi as $de){
				echo "1(".$de->id."). jenis:".$de->jenis." , setsoal : ".$de->setsoal." , pengajar:".$de->pengajar."<br>"; 
			}
		}else{
			redirect($urlback);
		}

		//print_r($_SESSION['member_id']);
		//cek jika member bukan peserta kelas
		//$ismember=$this->cl->check_member($)
		//print_r($ismember);
		//cek 
		
		//$datakelas=$this->ce->get_classbyId($idkelas);
		//print_r($datakelas);
		

		exit;
	}

	/* Evaluasi NPS */
	//auth : KDW
	//date :24012024


	function nps_list(){
		$cr_id=$this->uri->segment(4);
		$urlback='learning/class_room/my_classroom';

		$terjawab=0;
		$tersedia=0;
		$datanps=array();
		if(isset($cr_id) && $cr_id!=""){ 
			$filter=array("cr_id"=>$cr_id,"status"=>"1");
			$npsset=$this->ce->get_evaluasibyFilter($filter);
			if(count((array)$npsset) > 0){
				foreach($npsset as $n){
					$tersedia++;
					$pengajar=isset($n->pengajar)&&$n->pengajar!="" ?$n->pengajar:"";
					//cek jika sudah dijawab
					$filter2=array("set_id"=>$n->id);
					$isjawab=$this->ce->get_jawab($filter2);
					if(count((array)$isjawab)>0){
						$terjawab ++;
						$datanps[]=array("nps"=>$n->id,"pengajar"=>$pengajar,"nps_jawab"=>$isjawab[0]->id,"jawab"=>$isjawab[0]->jawab,"score"=>$isjawab[0]->score,"tanggal"=>date('d-m-Y',strtotime($isjawab[0]->edit_date)),"keterangan"=>$isjawab[0]->keterangan,"jenis"=>$n->jenis);
					}else{
						$datanps[]=array("nps"=>$n->id,"pengajar"=>$pengajar,"nps_jawab"=>"","jawab"=>"","score"=>"","tanggal"=>"","keterangan"=>"","jenis"=>$n->jenis);
					}
				}
				$this->data['npsset']=$npsset;
				$this->data['npsdata']=$datanps;
				$this->data['tersedia']=$tersedia;
				$this->data['terjawab']=$terjawab;
				$this->data['persen']=$terjawab>0?($terjawab/$tersedia)*100:0;
				$this->data['kelas']=$cr_id;
			}else{
				$this->data['npsset']=array();
				$this->data['npsdata']=array();
				$this->data['tersedia']=0;
				$this->data['terjawab']=0;
				$this->data['persen']=0;
				$this->data['kelas']=$cr_id;
			}
			
			$this->data['title'] = 'Daftar Evaluasi NPS';
			$this->page = 'class_room/nps_list';
			$this->menu = 'learning';
			$this->generate_layout();
		}else{
			redirect($urlback);
		}
	}

	/* END EVALUASI NPS */


	/* Evaluasi NPS */
	//auth : KDW
	//date :11012024
    public function evaluasi_mulai(){
		$this->data['title'] = $this->title;
		$this->page = 'evaluasi/evaluasi_mulai';

		
		$input=$this->uri->segment(4); 
		if(isset($input) && $input!=""){
			//
			$datainput=explode("-",$input);
			$kelas=$datainput[0];
			$jenis=$datainput[1];
			$pengajar=urldecode($datainput[2]);
			$datakelas=array();
			$datasoal=array();
			$urlback='learning/evaluasi/nps_list/'.$kelas;
			if($jenis=="narasumber"){
				$filter =array("cr_id"=>$kelas,"jenis"=>$jenis,"pengajar"=>$pengajar,"status"=>"1","tipe"=>"internal");
			}else{
				$filter =array("cr_id"=>$kelas,"jenis"=>$jenis,"status"=>"1","tipe"=>"internal");
			}
			
			//get setsoal dengan filter
			$datasoal=$this->ce->get_evaluasibyFilter($filter);
			
			if(count((array)$datasoal) > 0){
				//cek apakah sudah ada atau belum, kalau sudah ada maka lempar ke depan lagi
				$filterada=array("member_id"=>$_SESSION['member_id'],"cr_id"=>$kelas,"set_id"=>$datasoal[0]->id);
				$cekada=$this->ce->get_jawab($filterada);
				if(count((array)$cekada) > 0){
					redirect($urlback);
				}else{
					$datakelas=$this->ce->get_classbyId($kelas);
					$this->data['kelas']=$datakelas;
					$this->data['setsoal']=$datasoal;
					$this->generate_layout();
				}
			}else{
				redirect($urlback);
			}
		}else{
			redirect('learning/class_room/mylasscroom');
		}
		
	}

	function grabquiz(){
		/// ambil set soal
		//$setsoal=$_GET['evaluasi'];
		$id=$this->input->get('evaluasi');
		$datasoal=$this->ce->get_listsoal($id);

		foreach($datasoal as $d){
			$lsoal=explode(",",$d->setsoal);
			foreach ($lsoal as $si){
				$soal=$this->ce->get_soal($si); 
				///masukkan ke template
				$data[]=(object)[
							"elements"=>array(
												(object)[
													"name"=>"soal".$si,
													"title"=>$soal[0]->soal,
													"type"=>"rating",
													"rateMin"=>"1",
													"rateMax"=>"10",
													"mininumRateDescription"=> "Buruk",
													"maximumRateDescription"=> "Baik"
												]
											)
							];
			}
		}
	
		///tambahan untuk rekomendasi
		$jenis=isset($_GET['jenis'])?$_GET['jenis']:"internal";//isset($this->input->get('jenis')) && $this->input->get('jenis')!=""?$this->input->get('jenis'):'internal';
		$rekomendasi=$this->ce->get_soalFilter(" tipe='rekomendasi' AND jenis='".$jenis."'");

		foreach($rekomendasi as $r){
			$data[]=(object)["elements"=>array(
				(object)[
						"name"=>"rekomendasi ".$r->id,
						"title"=>$r->soal,
						"type"=>"comment"
						]
				)
			];
		}
		
	
		if(isset($id)){
			$respon=array("msg"=>"konten quiz untuk evaluasi ".$id,"stat"=>"true","data"=>$data);
		}else{
			$respon=array("msg"=>"konten quiz tidak ditemukan ".$id,"stat"=>"false","data"=>array());
		}
	
		echo json_encode($respon);
		exit;
	}

	function savequiz(){
		$respon=array();
		
		if(isset($_POST)){
			$soal=$_POST['soal'];
			$data=json_decode($_POST['data']);
			$jawab="";
			$deskripsi="";
			foreach($data as $key=>$d){
				if(strstr($key,"rekomendasi")==false){
					$jawab.=$d.",";
				}else{
					$deskripsi.=$d.";";
				} 
			}
			$jawab=rtrim($jawab,",");	
			// hitung skor NPS 
			$member=$_SESSION['member_id'];
			$keterangan = rtrim($deskripsi,";");
			$NPS=$this->ce->NPScalc($jawab);
			//input jawaban ke database
			$datainput=array("member_id"=>$member,"cr_id"=>$_POST['kelas'],"set_id"=>$_POST['soal'],'jawab'=>$jawab,'score'=>$NPS,'keterangan'=>$keterangan,'jenis'=>$_POST['jenis'],'tipe'=>$_POST['tipe']);
			$addevaluasi=$this->ce->add_evaluasi($datainput);
			if($addevaluasi > 0){
				$respon=array("stat"=>"true","msg"=>"evaluasi telah berhasil dimasukkan","data"=>$addevaluasi);
			}else{
				$respon=array("stat"=>"false","msg"=>"evaluasi gagal dilakukan","data"=>"");
			}
			$respon=array("stat"=>"true","msg"=>"evaluasi telah berhasil dimasukkan","data"=>$datainput);
			
		}else{
			$respon=array("stat"=>"false","msg"=>"tidak ada data terinput","data"=>array());
		}
		

		echo json_encode($respon);
		exit;
	}

	
}