<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_content_popular_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _content.*',FALSE);
        $this->db->from('_content');


        if ($param_query['filter_section']) {
            $this->db->where('_content.section_id',$param_query['filter_section']);
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


}
