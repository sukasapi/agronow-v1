<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_member_model extends CI_Model {
    function get_by_kompetensi_member($cr_id,$member_id){
        $this->db->select('_kompetensi_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_kompetensi_member');

        $this->db->join('_member', '_member.member_id = _kompetensi_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $cr_id);
        $this->db->where('_kompetensi_member.member_id', $member_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_kompetensi_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }
}