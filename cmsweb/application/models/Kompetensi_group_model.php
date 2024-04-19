<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_group_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($kompetensi_group_id){
        $this->db->select('_kompetensi_group.*,_group.group_name,_group.group_name');
        $this->db->from('_kompetensi_group');

        $this->db->join('_group', '_group.group_id = _kompetensi_group.group_id', 'left');

        $this->db->where('crm_id', $kompetensi_group_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_kompetensi($cr_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_kompetensi_group');

        $this->db->join('_group', '_group.group_id = _kompetensi_group.group_id', 'left');

        $this->db->where('cr_id', $cr_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_kompetensi($cr_id){
        $this->db->select('_kompetensi_group.*,_group.group_name,_group.group_name');
        $this->db->from('_kompetensi_group');

        $this->db->join('_group', '_group.group_id = _kompetensi_group.group_id', 'left');

        $this->db->where('cr_id', $cr_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_group($group_id){
        $this->db->select('_kompetensi_group.*,_group.group_name,_group.group_name');
        $this->db->from('_kompetensi_group');

        $this->db->join('_group', '_group.group_id = _kompetensi_group.group_id', 'left');

        $this->db->where('_kompetensi_group.group_id', $group_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_kompetensi_group($cr_id,$group_id){
        $this->db->select('_kompetensi_group.*,_group.group_name,_group.group_name');
        $this->db->from('_kompetensi_group');

        $this->db->join('_group', '_group.group_id = _kompetensi_group.group_id', 'left');


        $this->db->where('cr_id', $cr_id);
        $this->db->where('_kompetensi_group.group_id', $group_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_kompetensi_group', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['crm_id'];
        unset($data['crm_id']);
        $this->db->where('crm_id', $id);
        $this->db->update('_kompetensi_group' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($kompetensi_group_id){
        $this->db->where('crm_id',$kompetensi_group_id);
        $this->db->delete('_kompetensi_group');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
