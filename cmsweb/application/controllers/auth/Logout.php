<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

    }


    public function index(){
        create_log(3,user_id(),'Logout',NULL);

        $this->session->sess_destroy();
        redirect(site_url('auth/login'));
    }


}
