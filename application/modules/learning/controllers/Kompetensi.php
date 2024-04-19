<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Kompetensi_model kompetensi_model
 * @property Category_model category_model
 */
class Kompetensi extends MX_Controller {
    public $title = 'Kompetensi';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['kompetensi_model', 'group_model', 'classroom_model', 'category_model']);

        $this->data['title'] = $this->title;
        $this->data['back_url'] = $_SERVER['HTTP_REFERER'] ?? NULL;

        $this->member_id = $this->session->userdata('member_id');

        // special ID, add highlight on correct test answer
        $this->data['specialId'] = array("6020","6019","6005","6006","6007","6008","6125","6054","3016", "6178");
    }

    public function index(){
        $this->data['member_id'] = $this->member_id;

        $this->kompetensi_model->recData['memberId'] = $this->member_id;
        $this->data['datas'] = $this->kompetensi_model->select_kompetensi("listByMemberId");

        $this->page = 'kompetensi/list';

        $this->data['back_url'] = base_url('learning');

        $this->generate_layout();
    }

    private function set_up_common_variable($crId = 0, $memberId = 0){
        if($crId === 0) redirect(base_url('learning/kompetensi'));

        $this->kompetensi_model->recData['crId'] = $crId;
        $this->kompetensi_model->recData['memberId'] = $memberId;
        $dataCr = $this->kompetensi_model->select_kompetensi("activeByMemberId");
        $dataCr['group_name'] = $this->group_model->get_group_name($dataCr['group_id']);

        $this->data['data'] = $dataCr;

        $dataCr['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $dataCr['crm_step']);

        $dataStep = json_decode($dataCr['crm_step'],true);
        $step = $this->set_up_step($dataStep, $dataCr);

        $this->data['dataStep'] = $step;
    }

    private function set_up_step($step = array(), $dataCr = array()){
        $arrS = $step;
        $max_step  = $dataCr['cr_komp_max_lv'];
        $juml_soal = 3;
        $step = @$step['step'];

        if($step == 0){
            $arrS = array();
            
            $arrS['step'] = 1;
            $arrS['is_done_all'] = 0;
            $arrS['latest_work'] = "";
            $arrS['tgl_submit'] = "";
            $arrS['hasil'] = "0";
            $arrS['level'] = array();
            
            for($i=1;$i<=$max_step;$i++) {
                $arrS['level'][$i]['is_done'] = "";
                $arrS['level'][$i]['persen_benar'] = "";
                
                $k = 0;
                $sql = "SELECT crs_id FROM _kompetensi_soal WHERE cat_id='".$dataCr['cat_id']."' and crs_level='".$i."' order by RAND() limit ".$juml_soal." ";
                $data = $this->db->query($sql)->result_array();
                $j = 0;
                foreach($data as $key => $val) {
                    $j++;
                    $arrS['level'][$i]['pertanyaan'][$j]['id'] = $val['crs_id'];
                    $arrS['level'][$i]['pertanyaan'][$j]['jawaban'] = '';
                    $arrS['level'][$i]['pertanyaan'][$j]['tgl_jawab'] = '';
                }
                if($j!=$juml_soal) $arrS['level'][$i]['pertanyaan'] = null;
            }
            $sql = "UPDATE _kompetensi_member SET crm_step = '".json_encode($arrS)."' WHERE crm_id='".$dataCr['crm_id']."' ";
            $this->db->query($sql);
        }

        return $arrS;
    }

    public function detail(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');

        $this->set_up_common_variable($crId, $this->member_id);

        $max_lv = $this->data['data']['cr_komp_max_lv'];
        $step = $this->data['dataStep']['step'];
        $prasyarat = [];
        if ($step <= $max_lv && $this->data['dataStep']['is_done_all']){
            $prasyarat = $this->kompetensi_model->select_kompetensi_prasyarat($crId, $step);
        }
        $this->data['prasyarat'] = $prasyarat;
        $this->category_model->catId = $this->data['data']['cat_id'];
        $this->data['category'] = $this->category_model->select_category('byId');

        $this->data['memberId'] = $this->member_id;
        $this->page = 'kompetensi/detail';
        
        $this->data['back_url'] = base_url('learning/kompetensi');
        $this->customcss = array('fontawesome');
        $this->customjs = array('content');

        $this->generate_layout();
    }

    public function evaluasi(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');

        $this->set_up_common_variable($crId, $this->member_id);

        $this->data['memberId'] = $this->member_id;

        $max_step = $this->data['data']['cr_komp_max_lv'];

        $warning = '';
        $step = $this->data['dataStep']['step'];

        $today = date('Y-m-d');
        if ($this->data['data']['cr_is_daily'] === "1"){
            if ($this->data['dataStep']['latest_work'] && $this->data['dataStep']['latest_work'] >= $today){
                redirect(base_url('learning/kompetensi/detail?cr_id='.$crId));
                exit;
            }
        }

        $started = (int) $this->input->get('started');
        $juml_soal = count($this->data['dataStep']['level'][$step]['pertanyaan']);
        $current_no_soal = 0;
        $current_id_soal = 0;
        $juml_dijawab = 0;

        foreach($this->data['dataStep']['level'][$step]['pertanyaan'] as $key => $val) {
            $tgl_jawab = $val['tgl_jawab'];
            if(empty($tgl_jawab)) {
                if(empty($current_no_soal)) {
                    $current_no_soal = $key;
                    $current_id_soal = $val['id'];
                }
            } else {
                $juml_dijawab++;
            }
        }

        if($juml_dijawab == $juml_soal) {
            redirect(base_url('learning/kompetensi/detail?cr_id='.$crId));
            exit;
        }

        if($juml_soal == 0) {
            $warning .= "Data soal tidak ditemukan.";
        }

        $display_jaw_benar = false;
        $button_teks = '';
        $style = "display:none;";
        $soal = [];
        $arrJ = array();
        $addJS = '';
        $jawaban_benar = '';

        if($current_id_soal > 0) {
            $button_teks = ($current_no_soal < $juml_soal)? 'Simpan jawaban dan ke soal berikutnya' : 'Simpan jawaban (selesai)';
            if(in_array($this->member_id, $this->data['specialId'])){
                $display_jaw_benar = true;
            }
            
            $sql = "SELECT * FROM _kompetensi_soal WHERE crs_id='".$current_id_soal."'";
            $soal = $this->db->query($sql)->result_array();
            if($soal[0]['crs_id']>0) {
                $jawaban_benar = $soal[0]['crs_right'];
                $durasi_detik = $soal[0]['crs_durasi_detik'];
                
                $arrJ[0] = $soal[0]['crs_right'];
                $arrJ[1] = $soal[0]['crs_answer1'];
                $arrJ[2] = $soal[0]['crs_answer2'];
                $arrJ[3] = $soal[0]['crs_answer3'];
                shuffle($arrJ);
            } else {
                $warning .= "Data soal tidak ditemukan.";
            }

            if($this->input->post()){
                $started = 1;
                $jawaban_user = $this->function_api->security($this->input->post('choice'));
                $forcedSubmit = (int) $this->input->post('forcedSubmit');
                
                if(!empty($jawaban_user) || $forcedSubmit==true) {
                    $dataStep = $this->data['dataStep'];
                    
                    // skip jawaban?
                    if(empty($jawaban_user) && $forcedSubmit==true) {
                        $is_benar = 0;
                    } else {
                        $is_benar = ($jawaban_user==$jawaban_benar)? '1' : '0';
                    }
                    
                    $dataStep['level'][$step]['pertanyaan'][$current_no_soal]['jawaban'] = $is_benar;
                    $dataStep['level'][$step]['pertanyaan'][$current_no_soal]['tgl_jawab'] = date("Y-m-d H:i:s");

                    $ctBenar = array();
                    $ctSalah = array();
                    $juml_benar = 0;
                    $juml_dijawab = 0;
                    foreach($dataStep['level'][$step]['pertanyaan'] as $key => $val) {
                        if($val['jawaban']==1){
                            $juml_benar++;
                            $ctBenar[] = $val['id'];
                        } else {
                            $ctSalah[] = $val['id'];
                        };
                        if(!empty($val['tgl_jawab'])) $juml_dijawab++;
                    }
                    $persen_benar = ($juml_soal==0)? 0 : number_format(($juml_benar/$juml_soal)*100,2);
                    $is_done = ($juml_dijawab==$juml_soal)? 1 : 0;
                    $dataStep['latest_work'] = $today;
                    if($is_done) {
                        if($persen_benar>=100) {
                            $next_step = $step+1;
                            $is_done_all = ($next_step>$max_step)? 1 : 0;
                            $dataStep['hasil'] = $step;
                            $dataStep['is_done_all'] = $is_done_all;
                            $dataStep['tgl_submit'] = date("Y-m-d H:i:s");
                        } else {
                            $next_step = $step;
                            $dataStep['is_done_all'] = 1;
                            $dataStep['tgl_submit'] = date("Y-m-d H:i:s");
                        }
                        // $next_url = SITE_HOST.'/_api/_kompetensi/index.php?memberId='.$memberId.'&crId='.$crId.'&crPage=detail';
                        $next_url = base_url('learning/kompetensi/detail?cr_id='.$crId);
                    } else {
                        $next_step = $step;
                        // $next_url = SITE_HOST.'/_api/_kompetensi/index.php?memberId='.$memberId.'&crId='.$crId.'&crPage=evaluasi&started=1';
                        $next_url = base_url('learning/kompetensi/evaluasi?cr_id='.$crId.'&started=1');
                    }

                    $dataStep['ctBenar'] = $ctBenar;
                    $dataStep['ctSalah'] = $ctSalah;
                    
                    $dataStep['step'] = $next_step;
                    $dataStep['level'][$step]['is_done'] = $is_done;
                    $dataStep['level'][$step]['persen_benar'] = $persen_benar;
                    
                    $sql = "UPDATE _kompetensi_member SET crm_step = '".json_encode($dataStep)."' WHERE crm_id='".$this->data['data']['crm_id']."' ";
                    $this->db->query($sql);
                    header('location:'.$next_url);
                    exit;
                }
            }

            if($started==1) {
                $addJS = 'mulaiUjian();';
                $style = "display:block;";
            }
        }

        $this->page = 'kompetensi/evaluasi';

        $this->data['durasi_detik'] = $durasi_detik;
        $this->data['durasi'] = date('i:s', $durasi_detik);
        $this->data['current_no_soal'] = $current_no_soal;
        $this->data['juml_soal'] = $juml_soal;
        $this->data['warning'] = $warning;
        $this->data['button_teks'] = $button_teks;
        $this->data['display_jaw_benar'] = $display_jaw_benar;
        $this->data['style'] = $style;
        $this->data['soal'] = $soal;
        $this->data['arrJ'] = $arrJ;
        $this->data['addJS'] = $addJS;
        $this->data['jawaban_benar'] = $jawaban_benar;

        $this->data['back_url'] = base_url('learning/kompetensi/detail?cr_id='.$crId);
        $this->customcss = array('fontawesome');
        $this->customjs = array('kompetensi_evaluasi', 'content');

        $this->generate_layout();
    }

    public function reset(){
        $crId = 0;
        if($this->input->get('cr_id')) $crId = $this->input->get('cr_id');

//        $this->kompetensi_model->recData['crId'] = $crId;
//        $this->kompetensi_model->recData['memberId'] = $this->member_id;
//        $dataCr = $this->kompetensi_model->select_classroom("activeByMemberId");;
//
//        $this->set_up_step('', $dataCr);

        $this->kompetensi_model->recData['crId'] = $crId;
        $this->kompetensi_model->recData['memberId'] = $this->member_id;
        $dataCr = $this->kompetensi_model->select_kompetensi_member("byMemberId");
        $sql = "UPDATE _kompetensi_member SET crm_step = '' WHERE crm_id='".$dataCr[0]['crm_id']."' ";
        $this->db->query($sql);

        redirect(base_url('learning/kompetensi/detail?cr_id='.$crId));
    }
}