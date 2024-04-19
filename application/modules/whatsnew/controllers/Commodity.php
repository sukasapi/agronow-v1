<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Scrapper_model scrapper_model
 */
class Commodity extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
		$this->load->model(['scrapper_model']);
    }

	function index(){
        $commodity = $this->scrapper_model->get_day_data('commodity');
		$result = $commodity;
		$this->data['title'] = 'Commodity';
		$this->page = 'commodity';
		$this->menu = 'whatsnew';
		$this->data['data'] = $result;
		$this->data['date'] = date('d M Y');
		$this->generate_layout();
	}
}
