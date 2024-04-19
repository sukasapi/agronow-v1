<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Inbox_model inbox_model
 * @property Function_api function_api
 * @property Member_model member_model
 */
class History extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['member_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index(){
        $recData['memberId'] = $this->member_id;
        $this->data['data']['poin_history'] = $this->member_model->select_member_poin('byMemberId', $recData, 20);
        $this->data['data']['saldo_history'] = $this->member_model->select_member_saldo('byMemberId', $recData, 20);
        $this->data['title']    = 'History';
		$this->page             = 'history';
		$this->menu             = 'account';
		$this->generate_layout();
	}
}
