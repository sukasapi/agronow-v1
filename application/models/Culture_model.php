<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culture_model extends CI_Model {

    var $recData = array("crId"=>"","catId"=>"","crName"=>"","crDesc"=>"","crMateri"=>"",  "crType"=>"","crDateStart"=>"","crDateEnd"=>"",
        "crTimeStart"=>"","crTimeEnd"=>"",  "crDateDetail"=>"", "crPrelearning"=>"", "crPretest"=>"", "crLp"=>"", "crRp"=>"", 
        "crModule"=>"", "crCompetency"=>"", "crCertificate"=>"","crStatus"=>"","crCreateDate"=>"",
        
        "crmId"=>"","memberId"=>"","crmChannel"=>"","crmCreateDate"=>"","crmStep"=>"","crmFb"=>"",

        "crsId"=>"","crsQuestion"=>"","crsRight"=>"","crsAnswer1"=>"","crsAnswer2"=>"","crsAnswer3"=>"","crsAnswer4"=>"",
        "crsType"=>"","srcStatus"=>"","crsCreatedBy"=>"","crsCreateDate"=>"",

        "crscId"=>"","crscName"=>"","crscStatus"=>"","crscCreateDate"=>"",
        "ccId"=>"","moduleId"=>"","materiId"=>"","userId"=>"","ccParentId"=>"","ccDesc"=>"","ccStatus"=>"","ccCreateDate"=>"",

    );
    var $beginRec,$endRec;
    var $lastInsertId;

    function __construct(){
        parent::__construct();
        $this->load->library('function_api');
    }

    function insert_bank_soal($data){
        $sql = "INSERT INTO _culture_soal 
            VALUES('','".$data['catId']."','".$data['crscId']."','".$data['crsQuestion']."','".$data['crsRight']."',
            '".$data['crsAnswer1']."','".$data['crsAnswer2']."','".$data['crsAnswer3']."','".$data['crsAnswer4']."','
            ".$data['crsType']."','".$data['crsStatus']."','".$data['crsCreatedBy']."',NOW())";
            $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_bank_soal($data){
        $sql = "UPDATE _culture_soal 
            SET  cat_id    = '".$data['catId']."', 
            crsc_id        = '".$data['crscId']."', 
            crs_question   = '".$data['crsQuestion']."', 
            crs_right      = '".$data['crsRight']."', 
            crs_answer1    = '".$data['crsAnswer1']."', 
            crs_answer2    = '".$data['crsAnswer2']."', 
            crs_answer3    = '".$data['crsAnswer3']."', 
            crs_answer4    = '".$data['crsAnswer4']."', 
            crs_type       = '".$data['crsType']."', 
            crs_status     = '".$data['crsStatus']."', 
            crs_created_by = '".$data['crsCreatedBy']."' 
            WHERE crs_id = '".$data['crsId']."' ";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }

    function delete_bank_soal($id){
        $sql = "DELETE FROM _culture_soal WHERE crs_id = '".$id."' "; 
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }

    function select_bank_soal($opt="",$catId="",$crscId="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _culture_soal ORDER BY crs_id DESC";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _culture_soal ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="publish"){
            $sql = "SELECT * FROM _culture_soal WHERE crs_status = 'publish' ";
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countPublish"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _culture_soal WHERE crs_status = 'publish ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _culture_soal WHERE crs_id= '".$this->recData['crsId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
    }

    function get_soal($id){
        $sql = "SELECT * FROM _culture_soal WHERE crs_id IN(".$id.") ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function insert_culture($data){
        $sql = "INSERT INTO _culture 
            VALUES( '','".$data['catId']."','".$data['crName']."','".$data['crDesc']."','".$data['crMateri']."','".$data['crType']."',
            '".$data['crDateStart']."','".$data['crDateEnd']."','".$data['crTimeStart']."','".$data['crTimeEnd']."','".$data['crDateDetail']."',    
            '".$data['crPrelearning']."','".$data['crPretest']."','".$data['crLp']."','".$data['crRp']."','".$data['crModule']."',
            '".$data['crCompetency']."','".$data['crCertificate']."',   '".$data['crStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_culture($opt="",$data=array(),$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _culture 
                SET cat_id     = '".$data['catId']."',
                cr_name        = '".$data['crName']."', 
                cr_desc        = '".$data['crDesc']."', 
                cr_materi      = '".$data['crMateri']."', 
                cr_type        = '".$data['crType']."', 
                cr_date_start  = '".$data['crDateStart']."', 
                cr_date_end    = '".$data['crDateEnd']."', 
                cr_time_start  = '".$data['crTimeStart']."', 
                cr_time_end    = '".$data['crTimeEnd']."', 
                cr_date_detail = '".$data['crDateDetail']."', 
                cr_status      = '".$data['crStatus']."' 
                WHERE cr_id = '".$data['crId']."' "; 
            $result = $this->db->query($sql)->affected_rows();
            return $result;
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _culture SET ".$field." = '".$value."' WHERE cr_id = '".$data['crId']."' ";
            $result = $this->db->query($sql)->affected_rows();
            return $result;
        }
    }

    function delete_culture($crId){
        $sql = "DELETE FROM _culture WHERE cr_id IN(".$crId.")";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }

    function select_culture($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _culture ORDER BY cr_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _culture";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _culture WHERE cr_id = '".$this->recData['crId']."' ";
            $data = $this->db->query($sql)->result_array();
            return $data[0];
        }
        elseif($opt=="incoming"){
            $sql = "SELECT * FROM _culture WHERE cr_date_start > NOW() 
                ORDER BY cr_date_start ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countIncoming"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _culture 
                WHERE cr_date_start > NOW() ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="outgoing"){
            $sql = "SELECT * FROM _culture 
                WHERE cr_date_end < NOW() 
                ORDER BY cr_date_end DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit;}
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="countOutgoing"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _culture 
                WHERE cr_date_end < NOW() ";
            $data = $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _culture a, _culture_member b, _member c  
                WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND 
                b.member_id = '".$this->recData['memberId']."' ";
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
        elseif($opt=="activeByMemberId"){
            $sql = "SELECT * FROM _culture a, _culture_member b, _member c  
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
            $sql = "SELECT * FROM _culture a, _culture_member b, _member c  
                WHERE a.cr_id = b.cr_id AND b.member_id = c.member_id AND 
                b.member_id = '".$this->recData['memberId']."'  AND 
                a.cr_date_start <= '".date('Y-m-d')."' 
                AND a.cr_date_end >= '".date('Y-m-d')."'  
                ";
            $data = $this->db->query($sql)->result_array(); 
            return $data;
        }
    }

    function insert_culture_member($data){
        $sql = "INSERT INTO _culture_member 
            VALUES('','".$data['crId']."','".$data['memberId']."','".$data['crmChannel']."','".$data['crmStep']."','".$data['crmFb']."',NOW())";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }
    
    function update_culture_member($data){
        $sql = "UPDATE _culture_member 
            SET cr_id = '".$data['crId']."', 
            member_id   = '".$data['memberId']."',
            crm_step    = '".$data['crmStep']."', 
            crm_fb = '".$data['crmFb']."'  
            WHERE crm_id = '".$data['crmId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }
    
    function delete_culture_member($pmId){
        $sql = "DELETE FROM _culture_member WHERE crm_id IN(".$pmId.")";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }

    function select_culture_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.*, c.member_name, c.member_nip, d.group_name  
                FROM _culture_member a, _culture b, _member c, _group d 
                WHERE a.cr_id = b.cr_id 
                AND a.member_id = c.member_id 
                AND c.group_id = d.group_id 
                AND a.cr_id = '".$this->recData['crId']."' 
            ORDER BY crm_step DESC";
            $data= $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL  
                FROM _culture_member a, _culture b, _member c, _group d  
                WHERE a.cr_id = b.cr_id 
                AND a.member_id = c.member_id 
                AND c.group_id = d.group_id 
                AND a.cr_id = '".$this->recData['crId']."' ";
            $data= $this->db->query($sql)->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * 
                FROM _culture_member a, _culture b, _member c, _group d 
                WHERE a.cr_id = b.cr_id 
                AND a.member_id = c.member_id 
                AND c.group_id = d.group_id 
                AND a.cr_id = '".$this->recData['crId']."' 
                AND a.member_id = '".$this->recData['memberId']."' 
                ORDER BY crm_create_date DESC";
            $data= $this->db->query($sql)->result_array();
            return $data;
        }
        elseif($opt=="processUpdate"){
            $sql = "SELECT * 
                FROM _culture_member a, _culture b
                WHERE a.cr_id = b.cr_id 
                AND a.cr_id = '".$this->recData['crId']."'  
                AND a.crm_step != '' 
                ORDER BY crm_step DESC";
            $data= $this->db->query($sql)->result_array(); //echo $sql;exit;
            return $data;
        }
    }

    function count_peserta($crId){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _culture_member WHERE cr_id = '".$crId."' ";
        $data = $this->db->query($sql)->affected_rows();
        return $data[0]['TOTAL'];
    }
    
    function  select_cr_member_test($memberId,$crId){
        $sql = "SELECT * FROM _culture_member_test WHERE member_id = '".$memberId."' AND cr_id = '".$crId."'  ";
        $data = $this->db->query($sql)->affected_rows();
        return $data[0];
    }

    function insert_culture_chat($data){
        $sql = "INSERT INTO _culture_chat 
            VALUES('','".$data['crId']."','".$data['moduleId']."','".$data['materiId']."','".$data['userId']."',
            '".$data['memberId']."','".$data['ccParentId']."','".$data['ccDesc']."','".$data['ccStatus']."',NOW())"; 
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function delete_culture_chat($id){
        $sql = "DELETE FROM _culture_chat WHERE cc_id IN(".$id.")";
        $result = $this->db->query($sql)->affected_rows();
        return $result;
    }
    
    function select_culture_chat($opt="",$data,$limit=""){
        if($opt==""){
            $sql = "SELECT a.*, b.member_name, b.member_image, b.group_id, b.member_nip 
                FROM _culture_chat a, _member b
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
        $sql = "SELECT COUNT(*) AS TOTAL FROM _culture_member 
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

    public function get_lastest($member_id = 0){
        $this->db->select('cr_name, cr_date_start');
        $this->db->join('_culture_member', '_culture.cr_id = _culture_member.cr_id');
        $this->db->where('member_id', $member_id);
        $this->db->where('cr_date_start <=', date('Y-m-d'));
        $this->db->where('cr_date_end >=', date('Y-m-d'));
        $this->db->order_by('cr_date_start', 'desc');
        $data = $this->db->get('_culture', 1)->result_array();

        return $data;
    }

    private $modules_percent = 70;
    private $competency_percent = 30;

    public function get_latest_culture_home($member_id = 0){
        $ret = [];
        $this->recData['memberId'] = $member_id;
        foreach ($this->select_culture('listByMemberId') as $data) {
            $latest = 'Training Modules';
            $percent = 0;

            $data['crm_step'] = preg_replace("/[[:cntrl:]]/", "", $data['crm_step']);
            $step = json_decode($data['crm_step'], true);

            if(@count($step) > 0){
                $percent_per_module = $this->modules_percent / (@count($step['MP']) > 0 ? count($step['MP']) : 1);

                foreach (@$step['MP'] as $i => $module) {
                    if(@$step['MP'][$i]['EvaStatus'] == '2'){
                        $percent += $percent_per_module;
                        $latest = "Training Modules - Modul ".($i+1);
                    }
                }

                if(@$step['CT']['ctStatus'] == '2'){
                    $percent += $this->competency_percent;
                    $latest = "Selesai mengikuti class";
                    if(@$step['RESULT']){
                        $latest .= ' - Grade : '.$step['RESULT'];
                    }
                }
            }
            
            $ret[] = [
                'cr_id' => $data['cr_id'],
                'cr_lp' => $data['cr_lp'],
                'cr_name' => $data['cr_name'],
                'cr_latest' => $latest,
                'cr_percent' => $percent
            ];
        }

        return $ret;
    }

    public function update_cr_info($crm_id = 0, $cr_info = array()){
        $this->db->where('crm_id', $crm_id);
        return $this->db->update('_culture_member', array('cr_info' => json_encode($cr_info)));
    }
}