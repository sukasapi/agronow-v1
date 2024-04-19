<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MX_Controller {
	public $title = 'Attendance';
	public $menu = 'learning';

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'culture_model', 'classroom_model', 'forum_model']);
    }

	function index(){
		$this->data['title'] = $this->title;
		$this->customjs = array('qrcode-scanner');
		$this->page = 'attendance';
		$this->generate_layout();
	}
}
