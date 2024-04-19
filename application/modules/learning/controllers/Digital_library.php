<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Category_model category_model
 * @property Content_model content_model
 */
class Digital_library extends MX_Controller {
	public $title = 'Digital Library';
	public $menu = 'learning';
	private $section_id = 35;

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'member_model', 'media_model', 'content_type_model']);

        $this->data['title'] = $this->title;

        // get section_id by title
        $this->section_model->sectionName = $this->title;
        $section = $this->section_model->select_section('byName');
        if($section) $this->section_id = $section['section_id'];
    }

	public function index(){
		$memberId = $this->session->userdata('member_id');

		$cat_id = 0;
		$search = '';
		if($this->input->get('category')){
			$this->category_model->catAlias = $this->input->get('category');
			$category = $this->category_model->select_category('byAlias', $this->section_id);
			$cat_id = @$category['cat_id'];
			// $this->title .= ' - Category : '.@$category['cat_name'];
		}

		if($this->input->get('search')){
			$search = $this->input->get('search');
		}
		if($this->input->get('category')=='corporate-culture'){
			$this->data['title'] = 'corporate culture';
		}else{
			$this->data['title']='Digital Library';
		}
		$this->page = 'digital_library/index';

		$this->data['datas'] = $this->content_model->get_content_list_by_section_and_category($this->section_id, $cat_id, $search);
		$this->data['categories'] = $this->category_model->select_category('', $this->section_id);
		$this->data['search'] = $search;
		$this->data['category'] = $this->input->get('category');

		$mlevelId = $this->member_model->get_member_mlevelId($memberId);

		// recommended
		$this->data['recommended'] = array();
		foreach ($this->content_type_model->get_all() as $i => $ct) {
			$content_type_id = $ct['content_type_id'];

			$this->content_model->recData['sectionId'] = $this->section_id;
			$this->content_model->recData['contentTypeID'] = $content_type_id;
			$this->data['recommended'][$content_type_id] = $this->content_model->select_content("recommended","",5,$mlevelId);

			foreach ($this->data['recommended'][$content_type_id] as $j => $rmd) {
//				if($content_type_id == 3){
//                    $this->data['recommended'][$content_type_id][$j]['video'] = @$this->media_model->get_media('video', $this->section_id, $rmd['content_id'])[0];
//                }else{
//                    $this->data['recommended'][$content_type_id][$j]['image'] = $this->media_model->get_primary_image($this->section_id, $rmd['content_id']);
//                }
                $this->data['recommended'][$content_type_id][$j]['image'] = $this->media_model->get_primary_image($this->section_id, $rmd['content_id']);
            }
		}

		// latest
		$this->data['latest'] = array();
		foreach ($this->content_type_model->get_all() as $i => $ct) {
			$content_type_id = $ct['content_type_id'];

			$this->content_model->recData['contentTypeID'] = $content_type_id;
			$this->data['latest'][$content_type_id] = $this->content_model->get_latest_viewed($this->section_id,$memberId,5,'');
			foreach ($this->data['latest'][$content_type_id] as $j => $rmd) {
				$this->data['latest'][$content_type_id][$j]['cat_name'] = $this->category_model->get_cat_name($rmd['cat_id']);
//				if($content_type_id == 3){
//                    $this->data['latest'][$content_type_id][$j]['video'] = @$this->media_model->get_media('video', $this->section_id, $rmd['content_id'])[0];
//                }else{
//                    $this->data['latest'][$content_type_id][$j]['image'] = $this->media_model->get_primary_image($this->section_id, $rmd['content_id']);
//                }
                $this->data['latest'][$content_type_id][$j]['image'] = $this->media_model->get_primary_image($this->section_id, $rmd['content_id']);
            }
		}


		$this->data['back_url'] = site_url('learning');

		$this->generate_layout();
	}

	public function detail($contentAlias = ''){
        $this->data['back_url'] = site_url('learning/digital_library');
        $content = $this->content_model->get_content_detail($contentAlias, $this->section_id, $this->session->userdata('member_id'));
		
		if($content){
			$this->page = 'digital_library/detail'; // need switcher if document or audio or video

			$this->data['data'] = $content;

			$this->content_model->recData['sectionId'] = $this->section_id;
			$this->content_model->recData['contentTypeID'] = $this->data['data']['content_type_id'];

			$this->data['terkait'] = $this->content_model->select_content("publish",$this->data['data']['cat_id'],5);

			foreach ($this->data['terkait'] as $i => $tk) {
				$content_type_id = $tk['content_type_id'];

				$image_data = $this->media_model->get_primary_image($this->section_id, $tk['content_id']);

				if($image_data){
					$this->data['terkait'][$i]['image'] = $image_data['media_value'];
				}
				
				if($content_type_id == 4){
					$image_data = $this->media_model->get_media('video', $this->section_id, $tk['content_id'])[0];
					if($image_data){
						$image = $this->get_image_from_youtube_embed_link(@$image_data['media_value']);
						$this->data['terkait'][$i]['image'] = $image;
					}
				}
			}

			$this->data['title_terkait'] = $this->content_type_model->get_name_by_id($this->data['data']['content_type_id']).' Terkait';

			$this->customjs = array('audio_visualizer');

			$this->generate_layout();
		}else{
			redirect('learning/digital_library');
		}
	}

	public function preview($contentAlias = ''){
		$content = $this->content_model->get_content_detail($contentAlias, $this->section_id, $this->session->userdata('member_id'));
		
		if($content){
			$this->page = 'digital_library/preview';

			$this->data['data'] = $content;

			$this->generate_layout();
		}else{
			redirect('learning/digital_library');
		}
	}

	private function get_image_from_youtube_embed_link($link = ''){
		if(strpos($link, 'youtube') !== false && strpos($link, 'embed') !== false){
			$u = explode('embed', $link);
			if(isset($u[1])){
			    $video_id = @str_replace('/', '', $u[1]);
			    $link = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';
			}
		}
		return $link;
	}
}