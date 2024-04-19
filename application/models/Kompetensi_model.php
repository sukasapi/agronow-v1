<?php

class Kompetensi_model extends CI_Model{
    var $recData = array("crId"=>"","catId"=>"","crName"=>"","crDesc"=>"","crMateri"=>"",  "crType"=>"", "crDateStart"=>"","crDateEnd"=>"",
        "crTimeStart"=>"","crTimeEnd"=>"",  "crDateDetail"=>"", "crPrelearning"=>"", "crPretest"=>"", "crLp"=>"", "crRp"=>"", 
        "crModule"=>"", "crCompetency"=>"", "crCertificate"=>"","crFeedbackType"=>"","crFeedback"=>"","crStatus"=>"","crCreateDate"=>"",
        "crmId"=>"","memberId"=>"","crmChannel"=>"","crmCreateDate"=>"","crmStep"=>"","crmFb"=>"",

        "crsId"=>"","crsQuestion"=>"","crsRight"=>"","crsAnswer1"=>"","crsAnswer2"=>"","crsAnswer3"=>"","crsAnswer4"=>"",
        "crsType"=>"","crsType2"=>"","srcStatus"=>"","crsCreatedBy"=>"","crsCreateDate"=>"",

        "crscId"=>"","crscName"=>"","crscStatus"=>"","crscCreateDate"=>"",
        "ccId"=>"","moduleId"=>"","materiId"=>"","userId"=>"","ccParentId"=>"","ccDesc"=>"","ccStatus"=>"","ccCreateDate"=>"",
        "crfbId"=>"","crfbStep"=>"","crfbModule"=>"","crfbType"=>"","crfbQuestion"=>"","crfbCreateDate"=>"",
    );
    var $beginRec,$endRec;
    var $lastInsertId;
    
    function insert_bank_soal($data){
        $sql = "INSERT INTO _kompetensi_soal 
        VALUES('','".$data['catId']."','".$data['crscId']."','".$data['crs_level']."','".$data['crsQuestion']."','".$data['crsRight']."',
        '".$data['crsAnswer1']."','".$data['crsAnswer2']."','".$data['crsAnswer3']."','".$data['crsAnswer4']."','".$data['crs_durasi_detik']."','".$data['crsStatus']."','".$data['crsCreatedBy']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }
    
    function update_bank_soal($data){
        $sql = "UPDATE _kompetensi_soal SET
            cat_id       = '".$data['catId']."', 
            crsc_id      = '".$data['crscId']."', 
            crs_level    = '".$data['crs_level']."', 
            crs_question = '".$data['crsQuestion']."', 
            crs_right    = '".$data['crsRight']."', 
            crs_answer1  = '".$data['crsAnswer1']."', 
            crs_answer2  = '".$data['crsAnswer2']."', 
            crs_answer3  = '".$data['crsAnswer3']."', 
            crs_answer4  = '".$data['crsAnswer4']."', 
            crs_durasi_detik = '".$data['crs_durasi_detik']."', 
            crs_status     = '".$data['crsStatus']."', 
            crs_created_by = '".$data['crsCreatedBy']."' 
            WHERE crs_id   = '".$data['crsId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function delete_bank_soal($id){
        $sql = "DELETE FROM _kompetensi_soal WHERE crs_id = '".$id."' "; 
        $result = $this->db->query($sql);
        return $result;
    }

    function count_bank_soal($catId,$level) {
        $catId = (int) $catId;
        $level = (int) $level;
        $sql = "select count(crs_level) as juml from _kompetensi_soal where cat_id='".$catId."' and crs_level='".$level."' ";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['juml'];
    }
    
    function select_bank_soal($opt="",$catId="",$crscId="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _kompetensi_soal ORDER BY crs_id DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="all"){
            $sql = "SELECT * FROM _kompetensi_soal where 1 ORDER BY crs_id DESC";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_soal where 1 ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byCatId"){
            $sql = "SELECT * FROM _kompetensi_soal WHERE cat_id = '".$_SESSION['filterCatSoal']."' ORDER BY crs_id DESC";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countByCatId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_soal  WHERE cat_id = '".$_SESSION['filterCatSoal']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _kompetensi_soal WHERE crs_question LIKE '%".$_SESSION['SearchCrSoal']['Keyword']."%' ";
            if($_SESSION['SearchCrSoal']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCrSoal']['Cat']."' "; 
            }           
            $sql .= " ORDER BY crs_id DESC";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_soal WHERE crs_question LIKE '%".$_SESSION['SearchCrSoal']['Keyword']."%' ";
            if($_SESSION['SearchCrSoal']['Cat']!=""){
                $sql .= " AND cat_id = '".$_SESSION['SearchCrSoal']['Cat']."' "; 
            }
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _kompetensi_soal WHERE crs_status = 'publish' ";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_soal WHERE crs_status = 'publish ";
            $data = $this->execute($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _kompetensi_soal WHERE crs_id= '".$this->recData['crsId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
    }

    function get_soal($id){
        $sql = "SELECT * FROM _kompetensi_soal WHERE crs_id IN(".$id.") ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function insert_kompetensi($data){
        $sql = "INSERT INTO _kompetensi 
        VALUES( '','".$data['catId']."','".$data['crName']."','".$data['crDesc']."','".$data['crMateri']."',
        '".$data['crDateStart']."','".$data['crDateEnd']."','".$data['crKompMaxLv']."', 
        '".$data['crModule']."','".$data['crStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_kompetensi($opt="",$data=array(),$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _kompetensi SET 
            cat_id          = '".$data['catId']."',
            cr_name         = '".$data['crName']."', 
            cr_desc         = '".$data['crDesc']."', 
            cr_materi       = '".$data['crMateri']."', 
            cr_date_start   = '".$data['crDateStart']."', 
            cr_date_end     = '".$data['crDateEnd']."', 
            cr_komp_max_lv  = '".$data['crKompMaxLv']."', 
            cr_status       = '".$data['crStatus']."' 
            WHERE cr_id = '".$data['crId']."' "; 
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _kompetensi SET ".$field." = '".$value."' WHERE cr_id = '".$data['crId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
    }

    function delete_kompetensi($crId){
        $sql = "DELETE FROM _kompetensi WHERE cr_id IN(".$crId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_kompetensi($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _kompetensi ORDER BY cr_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _kompetensi WHERE cr_id = '".$this->recData['crId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
        elseif($opt=="incoming"){
            $sql = "SELECT * FROM _kompetensi WHERE cr_date_start > NOW() 
            ORDER BY cr_date_start ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countIncoming"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi 
            WHERE cr_date_start > NOW() ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="outgoing"){
            $sql = "SELECT * FROM _kompetensi 
            WHERE cr_date_end < NOW() 
            ORDER BY cr_date_end DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countOutgoing"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi 
            WHERE cr_date_end < NOW() ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _kompetensi a, _kompetensi_member b, _member c  
            WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND
            b.member_id = '".$this->recData['memberId']."' ";
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
        elseif($opt=="activeByMemberId"){
            $sql = "SELECT * FROM _kompetensi a, _kompetensi_member b, _member c  
            WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND
            b.member_id = '".$this->recData['memberId']."'  AND 
            a.cr_id = '".$this->recData['crId']."' AND 
            a.cr_date_start <= '".date('Y-m-d')."' 
            AND a.cr_date_end >= '".date('Y-m-d')."'  
            ";
            $data = $this->db->query($sql)->result_array(); 
            return $data[0];
        }
        elseif($opt=="listByMemberId"){
            $sql = "SELECT a.*,b.*,c.member_id, c.member_nip, c.member_name, c.mlevel_id, c.group_id, c.member_image FROM _kompetensi a, _kompetensi_member b, _member c  
            WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND
            b.member_id = '".$this->recData['memberId']."'  AND 
            a.cr_date_start <= '".date('Y-m-d')."' 
            AND a.cr_date_end >= '".date('Y-m-d')."'  
            ";
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
        elseif($opt=="search"){
            $sql = "SELECT * FROM _kompetensi WHERE cr_name LIKE '%".$_SESSION['SearchCr']['Keyword']."%' ";
            if($_SESSION['SearchCr']['Cat']!=""){ 
                $sql .= " AND cat_id = '".$_SESSION['SearchCr']['Cat']."' ";
            }
            $sql .= " ORDER BY cr_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countSearch"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi WHERE cr_name LIKE '%".$_SESSION['SearchCr']['Keyword']."%' ";
            if($_SESSION['SearchCr']['Cat']!=""){ 
                $sql .= " AND cat_id = '".$_SESSION['SearchCr']['Cat']."' ";
            }
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }elseif($opt=="daily"){
            $sql = "SELECT * FROM `_kompetensi` WHERE cr_is_daily=1 AND NOW() BETWEEN cr_date_start AND cr_date_end ORDER BY cr_date_start DESC LIMIT 1";
            $data = $this->db->query($sql)->result_array();
            return $data?$data[0]:[];
        }
    }

    function select_kompetensi_prasyarat($cr_id,$level){
        $sql = "SELECT cr.* FROM `_kompetensi_prasyarat` kp JOIN `_classroom` cr ON cr.cr_id = kp.classroom_id WHERE kp.cr_id = '".$cr_id."' AND kp.level = '".$level."' AND NOW() BETWEEN cr.cr_date_start AND cr.cr_date_end";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function insert_kompetensi_member($data){
        $sql = "INSERT INTO _kompetensi_member 
        VALUES('','".$data['crId']."','".$data['memberId']."','".$data['crmChannel']."','".$data['crmStep']."','".$data['crmFb']."',NOW())";
        $result = $this->db->query($sql);
        return $result;
    }

    function update_kompetensi_member($data){
        $sql = "UPDATE _kompetensi_member 
        SET cr_id = '".$data['crId']."', 
        member_id   = '".$data['memberId']."',
        crm_step    = '".$data['crmStep']."', 
        crm_fb = '".$data['crmFb']."'  
        WHERE crm_id = '".$data['crmId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_kompetensi_member($pmId){
        $sql = "DELETE FROM _kompetensi_member WHERE crm_id IN(".$pmId.")";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_kompetensi_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.cr_id, b.cr_name, c.member_name, c.member_nip, c.mlevel_id, d.group_name  
            FROM _kompetensi_member a, _kompetensi b, _member c, _group d 
            WHERE a.cr_id = b.cr_id 
            AND a.member_id = c.member_id 
            AND c.group_id = d.group_id 
            AND a.cr_id = '".$this->recData['crId']."' 
                    ORDER BY a.crm_step DESC"; //echo $sql;exit;
                    $data = $this->db->query($sql)->result_array();
                    return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL  
            FROM _kompetensi_member a, _kompetensi b, _member c, _group d  
            WHERE a.cr_id = b.cr_id 
            AND a.member_id = c.member_id 
            AND c.group_id = d.group_id 
            AND a.cr_id = '".$this->recData['crId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * 
            FROM _kompetensi_member a, _kompetensi b, _member c, _group d 
            WHERE a.cr_id = b.cr_id 
            AND a.member_id = c.member_id 
            AND c.group_id = d.group_id 
            AND a.cr_id = '".$this->recData['crId']."' 
            AND a.member_id = '".$this->recData['memberId']."' 
            ORDER BY crm_create_date DESC";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="processUpdate"){
            $sql = "SELECT * 
            FROM _kompetensi_member a, _kompetensi b
            WHERE a.cr_id = b.cr_id 
            AND a.cr_id = '".$this->recData['crId']."'  
            AND a.crm_step != '' 
            ORDER BY crm_step DESC";
            $data = $this->db->query($sql)->result_array(); //echo $sql;exit;
            return $data;
        }
        elseif($opt=="daily"){
            // get daily kompetensi yang belum selesai
            $sql = "SELECT a.*, b.*, c.member_id, c.member_nip, c.member_name, c.mlevel_id, c.group_id, c.member_image, d.* 
                    FROM _kompetensi_member a, _kompetensi b, _member c, _group d 
                    WHERE now() between b.cr_date_start AND b.cr_date_end
                    AND b.cr_is_daily = 1
                    AND a.cr_id = b.cr_id 
                    AND a.member_id = c.member_id 
                    AND c.group_id = d.group_id 
                    AND a.member_id = '".$this->recData['memberId']."'
                    ORDER BY crm_create_date DESC";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
    }
    
    function count_peserta($crId){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_member WHERE cr_id = '".$crId."' ";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['TOTAL'];
    }
    
    function  select_cr_member_test($memberId,$crId){
        $sql = "SELECT * FROM _kompetensi_member_test WHERE member_id = '".$memberId."' AND cr_id = '".$crId."'  ";
        $data = $this->db->query($sql)->result_array();
        return $data[0];
    }
    
    function insert_kompetensi_chat($data){
        $sql = "INSERT INTO _kompetensi_chat 
        VALUES('','".$data['crId']."','".$data['moduleId']."','".$data['materiId']."','".$data['userId']."',
        '".$data['memberId']."','".$data['ccParentId']."','".$data['ccDesc']."','".$data['ccStatus']."',NOW())"; 
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }
    
    function delete_kompetensi_chat($id){
        $sql = "DELETE FROM _kompetensi_chat WHERE cc_id IN(".$id.")";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function select_kompetensi_chat($opt="",$data,$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_image, b.group_id, b.member_nip 
            FROM _kompetensi_chat a, _member b
            WHERE a.member_id = b.member_id 
            AND a.materi_id = '".$data['materiId']."'  
            ORDER BY a.cc_create_date DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        
    }
    
    function is_absensi_exists($memberId,$crId,$start,$end){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _kompetensi_member 
        WHERE member_id = '".$memberId."' AND cr_id = '".$crId."' 
        AND DATE_FORMAT(pm_create_date,'%Y-%m-%d') = '".date('Y-m-d')."' ";
        $data = $this->db->query($sql)->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }
    
    function select_materi($opt=""){
        if($opt=="reading-room"){ $sectionId = 28;  }
        elseif($opt=="learning-room"){ $sectionId = 29; }
        else{ $sectionId = "";}
        $sql = "SELECT * FROM _content ";
        if($sectionId!=""){ 
            $sql .= " WHERE section_id = ".$sectionId;
        }
        $sql .= " ORDER BY content_publish_date DESC ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    
    //FEEDBACK
    
    function insert_kompetensi_feedback($recData){
        $sql = "INSERT INTO _kompetensi_feedback 
        VALUES('','".$recData['crId']."','".$recData['crStep']."','".$recData['crModule']."','".$recData['crType']."','".$recData['crQuestion']."',NOW())";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function update_kompetensi_feedback($recData){
        $sql = "UPDATE _kompetensi_feedback 
        SET crfb_step       ='".$recData['crfbStep']."', 
        crfb_module = '".$recData['crfbModule']."', 
        crfb_type       = '".$recData['crfbType']."', 
        crfb_question   = '".$recData['crfbQuestion']."' 
        WHERE crfb_id = '".$recData['crfbId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function delete_kompetensi_feedback($crfbId){
        $sql = "DELETE FROM _kompetensi_feedback WHERE crfb_id IN(".$crfbId.")";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function select_kompetensi_feedback($opt="",$recData=array()){
        if($opt==""){
            $sql = "SELECT * FROM _kompetensi_feedback 
            WHERE cr_id = '".$recData['crId']."' AND crfb_step = '".$recData['crfbStep']."' 
            AND crfb_module = '".$recData['crfbModule']."' ";

            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _kompetensi_feedback WHERE crfb_id = '".$recData['crfbId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
    }

    public function get_lastest($member_id = 0){
        $this->db->select('cr_name, cr_date_start');
        $this->db->join('_kompetensi_member', '_kompetensi.cr_id = _kompetensi_member.cr_id');
        $this->db->where('member_id', $member_id);
        $this->db->where('cr_date_start <=', date('Y-m-d'));
        $this->db->where('cr_date_end >=', date('Y-m-d'));
        $this->db->order_by('cr_date_start', 'desc');
        $data = $this->db->get('_kompetensi', 1)->result_array();
        
        return $data;
    }
}
?>