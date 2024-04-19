<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($kompetensi_member_id){
        $this->db->select('_kompetensi_member.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_kompetensi_member');

        $this->db->join('_member', '_member.member_id = _kompetensi_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('crm_id', $kompetensi_member_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_kompetensi($cr_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_kompetensi_member');

        $this->db->join('_member', '_member.member_id = _kompetensi_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $cr_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_kompetensi($cr_id){
        $this->db->select('_kompetensi_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_kompetensi_member');

        $this->db->join('_member', '_member.member_id = _kompetensi_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $cr_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

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

    function update($data){
        $id = $data['crm_id'];
        unset($data['crm_id']);
        $this->db->where('crm_id', $id);
        $this->db->update('_kompetensi_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($kompetensi_member_id){
        $this->db->where('crm_id',$kompetensi_member_id);
        $this->db->delete('_kompetensi_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }


    function delete_bulk($cr_id,$member_ids){

        $this->db->where_in('_kompetensi_member.member_id',$member_ids);
        $this->db->where('_kompetensi_member.cr_id',$cr_id);
        $this->db->delete('_kompetensi_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
