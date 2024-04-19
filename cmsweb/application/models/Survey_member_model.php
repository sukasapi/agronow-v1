<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Survey_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($survey_member_id){
        $this->db->select('_survey_member.*,_group.group_name, _member.member_name, _member.member_image');
        $this->db->from('_survey_member');

        $this->db->join('_member', '_member.member_id = _survey_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('sm_id', $survey_member_id);
        $this->db->order_by('sm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_survey($survey_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_survey_member');

        $this->db->join('_member', '_member.member_id = _survey_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('survey_id', $survey_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_survey($survey_id){
        $this->db->select('_survey_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_survey_member');

        $this->db->join('_member', '_member.member_id = _survey_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('survey_id', $survey_id);
        $this->db->order_by('sm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_survey_member($survey_id,$member_id){
        $this->db->select('_survey_member.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_survey_member');

        $this->db->join('_member', '_member.member_id = _survey_member.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('survey_id', $survey_id);
        $this->db->where('_survey_member.member_id', $member_id);
        $this->db->order_by('sm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_survey_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['sm_id'];
        unset($data['sm_id']);
        $this->db->where('sm_id', $id);
        $this->db->update('_survey_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($survey_member_id){
        $this->db->where('sm_id',$survey_member_id);
        $this->db->delete('_survey_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
