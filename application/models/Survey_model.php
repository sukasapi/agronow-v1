<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 30/08/20
 * Time: 1:12
 * @property CI_DB_query_builder db
 */

class Survey_model extends CI_Model
{

    var $recData = array(
        "surveyId"=>"","surveyName"=>"","surveyDesc"=>"","surveyImage"=>"","surveyData"=>"","surveyDateStart"=>"",
        "surveyDateEnd"=>"","surveyStatus"=>"", "surveyCreatedBy"=>"","surveyCreateDate"=>"",
        "smId"=>"","memberId"=>"","smData"=>"","smCreateDate"=>"",


    );
    var $beginRec,$endRec;
    var $lastInsertId;


    function insert_survey($data){
        $sql = "INSERT INTO _survey 
                      VALUES('','".$data['surveyName']."','".$data['surveyDesc']."','".$data['surveyImage']."', '".$data['surveyData']."','".$data['surveyDateStart']."',
					 					 '".$data['surveyDateEnd']."','".$data['surveyStatus']."','".$data['surveyCreatedBy']."',NOW())";
        $query = $this->db->query($sql);
        $result = $query->insert_id();
        return $result;
    }

    function update_survey($opt="",$data=array(),$field="",$value=""){
        if($opt==""){
            $sql = "  UPDATE _survey 
							SET  survey_name   		= '".$data['surveyName']."', 
									 survey_desc           	= '".$data['surveyDesc']."', 
									 survey_image         	= '".$data['surveyImage']."', 
									 survey_data      		= '".$data['surveyData']."', 
									 survey_date_start 	= '".$data['surveyDateStart']."', 
									 survey_date_end  	= '".$data['surveyDateEnd']."', 
									 survey_status        	= '".$data['surveyStatus']."', 
									 survey_created_by = '".$data['surveyCreatedBy']."' 
							WHERE survey_id = '".$data['surveyId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _survey SET ".$field."= '".$value."' WHERE survey_id = '".$data['surveyId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
    }

    function  delete_survey($id){
        $sql = "DELETE FROM _survey WHERE survey_id = '".$id."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_survey($opt="",$data=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _survey ORDER BY survey_id DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _survey ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _survey WHERE survey_status = 'publish' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _survey WHERE survey_status = 'publish ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _survey WHERE survey_id= '".$data['surveyId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="activePopup"){
            $sql = "SELECT * FROM _survey 
						WHERE survey_status='publish' 
							AND survey_date_start<='".date('Y-m-d H:i:s')."' AND survey_date_end>'".date('Y-m-d H:i:s')."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    // SURVEY END //


    // SURVEY MEMBER START //

    function insert_survey_member($data){
        $sql = "INSERT INTO _survey_member 
                      VALUES('','".$data['surveyId']."','".$data['memberId']."', '".$data['smData']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_survey_member($data){
        $sql = "  UPDATE _survey_member  
                        SET  survey_id  	= '".$data['surveyId']."', 
                                 member_id	= '".$data['memberId']."', 
                                 sm_data   		= '".$data['surveyData']."' 
                        WHERE sm_id = '".$data['smId']."' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function  delete_survey_member($id){
        $sql = "DELETE FROM _survey_member WHERE sm_id = '".$id."' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function select_survey_member($opt="",$data=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, c.member_name, c.member_nip, c.group_id, c.member_image, d.group_name 
							FROM _survey_member a, _survey b, _member c, _group d 
							WHERE a.survey_id = b.survey_id AND a.member_id = c.member_id AND c.group_id = d.group_id 
								AND a.survey_id = '".$data['surveyId']."' 
							GROUP BY a.member_id 	
							ORDER BY a.sm_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(DISTINCT(member_id)) AS TOTAL 
							FROM _survey_member WHERE survey_id = '".$data['surveyId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _survey_member WHERE survey_id= '".$data['smId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="bySurveyId"){
            $sql = "SELECT a.*,  b.*, c.member_name, b.member_nip, b.group_id, b.member_image, d.group_name 
							FROM _survey_member a, _survey b, _member c, _group d 
							WHERE a.survey_id = b.survey_id AND a.member_id = c.member_id AND c.group_id = d.group_id 
								AND survey_id = '".$data['surveyId']."' 
							ORDER BY a.sm_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countBySurveyId"){
            $sql = "SELECTCOUNT(*) AS TOTAL 
							FROM _survey_member a, _survey b, _member c, _group d 
							WHERE a.survey_id = b.survey_id AND a.member_id = c.member_id AND c.group_id = d.group_id 
								AND a.survey_id = '".$data['surveyId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function is_member_do_survey($memberId,$surveyId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _survey_member 
					WHERE member_id = '".$memberId."' AND survey_id = '".$surveyId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

}