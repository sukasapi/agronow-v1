<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getCategory')){
    function getCategory($category_id,$section_id){
        $ci = &get_instance();
        $ci->load->model('category_model');

        $get_category = $ci->category_model->get($category_id,$section_id);
        if ($get_category==FALSE){
            redirect(404);
        }else{
            return $get_category;
        }
    }
}