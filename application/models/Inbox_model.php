<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 30/08/20
 * Time: 3:05
 * @property CI_DB_query_builder db
 */

class Inbox_model extends CI_Model
{

    var $recData = array("inboxId"=>"","parentId"=>"","inboxFrom"=>"","inboxFromId"=>"",
        "inboxTitle"=>"","inboxDesc"=>"","inboxFile"=>"","inboxReadMember"=>"","inboxReadMemberDate"=>"",
        "inboxReadAdmin"=>"","inboxReadAdminDate"=>"","inboxCreateDate"=>"","inboxUpdateDate"=>"", ""
    );
    var $sort = 'ASC';
    var $lastInsertId;
    var $beginRec,$endRec;


    function insert_inbox($recData){
        $sql = "INSERT INTO _inbox 
				VALUES('','".$recData['parentId']."','".$recData['inboxFrom']."','".$recData['inboxFromId']."',
						'".$recData['inboxTitle']."','".$recData['inboxDesc']."','".$recData['inboxFile']."',
						'".$recData['inboxReadMember']."','".$recData['inboxReadMemberDate']."',
						'".$recData['inboxReadAdmin']."','".$recData['inboxReadAdminDate']."',NOW(),NOW())";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_inbox($opt="",$recData=array()){
        if($opt==""){
            $sql = "UPDATE _inbox 
					SET 
							parent_id 				= '".$recData['parentId']."', 
							inbox_from			= '".$recData['inboxFrom']."', 
							inbox_from_id		= '".$recData['inboxFromId']."', 
							inbox_title				= '".$recData['inboxTitle']."', 
							inbox_desc			= '".$recData['inboxDesc']."', 
							inbox_file				= '".$recData['inboxFile']."',
							inbox_read_member			= '".$recData['inboxReadMember']."', 
							inbox_read_member_date = '".$recData['inboxReadMemberDate']."',
							inbox_read_admin				= '".$recData['inboxReadAdmin']."', 
							inbox_read_admin_date 	= '".$recData['inboxReadAdminDate']."'
					WHERE inbox_id = '".$recData['inboxId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="memberRead"){
            $sql = "UPDATE _inbox 
					SET inbox_read_member			= 'read', 
							inbox_read_member_date = NOW(),
							inbox_update_date = NOW() 
					WHERE inbox_id = ('".$recData['inboxId']."' AND inbox_read_member_date = '0000-00-00 00:00:00')
							OR (parent_id = '".$recData['inboxId']."' AND inbox_read_member_date = '0000-00-00 00:00:00')";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="adminRead"){
            $sql = "UPDATE _inbox 
					SET inbox_read_admin			= 'read', 
							inbox_read_admin_date = NOW(),
							inbox_update_date = NOW() 
					WHERE inbox_id = ('".$recData['inboxId']."' AND inbox_read_admin_date = '0000-00-00 00:00:00') 
							OR (parent_id = '".$recData['inboxId']."' AND inbox_read_admin_date = '0000-00-00 00:00:00')";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="parentId"){
            $sql = "UPDATE _inbox SET parent_id = '".$recData['parentId']."' WHERE inbox_id = '".$recData['inboxId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
    }

    function delete_inbox($id){
        $sql = "DELETE FROM _inbox WHERE inbox_id IN(".$id.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_inbox($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT *, COUNT(*) AS TOTAL_CHAT FROM _inbox GROUP BY parent_id ORDER BY inbox_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $count = 0;
            $sql = "SELECT COUNT(*) AS TOTAL FROM _inbox GROUP BY parent_id ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(!empty($data)) $count = $data[0]['TOTAL'];
            return $count;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _inbox WHERE inbox_id = '".$recData['inboxId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _inbox 
						WHERE parent_id = '0' 
							AND inbox_from = 'member'  AND inbox_from_id = '".$recData['inboxFromId']."' 
						ORDER BY inbox_create_date ".$this->sort;
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByMemberId"){
            $count = 0;
            $sql = "SELECT COUNT(*) AS TOTAL  FROM _inbox 
						WHERE parent_id = '0' 
							AND inbox_from = 'member'  AND inbox_from_id = '".$recData['inboxFromId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(!empty($data)) $count = $data[0]['TOTAL'];
            return $count;
        }
        elseif($opt=="byParentId"){
            $sql = "SELECT * FROM _inbox 
						WHERE parent_id = '".$recData['parentId']."' 
						ORDER BY inbox_create_date ".$this->sort;
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countUnread"){
            $count = 0;
            $sql = "SELECT COUNT(*) as TOTAL
                    FROM `_inbox`
                    WHERE inbox_from = 'admin'
                          AND parent_id IN (SELECT parent_id FROM `_inbox` WHERE inbox_from_id='".$recData['memberId']."' AND parent_id=inbox_id)
                          AND inbox_read_member = 'unread'";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
        elseif($opt=="countByParentId"){
            $count = 0;
            $sql = "SELECT COUNT(*) AS TOTAL FROM _inbox 
						WHERE parent_id = '".$recData['parentId']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(!empty($data)) $count = $data[0]['TOTAL'];
            return $count;
        }
        elseif($opt=="getParentIdByMemberId"){
            $sql = "SELECT parent_id FROM _inbox 
						WHERE inbox_from = 'member' AND inbox_from_id = '".$recData['inboxFromId']."'
						GROUP BY parent_id 
						ORDER BY inbox_create_date ".$this->sort;
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countParentIdByMemberId"){
            $count = 0;
            $sql = "SELECT COUNT(parent_id) AS TOTAL FROM _inbox 
						WHERE inbox_from = 'member' AND inbox_from_id = '".$recData['inboxFromId']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(!empty($data)) $count = $data[0]['TOTAL'];
            return $count;
        }
    }

}