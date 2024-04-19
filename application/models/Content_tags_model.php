<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_tags_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_by_name($tag_name){
        $this->db->select('_content_tags.*');
        $this->db->from('_content_tags');

        $this->db->where_in('tags_name', $tag_name);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }


    function insert($data){
        $this->db->insert('_content_tags', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['tags_id'];
        unset($data['tags_id']);
        $this->db->where('tags_id', $id);
        $this->db->update('_content' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($content_id){
        $this->db->where('content_id',$content_id);
        $this->db->delete('_content');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
