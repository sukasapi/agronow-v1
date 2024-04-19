<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kurs_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_last(){
        $this->db->select('*');
        $this->db->from('_custom_data');
        $this->db->where(['label'=>'datakurs']);
        $this->db->where('data != "[]"');
//        $this->db->limit($limit,0);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->row();
        if ($result){
            return json_decode($result->data, true);
        } else {
            return NULL;
        }
    }


}
