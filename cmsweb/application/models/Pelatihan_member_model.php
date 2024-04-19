<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelatihan_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($pelatihan_member_id){
        $this->db->select('_pelatihan_member.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_pelatihan_member');

        $this->db->join('_member', '_member.member_id = _pelatihan_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('pm_id', $pelatihan_member_id);
        $this->db->order_by('pm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_pelatihan($pelatihan_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_pelatihan_member');

        $this->db->join('_member', '_member.member_id = _pelatihan_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('pelatihan_id', $pelatihan_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_pelatihan($pelatihan_id){
        $this->db->select('_pelatihan_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_pelatihan_member');

        $this->db->join('_member', '_member.member_id = _pelatihan_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('pelatihan_id', $pelatihan_id);
        $this->db->order_by('pm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_pelatihan_member($pelatihan_id,$member_id){
        $this->db->select('_pelatihan_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_pelatihan_member');

        $this->db->join('_member', '_member.member_id = _pelatihan_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('pelatihan_id', $pelatihan_id);
        $this->db->where('_pelatihan_member.member_id', $member_id);
        $this->db->order_by('pm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_pelatihan_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['pm_id'];
        unset($data['pm_id']);
        $this->db->where('pm_id', $id);
        $this->db->update('_pelatihan_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($pelatihan_member_id){
        $this->db->where('pm_id',$pelatihan_member_id);
        $this->db->delete('_pelatihan_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
