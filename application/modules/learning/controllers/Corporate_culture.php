<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Culture_model culture_model
 * @property Member_model member_model
 */
class Corporate_culture extends MX_Controller {
    public $title = 'Corporate Culture';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['culture_model', 'group_model', 'member_model']);

        $this->data['title'] = $this->title;
        $this->data['back_url'] = $_SERVER['HTTP_REFERER'] ?? NULL;

        // special ID, add highlight on correct test answer
        // $this->data['specialId'] = array("6020");
        $this->data['specialId'] = array("6020","6019","6005","6006","6007","6008","6125","6054","3016","6178");
    }

    public function index(){
        $memberId = $this->session->userdata('member_id');

        $this->culture_model->recData['memberId'] = $memberId;
        $this->data['datas'] = $this->culture_model->select_culture("listByMemberId");

        $this->page = 'corporate_culture/lp';
        $this->data['back_url'] = base_url('learning');

        $this->generate_layout();
    }

    public function info(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');

        $this->culture_model->recData['crId'] = $crId;
        $this->culture_model->recData['memberId'] = $memberId;
        $this->data['data'] = $this->culture_model->select_culture("activeByMemberId");

        $this->page = 'corporate_culture/info';
        $this->data['back_url'] = base_url('learning/corporate_culture');

        $this->generate_layout();
    }

    private function set_up_common_variable($crId = 0, $memberId = 0){
        if($this->input->get('cr_id') === NULL) redirect(base_url('learning/culture'));

        $this->data['memberId'] = $memberId;

        $this->culture_model->recData['crId'] = $crId;
        $this->culture_model->recData['memberId'] = $memberId;
        $dataCr = $this->culture_model->select_culture("activeByMemberId");
        if (!$dataCr) redirect(base_url('learning/culture'));
        $dataCr['group_name'] = $this->group_model->get_group_name($dataCr['group_id']);

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
//                'cr_price' => $dataCr['cr_price'],
            ];

            $this->culture_model->update_cr_info($dataCr['crm_id'], $cr_info);
        }
    }

    private function set_up_step($step = array(), $dataCr = array()){
        $dataMp = json_decode($dataCr['cr_module'],true);

        if($step == ''){
            $step = array();
            $step['PL'] = array();
            $step['PL']['plStatus'] = "0";
            $step['PT'] = array();
            $step['PT']['ptStatus'] = "0";
            $step['PT']['ptDate'] = "";
            $step['PT']['ptScore'] = "";
            $step['RP'] = "0";
            $step['MP'] = array();

            for($i=0;$i<count(@$dataMp['Module']);$i++){
                for($j=0;$j<count(@$dataMp['Module'][$i]['Materi']);$j++){
                    $step['MP'][$i]['Materi'][$j] = ($i==0 && $j==0) ? "1": "0";
                }
                $step['MP'][$i]['EvaStatus'] = "0"; // 0,1,2
                $step['MP'][$i]['EvaDate'] = "";
                $step['MP'][$i]['EvaScore'] = ""; // score-count-right-false

                $step['MP'][$i]['FbStatus'] = "0"; // 0,1,2
                $step['MP'][$i]['FbDate'] = "";
                $step['MP'][$i]['FbDesc'] = ""; // || separated
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
            $this->culture_model->update_culture_member($recData);
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
                $this->culture_model->update_culture_member($recData);
            }
        }

        return $step;
    }

    public function home(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');

        $this->set_up_common_variable($crId, $memberId);

        $this->page = 'corporate_culture/home';
        $this->customcss = array('fontawesome');
        $this->data['back_url'] = base_url('learning/corporate_culture');

        $this->generate_layout();
    }

    /* Training Modules */
    public function module(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');

        $this->set_up_common_variable($crId, $memberId);

        if($this->data['dataStep']['RP'] == '0'){
            redirect(base_url('learning/corporate_culture/rencana_pembelajaran?cr_id='.$crId));
        }else{
            for($i=0;$i<@count($this->data['dataMp']['Module']);$i++){
                for($j=0;$j<@count($this->data['dataMp']['Module'][$i]['Materi']);$j++){
                    if($j>0 && !isset($this->data['dataStep']['MP'][$i]['Materi'][$j])){
                        $step = $this->data['dataStep'];
                        $step['MP'][$i] ['Materi'][$j] = ($step['MP'][$i] ['Materi'][$j-1]=="2") ? "1" : "0";
                        $recData['crmId'] = $dataCr['crm_id'];
                        $recData['crId'] = $dataCr['cr_id'];
                        $recData['memberId'] = $dataCr['member_id'];
                        $recData['crmStep'] = json_encode($step);    
                        $recData['crmFb'] = $dataCr['crm_fb'];            
                        $this->culture_model->update_culture_member($recData);
                        $this->data['dataStep'] = $step;
                    }
                }
            }
        }

        $this->page = 'corporate_culture/module';
        $this->customcss = array('fontawesome');
        $this->data['back_url'] = base_url('learning/corporate_culture/home?cr_id='.$crId);

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
            $this->culture_model->update_culture_member($recData);

            redirect(base_url('learning/corporate_culture/module?cr_id='.$crId));
            exit;
        }

        $this->page = 'corporate_culture/rencana_pembelajaran';

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
        $step['MP'][$module]['Materi'][$materi] ="2";

        if(isset($step['MP'][$module]['Materi'][$materi+1]) && $step['MP'][$module]['Materi'][$materi+1]=="0"){
            $step['MP'][$module]['Materi'][$materi+1] ="1";
        }
        else{
            $step['MP'][$module]['Materi'][$materi+1] ="1";
            if(!isset($step['MP'][$module]['EvaStatus']) || $step['MP'][$module]['EvaStatus']=="0"){
                $step['MP'][$module]['EvaStatus'] = "1";
            }
        }

        $recData['crId'] = $this->data['data']['cr_id'];
        $recData['memberId'] = $memberId;
        $recData['crmStep'] = json_encode($step);
        $recData['crmId'] = $this->data['data']['crm_id'];
        $recData['crmFb'] = $this->data['data']['crm_fb'];

        $this->culture_model->update_culture_member($recData);

        $this->data['module'] = $module;
        $this->data['materi'] = $materi;

        $this->page = 'corporate_culture/materi1';
        $this->customcss = array('fontawesome');

        $this->generate_layout();
    }

    public function materi_doc(){
        $doc = base64_decode($this->input->get('doc'));
        $cr_id = $this->input->get('cr_id');

        $this->page = 'corporate_culture/doc';
        $this->data['doc'] = $doc;

        $this->generate_layout();
    }

    public function kuis1(){
        $this->page = 'corporate_culture/dev';
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
        $dataSoalMp = $this->culture_model->get_soal($arrSoal);

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
            $ctBenar = array();
            $ctSalah = array();
            $ansRight = 0;
            $ansWrong = 0;
            $attempt = $attempt>0?$attempt-1:$attempt;
            foreach($this->input->post('choice') as $key => $val){
                if($soal[$key]['right'] == $val){
                    $ansRight = $ansRight + 1;
                    $ctBenar[] = $soal[$key]['crs_id'];
                }else{
                    $ansWrong = $ansWrong + 1;
                    $ctSalah[] = $soal[$key]['crs_id'];
                }
            }

            $step = $this->data['dataStep'];
            if($ansRight >= $this->data['dataMp']['Module'][$module]['Evaluasi']['ReqPassed']){
                $step['MP'][$module]['EvaStatus'] = "2";
                $step['MP'][$module]['EvaDate'] = date('Y-m-d H:i:s');
                $step['MP'][$module]['EvaScore'] = "A-".count($dataSoalMp)."-".$ansRight."-".$ansWrong;
                $step['MP'][$module]['FbStatus'] = "1";

                $step['ctBenar'] = $ctBenar;
                $step['ctSalah'] = $ctSalah;
                $step['evaAttempt'][$module] = $attempt;

                $recData['crId'] = $this->data['data']['cr_id'];
                $recData['memberId'] = $this->data['data']['member_id'];
                $recData['crmStep'] = json_encode($step);
                $recData['crmId'] = $this->data['data']['crm_id'];
                $recData['crmFb'] = $this->data['data']['crm_fb'];

                $this->culture_model->update_culture_member($recData);
            }else{
                $step['MP'][$module]['EvaStatus'] = "2";
                $step['MP'][$module]['EvaDate'] = date('Y-m-d H:i:s');
                $step['MP'][$module]['EvaScore'] = "D-".count($dataSoalMp)."-".$ansRight."-".$ansWrong;
                $step['MP'][$module]['FbStatus'] = "1";

                $step['ctBenar'] = $ctBenar;
                $step['ctSalah'] = $ctSalah;
                $step['evaAttempt'][$module] = $attempt;

                $recData['crId'] = $this->data['data']['cr_id'];
                $recData['memberId'] = $this->data['data']['member_id'];
                $recData['crmStep'] = json_encode($step);
                $recData['crmId'] = $this->data['data']['crm_id'];
                $recData['crmFb'] = $this->data['data']['crm_fb'];

                $this->culture_model->update_culture_member($recData);
            }
            redirect(base_url('learning/corporate_culture/evaluasi?cr_id='.$crId.'&module='.$module));
        }

        $this->data['soal'] = $soal;
        $this->data['module'] = $module;
        $this->data['ulang'] = $this->input->get('ulang');
        $this->data['attempt'] = $attempt;

        $this->page = 'corporate_culture/evaluasi';

        $this->customcss = array('fontawesome');
        $this->customjs = array('evaluasi');

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

        if($this->input->post('submitFeedbackModule')){
            $step = $this->data['dataStep'];

            $dataFb = array();
            $dataFb[$module] = array();
            $dataFb[$module]['fb1'] = $this->input->post('fb1');
            $dataFb[$module]['fb2'] = $this->input->post('fb2');
            $dataFb[$module]['fb3'] = $this->input->post('fb3');
            $dataFb[$module]['fb4'] = $this->input->post('fb4');
            $dataFb[$module]['fb5'] = $this->input->post('fb5');
            $dataFb[$module]['fbText'] = nl2br($this->input->post('fbText'));
            
            $step['MP'][$module]['FbStatus'] = "2";
            $step['MP'][$module] ['FbDate'] = date('Y-m-d H:i:s');
            $step['MP'][$module] ['FbDesc'] = $this->input->post('fb1')."||".$this->input->post('fb2')."||".$this->input->post('fb3')."||".$this->input->post('fb4')."||".$this->input->post('fb5')."||".$dataFb[$module]['fbText']; // comma separated
            
            if($module==count($this->data['dataMp']['Module'])-1){
                $step['CT'] = array();
                $step['CT']['ctStatus'] = "1";
                $step['CT']['ctDate'] = "";
                $step['CT']['ctScore'] = "";
            }
            
            if(isset($this->data['dataMp']['Module'][($module+1)])){
                $step['MP'][($module+1)]['Materi'][0] = "1";
            }
            
            $recData['crmFb'] = json_encode($dataFb);
            $recData['crmId'] = $this->data['data']['crm_id'];
            $recData['crId'] = $this->data['data']['cr_id'];
            $recData['memberId'] = $this->data['data']['member_id'];
            $recData['crmStep'] = json_encode($step);
            $this->culture_model->update_culture_member($recData);
            
            redirect(base_url('learning/corporate_culture/feedback_module?cr_id='.$crId.'&module='.$module));
            exit;
        }

        $this->data['module'] = $module;
        $this->page = 'corporate_culture/feedback_module'; // feedback-module on old API
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

        $dataQue = explode(",",$this->data['dataCt']['Question']);

        $arrSoal = "'".$this->data['dataCt']['Question']."'";
        $arrSoal = str_replace(",","','",$arrSoal);
        $dataSoalCt = $this->culture_model->get_soal($arrSoal);

        if($this->data['dataCt']['Random']=="acak"){
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
            
            $ctResult = (($ansRight/count($dataQue))*100);

            if($ctResult >= $this->data['dataCt']['GradeA']){ $grade = "A"; }
            elseif($ctResult >= $this->data['dataCt']['GradeB']){ $grade = "B"; }
            elseif($ctResult >= $this->data['dataCt']['GradeC']){ $grade = "C"; }
            else{ $grade = "D"; }

            // begin of poin
            $this->culture_model->recData['crId'] = $crId;
            $dataCc = $this->culture_model->select_culture("byId");
            $poin_setting = $this->member_model->select_member_poin_setting();
            $recData['memberId']    = $memberId;
            $recData['mpSection']   = 'CC';
            $recData['mpContentId'] = '';
            $recData['mpName']      = $dataCc['cr_name'].' (Grade '.$grade.')';
            $recData['mpPoin']      = $poin_setting[0]['mps_cc_grade_'.strtolower($grade)];
            $this->member_model->insert_member_poin($recData);
            $recData['interval'] = '';
            $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
            $this->member_model->recData['memberId'] = $memberId;
            $this->member_model->update_member('byField','','member_poin',$total_poin);
            // end of poin

            $step['CT']['ctScore'] = $grade."-".count($dataSoalCt)."-".$ansRight."-".$ansWrong;
            $step['RESULT'] = $grade;

            $step['ctBenar'] = $ctBenar;
            $step['ctSalah'] = $ctSalah;

            $this->session->set_userdata('show_reward', true);
            $this->session->set_userdata('reward', ['poin' => $poin_setting[0]['mps_cc_grade_'.strtolower($grade)], 'cause' => 'Finish Corporate Culture']);

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

                    $targetPathPdf = $this->generate_sertifikat($dataSert, $grade, $number);

                    $step['CERTIFICATE'] = $targetPathPdf;
                }
            }

            $recData = array();
            $recData['crId'] = $this->data['data']['cr_id'];
            $recData['memberId'] = $this->data['data']['member_id'];
            $recData['crmStep'] = json_encode($step);
            $recData['crmId'] = $this->data['data']['crm_id'];
            $recData['crmFb'] = $this->data['data']['crm_fb'];

            $this->culture_model->update_culture_member($recData);

            redirect(base_url('learning/corporate_culture/competency?cr_id='.$crId));
            exit;
        }

        $this->data['soal'] = $soal;

        $this->page = 'corporate_culture/competency';
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

        $this->data['back_url'] = base_url('learning/corporate_culture/home?cr_id='.$crId);

        $this->generate_layout();
    }

    private function generate_sertifikat($dataSert = array(), $grade = ''){
        $targetPath = getcwd().'/'.MEDIA_IMAGE_PATH;
        $fontPath = getcwd().'/assets/fonts/';

        // $x = 842;
        // $y = 1190;
        $x = 792;
        $y = 612;

        $img1 = PATH_ASSETS.'img/sertifikat_bg_blank.png';
        $img2 = MEDIA_IMAGE_PATH.$dataSert['Logo'];
        $img3 = MEDIA_IMAGE_PATH.$dataSert['Signature'];

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
        imagettftext($outputImage, 16, 0, $x, 170, $black1, $font, $title);
        
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
        $date = date('d')." ".$arrMonth[intval(date('m'))-1]." ".date('Y');
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

        $filename = $targetPath.'sertifikat_'.$this->data['data']['cr_id'].'_'.$this->data['data']['member_id'].'.png';
        imagepng($outputImage, $filename);
        imagedestroy($outputImage); 

        $targetPathPdf = SERTIFIKAT_PATH.'sertifikat_corporate_culture_'.$this->data['data']['cr_id'].'_'.$this->data['data']['member_id'].'.pdf';

        require_once APPPATH."third_party/fpdf182/fpdf.php";

        // $pdf = new FPDF("L","mm","letter");
        $pdf = new FPDF("L", "pt", array($x, $y));
        $pdf->AddPage();
        $pdf->Image($filename,0,0,0,0);
        $pdf->Output("F", getcwd().'/'.$targetPathPdf);
        // unlink($filename);

        return base_url().$targetPathPdf;
    }

    /* # Competency */

    public function sertifikat_doc(){
        $doc = base64_decode($this->input->get('doc'));
        $cr_id = $this->input->get('cr_id');

        $this->page = 'corporate_culture/sertifikat_doc';
        $this->data['doc'] = $doc;

        $this->generate_layout();
    }

    public function reset(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');
        $memberId = $this->session->userdata('member_id');

        $this->culture_model->recData['crId'] = $crId;
        $this->culture_model->recData['memberId'] = $memberId;
        $dataCr = $this->culture_model->select_culture("activeByMemberId");
        $this->set_up_step('', $dataCr);

        redirect(base_url('learning/corporate_culture/home?cr_id='.$crId));
    }

    /* Mockup */
    function pembelajaran(){
        $this->data['title'] = 'Corporate Culture';
        $this->page = 'corporate_culture/mockup/culture_pembelajaran';
        $this->menu = 'learning';

        $this->generate_layout();
    }

    function menu($num=null){
        if($num>0){
            switch($num){
                case 3:
                $this->page = 'mockup/corporate_culture/culture_3';
                break;
                case 4:
                $this->page = 'mockup/corporate_culture/culture_4';
                break;
                case 5:
                $this->page = 'mockup/corporate_culture/culture_5';
                break;
                case 6:
                $this->page = 'mockup/corporate_culture/culture_6';
                break;
                case 7:
                $this->page = 'mockup/corporate_culture/culture_7';
                break;
                case 8:
                $this->page = 'mockup/corporate_culture/culture_8';
                break;
                case 9:
                $this->page = 'mockup/corporate_culture/culture_9';
                break;
                case 10:
                $this->page = 'mockup/corporate_culture/culture_10';
                break;
                case 11:
                $this->page = 'mockup/corporate_culture/culture_11';
                break;
                case 12:
                $this->page = 'mockup/corporate_culture/culture_12';
                break;
                case 13:
                $this->page = 'mockup/corporate_culture/culture_13';
                break;
                case 14:
                $this->page = 'mockup/corporate_culture/culture_14';
                break;
                case 15:
                $this->page = 'cmockup/orporate_culture/culture_15';
                break;
                case 16:
                $this->page = 'mockup/corporate_culture/culture_16';
                break;
                case 17:
                $this->page = 'mockup/corporate_culture/culture_17';
                break;
                default:
                show_404();
                break;
            }

            $this->generate_layout();
        }
    }
    /* # Mockup */
}
