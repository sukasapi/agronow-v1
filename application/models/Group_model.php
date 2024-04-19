<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 09/08/20
 * Time: 16:37
 * @property CI_DB_query_builder db
 */

class Group_model extends CI_Model
{ 
    var $recData = array("groupId"=>"","groupName"=>"","groupAlias"=>"","groupImage"=>"",
        "groupHasLevel"=>"","groupPortal"=>"","groupStatus"=>"","groupCreateDate"=>"");

    var $lastInsertId;
    var $beginRec,$endRec;

    function select_admin_group(){
        $sql = "SELECT * FROM _group 
				WHERE group_id IN (SELECT DISTINCT(user_code) FROM _user WHERE user_code != '') 
				ORDER BY group_id";
        $data = $this->doQuery($sql);
        return $data;
    }

    function insert_group($recData){
        $sql = "INSERT INTO _group 
				VALUES('','".$recData['groupName']."','".$recData['groupAlias']."','".$recData['groupImage']."',
						'".$recData['groupHasLevel']."','".$recData['groupPortal']."',
						'".$recData['groupStatus']."',NOW())";
        $result = $this->execute($sql);
        return $result;
    }

    function update_group($recData){
        $sql = "UPDATE _group 
				SET group_name	= '".$recData['groupName']."', 
					group_alias	= '".$recData['groupAlias']."', 
					group_image	= '".$recData['groupImage']."', 
					group_has_level	= '".$recData['groupHasLevel']."', 
					group_portal = '".$recData['groupPortal']."', 
					group_status= '".$recData['groupStatus']."' 
				WHERE group_id = '".$recData['groupId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function delete_group(){
        $sql = "DELETE FROM _group WHERE group_id = '".$this->recData['groupId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_group($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _group ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _group WHERE group_id = '".$this->recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
		elseif($opt=="detail_group_klien_by_id_group"){
            $sql = "select g.*, k.id as id_klien, k.nama as nama_klien, k.kategori as kategori_klien from _klien k, _group g where k.id=g.id_klien and g.group_id='".$this->recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
		elseif($opt=="by_aghris_company_code"){
            $sql = "SELECT * FROM _group WHERE aghris_company_code = '".$this->recData['aghris_company_code']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _group";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _group WHERE group_status = 'active'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _group WHERE group_status = 'active' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _group WHERE group_alias = '".$this->recData['groupAlias']."' ORDER BY group_name";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByAlias"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _group WHERE group_alias = '".$this->recData['groupALias']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byAliasActive"){
            $sql = "SELECT * FROM _group 
					WHERE group_alias = '".$this->recData['groupAlias']."' 
						AND group_status = 'active' 
					ORDER BY group_name";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByAliasActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _group 
					WHERE group_alias = '".$this->recData['groupALias']."' 
						AND group_alias = 'active' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="portalActive"){
            $sql = "SELECT * FROM _group WHERE group_portal = '1' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="registerList"){
            $sql = "SELECT * FROM _group 
					WHERE group_status = 'active' 
						AND group_portal = '0' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    function is_group_portal_active($groupId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _group WHERE group_portal = '1' AND group_Id = '".$groupId."' ";
        $query = $this->db->query($sql);
        $data = $query->row();
        if($data->TOTAL>0){
            $result = true;
        }
        return $result;
    }

    function get_group_name($id){
        $result = "";
        $sql = "SELECT * FROM _group WHERE group_id IN('".$id."')";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        for($i=0;$i<count($data);$i++){
            if($i>0) $result .= ", ";
            $result .= $data[$i]['group_name'];
        }
        return $result;
    }

    function is_name_exists($groupName){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _group WHERE group_name = '".$groupName."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }
}