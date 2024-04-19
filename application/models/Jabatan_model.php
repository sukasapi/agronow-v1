<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 23/02/21
 * Time: 22:14
 * @property CI_DB_query_builder db
 */

class Jabatan_model extends CI_Model
{
    public function select_jabatan_by_code($code){
        $sql = "SELECT * FROM _jabatan WHERE jabatan_code='".$code."'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    public function insert_jabatan($data){
        $this->db->insert('_jabatan', $data);
        return $this->db->insert_id();
    }

}