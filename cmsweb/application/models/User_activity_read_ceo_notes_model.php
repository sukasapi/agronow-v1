<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_activity_read_ceo_notes_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_content_by_section(){
        $this->db->select('_content.content_id, _content.section_id, _content.content_name, _content.content_author');
        $this->db->from('_content');

        $this->db->where('_content.section_id','34');

        $this->db->group_by('_content.content_id');
        $this->db->order_by('_content.content_id', 'desc');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get_counter_by_content_group(){
        $this->db->select('_content_hits.content_id, _content.section_id, _content.content_name, _content.content_author, 
        _content_hits.member_id, _group.group_id, _group.group_name, COUNT(*) AS total_view');
        $this->db->from('(SELECT * FROM _content_hits group by content_id, member_id) as _content_hits');
        $this->db->join('_content', '_content.content_id = _content_hits.content_id', 'left');
        $this->db->join('_member', '_member.member_id = _content_hits.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');
        $this->db->where('_content.section_id','34');

        $this->db->group_by('_group.group_id, _content_hits.content_id');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


}
