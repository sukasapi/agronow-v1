<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_group_chat_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    function get($forum_chat_id){
        $this->db->select('_forum_group_chat.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_forum_group_chat');

        $this->db->join('_member', '_member.member_id = _forum_group_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('fc_id', $forum_chat_id);
        $this->db->order_by('fc_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function count_member_by_forum($forum_id){
        $this->db->select('COUNT(DISTINCT _forum_group_chat.member_id) as total');
        $this->db->from('_forum_group_chat');

        $this->db->join('_member', '_member.member_id = _forum_group_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('forum_id', $forum_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_member_by_forum($forum_id){
        $this->db->select('_forum_group_chat.member_id, _group.group_name, _member.member_name, _member.member_image, _member.member_nip');
        $this->db->from('_forum_group_chat');

        $this->db->join('_member', '_member.member_id = _forum_group_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('forum_id', $forum_id);
        $this->db->group_by('_forum_group_chat.member_id');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function count_by_forum($forum_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_forum_group_chat');

        $this->db->join('_member', '_member.member_id = _forum_group_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('forum_id', $forum_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_forum($forum_id){
        $this->db->select('_forum_group_chat.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_forum_group_chat');

        $this->db->join('_member', '_member.member_id = _forum_group_chat.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('forum_id', $forum_id);
        $this->db->order_by('fc_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function update($data){
        $id = $data['fc_id'];
        unset($data['fc_id']);
        $this->db->where('fc_id', $id);
        $this->db->update('_forum_group_chat' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($forum_chat_id){
        $this->db->where('fc_id',$forum_chat_id);
        $this->db->delete('_forum_group_chat');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
