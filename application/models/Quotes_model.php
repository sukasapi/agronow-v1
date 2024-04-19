<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 30/08/20
 * Time: 21:35
 * @property CI_DB_query_builder db
 */

class Quotes_model extends CI_Model
{
    var $recData = array("quotesId"=>"","quotesText"=>"","quotesAuthor"=>"","quotesCreateDate"=>"");

    var $lastInsertId;
    var $beginRec,$endRec;


    function insert_quotes($recData){
        $sql = "INSERT INTO _quotes VALUES('','".$recData['quotesText']."','".$recData['quotesAuthor']."',NOW())";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function delete_quotes($id){
        $sql = "DELETE FROM _quotes WHERE quotes_id IN(".$id.")";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function select_quotes($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _quotes ORDER BY quotes_create_date DESC 
					LIMIT ".$this->beginRec.",".$this->endRec;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _quotes";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _quotes WHERE quotes_id = '".$this->recData['quotesId']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="random"){
            $sql = "SELECT * FROM _quotes ORDER BY RAND() LIMIT ".$limit;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

}