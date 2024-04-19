<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Member_model member_model
 * @property Content_model content_model
 * @property Inbox_model inbox_model
 */
class Settings extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['member_model', 'auth_model', 'group_model', 'media_model', 'forum_model', 'content_model', 'inbox_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index(){
		$this->data['title'] = 'Settings';
		$this->page = 'settings';
		$this->menu = 'account';
		$this->generate_layout();
	}
}
