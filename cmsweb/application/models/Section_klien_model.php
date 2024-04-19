<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Section_klien_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _section_klien.*,_klien.nama as nama_klien',FALSE);
        $this->db->from('_section_klien');
        $this->db->join('_klien', '_klien.id = _section_klien.id_klien','LEFT');

        // Keyword By
        if ($keyword!=NULL) {
            if (is_array($param_query['keyword_by'])) {
                foreach ($param_query['keyword_by'] as $k => $v) {
                    $this->db->like($k,$v);
                }
            } else{
                $this->db->like($param_query['keyword_by'],$keyword);
            }
        }


        $this->db->limit($limit,$offset);
        if (isset($param_query['sort'])) {
            $this->db->order_by($param_query['sort'],$param_query['sort_order']);
        }

        $query = $this->db->get();
        $result['data']     = $query->result_array();
        $result['count']    = $query->num_rows();
        $result['count_all']= $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }



    function get_by_section_data($section_id, $data_id, $source){
        $this->db->select('_section_klien.*,_klien.nama as nama_klien');
        $this->db->from('_section_klien');
        $this->db->where('section_id',$section_id);
        $this->db->where('data_id',$data_id);
        $this->db->where('source',$source);

        $this->db->join('_klien', '_klien.id = _section_klien.id_klien','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_member($member_id){
        $this->db->select('_member.*,_klien.nama as nama_klien, _klien.id as id_klien');
        $this->db->from('_member');
        $this->db->where('_member.member_id',$member_id);

        $this->db->join('_group', '_group.group_id = _member.group_id','LEFT');
        $this->db->join('_klien', '_klien.id = _group.id_klien','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_section_klien', $data);
        return $this->db->affected_rows() > 0 ?  TRUE : FALSE;
    }

    function delete_by_section_data($section_id,$data_id){
        $this->db->where('section_id',$section_id);
        $this->db->where('data_id',$data_id);
        $this->db->delete('_section_klien');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
