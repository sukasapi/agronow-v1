<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Function_api function_api
 * @property Scrapper_model scrapper_model
 */
class Whatsnew extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->model(['content_model', 'scrapper_model']);
        $this->load->library(['function_api','content_model']);
    }

	function index(){
        $this->data['data'] = [
            'news'          => '-',
            'article'       => '-',
            'commodity'     => '-',
            'exchange_rate' => '-',
            'announcement'  => '-',
            'ceo_note'      => '-',
            'bod_share'      => '-'
        ];
        // News
        $this->content_model->recData['sectionId'] = 12;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['news'] = $desc;
        }
        // Article
        $this->content_model->recData['sectionId'] = 13;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['article'] = $desc;
        }
        // Commodity
        $result = $this->scrapper_model->get_latest_data('commodity');
        if ($result){
            $i=0;
            $com = '';
            foreach ($result as $key=>$val){
                $i++;
                $com .= $key.' - '.(isset($val[0])?$val[0]:'').' - '.(isset($val[1])?$val[1]:'').' - '.(isset($val[2])?$val[2]:'');
                if ($i>=10) break;
                $com .= ' | ';
            }
            $this->data['data']['commodity'] = $com;
        }
        // Exchange rate
        $result = $this->scrapper_model->get_latest_data('datakurs');
        if ($result){
            $i=0;
            $com = '';
            foreach ($result as $key=>$val){
                $i++;
                $com .= $key.' - '.(isset($val['buy'])?$val['buy']:'').' - '.(isset($val['sell'])?$val['sell']:'').' - '.(isset($val['value'])?$val['value']:'');
                if ($i>=10) break;
                $com .= ' | ';
            }
            $this->data['data']['exchange_rate'] = $com;
        }
        // Announcement
        $this->content_model->recData['sectionId'] = 22;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
//            $desc = $this->function_api->excerpt($result[0]['content_desc'], 200);
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['announcement'] = $desc;
        }
        // Ceo Note
        $this->content_model->recData['sectionId'] = 34;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['ceo_note'] = $desc;
        }
        // BOD Share
        $this->content_model->recData['sectionId'] = 42;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['bod_share'] = $desc;
        }

        // POP UP
        $this->content_model->recData['sectionId'] = 40;
        $result = $this->content_model->select_content("publish","",1);
        if ($result) {
            $desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['pup_up'] = $desc;
        }

		$this->data['title'] = 'What&apos;s New';
		$this->page = 'whatsnew';
		$this->menu = 'whatsnew';
		$this->customjs = array('header_notification');
		$this->generate_layout();
	}
}
