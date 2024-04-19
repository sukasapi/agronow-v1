<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 05/09/20
 * Time: 4:34
 * @property CI_DB_query_builder db
 */

class Scrapper_model extends CI_Model
{
    function get_latest_data($label){
        $this->db->select('*');
        $this->db->from('_custom_data');
        $this->db->where(['label'=>$label]);
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

    function get_day_data($label, $day=0){
        if ($day){
            $today = date('Y-m-d', strtotime($day.' days'));
        } else {
            $today = date('Y-m-d');
        }
        $this->db->select('*');
        $this->db->from('_custom_data');
        $this->db->where(['label'=>$label, 'DATE(created_at)'=>$today]);
        $this->db->limit(1,0);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->row();
        if ($result){
            return json_decode($result->data, true);
        } else {
            return NULL;
        }
    }

    function insert_today_data($label, $raw_data){
        $data = ['data'=>json_encode($raw_data), 'label'=>$label];
        $this->db->insert('_custom_data', $data);
        return $this->db->insert_id();
    }


}