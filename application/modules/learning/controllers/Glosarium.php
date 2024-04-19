<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Glosarium extends MX_Controller {
    public $title = 'Glosarium';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['kamus_model']);
    }

    public function index(){
        $this->page = 'learning/glosarium';

        $this->data['datas'] = $this->kamus_model->select_kamus('all');
        $this->data['random'] = $this->kamus_model->select_kamus('random', array(), 5);
        $this->data['keyword'] = '';
        
        $this->customjs = array('glosarium');
        $this->generate_layout();
    }

    public function load_data(){

    }
}