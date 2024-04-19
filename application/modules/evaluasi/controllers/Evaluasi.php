<?php
/**
 * Created by sukasapi.
 * User: LPPAN
 * Date: 01/01/24
 * Time: 12:47
 */

class Evaluasi extends MX_Controller
{
		public $title = 'Evaluasi Produk';
		//public $menu = 'learning';
	
		public function __construct(){
			parent::__construct();
			$this->load->library('function_api');
			$this->load->model(['classroom_model', 'group_model', 'member_model']);
			$this->load->model('project_assignment_model','pa' );
			$this->load->model('module_assignment_model','ma' );
			$this->load->model('classroom_evaluasi_model','ce' );
			$this->data['title'] = $this->title;
			$this->data['back_url'] = $_SERVER['HTTP_REFERER'] ?? NULL;
			//$this->member_id = $this->session->userdata('member_id');
			//$this->bidang = $this->session->userdata('member_bidang');
			//encryption set
			$this->load->library('encryption');
			// special ID, add highlight on correct test answer
			// "6020","6019","6005","6006","6007","6008","6125","6054","3016","6178" 
			$this->data['specialId'] = array("6020","6125","8684");
			$this->section_id = 31;
		}
	
	function index(){
		$data['section_id']     = $this->section_id;
		session_destroy();
        $data['page_name']          = 'NPS';
        $data['page_sub_name']      = 'Masuk Evaluasi';
		$this->page = 'evaluasi/evaluasi';
		$this->generate_layout();
	}

	function kelas(){
		//cek jika session profil ada 
		if(isset($_SESSION['tokenOK']) && $_SESSION['tokenOK'] > 0){
			//cek jika ada profil, maka langsung ke list, jika tidak isi dulu profil
			if(isset($_SESSION['profil']) && count((array)$_SESSION['profil'])> 0){
				$cr_id=$_SESSION['cr_id'];
				$filter=array("cr_id"=>$cr_id,"status"=>"1");
				$nama=$_SESSION['profil']['nama'];
				$nip=$_SESSION['profil']['nip'];

				$filter=array("cr_id"=>$cr_id,"status"=>"1");
				$npsset=$this->ce->get_evaluasibyFilter($filter);

				$tersedia = 0;
				$terjawab = 0;
				$dataisjawab=array();

				foreach($npsset as $n){
					$tersedia++;
					
					$pengajar=isset($n->pengajar)&&$n->pengajar!="" ?$n->pengajar:"";
					//cek jika sudah dijawab
					$filter2=array("set_id"=>$n->id,"member_ext"=>strtoupper($nama),"nik_ext"=>strtoupper($nip));
					$isjawab=$this->ce->get_jawab($filter2);
					if(count((array)$isjawab)>0){
						$terjawab ++;
						$datanps[]=array(
							"nps"=>$n->id,
							"pengajar"=>$pengajar,
							"nps_jawab"=>$isjawab[0]->id,
							"jawab"=>$isjawab[0]->jawab,
							"score"=>$isjawab[0]->score,
							"tanggal"=>date('d-m-Y',strtotime($isjawab[0]->edit_date)),
							"keterangan"=>$isjawab[0]->keterangan,
							"jenis"=>$n->jenis);
					}else{
						$datanps[]=array(
							"nps"=>$n->id,
							"pengajar"=>$pengajar,
							"nps_jawab"=>"",
							"jawab"=>"",
							"score"=>"",
							"tanggal"=>"",
							"keterangan"=>"",
							"jenis"=>$n->jenis
						);
					}
					$dataisjawab[]=$isjawab;
				}

					$this->data['npsset']=$npsset;
					$this->data['npsdata']=$datanps;
					$this->data['tersedia']=$tersedia;
					$this->data['terjawab']=$terjawab;
					$this->data['isjawab']=$dataisjawab;
					$this->data['persen']=$terjawab>0?($terjawab/$tersedia)*100:0;
					$this->data['kelas']=$cr_id;
					$this->data['title'] = 'Daftar Evaluasi NPS';
					$this->page = 'evaluasi/nps_list';
					$this->generate_layout();

			}else{
				$filter=" cr_kode='".md5($_SESSION['pin'])."'";
				$kelas=$this->ce->get_classbyFilter($filter);
				if(count((array)$kelas)> 0 && $kelas[0]->cr_kelola!="dalam_app"){
					$datakelas=$kelas;
					$dataperusahaan=$this->group_model->select_group("active");
					$this->data['perusahaan']=$dataperusahaan;
					$this->data['kelas']=$datakelas;
					$this->page = 'evaluasi/profil';
					$this->generate_layout();
				}else{
					$this->session->set_flashdata('info','Anda harus menjadi member agronow untuk mengakses kelas ini');
					redirect('evaluasi');
				}
			}
		
		}else{
			if(isset($_POST) && $_POST['pin']!=""){
				$filter=" cr_kode='".md5($_POST['pin'])."'";
				$kelas=$this->ce->get_classbyFilter($filter);
				if(count((array)$kelas)> 0 && $kelas[0]->cr_kelola!="dalam_app"){
					$datakelas=$kelas;
					$this->session->set_userdata('tokenOK','1');
					$this->session->set_userdata('cr_id',$kelas[0]->cr_id);
					$this->session->set_userdata('pin',$_POST['pin']);
					$dataperusahaan=$this->group_model->select_group("active");
					$this->data['perusahaan']=$dataperusahaan;
					$this->data['kelas']=$datakelas;
					$this->page = 'evaluasi/profil';
					$this->generate_layout();
				}else{
					$this->session->set_flashdata('info','Anda harus menjadi member agronow untuk mengakses kelas ini');
					redirect('evaluasi');
				}
				
			}else{
				$this->session->set_flashdata('info','Pin tidak ditemukan');
				redirect('evaluasi');
			}
		}
	
		
	}
	
	function profil(){
		if(isset($_SESSION['tokenOK']) && $_SESSION['tokenOK'] > 0){
			if(isset($_SESSION['profil']) && count((array)$_SESSION['profil']) > 0){
				
				
				$cr_id=$_SESSION['profil']['kelasd'];
				$filter=array("cr_id"=>$cr_id,"status"=>"1");
				$nama=$_SESSION['profil']['nama'];
				$nip=$_SESSION['profil']['nip'];

				$filter=array("cr_id"=>$cr_id,"status"=>"1");
				$npsset=$this->ce->get_evaluasibyFilter($filter);

				$tersedia = 0;
				$terjawab = 0;
				$dataisjawab=array();
				if(count((array)$npsset) > 0){
					foreach($npsset as $n){
						$tersedia++;
						
						$pengajar=isset($n->pengajar)&&$n->pengajar!="" ?$n->pengajar:"";
						//cek jika sudah dijawab
						$filter2=array("set_id"=>$n->id,"member_ext"=>strtoupper($nama),"nik_ext"=>strtoupper($nip));
						$isjawab=$this->ce->get_jawab($filter2);
						if(count((array)$isjawab)>0){
							$terjawab ++;
							$datanps[]=array(
								"nps"=>$n->id,
								"pengajar"=>$pengajar,
								"nps_jawab"=>$isjawab[0]->id,
								"jawab"=>$isjawab[0]->jawab,
								"score"=>$isjawab[0]->score,
								"tanggal"=>date('d-m-Y',strtotime($isjawab[0]->edit_date)),
								"keterangan"=>$isjawab[0]->keterangan,
								"jenis"=>$n->jenis);
						}else{
							$datanps[]=array(
								"nps"=>$n->id,
								"pengajar"=>$pengajar,
								"nps_jawab"=>"",
								"jawab"=>"",
								"score"=>"",
								"tanggal"=>"",
								"keterangan"=>"",
								"jenis"=>$n->jenis
							);
						}
						$dataisjawab[]=$isjawab;
					}
	
						$this->data['npsset']=$npsset;
						$this->data['npsdata']=$datanps;
						$this->data['tersedia']=$tersedia;
						$this->data['terjawab']=$terjawab;
						$this->data['isjawab']=$dataisjawab;
						$this->data['persen']=$terjawab>0?($terjawab/$tersedia)*100:0;
						$this->data['kelas']=$cr_id;
				}else{
					$this->data['npsset']=array();
					$this->data['npsdata']=array();
					$this->data['tersedia']=0;
					$this->data['terjawab']=0;
					$this->data['isjawab']=0;
					$this->data['persen']=0;
					$this->data['kelas']=$cr_id;
				}
			
					$this->data['title'] = 'Daftar Evaluasi NPS';
					$this->page = 'evaluasi/nps_list';
					$this->generate_layout();
			/**/
			}else{
				$cr_id=$cr_id=$_SESSION['kelasd'];
				$filter=array("cr_id"=>$cr_id,"status"=>"1");
				$kelas=$this->ce->get_classbyFilter($filter);
				$this->data['kelas']=$kelas;		
				$this->page = 'evaluasi/profil';
				$this->generate_layout();
			}
		
		}else{
			$this->session->set_flashdata('info','Pin Evaluasi tidak ditemukan');
			redirect('evaluasi');
		}
		

	}


	function profil_add(){
		if(isset($_POST) && count((array)$_POST)>5){
			$dataprofil=array(
				"nama"=>$_POST['nama'],
				"nip"=>$_POST['nip'],
				"kelas"=>$_POST['kelas'],
				"kelasd"=>$_POST['autokelas_id'],
				"pin"=>$_POST['pin']
			);
			$this->session->set_userdata('profil',$dataprofil);
			$cr_id=$_POST['autokelas_id'];
			$filter=array("cr_id"=>$cr_id,"status"=>"1");
			
			$nama=$_POST['nama'];
			$nip=$_POST['nip'];

			$filter=array("cr_id"=>$cr_id,"status"=>"1");
			$npsset=$this->ce->get_evaluasibyFilter($filter);

			$tersedia = 0;
			$terjawab = 0;
			$dataisjawab=array();
			if(count((Array)$datanps)>0){
				foreach($npsset as $n){
					$tersedia++;
					
					$pengajar=isset($n->pengajar)&&$n->pengajar!="" ?$n->pengajar:"";
					//cek jika sudah dijawab
					$filter2=array("set_id"=>$n->id,"member_ext"=>strtoupper($nama),"nik_ext"=>strtoupper($nip));
					$isjawab=$this->ce->get_jawab($filter2);
					if(count((array)$isjawab)>0){
						$terjawab ++;
						$datanps[]=array(
							"nps"=>$n->id,
							"pengajar"=>$pengajar,
							"nps_jawab"=>$isjawab[0]->id,
							"jawab"=>$isjawab[0]->jawab,
							"score"=>$isjawab[0]->score,
							"tanggal"=>date('d-m-Y',strtotime($isjawab[0]->edit_date)),
							"keterangan"=>$isjawab[0]->keterangan,
							"jenis"=>$n->jenis);
					}else{
						$datanps[]=array(
							"nps"=>$n->id,
							"pengajar"=>$pengajar,
							"nps_jawab"=>"",
							"jawab"=>"",
							"score"=>"",
							"tanggal"=>"",
							"keterangan"=>"",
							"jenis"=>$n->jenis
						);
					}
					$dataisjawab[]=$isjawab;
				}
	
				$this->data['npsset']=$npsset;
				$this->data['npsdata']=$datanps;
				$this->data['tersedia']=$tersedia;
				$this->data['terjawab']=$terjawab;
				$this->data['isjawab']=$dataisjawab;
				$this->data['persen']=$terjawab>0?($terjawab/$tersedia)*100:0;
				$this->data['kelas']=$cr_id;
				$this->data['title'] = 'Daftar Evaluasi NPS';
			}else{
				$this->data['npsset']=array();
				$this->data['npsdata']=array();
				$this->data['tersedia']=0;
				$this->data['terjawab']=0;
				$this->data['isjawab']=0;
				$this->data['persen']=0;
				$this->data['kelas']=$cr_id;
				$this->data['title'] = 'Daftar Evaluasi NPS';
			}
			
			$this->page = 'evaluasi/nps_list';
			$this->generate_layout();
		}else{
			$this->session->set_flashdata('info','Data harus terisi dengan lengkap');
			redirect('evaluasi/profil');
		}
	}

	// update 20.02.2024
	// NPSlist
	function evaluasi_mulai(){
		$input=$this->uri->segment(3); 
		//print_r($_SESSION);
		$urlback='evaluasi/profil';
		if(isset($input) && $input!=""){
			$datainput=explode("-",$input);
			$kelas=$datainput[0];
			$jenis=$datainput[1];
			$pengajar=urldecode($datainput[2]);
			$datakelas=array();
			$datasoal=array();
		
			if($jenis=="narasumber"){
				$filter =array("cr_id"=>$kelas,"jenis"=>$jenis,"pengajar"=>$pengajar,"status"=>"1","tipe"=>"eksternal");
			}else{
				$filter =array("cr_id"=>$kelas,"jenis"=>$jenis,"status"=>"1","tipe"=>"eksternal");
			}

			//get setsoal dengan filter
			$datasoal=$this->ce->get_evaluasibyFilter($filter);

			if(count((array)$datasoal) > 0){
				//cek apakah sudah ada atau belum, kalau sudah ada maka lempar ke depan lagi
				$nama=$_SESSION['profil']['nama'];
				$nip=$_SESSION['profil']['nip'];
				$filterada=array("member_ext"=>strtoupper($nama),"nik_ext"=>$nip,"cr_id"=>$kelas,"set_id"=>$datasoal[0]->id);
				$cekada=$this->ce->get_jawab($filterada);
				if(count((array)$cekada) > 0){
					redirect($urlback);
				}else{
					$datakelas=$this->ce->get_classbyId($kelas);
					$this->page = 'evaluasi/evaluasi_mulai';
					$this->data['kelas']=$datakelas;
					$this->data['setsoal']=$datasoal;
				
					$this->generate_layout();
				}
			}else{
			
				redirect($urlback);
			}
		}else{
			redirect($urlback);
		}

	}

	function pilih_kelas(){
		//cek jika ada data terposting

		if(isset($_POST) && count((Array)$_POST)> 0){
			$this->data['data']=$this->input->post();
			$this->page = 'evaluasi/evaluasi_mulai';
		}else{
			//tampilkan pesan
			redirect('evaluasi/profil');
		}
	}

	function get_extclass(){

		///kelas dengan cr_kelola lms_ext_agronow
		
		$searchTerm = $_GET['term'];
		$filter="cr_kelola='lms_ext_agronow' AND cr_name like '%".$searchTerm."%'";
		$datakelas=$this->ce->get_classbyFilter2($filter);
		foreach($datakelas as $kelas){
			$result[]=array("value"=>$kelas->cr_id,"label"=>$kelas->cr_name);
		}
		
		echo json_encode($result);
		exit;
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
	
		// Update 20.02.2024
		///tambahan untuk rekomendasi
		$jenis=isset($_GET['jenis'])?$_GET['jenis']:"eksternal";//isset($this->input->get('jenis')) && $this->input->get('jenis')!=""?$this->input->get('jenis'):'internal';
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
		
		
		/*$data[]=(object)["elements"=>array(
								(object)[
										"name"=>"keterangan",
										"title"=>"Bagaimanakah kami dapat meningkatkan  kelas kami?",
										"type"=>"comment"
										]
								)
						];
						*/
	
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
			
			//update 20.02.2024
			//catch rekomendasi 
			foreach($data as $key=>$d){
				if(strstr($key,"rekomendasi")==false){
					$jawab.=$d.",";
				}else{
					$deskripsi.=$d.";";
				} 
			}
			/*foreach($data as $key=>$d){
				if($key!='keterangan'){
					$jawab.=$d.",";
				}else{

				}
			}*/
			$jawab=rtrim($jawab,",");	
			// hitung skor NPS 
			$member=isset($_SESSION['member_id']) && $_SESSION['member_id']!=""?$_SESSION['member_id']:"0";
			$member_ext=$_SESSION['profil']['nama'];
			$nik_ext=$_SESSION['profil']['nip'];
			$keterangan = rtrim($deskripsi,";");
			$NPS=$this->ce->NPScalc($jawab);
			//input jawaban ke database
			$datainput=array("member_id"=>$member,"member_ext"=>$member_ext,"nik_ext"=>$nik_ext,"cr_id"=>$_POST['kelas'],"set_id"=>$_POST['soal'],'jawab'=>$jawab,'score'=>$NPS,'keterangan'=>$keterangan,'jenis'=>$_POST['jenis'],'tipe'=>'eksternal');
			$addevaluasi=$this->ce->add_evaluasi($datainput);
			if($addevaluasi > 0){
				$respon=array("stat"=>"true","msg"=>"evaluasi telah berhasil dimasukkan","data"=>$addevaluasi);
			}else{
				$respon=array("stat"=>"false","msg"=>"evaluasi gagal dilakukan","data"=>"");
			}
			
		}else{
			$respon=array("stat"=>"false","msg"=>"tidak ada data terinput","data"=>array());
		}
		

		echo json_encode($respon);
		exit;
	}

}
