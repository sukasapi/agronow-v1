<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 30/08/20
 * Time: 0:46
 * @property CI_DB_query_builder db
 * @property Function_api function_api
 */

class Content_model extends CI_Model
{
    var $recData = array("contentId"=>"","sectionId"=>"","catId"=>"","memberId"=>"","groupId"=>"","mlevelId"=>"",
        "contentName"=>"","contentAlias"=>"","contentDesc"=>"","contentTags"=>"",
        "contentHits"=>"","contentSource"=>"","contentAuthor"=>"","contentBidang"=>"",
        "contentSeoTitle"=>"search_content","contentSeoKeyword"=>"",
        "contentSeoDesc"=>"","contentStatus"=>"","contentPublishDate"=>"",
        "contentCreateDate"=>"","contentCreateBy"=>"","contentNotif"=>"",

        "tagsId"=>"","tagsName"=>"","tagsAlias"=>"","tagsCount"=>"",

        "commentId"=>"","commentName"=>"","commentEmail"=>"","commentPhone"=>"","commentWeb"=>"",
        "commentText"=>"","commentType"=>"","commentIp"=>"","commentStatus"=>"",
        "commentLike"=>"","commentRate"=>"",

        "cdId"=>"","cdDate"=>"","cdChannel"=>"",

        "catgroupId"=>"","catgroupName"=>"","catgroupAlias"=>"","catgroupImage"=>"",
        "catgroupStatus"=>"","catgroupOrder"=>"","catgroupCreateDate"=>"","catgroupCreateBy"=>"",

        "contentHitsChannel"=>"","contentHitsDate"=>"",
        "notifId"=>"","notifData"=>"","notifDate"=>"","notifPushDate"=>"","notifStatus"=>"",
        "NotifSendTotal"=>"","notifSendAndroid"=>"","notifSendIos"=>"","notifReceiveTotal"=>"",
        "notifReceiveAndroid"=>"","notifReceiveIos"=>"",

        "contentTypeID" => ""
    );

    var $lastInsertId;
    var $beginRec,$endRec;

    function __construct()
    {
        parent::__construct();
        $this->load->library('function_api');
    }

    // function general
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
    // end function general

    function select_reading_learning(){
        $sql = "SELECT * FROM _content WHERE section_id IN('28','29')";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // CONTENT START //
    function insert_content($recData){
        $data = [
            'section_id'        => $recData['sectionId'],
            'cat_id'            => $recData['catId'],
            'member_id'         => $recData['memberId'],
            'group_id'          => $recData['groupId'],
            'mlevel_id'         => $recData['mlevelId'],
            'content_name'      => $recData['contentName'],
            'content_alias'     => $recData['contentAlias'],
            'content_desc'      => $recData['contentDesc'],
            'content_tags'      => $recData['contentTags'],
            'content_hits'      => $recData['contentHits'],
            'content_source'    => $recData['contentSource'],
            'content_author'    => $recData['contentAuthor'],
            'content_bidang'    => $recData['contentBidang'],
            'content_seo_title' => $recData['contentSeoTitle'],
            'content_seo_keyword'   => $recData['contentSeoKeyword'],
            'content_seo_desc'  => $recData['contentSeoDesc'],
            'content_type_id'   => $recData['contentTypeID'],
            'content_status'    => $recData['contentStatus'],
            'content_publish_date'  => $recData['contentPublishDate'],
            'content_create_date'   => date('Y-m-d H:i:s'),
            'content_create_by' => $recData['contentCreateBy'],
            'content_notif'     => $recData['contentNotif'],
            'crm_id'            => isset($recData['crmId']) ? "'".$recData['crmId']."'" : NULL
        ];
        $this->db->insert('_content', $data);
//        $sql = "INSERT INTO _content
//				VALUES ('','".$recData['sectionId']."','".$recData['catId']."','".$recData['memberId']."','".$recData['groupId']."',
//						'".$recData['mlevelId']."','".$recData['contentName']."','".$recData['contentAlias']."','".$recData['contentDesc']."',
//						'".$recData['contentTags']."','".$recData['contentHits']."','".$recData['contentSource']."',
//						'".$recData['contentAuthor']."','".$recData['contentBidang']."','".$recData['contentSeoTitle']."',
//						'".$recData['contentSeoKeyword']."','".$recData['contentSeoDesc']."','".$recData['contentTypeID']."',
//						'".$recData['contentStatus']."','".$recData['contentPublishDate']."',NOW(),
//						'".$recData['contentCreateBy']."','".$recData['contentNotif']."', ".(isset($recData['crmId']) ? "'".$recData['crmId']."'" : '')."
//						)";
//
//        $this->db->query($sql);
        $this->lastInsertId = $this->db->insert_id();

        if($recData['contentTags']!=""){
            $arrTags = explode(",",$recData['contentTags']);
            for($i=0;$i<count($arrTags);$i++){
                $dataTags['sectionId'] = $recData['sectionId'];
                $dataTags['tagsName'] = trim($arrTags[$i]);
                $dataTags['tagsAlias'] = $this->function_api->generate_alias($dataTags['tagsName']);
                $this->recData['tagsName'] = $dataTags['tagsName'];
                $dataTag = $this->select_content_tags("byName");
                if(!empty($datTag) && count($dataTag)==0) $this->insert_content_tags($dataTags);
                if(!empty($dataTag) && count($dataTag)>0) $this->update_content_tags("plus");
            }
        }

        return $this->lastInsertId;
    }

    function update_content($opt="",$data=array(),$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _content 
					SET section_id 			= '".$data['sectionId']."', 
						cat_id				= '".$data['catId']."', 
						member_id			= '".$data['memberId']."', 
						group_id			= '".$data['groupId']."',
						mlevel_id			= '".$data['mlevelId']."',
						content_name		= '".$data['contentName']."', 
						content_alias		= '".$data['contentAlias']."', 
						content_desc		= '".$data['contentDesc']."', 
						content_tags		= '".$data['contentTags']."', 
						content_hits		= '".$data['contentHits']."', 
						content_source		= '".$data['contentSource']."', 
						content_author		= '".$data['contentAuthor']."', 
						content_bidang		= '".$data['contentBidang']."', 
						content_seo_title	= '".$data['contentSeoTitle']."', 
						content_seo_keyword	= '".$data['contentSeoKeyword']."', 
						content_seo_desc	= '".$data['contentSeoDesc']."', 
						content_status		= '".$data['contentStatus']."', 
						content_publish_date= '".$data['contentPublishDate']."', 
						content_create_by	= '".$data['contentCreateBy']."', 
						content_notif		= '".$data['contentNotif']."' 
					WHERE content_id = '".$data['contentId']."' ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
        elseif($opt=="hits"){
            $sql = "UPDATE _content SET content_hits = content_hits+1 WHERE content_id = '".$data['contentId']."' ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _content SET ".$field." = '".$value."' WHERE content_id = '".$data['contentId']."' ";
            $this->db->query();
            $result = $this->db->affected_rows();
            return $result;
        }
        elseif($opt=="setPublish"){
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE _content SET content_status = 'publish' 
					WHERE content_status = 'draft' 
					AND STR_TO_DATE(content_publish_date, '%Y-%m-%d %H:%i:%s') < NOW()
					AND content_publish_date > content_create_date ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
    }

    function delete_content($contentId){
        $sql = "DELETE FROM _content WHERE content_id IN('".$contentId."')";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_content($opt="",$catId="",$limit="",$mlevelId="",$bidang="", $group_id=""){
        $levelId = "";
        if(intval($mlevelId)>0){
            $levelId = "'all'";
            for($i=intval($mlevelId);$i<=6;$i++){
                $levelId .= ",'".$i."'";
            }
        }

        if($opt==""){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }

            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id = 'all' ";
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $sql .= "	ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _content
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";

            if($this->recData['contentTypeID'] != ''){
                $sql .= " AND content_type_id='".$this->recData['contentTypeID']."' ";
            }

            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            if($this->recData['contentTypeID'] != ''){
                $sql .= " AND content_type_id = ".$this->recData['contentTypeID']." ";
            }

            if ($group_id){
                $sql .= " AND group_id = '$group_id'";
            }

            $sql .=" ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            if($this->recData['contentTypeID'] != ''){
                $sql .= " AND content_type_id='".$this->recData['contentTypeID']."' ";
            }
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $query = $this->db->query($sql);
            $result = $query->row();
            return $result->TOTAL;
        }
        elseif($opt=="CeoNotesMyList" || $opt=="BodShareMyList"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."'  
					AND member_id = '".$this->recData['memberId']."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }

            $sql .=" ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countCeoNotesMyList" || $opt=="countBodShareMyList"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."'  
					AND member_id = '".$this->recData['memberId']."' ";

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
        elseif($opt=="popularPublish"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }

            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $sql .=" ORDER BY content_hits DESC, content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="freemium"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND CONCAT(',',group_id,',') LIKE '%,".$this->recData['groupId'].",%' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }

            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $sql .=" ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countFreemium"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND CONCAT(',',group_id,',') LIKE '%,".$this->recData['groupId'].",%' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }

            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            if($bidang!="" && $bidang!="all"){
                $sql .= " AND ( content_bidang = 'all' OR  content_bidang LIKE '%".$bidang."%' )";
            }

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _content WHERE content_alias = '".$this->recData['contentAlias']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result?$result[0]:NULL;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _content WHERE content_id = '".$this->recData['contentId']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result?$result[0]:NULL;
        }
        elseif($opt=="pushNotif"){
            $sql = "SELECT * FROM _content 
					WHERE content_notif = '1' 
					AND content_status = 'publish' 
					AND content_publish_date != content_create_date 
					AND DATE_FORMAT(content_publish_date,'%Y-%m-%d %H:%i') = '".date('Y-m-d H:i')."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="popular"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            if($catId!=""){
                $sql .= " AND cat_id IN('".$catId."') ";
            }
            $sql .=" ORDER BY content_publish_date DESC, content_hits DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="recommended"){
            $where = '';
            if($this->recData['contentTypeID'] != ''){
                $where .= " AND content_type_id='".$this->recData['contentTypeID']."' ";
            }

            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' ".$where."
					ORDER BY RAND() 
					LIMIT 0,".$limit." 
					";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="sharing"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."'  
						AND member_id = '".$this->recData['memberId']."'  
					ORDER BY content_create_date DESC 
					";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="countSharing"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND member_id = '".$this->recData['memberId']."' 
					";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }elseif ($opt=="unread"){
            $sql = "SELECT c.*
                    FROM _content c, _member m
                    WHERE m.member_id = '".$this->recData['memberId']."'
                    AND c.content_publish_date >= DATE_SUB(NOW(), INTERVAL 4 MONTH) 
                    AND c.content_notif = 1";
            if ($this->recData['sectionId']){
                if (is_array($this->recData['sectionId'])){
                    $secs = implode(", ", $this->recData['sectionId']);
                    $sql .= " AND c.section_id IN (".$secs.")";
                } else {
                    $sql .= " AND c.section_id = '".$this->recData['sectionId']."'";
                }
            }
            if($bidang!="" && $bidang!="all"){
                $sql .= " AND (c.content_bidang = 'all' OR c.content_bidang LIKE '%".$bidang."%' )";
            }
            $sql .= " AND c.content_publish_date >= m.member_create_date AND c.content_status = 'publish'
                          AND c.content_id NOT IN
                              (SELECT ch.content_id FROM _content_hits ch WHERE ch.member_id = '".$this->recData['memberId']."' GROUP BY ch.content_id)
                    ORDER BY c.content_publish_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }elseif ($opt=="countUnread"){
            $sql = "SELECT COUNT(c.content_id) TOTAL 
                    FROM _content c, _member m
                    WHERE m.member_id = '".$this->recData['memberId']."'
                    AND c.content_publish_date >= DATE_SUB(NOW(), INTERVAL 4 MONTH) 
                    AND c.content_notif = 1";
            if ($this->recData['sectionId']){
                if (is_array($this->recData['sectionId'])){
                    $secs = implode(", ", $this->recData['sectionId']);
                    $sql .= " AND c.section_id IN (".$secs.")";
                } else {
                    $sql .= " AND c.section_id = '".$this->recData['sectionId']."'";
                }
            }
            if($bidang!="" && $bidang!="all"){
                $sql .= " AND (c.content_bidang = 'all' OR c.content_bidang LIKE '%".$bidang."%' )";
            }
            $sql .= " AND c.content_publish_date >= m.member_create_date AND c.content_status = 'publish' 
                          AND c.content_id NOT IN
                              (SELECT ch.content_id FROM _content_hits ch WHERE ch.member_id = '".$this->recData['memberId']."' GROUP BY ch.content_id)";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result[0]['TOTAL'];
        }
    }

    function select_pending(){
        $sql = "SELECT * FROM _content WHERE content_publish_date > '2018-07-20 00:00:01' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function select_content_group($opt="",$catId="",$limit="",$mlevelId="",$bidang=""){
        $levelId = "";
        if(intval($mlevelId)>0){
            $levelId = "'all'";
            for($i=intval($mlevelId);$i<=6;$i++){
                $levelId .= ",'".$i."'";
            }
        }

        if($opt==""){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' 
						AND cat_id ='".$catId."' ";
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }
            $sql .= " 	ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' 
						AND cat_id = '".$catId."' ";
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="all"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' 
					ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countAll"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' 
						AND mlevel_id >= '".$this->recData['mlevelId']."' 
						AND cat_id ='".$catId."' ";
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }
            $sql .= " 	AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' 
					ORDER BY content_publish_date DESC, content_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND group_id = '".$this->recData['groupId']."' 
						AND mlevel_id >= '".$this->recData['mlevelId']."' 
						AND cat_id = '".$catId."'  ";
            if(intval($mlevelId)>0){
                $sql .= " AND mlevel_id IN(".$levelId.") ";
            }
            $sql .= " 	AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function related_by_tags($contentId,$tags=array(),$limit){
        if(count($tags)>0){
            $sql = "SELECT * FROM _content 
					WHERE section_id = '".$this->recData['sectionId']."' 
						AND content_status = 'publish' 
						AND content_publish_date <= '".date('Y-m-d H:i:s')."' 
						AND content_id != '".$contentId."' 
						AND (";
            for($i=0;$i<count($tags);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " CONCAT(',',LOWER(content_tags),',') LIKE '%,".strtolower($tags[$i])."%,' ";
            }
            $sql .= ")";
            $sql .=" ORDER BY content_publish_date DESC, content_hits DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    function count_in_cat($catId,$groupId=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _content WHERE cat_id = '".$catId."' ";
        if($groupId!=""){
            $sql .= " AND CONCAT(',',group_id,',') LIKE '%,".$groupId.",%' ";
        }
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function is_content_detail($alias="",$sectionId=""){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _content WHERE content_alias = '".$alias."' 
					AND section_id = '".$sectionId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function search_content_new($kw="",$type="",$sectionName="",$isLogin="",$limit="",$groupId=""){
        $keyword = explode("-",$this->function_api->generate_alias($kw));

        if($type=="Pengarang"){
            $field = "a.content_author";
        }
        else{
            $field = "a.content_name";
        }
        $result = array();

        $sql = "SELECT a.content_id, a.member_id, a.section_id, a.content_name, a.content_desc, a.content_publish_date, b.section_alias_front , a.content_hits 
                               FROM _content a, _section b 
                            WHERE a.section_id = b.section_id 
                            AND a.section_id != '7' 
                            AND a.content_status = 'publish' 
                            AND a.content_publish_date <= '".date('Y-m-d H:i:s')."' ";

        if($sectionName=="" && $isLogin===0){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman') ";
        }
        elseif($sectionName=="" && $isLogin===1){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman','knowledge','elearning') ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }
        else{
            $sql .= " AND b.section_alias_front = '".$sectionName."' ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= $field." LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " )  ";
        }
        $sql .= " ORDER BY a.content_publish_date DESC ";

        if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
        else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }



    function search_content($kw,$type="",$sectionName="",$isLogin="",$limit="",$groupId=""){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        if($type=="Pengarang"){
            $field = "a.content_author";
        }
        else{
            $field = "a.content_name";
        }
        $result = array();
        $sql = "SELECT a.*,b.section_alias_front ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when ".$field." like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end)  as priority";
        }

        $sql .= " FROM _content a, _section b 
				WHERE a.section_id = b.section_id 
					AND a.section_id != '7' 
					AND a.content_status = 'publish' 
					AND a.content_publish_date <= '".date('Y-m-d H:i:s')."' ";

        if($sectionName=="" && $isLogin===0){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman') ";
        }
        elseif($sectionName=="" && $isLogin===1){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman','knowledge','elearning') ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }
        else{
            $sql .= " AND b.section_alias_front = '".$sectionName."' ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= $field." LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY priority, a.content_publish_date DESC ";

        if(intval($limit)>0){
            $sql .= " LIMIT 0,".$limit;
        }
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function count_search_content($kw,$type="",$sectionName="",$isLogin="",$limit="",$groupId=""){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        if($type=="Pengarang"){
            $field = "a.content_author";
        }
        else{
            $field = "a.content_name";
        }
        $result = array();
        $sql = "SELECT COUNT(*) AS TOTAL ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when ".$field." like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end)  as priority";
        }

        $sql .= " FROM _content a, _section b 
				WHERE a.section_id = b.section_id 
					AND a.section_id != '7' 
					AND a.content_status = 'publish' 
					AND a.content_publish_date <= '".date('Y-m-d H:i:s')."' ";

        if($sectionName=="" && $isLogin===0){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman') ";
        }
        elseif($sectionName=="" && $isLogin===1){
            $sql .= " AND b.section_alias_front IN('berita','artikel','pengumuman','knowledge','elearning','berita-ptpn','berita-rni') ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }
        else{
            $sql .= " AND b.section_alias_front = '".$sectionName."' ";
            if(intval($groupId)>0){
                $sql .= " AND (a.group_id IN('all','') OR CONCAT(',',a.group_id,',') LIKE '%,".$groupId.",%' )";
            }
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= $field." LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }

        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function search_content_group($kw,$sectionId="",$catId="",$limit=""){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        $result = array();
        $sql = "SELECT a.*,b.section_alias_front ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when a.content_name like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end)  as priority";
        }

        $sql .= " FROM _content a, _section b 
				WHERE a.section_id = b.section_id 
					AND a.section_id != '7' 
					AND a.content_status = 'publish' 
					AND a.content_publish_date <= '".date('Y-m-d H:i:s')."' 
					AND a.section_id = '".$sectionId."' 
					AND a.mlevel_id >= '".$this->recData['mlevel_id']."' ";

        if(intval($catId)!=0){
            $sql .= " AND a.cat_id = '".$catId."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " a.content_name LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY priority, a.content_publish_date DESC ";

        if(intval($limit)>0){
            $sql .= " LIMIT 0,".$limit;
        }
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function search_elearning($kw,$sectionId="",$catId="",$limit=""){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        $result = array();
        $sql = "SELECT a.*,b.section_alias_front ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when a.content_name like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end) as priority";
        }

        $sql .= " FROM _content a, _section b 
				WHERE a.section_id = b.section_id 
					AND a.content_status = 'publish' 
					AND a.content_publish_date <= '".date('Y-m-d H:i:s')."' 
					AND a.section_id = '".$sectionId."' ";

        if(intval($catId)!=0){
            $sql .= " AND a.cat_id = '".$catId."' ";
        }

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= " a.content_name LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY priority, a.content_publish_date DESC ";

        if(intval($limit)>0){
            $sql .= " LIMIT 0,".$limit;
        }
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }


    /*function search_content_group($kw,$limit=""){

        $sql = "SELECT * FROM _content WHERE section_id = '".$this->recData['sectionId']."'
                AND content_name LIKE '%".$kw."%'
                ORDER BY content_publish_date DESC ";

        if(intval($limit)>0){
            $sql .= " LIMIT 0,".$limit;
        }
        //echo $sql;exit;
        $data = $this->doQuery($sql); //p($data);exit;
        return $data;
    }*/

    function get_section_id($contentId){
        $sql = "SELECT section_id FROM _content WHERE content_id = '".$contentId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['section_id'];
    }


    // CONTENT END //




    // TAGS START //

    function insert_content_tags($data){
        $sql = "INSERT INTO _content_tags 
				VALUES('','".$data['sectionId']."','".$data['tagsName']."','".$data['tagsAlias']."','1') ";
        $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_content_tags($opt=""){
        if($opt=="plus") $opr = "+";
        if($opt=="min") $opr = "-";
        $sql = "UPDATE _content_tags SET tags_count = tags_count ".$opr." 1 WHERE tags_name = '".$this->recData['tagsName']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function select_content_tags($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _content_tags ORDER BY tags_count DESC ";
            if($limit!="") $sql .= " LIMIT 0,".$limit;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byName"){
            $sql = "SELECT * FROM _content_tags WHERE tags_name = '".$this->recData['tagsName']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            if(count($data)==0){$data[0] = array();}
            return $data[0];
        }

    }

    // TAGS END //

    // COMMENT START //

    function insert_content_comment($data){
        $sql = "INSERT INTO _content_comment 
				VALUES('','".$data['sectionId']."','".$data['contentId']."','".$data['memberId']."','".$data['commentName']."',
						'".$data['commentEmail']."','".$data['commentPhone']."','".$data['commentWeb']."',
						'".$data['commentText']."','".$data['commentType']."','".$_SERVER['REMOTE_ADDR']."',
						'".$data['commentStatus']."','".$data['commentLike']."','".$data['commentRate']."',NOW()) 					
					"; //echo $sql;exit;
        $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function select_content_comment($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _content_comment 
					ORDER BY comment_create_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit." ";
            }else{
                $sql .= " LIMIT " . $this->beginRec . "," . $this->endRec . "";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_comment ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byContentId"){
            $sql = "SELECT * FROM _content_comment WHERE content_id = '".$this->recData['contentId']."' 
					ORDER BY comment_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _content_comment WHERE comment_id = '".$this->recData['commentId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="likeByContentId"){
            $sql = "SELECT * FROM _content_comment 
							WHERE comment_like = 'like'  AND content_id = '".$this->recData['contentId']."'  
					ORDER BY comment_create_date DESC";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit." ";
            }else{
                $sql .= " LIMIT " . $this->beginRec . "," . $this->endRec . "";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countLikeByContentId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_comment 
							WHERE comment_like = 'like'  AND content_id = '".$this->recData['contentId']."'  ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="commentByContentId"){
            $sql = "SELECT a.*, b.member_name,b.member_image, c.group_name 
						FROM _content_comment  a, _member b, _group c 
							WHERE a.user_id = b.member_id AND b.group_id = c.group_id 
								AND a.comment_type = 'comment'  
								AND a.comment_text != '' AND a.content_id = '".$this->recData['contentId']."'  
					ORDER BY a.comment_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countCommentByContentId"){
            $sql = "SELECT COUNT(*) AS TOTAL 
						FROM _content_comment  a, _member b, _group c 
							WHERE a.user_id = b.member_id AND b.group_id = c.group_id 
								AND a.comment_type = 'comment'  
								AND a.comment_text != '' AND a.content_id = '".$this->recData['contentId']."'  ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }


    function delete_content_comment(){
        $sql = "DELETE FROM _content_comment WHERE comment_id = '".$this->recData['commentId']."' ";
        $this->db->query($sql);
    }

    function is_member_like($contentId,$memberId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _content_comment 
						WHERE content_id = '".$contentId."'  AND user_id = '".$memberId."' 
							AND comment_like = 'like' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function member_has_comment_like($contentId,$memberId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _content_comment 
						WHERE content_id = '".$contentId."'  AND user_id = '".$memberId."'  ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }


    function update_content_comment($opt="",$recData=array()){
        if($opt=="like"){
            $sql = "UPDATE _content_comment 
						SET comment_like = 'like' 
						WHERE content_id = '".$recData['content_id']."' AND user_id = '".$recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    // COMMENT END //


    // CONTENT DOWNLOAD START //

    function insert_content_download($recData){
        $sql = "INSERT INTO _content_download 
				VALUES('','".$recData['contentId']."','".$recData['memberId']."',NOW(),'".$recData['cdChannel']."')";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function select_content_download($opt=""){
        if($opt==""){
            $sql = "SELECT COUNT(*) AS TOTAL 
					FROM (SELECT * FROM `_content_download` GROUP BY content_id,member_id) AS data 
					WHERE content_id = '".$this->recData['contentId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByMember"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_download 
					WHERE member_id = '".$this->recData['memberId']."' 
					GROUP BY content_id ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByChannel"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_download 
					WHERE cd_channel = '".$this->recData['cdChannel']."' 
					GROUP BY content_id, member_id ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="groupByChannel"){
            $sql = "SELECT cd_channel, COUNT(*) AS TOTAL FROM _content_download 
					GROUP BY cd_channel";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countAll"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM (SELECT * FROM `_content_download` GROUP BY content_id,member_id) AS data";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    // CONTENT DOWNLOAD END //


    // CONTENT BOOKMARK START //
    function is_bookmark($memberId,$contentId){
        $result = false;
        //$dataBookmark = array();
        $sql = "SELECT content_id FROM _member_bookmark WHERE member_id = '".intval($memberId)."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)>0){
            $dataId = array_column($data, 'content_id');
            if(in_array($contentId,$dataId)){
                $result = true;
            }
        }
        return $result;

    }

    function select_content_bookmark($opt="",$catId="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _content 
					WHERE content_id IN(SELECT content_id FROM _member_bookmark 
										WHERE member_id = '".$this->recData['memberId']."') ";

            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }

            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE content_id IN(SELECT content_id FROM _member_bookmark 
										WHERE member_id = '".$this->recData['memberId']."') ";
            if(intval($catId)>0){
                $sql .= " AND cat_id = '".$catId."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    // CONTENT BOOKMARK END //


    function statistic_view($sectionId,$limit){
        $sql = "SELECT content_id,content_name, content_create_date, content_hits 
				FROM _content 
				WHERE section_id = '".$sectionId."' 
				ORDER BY content_hits DESC LIMIT ".$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function statistic_view_group($sectionId,$catId,$limit){
        $sql = "SELECT a.content_id, b.content_name, count(*) AS TOTAL_HITS, count(DISTINCT(member_id)) AS TOTAL_USER 
				FROM _content_hits a 
				LEFT JOIN (SELECT content_id, section_id, cat_id, content_name FROM _content) AS b 
					ON a.content_id = b.content_id 
				WHERE b.section_id = '".$sectionId."' AND b.cat_id = '".$catId."' 
				GROUP BY a.content_id
				ORDER BY TOTAL_HITS DESC LIMIT ".$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function statistic_member_view_group($contentId){
        $sql = "SELECT a.content_id, a.member_id, COUNT(*) AS TOTAL_HITS, b.member_nip, b.member_name 
				FROM _content_hits a, _member b 
				WHERE a.member_id = b.member_id AND a.content_id = '".$contentId."' 
				GROUP BY a.member_id 
				ORDER BY TOTAL_HITS DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }


    // NOTIF LOG

    function insert_notif_log($recData){
        $sql = "INSERT INTO _notif_log 
				VALUES('','".$recData['sectionId']."','".$recData['contentId']."','".$recData['groupId']."',
						'".$recData['mlevelId']."','".$recData['notifData']."','".$recData['notifDate']."',
						'".$recData['notifPushDate']."','".$recData['notifStatus']."','0','0','0','0','0','0')";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function update_notif_log($recData){
        $sql = "UPDATE _notif_log 
				SET notif_push_date = NOW(),
					notif_send_total = '".$recData['notifSendTotal']."', 
					notif_send_android = '".$recData['notifSendAndroid']."', 
					notif_send_ios = '".$recData['notifSendIos']."', 
					notif_receive_total = '".$recData['notifReceiveTotal']."', 
					notif_receive_android = '".$recData['notifReceiveAndroid']."', 
					notif_receive_ios = '".$recData['notifReceiveIos']."' 
				WHERE notif_id = '".$recData['notifId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function update_content_notif_log($recData){
        $sql = "UPDATE _notif_log 
				SET group_id = '".$recData['groupId']."', 
					mlevel_id = '".$recData['mlevelId']."', 
					notif_date = '".$recData['notifDate']."', 
					notif_status = '".$recData['notifStatus']."', 
					notif_date = '".$recData['notifDate']."' 
				WHERE notif_id = '".$recData['notifId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_notif_log($notifId){
        $sql = "DELETE FROM _notif_log WHERE notif_id IN(".$notifId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_notif_log($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _notif_log 
					WHERE notif_push_date = '' OR notif_push_date = '0000-00-00 00:00:00' 
					ORDER BY notif_date DESC ";
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _notif_log 
					WHERE notif_push_date = '' OR notif_push_date = '0000-00-00 00:00:00' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="pushNotif"){
            $sql = "SELECT * FROM _notif_log 
					WHERE notif_status = 'publish' 
						AND notif_push_date = '0000-00-00 00:00:00' 
						AND DATE_FORMAT(notif_date,'%Y-%m-%d %H:%i') = '".date('Y-m-d H:i')."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="Classroom"){
            $sql = "SELECT * FROM _notif_log 
					WHERE notif_status = 'publish' 
						AND section_id = '30'  
						AND notif_push_date = '0000-00-00 00:00:00' 
						AND DATE_FORMAT(notif_date,'%Y-%m-%d') = '".date('Y-m-d')."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="Culture"){
            $sql = "SELECT * FROM _notif_log 
					WHERE notif_status = 'publish' 
						AND section_id = '33' 
						AND notif_push_date = '0000-00-00 00:00:00' 
						AND DATE_FORMAT(notif_date,'%Y-%m-%d') = '".date('Y-m-d')."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _notif_log WHERE notif_id = '".$this->recData['notifId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byContentId"){
            $sql = "SELECT * FROM _notif_log WHERE content_id = '".$this->recData['contentId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    function is_notif_exists($sectionId,$contentId){
        $result = false;
        $sql = "SELECT * FROM _notif_log WHERE section_id = '".$sectionId."' AND content_id = '".$contentId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)>0){
            $result = true;
        }
        return $result;
    }

    function push_notif($dataNotif=array()){
        $fields = array(
            'app_id' => "9242157d-9bc1-4853-a59f-cc7bc90b75f1",
            'included_segments' => array('All'),
            'headings'=> array("en"=>$dataNotif['headings']),
            'contents' => array("en"=>$dataNotif['contents']),
            'data' => $dataNotif['data']
        );

        /*
        $fields = array(
            'app_id' => "5eb5a37e-b458-11e3-ac11-000c2940e62c",
            'include_player_ids' => array("6392d91a-b206-4b7b-a620-cd68e32c3a76","76ece62b-bcfe-468c-8a78-839aeaa8c5fa","8e0f21fa-9a5a-4ae7-a9a6-ca1f24294b86"),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
        */

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NjcxYjZiZmUtMDQ4YS00OTliLThhMjMtODA1MTUwNDgxNTdl'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        //p($response);exit;
        //return $response;
    }

    function insert_content_catgroup($recData){
        $sql = "INSERT INTO _content_catgroup 
				VALUES('','".$recData['sectionId']."','".$recData['groupId']."','".$recData['catgroupName']."',
						'".$recData['catgroupAlias']."','".$recData['catgroupImage']."',
						'".$recData['catgroupStatus']."','".$recData['catgroupOrder']."',
						NOW(),'".$recData['catgroupCreateBy']."')";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function update_content_catgroup($recData){
        $sql = "UPDATE _content_catgroup 
				SET section_id		= '".$recData['sectionId']."', 
					catgroup_name	= '".$recData['catgroupName']."', 
					catgroup_alias	= '".$recData['catgroupAlias']."', 
					catgroup_image	= '".$recData['catgroupImage']."', 
					catgroup_status	= '".$recData['catgroupStatus']."', 
					catgroup_order	= '".$recData['catgroupOrder']."' 
				WHERE catgroup_id = '".$this->recData['catgroupId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function update_content_catgroup_hits($catId,$orderId){
        $sql = "UPDATE _content_catgroup 
				SET catgroup_order = '".$orderId."' 
				WHERE catgroup_id = '".$catId."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_content_catgroup(){
        $sql = "DELETE FROM _content_catgroup WHERE catgroup_id = '".$this->recData['catgroupId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_content_catgroup($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _content_catgroup 
					WHERE group_id = '".$this->recData['groupId']."' 
						AND section_id = '".$this->recData['sectionId']."' 
					ORDER BY catgroup_order ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _content_catgroup WHERE catgroup_id = '".$this->recData['catgroupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="nameById"){
            $sql = "SELECT catgroup_name FROM _content_catgroup 
					WHERE catgroup_id = '".$this->recData['catgroupId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['catgroup_name'];
        }
    }

    function count_content_in_catgroup($catId){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _content 
					WHERE cat_id = '".$catId."' AND section_id = '".$this->recData['sectionId']."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function insert_content_hits($data){
        $sql = "INSERT INTO _content_hits 
				VALUES('".$data['contentId']."','".$data['memberId']."','".$data['contentHitsChannel']."',NOW()) ";
        $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function select_content_hits($opt="",$data){
        if($opt=="countByMember"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_hits 
					WHERE content_id = '".$data['contentId']."' AND member_id = '".$data['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByContent"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _content_hits 
					WHERE content_id = '".$data['contentId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function get_latest_viewed($sectionId,$memberId,$limit="",$mlevelId=""){
        $levelId = "";
        if(intval($mlevelId)>0){
            $levelId = "'all'";
            for($i=intval($mlevelId);$i<=6;$i++){
                $levelId .= ",'".$i."'";
            }
        }

        if(intval($limit)==0){$limit = 5;}
        
        $where = '';
        if($this->recData['contentTypeID'] != ''){
            $where .= " AND content_type_id='".$this->recData['contentTypeID']."' ";
        }

        $sql = "SELECT * 
				FROM _content a, _content_hits b 
				WHERE a.content_id = b.content_id 
					AND a.section_id = '".$sectionId."' 
					AND b.member_id = '".$memberId."' AND a.content_status = 'publish'";
        if ($mlevelId){
            $sql .= " AND a.mlevel_id IN(".$levelId.") ";
        }
        $sql .= $where."
				GROUP BY b.content_id 
				ORDER BY b.content_hits_date DESC 
				LIMIT 0,".$limit." 
				";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function get($id,$section_id){
        $this->db->select('_content.*,_media.media_value,_media.media_type,_category.cat_name,_category.cat_id');

        $this->db->from('_content');

        $this->db->join('_category','_category.cat_id=_content.cat_id','LEFT');

        $sql_media = "select * from _media where section_id = ".$section_id." and media_primary = '1' and media_status = '1'";
        $this->db->join('('.$sql_media.') as _media','_media.data_id=_content.content_id','LEFT');

        $this->db->where('content_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_section($sectionName){
        $sName = "";
        if($sectionName=="berita"){ $sName = "News";}
        if($sectionName=="artikel"){ $sName = "Article";}
        if($sectionName=="knowledge"){ $sName = "Knowledge Sharing";}
        if($sectionName=="elearning"){ $sName = "e-Learning";}
        if($sectionName=="berita-ptpn"){ $sName = "Berita PTPN";}
        if($sectionName=="berita-rni"){ $sName = "Berita RNI";}
        if($sectionName=="pengumuman"){ $sName = "Announcement";}
        if($sectionName=="qrcontent"){ $sName = "QR Content";}
        if($sectionName=="ceo-notes"){ $sName = "CEO Notes";}
        if($sectionName=="bod-share"){ $sName = "BOD Share";}

        return $sName;
    }

    public function get_content_list_by_section_and_category($section_id = 0, $cat_id = 0, $search = ''){
        $this->load->model('media_model');

        // look content_type on table _content_type
        $content_type_map = array(
            '' => 'ebook',
            1 => 'ebook',
            2 => 'document',
            3 => 'video',
            4 => 'audio'
        );

        $ret = array();
        $ret['ebook'] = array();
        $ret['document'] = array();
        $ret['audio'] = array();
        $ret['video'] = array();

        $this->db->select('content_id, content_alias, content_name, content_type_id, content_publish_date');
        $this->db->where('section_id', $section_id);
        if($cat_id != 0) $this->db->where('cat_id', $cat_id);
        if($search != ''){
            $this->db->group_start();
            $this->db->like('content_name', $search, 'BOTH');
            $this->db->or_like('content_desc', $search, 'BOTH');
            $this->db->group_end();
        }
        $this->db->where('content_status', 'publish');
        $this->db->where('content_publish_date <= ', date('Y-m-d H:i:s'));
        $this->db->order_by('content_publish_date', 'DESC');
        $this->db->order_by('content_create_date', 'DESC');
        $this->db->limit(20);
        $datas = $this->db->get('_content')->result_array();
        
        foreach ($datas as $i => $data) {
            $id = $data['content_id'];
            // $datas[$i]['content_cover'] = $this->media_model->get_primary_image($section_id, $id);
            $data['content_cover'] = $this->media_model->get_primary_image($section_id, $id);
            
            $data['media'] = array();
            if($section_id != 0) $this->db->where('section_id', $section_id);
            $this->db->where('data_id', $id);
            $this->db->where('media_type !=', 'image');
            $media = $this->db->get('_media', 1)->row_array();
            if($media){
                $data['media'] = $media;
            }

            $ret[$content_type_map[$data['content_type_id']]][] = $data;
        }

        return $ret;
    }

    public function get_content_detail($content_alias = '', $section_id = 0, $member_id = 0){
        $this->load->model('media_model');
        $this->db->where('section_id', $section_id);
        $this->db->where('content_alias', $content_alias);
		$this->db->where('content_status', 'publish');
        $data = $this->db->get('_content')->row_array();

        $id = @$data['content_id'];

        // add hits
        $data_hits = [
            'contentId' => $id,
            'memberId'  => $member_id,
            'contentHitsChannel'    => 'android'
        ];
        $this->insert_content_hits($data_hits);
        $this->update_content('hits', $data_hits);

        // get cover
        $data['content_cover'] = $this->media_model->get_primary_image($section_id, $id);

        // get all media
        $data['media'] = array();
        if($section_id != 0) $this->db->where('section_id', $section_id);
        $this->db->where('data_id', $id);
        $this->db->where('media_type !=', 'image');
        $media = $this->db->get('_media', 1)->row_array();
        if($media){
            $data['media'] = $media;
        }

        return $data;
    }

    function search_content_bookmark($kw,$type="",$limit=""){
        $keyword = $this->arrWords($kw); //p($keyword);exit;
        if($type=="Pengarang"){
            $field = "a.content_author";
        }
        else{
            $field = "a.content_name";
        }
        $sql = "SELECT a.*,b.section_name ";

        if(count($keyword)>0){
            $sql .= ", (CASE ";
            for($i=0;$i<count($keyword);$i++){
                $sql .= " when ".$field." like '%".$keyword[$i]."%' then ".($i+1)." ";
                //$sql .= " as priority ";
            }
            $sql .= " else 0 end)  as priority";
        }

        $sql .= " FROM _content a, _section b, _member_bookmark c
				WHERE c.member_id = '".$this->recData['memberId']."'
				    AND a.section_id = b.section_id
				    AND a.content_id = c.content_id";

        if(count($keyword)>0){
            $sql .= " AND ( ";
            for($i=0;$i<count($keyword);$i++){
                if($i>0) $sql .= " OR ";
                $sql .= $field." LIKE '%".$keyword[$i]."%' ";
            }
            $sql .= " ) ";
        }
        $sql .= " ORDER BY priority, a.content_publish_date DESC ";

        if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
        else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    public $specialIds = array("6005","6006","6007","6008","6019","6020","6054");

    public function get_popup_list($member_id = 0){
        $today = date('Y-m-d');
        $this->load->model('survey_model');
        $this->load->model('media_model');
        $ret = array();

        $sections = array(34, 39, 40); // CEO Notes, Survey, Popup
        $this->reset_recdata();

        foreach ($sections as $i => $section) {
            $this->recData['sectionId'] = $section;
            $ct = $this->select_content('publish', '', 1);

            if(isset($ct[0])){
                $content = $ct[0];
                $start_date =  date('Y-m-d',strtotime($content['content_publish_date']));
                $end_date = date('Y-m-d', strtotime($start_date. ' +30 days'));

                // cek apakah member sudah membaca & termasuk dalam jangka waktu
                $ht = $this->select_content_hits('countByMember', ['contentId' => $content['content_id'], 'memberId' => $member_id]);
                if($ht == '0' && $start_date <= $today && $end_date >= $today){
                    $primaryImage = $this->media_model->get_primary_image($section, $content['content_id']);
                    $image = @$primaryImage['media_value'];

                    if($image == ''){
                        $image = PATH_ASSETS.'icon/main_icon.png';
                    }else{
                        $image = URL_MEDIA_IMAGE.$image;
                    }

                    if($section == 34){
                        $head = 'CEO Notes';
                        $url = base_url('whatsnew/ceo_note/detail/'.$content['content_id']);
                    }elseif($section == 39){
                        $head = 'Survey';
                        $url = '#';
                    }elseif($section == 40){
                        $head = 'Popup';
                        $url = base_url('home/popup/detail/'.$content['content_id']);
                    }else{
                        $head = 'Other';
                        $url = '#';
                    }

                    $ret[] = [
                        'head' => $head,
                        'name' => $content['content_name'],
                        'url' => $url,
                        'image' => $image,
                    ];
                    // $ret[] = $content;
                }
            }
        }

        $activeSurvey = $this->survey_model->select_survey("activePopup");

        if(count($activeSurvey)>0){
            $survey = $activeSurvey[0];

            $surveyId = $survey['survey_id'];
            $isMemberDoSurvey = $this->survey_model->is_member_do_survey($member_id, $surveyId);

            // if($isMemberDoSurvey === false || in_array($member_id, $this->specialIds)){
            if($isMemberDoSurvey === false){
                $start_date =  date('Y-m-d',strtotime($survey['survey_date_start']));
                $end_date = date('Y-m-d', strtotime($survey['survey_date_end']));

                if($start_date <= $today && $end_date >= $today){
                    $head = 'Survey';
                    $image = URL_MEDIA_IMAGE.$survey['survey_image'];

                    $url = base_url('home/survey?surveyId='.$surveyId);

                    $ret[] = [
                        'head' => $head,
                        'name' => $survey['survey_name'],
                        'url' => $url,
                        'image' => $image,
                    ];
                }
            }
        }

        return $ret;
    }

    private function reset_recdata(){
        foreach ($this->recData as $index => $value) {
            $this->recData[$index] = '';
        }
    }
}