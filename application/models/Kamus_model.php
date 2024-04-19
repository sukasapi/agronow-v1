<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kamus_model extends CI_Model {

    var $recData = array(
        "kamusId"=>"","kamusName"=>"","kamusDesc"=>"","kamusCreateDate"=>""
    );
    var $beginRec,$endRec;
    var $lastInsertId;
    
    public function insert_kamus($data){
        $sql = "INSERT INTO _kamus VALUES('','".$data['kamusName']."','".$data['kamusDesc']."',NOW())";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }
    
    public function update_kamus($data){
        $sql = "UPDATE _kamus
            SET kamus_name = '".$data['kamusName']."', 
            kamus_desc     = '".$data['kamusDesc']."'
            WHERE kamus_id = '".$data['kamusId']."' ";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }
    
    public function delete_kamus($kamusId){
        $sql = "DELETE FROM _kamus WHERE kamus_id = '".$kamusId."' ";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }
    
    public function select_kamus($opt="",$data=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _kamus ORDER BY kamus_name";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kamus";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _kamus WHERE kamus_id = '".$data['kamusId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
        elseif($opt=="random"){
            $sql = "SELECT * FROM _kamus ORDER BY RAND()";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="all"){
            $sql = "SELECT * FROM _kamus ORDER BY kamus_name";
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
    }
    
    function search_kamus($opt="",$keyword="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _kamus WHERE kamus_name LIKE '%".$keyword."%' ORDER BY kamus_name";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kamus WHERE kamus_name LIKE '%".$keyword."%'";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
    }
    
    function is_name_exists($kamusName){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _kamus WHERE kamus_name = '".$kamusName."' ";
        $data = $this->db->query($sql)->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }
}