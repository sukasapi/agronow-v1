<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 09/08/20
 * Time: 16:37
 * @property CI_DB_query_builder db
 */

class Forum_model extends CI_Model
{
    var $recData = ["forumId"=>"","catId"=>"","userId"=>"","memberId"=>"","forumName"=>"","forumDesc"=>"",
        "forumSticky"=>"","forumStatus"=>"","forumCreateDate"=>"","forumUpdateDate"=>"",
        "forumCloseDate"=>"","forumCloseBy"=>"","forumCloseReason"=>"",
        "fcId"=>"","fcDesc"=>"","fcImage"=>"","fcStatus"=>"","fcCreateDate"=>"",
        "fsId"=>"","fsName"=>""];
    var $lastInsertId;
    var $beginRec,$endRec;

    // FORUM START //
    function insert_forum($recData){
        $sql = "INSERT INTO _forum 
				VALUES( '','".$recData['catId']."','".$recData['userId']."','".$recData['memberId']."',
						'".$recData['forumName']."','".$recData['forumAlias']."','".$recData['forumDesc']."',
						'".$recData['forumSticky']."','".$recData['forumStatus']."',NOW(),NOW(),
						'".$recData['forumCloseDate']."','".$recData['forumCloseBy']."','".$recData['forumCloseReason']."')";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_forum($opt="",$recData=array()){
        if($opt==""){
            $sql = "UPDATE _forum 
					SET cat_id				= '".$recData['catId']."', 
						user_id				= '".$recData['userId']."', 
						member_id			= '".$recData['memberId']."', 
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
            $sql = "UPDATE _forum 
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
            $sql = "UPDATE _forum SET forum_update_date = NOW() 
					WHERE forum_id = '".$recData['forumId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
    }

    function delete_forum($id){
        $sql = "DELETE FROM _forum WHERE forum_id = '".$id."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function is_name_exists($topic){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _forum WHERE forum_name = '".$topic."' ";
        $data = $this->doQuery($sql);
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function select_forum($opt="",$catId="",$limit="",$groupId=""){
        if($opt==""){
            $sql = "SELECT * FROM _forum ";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="select"){
            $sql = "SELECT f.*, m.member_name, m.member_image, g.group_name FROM _forum f LEFT JOIN _member m USING(member_id) LEFT JOIN _group g USING(group_id)";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' ";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="selectByGroup"){
            $sql = "SELECT f.*, m.member_name, m.member_image, g.group_name FROM _forum f LEFT JOIN _member m USING(member_id) LEFT JOIN _group g USING(group_id)";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' AND m.group_id = '".$groupId."'";
            }else{
                $sql .= " WHERE m.group_id = '".$groupId."'";
            }
            $sql .= " ORDER BY forum_sticky DESC, forum_update_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $result = $query->row();
        }
        elseif($opt=="open"){
            $sql = "SELECT * FROM _forum WHERE forum_status = 'open' ";
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
            $result = $query->row();
        }
        elseif($opt=="countOpen"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum 
					WHERE forum_status = 'open' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $result = $query->row();
        }
        elseif($opt=="byId"){
            $sql = "SELECT f.*, m.member_name, m.member_image, g.group_name FROM _forum f LEFT JOIN _member m USING(member_id) LEFT JOIN _group g USING(group_id) WHERE forum_id = '".$this->recData['forumId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _forum WHERE forum_alias = '".$this->recData['forumAlias']."' ";
            $query = $this->db->query($sql);
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _forum WHERE member_id = '".$this->recData['memberId']."' ";
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
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _forum ";
            if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " WHERE forum_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
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
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum ";if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " WHERE forum_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
            }
            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " AND member_id IN (SELECT member_id FROM _member WHERE member_name LIKE '%".$_SESSION['Search']['Keyword']."%')";
            }
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }

    }


    function search_forum($kw,$catId){
        $keyword = arrWords($kw); //p($keyword);exit;
        $result = array();
        $sql = "SELECT * ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when forum_name like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end) as priority";
        }

        $sql .= " FROM _forum WHERE forum_status = 'open' ";

        if(intval($catId)>0){
            $sql .= " AND cat_id = '".$catId."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " forum_name LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY forum_sticky DESC, priority, forum_update_date DESC ";

        //echo $sql;exit;
        $data = $this->doQuery($sql); //p($data);exit;
        return $data;
    }

    // FORUM END //


    // FORUM CHAT START //

    function insert_forum_chat($recData){
        $sql = "INSERT INTO _forum_chat 
				VALUES('','".$recData['forumId']."','".$recData['userId']."','".$recData['memberId']."',
                    '".$recData['fcDesc']."','".$recData['fcImage']."','".$recData['fcStatus']."',NOW())";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_forum_chat($recData){
        $sql = "UPDATE _forum_chat 
				SET forum_id	= '".$recData['forumId']."', 
					user_id 	= '".$recData['userId']."', 
					member_id	= '".$recData['memberId']."', 
					fc_desc		= '".$recData['fcDesc']."', 
					fc_image	= '".$recData['fcImage']."', 
					fc_status	= '".$recData['fcStatus']."' 
				WHERE fc_id = '".$recData['fcId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function delete_forum_chat($id){
        $sql = "DELETE FROM _forum_chat WHERE fc_id = '".$id."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_forum_chat($opt="",$limit=""){
        if($opt=="all"){
            $sql = "SELECT a.*, b.forum_alias, c.member_name FROM _forum_chat a, _forum b LEFT JOIN _member c USING(member_id)
					WHERE a.forum_id = b.forum_id AND a.fc_status = 'active' 
					ORDER BY a.fc_create_date DESC ";

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt==""){
            $sql = "SELECT a.*, b.forum_alias, c.member_name, c.member_image FROM _forum_chat a JOIN _forum b USING(forum_id) LEFT JOIN _member c ON a.member_id = c.member_id
            WHERE a.forum_id = '".$this->recData['forumId']."'
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_chat WHERE forum_id = '".$this->recData['forumId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT a.fc_id, a.forum_id, a.user_id as user_id_comment, a.member_id as member_id_comment, 
						a.fc_desc, a.fc_status, a.fc_create_date,			
						a.user_id, b.member_id, b.forum_name, b.forum_sticky, b.forum_status, 
						b.forum_create_date, b.forum_update_date
					FROM _forum_chat a, _forum b 
					WHERE a.forum_id = b.forum_id  
						AND a.fc_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $sql .= " ORDER BY b.forum_sticky DESC, a.fc_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_chat a, _forum b 
					WHERE a.forum_id = b.forum_id  
						AND a.fc_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _forum_chat 
					WHERE forum_id = '".$this->recData['forumId']."' 
						AND fc_status = 'active' 
					ORDER BY fc_create_date DESC ";

            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="countActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_chat 
					WHERE forum_id = '".$this->recData['forumId']."' 
						AND fc_status = 'active' ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countUser"){
            $sql = "SELECT COUNT(DISTINCT(member_id)) AS TOTAL FROM _forum_chat 
					WHERE forum_id = '".$this->recData['forumId']."' ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countComment"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_chat 
					WHERE forum_id = '".$this->recData['forumId']."' ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _forum_chat 
					WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $result = $query->row();
            return $result->TOTAL;
        }
    }

    function list_user_forum($forumId){
        $sql = "SELECT a.member_name, a.member_nip, a.member_image, c.group_name  
				FROM _member a, _forum_chat b, _group c  
				WHERE a.member_id = b.member_id AND a.group_id = c.group_id 
					AND b.forum_id = '".$forumId."' AND b.member_id != '0' 
				GROUP BY b.member_id 
				ORDER BY b.fc_create_date DESC ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function count_in_cat($catId,$groupId=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _forum WHERE cat_id = '".$catId."' ";
        $data = $this->doQuery($sql);
        return $data[0]['TOTAL'];
    }

    // FORUM CHAT END //


    // FORUM SUGGEST START //

    function insert_forum_suggest($recData=array()){
        $data = ['member_id'=>$recData['memberId'],'fs_name'=>$recData['fsName'],'fs_create_date'=>date('Y-m-d H:i:s')];
        $this->db->insert('_forum_suggest', $data);
        return $this->db->insert_id();
    }

    function delete_forum_suggest($fsId){
        $sql = "DELETE FROM _forum_suggest WHERE fs_id = '".$fsId."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_forum_suggest($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_nip, b.member_jabatan, c.* 
							FROM _forum_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
					ORDER BY fs_create_date DESC ";

            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL 
							FROM _forum_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
					ORDER BY fs_create_date DESC ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
    }


    // FORUM SUGGEST END //
    
    public function get_lastest(){
        $this->db->select('forum_name, forum_create_date');
        $this->db->order_by('forum_create_date', 'desc');
        $data = $this->db->get('_forum', 1)->result_array();

        return $data;
    }
}