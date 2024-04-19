<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'user_model',
        ));

        $this->section_id = 3;

    }


    function get_user($user_id){
        $get_user = $this->user_model->get($user_id);
        if ($get_user==FALSE){
            redirect(404);
        }else{
            return $get_user;
        }
    }

    function check_password($password){
        $get_user = $this->user_model->get(user_id());
        $current_password = $get_user['user_password'];
        $input_password = md5(md5(trim($password)));

        if ($current_password == $input_password){
            return TRUE;
        }else{
            $this->form_validation->set_message('check_password', 'Password Saat Ini tidak sesuai');
            return FALSE;
        }

    }

    function change_password(){
        $user = $this->get_user(user_id());

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('profile/change_password/'.user_id());
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Password', 'required|callback_check_password');
        $this->form_validation->set_rules('password_new', 'New Password', 'required');
        $this->form_validation->set_rules('password_new_confirm', 'Confirm New Password', 'required|matches[password_new]');

        if ($this->form_validation->run() == FALSE){

            $data['request']            = $user;
            $data['user']               = $user;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('profile/change_password');
            $data['editable']           = TRUE;
            $data['page_name']          = "Profile";
            $data['page_sub_name']      = 'Ganti Password';
            $data['page']               = 'profile/profile_form_change_password_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'user_id'  => user_id(),
                'user_password'   => md5(md5(trim($post['password_new']))),
            );


            $edit = $this->user_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,user_id(),'Edit','Password');
                flash_notif_success('Password berhasil diganti',$url_return);
            }else{
                $url_return = site_url('profile/change_password/');
                $msg = 'Tidak ada perubahan data';
                flash_notif_warning($msg,$url_return);
            }

        }
    }





}