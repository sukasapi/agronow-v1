<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_setting_monthly_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($id){
        $this->db->select('_member_poin_setting_monthly.*');
        $this->db->from('_member_poin_setting_monthly');

        $this->db->where('mps_monthly_id', $id);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_mps($mps_id){
        $this->db->select('_member_poin_setting_monthly.*');
        $this->db->from('_member_poin_setting_monthly');

        $this->db->where('mps_id', $mps_id);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_member_poin_setting_monthly', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['mps_monthly_id'];
        unset($data['mps_monthly_id']);
        $this->db->where('mps_monthly_id', $id);
        $this->db->update('_member_poin_setting_monthly' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($mps_monthly_id){
        $this->db->where('mps_monthly_id',$mps_monthly_id);
        $this->db->delete('_member_poin_setting_monthly');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

}