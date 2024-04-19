<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_prasyarat_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get($kompetensi_classroom_id){
        $this->db->select('_kompetensi_prasyarat.*,_classroom.cr_name,_category.cat_name');
        $this->db->from('_kompetensi_prasyarat');

        $this->db->join('_classroom', '_classroom.cr_id = _kompetensi_prasyarat.classroom_id', 'left');
        $this->db->join('_category', '_classroom.cat_id = _category.cat_id', 'left');

        $this->db->where('crm_id', $kompetensi_classroom_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_kompetensi_level($cr_id,$level){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_kompetensi_prasyarat');

        $this->db->join('_classroom', '_classroom.cr_id = _kompetensi_prasyarat.classroom_id', 'left');

        $this->db->where('_kompetensi_prasyarat.cr_id', $cr_id);
        $this->db->where('_kompetensi_prasyarat.level', $level);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_kompetensi($cr_id){
        $this->db->select('_kompetensi_prasyarat.*,_classroom.cr_name,_category.cat_name');
        $this->db->from('_kompetensi_prasyarat');

        $this->db->join('_classroom', '_classroom.cr_id = _kompetensi_prasyarat.classroom_id', 'left');
        $this->db->join('_category', '_classroom.cat_id = _category.cat_id', 'left');

        $this->db->where('_kompetensi_prasyarat.cr_id', $cr_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get_by_kompetensi_level($cr_id,$level){
        $this->db->select('_kompetensi_prasyarat.*,_classroom.cr_name,_category.cat_name');
        $this->db->from('_kompetensi_prasyarat');

        $this->db->join('_classroom', '_classroom.cr_id = _kompetensi_prasyarat.classroom_id', 'left');
        $this->db->join('_category', '_classroom.cat_id = _category.cat_id', 'left');

        $this->db->where('_kompetensi_prasyarat.cr_id', $cr_id);
        $this->db->where('_kompetensi_prasyarat.level', $level);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }



    function get_by_kompetensi_classroom_level($cr_id,$classroom_id,$level){
        $this->db->select('_kompetensi_prasyarat.*,_classroom.cr_name,_category.cat_name');
        $this->db->from('_kompetensi_prasyarat');

        $this->db->join('_classroom', '_classroom.cr_id = _kompetensi_prasyarat.classroom_id', 'left');
        $this->db->join('_category', '_classroom.cat_id = _category.cat_id', 'left');


        $this->db->where('_kompetensi_prasyarat.cr_id', $cr_id);
        $this->db->where('_kompetensi_prasyarat.classroom_id', $classroom_id);
        $this->db->where('_kompetensi_prasyarat.level', $level);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_kompetensi_prasyarat', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['crm_id'];
        unset($data['crm_id']);
        $this->db->where('crm_id', $id);
        $this->db->update('_kompetensi_prasyarat' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($kompetensi_classroom_id){
        $this->db->where('crm_id',$kompetensi_classroom_id);
        $this->db->delete('_kompetensi_prasyarat');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
