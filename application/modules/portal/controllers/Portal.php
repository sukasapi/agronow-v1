<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Group_model group_model
 * @property Content_model content_model
 * @property Media_model media_model
 * @property Function_api function_api
 */
class Portal extends MX_Controller {

    protected $member_id, $group_id;

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
    }

	function index(){
        $this->group_model->recData['groupId'] = $this->group_id;
        $group = $this->group_model->select_group('byId');

		$this->data['title'] = 'Portal';
		$this->data['group'] = $group;
		$this->page = 'portal';
		$this->menu = 'portal';
		$this->generate_layout();
	}
}
