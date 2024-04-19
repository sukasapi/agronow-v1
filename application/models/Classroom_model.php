<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder db
 */
class Classroom_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database(); 
    }

    var $recData = array("crId"=>"","catId"=>"","crName"=>"","crDesc"=>"","crMateri"=>"",  "crType"=>"","crDateStart"=>"","crDateEnd"=>"",
        "crTimeStart"=>"","crTimeEnd"=>"",  "crDateDetail"=>"", "crPrelearning"=>"", "crPretest"=>"", "crLp"=>"", "crRp"=>"",
        "crModule"=>"", "crCompetency"=>"", "crCertificate"=>"","crFeedbackType"=>"","crFeedback"=>"","crStatus"=>"","crCreateDate"=>"",

        "crmId"=>"","memberId"=>"","crmChannel"=>"","crmCreateDate"=>"","crmStep"=>"","crmFb"=>"",

        "crsId"=>"","crsQuestion"=>"","crsRight"=>"","crsAnswer1"=>"","crsAnswer2"=>"","crsAnswer3"=>"","crsAnswer4"=>"",
        "crsType"=>"","srcStatus"=>"","crsCreatedBy"=>"","crsCreateDate"=>"",

        "crscId"=>"","crscName"=>"","crscStatus"=>"","crscCreateDate"=>"",
        "ccId"=>"","moduleId"=>"","materiId"=>"","userId"=>"","ccParentId"=>"","ccDesc"=>"","ccStatus"=>"","ccCreateDate"=>"",
        "crfbId"=>"","crfbStep"=>"","crfbModule"=>"","crfbType"=>"","crfbQuestion"=>"","crfbCreateDate"=>"",
    );
    var $beginRec,$endRec;
    var $lastInsertId;

    function insert_bank_soal($data){
        $sql = "INSERT INTO _classroom_soal  
                      VALUES('','".$data['catId']."','".$data['crscId']."','".$data['crsQuestion']."','".$data['crsRight']."',
					  '".$data['crsAnswer1']."','".$data['crsAnswer2']."','".$data['crsAnswer3']."','".$data['crsAnswer4']."','
					  ".$data['crsType']."','".$data['crsStatus']."','".$data['crsCreatedBy']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_bank_soal($data){
        $sql = "  UPDATE _classroom_soal 
                        SET  cat_id                = '".$data['catId']."', 
                                 crsc_id              = '".$data['crscId']."', 
                                 crs_question   = '".$data['crsQuestion']."', 
                                 crs_right           = '".$data['crsRight']."', 
                                 crs_answer1      = '".$data['crsAnswer1']."', 
                                 crs_answer2      = '".$data['crsAnswer2']."', 
                                 crs_answer3      = '".$data['crsAnswer3']."', 
                                 crs_answer4      = '".$data['crsAnswer4']."', 
                                 crs_type            = '".$data['crsType']."', 
                                 crs_status         = '".$data['crsStatus']."', 
                                 crs_created_by = '".$data['crsCreatedBy']."' 
                        WHERE crs_id = '".$data['crsId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_bank_soal($id){
        $sql = "DELETE FROM _classroom_soal WHERE crs_id = '".$id."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_bank_soal($opt="",$catId="",$crscId="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _classroom_soal ORDER BY crs_id DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="all"){
            $sql = "SELECT * FROM _classroom_soal ORDER BY crs_id DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_soal ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byCatId"){
            $sql = "SELECT * FROM _classroom_soal WHERE cat_id = '".$_SESSION['filterCatSoal']."' ORDER BY crs_id DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByCatId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_soal  WHERE cat_id = '".$_SESSION['filterCatSoal']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _classroom_soal WHERE crs_question LIKE '%".$_SESSION['SearchCrSoal']['Keyword']."%' ";
            if($_SESSION['SearchCrSoal']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCrSoal']['Cat']."' ";
            }
            $sql .= " ORDER BY crs_id DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_soal WHERE crs_question LIKE '%".$_SESSION['SearchCrSoal']['Keyword']."%' ";
            if($_SESSION['SearchCrSoal']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCrSoal']['Cat']."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _classroom_soal WHERE crs_status = 'publish' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_soal WHERE crs_status = 'publish' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _classroom_soal WHERE crs_id= '".$this->recData['crsId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    function get_soal($id){
        $sql = "SELECT * FROM _classroom_soal WHERE crs_id IN(".$id.") ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function insert_classroom($data){
        $sql = "INSERT INTO _classroom 
				VALUES( '','".$data['catId']."','".$data['crName']."','".$data['crDesc']."','".$data['crMateri']."','".$data['crType']."',
						'".$data['crDateStart']."','".$data['crDateEnd']."','".$data['crTimeStart']."','".$data['crTimeEnd']."','".$data['crDateDetail']."',	
						'".$data['crPrelearning']."','".$data['crPretest']."','".$data['crLp']."','".$data['crRp']."','".$data['crModule']."',
						'".$data['crCompetency']."','".$data['crCertificate']."','".$data['crFeedbackType']."','".$data['crFeedback']."',
						'".$data['crStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_classroom($opt="",$data=array(),$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _classroom 
					SET cat_id			= '".$data['catId']."',
						cr_name			= '".$data['crName']."', 
						cr_desc			= '".$data['crDesc']."', 
						cr_materi		= '".$data['crMateri']."', 
						cr_type			= '".$data['crType']."', 
						cr_date_start	= '".$data['crDateStart']."', 
						cr_date_end		= '".$data['crDateEnd']."', 
						cr_time_start	= '".$data['crTimeStart']."', 
						cr_time_end		= '".$data['crTimeEnd']."', 
						cr_date_detail	= '".$data['crDateDetail']."', 
						cr_status		= '".$data['crStatus']."' 
					WHERE cr_id = '".$data['crId']."' ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _classroom SET ".$field." = '".$value."' WHERE cr_id = '".$data['crId']."' ";
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            return $result;
        }
    }

    function delete_classroom($crId){
        $sql = "DELETE FROM _classroom WHERE cr_id IN(".$crId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_classroom($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _classroom ORDER BY cr_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _classroom WHERE cr_id = '".$this->recData['crId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:null;
        }
        elseif($opt=="incoming"){
            $sql = "SELECT * FROM _classroom WHERE cr_date_start > NOW() 
					ORDER BY cr_date_start ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countIncoming"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom 
					WHERE cr_date_start > NOW() ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="outgoing"){
            $sql = "SELECT * FROM _classroom 
					WHERE cr_date_end < NOW() 
					ORDER BY cr_date_end DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countOutgoing"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom 
					WHERE cr_date_end < NOW() ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _classroom a, _classroom_member b, _member c  
					WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND 
					b.member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="activeByMemberId"){
            $sql = "SELECT * FROM _classroom a, _classroom_member b, _member c  
						 WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND 
						 	b.member_id = '".$this->recData['memberId']."'  AND 
							a.cr_id = '".$this->recData['crId']."' AND 
							a.cr_date_start <= '".date('Y-m-d')."' 
							AND a.cr_date_end >= '".date('Y-m-d')."'  
						";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="listByMemberId"){
            $sql = "SELECT * FROM _classroom a, _classroom_member b, _member c  
						 WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND 
						 	b.member_id = '".$this->recData['memberId']."'  AND 
							a.cr_date_start <= '".date('Y-m-d')."' 
							AND a.cr_date_end >= '".date('Y-m-d')."'  
						";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _classroom WHERE cr_name LIKE '%".$_SESSION['SearchCr']['Keyword']."%' ";
            if($_SESSION['SearchCr']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCr']['Cat']."' ";
            }
            $sql .= " ORDER BY cr_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom WHERE cr_name LIKE '%".$_SESSION['SearchCr']['Keyword']."%' ";
            if($_SESSION['SearchCr']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCr']['Cat']."' ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }elseif($opt=="listSell"){
            // get array of purchased class
            $purchased = array();
            $this->db->select('a.cr_id');
            $this->db->join('_classroom_member b', 'a.cr_id = b.cr_id');
            $this->db->where('b.member_id', $this->recData['memberId']);
            $this->db->group_start();
            $this->db->where('a.cr_price IS NOT NULL');
            $this->db->or_where('a.cr_price >', 0);
            $this->db->group_end();
            foreach ($this->db->get('_classroom a')->result_array() as $i => $data) {
                $purchased[] = $data['cr_id'];
            }
            
            $this->db->select('a.*');
            $this->db->select('(SELECT COUNT(crm_id) FROM _classroom_member b WHERE a.cr_id = b.cr_id AND member_id = '.$this->recData['memberId'].') as cr_sold');
            if(count($purchased) > 0) $this->db->where_not_in('cr_id', $purchased);
            $this->db->group_start();
            $this->db->where('a.cr_price IS NOT NULL');
            $this->db->or_where('a.cr_price >', 0);
            $this->db->group_end();
            return $this->db->get('_classroom a')->result_array();
        }
    }

    function insert_classroom_member($data){
        $sql = "INSERT INTO _classroom_member 
                VALUES('','".$data['crId']."','".$data['memberId']."','".$data['crmChannel']."','".$data['crmStep']."', '', '', '".$data['crmFb']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_classroom_member($data){
        $sql = "UPDATE _classroom_member 
				SET cr_id = '".$data['crId']."', 
					member_id 	= '".$data['memberId']."',
					crm_step 	= '".addslashes($data['crmStep'])."', 
					crm_fb = '".$data['crmFb']."'  
				WHERE crm_id = '".$data['crmId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_classroom_member($pmId){
        $sql = "DELETE FROM _classroom_member WHERE crm_id IN(".$pmId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_classroom_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.cr_id, b.cr_name, c.member_name, c.member_nip, c.mlevel_id, d.group_name  
					FROM _classroom_member a, _classroom b, _member c, _group d 
					WHERE a.cr_id = b.cr_id 
						AND a.member_id = c.member_id 
						AND c.group_id = d.group_id 
						AND a.cr_id = '".$this->recData['crId']."' 
					ORDER BY a.crm_step DESC"; //echo $sql;exit;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL  
					FROM _classroom_member a, _classroom b, _member c, _group d  
					WHERE a.cr_id = b.cr_id 
						AND a.member_id = c.member_id 
						AND c.group_id = d.group_id 
						AND a.cr_id = '".$this->recData['crId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * 
					FROM _classroom_member a, _classroom b, _member c, _group d 
					WHERE a.cr_id = b.cr_id 
						AND a.member_id = c.member_id 
						AND c.group_id = d.group_id 
						AND a.cr_id = '".$this->recData['crId']."' 
						AND a.member_id = '".$this->recData['memberId']."' 
					ORDER BY crm_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="processUpdate"){
            $sql = "SELECT * 
					FROM _classroom_member a, _classroom b
					WHERE a.cr_id = b.cr_id 
						AND a.cr_id = '".$this->recData['crId']."'  
						AND a.crm_step != '' 
					ORDER BY crm_step DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    function count_peserta($crId){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_member WHERE cr_id = '".$crId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function  select_cr_member_test($memberId,$crId){
        $sql = "SELECT * FROM _classroom_member_test WHERE member_id = '".$memberId."' AND cr_id = '".$crId."'  ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }

    function insert_classroom_chat($data){
        $sql = "INSERT INTO _classroom_chat 
					VALUES('','".$data['crId']."','".$data['moduleId']."','".$data['materiId']."','".$data['userId']."',
								'".$data['memberId']."','".$data['ccParentId']."','".$data['ccDesc']."','".$data['ccStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function delete_classroom_chat($id){
        $sql = "DELETE FROM _classroom_chat WHERE cc_id IN(".$id.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_classroom_chat($opt="",$data,$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_image, b.group_id, b.member_nip 
						 FROM _classroom_chat a, _member b
						 WHERE a.member_id = b.member_id 
						 	AND a.materi_id = '".$data['materiId']."'  
						 ORDER BY a.cc_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }

    }

    function is_absensi_exists($memberId,$crId,$start,$end){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _classroom_member 
				WHERE member_id = '".$memberId."' AND cr_id = '".$crId."' 
					AND DATE_FORMAT(pm_create_date,'%Y-%m-%d') = '".date('Y-m-d')."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function select_materi($opt=""){
        if($opt=="reading-room"){ $sectionId = 28; 	}
        elseif($opt=="learning-room"){ $sectionId = 29; }
        else{ $sectionId = "";}
        $sql = "SELECT * FROM _content ";
        if($sectionId!=""){
            $sql .= " WHERE section_id = ".$sectionId;
        }
        $sql .= " ORDER BY content_publish_date DESC ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }


    //FEEDBACK

    function insert_classroom_feedback($recData){
        $sql = "INSERT INTO _classroom_feedback 
						VALUES('','".$recData['crId']."','".$recData['crStep']."','".$recData['crModule']."','".$recData['crType']."','".$recData['crQuestion']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_classroom_feedback($recData){
        $sql = "UPDATE _classroom_feedback 
						SET crfb_step 		='".$recData['crfbStep']."', 
								crfb_module = '".$recData['crfbModule']."', 
								crfb_type 		= '".$recData['crfbType']."', 
								crfb_question	= '".$recData['crfbQuestion']."' 
						WHERE crfb_id = '".$recData['crfbId']."' ";
        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_classroom_feedback($crfbId){
        $sql = "DELETE FROM _classroom_feedback WHERE crfb_id IN(".$crfbId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_classroom_feedback($opt="",$recData=array()){
        if($opt==""){
            $sql = "SELECT * FROM _classroom_feedback 
						WHERE cr_id = '".$recData['crId']."' AND crfb_step = '".$recData['crfbStep']."' 
							AND crfb_module = '".$recData['crfbModule']."' ";

            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _classroom_feedback WHERE crfb_id = '".$recData['crfbId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    public function get_lastest($member_id = 0){
        $this->db->select('cr_name, cr_date_start');
        $this->db->join('_classroom_member', '_classroom.cr_id = _classroom_member.cr_id');
        $this->db->where('member_id', $member_id);
        $this->db->where('cr_date_start <=', date('Y-m-d'));
        $this->db->where('cr_date_end >=', date('Y-m-d'));
        $this->db->order_by('cr_date_start', 'desc');
        $data = $this->db->get('_classroom', 1)->result_array();
        
        return $data;
    }

    public function get_latest_classroom_home($member_id = 0){
		$ret = [];
		$this->recData['memberId'] = $member_id;
		$classrooms = $this->select_classroom('listByMemberId');
		foreach ($classrooms as $data) {
			$total_item = 0;
			$item_done = 0;
			$latest = 'Pre Learning';
			$data['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $data['crm_step']);
			$step = json_decode($data['crm_step'], true);
			if (!isset($step['MP'])){ // cek apakah sudah ada data
				$percent = 0;

				$ret[] = [
					'cr_id' => $data['cr_id'],
					'cr_lp' => $data['cr_lp'],
					'cr_name' => $data['cr_name'],
					'cr_latest' => $latest,
					'cr_percent' => round($percent)
				];
				continue;
			}
			if ($data['cr_has_prelearning']){
				$total_item++;
				if ($step['PL']['plStatus'] == '2'){
					$item_done++;
				}
			}
			if ($data['cr_has_pretest']) {
				$total_item++;
				if ($step['PT']['ptStatus'] == '2'){
					$item_done++;
					$latest = "Pre-Test";
				}
			}

			$cr_module = preg_replace("/[[:cntrl:]]/", "", $data['cr_module']);
			$cr_module = json_decode($cr_module, true);
			foreach ($cr_module['Module'] as $i => $module){
				$total_item += count($step['MP'][$i]['Materi']);
				$counts = array_count_values($step['MP'][$i]['Materi']);
				$item_done += isset($counts['2'])?$counts['2']:0;
				$latest = "Training Modules";
				if ($module['Feedback']['Status'] == 'active'){
					$total_item++;
					if ($data['cr_has_learning_point']){
						if (isset($step['MP'][$i]['LearningPoint']) && $step['MP'][$i]['LearningPoint']['status'] == '2'){
							$item_done++;
						}
					} else {
						if ($step['MP'][$i]['FbStatus'] == '2'){
							$item_done++;
						}
					}
				}
				if ($module['Evaluasi']['Status'] == 'active'){
					$total_item++;
					if ($step['MP'][$i]['EvaStatus'] == '2'){
						$item_done++;
					}
				}
			}

			if ($data['cr_has_kompetensi_test']) {
				$total_item++;
				if ($step['CT']['ctStatus'] == '2'){
					$item_done++;
					$latest = "Kompetensi Test";
				}
			}
			if ($data['cr_has_knowledge_management']) {
				$total_item++;
				if ($data['content_id']){
					$item_done++;
					$latest = "Knowledge Management";
				}
			}

			// + feedback classroom
			$total_item++;
			if ($data['crm_fb']){
				$item_done++;
				$latest = "Feedback Classroom";
			}

			$percent = $item_done/$total_item*100;
            
            $ret[] = [
                'cr_id' => $data['cr_id'],
                'cr_lp' => $data['cr_lp'],
                'cr_name' => $data['cr_name'],
                'cr_latest' => $latest,
                'cr_percent' => round($percent)
            ];
        }
        return $ret;
    }

    public function get_crm_id($cr_id = 0, $member_id = 0){
        $this->db->select('crm_id');
        $this->db->where('cr_id', $cr_id);
        $this->db->where('member_id', $member_id);
        $data = $this->db->get('_classroom_member', 1)->row_array();
        if($data){
            return $data['crm_id'];
        }else{
            return 0;
        }
    }

    public function update_classroom_member_content_id($crm_id = 0, $content_id = 0){
        $this->db->where('crm_id', $crm_id);
        $this->db->update('_classroom_member', array('content_id' => $content_id));
    }

    public function new_individual_report($member_id = 0){
        $this->db->select('b.*, c.*, cr.*');
        $this->db->join('_classroom cr', 'b.cr_id = cr.cr_id', 'left');
        $this->db->join('_member c', 'b.member_id = c.member_id', 'left');
        $this->db->where('b.member_id', $member_id);
		$this->db->where('b.crm_fb != "" ');
		
		$datas = $this->db->get('_classroom_member b')->result_array();
        foreach ($datas as $i => $data) {
            $cr_info = json_decode($data['cr_info'], true);
            if (!$cr_info) continue;
            foreach ($cr_info as $index => $value) {
                if(!isset($datas[$i][$index])) $datas[$i][$index] = $value;
            }
        }

        return $datas;
    }

    public function update_cr_info($crm_id = 0, $cr_info = array()){
        $this->db->where('crm_id', $crm_id);
        return $this->db->update('_classroom_member', array('cr_info' => json_encode($cr_info)));
    }


   
}
