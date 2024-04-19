<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_content_download_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function count_content_download(){
        $this->db->select('_content_download.*, COUNT(*) as total_download,
        _content.content_name, _content.section_id, _content.content_publish_date');
        $this->db->from('_content_download');

        $this->db->join('_content', '_content.content_id = _content_download.content_id', 'left');

        $this->db->group_by('_content_download.content_id');
        $this->db->order_by('total_download', 'desc');

        $this->db->limit(20);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


}
