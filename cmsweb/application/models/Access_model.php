<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_all(){
        $this->db->select('_access.*');
        $this->db->from('_access');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_code($code){
        $this->db->select('_access.*');
        $this->db->from('_access');

        $this->db->where('_access.access_code', $code);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_available_menu(){
        $this->db->select('_access.*');
        $this->db->from('_access');

        $this->db->group_by('menu_name');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }




    function insert($data){

        $this->db->insert('_access', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }


    function update($data){
        $id = $data['access_id'];
        unset($data['access_id']);
        $this->db->where('access_id', $id);
        $this->db->update('_access' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($access_id){
        $this->db->where('access_id',$access_id);
        $this->db->delete('_access');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
