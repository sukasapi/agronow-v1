<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 09/08/20
 * Time: 16:37
 * @property CI_DB_query_builder db
 */

class ForumGroup_model extends CI_Model
{
    var $recData = array("forumId"=>"","catId"=>"","userId"=>"","memberId"=>"","groupId"=>"",
        "forumName"=>"","forumDesc"=>"","forumSticky"=>"","forumStatus"=>"",
        "forumCreateDate"=>"","forumUpdateDate"=>"","forumCloseDate"=>"",
        "forumCloseBy"=>"","forumCloseReason"=>"","fcId"=>"","fcDesc"=>"",
        "fcImage"=>"","fcStatus"=>"","fcCreateDate"=>""
    );

    var $lastInsertId;
    var $beginRec,$endRec;

    // FORUM START //
    function insert_forum($recData){
        $data = [
            'cat_id'    => $recData['catId'],
            'user_id'   => $recData['userId'],
            'member_id' => $recData['memberId'],
            'group_id'  => $recData['groupId'],
            'forum_name'=> $recData['forumName'],
            'forum_alias'   => $recData['forumAlias'],
            'forum_desc'    => $recData['forumDesc'],
            'forum_sticky'  => $recData['forumSticky'],
            'forum_status'  => $recData['forumStatus'],
            'forum_create_date' => date('Y-m-d H:i:s'),
            'forum_update_date' => date('Y-m-d H:i:s'),
            'forum_close_date'  => $recData['forumCloseDate'],
            'forum_close_by'    => $recData['forumCloseBy'],
            'forum_close_reason'=> $recData['forumCloseReason']
        ];
        $this->db->insert('_forum_group', $data);
//        $sql = "INSERT INTO _forum_group
//				VALUES( '','".$recData['catId']."','".$recData['userId']."','".$recData['memberId']."',
//						'".$recData['groupId']."','".$recData['forumName']."','".$recData['forumAlias']."',
//						'".$recData['forumDesc']."','".$recData['forumSticky']."','".$recData['forumStatus']."',
//						NOW(),NOW(),'".$recData['forumCloseDate']."','".$recData['forumCloseBy']."',
//						'".$recData['forumCloseReason']."')";
//        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_forum($opt="",$recData=array()){
        if($opt==""){
            $sql = "UPDATE _forum_group 
					SET cat_id				= '".$recData['catId']."', 
						user_id				= '".$recData['userId']."', 
						member_id			= '".$recData['memberId']."', 
						group_id			= '".$recData['groupId']."', 
						forum_name		 	= '".$recData['forumName']."', 
						forum_alias		 	= '".$recData['forumAlias']."', 
						forum_desc			= '".$recData['forumDesc']."', 
						forum_sticky		= '".$recData['forumSticky']."', 
						forum_status		= '".$recData['forumStatus']."', 
						forum_close_date 	= '".$recData['forumCloseDate']."', 
						forum_close_by 		= '".$recData['forumCloseBy']."', 
						forum_close_reason	= '".$recData['forumCloseReason']."' 
					WHERE forum_id = '".$recData['forumId']."' 
					";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="setClose"){
            $sql = "UPDATE _forum_group 
					SET forum_status		= '".$recData['forumStatus']."', 
						forum_close_date 	= NOW(), 
						forum_close_by 		= '".$recData['forumCloseBy']."', 
						forum_close_reason	= '".$recData['forumCloseReason']."' 
					WHERE forum_id 			= '".$recData['forumId']."' 
					";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="updateDate"){
            $sql = "UPDATE _forum_group SET forum_update_date = NOW() 
					WHERE forum_id = '".$recData['forumId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
    }

    function delete_forum($id){
        $sql = "DELETE FROM _forum_group WHERE forum_id = '".$id."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function is_name_exists($topic,$groupId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group WHERE forum_name = '".$topic."' AND group_id = '".$groupId."' ";
        $data = $this->doQuery($sql);
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function select_forum($opt="",$catId="",$limit=""){
        if($opt==""){
            $sql = "SELECT f.*, m.member_name, m.member_image, g.group_name FROM _forum_group f LEFT JOIN _group g USING(group_id) LEFT JOIN _member m USING(member_id) WHERE f.group_id = '".$this->recData['groupId']."' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="open"){
            $sql = "SELECT * FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' AND forum_status = 'open' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countOpen"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group 
					WHERE group_id = '".$this->recData['groupId']."' AND forum_status = 'open' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT f.*, m.member_name, m.member_image, g.group_name FROM _forum_group f LEFT JOIN _group g USING(group_id) LEFT JOIN _member m USING(member_id) WHERE forum_id = '".$this->recData['forumId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_alias = '".$this->recData['forumAlias']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' 
						AND member_id = '".$this->recData['memberId']."' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' ";
            if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " AND forum_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
            }
            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " AND member_id IN (SELECT member_id FROM _member WHERE member_name LIKE '%".$_SESSION['Search']['Keyword']."%')";
            }
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group WHERE group_id = '".$this->recData['groupId']."' ";

            if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " AND forum_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
            }
            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " AND member_id IN (SELECT member_id FROM _member WHERE member_name LIKE '%".$_SESSION['Search']['Keyword']."%')";
            }
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }

    }


    function search_forum_group($keyword,$catId,$groupId){
        $keyword = explode(' ', $keyword);
        $sql = "SELECT f.*, m.member_name, m.member_image FROM _forum_group f LEFT JOIN _member m USING(member_id) WHERE forum_status = 'open' AND f.group_id = '".$groupId."' ";

        if($catId){
            $sql .= " AND cat_id = '".$catId."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            $i = 0;
            foreach ($keyword as $kw){
                if ($i>0) $sql .= " AND ";
                $sql .= " forum_name LIKE '%".$kw."%' ";
                $i++;
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC LIMIT ".$this->beginRec.", ".$this->endRec;

        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // FORUM END //


    // FORUM CHAT START //

    function insert_forum_chat($recData){
        $data = [
            'forum_id'  => $recData['forumId'],
            'user_id'   => $recData['userId'],
            'member_id' => $recData['memberId'],
            'group_id'  => $recData['groupId'],
            'fc_desc'   => $recData['fcDesc'],
            'fc_image'  => $recData['fcImage'],
            'fc_status' => $recData['fcStatus'],
            'fc_create_date'    => date('Y-m-d H:i:s')
        ];
        $this->db->insert('_forum_group_chat', $data);
//        $sql = "INSERT INTO _forum_group_chat
//				VALUES('','".$recData['forumId']."','".$recData['userId']."','".$recData['memberId']."',
//					'".$recData['groupId']."','".$recData['fcDesc']."','".$recData['fcImage']."',
//					'".$recData['fcStatus']."',NOW())";
//        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_forum_chat($recData){
        $sql = "UPDATE _forum_group_chat 
				SET forum_id	= '".$recData['forumId']."', 
					user_id 	= '".$recData['userId']."', 
					member_id	= '".$recData['memberId']."', 
					group_id	= '".$recData['groupId']."', 
					fc_desc		= '".$recData['fcDesc']."', 
					fc_image	= '".$recData['fcImage']."', 
					fc_status	= '".$recData['fcStatus']."' 
				WHERE fc_id = '".$recData['fcId']."' ";
        $query = $this->db->query($sql);
        return $query->affected_row();
    }

    function delete_forum_chat($id){
        $sql = "DELETE FROM _forum_group_chat WHERE fc_id = '".$id."' ";
        $query = $this->db->query($sql);
        return $query;
    }

    function select_forum_chat($opt="",$limit=""){
        if($opt=="all"){

            //edit ud
            $sql = "SELECT a.*, b.forum_alias, c.member_name, c.member_image
                FROM _forum_group_chat a, _forum_group b LEFT JOIN _member c USING(member_id)
                WHERE a.forum_id = b.forum_id AND a.fc_status = 'active' 
                    AND a.group_id = '".$this->recData['groupId']."' 
                    AND a.forum_id = '".$this->recData['forumId']."' 
                ORDER BY a.fc_create_date DESC ";
            //end edit

            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt==""){
            $sql = "SELECT * FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_id = '".$this->recData['forumId']."' 
					ORDER BY fc_create_date DESC ";

            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_id = '".$this->recData['forumId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT a.fc_id, a.forum_id, a.user_id as user_id_comment, a.member_id as member_id_comment, 
						a.fc_desc, a.fc_status, a.fc_create_date,			
						a.user_id, b.member_id, b.forum_name, b.forum_sticky, b.forum_status, 
						b.forum_create_date, b.forum_update_date
					FROM _forum_group_chat a, _forum_group b 
					WHERE a.forum_id = b.forum_id 
						AND a.group_id = '".$this->recData['groupId']."' 
						AND a.fc_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $sql .= " ORDER BY b.forum_sticky DESC, a.fc_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group_chat a, _forum_group b 
					WHERE a.forum_id = b.forum_id AND 
						a.group_id = '".$this->recData['groupId']."' 
						AND a.fc_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' AND forum_id = '".$this->recData['forumId']."' 
						AND fc_status = 'active' 
					ORDER BY fc_create_date DESC ";

            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_id = '".$this->recData['forumId']."' 
						AND fc_status = 'active' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countUser"){
            $sql = "SELECT COUNT(DISTINCT(member_id)) AS TOTAL FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_id = '".$this->recData['forumId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countComment"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND forum_id = '".$this->recData['forumId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group_chat 
					WHERE group_id = '".$this->recData['groupId']."' AND member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
    }

    function list_user_forum($forumId,$groupId){
        $sql = "SELECT a.member_name, a.member_nip, a.member_image, c.group_name  
				FROM _member a, _forum_group_chat b, _group c  
				WHERE a.member_id = b.member_id AND a.group_id = c.group_id 
					AND b.forum_id = '".$forumId."' AND a.group_id = '".$groupId."' AND b.member_id != '0' 
				GROUP BY b.member_id 
				ORDER BY b.fc_create_date DESC ";// echo $sql;exit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function count_in_cat($catId,$groupId=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_group WHERE cat_id = '".$catId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }



    // FORUM CHAT END //


    // FORUM SUGGEST START //

    function insert_forum_suggest($recData=array()){
        $sql = "INSERT INTO _forum_group_suggest VALUES('','".$recData['memberId']."','".$recData['groupId']."','".$recData['fsName']."',NOW())";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function delete_forum_suggest($fsId){
        $sql = "DELETE FROM _forum_group_suggest WHERE fs_id = '".$fsId."' ";
        $query = $this->db->query($sql);
        return $query;
    }

    function select_forum_suggest($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_nip, b.member_jabatan, c.* 
							FROM _forum_group_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
								AND a.group_id = '".$recData['groupId']."' 
					ORDER BY fs_create_date DESC ";

            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL 
							FROM _forum_group_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
								AND a.group_id = '".$recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }


    // FORUM SUGGEST END //
}