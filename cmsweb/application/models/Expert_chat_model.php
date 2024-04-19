<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expert_chat_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    function get($expert_chat_id){
        $this->db->select('_expert_chat.*,_group.group_name, _member.member_name, _member.member_image,
        _expert_member.em_name,_expert_member.em_concern,_expert_member.em_image');
        $this->db->from('_expert_chat');

        $this->db->join('_expert_member', '_expert_member.em_id = _expert_chat.em_id', 'left');
        $this->db->join('_member', '_member.member_id = _expert_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('ec_id', $expert_chat_id);
        $this->db->order_by('ec_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function count_member_by_expert($expert_id){
        $this->db->select('COUNT(DISTINCT _expert_chat.member_id) as total');
        $this->db->from('_expert_chat');

        $this->db->join('_member', '_member.member_id = _expert_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('expert_id', $expert_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_member_by_expert($expert_id){
        $this->db->select('_expert_chat.*,_group.group_name, _member.member_name, _member.member_image,
        _expert_member.em_name,_expert_member.em_concern,_expert_member.em_image');
        $this->db->from('_expert_chat');

        $this->db->join('_expert_member', '_expert_member.em_id = _expert_chat.em_id', 'left');

        $this->db->join('_member', '_member.member_id = _expert_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('expert_id', $expert_id);
        $this->db->group_by('_expert_chat.member_id');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function count_by_expert($expert_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_expert_chat');

        $this->db->join('_member', '_member.member_id = _expert_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('expert_id', $expert_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_expert($expert_id){
        $this->db->select('_expert_chat.*,_group.group_name, _member.member_name, _member.member_image,
        _expert_member.em_name,_expert_member.em_concern,_expert_member.em_image');
        $this->db->from('_expert_chat');

        $this->db->join('_expert_member', '_expert_member.em_id = _expert_chat.em_id', 'left');

        $this->db->join('_member', '_member.member_id = _expert_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('expert_id', $expert_id);
        $this->db->order_by('ec_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function update($data){
        $id = $data['expert_id'];
        unset($data['expert_id']);
        $this->db->where('ec_id', $id);
        $this->db->update('_expert_chat' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($expert_chat_id){
        $this->db->where('ec_id',$expert_chat_id);
        $this->db->delete('_expert_chat');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
