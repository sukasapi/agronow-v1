<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('user_is_login'))
{
    function user_is_login($redirect=TRUE)
    {
        $ci = &get_instance();

        if (!$ci->session->userdata('logged_in')){
            redirect('auth/login');
        }

    }
}


if ( !function_exists('user_id'))
{
    function user_id()
    {
        $ci = &get_instance();
        return $ci->session->userdata('id');
    }
}


if ( !function_exists('user_login_data'))
{
    function user_login_data($parameter=FALSE)
    {
        $ci = &get_instance();

        $session_data = $ci->session->userdata('user_login_data');

        if ($parameter!=FALSE) {
            if (isset($session_data[$parameter])) {
                return $session_data[$parameter];
            }else{
                return FALSE;
            }
        }else{
            return $session_data;
        }


    }
}


if(!function_exists('generate_user_token')){
    function generate_user_token(){
        return md5(uniqid(rand(), TRUE));
    }
}


if(!function_exists('encyrpt_password')){

    function encyrpt_password($password_text){
        $options = array('cost' => 12);

        $password = md5(strrev($password_text));
        $hash     = password_hash($password, PASSWORD_DEFAULT, $options);

        $result = array(
            'password'  => $password,
            'hash'      => $hash
        );

        return $result;
    }

}


if(!function_exists('verify_password')){

    function verify_password($password_text,$hash){
        $password = md5(strrev($password_text));
        if (password_verify($password, $hash)==TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}


if ( !function_exists('create_log'))
{
    function create_log($section_id,$data_id=NULL,$user_activity_type=NULL,$user_activity_desc=NULL)
    {
        $ci = &get_instance();

        $ci->load->model('user_activity_model');
        $user_id = $ci->session->userdata('id');


        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED'])){
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED'])){
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        }
        else if(isset($_SERVER['REMOTE_ADDR'])){
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        else{
            $ipaddress = 'UNKNOWN';
        }


        $data = array(
            'user_id'   => $user_id,
            'section_id'   => $section_id,
            'data_id'   => $data_id,
            'user_activity_type'   => $user_activity_type,
            'user_activity_desc'   => $user_activity_desc?$user_activity_desc:'',
            'user_activity_create_date'   => date('Y-m-d H:i:s'),
            'ip_address'    => $ipaddress
        );

        $ci->user_activity_model->insert($data);

    }
}



if ( !function_exists('has_access'))
{
    function has_access($access_code,$redirect=TRUE){

        
        $ci = &get_instance();
        $ci->load->model(array('access_model','user_level_access_model'));

        $access_by_code = $ci->access_model->get_by_code($access_code);
        if (!$access_by_code){
            redirect('404');
        }


        $access_id = $access_by_code['access_id'];

        $user_level_id = $ci->session->userdata('user_level_id');

        $user_level_access = $ci->user_level_access_model->get_by_level($user_level_id);


        if (!$user_level_access){
            $result = FALSE;
        }else{

            foreach ($user_level_access as $v){
                $arr_access[] = $v['access_id'];
            }

            if (in_array($access_id,$arr_access)){
                $result = TRUE;
            }else{
                $result = FALSE;
            }

        }



        if ($redirect==TRUE){
            if ($result==FALSE){
                redirect('404');
            }
        } else{
            return $result;
        }

    }
}



if ( !function_exists('is_my_group'))
{
    function is_my_group($group_id,$if_superadmin_return_true = TRUE){

        /*$ci = &get_instance();
        $user_group_id = $ci->session->userdata('group_id');
        $user_id_klien = $ci->session->userdata('id_klien');

        if ($ci->session->userdata('user_level_id')==1){
            if ($if_superadmin_return_true){
                return TRUE;
            }else{
                return FALSE;
            }
        }


        if ($user_group_id == $group_id){
            return TRUE;
        }else{
            return FALSE;
        }*/


        if (in_array($group_id,my_groups())){
            return TRUE;
        }else{
            return FALSE;
        }

    }
}


if ( !function_exists('my_groups'))
{
    function my_groups(){
        $ci = &get_instance();

        $ci->load->model('group_model');

        $group_id = $ci->session->userdata('group_id');
        $id_klien = $ci->session->userdata('id_klien');
        $groups = [];

        if (!$id_klien && !$group_id){
            // Get Semua Group
            $get_group = $ci->group_model->get_all();
            if ($get_group){
                foreach ($get_group['data'] as $v){
                    array_push($groups,$v['group_id']);
                }
            }
        }

        if($id_klien && !$group_id){
            // Get Group By Klien
            $get_group = $ci->group_model->get_by_klien($id_klien);
            if ($get_group){
                foreach ($get_group as $v){
                    array_push($groups,$v['group_id']);
                }
            }
        }

        if($group_id){
            // Get Group
            array_push($groups,$group_id);
        }

        return $groups;

    }
}


if ( !function_exists('my_klien'))
{
    function my_klien(){
        $ci = &get_instance();

        return $ci->session->userdata('id_klien');
    }
}



if ( !function_exists('is_superadmin'))
{
    function is_superadmin(){

        $ci = &get_instance();
        if ($ci->session->userdata('user_level_id')==1){
            return TRUE;
        }else{
            return FALSE;
        }


    }
}


if ( !function_exists('has_access_manage_all_member'))
{
    function has_access_manage_all_member(){

        if (has_access('member.manage_all',FALSE)){
            return TRUE;
        }else{
            return FALSE;
        }


    }
}

