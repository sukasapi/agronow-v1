<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_type_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    public function get_all(){
        return $this->db->get('_content_type')->result_array();
    }

    public function get_name_by_id($content_type_id = ''){
    	$this->db->select('content_type_name');
    	$this->db->where('content_type_id', $content_type_id != '' ? $content_type_id : 1);
        $data = $this->db->get('_content_type', 1)->row_array();
        if($data){
        	return $data['content_type_name'];
        }else{
        	return '';
        }
    }
}
