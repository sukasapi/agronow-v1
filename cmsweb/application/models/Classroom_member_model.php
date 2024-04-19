<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($classroom_member_id){
        $this->db->select('_classroom_member.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_classroom_member');

        $this->db->join('_member', '_member.member_id = _classroom_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('crm_id', $classroom_member_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_classroom($cr_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_classroom_member');

        $this->db->join('_member', '_member.member_id = _classroom_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $cr_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_classroom($cr_id){
        $this->db->select('_classroom_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image, _klien.nama as nama_klien');
		$this->db->from('_classroom_member');
		
		$this->db->join('_member', '_member.member_id = _classroom_member.member_id', 'left');
		// $this->db->join('_group', '_group.group_id = _member.group_id', 'left');
		$this->db->join('_group', '_group.group_id = _classroom_member.id_group', 'left');
		$this->db->join('_klien', '_group.id_klien = _klien.id');

        $this->db->where('cr_id', $cr_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_classroom_member($cr_id,$member_id){
        $this->db->select('_classroom_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_classroom_member');

        $this->db->join('_member', '_member.member_id = _classroom_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $cr_id);
        $this->db->where('_classroom_member.member_id', $member_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_classroom_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['crm_id'];
        unset($data['crm_id']);
        $this->db->where('crm_id', $id);
        $this->db->update('_classroom_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($classroom_member_id){
        $this->db->where('crm_id',$classroom_member_id);
        $this->db->delete('_classroom_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    /// FUNCTION :UPDATE MEMBER STATUS
    //  AUTH : KDW
    //  DATE : 05.09.2023
    function update_member_class($where=null,$data=null){
      
        $this->db->where($where);
        $query= $this->db->update('_classroom_member' ,$data);
        if($query){
            return "ok";
        }else{
            return $this->db->error();
        }
    }


}
