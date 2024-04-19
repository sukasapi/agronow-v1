<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class custom404 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //user_is_login();
    }

    public function index()
    {
        $this->output->set_status_header('404');
        $data['page_name'] = 'Page Not Found';
        $data['page_sub_name'] = '';
        $data['page'] = 'custom404view';  // View name
        $this->load->view('main_view', $data);
    }
}