<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level_access_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_by_level($user_level_id){
        $this->db->select('_user_level_access.*'); 
        $this->db->from('_user_level_access');

        $this->db->where('_user_level_access.user_level_id', $user_level_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function insert_batch($data){

        $this->db->insert_batch('_user_level_access', $data);
        return $this->db->affected_rows() > 0 ?  TRUE : FALSE;

    }


    function delete_by_level($user_level_id){
        $this->db->where('user_level_id',$user_level_id);
        $this->db->delete('_user_level_access');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
