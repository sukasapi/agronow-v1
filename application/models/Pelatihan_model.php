<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 01/09/20
 * Time: 18:52
 * @property CI_DB_query_builder db
 */

class Pelatihan_model extends CI_Model
{
    var $recData = array("pelatihanId"=>"","pelatihanName"=>"","pelatihanDesc"=>"","pelatihanQrcode"=>"",
        "pelatihanImage"=>"","pelatihanLocation"=>"","pelatihanDateStart"=>"","pelatihanDateEnd"=>"",
        "pelatihanDateDetail"=>"","pelatihanStatus"=>"","pelatihanCreateDate"=>"",

        "pmId"=>"","memberId"=>"","pmChannel"=>"","pmCreateDate"=>"",
    );
    var $beginRec,$endRec;
    var $lastInsertId;

    function insert_pelatihan($data){
        $sql = "INSERT INTO _pelatihan 
				VALUES( '','".$data['pelatihanName']."','".$data['pelatihanDesc']."','".$data['pelatihanQrcode']."',
						'".$data['pelatihanImage']."','".$data['pelatihanLocation']."','".$data['pelatihanDateStart']."',
						'".$data['pelatihanDateEnd']."','".$data['pelatihanDateDetail']."',
						'".$data['pelatihanStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_pelatihan($opt="",$data,$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _pelatihan 
					SET pelatihan_name		= '".$data['pelatihanName']."', 
						pelatihan_desc		= '".$data['pelatihanDesc']."', 
						pelatihan_qrcode	= '".$data['pelatihanQrcode']."', 
						pelatihan_image		= '".$data['pelatihanImage']."', 
						pelatihan_location	= '".$data['pelatihanLocation']."', 
						pelatihan_date_start= '".$data['pelatihanDateStart']."', 
						pelatihan_date_end	= '".$data['pelatihanDateEnd']."', 
						pelatihan_date_detail	= '".$data['pelatihanDateDetail']."', 
						pelatihan_status	= '".$data['pelatihanStatus']."' 
					WHERE pelatihan_id = '".$data['pelatihanId']."' ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
        elseif($opt=="qrcode"){
            $sql = "UPDATE _pelatihan SET pelatihan_qrcode = '".$this->recData['pelatihanQrcode']."' 
					WHERE pelatihan_id = '".$this->recData['pelatihanId']."'";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
    }

    function delete_pelatihan($pelatihanId){
        $sql = "DELETE FROM _pelatihan WHERE pelatihan_id IN(".$pelatihanId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_pelatihan($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _pelatihan ORDER BY pelatihan_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _pelatihan";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _pelatihan WHERE pelatihan_id = '".$this->recData['pelatihanId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data ? $data[0] : '';
        }
        elseif($opt=="incoming"){
            $sql = "SELECT * FROM _pelatihan WHERE pelatihan_date_start > NOW() 
					ORDER BY pelatihan_date_start ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countIncoming"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _pelatihan 
					WHERE pelatihan_date_start > NOW() ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="outgoing"){
            $sql = "SELECT * FROM _pelatihan 
					WHERE pelatihan_date_end < NOW() 
					ORDER BY pelatihan_date_end DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countOutgoing"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _pelatihan 
					WHERE pelatihan_date_end < NOW() ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }


    function insert_pelatihan_member($data){
        $sql = "INSERT INTO _pelatihan_member 
				VALUES('','".$data['pelatihanId']."','".$data['memberId']."','".$data['pmChannel']."',NOW())"; //echo $sql;exit;
        $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_pelatihan_member($data){
        $sql = "UPDATE _pelatihan_member 
				SET pelatihan_id = '".$data['pelatihanId']."', 
					member_id 	= '".$data['memberId']."', 
					pm_channel 	= '".$data['pmChannel']."' 
				WHERE pm_id = '".$data['pmId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_pelatihan_member($pmId){
        $sql = "DELETE FROM _pelatihan_member WHERE pm_id IN(".$pmId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_pelatihan_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * 
					FROM _pelatihan_member a, _pelatihan b, _member c, _group d 
					WHERE a.pelatihan_id = b.pelatihan_id 
						AND a.member_id = c.member_id 
						AND c.group_id = d.group_id 
						AND a.pelatihan_id = '".$this->recData['pelatihanId']."' 
					ORDER BY pm_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL  
					FROM _pelatihan_member a, _pelatihan b, _member c, _group d  
					WHERE a.pelatihan_id = b.pelatihan_id 
						AND a.member_id = c.member_id 
						AND c.group_id = d.group_id 
						AND a.pelatihan_id = '".$this->recData['pelatihanId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function is_absensi_exists($memberId,$pelatihanId,$start,$end){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _pelatihan_member 
				WHERE member_id = '".$memberId."' AND pelatihan_id = '".$pelatihanId."' 
					AND DATE_FORMAT(pm_create_date,'%Y-%m-%d') = '".date('Y-m-d')."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

}