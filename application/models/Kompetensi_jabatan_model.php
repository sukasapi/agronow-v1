<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kompetensi_jabatan_model extends CI_Model {
    function get_by_jabatan($jabatan_id){
        $this->db->select('_kompetensi_jabatan.*,_jabatan.jabatan_name,_group.group_name');
        $this->db->from('_kompetensi_jabatan');

        $this->db->join('_jabatan', '_jabatan.jabatan_id = _kompetensi_jabatan.jabatan_id', 'left');
        $this->db->join('_group', '_jabatan.group_id = _group.group_id', 'left');

        $this->db->where('_kompetensi_jabatan.jabatan_id', $jabatan_id);
        $this->db->order_by('crm_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }
}