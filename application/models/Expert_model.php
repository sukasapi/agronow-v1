<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder db
 */
class Expert_model extends CI_Model {

    var $recData = array("expertId"=>"","emConcern"=>"","emId"=>"","memberId"=>"","expertName"=>"","expertAlias"=>"",
        "expertDesc"=>"","expertSticky"=>"","expertStatus"=>"","expertCreateDate"=>"",
        "expertUpdateDate"=>"","expertCloseDate"=>"","expertCloseBy"=>"","expertCloseReason"=>"",
        "catId"=>"","sectionId"=>"","catName"=>"","catAlias"=>"","catDesc"=>"","catImage"=>"",
        "catColor"=>"","catHits"=>"","catParent"=>"","catLevel"=>"","catStatus"=>"","catRoot"=>"",
        "catOrder"=>"","groupId"=>"","emName"=>"","emProfil"=>"","emImage"=>"","emEducation"=>"",
        "emExperience"=>"","emQualification"=>"","emStatus"=>"","emCreateDate"=>"","ecId"=>"",
        "ecDesc"=>"","ecImage"=>"","ecStatus"=>"","ecCreateDate"=>"","commentId"=>"","commentText"=>"",
        "commentType"=>"","commentChannel"=>"","commentStatus"=>"","commentLike"=>"","commentRate"=>"",
        "commentCreateDate"=>"","esId"=>"","esName"=>"","esCreateDate"=>"","mlevelId"=>"",
        "memberName"=>"","memberNip"=>"","memberType"=>"","memberEmail"=>"","memberPassword"=>"",
        "memberLoginWeb"=>"","memberLoginApk"=>"","memberLoginIpa"=>"","memberRegId"=>"",
        "memberRegChannel"=>"","memberDevice"=>"","memberDesc"=>"","memberJabatan"=>"",
        "memberKelJabatan"=>"","memberUnitKerja"=>"","memberImage"=>"","memberGender"=>"",
        "memberBirthPlace"=>"","memberBirthDate"=>"","memberPhone"=>"","memberAddress"=>"",
        "memberCity"=>"","memberProvince"=>"","memberPostcode"=>"","memberCeo"=>"","memberStatus"=>"",
        "memberCreateDate"=>"");
    var $beginRec,$endRec;
    var $lastInsertId;

    function generate_alias($str)
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        $plink = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $plink = str_replace(" &amp; ", " ", $plink);
        $plink = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $plink);
        $plink = strtolower(trim($plink, '-'));
        $plink = preg_replace("/[\/_| -]+/", '-', $plink);
        return $plink;
    }

    function arrWords($text = "")
    {
        $text = $this->generate_alias($text);
        $arrText = explode("-", strtolower($text));
        $count = count($arrText);
        $value = array();
        $key = 0;
        $first = 0;

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $len = $count - $i;
                $next = 1;
                $start = 0;
                while ($next == 1) {
                    $value[$key] = "";
                    for ($j = 0; $j < $len; $j++) {
                        $value[$key] .= $arrText[$start] . " ";
                        $start++;
                    }
                    $value[$key] = trim($value[$key]);
                    $last = $arrText[$len - 1];
                    if ($start == $count) {
                        $next = 0;
                    }
                    $key++;
                    $first = $first + 1;
                    $start = $first;
                }
                $first = 0;
                $start = 0;
            }
        }
        return $value;
    }

    // EXPERT START //
    function auth_expert($groupId){
        $result = false;
        $sql = "SELECT * FROM _expert_member
				WHERE group_id = '".$groupId."'  ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)>0){
            if($data[0]['expert_status']=="block"){
                $result = false;
            }
            else{
                $result = true;
                $_SESSION['Expert']['Id'] 		= $data[0]['expert_id'];
                $_SESSION['Expert']['Name'] 	= $data[0]['expert_name'];
                $_SESSION['Expert']['LoginWeb'] = $data[0]['expert_login_web'];
                $_SESSION['Expert']['LoginApk'] = $data[0]['expert_login_apk'];
                $_SESSION['Expert']['LoginIpa'] = $data[0]['expert_login_ipa'];
            }
        }
        return $result;
    }

    function get_group_id($nip=""){
        if(strlen($nip)<3){
            $groupId = 0;
        }
        else{
            $groupId = substr($nip,1,2);
        }
        return intval($groupId);
    }

    function insert_expert($recData){
        $sql = "INSERT INTO _expert(cat_id,em_concern,em_id,member_id,expert_name,expert_alias,expert_desc,
									expert_sticky,expert_status,expert_create_date)  
				VALUES('".$recData['catId']."','".$recData['emConcern']."','".$recData['emId']."','".$recData['memberId']."',
					   '".$recData['expertName']."','".$recData['expertAlias']."','".$recData['expertDesc']."',
					   '".$recData['expertSticky']."','".$recData['expertStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_expert($opt="",$recData,$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _expert
					   SET cat_id				= '".$recData['catId']."', 
						   em_concern			= '".$recData['emConcern']."', 
						   em_id				= '".$recData['emId']."', 
						   member_id			= '".$recData['memberId']."', 
						   expert_name	 		= '".$recData['expertName']."', 
						   expert_alias			= '".$recData['expertAlias']."', 
						   expert_desc			= '".$recData['expertDesc']."', 
						   expert_sticky		= '".$recData['expertSticky']."', 
						   expert_status		= '".$recData['expertStatus']."', 
						   expert_close_date 	= '".$recData['expertCloseDate']."', 
						   expert_close_by 		= '".$recData['expertCloseBy']."', 
						   expert_close_reason	= '".$recData['expertCloseReason']."' 
				     WHERE expert_id 			= '".$recData['expertId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="setClose"){
            $sql = "UPDATE _expert
					SET expert_status		= '".$recData['expertStatus']."', 
						expert_close_date 	= NOW(), 
						expert_close_by 	= '".$recData['expertCloseBy']."', 
						expert_close_reason	= '".$recData['expertCloseReason']."' 
					WHERE expert_id 		= '".$recData['expertId']."' 
					";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="updateDate"){
            $sql = "UPDATE _expert SET expert_update_date = NOW() 
					WHERE expert_id = '".$recData['expertId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
    }

    function get_group_expert_byid($expertId){
        $sql = "SELECT group_id FROM _expert WHERE expert_id = '".$expertId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['group_id'];
    }

    function delete_expert($expertId){
        $sql = "DELETE FROM _expert WHERE expert_id = '".$expertId."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_expert_login($user,$pass,$groupId){
        $sql = "SELECT * FROM _expert 
				WHERE group_id = '".$groupId."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }

    function get_group_expert_type($expertId){
        $sql = "SELECT group_id, expert_type FROM _expert WHERE expert_id = '".$expertId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }

    function select_category_with_expert_count(){
        $sql = "SELECT c.*, count(e.expert_id) total
                FROM `_category` c
                  LEFT JOIN `_expert` e ON c.cat_id = e.cat_id
                WHERE section_id = 37
                AND cat_status = '1'
                GROUP BY c.cat_id
                ORDER BY c.cat_order";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function select_expert($opt="",$emConcern="",$limit=""){
        if($opt==""){
//            $sql = "SELECT * FROM _expert WHERE";
            $sql = "SELECT * FROM _expert LEFT JOIN _expert_chat ec ON ec.expert_id = _expert.expert_id WHERE";
            $and = "";
            if(intval($this->recData['emId']) > 0){
                $sql .= " _expert.em_id = '".$this->recData['emId']."' ";
                $and = "AND";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= $and." cat_id = '".$this->recData['catId']."' ";
                $and = "AND";
            }
            if(strlen($emConcern) > 0){
                $sql .= $and." _expert.em_concern = '".$emConcern."' ";
            }
//            $sql .= " ORDER BY expert_sticky DESC, expert_update_date DESC";
            $sql .= " GROUP BY _expert.expert_id ORDER BY expert_sticky DESC, ec.ec_id DESC";
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert";
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " WHERE em_concern = '".$emConcern."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="open"){
            $sql = "SELECT * FROM _expert WHERE expert_status = 'open' ";
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " AND em_concern = '".$emConcern."' ";
            }
            $sql .= " ORDER BY expert_sticky DESC, expert_update_date DESC";
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert 
					WHERE expert_status = 'open' ";
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " AND em_concern = '".$emConcern."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _expert WHERE expert_id = '".$this->recData['expertId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _expert WHERE expert_alias = '".$this->recData['expertAlias']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _expert WHERE member_id = '".$this->recData['memberId']."' ";
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " AND em_concern = '".$emConcern."' ";
            }
            $sql .= " ORDER BY expert_sticky DESC, expert_update_date DESC";
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _expert ";
            if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " WHERE expert_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
            }
            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " AND member_id IN (SELECT member_id FROM _member WHERE member_name LIKE '%".$_SESSION['Search']['Keyword']."%')";
            }
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " AND em_concern = '".$emConcern."' ";
            }
            $sql .= " ORDER BY expert_sticky DESC, expert_update_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert ";
            if($_SESSION['Search']['Type']=="Topik"){
                $sql .= " WHERE expert_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
            }
            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " AND member_id IN (SELECT member_id FROM _member WHERE member_name LIKE '%".$_SESSION['Search']['Keyword']."%')";
            }
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " AND cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " AND em_concern = '".$emConcern."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="detailByCategory"){
            $sql = "SELECT _expert.*, member FROM _expert ";
            if(intval($this->recData['emId']) > 0){
                $sql .= " AND em_id = '".$this->recData['emId']."' ";
            }
            if(intval($this->recData['catId']) > 0){
                $sql .= " WHERE cat_id = '".$this->recData['catId']."' ";
            }
            if(strlen($emConcern) > 0){
                $sql .= " WHERE em_concern = '".$emConcern."' ";
            }
            $sql .= " ORDER BY expert_sticky DESC, expert_update_date DESC";
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
    }

    function search_expert($kw,$emConcern){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        $result = array();
        $sql = "SELECT * ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when expert_name like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end) as priority";
        }

        $sql .= " FROM _expert WHERE expert_status = 'open' ";

        if(intval($this->recData['emId']) > 0){
            $sql .= " AND em_id = '".$this->recData['emId']."' ";
        }
        if(intval($this->recData['catId']) > 0){
            $sql .= " AND cat_id = '".$this->recData['catId']."' ";
        }
        if(strlen($emConcern) > 0){
            $sql .= " AND em_concern = '".$emConcern."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " expert_name LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY expert_sticky DESC, priority, expert_update_date DESC ";

        //echo $sql;exit;
        $query = $this->db->query($sql);
        $data = $query->result_array(); //p($data);exit;
        return $data;
    }

    function select_quick_expert(){
        $sql = "SELECT a.expert_id, a.expert_name, b.group_name 
				  FROM _expert a, _group b 
				 WHERE a.group_id = b.group_id
				 ORDER BY a.expert_id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // EXPERT END //

    // EXPERT CHAT START //

    function insert_expert_chat($recData){
        $data = [
            'expert_id' => $recData['expertId'],
            'em_id'     => $recData['emId'],
            'member_id' => $recData['memberId'],
            'ec_desc'   => $recData['ecDesc'],
            'ec_image'  => $recData['ecImage'],
            'ec_status' => $recData['ecStatus'],
            'ec_create_date'    => date('Y-m-d H:i:s')
        ];
        $this->db->insert('_expert_chat', $data);
//        $sql = "INSERT INTO _expert_chat (expert_id,em_id,member_id,ec_desc,ec_image,ec_status,ec_create_date)
//				VALUES('".$recData['expertId']."','".$recData['emId']."','".$recData['memberId']."',
//					'".$this->db->escape($recData['ecDesc'])."','".$recData['ecImage']."','".$recData['ecStatus']."',NOW())";
//        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_expert_chat($recData){
        $sql = "UPDATE _expert_chat 
					SET expert_id	= '".$recData['expertId']."', 
						em_id 		= '".$recData['emId']."', 
						member_id	= '".$recData['memberId']."', 
						ec_desc		= '".$recData['ecDesc']."', 
						ec_image	= '".$recData['ecImage']."', 
						ec_status	= '".$recData['ecStatus']."' 
				WHERE ec_id = '".$recData['ecId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_expert_chat($id){
        $sql = "DELETE FROM _expert_chat WHERE ec_id = '".$id."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_expert_chat($opt="",$limit=""){
        if($opt=="all"){
            $sql = "SELECT a.*, b.expert_alias FROM _expert_chat a, _expert b 
					WHERE a.expert_id = b.expert_id AND a.ec_status = 'active' 
					ORDER BY a.ec_create_date DESC ";

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
            $sql = "SELECT * FROM _expert_chat WHERE expert_id = '".$this->recData['expertId']."' 
					ORDER BY ec_create_date ASC ";

            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            elseif ($this->endRec>0){
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="withDetail"){
            $sql = "SELECT ec.*, CAST(ec_create_date AS DATE) date, DATE_FORMAT(ec_create_date, '%H:%i') as time, 
                    m.member_name FROM _expert_chat ec LEFT JOIN _member m ON m.member_id=ec.member_id
                    WHERE ec.expert_id = '".$this->recData['expertId']."' ORDER BY ec_create_date ASC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit;
            }
            elseif ($this->endRec>0){
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_chat WHERE expert_id = '".$this->recData['expertId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT a.ec_id, a.expert_id, a.user_id as user_id_comment, a.member_id as member_id_comment, 
						a.ec_desc, a.ec_status, a.ec_create_date,			
						a.user_id, b.member_id, b.expert_name, b.expert_sticky, b.expert_status, 
						b.expert_create_date, b.expert_update_date
					FROM _expert_chat a, _expert_qa b 
					WHERE a.expert_id = b.expert_id  
						AND a.ec_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $sql .= " ORDER BY b.expert_sticky DESC, a.ec_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_chat a, _expert_qa b 
					WHERE a.expert_id = b.expert_id  
						AND a.ec_desc LIKE '%".$_SESSION['Search']['Keyword']."%' ";
            if(isset($_SESSION['Search']['Cat']) && $_SESSION['Search']['Cat']!=""){
                $sql .= " AND b.cat_id = '".$_SESSION['Search']['Cat']."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _expert_chat 
					WHERE expert_id = '".$this->recData['expertId']."' 
						AND ec_status = 'active' 
					ORDER BY ec_create_date DESC ";

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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_chat 
					WHERE expert_id = '".$this->recData['expertId']."' 
						AND ec_status = 'active' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countUser"){
            $sql = "SELECT COUNT(DISTINCT(member_id)) AS TOTAL FROM _expert_chat 
					WHERE expert_id = '".$this->recData['expertId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countComment"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_chat 
					WHERE expert_id = '".$this->recData['expertId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_chat 
					WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function get_latest_chat_another($opt="", $recData=[], $limit=10, $offset=0){
        if ($opt=="member"){
            $sql = "SELECT ec.*, m.member_id, m.member_name FROM _expert_chat ec inner join (select expert_id, ec_desc, member_id, max(ec_id) as maxId 
                    from `_expert_chat` WHERE expert_id IN (SELECT expert_id FROM `_expert_chat` WHERE member_id='".$recData['memberId']."' 
                    GROUP BY expert_id) group by expert_id DESC LIMIT ".$offset.",".$limit.") tm 
                    INNER JOIN _member m on ec.member_id = m.member_id and ec.member_id != '".$recData['memberId']."' and ec.ec_id = tm.maxId ORDER BY ec_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        } elseif ($opt=="expertMember"){
            $sql = "SELECT ec.*, m.member_id, m.member_name FROM _expert_chat ec inner join (select expert_id, ec_desc, member_id, max(ec_id) as maxId 
                    from `_expert_chat` WHERE expert_id IN (SELECT expert_id FROM `_expert_chat` WHERE em_id='".$recData['emId']."' 
                    GROUP BY expert_id) group by expert_id DESC LIMIT ".$offset.",".$limit.") tm INNER JOIN _member m 
                    on ec.member_id = m.member_id and ec.member_id != '0' and ec.ec_id = tm.maxId ORDER BY ec_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        } else {
            return NULL;
        }
    }

    function list_user_expert_chat($expertId){
        $sql = "SELECT a.member_name, a.member_nip, a.member_image, c.group_name  
				FROM _member a, _expert_chat b, _group c  
				WHERE a.member_id = b.member_id AND a.group_id = c.group_id 
					AND b.expert_id = '".$expertId."' AND b.member_id != '0' 
				GROUP BY b.member_id,a.member_name,a.member_nip,a.member_image,c.group_name,b.ec_create_date
				ORDER BY b.ec_create_date DESC ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function count_in_cat_expert($emConcern,$groupId=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _expert WHERE cat_id = '".$emConcern."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }
    // EXPERT CHAT END //

    // EXPERT SUGGEST START //
    function insert_expert_suggest($recData=array()){
        $sql = "INSERT INTO _expert_suggest VALUES('','".$recData['memberId']."','".$recData['esName']."',NOW())";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function delete_expert_suggest($esId){
        $sql = "DELETE FROM _expert_suggest WHERE es_id = '".$esId."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_expert_suggest($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_nip, b.member_jabatan, c.* 
							FROM _expert_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
					ORDER BY es_create_date DESC ";

            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL 
							FROM _expert_suggest a, _member b, _group c 
							WHERE a.member_id = b.member_id AND b.group_id = c.group_id 
					ORDER BY es_create_date DESC ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    // EXPERT SUGGEST END //

    // MEMBER START//
    function select_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _member ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="listGroup"){
            $sql = "SELECT * FROM _member 
					WHERE group_id = '".$this->recData['groupId']."' 
					ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countListGroup"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member WHERE group_id = '".$this->recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _member 
					 WHERE (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_nip LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_email LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_phone LIKE '%".$_SESSION['Search']['Keyword']."%') ";
            if($_SESSION['Search']['Group']!=""){
                $sql .= " AND group_id = '".$_SESSION['Search']['Group']."' ";
            }
            if(isset($_SESSION['Search']['Level']) && $_SESSION['Search']['Level']!=""){
                $sql .= " AND mlevel_id = '".$_SESSION['Search']['Level']."' ";
            }
            if(isset($_SESSION['Search']['Bidang']) && $_SESSION['Search']['Bidang']!=""){
                $sql .= " AND member_desc = '".$_SESSION['Search']['Bidang']."' ";
            }
            if(isset($_SESSION['Search']['CeoNotes']) && $_SESSION['Search']['CeoNotes']!=""){
                $ceoNotes = ($_SESSION['Search']['CeoNotes']=="allow") ? "1" : "0";
                $sql .= " AND member_ceo = '".$ceoNotes."' ";
            }
            $sql .= " ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member 
					 WHERE (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_nip LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_email LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_phone LIKE '%".$_SESSION['Search']['Keyword']."%') ";
            if($_SESSION['Search']['Group']!=""){
                $sql .= " AND group_id = '".$_SESSION['Search']['Group']."' ";
            }
            if(isset($_SESSION['Search']['Level']) && $_SESSION['Search']['Level']!=""){
                $sql .= " AND mlevel_id = '".$_SESSION['Search']['Level']."' ";
            }
            if(isset($_SESSION['Search']['Bidang']) && $_SESSION['Search']['Bidang']!=""){
                $sql .= " AND member_desc = '".$_SESSION['Search']['Bidang']."' ";
            }
            if(isset($_SESSION['Search']['CeoNotes']) && $_SESSION['Search']['CeoNotes']!=""){
                $ceoNotes = ($_SESSION['Search']['CeoNotes']=="allow") ? "1" : "0";
                $sql .= " AND member_ceo = '".$ceoNotes."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="searchGroup"){
            $sql = "SELECT * FROM _member 
					 WHERE (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_nip LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_email LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_phone LIKE '%".$_SESSION['Search']['Keyword']."%')  
						AND group_id = '".$_SESSION['Admine']['GroupId']."' ";
            if(isset($_SESSION['Search']['Level']) && $_SESSION['Search']['Level']!=""){
                $sql .= " AND mlevel_id = '".$_SESSION['Search']['Level']."' ";
            }
            if(isset($_SESSION['Search']['Bidang']) && $_SESSION['Search']['Bidang']!=""){
                $sql .= " AND member_desc = '".$_SESSION['Search']['Bidang']."' ";
            }
            if(isset($_SESSION['Search']['CeoNotes']) && $_SESSION['Search']['CeoNotes']!=""){
                $ceoNotes = ($_SESSION['Search']['CeoNotes']=="allow") ? "1" : "0";
                $sql .= " AND member_ceo = '".$ceoNotes."' ";
            }
            $sql .= " ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearchGroup"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member 
					 WHERE (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_nip LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_email LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
							member_phone LIKE '%".$_SESSION['Search']['Keyword']."%') 
						AND group_id = '".$_SESSION['Admine']['GroupId']."' ";

            if(isset($_SESSION['Search']['Level']) && $_SESSION['Search']['Level']!=""){
                $sql .= " AND mlevel_id = '".$_SESSION['Search']['Level']."' ";
            }
            if(isset($_SESSION['Search']['Bidang']) && $_SESSION['Search']['Bidang']!=""){
                $sql .= " AND member_desc = '".$_SESSION['Search']['Bidang']."' ";
            }
            if(isset($_SESSION['Search']['CeoNotes']) && $_SESSION['Search']['CeoNotes']!=""){
                $ceoNotes = ($_SESSION['Search']['CeoNotes']=="allow") ? "1" : "0";
                $sql .= " AND member_ceo = '".$ceoNotes."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _member WHERE member_status = 'active' 
					ORDER BY member_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member WHERE member_status = 'active'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byType"){
            $sql = "SELECT * FROM _member WHERE member_type = '".$this->recData['menberType']."' 
					ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _member WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byEmail"){
            $sql = "SELECT * FROM _member WHERE member_email = '".$this->recData['memberEmail']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="checkRegister"){
            $sql = "SELECT * FROM _member 
					WHERE (member_email = '".$this->recData['memberEmail']."' 
						OR member_nip = '".$this->recData['memberNip']."') 
					";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="countInGroup"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member WHERE group_id = '".$this->recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="authorForum"){
            $sql = "SELECT a.member_name, a.member_image, b.group_name  
					  FROM _member a, _group b 
					 WHERE a.group_id = b.group_id 
					   AND a.member_id = '".$this->recData['memberId']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="searchAbsensi"){
            $sql = "SELECT * FROM _member a, _group b  
					 WHERE a.group_id = b.group_id 
					   AND(a.member_name LIKE '%".$this->recData['memberName']."%' 
						OR a.member_nip LIKE '%".$this->recData['memberNip']."%') 
					";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="nameById"){
            $sql = "SELECT member_name FROM _member WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['member_name'];
        }
    }
    //MEMBER END //

    // EXPERT SETTING START //
    function insert_expert_setting($recData){
        $sql = "INSERT INTO _expert_setting 
				VALUES('".$recData['msetPlayerId']."','".$recData['msetChannel']."',
						'".$recData['expertId']."','".$recData['msetRegDate']."',
						'".$recData['msetUpdateDate']."','".$recData['msetPush']."')";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_expert_setting($opt="",$recData=""){
        if($opt=="byPlayerId"){
            $sql = "UPDATE _expert_setting 
					SET expert_id 		= '".$recData['expertId']."', 
						mset_channel	= '".$recData['msetChannel']."', 
						mset_update_date= '".$recData['msetUpdateDate']."', 
						mset_push		= '".$recData['msetPush']."' 
					WHERE mset_playerid = '".$recData['msetPlayerId']."' 
					";
            $result = $this->db->query($sql);;
            return $result;
        }
        elseif($opt=="byExpertId"){
            $sql = "UPDATE _expert_setting 
					SET mset_playerid	= '".$recData['msetPlayerId']."', 
						mset_channel	= '".$recData['msetChannel']."', 
						mset_reg_date	= '".$recData['msetRegDate']."', 
						mset_update_date= '".$recData['msetUpdateDate']."', 
						mset_push		= '".$recData['msetPush']."' 
					WHERE expert_id = '".$recData['expertId']."' 
					";
            $result = $this->db->query($sql);;
            return $result;
        }
        elseif($opt=="logoutAll"){
            $sql = "UPDATE _expert_setting SET mset_login = '0' WHERE expert_id = '".$recData['expertId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="logoutByPlayerId"){
            $sql = "UPDATE _expert_setting SET mset_login = '0' 
					WHERE expert_id = '".$recData['expertId']."' 
						AND mset_playerid = '".$recData['msetPlayerId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
    }

    function is_playerid_exists_in_expert_setting($playerId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_setting WHERE mset_playerid = '".$playerId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function delete_expert_setting($playerId=""){
        $sql = "DELETE FROM _expert_setting WHERE mset_playerid IN(".$playerId.") ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_expert_setting($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _expert_setting a, _expert b 
					WHERE a.expert_id = b.expert_id ";
            if(intval($limit)>0){
                $sql .= " LIMIT 0, ".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_setting a, _expert b 
					WHERE a.expert_id = b.expert_id";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="activePush"){
            $sql = "SELECT mset_playerid FROM _expert_setting 
					WHERE expert_id != '0' AND mset_push = '1' ";
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
        elseif($opt=="countPushActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_setting 
					WHERE expert_id != '0' AND mset_push = '1' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="free"){
            $sql = "SELECT mset_playerid FROM _expert_setting 
					WHERE mset_push = '1' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="freemium"){
            // select groupId, mlevelId
            $sql = "SELECT mset_playerid FROM _expert_setting 
					WHERE mset_push = '1' AND expert_id != '0' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="freemiumGroup"){
            $sql = "SELECT a.mset_playerid 
					FROM _expert_setting a, _expert b 
					WHERE a.expert_id = b.expert_id 
						AND a.mset_push = '1' AND a.expert_id != '0' 
						AND b.group_id = '".$this->recData['groupId']."' ";
            if($this->recData['mlevelId']!="all"){
                $sql .= " AND b.mlevel_id IN(".$this->recData['mlevelId'].") ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array(); //echo $sql; exit;
            return $data;
        }
    }
    // EXPERT SETTING END //

    function stat_expert_activity($groupId, $orderBy="", $orderType=""){
        $sql = "SELECT a.expert_id, a.expert_name, a.expert_nip, b.total_hits, 
				c.total_download,  d.total_topic, e.total_comment 
				
				FROM _expert a 
				LEFT JOIN (SELECT expert_id, COUNT(*) AS total_hits FROM _content_hits GROUP BY expert_id) AS b 
					ON a.expert_id = b.expert_id 
				
				LEFT JOIN (SELECT expert_id, COUNT(*) AS total_download FROM _media_download GROUP BY expert_id) AS c 
					ON a.expert_id = c.expert_id 
					
				LEFT JOIN (SELECT expert_id, COUNT(*) AS total_topic FROM _forum_group GROUP BY expert_id) AS d 
					ON a.expert_id = d.expert_id 
					
				LEFT JOIN (SELECT expert_id, COUNT(*) AS total_comment FROM _forum_group_chat GROUP BY expert_id) AS e 
					ON a.expert_id = e.expert_id 
						
				
				WHERE a.group_id = '".$groupId."' 
					AND (b.total_hits > 0 OR c.total_download > 0 OR d.total_topic > 0 OR e.total_comment > 0 ) 
				ORDER BY b.total_hits DESC, c.total_download DESC, d.total_topic DESC, e.total_comment DESC 
				";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // CATEGORY EXPERT START //
    function insert_category(){
        $sql = "INSERT INTO _expert_category (section_id,cat_name,cat_alias,cat_desc,cat_image,cat_color,cat_hits,cat_parent,
                                              cat_level,cat_status,cat_root,cat_order)
				VALUES(0, '".$this->catName."', '".$this->catAlias."', '".$this->catDesc."', '".$this->catImage."',
							'".$this->catColor."', 0, '".$this->catParent."', '".$this->catLevel."', '1', 
							'".$this->catRoot."','".$this->catOrder."'
						)";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function is_parent($id=""){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_category WHERE cat_parent = '".intval($id)."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0)	$result = true;
        return $result;
    }

    function update_category($opt="",$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _expert_category 
					SET cat_name 	= '".$this->catName."',
						cat_alias 	= '".$this->catAlias."',
						cat_desc 	= '".$this->catDesc."',
						cat_image	= '".$this->catImage."', 
						cat_color	= '".$this->catColor."', 
						cat_parent 	= '".$this->catParent."',
						cat_status	= '".$this->catStatus."', 
						cat_level 	= '".$this->catLevel."',
						cat_root 	= '".$this->catRoot."'
					WHERE cat_id = '".$this->catId."' 
					";
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _expert_category SET ".$field." = '".$value."' WHERE cat_id = '".$this->catId."' ";
        }
        $result = $this->db->query($sql);
        return $result;
    }

    function get_cat_name($catId=""){
        $sqlCatRoot = "SELECT cat_root FROM _expert_category WHERE cat_id = '".$catId."'";
        $result = $this->db->query($sql);
        $dataCatRoot = $result->result_array();
        if(count($dataCatRoot)>0){
            $sql = "SELECT cat_name FROM _expert_category 
					WHERE cat_id IN (".$dataCatRoot[0]['cat_root'].") ORDER BY cat_id";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $catName = "";
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $catName .= $data[$i]['cat_name'];
                    if($i<count($data)-1){
                        $catName .=", ";
                    }
                }
            }
        }
        else{
            $catName = "&mdash;";
        }
        return $catName;
    }

    function cat_name_link($catId="",$area=""){
        $dataCatRoot = $this->get_cat_root($catId);
        $catRoot = str_replace(",","','",$dataCatRoot);

        $sql = "SELECT a.cat_id,a.cat_name, a.cat_alias, b.section_alias_front 
				FROM _expert_category a, _section b 
				WHERE a.section_id = b.section_id AND a.cat_id IN ('".$catRoot."') ORDER BY a.cat_id";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $result = array();
        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $recData['cat_name'] = $data[$i]['cat_name'];
                $recData['cat_link'] = SITE_HOST."/".$data[$i]['section_alias_front']."/category/".$data[$i]['cat_id']."-".$data[$i]['cat_alias'];
                array_push($result,$recData);
            }
        }
        return $result;
    }

    function get_cat_root($catId){
        $sql = "SELECT cat_root FROM _expert_category WHERE cat_id = '".$catId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['cat_root'];
    }

    function get_id_sub($id){
        $sql = "SELECT cat_root FROM _expert_category WHERE CONCAT(',',cat_root,',') LIKE '%,".$id.",%'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $dataCat = $id;
        for($i=0;$i<count($data);$i++){
            $dataCat .= ",".$data[$i]['cat_root'];
        }
        return $dataCat;
    }

    function is_category($sectionId="",$catAlias=""){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_category 
				WHERE section_id = '".$sectionId."' AND cat_alias = '".$catAlias."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0) $result = true;
        return $result;
    }

    function is_cat_exists(){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_category 
				WHERE section_id = '".$this->sectionId."' AND cat_name = '".$this->catName."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function get_max_level(){
        $sql = "SELECT MAX(cat_level) AS TOTAL FROM _expert_category 
				WHERE section_id = '".$this->sectionId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function get_max_order(){
        $sql = "SELECT MAX(cat_order) AS MAX_ORDER FROM _expert_category WHERE section_id = '".$this->sectionId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['MAX_ORDER'];
    }

    function get_new_order($parentId){
        $sql = "SELECT MAX(cat_order) AS MAX_ORDER FROM _expert_category WHERE CONCAT(',',cat_root,',') LIKE '%,".$parentId.",%' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['MAX_ORDER'];
    }

    function reorder($lastOrder){
        $sql = "UPDATE _expert_category SET cat_order = cat_order+1 
				WHERE cat_id IN (SELECT cat_id FROM 
									(SELECT cat_id FROM _expert_category WHERE section_id = '".$this->sectionId."' AND cat_order > '".$lastOrder."') AS cats
								) ";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_category(){
        $sql = "DELETE FROM _expert_category WHERE CONCAT(',',cat_root,',') LIKE '%,".$this->catId.",%' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_category($opt="",$sectionId="",$desc=""){
        if($opt==""){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' ";
            if($desc!=""){
                $sql .= " AND cat_desc = '".$desc."' ";
            }
            $sql .= " ORDER BY cat_order ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_category WHERE section_id = '".$sectionId."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND cat_id = '".$this->catId."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byName"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND LOWER(cat_name) = '".strtolower($this->catName)."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND cat_alias = '".$this->catAlias."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="parent0"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND cat_level = '1'  ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    if($data[$i]['cat_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$data[$i]['cat_image'])){
                        $data[$i]['cat_image'] = MEDIA_IMAGE_HOST."/".$data[$i]['cat_image'];
                    }
                }
            }
            return $data;
        }
        elseif($opt=="child"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND cat_level = '2'  ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byParent"){
            $sql = "SELECT * FROM _expert_category 
					WHERE section_id = '".$sectionId."' AND cat_parent = '".$this->catParent."' 
						AND cat_status = '1' 
					ORDER BY CAST(`cat_root` AS SIGNED) ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    if($data[$i]['cat_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$data[$i]['cat_image'])){
                        $data[$i]['cat_image'] = MEDIA_IMAGE_HOST."/".$data[$i]['cat_image'];
                    }
                }
            }
            return $data;
        }
        elseif($opt=="DatabyParent"){
            $sql = "SELECT * FROM _expert_category 
					WHERE section_id = '".$sectionId."' AND cat_parent = '".$this->catParent."' 
						AND cat_status = '1' 
					ORDER BY CAST(`cat_root` AS SIGNED) ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byRoot"){
            $sql = "SELECT * FROM _expert_category WHERE section_id = '".$sectionId."' AND cat_root = '".$this->catRoot."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt="nameByRoot"){
            $catName = "";
            if($this->catRoot!=""){
                $sql = "SELECT cat_name FROM _expert_category WHERE cat_id IN (".$this->catRoot.")";
                $query = $this->db->query($sql);
                $data = $query->result_array();
                for($i=0;$i<count($data);$i++){
                    $catName .= $data[$i]['cat_name'];
                    if($i<count($data)-1) $catName .= ", ";
                }
            }
            return $catName;
        }

    }


    function update_cat_order($dataOrder,$catParent=0,&$catOrder=1){
        foreach($dataOrder as $data){
            $catId = $data->id;
            if($catParent==0){
                $catLevel = 1;
                $catRoot = $catId;
                $lastParent = 0;
            }
            else{
                $this->catId = $catParent;
                $dataParent = $this->select_category("byId",$this->sectionId);
                $catLevel = $dataParent['cat_level']+1;
                $catRoot = $dataParent['cat_root'].",".$catId;
                $lastParent = $catParent;
            }
            $sql = "UPDATE _expert_category 
					SET cat_level = '".$catLevel."', 
						cat_parent = '".$catParent."', 
						cat_root = '".$catRoot."', 
						cat_order = '".$catOrder."'  
					WHERE cat_id = '".$catId."' ";
            $this->db->query($sql);
            if(isset($data->children)){
                $catParent = $catId;
                $catOrder++;
                $this->update_cat_order($data->children,$catId,$catOrder);
                $catParent = $lastParent;
                $catOrder--;
            }
            $catOrder++;
        }
    }

    function form_add_category(){
        if(isset($_POST['addCat'])){
            global $asParent;
            $_SESSION['formType']="add";
            $this->catName = ucwords(security($_POST['catName']));
            $this->catAlias = generate_alias($this->catName);
            $this->catDesc = $_SESSION['Admine']['GroupId'];
            $this->catColor = ucwords(security($_POST['catColor']));
            $asParent = intval($_POST['asParent']);
            if($asParent=="1"){
                $this->catParent = "0";
                $this->catLevel = "1";
                $this->catRoot = "";
                $this->catOrder = intval($this->get_max_order())+1;
            }
            if($asParent=="0"){
                if(isset($_POST['catParent'])){
                    $arrParent = explode("-",security($_POST['catParent']));
                    $this->catParent = $arrParent[0];
                    $this->catLevel = $arrParent[1]+1;
                    $this->catRoot = $arrParent[2];
                    $this->catOrder = intval($this->get_new_order($this->catParent))+1;
                }
            }

            if($asParent==0 && !isset($_POST['catParent'])){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Silahkan pilih sub kategori.");
                $_SESSION['errotCat'] = "subcat";
            }
            elseif($this->catName==""){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Silahkan isi nama kategori.");
            }
            elseif($this->is_cat_exists()===true){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Duplikat kategori <strong>".$this->catName."</strong>.");
            }
            else{
                if(isset($_FILES['catImage']) && $_FILES["catImage"]["name"]!=""){
                    $upload_directory 	= "media/image/";
                    $allowedExt 		= array("png","jpg","jpeg","gif");
                    $mimeType			= array("image/jpg","image/png","image/gif");
                    $arrExt 			= explode(".", $_FILES["catImage"]["name"]);
                    $imgExt 			= strtolower(end($arrExt));
                    $this->catImage	= "cat-".time().".".$imgExt;

                    if($_FILES['catImage']['size'] > 2000000){
                        $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Ukuran gambar maksimal 2 MB.");
                    }
                    elseif(!in_array($_FILES["catImage"]["type"],$mimeType) && !in_array($imgExt,$allowedExt)){
                        $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Format gambar yang diijinkan : .jpg, .png dan .gif");
                    }
                    else{
                        move_uploaded_file($_FILES['catImage']['tmp_name'], $upload_directory.$this->catImage);
                    }
                }

                if($_SESSION['TxtMsg']['status']!="0"){
                    if($asParent=="0") {
                        $this->reorder(($this->catOrder - 1));
                    }
                    $this->insert_category();
                    $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Penambahan kategori <strong>".$this->catName."</strong> berhasil.");

                }
            }
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }

    function form_edit_category(){
        if(isset($_POST['editCat'])){
            $_SESSION['formType']="edit";
            $this->catId = intval($_POST['catIdEdit']);
            $detailCat = $this->select_category("byId",$this->sectionId);
            $catName = security($_POST['catNameEdit']);
            $this->catName = ucwords(security($_POST['catName']));
            $this->catColor = ucwords(security($_POST['catColor']));
            $this->catAlias = generate_alias($this->catName);
            $this->catDesc = $_SESSION['Admine']['GroupId'];
            $this->catParent = $detailCat['cat_parent'];
            $this->catLevel = $detailCat['cat_level'];
            $this->catRoot = $detailCat['cat_root'];
            $this->catStatus = $_POST['catStatus'];
            $this->catImage = $detailCat['cat_image'];
            if($this->catName==""){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Nama kategori tidak boleh kosong.");
            }
            if($_FILES["catImage"]["name"]!=""){
                $upload_directory 	= "media/image/";
                $allowedExt 		= array("png","jpg","jpeg","gif");
                $mimeType			= array("image/jpg","image/png","image/gif");
                $arrExt 			= explode(".", $_FILES["catImage"]["name"]);
                $imgExt 			= strtolower(end($arrExt));
                $this->catImage	= "cat-".time().".".$imgExt;

                if($_FILES['catImage']['size'] > 2000000){
                    $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Ukuran gambar maksimal 2 MB.");
                }
                elseif(!in_array($_FILES["catImage"]["type"],$mimeType) && !in_array($imgExt,$allowedExt)){
                    $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Format gambar yang diijinkan .jpg, .png dan .gif");
                }
                else{
                    move_uploaded_file($_FILES['catImage']['tmp_name'], $upload_directory.$this->catImage);
                }
            }

            if($_SESSION['TxtMsg']['status']!="0"){
                $this->update_category();
                $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Kategori <strong>".$catName."</strong> berhasil diperbarui.");
            }
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }

    function form_update_order_category(){
        if(isset($_POST['updateCatOrder'])){
            $_SESSION['formType']="edit";
            $catOrder = json_decode(stripslashes($_POST['catOrder']));
            if(count($catOrder)>0){
                $this->update_cat_order($catOrder);
                $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Urutan kategori berhasil diperbarui.");
                header("Location:".$_SERVER['HTTP_REFERER']);exit;
            }
        }
    }

    function form_delete_category(){
        if(isset($_POST['delCat'])){
            $_SESSION['formType']="delete";
            $this->catId = security($_POST['catIdDel']);
            $catName = security($_POST['catNameDel']);
            $this->delete_category();
            $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Kategori <strong>".$catName."</strong> telah dihapus.");
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }
    // CATEGORY EXPERT END //

    // EXPERT MEMBER START //
    function insert_expert_member($recData){
        $sql = "INSERT INTO _expert_member(cat_id,member_id,group_id,em_concern,em_name,em_profil,em_image,
										   em_education,em_experience,em_qualification,em_status,em_create_date)  
				VALUES('".$recData['catId']."','".$recData['memberId']."','".$recData['groupId']."',
					   '".$recData['emConcern']."','".$recData['emName']."','".$recData['emProfil']."',
					   '".$recData['emImage']."','".$recData['emEducation']."','".$recData['emExperience']."',
					   '".$recData['emQualification']."','".$recData['emStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();;
    }

    function update_expert_member($opt="",$recData,$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _expert_member
					   SET cat_id			= '".$recData['catId']."', 
						   member_id		= '".$recData['memberId']."', 
						   group_id			= '".$recData['groupId']."', 
						   em_concern	 	= '".$recData['emConcern']."', 
						   em_name			= '".$recData['emName']."', 
						   em_profil		= '".$recData['emProfil']."', 
						   em_image			= '".$recData['emImage']."', 
						   em_education		= '".$recData['emEducation']."', 
						   em_experience 	= '".$recData['emExperience']."', 
						   em_qualification	= '".$recData['emQualification']."', 
						   em_status		= '".$recData['emStatus']."'
				     WHERE em_id 			= '".$recData['emId']."' ";
            echo $sql;
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="setStatus"){
            $sql = "UPDATE _expert_member
					   SET em_status	= '".$recData['emStatus']."'
					WHERE em_id 		= '".$recData['emId']."'";
            $result = $this->db->query($sql);
            return $result;
        }
    }

    function get_group_expert_member_byid($emId){
        $sql = "SELECT group_id FROM _expert_member WHERE em_id = '".$emId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['group_id'];
    }

    function delete_expert_member($emId){
        $sql = "DELETE FROM _expert_member WHERE em_id = '".$emId."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_expert_member($opt="",$catId="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _expert_member ";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' ";
            }
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_member";
            if(intval($catId)>0){
                $sql .= " WHERE cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _expert_member WHERE em_status = 'active' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_member 
					WHERE expert_status = 'open' ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _expert_member WHERE em_id = '".$this->recData['emId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _expert_member WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:NULL;
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_member WHERE member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $opsi = $this->input->post('opsi');
            $keyword = $this->input->post('keyword');
            $sql = "SELECT em.*,m.member_city FROM _expert_member em LEFT JOIN _member m ON em.member_id=m.member_id";
            if ($opsi == 'nama'){
//            if($_SESSION['Search']['Type']=="Nama"){
                $sql .= " WHERE em.em_name LIKE '%".$keyword."%'";
            } elseif ($opsi == 'lokasi'){
//            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " WHERE em.member_id IN (SELECT member_id FROM _member WHERE member_city LIKE '%".$this->input->post('keyword')."%')";
            } elseif ($opsi == 'bidang'){
//            if($_SESSION['Search']['Type']=="Member"){
                $sql .= " WHERE em.em_concern LIKE '%".$keyword."%'";
            } elseif ($opsi == 'auto'){
                $sql .= " WHERE (em.em_name LIKE '%".$keyword."%' OR em.member_id IN (SELECT member_id FROM _member WHERE member_city LIKE '%".$keyword."%') 
                OR em.em_concern LIKE '%".$keyword."%')";
            }
            if ($this->input->post('kategori') != ''){
                $sql .= " AND em.cat_id = '".$this->input->post('kategori')."'";
            }
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _expert_member ";

            if($_SESSION['Search']['Type']=="Nama"){
                $sql .= " WHERE em_name LIKE '%".$_SESSION['Search']['Keyword']."%'";
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

    function search_expert_member($kw,$catId){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        $result = array();
        $sql = "SELECT * ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when em_name like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end) as priority";
        }

        $sql .= " FROM _expert_member WHERE em_status = 'active' ";

        if(intval($catId)>0){
            $sql .= " AND cat_id = '".$catId."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " em_name LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }

        $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;

        //echo $sql;exit;
        $query = $this->db->query($sql);
        $data = $query->result_array(); //p($data);exit;
        return $data;
    }

    function select_quick_expert_member(){
        $sql = "SELECT a.em_id, a.em_name, b.group_name 
				  FROM _expert_name a, _group b 
				 WHERE a.group_id = b.group_id
				 ORDER BY a.expert_id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
    // EXPERT MEMBER END //


    // new update dec 2021
    function get_count_unread_chat($latest_read_id, $is_expert_member=false){
        if ($is_expert_member){
            $sql = "SELECT COUNT(*) total FROM `_expert_chat` WHERE expert_id = '".$this->recData['expertId']."' AND ec_id > $latest_read_id ORDER BY ec_id DESC";
        } else {
            $sql = "SELECT COUNT(*) total FROM `_expert_chat` WHERE expert_id = '".$this->recData['expertId']."' AND '".$this->recData['memberId']."' IN (SELECT member_id FROM `_expert_chat` WHERE expert_id='".$this->recData['expertId']."') AND ec_id > $latest_read_id ORDER BY ec_id DESC";
        }
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data?$data[0]['total']:0;
    }

    function get_latest_read_chat_id(){
        $sql = "SELECT * FROM `_expert_chat` WHERE expert_id = '".$this->recData['expertId']."' AND member_id ='".$this->recData['memberId']."' ORDER BY ec_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data?$data[0]['ec_latest_read_id']:0;
    }

    function update_latest_read_chat_id($ec_id){
        $sql = "UPDATE `_expert_chat` SET ec_latest_read_id = '$ec_id' WHERE expert_id = '".$this->recData['expertId']."' AND member_id = '".$this->recData['memberId']."' ORDER BY ec_id DESC LIMIT 1";
        return $this->db->query($sql);
    }

}
