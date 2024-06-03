<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }


    // VALIDATION CHECK ID IN DATABASE
    public function is_login_valid(){
        $post = $this->input->post();

        if (empty($post)||$post==NULL) {
            redirect('auth/login');
        }

        $email = $post['email'];
        $password = $post['password'];
        sleep(1);
        $get_user = $this->user_model->login($email,$password);
        if ($get_user!=FALSE) {
            return TRUE; 
        }else{
            $message = "Invalid Email or Password";

            $this->form_validation->set_message('is_login_valid', $message);

            return FALSE;
        }
    }
    // VALIDATION CHECK ID IN DATABASE


    public function index(){

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('auth/login');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|callback_is_login_valid');

        if ($this->form_validation->run() == FALSE){
            $data['form_action']        = site_url('auth/login');
            $data['editable']           = FALSE;
            $data['is_content_header']  = FALSE;
            $data['page_name']          = 'Login' ;
            $data['page']               = 'auth/login_form_view';
            $this->load->view('main_auth_view',$data);
        }else{
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            $get_user = $this->user_model->login($email,$password);

            $data_user = array(
                'id'        => $get_user['user_id'],
                'name'      => $get_user['user_name'],
                'email'     => $email,
                'group_id'  => $get_user['user_code'],
                'group_name'  => $get_user['group_name'],
                'id_klien'  => $get_user['id_klien'],
                'klien_nama'  => $get_user['klien_nama'],
                'user_level_id'  => $get_user['user_level_id'],
                'logged_in' => TRUE
            );

            $this->session->set_userdata($data_user);

            create_log(3,$get_user['user_id'],'Login',NULL);
            redirect(site_url('dashboard'));
        }



    }





}
