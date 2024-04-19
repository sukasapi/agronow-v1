<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Province_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }



    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _city.*',FALSE);
        $this->db->from('_city');

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

        $this->db->group_by('city_province');

        $query = $this->db->get();
        $result['data']     = $query->result_array();
        $result['count']    = $query->num_rows();
        $result['count_all']= $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get($id){
        $this->db->select('_city.*');
        $this->db->from('_city');

        $this->db->where('city_province', $id);
        $this->db->group_by('city_province');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



}
