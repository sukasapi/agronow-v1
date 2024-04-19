<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function count(){
        $this->db->select('count(*) as total_member, MONTH(member_create_date) as month, YEAR(member_create_date) as year ');
        $this->db->from('_member');

        $this->db->group_by('MONTH(member_create_date),YEAR(member_create_date)');
        $this->db->order_by('member_create_date', 'ASC');

        $query = $this->db->get();
        $result  = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }





}
