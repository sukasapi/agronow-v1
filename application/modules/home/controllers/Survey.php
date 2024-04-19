<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends MX_Controller {
    public $title = 'Survey';
    public $menu = 'home';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['survey_model', 'content_model']);
    }

    public function index(){
        if($this->input->get('surveyId') === NULL) redirect(base_url('home'));
        $surveyId = $this->input->get('surveyId');

        $isMemberDoSurvey = $this->survey_model->is_member_do_survey($this->session->userdata('member_id'), $surveyId);

        if($isMemberDoSurvey === false){
            $this->data['surveyId'] = $surveyId;
            $this->page = 'home/survey';

            $recData = array();
            $recData['surveyId'] = $surveyId;
            $recData['memberId'] = $this->session->userdata('member_id');
            $this->data['survey'] = $this->survey_model->select_survey("byId",$recData);
            
            $this->data['back_url'] = base_url('home');
            $this->generate_layout();
        }else{
            $this->page = 'home/survey_done';

            $this->data['back_url'] = base_url('home');
            $this->generate_layout();
        }
    }

    public function save(){
        if($this->input->get('surveyId') === NULL) redirect(base_url('home'));
        $surveyId = $this->input->get('surveyId');

        if($this->input->post('submitSurvey')){
            $answer = array();
            $post_answer = $this->input->post('answer');

            for($i=0;$i<count($post_answer);$i++){
                $answer['Q'.$i] = $post_answer[$i];
            }
            $recData['surveyId'] = $surveyId;
            $recData['memberId'] = $this->session->userdata('member_id');
            $recData['smData'] = json_encode($answer);
            
            $this->survey_model->insert_survey_member($recData);
        }

        redirect(base_url('home/survey?surveyId='.$surveyId));
    }
}