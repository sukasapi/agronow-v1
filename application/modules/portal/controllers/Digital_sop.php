<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Group_model group_model
 * @property Content_model content_model
 * @property Media_model media_model
 * @property Function_api function_api
 * @property Member_model member_model
 */
class Digital_sop extends MX_Controller {

    protected $member_id, $group_id, $section_id;

	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['member_model', 'group_model', 'content_model', 'media_model']);
        $this->member_id = $this->session->userdata('member_id');
        $this->group_id = $this->session->userdata('group_id');
        $this->section_id = 36;
    }

	function index(){
	    $page = $this->input->get('page');
        $page = $page>=1?$page:1;
        $limit = 50; // content per page
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $this->content_model->recData['sectionId'] = $this->section_id;
        $dataContent = $this->content_model->select_content("publish","", "", 0, "", $this->group_id);
        $data = [];
        $result = [];
        foreach ($dataContent as $dc){
            $this->content_model->recData['contentId'] = $dc['content_id'];
            $data['id'] 	= $dc['content_id'];
            $data['title'] 	= $dc['content_name'];
            $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dc['content_id']);
            $data['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
            $data['date'] 	= $this->function_api->date_indo($dc['content_publish_date'],"datetime");
            array_push($result,$data);
        }
        $this->data['data'] = $result;
        $this->data['page'] = $page;
        $this->data['title'] = 'Portal';
        $this->page = 'digital_sop';
        $this->menu = 'portal';
        $this->generate_layout();
	}

    function detail($content_id=NULL){
        $this->content_model->recData['contentId'] = $content_id;
        $content = $this->content_model->select_content("byId");
        if (!$content){
            show_404();
        }
        if ($content['section_id']!=$this->section_id){
            show_404();
        }
        
        if ($content['group_id']!=$this->group_id){
            show_404();
        }

        $data_hits = [
            'contentId' => $content_id,
            'memberId'  => $this->member_id,
            'contentHitsChannel'    => 'android'
        ];
        $this->content_model->insert_content_hits($data_hits);
        $this->content_model->update_content('hits', $data_hits);

        $content['image']           = $this->media_model->get_primary_image($content['section_id'],$content['content_id']);
        $this->data['content']  = $content;
        $this->data['title']    = 'Digital SOP';
        $this->page = 'digital_sop_detail';
        $this->menu = 'portal';

        $dataContent = $this->content_model->select_content("publish","",5);
        $tmp = [];
        $info = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $tmp['id'] 	= $dataContent[$i]['content_id'];
                $tmp['title'] 	= $dataContent[$i]['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->section_id,$dataContent[$i]['content_id']);
                $tmp['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"dd FF YYYY");
                array_push($info,$tmp);
            }
        }
        $this->data['info'] = $info;
        $this->customjs = array('content');
        $this->generate_layout();
    }

}
