<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Member_model member_model
 */

use chillerlan\QRCode\{QRCode, QROptions};

/**
 * @property Classroom_model classroom_model
 * @property Content_model content_model
 * @property Media_model media_model
 * @property Member_model member_model
 * @property Category_model category_model
 * @property Function_api function_api
 */
class Class_room extends MX_Controller {
	public $title = 'Class Room';
	public $menu = 'learning';

	public function __construct(){
		parent::__construct(); 
		if (empty($this->session->userdata('member_name'))){
			redirect('login');
		}
		$this->load->library('function_api');
		$this->load->model(['classroom_model', 'group_model', 'member_model']);
		$this->load->model('project_assignment_model','pa' );
		$this->load->model('module_assignment_model','ma' );
		$this->load->model('classroom_evaluasi_model','ce' );
		$this->data['title'] = $this->title;
		$this->data['back_url'] = $_SERVER['HTTP_REFERER'] ?? NULL;
        $this->member_id = $this->session->userdata('member_id');
        $this->bidang = $this->session->userdata('member_bidang');
		//encryption set
		$this->load->library('encryption');
		// special ID, add highlight on correct test answer
		// "6020","6019","6005","6006","6007","6008","6125","6054","3016","6178" 
		$this->data['specialId'] = array("6020","6125","8684");
	}

	public function index(){
		// get klien
		$kategori_klien = $this->session->userdata('kategori_klien');
		// get project assignment bawahan
		$project_assignment = $this->pa->getpa_parameter(array('atasan_id'=>$this->session->userdata('member_id')));
		
			//tambahan apakah PK
			$filter=array('cm.member_id'=>$_SESSION['member_id'],"is_pk"=>"1");
			$select='c.cr_name,cm.cr_id,cm.is_pk';
			$datacr=$this->pa->getcr_byparam($filter,$select);
			$is_pk="";
			if(count((array)$datacr)>0){
				$is_pk="ya";
			}else{
				$is_pk="tidak";
			}
			/*foreach($datacr as $cr){
				if($cr->is_pk > 0){
					$is_pk="ya";
				}else{
					
				}
			}*/
		
		if($kategori_klien=="classroom_only") {
			$this->page = 'class_room/klien_sederhana';
			$this->data['back_url'] = base_url('learning/class_room');
			$this->data['pa'] = $project_assignment;
			$this->data['ispk']=$is_pk;
			$this->data['pkcr']=$datacr;
			$this->customjs = array('klien_sederhana');
			$this->generate_layout();
		} else if($kategori_klien=="komplit") {
			$memberId = $this->session->userdata('member_id');
			$this->page = 'class_room/selection';
			$this->data['pa'] = $project_assignment;
			$this->data['member_id'] = $memberId;
			$this->data['pkcr']=$datacr;
			$this->data['ispk']=$is_pk;
			$this->data['back_url'] = base_url('learning');
			$this->generate_layout();
		} else {
			// do nothing
		}
	}

	public function my_classroom(){
		$memberId = $this->session->userdata('member_id');

		$this->page = 'class_room/lp';

		$this->data['member_id'] = $memberId;
 
		$this->classroom_model->recData['memberId'] = $memberId;
		$this->data['datas'] = $this->classroom_model->select_classroom("listByMemberId");

		$this->data['back_url'] = base_url('learning/class_room');

		$this->generate_layout();
	}

	public function buy_classroom(){
		$memberId = $this->session->userdata('member_id');

		$this->page = 'class_room/buy';

		$this->data['member_id'] = $memberId;

		$this->classroom_model->recData['memberId'] = $memberId;
		$this->data['datas'] = $this->classroom_model->select_classroom("listSell");

		$this->data['saldo'] = $this->member_model->get_member_saldo($memberId);

		$this->data['back_url'] = base_url('learning/class_room');
		$this->customjs = array('classroom_buy', 'reward');

		$this->generate_layout();
	}

	public function process_classroom(){
		if (!$this->input->is_ajax_request()) {
			redirect(base_url());
		}

		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$ret = [];
		$ret['status'] = 0;
		$ret['msg'] = '';

		$saldo = $this->member_model->get_member_saldo($memberId);

		$this->classroom_model->recData['crId'] = $crId;
		$classroom = $this->classroom_model->select_classroom('byId');

		if($saldo >= $classroom['cr_price']){
			$data = [
				'crId' => $crId,
				'memberId' => $memberId,
				'crmChannel' => '',
				'crmStep' => '',
				'crmFb' => '',
			];

			$this->db->trans_start();
			$this->classroom_model->insert_classroom_member($data);
			$this->member_model->add_purchase_to_member_saldo($memberId, $classroom['cr_name'], $classroom['cr_price'], $crId);

			// begin of poin
            $poin_setting = $this->member_model->select_member_poin_setting();
            $recData['memberId']    = $memberId;
            $recData['mpSection']   = 'CR';
            $recData['mpContentId'] = '';
            $recData['mpName']      = $classroom['cr_name'].' (Join)';
            $recData['mpPoin']      = $poin_setting[0]['mps_cr_join'];
            $this->member_model->insert_member_poin($recData);
            $recData['interval'] = '';
            $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
            $this->member_model->recData['memberId'] = $memberId;
            $this->member_model->update_member('byField','','member_poin',$total_poin);
            // end of poin

            if($this->db->trans_complete() !== false){
				$ret['status'] = 1;
				$ret['show_reward'] = true;
				$ret['reward'] = [
				    'poin' => $poin_setting[0]['mps_cr_join'],
				    'cause' => 'Join Classroom'
				];
			}else{
				$ret['status'] = 0;
				$ret['msg'] = 'Terjadi kesalahan dalam proses pembelian classroom. Mohon coba lagi atau segera hubungi admin jika tetap tidak bisa melakukan pembelian.';
			}
		}else{
			$ret['status'] = 0;
			$ret['msg'] = 'Mohon maaf, Saldo anda tidak mencukupi untuk melakukan pembelian classroom!';
		}

		echo json_encode($ret);
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
		/*$qr_img = '';
		if ($dataCr['cr_type'] == 'inclasstraining'){
		    $qr_data = '{"cr_id":'.$crId.',"member_id":'.$memberId.'}';
		    $qr_data = base64_encode($qr_data);
		    $qr = new QRCode();
            $qr_img = $qr->render($qr_data);
        }
        $dataCr['qr_image'] = $qr_img;*/
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

	private function set_up_step($step = array(), $dataCr = array()){
		$dataMp = json_decode($dataCr['cr_module'],true);

		if($step == ''){
			$step = array();
			$step['PL'] = array();
			$step['PL']['plStatus'] = "0";
			$step['PL']['plDate'] = "";
			$step['PT'] = array();
			$step['PT']['ptStatus'] = "0";
			$step['PT']['ptDate'] = "";
			$step['PT']['ptScore'] = "";
			$step['RP'] = "0";
			$step['MP'] = array();	


			$modulNum = count($dataMp['Module']);
			for($i=0;$i<$modulNum;$i++){
			    $materiNum = count($dataMp['Module'][$i]['Materi']);
				for($j=0;$j<$materiNum;$j++){
					$step['MP'][$i]['Materi'][$j] = ($i==0 && $j==0) ? "1": "0";
					$step['MP'][$i]['MateriRead'][$j] = ""; // datetime last time akses materi
				}

				$step['MP'][$i]['date_access_start']        = ""; // datetime akses materi pertama
                $step['MP'][$i]['LearningPoint']            = [];
                $step['MP'][$i]['LearningPoint']['status']  = "0";
                $step['MP'][$i]['LearningPoint']['tanggal'] = "";
                $step['MP'][$i]['LearningPoint']['isi']     = "-";
                $step['MP'][$i]['date_access_end']          = ""; // datetime akses terakhir

				$step['MP'][$i]['EvaStatus']    = "0"; // 0,1,2
				$step['MP'][$i]['EvaDate']      = "";
				$step['MP'][$i]['EvaScore']     = ""; // score-count-right-false
				
				$step['MP'][$i]['FbStatus']     = "0"; // 0,1,2
				$step['MP'][$i]['FbDate']       = "";
				$step['MP'][$i]['FbDesc']       = ""; // || separated
			}
			
			$step['CT'] = array();
			$step['CT']['ctStatus'] = "0";
			$step['CT']['ctDate'] = "";
			$step['CT']['ctScore'] = "";
			$step['RESULT'] = "";
			$step['CERTIFICATE'] = "";
			$step['UPDATED'] = "0";

			$recData['crId'] = $dataCr['cr_id'];
			$recData['memberId'] = $dataCr['member_id'];
			$recData['crmStep'] = json_encode($step);
			$recData['crmId'] = $dataCr['crm_id'];
			$recData['crmFb'] = $dataCr['crm_fb'];
			$this->classroom_model->update_classroom_member($recData);
		}else{
			if(!isset($step['PL'])){
				$step['PL'] = array();
				$step['PL']['plStatus'] = "0";
			}elseif(!is_array($step['PL'])){
				$step['PL'] = array();
				$step['PL']['plStatus'] = "0";
			}

			if(!isset($step['UPDATED']) || (isset($step['UPDATED']) && $step['UPDATED']=="0")){
				if(!isset($step['PL'])){
					$step['PL'] = array();
					$step['PL']['plStatus'] = "0";
				}
				
				if(!isset($step['PT'])){
					$step['PT'] = array();
					$step['PT']['ptStatus'] = "0";
				}
				if(!isset($step['RP'])) $step['RP'] = "0";
				if(!isset($step['MP'])) $step['MP'] = array();
				for($i=0;$i<@count($dataMp['Module']);$i++){
					//$step['MP'][$i]['Materi'] = array();
					for($j=0;$j<@count($dataMp['Module'][$i]['Materi']);$j++){
						if(!isset($step['MP'][$i]['Materi'][$j])){
							$step['MP'][$i]['Materi'][$j] = ($i==0 && $j==0) ? "1": "0";
						}
					}
					//p($step);
					if(!is_array($step['MP']) || !isset($step['MP'][$i]['EvaStatus'])){ 
						$step['MP'] = array();
						$step['MP'][$i]['EvaStatus'] = "0"; // 0,1,2
						$step['MP'][$i]['EvaDate'] = "";
						$step['MP'][$i]['EvaScore'] = ""; // score-count-right-false
					}
											
					if(isset($step['FB'][$i]['FbStatus']) && !isset($step['MP'][$i]['FbStatus'])){ 
						$step['MP'][$i]['FbStatus'] = $step['FB'][$i]['FbStatus']; // 0,1,2
					}else{
						$step['MP'][$i]['FbStatus'] = "0"; // 0,1,2
					}
					
					if(isset($step['FB'][$i]['FbDate']) && !isset($step['MP'][$i]['FbDate'])){ 
						$step['MP'][$i]['FbDate'] = $step['FB'][$i]['FbDate'];
					}else{
						$step['MP'][$i]['FbDate'] = "";
					}
					
					if(isset($step['FB'][$i]['FbDesc']) && !isset($step['MP'][$i]['FbDesc'])){ 
						$step['MP'][$i]['FbDesc'] = $step['FB'][$i]['FbDesc']; // || separated
					}else{
						$step['MP'][$i]['FbDesc'] = ""; // || separated
					}
				}
				
				if(isset($step['FB'])) unset($step['FB']);
				
				if(!isset($step['CT']) || is_array($step['CT'])===false) $step['CT'] = array();					
				if(!isset($step['CT']['ctStatus'])) $step['CT']['ctStatus'] = "0";
				$countModule = (@count($dataMp['Module'])==0) ? 1 : @count($dataMp['Module']);
				if(isset($step['MP'][$countModule-1]['FbStatus']) && $step['MP'][$countModule-1]['FbStatus']=="2"){
						$step['CT']['ctStatus'] = "1";
				}
				if(!isset($step['CT']['ctDate'])) $step['CT']['ctDate'] = "";
				if(!isset($step['CT']['ctScore'])) $step['CT']['ctScore'] = "";
				
				if(isset($step['RESULT']) && is_array($step['RESULT'])) unset($step['RESULT']);
				if(!isset($step['RESULT'])) $step['RESULT'] = "";
				if(!isset($step['CERTIFICATE'])) $step['CERTIFICATE'] = "";
				$step['UPDATED'] = "1";
				
				$recData['crId'] = $dataCr['cr_id'];
				$recData['memberId'] = $dataCr['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $dataCr['crm_id'];
				$recData['crmFb'] = $dataCr['crm_fb'];
				$this->classroom_model->update_classroom_member($recData);
			}
		}

		return $step;
	}

	public function info(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$this->page = 'class_room/info';
		$this->data['back_url'] = base_url('learning/class_room/my_classroom');

		$this->generate_layout();
	}

	public function home(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		// VALIDATION //
        $step = $this->data['dataStep']; // data step user
        $module = $this->data['dataMp']['Module']; // data module classroom

        $validPreLearning = true;
        if ($this->data['data']['cr_has_prelearning']){
            if ($step['PL']['plStatus']!="2"){
                $validPreLearning = false;
            }
        }

        $validPretest = $validPreLearning;
        if ($this->data['data']['cr_has_pretest']){
            $arrScorePt = explode("-",$step['PT']['ptScore']); // dapatkan array pretest score
            if ($step['PT']['ptStatus']!="2" || $arrScorePt[0]=="D"){ // jika PT tdk selesai atau score D
                $validPretest = false;
            }
        }

        // module validation
        $validModule = $validPretest;
        foreach ($step['MP'] as $i => $mp){
            $materiValid = $validPreLearning;
            if (end($mp['Materi']) != "2"){
                $materiValid = false;
            }

            $learningPointOrEvaluasiValid = $materiValid; // set default penanda eva / learning point selesai
            if ($this->data['data']['cr_has_learning_point']){
                if (isset($module[$i]['LearningPoint']) && $module[$i]['LearningPoint']['Status'] == 'active'){
                    if ($this->data['dataStep']['MP'][$i]['LearningPoint']['status']!="2"){ // cek apakah learning point valid (ada dan selesai)
                        $learningPointOrEvaluasiValid = false;
                    }
                }
            } else {
                if (isset($module[$i]['Evaluasi']) && $module[$i]['Evaluasi']['Status'] == 'active'){
                    if ($this->data['dataStep']['MP'][$i]['EvaStatus']!="2"){ // cek apakah evaluasi valid (ada dan selesai)
                        $learningPointOrEvaluasiValid = false;
                    }
                }
            }

            $feedbackValid  = $learningPointOrEvaluasiValid;
            if ($module[$i]['Feedback']['Status']=='active'){
                if ($this->data['dataStep']['MP'][$i]['FbStatus']!="2"){
                    $feedbackValid = false;
                }
            }

            $validModule = $feedbackValid;
            if (!$validModule){
                break;
            }
        }

        $validCompTest = $validModule;
        if ($this->data['data']['cr_has_kompetensi_test']){
//            $validDate = (strtotime($this->data['dataCt']['ctStart'])<=strtotime(date('Ymd')) && strtotime($this->data['dataCt']['ctEnd'])>=strtotime(date('Ymd')));
            $validDate = true; // set true (tanpa cek tanggal)
            if ($step['CT']['ctStatus'] != "2" || !$validDate){
                $validCompTest = false;
            }
        } 

        $validKnowledgeManagement = $validCompTest;
        if ($this->data['data']['cr_has_knowledge_management']){
            if (!$this->data['data']['content_id'] || $this->data['data']['content_id'] == "0"){
                $validKnowledgeManagement = false;
            }
        }

		$uid=$_SESSION['member_id'];
		$classroom=$_GET['cr_id'];

		$datapa=$this->pa->getpa_parameter(array("p.cr_id"=>$classroom,"p.member_id"=>$uid));
		$pa_status=count((Array)$datapa)>0?$datapa[0]->pa_status:"";
		$this->data['pa_status']=$pa_status;


		/* cek apakah ada evaluasi NPS*/
		// NPS Feedback
		// auth : KDW
		// date : 24012024
		$filter=array("cr_id"=>$classroom,"status"=>"1");
		$adanps=$this->ce->get_evaluasibyFilter($filter);
		$this->data['nps']=$adanps;

		/* End Cek apakah ada Evaluasi NPS*/

        $validity = compact( 'validPreLearning', 'validPretest', 'validModule', 'validCompTest', 'validKnowledgeManagement');
        $this->data['validity'] = $validity;

		$this->page = 'class_room/home';
		$this->customcss = array('fontawesome');
		$this->data['back_url'] = base_url('learning/class_room/my_classroom');

		$this->generate_layout();
	}

	/* Prelearning Area */
	public function prelearning(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$this->page = 'class_room/prelearning';

		$step = $this->data['dataStep'];

		if ($step['PL']['plStatus']!="2"){
            $step['PL']['plStatus']  = "2";
            $step['PL']['plDate'] = date('Y-m-d H:i:s');
        }

		$recData['crmId'] = $this->data['data']['crm_id'];
		$recData['crId'] = $this->data['data']['cr_id'];
		$recData['memberId'] = $this->data['data']['member_id'];
		$recData['crmStep'] = json_encode($step);
		$recData['crmFb'] = $this->data['data']['crm_fb'];
		$this->classroom_model->update_classroom_member($recData);

		$this->customcss = array('fontawesome');

		$this->generate_layout();
	}

	public function materi_doc(){
		$doc = base64_decode($this->input->get('doc'));
		$cr_id = $this->input->get('cr_id');

		$this->page = 'class_room/doc';
		$this->data['doc'] = $doc;

		$this->generate_layout();
	}
	/* # Prelearning Area */

	/* Pretest Area */
	public function pretest(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$this->page = 'class_room/pretest';

		$reTest = (isset($_GET['reTest']) && $_GET['reTest']=="1") ? 1 : 0;

		$this->data['reTest'] = $reTest;
		
		$arrSoal = "'".@$this->data['dataPt']['Question']."'";
		$arrSoal = str_replace(",","','",$arrSoal);
		$dataSoalPt = $this->classroom_model->get_soal($arrSoal);

        if(@$this->data['dataPt']['Random']=="acak"){
            shuffle($dataSoalPt);
        }

        $soal = array();
        foreach ($dataSoalPt as $ds){
            $ans = [
                $ds['crs_answer1'],
                $ds['crs_answer2'],
                $ds['crs_answer3'],
                $ds['crs_right']
            ];
            shuffle($ans);
            $soal[$ds['crs_id']] = [
                'crs_id'    => $ds['crs_id'],
                'que'       => $ds['crs_question'],
                'ans'       => $ans,
                'right'     => $ds['crs_right']
            ];
        }
		
		$jumlSoal = count($dataSoalPt);
		
        if($this->input->post('sendAnswer')){
            $ptBenar = array();
            $ptSalah = array();
			$ansRight = 0;
			$ansWrong = 0;
			foreach($this->input->post('choice') as $key=>$val){
				if($soal[$key]['right'] == $val){
					$ansRight = $ansRight + 1;
					$ptBenar[] = $soal[$key]['crs_id'];
				}else{
					$ansWrong = $ansWrong + 1;
					$ptSalah[] = $soal[$key]['crs_id'];
				}
			}
			
			$nilai_test = ($jumlSoal==0)? 0 : ($ansRight/$jumlSoal)*100;
			$nilai_test = number_format($nilai_test,3);
			
			if($ansRight >= $this->data['dataPt']['ReqPassed']){
				$step = $this->data['dataStep'];
				$step['PT']['ptDate'] = date('Y-m-d H:i:s');
				$step['PT']['ptScore'] = "A-".$jumlSoal."-".$ansRight."-".$ansWrong;
				$step['PT']['ptStatus'] = "2";
				$step['PL']['plStatus'] = "2";

				$step['ptBenar'] = $ptBenar;
				$step['ptSalah'] = $ptSalah;

				$recData['crId'] = $this->data['data']['cr_id'];
				$recData['memberId'] = $this->data['data']['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $this->data['data']['crm_id'];
				$recData['crmFb'] = $this->data['data']['crm_fb'];
				$recData['nilai_pre_test'] = $nilai_test;
				$this->classroom_model->update_classroom_member($recData,"pre");
			}
			else{
				$step = $this->data['dataStep'];

				$step['PT']['ptStatus'] = "2";
				$step['PT']['ptDate'] = date('Y-m-d H:i:s');
				$step['PT']['ptScore'] = "D-".$jumlSoal."-".$ansRight."-".$ansWrong;

                $step['ptBenar'] = $ptBenar;
                $step['ptSalah'] = $ptSalah;

				$recData['crId'] = $this->data['data']['cr_id'];
                $recData['memberId'] = $this->data['data']['member_id'];
                $recData['crmStep'] = json_encode($step);
                $recData['crmId'] = $this->data['data']['crm_id'];
                $recData['crmFb'] = $this->data['data']['crm_fb'];
				$recData['nilai_pre_test'] = $nilai_test;
				$this->classroom_model->update_classroom_member($recData,"pre");
			}
			redirect(base_url('learning/class_room/pretest?cr_id='.$crId));
		}

		$this->data['soal'] = $soal;
		
		$this->customcss = array('fontawesome');
		$this->customjs = array('pretest');

		$this->generate_layout();
	}
	/* # Pretest Area */

	/* Training Modules */
	public function module(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		if($this->data['dataStep']['RP'] == '0'){
			redirect(base_url('learning/class_room/rencana_pembelajaran?cr_id='.$crId));
		}else{
		    $module = $this->data['dataMp']['Module'];
		    $moduleCount = count($module);
            for($i=0;$i<$moduleCount;$i++){
                $materiValid = true; // set default penanda materi selesai
                $materiCount = count($module[$i]['Materi']);
                for($j=0;$j<$materiCount;$j++){
                    if($j>0 && !isset($this->data['dataStep']['MP'][$i]['Materi'][$j])){
                        $step = $this->data['dataStep'];
						$step['MP'][$i]['Materi'][$j] = (isset($step['MP'][$i]['Materi'][$j-1]) && $step['MP'][$i]['Materi'][$j-1]=="2") ? "1" : "0";
						$recData['crmId'] = $this->data['data']['crm_id'];
						$recData['crId'] = $this->data['data']['cr_id'];
						$recData['memberId'] = $this->data['data']['member_id'];
						$recData['crmStep'] = json_encode($step);
						$recData['crmFb'] = $this->data['data']['crm_fb'];
						$this->classroom_model->update_classroom_member($recData);
						$this->data['dataStep'] = $step;
                    }
                    if (!isset($this->data['dataStep']['MP'][$i]['Materi'][$j]) || $this->data['dataStep']['MP'][$i]['Materi'][$j] != "2"){ // cek apakah ada materi yang belum selesai
                        $materiValid = false;
                    }
                }

				$learningPointOrEvaluasiValid = $materiValid; // set default penanda eva / learning point selesai
				if ($this->data['data']['cr_has_learning_point']){
				    if (isset($module[$i]['LearningPoint']) && $module[$i]['LearningPoint']['Status'] == 'active'){
				        if ($this->data['dataStep']['MP'][$i]['LearningPoint']['status']!="2"){ // cek apakah learning point valid (ada dan selesai)
				            $learningPointOrEvaluasiValid = false;
                        }
                    }
                } else {
                    if (isset($module[$i]['Evaluasi']) && $module[$i]['Evaluasi']['Status'] == 'active'){
                        if ($this->data['dataStep']['MP'][$i]['EvaStatus']!="2"){ // cek apakah evaluasi valid (ada dan selesai)
                            $learningPointOrEvaluasiValid = false;
                        }
                    }
                }

                $feedbackValid  = $learningPointOrEvaluasiValid;
				if ($module[$i]['Feedback']['Status']=='active'){
				    if ($this->data['dataStep']['MP'][$i]['FbStatus']!="2"){
				        $feedbackValid = false;
                    }
                }

                if (strtotime($module[$i]['ModuleStart'])<=strtotime(date('Ymd')) && strtotime($module[$i]['ModuleEnd'])>=strtotime(date('Ymd'))){
				    $dateValid = true;
                } else {
				    $dateValid = false;
                }

				//validasi assignment jika ada
				$validAssignment=false;

				$filterAsg=array("classroom_id"=>$crId,"member_id"=>$this->data['data']['member_id'],"urut_modul"=>$i);
				$dataAssignment=$this->ma->get_ma($filterAsg);
				if(count((array)$dataAssignment) > 0){
					$validAssignment=$dataAssignment[0]->status_ma =="final" ||$dataAssignment[0]->status_ma =="check" ? "done":"undone";
				}else{
					$validAssignment="undone"; 
				}

				$this->data['validity'][$i] = [
                    'Materi'            => $materiValid,
                    'EvaLearningPoint'  => $learningPointOrEvaluasiValid,
                    'Feedback'          => $feedbackValid,
					/// tambahan module assignment
					'Assignment'		=> $validAssignment,
                    'ModuleActive'      => $dateValid // set default status modul
                ];

				// validasi apakah kondisinya sah untuk aktif
                if ($i==0) continue; // abaikan jika ini modul pertama (selalu aktif)
                if (!$this->data['data']['cr_modul_harus_urut']) continue; // abaikan jika pengerjaan modul tidak harus urut
                // atur status modul mengikuti status feedback (validasi final suatu modul) modul sebelumnya jika masih berlaku, selain itu false
                $this->data['validity'][$i]['ModuleActive'] = ($dateValid?$this->data['validity'][$i-1]['Feedback']:false);
			}
		}

		$this->page = 'class_room/module';
		$this->customcss = array('fontawesome');
		$this->data['back_url'] = base_url('learning/class_room/home?cr_id='.$crId);

		$this->generate_layout();
	}

	public function rencana_pembelajaran(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		if($this->input->post('doAgree')){
			$step = $this->data['dataStep'];
			$step['RP'] = "1";
			
			$recData['crmId'] = $this->data['data']['crm_id'];
			$recData['crId'] = $this->data['data']['cr_id'];
			$recData['memberId'] = $this->data['data']['member_id'];
			$recData['crmStep'] = json_encode($step);	
			$recData['crmFb'] = $this->data['data']['crm_fb'];	
			$this->classroom_model->update_classroom_member($recData);

			redirect(base_url('learning/class_room/module?cr_id='.$crId));
			exit;
		}

		$this->page = 'class_room/rencana_pembelajaran';

		$this->generate_layout();
	}

	public function materi1(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$module = $this->input->get('module');
		$materi = $this->input->get('materi');

		$step = $this->data['dataStep'];
		$step['MP'][$module]['Materi'][$materi] = "2";

		$now = date('Y-m-d H:i:s');
		if (!$step['MP'][$module]['date_access_start']){ // jika belum ada berarti pertama kali, maka catat
            $step['MP'][$module]['date_access_start'] = $now;
        }
        $step['MP'][$module]['MateriRead'][$materi] = $now;

		if(isset($step['MP'][$module]['Materi'][$materi+1]) && $step['MP'][$module]['Materi'][$materi+1]=="0"){
			$step['MP'][$module]['Materi'][$materi+1] ="1";
		}
		else{
            if (isset($step['MP'][$module]['Materi'][$materi+1]) && $step['MP'][$module]['Materi'][$materi+1] != "2"){
                $step['MP'][$module]['Materi'][$materi+1] ="1";
            }

            $dataModule = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['cr_module']);
            $dataModule = json_decode($dataModule, true);

            $learningPoint_evaluasi = true;
            // set learning point
            if ($dataModule['Module'][$module]['LearningPoint']['Status'] == 'active'){
                if(!isset($step['MP'][$module]['LearningPoint']['status']) || $step['MP'][$module]['LearningPoint']['status']=="0"){
                    $step['MP'][$module]['LearningPoint']['status'] = "1";
                    $learningPoint_evaluasi = false;
                }
            } else {
                // set evaluasi
                if ($dataModule['Module'][$module]['Evaluasi']['Status'] == 'active'){
                    if(!isset($step['MP'][$module]['EvaStatus']) || $step['MP'][$module]['EvaStatus']=="0"){
                        $step['MP'][$module]['EvaStatus'] = "1";
                        $learningPoint_evaluasi = false;
                    }
                }
            }

            if ($learningPoint_evaluasi && $dataModule['Module'][$module]['Feedback']['Status'] == 'active'){
                if($step['MP'][$module]['FbStatus']=="0"){
                    $step['MP'][$module]['FbStatus'] = "1";
                }
            }
		}

		$recData['crId'] = $this->data['data']['cr_id'];
		$recData['memberId'] = $memberId;
		$recData['crmStep'] = json_encode($step);
		$recData['crmId'] = $this->data['data']['crm_id'];
		$recData['crmFb'] = $this->data['data']['crm_fb'];

		$this->classroom_model->update_classroom_member($recData);

		$this->data['module'] = $module;
		$this->data['materi'] = $materi;

		$this->page = 'class_room/materi1';
		$this->customcss = array('fontawesome');

		$this->generate_layout();
	}

	public function kuis1(){
		$this->page = 'class_room/dev';
		$this->generate_layout();
	}

	public function evaluasi(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$module = $this->input->get('module');
		
		$arrSoal = "'".$this->data['dataMp']['Module'][$module]['Evaluasi']['Question']."'";
		$arrSoal = str_replace(",","','",$arrSoal);
		$dataSoalMp = $this->classroom_model->get_soal($arrSoal);

        if($this->data['dataMp']['Module'][$module]['Evaluasi']['Random']=="acak"){
            shuffle($dataSoalMp);
        }

        $soal = array();
        foreach ($dataSoalMp as $ds){
            $ans = [
                $ds['crs_answer1'],
                $ds['crs_answer2'],
                $ds['crs_answer3'],
                $ds['crs_right']
            ];
            shuffle($ans);
            $soal[$ds['crs_id']] = [
                'crs_id'    => $ds['crs_id'],
                'que'       => $ds['crs_question'],
                'ans'       => $ans,
                'right'     => $ds['crs_right']
            ];
        }


        if (isset($this->data['dataStep']['evaAttempt']) && isset($this->data['dataStep']['evaAttempt'][$module])){
            $attempt = $this->data['dataStep']['evaAttempt'][$module];
        } else {
            $attempt = $this->data['dataMp']['Module'][$module]['Evaluasi']['Attemp']?:-1;
        }

        $this->data['dataMp']['Module'][$module]['Evaluasi']['Attemp'] = $attempt;

		if ($this->input->post('sendAnswerEvaluasi')) {
		    $evaBenar = array();
		    $evaSalah = array();
			$ansRight = 0;
			$ansWrong = 0;
			$attempt = $attempt>0?$attempt-1:$attempt;
			foreach($this->input->post('choice') as $key => $val){
				if($soal[$key]['right'] == $val){
					$ansRight = $ansRight + 1;
                    $evaBenar[] = $soal[$key]['crs_id'];
				}else{
					$ansWrong = $ansWrong + 1;
                    $evaSalah[] = $soal[$key]['crs_id'];
				}
			}

            $step = $this->data['dataStep'];
            if($ansRight >= $this->data['dataMp']['Module'][$module]['Evaluasi']['ReqPassed']){
				$step['MP'][$module]['EvaStatus'] = "2";
				$step['MP'][$module]['EvaDate'] = date('Y-m-d H:i:s');
				$step['MP'][$module]['EvaScore'] = "A-".count($dataSoalMp)."-".$ansRight."-".$ansWrong;
				$step['MP'][$module]['FbStatus'] = "1";

                $step['evaBenar_modul'.$module] = $evaBenar;
                $step['evaSalah_modul'.$module] = $evaSalah;
                $step['evaAttempt'][$module] = $attempt;

				$recData['crId'] = $this->data['data']['cr_id'];
				$recData['memberId'] = $this->data['data']['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $this->data['data']['crm_id'];
				$recData['crmFb'] = $this->data['data']['crm_fb'];

				$this->classroom_model->update_classroom_member($recData);
			}else{
				$step['MP'][$module]['EvaStatus'] = "2";
				$step['MP'][$module]['EvaDate'] = date('Y-m-d H:i:s');
				$step['MP'][$module]['EvaScore'] = "D-".count($dataSoalMp)."-".$ansRight."-".$ansWrong;
				$step['MP'][$module]['FbStatus'] = "1";

                $step['evaBenar_modul'.$module] = $evaBenar;
                $step['evaSalah_modul'.$module] = $evaSalah;
                $step['evaAttempt'][$module] = $attempt;

				$recData['crId'] = $this->data['data']['cr_id'];
				$recData['memberId'] = $this->data['data']['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $this->data['data']['crm_id'];
				$recData['crmFb'] = $this->data['data']['crm_fb'];

				$this->classroom_model->update_classroom_member($recData);
			}
			redirect(base_url('learning/class_room/evaluasi?cr_id='.$crId.'&module='.$module));
		}

		$this->data['soal'] = $soal;
		$this->data['module'] = $module;
		$this->data['ulang'] = $this->input->get('ulang');
		$this->data['attempt'] = $attempt;

		$this->page = 'class_room/evaluasi';

		$this->customcss = array('fontawesome');
		$this->customjs = array('evaluasi');

		$this->generate_layout();
	}

	function learning_point(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');

        $this->set_up_common_variable($crId, $memberId);

        $module = $this->input->get('module');

        if($this->input->post('submitLearningPoint')){
            $step = $this->data['dataStep'];
            $stepFb = array();
            $stepFb[$module] = array();
            $step['MP'][$module]['LearningPoint']['status'] = "2";
            $step['MP'][$module]['LearningPoint']['tanggal'] = date('Y-m-d H:i:s');
            $step['MP'][$module]['LearningPoint']['isi'] = "";

            // set evaluasi status
            $step['MP'][$module]['EvaStatus'] = "2";
            // set feedback status
            $step['MP'][$module]['FbStatus'] = "1";

            $learningPoint = $this->input->post('learning_point');
            if ($learningPoint){
                $step['MP'][$module]['LearningPoint']['isi'] = $learningPoint;
            }

            $recData['crmId'] = $this->data['data']['crm_id'];
            $recData['crId'] = $this->data['data']['cr_id'];
            $recData['memberId'] = $this->data['data']['member_id'];
            $recData['crmStep'] = json_encode($step);

            $this->classroom_model->update_classroom_member($recData);

            redirect(base_url('learning/class_room/learning_point?cr_id='.$crId.'&module='.$module));
            exit;
        }

        $this->data['module'] = $module;
        $this->page = 'class_room/learning_point'; // feedback-module on old API
        $this->customcss = array('fontawesome');

        $this->generate_layout();
    }

	public function feedback_module(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		$module = $this->input->get('module');

		$this->data['fbType'] = $this->data['dataMp']['Module'][$module]['Feedback']['Type'];
		$this->data['fbQuestion'] = $this->data['dataMp']['Module'][$module]['Feedback']['Question'];
		
		// re-sort data survey (in case ada pertanyaan yg dihapus di tengah2
		$i = 0;
		$arrT1 = array();
		$arrT2 = array();
		foreach($this->data['fbQuestion'] as $keyT => $valT) {
			$arrT1[$i] = $this->data['fbQuestion'][$keyT];
			$arrT2[$i] = $this->data['fbType'][$keyT];
			$i++;
		}
		$this->data['fbQuestion'] = $arrT1;
		$this->data['fbType'] = $arrT2;
		
		if($this->input->post('submitFeedbackModule')){
			$step = $this->data['dataStep'];
			$stepFb = array();
			$stepFb[$module] = array();
			$step['MP'][$module]['FbStatus'] = "2";
			$step['MP'][$module]['FbDate'] = date('Y-m-d H:i:s');
			$step['MP'][$module]['FbDesc'] = "";

			$fb = $this->input->post('fb');
			for($i=0;$i<count($fb);$i++){
				if($i>0){
					$step['MP'][$module]['FbDesc'] .= "||";
				}
				$step['MP'][$module]['FbDesc'] .= nl2br($fb[$i]);
			}
			
			if($module==count($this->data['dataMp']['Module'])-1){
				$step['CT'] = array();
				$step['CT']['ctStatus'] = "1";
				$step['CT']['ctDate'] = "";
				$step['CT']['ctScore'] = "";
			}
			
			// cek apakah materi setelahnya sudah? (misal module boleh tidak urut), jika belum 2 maka ubah ke 1
            if (isset($step['MP'][$module+1]) && $step['MP'][$module+1]['Materi'][0] != "2"){
                $step['MP'][$module+1]['Materi'][0]="1";
            }
			
			$recData['crmId'] = $this->data['data']['crm_id'];
			$recData['crId'] = $this->data['data']['cr_id'];
			$recData['memberId'] = $this->data['data']['member_id'];
			$recData['crmFb'] = $this->data['data']['crm_fb'];
			$recData['crmStep'] = json_encode($step);

			$this->classroom_model->update_classroom_member($recData);
			
			redirect(base_url('learning/class_room/feedback_module?cr_id='.$crId.'&module='.$module));
			exit;
		}

		$this->data['module'] = $module;
		$this->page = 'class_room/feedback_module'; // feedback-module on old API
		$this->customcss = array('fontawesome');

		$this->generate_layout();
	}

	/* # Training Modules */

	/* Competency */
	public function competency(){
		
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		// cek jika tidak ada kompetensi tes maka redirect ke home
		if (!$this->data['data']['cr_has_kompetensi_test']) redirect(base_url('learning/class_room/home?cr_id='.$crId));

		if($this->data['data']['cr_has_knowledge_management'] && $this->data['data']['content_id']=='') redirect(base_url('learning/class_room/knowledge_management_add?cr_id='.$crId));

		$dataQue = explode(",",$this->data['dataCt']['Question']);
		
		$arrSoal = "'".$this->data['dataCt']['Question']."'";
		$arrSoal = str_replace(",","','",$arrSoal);
		$dataSoalCt = $this->classroom_model->get_soal($arrSoal);

        if(@$this->data['dataCt']['Random']=="acak"){
            shuffle($dataSoalCt);
        }

        $soal = array();
        foreach ($dataSoalCt as $ds){
            $ans = [
                $ds['crs_answer1'],
                $ds['crs_answer2'],
                $ds['crs_answer3'],
                $ds['crs_right']
            ];
            shuffle($ans);
            $soal[$ds['crs_id']] = [
                'crs_id'    => $ds['crs_id'],
                'que'       => $ds['crs_question'],
                'ans'       => $ans,
                'right'     => $ds['crs_right']
            ];
        }

		if($this->input->post('sendAnswerCompetency')){
			$step = $this->data['dataStep'];
			$step['CT'] = array();
			$step['CT']['ctStatus'] = "2";
			$step['CT']['ctDate'] = date('Y-m-d H:i:s');

			$ctBenar = array();
			$ctSalah = array();
			$ansRight = 0;
			$ansWrong = 0;

			foreach($this->input->post('choice') as $key => $val){
				if($soal[$key]['right'] == $val){
					$ansRight = $ansRight + 1;
					$ctBenar[] = $soal[$key]['crs_id'];
                }else{
                    $ansWrong = $ansWrong + 1;
                    $ctSalah[] = $soal[$key]['crs_id'];
                }
			}
			
			$jumlSoal = count($soal);
			$ctResult = ($jumlSoal==0)? 0 : (($ansRight/$jumlSoal) * 100);
			
			$nilai_test = number_format($ctResult,3);
			
			if($ctResult >= $this->data['dataCt']['GradeA']){ $grade = "A"; }
			elseif($ctResult >= $this->data['dataCt']['GradeB']){ $grade = "B"; }
			elseif($ctResult >= $this->data['dataCt']['GradeC']){ $grade = "C"; }
			else{ $grade = "D"; }
			
			// begin of poin
            $this->classroom_model->recData['crId'] = $crId;
            $classroom = $this->classroom_model->select_classroom('byId');
            $poin_setting = $this->member_model->select_member_poin_setting();
            if ($poin_setting){ // cek apakah ada setting poin
                $recData['memberId']    = $memberId;
                $recData['mpSection']   = 'CR';
                $recData['mpContentId'] = '';
                $recData['mpName']      = $classroom['cr_name'].' (Grade '.$grade.')';
                $recData['mpPoin']      = $poin_setting[0]['mps_cr_grade_'.strtolower($grade)];
                $this->member_model->insert_member_poin($recData);
                $recData['interval'] = '';
                $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
                $this->member_model->recData['memberId'] = $memberId;
                $this->member_model->update_member('byField','','member_poin',$total_poin);
            }
            // end of poin

			$step['CT']['ctScore'] = $grade."-".count($dataSoalCt)."-".$ansRight."-".$ansWrong;
			$step['RESULT'] = $grade;

            $step['ctBenar'] = $ctBenar;
            $step['ctSalah'] = $ctSalah;

            if ($poin_setting){ // cek apakah ada poin setting
                $this->session->set_userdata('show_reward', true);
                $this->session->set_userdata('reward', ['poin' => $poin_setting[0]['mps_cr_grade_'.strtolower($grade)], 'cause' => 'Finish Classroom']);
            }

			if($grade != "D"){ // PASSED
				$this->data['data']['cr_certificate'] = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['cr_certificate']);
				$dataSert = json_decode($this->data['data']['cr_certificate'],true);

				if ($this->data['data']['cr_has_certificate'] === '1'){
                    if(!isset($dataSert['serType'])){ $dataSert['serType']="";}
                    if(!isset($dataSert['noStart'])){ $dataSert['noStart']="";}
                    if(!isset($dataSert['noEnd'])){ $dataSert['noEnd']="";}
                    if(!isset($dataSert['noUsed'])){ $dataSert['noUsed']="";}
                    if(!isset($dataSert['serCode'])){ $dataSert['serCode']="";}

                    $number = (intval($dataSert['noUsed']) > 0) ? $dataSert['noUsed'] + 1 : $dataSert['noStart'];

                    // jika cr_show_nilai = 1 maka tampilkan grade
                    $grade = ($this->data['data']['cr_show_nilai']?$grade:'');
                    $targetPathPdf = $this->generate_sertifikat($dataSert, $grade, $number);

                    $step['CERTIFICATE'] = $targetPathPdf;
                }
				
				$recData = array();
				$recData['crId'] = $this->data['data']['cr_id'];
				$recData['memberId'] = $this->data['data']['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $this->data['data']['crm_id'];
				$recData['crmFb'] = $this->data['data']['crm_fb'];
				$recData['nilai_post_test'] = $nilai_test;
				$this->classroom_model->update_classroom_member($recData,"post");
				
				// sertifikat
				$dataSert['noUsed'] = $number;
				$dataSertifikat = json_encode($dataSert); 
				$recData = array();
				$recData['crId'] = $this->data['data']['cr_id'];
				$this->classroom_model->update_classroom("byField",$recData,"cr_certificate",$dataSertifikat);
				
				redirect(base_url('learning/class_room/feedback?cr_id='.$crId));
				exit;
			}
			else{
				$recData = array();
				$recData['crId'] = $this->data['data']['cr_id'];
				$recData['memberId'] = $this->data['data']['member_id'];
				$recData['crmStep'] = json_encode($step);
				$recData['crmId'] = $this->data['data']['crm_id'];
				$recData['crmFb'] = $this->data['data']['crm_fb'];
				$recData['nilai_post_test'] = $nilai_test;
				$this->classroom_model->update_classroom_member($recData,"post");
				
				redirect(base_url('learning/class_room/competency?cr_id='.$crId));
				exit;
			}
		}

		$this->data['soal'] = $soal;
		
		$this->page = 'class_room/competency';
		$this->customcss = array('fontawesome');
		
		if($this->session->userdata('show_reward') == true){
			$this->data['show_reward'] = true;
			$this->customjs = array('competency', 'reward');
			$this->data['reward'] = $this->session->userdata('reward');
			$this->session->unset_userdata('show_reward');
			$this->session->unset_userdata('reward');
		}else{
			$this->customjs = array('competency');
		}

		$this->data['back_url'] = base_url('learning/class_room/home?cr_id='.$crId);

		$this->generate_layout();
	}
	
	public function competency_prev_answer(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');
		
		// hanya bisa diakses kl ada post
		if(!$_POST) { exit; }
		
		$this->set_up_common_variable($crId, $memberId);
		
		if($this->data['dataCt']['Attemp']=="1") {
			$btn_kesempatan_test = 'Submit';
			$label_kesempatan_test = '1 kali';
		} else if($this->data['dataCt']['Attemp']=="N") {
			$btn_kesempatan_test = 'Preview Nilai';
			$label_kesempatan_test = 'Tanpa Batas';
		}
		
		$arrSoal = "'".$this->data['dataCt']['Question']."'";
		
		$arrSoal = str_replace(",","','",$arrSoal);
		$dataSoalCt = $this->classroom_model->get_soal($arrSoal);

        $jumlSoal = 0;
		$soal = array();
        foreach ($dataSoalCt as $ds){
			$jumlSoal++;
            $soal[$ds['crs_id']] = [
                'crs_id'    => $ds['crs_id'],
                'right'     => $ds['crs_right']
            ];
        }
		
		// $ctBenar = array();
		// $ctSalah = array();
		$ansRight = 0;
		$ansWrong = 0;

		foreach($this->input->post('choice') as $key => $val){
			if($soal[$key]['right'] == $val){
				$ansRight = $ansRight + 1;
				// $ctBenar[] = $soal[$key]['crs_id'];
			}else{
				$ansWrong = $ansWrong + 1;
				// $ctSalah[] = $soal[$key]['crs_id'];
			}
		}
		
		$ui =
			'<div class="text-left">Data competency test Saudara:</div>
			 <table class="table table-sm table-bordered">
				<tr>
					<td>Jumlah Soal</td>
					<td>Jawaban Benar</td>
					<td>Jawaban Salah</td>
				</tr>
				<tr>
					<td>'.$jumlSoal.'</td>
					<td>'.$ansRight.'</td>
					<td>'.$ansWrong.'</td>
				</tr>
			 </table>
			 
			 <div class="text-left">
			 <b>catatan</b>:<br/>
			 <ul>
				<li>Klik tombol <b>Ulangi Ujian</b> untuk mengulangi pengerjaan ujian dari awal (hasil pengerjaan saat ini akan dihapus)</li>
				<li>Klik tombol <b>Submit</b> untuk menyimpan Jawaban Saudara (hasil pengerjaan saat ini akan disimpan final).</li>
			 </ul>
			 </div>';
		
		$arrH = array();
		$arrH['sukses'] = '1';
		$arrH['pesan'] = $ui;
		
		$html = json_encode($arrH);
		
		echo $html;
		
		exit;
	}

	public function feedback(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		if($this->input->post('submitFeedback')){
			$step = $this->data['dataStep'];
			$dataFb = array();

			$fb = $this->input->post('fb');
			for($i=0; $i < count($fb); $i++){
				array_push($dataFb, $fb[$i]);
			}
			
			$recData['crmFb'] = addslashes(json_encode($dataFb));
			$recData['crmId'] = $this->data['data']['crm_id'];
			$recData['crId'] = $this->data['data']['cr_id'];
			$recData['memberId'] = $this->data['data']['member_id'];
			$recData['crmStep'] = json_encode($step);
			$this->classroom_model->update_classroom_member($recData);

			// generate sertifikat di sini jika belum ada (case tanpa kompetensi test dan ada sertifikat)
            if (!$this->data['cr_has_kompetensi_test'] && $this->data['cr_has_certificate']){
                $this->data['data']['cr_certificate'] = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['cr_certificate']);
                $dataSert = json_decode($this->data['data']['cr_certificate'],true);
                if ($this->data['data']['cr_has_certificate'] === '1'){
                    if(!isset($dataSert['serType'])){ $dataSert['serType']="";}
                    if(!isset($dataSert['noStart'])){ $dataSert['noStart']="";}
                    if(!isset($dataSert['noEnd'])){ $dataSert['noEnd']="";}
                    if(!isset($dataSert['noUsed'])){ $dataSert['noUsed']="";}
                    if(!isset($dataSert['serCode'])){ $dataSert['serCode']="";}
                    $number = (intval($dataSert['noUsed']) > 0) ? $dataSert['noUsed'] + 1 : $dataSert['noStart'];
                    $targetPathPdf = $this->generate_sertifikat($dataSert, '', $number);
                    $step['CERTIFICATE'] = $targetPathPdf;
                }
            }

			redirect(base_url('learning/class_room/feedback?cr_id='.$crId.'&type=confirm'));
		}
		///auth : KDW
		///date : 03042023
		else{
			$this->data['data']['cr_feedback'] = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['cr_feedback']);

			$this->data['fb'] = json_decode($this->data['data']['cr_feedback'], true);
			$this->data['type'] = $this->input->get('type');
			
			// re-sort data survey (in case ada pertanyaan yg dihapus di tengah2
			$i = 0;
			$arrT1 = array();
			$arrT2 = array();
			foreach($this->data['fb']['Question'] as $keyT => $valT) {
				$arrT1[$i] = $this->data['fb']['Question'][$keyT];
				$arrT2[$i] = $this->data['fb']['Type'][$keyT];
				$i++;
			}
			$this->data['fb']['Question'] = $arrT1;
			$this->data['fb']['Type'] = $arrT2;
			
			$this->page = 'class_room/feedback';
			$this->customcss = array('fontawesome');
	
			if($this->session->userdata('show_reward') == true){
				$this->data['show_reward'] = true;
				$this->customjs = array('reward');
				$this->data['reward'] = $this->session->userdata('reward');
				$this->session->unset_userdata('show_reward');
				$this->session->unset_userdata('reward');
			}
	
			$this->generate_layout();
		}

		
	}

	private function generate_sertifikat($dataSert = array(), $grade = '', $number = ''){
	    $cert_path = SERTIFIKAT_PATH;
        if (!is_dir($cert_path)){
            mkdir($cert_path, 0755, true);
        }
		$targetPath = getcwd().'/'.$cert_path;
		$fontPath = getcwd().'/assets/fonts/';
		
		// $x = 842;
		// $y = 1190;
		$x = 792;
		$y = 612;
		$page_x = 3/4*$x;
		$page_y = 3/4*$y;

		$img1 = PATH_ASSETS.'img/sertifikat_bg_blank.png';
		$img2 = MEDIA_IMAGE_PATH.$dataSert['Logo'];
		$img3 = MEDIA_IMAGE_PATH.$dataSert['Signature'];

		// $outputImage = imagecreatetruecolor(1190, 842);
		$outputImage = imagecreatetruecolor($x, $y);
		
		// set background to white
		$blue = imagecolorallocate($outputImage, 11, 77, 138);
		imagefill($outputImage, 0, 0, $blue);
		
		$black1 = imagecolorallocate($outputImage, 77, 77, 77);
		imagefill($outputImage, 0, 0, $black1);							
		$black2 = imagecolorallocate($outputImage, 77, 77, 77);
		imagefill($outputImage, 0, 0, $black2);
		
		$white = imagecolorallocate($outputImage, 255, 255, 255);
		imagefill($outputImage, 0, 0, $white);
		
		$first = imagecreatefrompng($img1);
		$second = imagecreatefrompng($img2);
		$third = imagecreatefrompng($img3);
		
		imagecopyresized($outputImage,$first,0,0,0,0, $x, $y,$x,$y);							
		imagecopyresized($outputImage,$second,196,10,0,0,  400, 120, 400, 120);
		imagecopyresized($outputImage,$third,522,448,0,0, 200, 80, 200, 80);

		$title = $dataSert['Text1'];

		$font = $fontPath.'Trajan Pro Bold.ttf';
		$text_box = imagettfbbox(16,0,$font,$title);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/2) - ($text_width/2);
		imagettftext($outputImage, 16, 0, $x, 160, $black1, $font, $title);

		$text2 = trim($dataSert['Text2']);
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text2);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/2) - ($text_width/2);
		imagettftext($outputImage, 8, 0, $x, 220, $black1, $font, $text2);

		$text3 = trim($dataSert['Text3']);
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text3);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/2) - ($text_width/2);
		imagettftext($outputImage, 8, 0, $x, 320, $black1, $font, $text3);

		$date = $this->function_api->date_indo($this->data['data']['cr_date_start'])." to ".$this->function_api->date_indo($this->data['data']['cr_date_end']);
		$text4 = trim($dataSert['Text4'])." ".$date;
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text4);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/2) - ($text_width/2);
		imagettftext($outputImage, 8, 0, $x, 420, $black1, $font, $text4);

		$name = $this->data['data']['member_name'];
		$font = $fontPath.'Trajan Pro Bold.ttf';
		$text_box = imagettfbbox(14,0,$font,$name);
		$text_width = $text_box[2]-$text_box[0];		
		$x = (792/2) - ($text_width/2);								
		imagettftext($outputImage, 14, 0, $x, 270, $blue, $font, $name);

		$pel = $this->data['data']['cr_name'];
		$font = $fontPath.'Trajan Pro Bold.ttf';
		$text_box = imagettfbbox(14,0,$font,$pel);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/2) - ($text_width/2);																
		imagettftext($outputImage, 14, 0, $x, 370, $blue, $font, $pel);

		$font = $fontPath.'OpenSans-ExtraBold.ttf';
		imagettftext($outputImage, 52, 0, 56, 112, $blue, $font, $grade);

		$arrMonth = $this->function_api->arrMonths("id");
		// $date = date('d')." ".$arrMonth[intval(date('m'))-1]." ".date('Y');
		$crEndDay = substr($this->data['data']['cr_date_end'],8,2);
		$endMonth = substr($this->data['data']['cr_date_end'],5,2);
		$crEndMonth = $arrMonth[intval($endMonth)-1];
		$crEndYear = substr($this->data['data']['cr_date_end'],0,4);
		$date = $crEndDay." ".$crEndMonth." ".$crEndYear;

		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$date);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/4) - ($text_width/2) - 30;																
		imagettftext($outputImage, 8, 0, $x, 520, $black1, $font, $date);
		
		$text7 = "DATE";
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text7);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/4) - ($text_width/2) - 30;		
		imagettftext($outputImage, 8, 0, $x, 546, $black1, $font, $text7);

		$text5 = $dataSert['Text5'];
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text5);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/4) - ($text_width/2) + (792/2) + 30;		
		imagettftext($outputImage, 8, 0, $x, 546, $black1, $font, $text5);

		$text6 = $dataSert['Text6'];
		$font = $fontPath.'OpenSans-Semibold.ttf';
		$text_box = imagettfbbox(8,0,$font,$text6);
		$text_width = $text_box[2]-$text_box[0];						
		$x = (792/4) - ($text_width/2) + (792/2) + 30;		
		imagettftext($outputImage, 8, 0, $x, 560, $black1, $font, $text6);

		$filename = $targetPath.'sertifikat_'.$this->data['data']['cr_id'].'_'.$this->data['data']['member_id'].'_'.$number.'.png';
		imagepng($outputImage, $filename);
		imagedestroy($outputImage);	

		$targetPathPdf = $cert_path.'sertifikat_classroom_'.$this->data['data']['cr_id'].'_'.$this->data['data']['member_id'].'_'.$number.'.pdf';

		require_once APPPATH."third_party/fpdf182/fpdf.php";

		// $pdf = new FPDF("L","mm","letter");
		$pdf = new FPDF("L", "pt", array($page_x, $page_y));
		$pdf->AddPage();
		$pdf->Image($filename,0,0,0,0);
		$pdf->Output("F", getcwd().'/'.$targetPathPdf);
		// unlink($filename);

		return base_url().$targetPathPdf;
	}
	/* # Competency */

	public function knowledge_management(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->set_up_common_variable($crId, $memberId);

		if(!$this->data['data']['content_id']){
			redirect(base_url('learning/class_room/knowledge_management_add?cr_id='.$crId));
		}else{
            $this->data['cr_id'] = $crId;
            $section_id = 31;

            $content_id = $this->data['data']['content_id'];
            $this->load->model(['category_model', 'content_model', 'media_model']);

            $this->content_model->recData['contentId'] = $content_id;
            $content = $this->content_model->select_content("byId");
            if (!$content){
                show_404();
            }
            if ($content['section_id']!=$section_id){
                show_404();
            }

            $data_hits = [
                'contentId' => $content_id,
                'memberId'  => $memberId,
                'contentHitsChannel'    => 'android'
            ];
            $this->content_model->insert_content_hits($data_hits);
            $this->content_model->update_content('hits', $data_hits);

            $content['image']           = $this->media_model->get_primary_image($content['section_id'],$content['content_id']);
            $count_like                 = $this->content_model->select_content_comment("countLikeByContentId");
            $content['like_count']      = $this->function_api->get_size_number($count_like);
            $count_comment              = $this->content_model->select_content_comment("countCommentByContentId");
            $content['comment_count']   = $this->function_api->get_size_number($count_comment);
            $content['is_liked'] = $this->content_model->is_member_like($content_id, $this->session->userdata('member_id'));
            $content['is_bookmarked']   = $this->member_model->in_bookmark($memberId, $content_id);
            $this->data['content'] = $content;

            $dataContent = $this->content_model->select_content("publish","",5);
            $tmp = [];
            $info = [];
            for($i=0;$i<count($dataContent);$i++){
                $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
                if(isset($dataContent[$i])){
                    $tmp['id'] 	= $dataContent[$i]['content_id'];
                    $tmp['title'] 	= $dataContent[$i]['content_name'];
                    $primaryImage 	= $this->media_model->get_primary_image($section_id,$dataContent[$i]['content_id']);
                    $tmp['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                    $tmp['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"dd FF YYYY");
                    $tmp['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                    $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                    $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                    array_push($info,$tmp);
                }
            }
            $this->data['info'] = $info;

            $this->data['title'] = 'Knowledge Management';
			$this->page = 'class_room/knowledge_management';
			$this->menu = 'learning';
            $this->customjs = array('content','sharelink','comments');
			$this->generate_layout();
		}
	}

	public function knowledge_management_add(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$section_id = 31;

		$this->load->model(['category_model', 'content_model', 'media_model']);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
		$this->form_validation->set_rules('judul', 'Judul', 'required|trim');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');

		if ($this->form_validation->run() == FALSE){

//		    $category = $this->category_model->select_category('parent0',$section_id);
		    $category = $this->category_model->select_category('',$section_id);
            $hierarchy = $this->function_api->convertToHierarchy($category,'cat_id','cat_parent','child');
            $this->data['category'] = $hierarchy;
		    $this->data['cr_id'] = $crId;
		    $this->data['title'] = 'Knowledge Management';
		    $this->page = 'learning/class_room/knowledge_management_add';
		    $this->menu = 'classroom';
		    $this->customcss = array('select2');
		    $this->customjs = array('select2');
		    $this->generate_layout();
		}else{
		    $post = $this->input->post();

		    $group_id = 'all';

		    // Proses Bidang Member
		    $bidang = 'all';
		    // if (isset($post['bidang'])){
		    //     if (in_array('all',$post['bidang'])){
		    //         $bidang = 'all';
		    //     }else{
		    //         $bidang = implode(',',$post['bidang']);
		    //     }
		    // }

		    // Proses Level Member
		    $member_level = NULL;
		    // if (isset($post['member_level'])){
		    //     if (in_array('all',$post['member_level'])){
		    //         $member_level = 'all';
		    //     }else{
		    //         $member_level = implode(',',$post['member_level']);
		    //     }
		    // }

		    // Proses Tags
		    $tags = '';
		     if (isset($post['content_tags'])) {
                 $tags = implode(',', $post['content_tags']);
                 foreach ($post['content_tags'] as $v) {
                     $get_tags = $this->content_tags_model->get_by_name($v);
                     if ($get_tags == FALSE) {
                         $data_tags = array(
                             'section_id' => $section_id,
                             'tags_name' => $v,
                             'tags_alias' => $this->function_api->slugify($v),
                         );
                         $this->content_tags_model->insert($data_tags);
                     }
                 }
             }

		    $this->db->trans_start();
		    $crm_id = $this->classroom_model->get_crm_id($crId, $memberId);
		    $post['keterangan'] = $this->db->escape($post['keterangan']);
		    //insert
		    $recData['sectionId']		    = $section_id;
		    $recData['catId']			    = $post['kategori'];
		    $recData['userId']			    = 0;
		    $recData['memberId']		    = 0;
		    $recData['groupId']			    = $group_id;
		    $recData['mlevelId']		    = 'all';
		    $recData['contentName']		    = $post['judul'];
		    $recData['contentAlias']	    = $this->function_api->generate_alias($post['judul']);
		    $recData['contentDesc']		    = $this->input->post('keterangan');
		    $recData['contentTags']		    = $tags;
		    $recData['contentHits']		    = '0';
		    $recData['contentSource']	    = NULL;
		    $recData['contentBidang']	    = $bidang;

            $this->member_model->recData['memberId']= $this->member_id;
            $detailMember 	= $this->member_model->select_member("byId");
            $groupName = $this->group_model->get_group_name($detailMember['group_id']);
            $recData['contentAuthor']	    = $detailMember['member_name'];
            if($groupName!=""){
                $recData['contentAuthor']= $detailMember['member_name']." (".$groupName.")";
            }

            $recData['contentSeoTitle']	    = $post['judul'];
            $recData['contentSeoKeyword']   = $post['judul'];
            $recData['contentSeoDesc']	    = $post['keterangan'];
		    $recData['contentTypeID']       = null;
		    $recData['contentStatus']	    = 'draft';
		    $recData['contentCreateBy']	    = $memberId;
            $recData['contentPublishDate']  = date('Y-m-d H:i:s');
            $recData['contentSource']       = '';
		    $recData['contentNotif']	    = '0';
		    $recData['crmId']	    		= $crm_id;

		    $this->content_model->insert_content($recData);
		    $ContentId = $this->content_model->lastInsertId;

		    $this->classroom_model->update_classroom_member_content_id($crm_id, $ContentId);

		    //image 1
		    $this->media_model->dataId = $this->content_model->lastInsertId;
		    $this->media_model->sectionId = $section_id;
		    $this->media_model->mediaType = "document";
		    $this->media_model->mediaStatus = "1";
		    $this->media_model->mediaName 	= $post['judul'];
		    $this->media_model->mediaAlias 	= $this->function_api->generate_alias($this->media_model->mediaName);
		    $this->media_model->mediaSize 	= $this->function_api->get_size($_FILES['content_doc']['size']);

		    $name = $_FILES["content_doc"]["name"];

		    $arrName = explode(".",$name);
		    $ext = end($arrName);
		    $this->media_model->mediaValue 	= "knowledge_management_document_".uniqid().'.'.$ext;
		    $this->media_model->mediaDesc = "";
		    $this->media_model->mediaPrimary= "1";
		    $this->media_model->insert_media();

		    $config['upload_path']          = MEDIA_DOCUMENT_PATH;
		    $config['allowed_types']        = 'pdf';
		    $config['file_name']            = $this->media_model->mediaValue;
		    $config['overwrite']			= true;
		    $config['max_size']             = 1024; // 1MB
		    $this->load->library('upload', $config);
		    $this->upload->do_upload('content_doc');
		    $this->db->trans_complete();

		    redirect('learning/class_room/knowledge_management?cr_id='.$crId);
		}
	}

	public function report(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');
		
		$this->set_up_common_variable($crId, $memberId);
		
		if ($this->data['data']['cr_has_certificate'] == '1'){
            $step = $this->data['dataStep'];
            if (!$step['CERTIFICATE'] || !file_exists(getcwd().'/'.SERTIFIKAT_PATH.basename($step['CERTIFICATE']))){
                // generate sertifikat di sini jika belum ada
                $this->data['data']['cr_certificate'] = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['cr_certificate']);
                $dataSert = json_decode($this->data['data']['cr_certificate'],true);

                if(!isset($dataSert['serType'])){ $dataSert['serType']="";}
                if(!isset($dataSert['noStart'])){ $dataSert['noStart']="";}
                if(!isset($dataSert['noEnd'])){ $dataSert['noEnd']="";}
                if(!isset($dataSert['noUsed'])){ $dataSert['noUsed']="";}
                if(!isset($dataSert['serCode'])){ $dataSert['serCode']="";}
                $number = (intval($dataSert['noUsed']) > 0) ? $dataSert['noUsed'] + 1 : $dataSert['noStart'];
				
				$dataResult = json_decode($this->data['data']['crm_step'],true);
				$grade = $dataResult['RESULT'];
				
				// jika cr_show_nilai = 1 maka tampilkan grade
                $grade = ($this->data['data']['cr_show_nilai']?$grade:'');
                $targetPathPdf = $this->sertifikat_reset($dataSert, $grade, $number);
				$step['CERTIFICATE'] = $targetPathPdf;

                $recData = array();
                $recData['crId'] = $this->data['data']['cr_id'];
                $recData['memberId'] = $this->data['data']['member_id'];
                $recData['crmStep'] = json_encode($step);
                $recData['crmId'] = $this->data['data']['crm_id'];
                $crmFb = preg_replace("/[[:cntrl:]]/", "", $this->data['data']['crm_fb']);
                $crmFb = json_decode($crmFb);
                $recData['crmFb'] = addslashes(json_encode($crmFb));
                $this->classroom_model->update_classroom_member($recData);
                $this->set_up_common_variable($crId, $memberId);
            }
        }

		$this->page = 'class_room/report';
		$this->generate_layout();
	}
	
	public function sertifikat_reset() {
		$crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');
		
		$this->classroom_model->recData['crId'] = $crId;
		$this->classroom_model->recData['memberId'] = $memberId;
		$dataCr = $this->classroom_model->select_classroom_member("byMemberId");
		$dataCr[0]['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $dataCr[0]['crm_step']);
		$detail_step = json_decode($dataCr[0]['crm_step'],true);
		
		// reset 
		if(!empty($detail_step['CERTIFICATE'])) @unlink($detail_step['CERTIFICATE']);
		$detail_step['CERTIFICATE'] = '';
		
		$recData = array();
		$recData['crId'] = $dataCr[0]['cr_id'];
		$recData['memberId'] = $dataCr[0]['member_id'];
		$recData['crmStep'] = json_encode($detail_step);
		$recData['crmId'] = $dataCr[0]['crm_id'];
		$recData['crmFb'] = $dataCr[0]['crm_fb'];
		
		$this->classroom_model->update_classroom_member($recData);
		
		$this->session->set_flashdata('info', 'Sertifikat berhasil diperbaharui.');
		redirect(base_url('learning/class_room/report?cr_id='.$crId));
	}

	public function sertifikat_doc(){
		$doc = base64_decode($this->input->get('doc'));
		$cr_id = $this->input->get('cr_id');

		$this->page = 'class_room/sertifikat_doc';
		$this->data['doc'] = $doc;

		$this->generate_layout();
	}

	public function reset(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		$this->classroom_model->recData['crId'] = $crId;
		$this->classroom_model->recData['memberId'] = $memberId;
		$dataCr = $this->classroom_model->select_classroom("activeByMemberId");
		$dataCr['crm_fb'] = null;
		
		$this->set_up_step('', $dataCr);
		
		$sql = "UPDATE _classroom_member 
				SET nilai_pre_test='', nilai_post_test=''
				WHERE crm_id = '".$dataCr['crm_id']."' ";
		$this->db->query($sql);

		redirect(base_url('learning/class_room/home?cr_id='.$crId));
	}

	public function fixmodule(){
		// fungsi untuk mencari data yang nyangkut (karena jumlah data materi tidak sama dengan di classroom)
		$crId = $this->input->get('cr_id');
		$targetId = $this->input->get('member_id');
		if (!$crId){
			exit('Invalid ID');
		}
		$this->classroom_model->recData['crId'] = $crId;
		$classroom = $this->classroom_model->select_classroom('byId');
		if (!$classroom) exit('No classroom found');
		$crModule = json_decode($classroom['cr_module'], true);
		$classroomMembers = $this->classroom_model->select_classroom_member();
		$materiModuleCounts = [];
		foreach ($crModule['Module'] as $i => $module){
			$materiCount = count($module['Materi']);
			$materiModuleCounts[$i] = $materiCount;
		}

		echo 'Jumlah member classroom: '.count($classroomMembers).'<br><br>';
		$memberCount = 0;
		foreach ($classroomMembers as $i => $memberCr){
			$nyangkut = false;
			$dataStep = preg_replace("/[[:cntrl:]]/", "", $memberCr['crm_step']);
			$step = json_decode($dataStep, true);
			if (!$step) {
				echo "Tidak ada step untuk member $memberCr[member_id]<br>";
				continue;
			}
			$moduleCount = count($crModule['Module']);
			$dataModuleCount = count($step['MP']);
			if ($dataModuleCount != $moduleCount){
				echo "<h3 style='color: red'>#WARNING#</h3> Jumlah module di classroom $classroom[cr_id] (total: $moduleCount) tidak sama dengan di data User ID: $memberCr[member_id] (total: $dataModuleCount)<br><br>";
			}
			foreach ($step['MP'] as $j => $mp){
				if (!$mp || !$mp['Materi']) continue;
				$dataMateriCount = count($mp['Materi']);
				if (!isset($materiModuleCounts[$j])) continue;
				if ($dataMateriCount > $materiModuleCounts[$j]){
					if (!$nyangkut){
						echo '<b>No: '.($memberCount+1).'</b><br>';
						$nyangkut = true;
					}
					echo 'User ID: '.$memberCr['member_id'].' | Module: '.$j.'<br>';
					echo 'Jumlah materi module: '.$materiModuleCounts[$j].' | Jumlah data tersimpan: '.$dataMateriCount.'<br>';
					echo json_encode($mp);
					echo '<br><b>Correction:</b><br>';
					$step['MP'][$j]['Materi'] = [];
					$materiRead = [];
					for ($x=0;$x<$materiModuleCounts[$j];$x++){
						if (isset($step['MP'][$j]['MateriRead']) && $step['MP'][$j]['MateriRead'][$x]){
							$materiRead[] = $step['MP'][$j]['MateriRead'][$x];
							$step['MP'][$j]['Materi'][] = "2";
						} else {
							$step['MP'][$j]['Materi'][] = "1";
							$materiRead[] = "";
						}
					}
					$step['MP'][$j]['MateriRead'] = $materiRead;
					echo json_encode($step['MP'][$j]);
					echo '<br><br>';
				}
			}
			if ($nyangkut) {
				$memberCount++;
				echo '<b>Full crm_step:</b><br>'.json_encode($step).'<br><br>';

				// fix action start from here
				if ($memberCr['member_id'] == $targetId){
					$recData['crId'] = $crId;
					$recData['memberId'] = $memberCr['member_id'];
					$recData['crmStep'] = json_encode($step);
					$recData['crmFb'] = $memberCr['crm_fb'];
					$recData['crmId'] = $memberCr['crm_id'];
					$this->classroom_model->update_classroom_member($recData);
					echo '<b>Fixed!!</b><br><br><br>';
				} else {
					echo '<a href="'.site_url().'learning/class_room/fixmodule?cr_id='.$crId.'&member_id='.$memberCr['member_id'].'">Fix It!</a><br><br><br>';
				}
				// end of fix action
			}
		}
		echo 'Jumlah member nyangkut: '.$memberCount;
	}

	/* -- evaluasi level 3 start -- */
	
	public function evaluasi_lv3(){
		$memberId = $this->session->userdata('member_id');

		$this->page = 'class_room/evaluasi_lv3_home';

		$this->data['member_id'] = $memberId;

		$sql =
			"select 
				c.cr_id, c.cr_name, c.cr_date_start, c.cr_date_end, 
				h.tanggal_mulai, h.tanggal_selesai, 
				p.id as id_pairing, p.status_penilai, p.id_dinilai, p.progress,
				m.member_name
			 from _classroom_evaluasi_lv3_pairing p, _classroom c, _classroom_evaluasi_lv3_header h, _member m
			 where 
				p.id_penilai='".$memberId."' and h.status='1' and p.cr_id=c.cr_id and h.cr_id=c.cr_id 
				and p.id_dinilai=m.member_id
				and now() between h.tanggal_mulai and h.tanggal_selesai
			 order by m.member_name, c.cr_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
			
		$this->data['row'] = $row;
		
		$this->data['title'] = 'Evaluasi Pelatihan Level 3';
		$this->data['back_url'] = base_url('learning/class_room');
		
		$this->generate_layout();
	}
	
	public function evaluasi_lv3_detail($id_pairing){
		$memberId = $this->session->userdata('member_id');
		
		$this->page = 'class_room/evaluasi_lv3_detail';

		$this->data['member_id'] = $memberId;
		
		$strError = '';
		$jaw_pre = array();
		$jaw_post = array();
		
		$sql =
			"select 
				c.cr_id, c.cr_name, c.cr_date_start, c.cr_date_end, 
				h.deskripsi_pelatihan, h.tujuan_pelatihan, h.tanggal_mulai, h.tanggal_selesai, h.daftar_pertanyaan, h.simpan_final_enabled,
				p.id as id_pairing, p.id_penilai, p.status_penilai, p.id_dinilai, p.progress, p.jawaban
			 from _classroom_evaluasi_lv3_pairing p, _classroom c, _classroom_evaluasi_lv3_header h
			 where p.id_penilai='".$memberId."' and h.status='1' and p.cr_id=c.cr_id and h.cr_id=c.cr_id and p.id='".$id_pairing."' and now() between h.tanggal_mulai and h.tanggal_selesai ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if(count($row)<1) {
			redirect(base_url('learning/class_room/evaluasi_lv3'));
		}
		
		$time_selesai_pelatihan = strtotime($row[0]['cr_date_end']);
		$time_today = strtotime(date("Y-m-d 00:00:00"));
		
		// $btn_final_enabled = ($time_today<=$time_selesai_pelatihan)? false : true;
		$btn_final_enabled = ($row[0]['simpan_final_enabled']==false)? false : true;
		
		$json_jaw = (json_decode($row[0]['jawaban'],true));
		$jaw_pre = $json_jaw['jaw_pre'];
		$jaw_post = $json_jaw['jaw_post'];
		$saran = $json_jaw['saran'];
		
		// kelasnya ada pre/post test?
		$this->classroom_model->recData['crId'] = $row[0]['cr_id'];
		$dataCr = $this->classroom_model->select_classroom('byId');
		$adaPreTest = ($dataCr['cr_has_pretest']==true)? 1 : 0;
		$adaPostTest = ($dataCr['cr_has_kompetensi_test']==true)? 1 : 0;
		
		// get nilai knowledge
		$this->classroom_model->recData['crId'] = $row[0]['cr_id'];
		$this->classroom_model->recData['memberId'] = $row[0]['id_dinilai'];
		$dataCr = $this->classroom_model->select_classroom_member("byMemberId");
		$dataCr[0]['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $dataCr[0]['crm_step']);
		$detail_step = json_decode($dataCr[0]['crm_step'],true);
		$nilai_pre = 0;
		$nilai_post = 0;
		$arr_pre = explode('-',@$detail_step['PT']['ptScore']);
		$arr_post = explode('-',@$detail_step['CT']['ctScore']);
		if(is_array($arr_pre) && count($arr_pre)==4) {
			$nilai_pre = ($arr_pre[1]==0)? 0 : ($arr_pre[2]/$arr_pre[1])*100;
			$nilai_pre = number_format($nilai_pre,2);
		}
		if(is_array($arr_post) && count($arr_post)==4) {
			$nilai_post = ($arr_post[1]==0)? 0 : ($arr_post[2]/$arr_post[1])*100;
			$nilai_post = number_format($nilai_post,2);
		}
		
		$arr_nilai_kategori = array();
		if($adaPreTest) $arr_nilai_kategori['pre']['knowledge'] = $nilai_pre;
		if($adaPostTest) {
			$arr_nilai_kategori['post']['knowledge'] = $nilai_post;
		} else {
			// cek apakah ada evaluasi modul?
			// kl ga ada CT tp ada evaluasi modul gunakan nilai rerata evaluasi modul sebagai nilai post test
			$cr_module = preg_replace('/[[:cntrl:]]/', '', $dataCr[0]['cr_module']);
			$js_modul = json_decode($cr_module,true);
			
			$nilai_modul = 0;
			$juml_modul_w_soal = 0;
			foreach($js_modul['Module'] as $key2 => $val2) {
				$es = $val2['Evaluasi']['Status'];
				$es = trim($es);
				if($es=="active") {
					$juml_modul_w_soal++;
					// get rerata nilai user
					$arr_modul = explode('-',@$detail_step['MP'][$key2]['EvaScore']);
					if(is_array($arr_modul) && count($arr_modul)==4) {
						$nilai_modul += ($arr_modul[1]==0)? 0 : ($arr_modul[2]/$arr_modul[1])*100;
					}
				}
			}
			if($juml_modul_w_soal>0) {
				$nilai_post = $nilai_modul/$juml_modul_w_soal;
				$nilai_post = number_format($nilai_post,2);
				$arr_nilai_kategori['post']['knowledge'] = $nilai_post;
			}
		}
		
		$hit = array();
		$hit['pre'] = array();
		$hit['post'] = array();
		
		if($_POST) {
			$jaw_pre = $this->input->post('jaw_pre');
			$jaw_post = $this->input->post('jaw_post');
			$saran = $this->input->post('saran');
			$act = $this->input->post('act');
			
			$juml_dijawab = 0;
			$juml_total = 0;
			
			foreach($jaw_pre as $key => $val) {
				// inisiasi
				if(!isset($hit['pre'][$key])) {
					$hit['pre'][$key] = array();
					$hit['pre'][$key]['nilai_raw'] = 0;
					$hit['pre'][$key]['juml'] = 0;
				}
				foreach($val as $key2 => $val2) {
					$val2 = (int) $val2;
					$jaw_pre[$key][$key2] = $val2;
					
					$juml_total++;
					if(!empty($val2)) $juml_dijawab++;
					
					// nilai
					$dnilai = empty($val2)? 0 : ($val2/5)*100;
					$hit['pre'][$key]['nilai_raw'] += number_format($dnilai,2);
					$hit['pre'][$key]['juml']++;
				}
			}
			foreach($jaw_post as $key => $val) {
				// inisiasi
				if(!isset($hit['post'][$key])) {
					$hit['post'][$key] = array();
					$hit['post'][$key]['nilai_raw'] = 0;
					$hit['post'][$key]['juml'] = 0;
				}
				foreach($val as $key2 => $val2) {
					$val2 = (int) $val2;
					$jaw_post[$key][$key2] = $val2;
					
					$juml_total++;
					if(!empty($val2)) $juml_dijawab++;
					
					// nilai
					$dnilai = empty($val2)? 0 : ($val2/5)*100;
					$hit['post'][$key]['nilai_raw'] += number_format($dnilai,2);
					$hit['post'][$key]['juml']++;
				}
			}
			
			$juml_x_dijawab = $juml_total - $juml_dijawab;
			if($act=="sf") {
				if($juml_x_dijawab>0) $strError .= "<li>Terdapat ".$juml_x_dijawab." pertanyaan yang belum dijawab.</li>";
				if(!$btn_final_enabled) $strError .= "<li>Tidak dapat submit (menyimpan final) karena hari ini pelatihan masih berjalan. Gunakan Simpan Draft untuk menyimpan data sementara.</li>";
				if(empty($saran)) $strError .= "<li>Saran masih kosong.</li>";
			}
			
			// ada error?
			if(strlen($strError)<=0) {
				// hitung nilai per kategori
				foreach($hit as $key => $val) {
					foreach($val as $key2 => $val2) {
						$dnilai = ($val2['juml']==0)? 0 : ($val2['nilai_raw']/$val2['juml']);
						$dnilai = number_format($dnilai,2);
						$arr_nilai_kategori[$key][$key2] = $dnilai;
					}
				}
				
				// simpan datanya
				$arr_jaw = array();
				$arr_jaw['nilai_kategori'] = $arr_nilai_kategori;
				$arr_jaw['jaw_pre'] = $jaw_pre;
				$arr_jaw['jaw_post'] = $jaw_post;
				$arr_jaw['saran'] = $saran;
				
				$progress = ($juml_total==0)? 0 : ($juml_dijawab/$juml_total)*100;
				$progress = number_format($progress,2);
				if($progress=="100" && $act=="ss") $progress = 99;
				
				$json_jaw = $tanggal_mulai = $this->db->escape_str(json_encode($arr_jaw));
				$sql = "update _classroom_evaluasi_lv3_pairing set progress='".$progress."', jawaban='".$json_jaw."' where id='".$id_pairing."' ";
				$res = $this->db->query($sql);
				
				redirect(base_url('learning/class_room/evaluasi_lv3'));
			}
		}
		
		$this->data['row'] = $row;
		$this->data['strError'] = $strError;
		$this->data['jaw_pre'] = $jaw_pre;
		$this->data['jaw_post'] = $jaw_post;
		$this->data['saran'] = $saran;
		$this->data['btn_final_enabled'] = $btn_final_enabled;
		
		$this->data['title'] = 'Detail Evaluasi Pelatihan Level 3';
		$this->data['back_url'] = base_url('learning/class_room/evaluasi_lv3');
		
		$this->customjs = array('evaluasi_lv3');
		
		$this->generate_layout();
	}
	
	/* -- evaluasi level 3 end -- */

	/* -- project assignment start -- */
	public function project_assignment_atasan(){ // beranda project assignment view by atasan peserta classroom
		$this->data['title'] = 'Project Assignment';
		$datatask=$this->pa->get_palist($_SESSION['member_id']);
		$this->data['project_assignment']=$datatask;

		if(!empty($_POST['act']) || $_POST['act']=''){
		
		}else{
			$this->page = 'class_room/project_assignment_atasan';
		}
			$this->menu = 'learning';
			$this->generate_layout();
		
	
	}
	public function project_assignment_peserta(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		//classroom
		$this->classroom_model->recData['crId'] = $crId;
		$classroom = $this->classroom_model->select_classroom('byId');

		//list personel dalam grup
		$atasan=$this->pa->get_personelgroup($_SESSION['group_id']);
		//print_r($atasan);
		
		//header project
		$header=array();

		//detail project
		$detail=array();

		if(!$classroom['cr_has_project_assignment']) { // beranda project assignment view by peserta classroom
			show_404();
		} else {
			
            $this->data['title'] = 'Project Assignment';
			$this->data['class']=$classroom;
			$this->data['atasan']=$atasan;
			//cek dulu jika ada project dengan id kelas , maka tampilkan nilainya, jika tidak maka berarti baru
			$param=array("cr_id"=>$_GET['cr_id'],"member_id"=>$_SESSION['member_id']);
			$pa=$this->pa->get_pabyparam($param);
			$adapa=count((array)$pa);
			
		if($adapa > 0 ){
				$searchdata=array("cr_id"=>$_GET['cr_id'],"member_id"=>$_SESSION['member_id']);
				$datapa=$this->pa->get_pabyparam($searchdata);
				$idpa=$this->encryption->encrypt($datapa[0]->pa_id);
				
				//detail
				$param2=array('pa_id'=>$datapa[0]->pa_id);
				$datadetail=$this->pa->getdetail_pabyparam($param2);		
			}else{
				//insert pa baru
				$insertdata=array("cr_id"=>$_GET['cr_id'],"member_id"=>$_SESSION['member_id']);
				$idpanew=$this->pa->insert_pa($insertdata);
				$idpa=$this->encryption->encrypt($idpanew);
				$searchdata=array("pa_id"=>$idpanew);
				$datapa=$this->pa->get_pabyparam($searchdata);
				
				//detail
				$param2=array('pa_id'=>$idpanew);
				$datadetail=$this->pa->getdetail_pabyparam($param2);
			}
		
			$this->data['pa_id']=$idpa;
			$this->data['data_pa']=$datapa;
			$this->data['detail_pa']=$datadetail;
			$this->page = 'class_room/project_assignment_peserta';
			$this->menu = 'learning';
            $this->generate_layout();
            	/**/
		}
	/*	*/
	
	
	
	}
	public function isi_progress_pa(){
		
		if(empty($_GET)){
			redirect(base_url("learning/class_room/project_assignment_atasan"));
		}else{
			$pa_id=$_GET['tkn'];
			$datapa=$this->pa->get_pa($pa_id);
			//detail
			$param2=array('pa_id'=>$pa_id);
			$datadetail=$this->pa->getdetail_pabyparam($param2);
		}

		

		//list personel dalam grup
		$atasan=$this->pa->get_personelgroup($datapa[0]->group_id);

		
		$this->page = 'class_room/project_assignment_progress';
		$this->menu = 'learning';
		$this->data['detail_pa']=$datadetail;
		$this->data['pa']=$datapa;
		$this->generate_layout();
	}
	public function individual_scoreboard(){
		$crId = 0;
		if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
		$memberId = $this->session->userdata('member_id');

		//classroom
		$this->classroom_model->recData['crId'] = $crId;
		$classroom = $this->classroom_model->select_classroom('byId');

		// detail
		$paid=$_GET['pa_id'];
		$datapa=$this->pa->get_pa($paid);
		$filterdetail=array("pa_id"=>$paid);
		$detail_pa=$this->pa->getdetail_pabyparam($filterdetail);

		$this->data['pa']=$datapa;
		$this->data['detail_pa']=$detail_pa;
		$this->page = 'class_room/project_assignment_individual_dashboard';
		$this->generate_layout();
		
	}
	public function class_scoreboard(){
		if(empty($_GET)){
			redirect(base_url("learning/class_room/project_assignment_atasan"));
		}else{
			$cr_id=$_GET['tkn'];
			//datascoreboard
			$param2=array('c.cr_id'=>$cr_id);
			$datascoreboard=$this->pa->getpa_parameter($param2);
			$total = array_sum(array_column($datascoreboard,'pa_progress'));
			$count=count((array)$datascoreboard);
			if($total > 0 || $count > 0){
				$allsum=round($total/$count);
			}else{
				$allsum=0;
			}

			//log pa=
			$logperson=array();
			foreach($datascoreboard as $ds){
				$datalogs=$this->pa->getpa_log($ds->pa_id);
				foreach($datalogs as $dl){
					$logperson[$ds->member_name][]=$dl->pap_progress;
				}
				
				
			}

			$this->data['histori']=$logperson;
			$this->data['progress']=$datascoreboard;
			$this->data['total']=$allsum;
		}
		
		$this->page = 'class_room/project_assignment_scoreboard';
		$this->generate_layout();
		
	}	
	public function view_pafile(){
		
		
	}
	public function pa_ajax(){
		
		$act=$_POST['act'];
		$res="";
		switch($act){
			case 'addtask':
			    $pa_id=$this->encryption->decrypt($_POST['tokenpa']);
				$data=array(
								"pa"=>$pa_id,
								"pad_program"=>$_POST['program'],
								"pad_deliverable"=>$_POST['deliverable'],
								"pad_outcome"=>$_POST['outcome']);
				$add=$this->pa->insert_task($data);
				if($add !=0){
					$res="ok";
				}else{
					$res="gagal";
				}
			//	$res=$pa_id;
			
			break;
			case 'uploadfile':
				$fileupload = $_FILES['file']['tmp_name'] ;
				$paid=$this->encryption->decrypt($_POST['paid']);
				$uploadnow=$this->pa->upload_filepa($fileupload,$paid);
				$res=$uploadnow;
			break;
			case 'simpandraft':
				$pa_id=$this->encryption->decrypt($_POST['paid']);
				$draftupdate=array("atasan_id"=>$_POST['atasan'],"pa_jabatan"=>$_POST['jabatan'],"pa_status"=>"draft","pa_problem"=>$_POST["problem"],"pa_solution"=>$_POST["solusi"],"pa_timeframe"=>$_POST["timeframe"],"pa_date_change"=>date('Y-m-d H:i:s'));
				$update_pa=$this->pa->update_pa($draftupdate,$pa_id);
				$res=$update_pa;
			break;
			case 'simpanfinal':
				$pa_id=$this->encryption->decrypt($_POST['paid']);
				$draftupdate=array("atasan_id"=>$_POST['atasan'],"pa_jabatan"=>$_POST['jabatan'],"pa_status"=>"progress","pa_problem"=>$_POST["problem"],"pa_solution"=>$_POST["solusi"],"pa_timeframe"=>$_POST["timeframe"],"pa_date_change"=>date('Y-m-d H:i:s'));
				$update_pa=$this->pa->update_pa($draftupdate,$pa_id);
				$res=$update_pa;
			break;
			case 'updatetask':
				$pad_id=$this->encryption->decrypt($_POST['token']);
				$data=array("pad_progress"=>$_POST['progress'],"pad_date_change"=>date('Y-m-d H:i:s'));
				$updatetask=$this->pa->updatetask($data,$pad_id);
				$res=$updatetask;
			break;
			///update task 
			case 'updatetaskpeserta':
				$pad_id=$_POST['tokenpad'];
				$data=array("pad_program"=>$_POST['programed'],
				"pad_deliverable"=>$_POST['deliverabled'],
				"pad_outcome"=>$_POST['outcomed'],
				"pad_date_change"=>date('Y-m-d H:i:s'));
				$updatetask=$this->pa->updatetask($data,$pad_id);
				$res=$updatetask;
			break;
			case 'hapustask':
				$pad_id=$_POST['tokenpad'];
				$hapus=$this->pa->hapustask($pad_id);
				$res=$hapus;
			break;

			/// delete task
			case 'updateprogresspa':
				$pa_id=$this->encryption->decrypt($_POST['paid']);
				$draftupdate=array("pa_progress"=>$_POST['progress'],"pa_status"=>"progress","pa_date_change"=>date('Y-m-d H:i:s'));
				$update_pa=$this->pa->update_pa($draftupdate,$pa_id);
				//jika sudah update, masukkan ke dalam log task
				$datalog=array("pa_id"=>$pa_id,"pap_progress"=>$_POST['progress']);
				$updatelog_pa=$this->pa->updatelog_pa($datalog);
				$res=$update_pa;
			break;
			case 'catatanpa':
				$pa_id=$this->encryption->decrypt($_POST['paid']);
				$draftupdate=array("pa_catatan"=>$_POST['catatan'],"pa_date_change"=>date('Y-m-d H:i:s'));
				$update_pa=$this->pa->update_pa($draftupdate,$pa_id);
				$res=$update_pa;
			break;
			case 'searchpeserta':
				$cr_id=$_POST['crid'];
				$filter=array('cm.cr_id'=>$cr_id);
				$select='m.member_name,m.member_id';
			
				$datacr=$this->pa->getcr_byparam($filter,$select);				
				$res=$datacr;
			break;
			case 'updatestatus':
				$pa_id=$_POST['token'];
				$draftupdate=array("pa_status"=>$_POST['stat'],"pa_date_change"=>date('Y-m-d H:i:s'));
				$update_pa=$this->pa->update_pa($draftupdate,$pa_id);
				$res=$update_pa;
			break;
			default:
				$res="kosong";
			break;
		}

		
		echo  json_encode($res);
		exit;
	}
	/* -- project assignment end -- */

	/* -- Assignment Module -- */
	public function assignment_module(){
		
		$member_id=$_SESSION['member_id'];
		$classroom=$_GET['cr_id'];
		$module=$_GET['module'];
		$this->set_up_common_variable($classroom, $member_id);
		if(isset($_POST) && !empty($_POST)){
			
		}else{
			$filter_ma=array("classroom_id"=>$classroom,"member_id"=>$member_id,"urut_modul"=>$module);
			$cek_ma=$this->ma->get_ma($filter_ma);
			if(count((array)$cek_ma) > 0){
				$id_ma=$cek_ma[0]->id_cmm;
			}else{
				$dataIns=array("classroom_id"=>$classroom,"member_id"=>$member_id,"urut_modul"=>$module,"date_created"=>date("Y-m-d H:i:s"));
				$insMod=$this->ma->add_ma($dataIns);
				$id_ma=$insMod;
				///jika sudah ada
			}
			$infomoduleassignment=isset($this->data['dataMp']['Module'][$module]['InfoAssignment'])?$this->data['dataMp']['Module'][$module]['InfoAssignment']:"";
			$search_ma=array("id_cmm"=>$id_ma);
			$data_ma=$this->ma->get_ma($search_ma);	
			
			$this->data['modul_urut']=$module;
			$this->data['data_ma']=$data_ma;
			$this->data['info']=$infomoduleassignment;
			$this->page = 'class_room/module_assignment_upload';
			$this->generate_layout();
		}
	
	}

	function project_assignment_pk(){
		
		
		$filter=array('cm.member_id'=>$_SESSION['member_id']);
		$select='c.cr_name,cm.cr_id,c.cr_has_project_assignment';
		$datacr=$this->pa->getcr_byparam($filter,$select);
		foreach($datacr as $dc){
			$isPA=$dc->cr_has_project_assignment;
			if($isPA > 0){
				$kelas[]=array("id"=>$dc->cr_id,"nama"=>$dc->cr_name);

			}else{
			}			
			
		}
		
		$this->data['kelas']=$kelas;
		$this->page = 'class_room/project_assignment_pk';
		$this->generate_layout();
	}

	function cek_pa_bypk(){
		$crId = 0;	
		$memberId =isset($_GET['mid'])?$_GET['mid']:"";
		$crId =isset($_GET['cr_id'])?$_GET['cr_id']:"";
		
		//classroom
		$this->classroom_model->recData['crId'] = $crId;
		$datamember=array();
		if(!isset($memberId) || $memberId=="" ||$memberId=="all"){
			
			$memberkelas=$this->pa->get_membercr($crId);
			foreach($memberkelas as $m){
				$memberid=$m->member_id;
				$param=array("p.cr_id"=>$crId,"p.member_id"=>$memberid);
				$datapa=$this->pa->getpa_parameter($param);
				
				if(count((array)$datapa) > 0){
					$progres=$datapa[0]->pa_progress;
					$file=$datapa[0]->pa_file;
					$status=$datapa[0]->pa_status;
					$pa_id=$datapa[0]->pa_id;
					$ispk=$datapa[0]->ispk;

				}else{
					$progres="";
					$file="";
					$status="";
					$pa_id="";
				}
				
				$datamember[]=array(
									"nip"=>$m->member_nip,
									"mid"=>$memberid,
									"nama"=>$m->member_name,
									"grup"=>$m->member_group,
									"progres"=>$progres,
									"file"=>$file,
									"status"=>$status,
									"pa_id"=>$pa_id,
									"is_pk"=>$ispk);
			}
			
		}else{
			$param=array("p.cr_id"=>$crId,"p.member_id"=>$memberId);
			$datapa=$this->pa->getpa_parameter($param);
			if(count((array)$datapa) > 0){
				$datamember[]=array(
					"nip"=>$datapa[0]->member_nip,
					"nama"=>$datapa[0]->member_name,
					"grup"=>$datapa[0]->group_name,
					"progres"=>$datapa[0]->pa_progress,
					"file"=>$datapa[0]->pa_file,
					"status"=>$datapa[0]->pa_status,
					"pa_id"=>$datapa[0]->pa_id,
					"ispk"=>$datapa[0]->	ispk);
			}else{
				
			}
			
		}

		//list personel dalam grup
		
		
		$this->data['title'] = 'Project Assignment';
		$this->data['personel']	= $datamember;
		$this->page = 'class_room/listPA_pk';
		$this->menu = 'learning';
		$this->generate_layout();
	}

	function cek_detail_pa_bypk(){
		
		$pid=$this->uri->segment(4);
		if(isset($pid) || $pid!=""){

		}
		
			$datapa=$this->pa->get_pa($pid);
			//detail
			$param2=array('pa_id'=>$pid);
			$datadetail=$this->pa->getdetail_pabyparam($param2);			

			$this->data['pa_id']=$pid;
			$this->data['detail_pa']=$datadetail;
			$this->data['pa']=$datapa;
			$this->page = 'class_room/project_assignment_pk_detail';
			$this->menu = 'learning';
            $this->generate_layout();
	}

	public function readpdf(){
	
		$act=$_GET['act'];
		$modul=$_GET['modul'];

		switch($act){
			case "module":
				$dtpath=explode("_",$_GET['pathnext']);
				$pathfile=$dtpath[0]."-".$dtpath[1]."/".$dtpath[2].".pdf";
				$fileurl=FCPATH."media/module_assignment/".$pathfile;
				$file="media/module_assignment/".$pathfile;
				//header("content-type: application/pdf");
				//readfile($fileurl);
			break;
			case "project":
				$dtpath=explode("_",$_GET['pathnext']);
				$pathfile=$dtpath[0]."-".$dtpath[1]."/".$dtpath[2].".pdf";
				$fileurl=FCPATH."media/project_assignment/".$pathfile;
				$file="media/module_assignment/".$pathfile;
				//header("content-type: application/pdf");
				//$urlfile=FCPATH."media/module_assignment/451-6020/0.pdf";
				//readfile($fileurl);
				//echo $urlfile;
			break;
			default:
			$fileurl="";
			//echo "file tidak ditemukan";
			break;
		}
		
		$this->data['fileurl']= $fileurl;
		$this->data['file']= $file;
		$this->data['modul']=$modul;
		$this->page = 'class_room/readmodul';
		$this->generate_layout();
		
		}

	public function ma_ajax(){
		$act=$_POST['act'];
		$res="";

		switch($act){
			case 'uploadFile';
				$folder=$_POST['crid']."-".$_POST['mbid'];
				$nomor=$_POST['murut'];
				$fileupload = $this->ma->upload_ma($folder,$nomor);
				if($fileupload=="ok"){
					$filenew=$folder."/".$nomor.".pdf";
					//update data
					$upd_data=array("file_assignment"=>$filenew);
					$upd_search=array("classroom_id"=>$_POST['crid'],"member_id"=>$_POST['mbid'],"urut_modul"=>$_POST['murut']);
					$doupdt=$this->ma->update_ma($upd_data,$upd_search);
					if($doupdt =="ok"){
						$res="ok";
					}else{
						$res=$doupdt;
					}
					
				}else{
					$filenew="";
					$res="gagal";
				}
				
			break;
			case 'update_ma';
				$upd_data=array("status_ma"=>$_POST['status']);
				$upd_search=array("classroom_id"=>$_POST['crid'],"member_id"=>$_POST['mbid'],"urut_modul"=>$_POST['murut']);
				$doupdt=$this->ma->update_ma($upd_data,$upd_search);
				$res=$doupdt;
			break;
			default:
				$res="ok";
			break;
		}
		echo json_encode($res);
	}

	
}