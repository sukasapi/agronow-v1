<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 07/08/20
 * Time: 22:14
 * @property CI_DB_query_builder db
 */

class Member_model extends CI_Model
{
    var $recData = ["memberId"=>"","groupId"=>"","memberName"=>"","memberNip"=>"","memberType"=>"",
        "memberEmail"=>"","memberPassword"=>"","memberLoginWeb"=>"","memberLoginApk"=>"",
        "memberLoginIpa"=>"","memberRegId"=>"","memberRegChannel"=>"","memberDevice"=>"",
        "memberDesc"=>"","memberJabatan"=>"","memberKelJabatan"=>"","memberUnitKerja"=>"",
        "memberImage"=>"","memberGender"=>"","memberBirthPlace"=>"",
        "memberBirthDate"=>"","memberPhone"=>"","memberAddress"=>"","memberCity"=>"",
        "memberProvince"=>"","memberPostcode"=>"","memberCeo"=>"","memberStatus"=>"","memberCreateDate"=>"",
        "mbId"=>"","mbStatus"=>"","mbReason"=>"","mbAdmin"=>"","mbCreateDate"=>"",
        "bookmarkId"=>"","bookmarkCreateDate"=>"","contentId"=>"",
        "mlevelId"=>"","mlevelName"=>"","mlevelStatus"=>"",
        "mplId"=>"", "mplName"=>"","mplPoinMin"=>"","mplPoinMax"=>"",
        "mpId"=>"","mpSection"=>"","mpName"=>"","mpPoin"=>"","mpCreateDate"=>"",
        "mpsId"=>"","mpsCrJoin"=>"","mpsCrGradeA"=>"","mpsCrGradeB"=>"","mpsCrGradeC"=>"","mpsCrGradeD"=>"",
        "mpsCcJoin"=>"","mpsCcGradeA"=>"","mpsCcGradeB"=>"","mpsCcGradeC"=>"","mpsCcGradeD"=>"",
        "mpsKsApproved"=>"","mpsKsRejected"=>"","mpsKsLiked"=>"","mpsStart"=>"","mpsEnd"=>"",
        "mpsCreateDate"=>"","mpsCreateBy"=>"", "msetPlayerId"=>"","msetChannel"=>"","msetRegDate"=>"","msetUpdateDate"=>"","msetPush"=>"",
    ];
    var $beginRec,$endRec;
    var $lastInsertId;


    // MEMBER START //
    function auth_member($user,$pass,$groupId){
        $result = false;
        $sql = "SELECT * FROM _member 
				WHERE (group_id = '".$groupId."' AND member_nip = '".$user."' AND member_password = '".$pass."') 
					OR (group_id = '".$groupId."' AND member_email = '".$user."' AND member_password = '".$pass."') ";
        $data = $this->doQuery($sql);
        if(count($data)>0){
            if($data[0]['member_status']=="block"){
                $result = false;
            }
            else{
                $result = true;
                $_SESSION['Member']['Id'] 		= $data[0]['member_id'];
                $_SESSION['Member']['Name'] 	= $data[0]['member_name'];
                $_SESSION['Member']['LoginWeb'] = $data[0]['member_login_web'];
                $_SESSION['Member']['LoginApk'] = $data[0]['member_login_apk'];
                $_SESSION['Member']['LoginIpa'] = $data[0]['member_login_ipa'];
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

    function insert_member_level($recData){
        $sql = "INSERT INTO _member_level VALUES('','".$recData['mlevelName']."','".$recData['mlevelStatus']."')";
        $result = $this->execute($sql);
        return $result;
    }

    function update_member_level($recData){
        $sql = "UPDATE _member_level 
				SET mlevel_name = '".$recData['mlevelName']."', 
					mlevel_status = '".$recData['mlevelStatus']."' 				
				WHERE mlevel_id = '".$this->recData['mlevelId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function delete_member_level(){
        $sql = "DELETE FROM _member_level WHERE mlevel_id = '".$this->recData['mlevelId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_member_level($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _member_level ORDER BY mlevel_id";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _member_level WHERE mlevel_status = 'active' ORDER BY mlevel_id ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _member_level WHERE mlevel_id = '".$this->recData['mlevelId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    function get_level_name($id){
        $arrId = explode(",",$id);
        $levelName = "";
        if($id=="all"){
            $levelName = "SEMUA LEVEL";
        }
        else{
            $dataId = "'";
            $dataId .= join("','",$arrId);
            $dataId .= "'";
            $sql = "SELECT mlevel_name FROM _member_level WHERE mlevel_id IN(".$dataId.")";
            $data = $this->doQuery($sql);
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    if($i>0) $levelName.= ", ";
                    $levelName .= $data[$i]['mlevel_name'];
                }
            }
        }
        return $levelName;
    }

    function get_member_mlevelId($memberId){
        $sql = "SELECT mlevel_id FROM _member WHERE member_id = '".$memberId."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return intval($data[0]['mlevel_id']);
    }

    function get_member_bidang($memberId){
        $sql = "SELECT member_desc FROM _member WHERE member_id = '".$memberId."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['member_desc'];
    }

    function insert_member($recData){
        $sql = "INSERT INTO _member  
				VALUES('','".$recData['groupId']."','".$recData['mlevelId']."','".$recData['memberName']."',
				'".$recData['memberNip']."','".$recData['memberType']."','".$recData['memberEmail']."',
				'".$recData['memberPassword']."','".$recData['memberLoginWeb']."','".$recData['memberLoginApk']."',
				'".$recData['memberLoginIpa']."','".$recData['memberRegId']."','".$recData['memberRegChannel']."',
				'".$recData['memberDevice']."','".$recData['memberDesc']."','".$recData['memberJabatan']."',
				'".$recData['memberKelJabatan']."','".$recData['memberUnitKerja']."','".$recData['memberImage']."',
				'".$recData['memberGender']."','".$recData['memberBirthPlace']."','".$recData['memberBirthDate']."',
				'".$recData['memberPhone']."','".$recData['memberAddress']."','".$recData['memberCity']."',
				'".$recData['memberProvince']."','".$recData['memberPostcode']."','".$recData['memberCeo']."','".$recData['memberStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function insert_member_api($data){
		$res = $this->db->insert('_member', $data);
		
		if(!$res) {
			$error = $this->db->error();
			// print_r($error);
			echo 'ups, something is wrong E001';
			exit;
		}
		
        return $this->db->insert_id();
    }

    function insert_member2($recData){
        $sql = "INSERT INTO _member_import 
				VALUES('','".$recData['groupId']."','".$recData['mlevelId']."','".$recData['memberName']."',
				'".$recData['memberNip']."','".$recData['memberType']."','".$recData['memberEmail']."',
				'".$recData['memberPassword']."','".$recData['memberLoginWeb']."','".$recData['memberLoginApk']."',
				'".$recData['memberLoginIpa']."','".$recData['memberRegId']."','".$recData['memberRegChannel']."',
				'".$recData['memberDevice']."','".$recData['memberDesc']."','".$recData['memberImage']."',
				'".$recData['memberGender']."','".$recData['memberBirthPlace']."','".$recData['memberBirthDate']."',
				'".$recData['memberPhone']."','".$recData['memberAddress']."','".$recData['memberCity']."',
				'".$recData['memberProvince']."','".$recData['memberPostcode']."',''".$recData['memberCeo']."',".$recData['memberStatus']."',NOW())";
        $this->db->query($sql);
        return $this->lastInsertId = $this->db->insert_id();
    }

    function update_member($opt="",$recData,$field="",$value=""){
        if($opt==""){
            $data = [
                'group_id'				=> $recData['groupId'],
                'mlevel_id'				=> $recData['mlevelId'],
                'member_name'			=> $recData['memberName'],
                'member_nip'            => $recData['memberNip'],
                'member_type' 			=> $recData['memberType'],
                'member_email'		=> $recData['memberEmail'],
                'member_password'		=> $recData['memberPassword'],
                'member_login_web'	=> $recData['memberLoginWeb'],
                'member_login_apk'		=> $recData['memberLoginApk'],
                'member_login_ipa'		=> $recData['memberLoginIpa'],
                'member_reg_id'			=> $recData['memberRegId'],
                'member_reg_channel'    => $recData['memberRegChannel'],
                'member_device'			=> $recData['memberDevice'],
                'member_desc'				=> $recData['memberDesc'],
                'member_jabatan' 		=> $recData['memberJabatan'],
                'member_kel_jabatan' 	=> $recData['memberKelJabatan'],
                'member_unit_kerja' 	=> $recData['memberUnitKerja'],
                'member_image'			=> $recData['memberImage'],
                'member_gender'			=> $recData['memberGender'],
                'member_birth_place'	=> $recData['memberBirthPlace'],
                'member_birth_date'	=> $recData['memberBirthDate'],
                'member_phone'			=> $recData['memberPhone'],
                'member_address'		=> $recData['memberAddress'],
                'member_city'				=> $recData['memberCity'],
                'member_province'		=> $recData['memberProvince'],
                'member_postcode'		=> $recData['memberPostcode'],
                'member_ceo'				=> $recData['memberCeo'],
                'member_status' 			=> $recData['memberStatus']
            ];
            $this->db->update('_member', $data, ['member_id'=>$this->recData['memberId']]);
//            $sql = "UPDATE _member
//					SET group_id					= '".$recData['groupId']."',
//						mlevel_id						= '".$recData['mlevelId']."',
//						member_name			= '".$recData['memberName']."',
//						member_nip					= '".$recData['memberNip']."',
//						member_type 				= '".$recData['memberType']."',
//						member_email			= '".$recData['memberEmail']."',
//						member_password		= '".$recData['memberPassword']."',
//						member_login_web	= '".$recData['memberLoginWeb']."',
//						member_login_apk		= '".$recData['memberLoginApk']."',
//						member_login_ipa		= '".$recData['memberLoginIpa']."',
//						member_reg_id			= '".$recData['memberRegId']."',
//						member_reg_channel 	= '".$recData['memberRegChannel']."',
//						member_device			= '".$recData['memberDevice']."',
//						member_desc				= '".$recData['memberDesc']."',
//						member_jabatan 		= '".$recData['memberJabatan']."',
//						member_kel_jabatan 	= '".$recData['memberKelJabatan']."',
//						member_unit_kerja 	= '".$recData['memberUnitKerja']."',
//						member_image			= '".$recData['memberImage']."',
//						member_gender			= '".$recData['memberGender']."',
//						member_birth_place	= '".$recData['memberBirthPlace']."',
//						member_birth_date	= '".$recData['memberBirthDate']."',
//						member_phone			= '".$recData['memberPhone']."',
//						member_address		= '".$recData['memberAddress']."',
//						member_city				= '".$recData['memberCity']."',
//						member_province		= '".$recData['memberProvince']."',
//						member_postcode		= '".$recData['memberPostcode']."',
//						member_ceo				= '".$recData['memberCeo']."',
//						member_status 			= '".$recData['memberStatus']."'
//					WHERE member_id = '".$this->recData['memberId']."' ";
//            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _member SET ".$field."='".$value."' WHERE member_id = '".$this->recData['memberId']."' ";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
    }

    function update_member_api($data, $nip){
        $this->db->update('_member', $data, ['member_nip'=>$nip]);
        return $this->db->affected_rows();
    }

    function set_login($memberId,$channel,$session){
        if($channel=="web"){$field = "member_login_web";}
        if($channel=="android"){$field = "member_login_apk";}
        if($channel=="ios"){$field = "member_login_ipa";}

        $sql = "UPDATE _member SET ".$field." = '".$session."'
				WHERE member_id = '".$memberId."' ";

        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function get_login_session($memberId,$channel){
        if($channel=="android"){ $channel = "apk";}
        if($channel=="ios"){ $channel = "ipa";}

        if(!in_array($channel,array("apk","ipa"))){
            return 0;
        }
        else{
            $sql = "SELECT member_login_".$channel." FROM _member WHERE member_id = '".intval($memberId)."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['member_login_'.$channel];
        }
    }

    function get_group_byid($memberId){
        $sql = "SELECT group_id FROM _member WHERE member_id = '".$memberId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['group_id'];
    }

    function delete_member($memberId){
        $sql = "DELETE FROM _member WHERE member_id = IN('".$memberId."') ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_member_login($user,$pass,$groupId){
        $sql = "SELECT * FROM _member 
				WHERE (member_nip = '".$user."' AND member_password = '".$pass."' AND group_id = '".$groupId."' ) 
					OR (member_email = '".$user."' AND member_password = '".$pass."' AND group_id = '".$groupId."' ) ";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    function select_member_login_api($nip, $token){
        $sql = "SELECT * FROM _member 
				WHERE (member_nip = '".$nip."' AND member_token = '".$token."')";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    function get_group_type($memberId){
        $sql = "SELECT group_id, member_type FROM _member WHERE member_id = '".$memberId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }


    function select_member($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _member ORDER BY member_create_date DESC";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
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
					WHERE  (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
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
					WHERE  (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
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
					WHERE  (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
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
					WHERE  (member_name LIKE '%".$_SESSION['Search']['Keyword']."%' OR 
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
            $sql = "SELECT * FROM _member WHERE member_type = '".$this->recData['memberType']."' 
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
            $result = $query->result_array();
            return $result?$result[0]:NULL;
        }
        elseif($opt=="byEmail"){
            $sql = "SELECT * FROM _member WHERE member_email = '".$this->recData['memberEmail']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:NULL;
        }
        elseif($opt=="checkRegister"){
            $sql = "SELECT * FROM _member 
					WHERE (member_email = '".$this->recData['memberEmail']."' 
						OR member_nip = '".$this->recData['memberNip']."') 
					";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:NULL;
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
            return $data?$data[0]:NULL;
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
		elseif($opt=="levelKaryawanById"){
            $sql = "SELECT m.id_level_karyawan, l.nama as nama_level_karyawan FROM _member m, _member_level_karyawan l WHERE l.id=m.id_level_karyawan and m.member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result?$result[0]:'';
        }
        elseif($opt=="byNip"){
            $sql = "SELECT * FROM _member WHERE member_nip = '".$this->recData['memberNip']."' ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result?$result[0]:NULL;
        }
    }

    function select_quick_member(){
        $sql = "SELECT a.member_id, a.member_name, a.member_nip, a.member_email, b.group_name 
				FROM _member a, _group b 
				WHERE a.group_id = b.group_id  AND a.member_nip!=''  
				ORDER BY a.member_id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function is_email_exists($email){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _member WHERE member_email = '".$email."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function is_group_nip_exists($groupId,$nip){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _member 
				WHERE group_id = '".$groupId."' AND member_nip = '".$nip."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }


    function count_member_reg($opt=""){
        if($opt==""){
            //$dataDef = array("apk"=>0,"ipa"=>0,"web"=>0);
            $sql = "SELECT member_reg_channel AS CHANNEL, COUNT(*) AS TOTAL 
					FROM _member 
					GROUP BY CHANNEL ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $result = array_column($data,"TOTAL","CHANNEL");
            //$result = array_merge($dataDef,$data);
            return $result;
        }
        elseif($opt=="group"){
            $dataDef = array("apk"=>0,"ipa"=>0,"web"=>0);
            $sql = "SELECT member_reg_channel AS CHANNEL, COUNT(*) AS TOTAL 
					FROM _member WHERE group_id = '".$this->recData['groupId']."' 
					GROUP BY CHANNEL ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $result = @array_column($data,"TOTAL","CHANNEL");
            $result = @array_merge($dataDef,$result);
            return $result;
        }

    }

    function select_member_to_culture($group,$level){
        $sql = "SELECT member_id  FROM _member WHERE group_id IN (".$group.") AND mlevel_id IN (".$level.")";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function select_member_to_classroom($group,$level){
        $sql = "SELECT member_id  FROM _member WHERE group_id IN (".$group.") AND mlevel_id IN (".$level.")";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function select_member_installed($channel,$groupId=""){
        if($channel == "android"){$ext = "apk";}
        if($channel == "ios"){$ext = "ipa";}

        $sql = "SELECT COUNT(*) AS TOTAL  FROM _member WHERE member_login_".$ext." != ''  ";
        if($groupId!=""){
            $sql .= " AND group_id = '".$groupId."' ";
        }

        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function data_member_installed($channel,$groupId=""){
        if($channel == "android"){$ext = "apk";}
        if($channel == "ios"){$ext = "ipa";}

        if($channel=="android"){
            $sql = "SELECT a.*,b.group_name FROM _member a, _group b 
							WHERE a.group_id = b.group_id 
							AND a.member_login_apk != '' AND a.member_login_ipa=''  ";
            if(intval($groupId)!=0){
                $sql .= " AND a.group_id = '".$groupId."' ";
            }
        }
        elseif($channel=="ios"){
            $sql = "SELECT a.*,b.group_name FROM _member a, _group b 
							WHERE a.group_id = b.group_id 
							AND a.member_login_ipa != ''  ";
            if(intval($groupId)!=0){
                $sql .= " AND a.group_id = '".$groupId."' ";
            }
        }


        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function data_member_notinstalled($groupId=""){
        $sql = "SELECT a.*,b.group_name FROM _member a, _group b 
						WHERE a.group_id = b.group_id 
							AND a.member_login_apk = ''  AND a.member_login_ipa= ''";
        if(intval($groupId)!=0){
            $sql .= " AND a.group_id = '".$groupId."' ";
        }
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // MEMBER END //


    // MEMBER BLOCK //

    function insert_member_block($recData){
        $sql = "INSERT INTO _member_block 
				VALUES( '','".$recData['memberId']."','".$recData['mbStatus']."',
						'".$recData['mbReason']."','".$recData['mbAdmin']."','".$recData['mbCreateDate']."')";
        $result = $this->execute($sql);
        return $result;
    }

    function delete_member_block($id){
        $sql = "DELETE FROM _member_block WHERE mb_jd IN(".$id.")";
        $result = $this->execute($sql);
        return $result;
    }

    function select_member_block($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _member_block WHERE member_id = '".$this->recData['memberId']."' 
					ORDER BY mb_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="last"){
            $sql = "SELECT * FROM _member_block WHERE member_id = '".$this->recData['memberId']."' 
					ORDER BY mb_create_date DESC LIMIT 1";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    // MEMBER END //

    // MEMBER BOOKMARK START //

    function insert_bookmark($recData){
        $sql = "INSERT INTO _member_bookmark VALUES('','".$recData['memberId']."','".$recData['contentId']."',NOW())";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_bookmark($memberId,$contentId){
        $sql = "DELETE FROM _member_bookmark WHERE member_id = '".$memberId."' AND content_id = '".$contentId."'";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_bookmark($opt=""){
        if($opt==""){
            $sql = "SELECT a.*, b.*, c.*, d.section_name FROM _member_bookmark a, _member b, _content c LEFT JOIN _section d ON c.section_id=d.section_id
					WHERE a.member_id = b.member_id AND a.content_id = c.content_id 
						AND a.member_id = '".$this->recData['memberId']."' 
					ORDER BY a.bookmark_create_date DESC ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member_bookmark a, _member b, _content c 
					WHERE a.member_id = b.member_id AND a.content_id = c.content_id 
						AND a.member_id = '".$this->recData['memberId']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function in_bookmark($memberId,$contentId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _member_bookmark 
				WHERE member_id = '".$memberId."' AND content_id = '".$contentId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function set_bookmark($contentId){
        if(isset($_POST['setBookmark'])){
            $recData['memberId'] = $_SESSION['Member']['Id'];
            $recData['contentId'] = $contentId;

        }
    }

    // MEMBER BOOKMARK END //


    // MEMBER SETTING START //

    function insert_member_setting($recData){
        $sql = "INSERT INTO _member_setting 
				VALUES('".$recData['msetPlayerId']."','".$recData['msetChannel']."',
						'".$recData['memberId']."','".$recData['msetRegDate']."',
						'".$recData['msetUpdateDate']."','".$recData['msetPush']."')";
        $result = $this->db->query($sql);
        return $result;
    }

    function update_member_setting($opt="",$recData=""){
        if($opt=="byPlayerId"){
            $sql = "UPDATE _member_setting 
					SET member_id 		= '".$recData['memberId']."', 
						mset_channel	= '".$recData['msetChannel']."', 
						mset_update_date= '".$recData['msetUpdateDate']."', 
						mset_push		= '".$recData['msetPush']."' 
					WHERE mset_playerid = '".$recData['msetPlayerId']."' 
					";
            $result = $this->db->query($sql);
            return $result;
        }
        elseif($opt=="byMemberId"){
            $sql = "UPDATE _member_setting 
					SET mset_playerid	= '".$recData['msetPlayerId']."', 
						mset_channel	= '".$recData['msetChannel']."', 
						mset_reg_date	= '".$recData['msetRegDate']."', 
						mset_update_date= '".$recData['msetUpdateDate']."', 
						mset_push		= '".$recData['msetPush']."' 
					WHERE member_id = '".$recData['memberId']."' 
					";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="logoutAll"){
            $sql = "UPDATE _member_setting SET mset_login = '0' WHERE member_id = '".$recData['memberId']."' ";
            $result = $this->execute($sql);
            return $result;
        }
        elseif($opt=="logoutByPlayerId"){
            $sql = "UPDATE _member_setting SET mset_login = '0' 
					WHERE member_id = '".$recData['memberId']."' 
						AND mset_playerid = '".$recData['msetPlayerId']."' ";
            $result = $this->db->query($sql);
            return $result;
        }
    }

    function is_playerid_exists($playerId){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _member_setting WHERE mset_playerid = '".$playerId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function delete_member_setting($playerId=""){
        $sql = "DELETE FROM _member_setting WHERE mset_playerid IN(".$playerId.") ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_member_setting($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _member_setting a, _member b 
					WHERE a.member_id = b.member_id ";
            if(intval($limit)>0){
                $sql .= " LIMIT 0, ".$limit;
            }
            else{
                $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member_setting a, _member b 
					WHERE a.member_id = b.member_id";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="activePush"){
            $sql = "SELECT mset_playerid FROM _member_setting 
					WHERE member_id != '0' AND mset_push = '1' ";
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
            $sql = "SELECT COUNT(*) AS TOTAL FROM _member_setting 
					WHERE member_id != '0' AND mset_push = '1' ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="free"){
            $sql = "SELECT mset_playerid FROM _member_setting 
					WHERE mset_push = '1' ";
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="freemium"){
            // select groupId, mlevelId
            $sql = "SELECT mset_playerid FROM _member_setting 
					WHERE mset_push = '1' AND member_id != '0' ";
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="freemiumGroup"){
            $sql = "SELECT a.mset_playerid 
					FROM _member_setting a, _member b 
					WHERE a.member_id = b.member_id 
						AND a.mset_push = '1' AND a.member_id != '0' 
						AND b.group_id = '".$this->recData['groupId']."' ";
            if($this->recData['mlevelId']!="all"){
                $sql .= " AND b.mlevel_id IN(".$this->recData['mlevelId'].") ";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }

    }

    function get_member_classroom($classroomId){

        $sql = "SELECT mset_playerid FROM _member_setting 
				WHERE mset_push = '1' 
				AND member_id IN(SELECT member_id FROM _classroom_member WHERE cr_id = '".$classroomId."' ) ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;

    }

    function get_member_culture($cultureId){

        $sql = "SELECT mset_playerid FROM _member_setting 
				WHERE mset_push = '1' 
				AND member_id IN(SELECT member_id FROM _culture_member WHERE cr_id = '".$cultureId."' ) ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;

    }

    // MEMBER SETTING END //

    function stat_member_activity($groupId, $orderBy="", $orderType=""){
        $sql = "SELECT a.member_id, a.member_name, a.member_nip, b.total_hits, 
				c.total_download,  d.total_topic, e.total_comment 
				
				FROM _member a 
				LEFT JOIN (SELECT member_id, COUNT(*) AS total_hits FROM _content_hits GROUP BY member_id) AS b 
					ON a.member_id = b.member_id 
				
				LEFT JOIN (SELECT member_id, COUNT(*) AS total_download FROM _media_download GROUP BY member_id) AS c 
					ON a.member_id = c.member_id 
					
				LEFT JOIN (SELECT member_id, COUNT(*) AS total_topic FROM _forum_group GROUP BY member_id) AS d 
					ON a.member_id = d.member_id 
					
				LEFT JOIN (SELECT member_id, COUNT(*) AS total_comment FROM _forum_group_chat GROUP BY member_id) AS e 
					ON a.member_id = e.member_id 
						
				
				WHERE a.group_id = '".$groupId."' 
					AND (b.total_hits > 0 OR c.total_download > 0 OR d.total_topic > 0 OR e.total_comment > 0 ) 
				ORDER BY b.total_hits DESC, c.total_download DESC, d.total_topic DESC, e.total_comment DESC 
				";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }


    // MEMBER POIN  START //

    //MP LEVEL

    function insert_member_poin_level($recData=array()){
        $sql = "INSERT INTO _member_poin_level VALUES('','".$recData['mplName']."','".$recData['mplPoinMin']."','".$recData['mplPoinMax']."')";
        $result = $this->db->query($sql);
        return $result;
    }

    function update_member_poin_level($recData=array()){
        $sql = "UPDATE _member_poin_level 
						SET mpl_name			= '".$recData['mplName']."', 
								mpl_poin_min	= '".$recData['mplPoinMin']."', 
								mpl_poin_max	= '".$recData['mplPoinMax']."'
						WHERE mpl_id = '".$recData['mplId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_member_poin_level($recData=array()){
        $sql = "DELETE FROM _member_poin_level WHERE mpl_id = '".$recData['mplId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_member_poin_level($opt="",$recData=array()){
        if($opt==""){
            $sql = "SELECT * FROM _member_poin_level ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _member_poin_level WHERE mpl_id = '".$recData['mplId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
			return $data?$data[0]:[];
        }
        elseif($opt=="byPoin"){
            $sql = "SELECT * FROM _member_poin_level WHERE mpl_poin_min <= '".$recData['mPoin']."' 
                    AND mpl_poin_max >= '".$recData['mPoin']."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:[];
        }
    }


    // MP SETTING

    function insert_member_poin_setting($recData=array()){
        $sql = "INSERT INTO _member_poin_setting 
						VALUES('','".$recData['mpsCrJoin']."','".$recData['mpsCrGradeA']."','".$recData['mpsCrGradeB']."','".$recData['mpsCrGradeC']."',
										'".$recData['mpsCrGradeD']."','".$recData['mpsCcJoin']."','".$recData['mpsCcGradeA']."','".$recData['mpsCcGradeB']."',
										'".$recData['mpsCcGradeC']."','".$recData['mpsCcGradeD']."','".$recData['mpsKsApproved']."',	'".$recData['mpsKsReject']."',
										'".$recData['mpsKsLiked']."','".$recData['mpsStart']."','".$recData['mpsEnd']."',NOW(),'".$recData['mpsCreateBy']."') ";
        $this->execute($sql);
        return $this->lastInsertId = mysqli_insert_id($this->con);
    }

    function update_member_poin_setting($recData=array()){
        $sql = "UPDATE _member_poin_setting 
						SET mps_cr_join			= '".$recData['mpsCrJoin']."', 
								mps_cr_grade_a	= '".$recData['mpsCrGradeA']."', 
								mps_cr_grade_b	= '".$recData['mpsCrGradeB']."', 
								mps_cr_grade_c	= '".$recData['mpsCrGradeC']."', 
								mps_cr_grade_d	= '".$recData['mpsCrGradeD']."', 
								mps_cc_join			= '".$recData['mpsCcJoin']."', 
								mps_cc_grade_a	= '".$recData['mpsCcGradeA']."', 
								mps_cc_grade_b	= '".$recData['mpsCcGradeB']."', 
								mps_cc_grade_c	= '".$recData['mpsCcGradeC']."', 
								mps_cc_grade_d	= '".$recData['mpsCcGradeD']."', 
								mps_ks_approved = '".$recData['mpsKsApproved']."', 
								mps_ks_reject		= '".$recData['mpsKsReject']."', 
								mps_ks_liked		= '".$recData['mpsKsLiked']."', 
								mps_start				= '".$recData['mpsStart']."', 
								mps_end				= '".$recData['mpsEnd']."' 
						WHERE mps_id = '".$recData['mpsId']."' 				
						";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_member_poin_setting($recData=array()){
        $sql = "DELETE FROM _member_poin_setting WHERE mpl_id = '".$recData['mpsId']."' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_member_poin_setting($opt="",$recData=array()){
        if($opt==""){
            $sql = "SELECT * FROM _member_poin_setting WHERE mps_start <= now() AND (mps_end > now() OR mps_end IS NULL)
                    ORDER BY mps_create_date DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _member_poin_setting WHERE mps_id = '".$recData['mpsId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    function select_member_poin_setting_monthly($opt="",$recData=array()){
        if($opt==""){
            $sql = "SELECT mpsm.* FROM _member_poin_setting_monthly mpsm LEFT JOIN _member_poin_setting mps ON mps.mps_id=mpsm.mps_id
                    WHERE mps.mps_start <= now() AND (mps.mps_end > now() OR mps.mps_end IS NULL)";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byPercentage"){
            $sql = "SELECT mpsm.* FROM _member_poin_setting_monthly mpsm LEFT JOIN _member_poin_setting mps ON mps.mps_id=mpsm.mps_id
                    WHERE mps.mps_start <= now() AND (mps.mps_end > now() OR mps.mps_end IS NULL) AND '".$recData['percentage']."' >= mps_monthly_percent_min 
                    AND '".$recData['percentage']."' <= mps_monthly_percent_max";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]['mps_monthly_poin']:0;
        }
    }


    // MEMBER POIN

    function insert_member_poin($recData=array()){
        $data = [
            'member_id'     => $recData['memberId'],
            'mp_section'    => $recData['mpSection'],
            'mp_name'       => $recData['mpName'],
            'mp_poin'       => $recData['mpPoin'],
            'mp_content_id' => $recData['mpContentId'],
            'mp_create_date'=> date('Y-m-d H:i:s')
        ];
//        $sql = "INSERT INTO _member_poin
//						VALUES ('','".$recData['memberId']."','".$recData['mpSection']."','".$recData['mpContentId']."',
//						'".$recData['mpName']."','".$recData['mpPoin']."',NOW())";
//        $this->db->query($sql);
        $this->db->insert('_member_poin', $data);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_member_poin($recData=array()){
        $sql = "UPDATE _member_poin 
					SET member_id 	= '".$recData['memberId']."', 
							mp_section 	= '".$recData['mpSection']."', 
							mp_name 		= '".$recData['mpName']."', 
							mp_poin			= '".$recData['mpPoin']."' 
					WHERE mp_id = '".$recData['mpId']."' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function delete_member_poin($recData=array()){
        $sql = "DELETE FROM _member_poin WHERE mp_id = '".$recData['mpId']."' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    function select_member_poin($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _member_poin ORDER BY mp_create_date DESC ";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit ;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _member_poin WHERE mp_id = '".$recData['mpId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _member_poin WHERE member_id = '".$recData['memberId']."' ORDER BY mp_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit ; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countByMemberId"){
            $sql = "SELECT COUNT (*) AS TOTAL FROM _member_poin WHERE member_id = '".$recData['memberId']."' ORDER BY mp_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit ; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="sumByMemberId"){
            $sql = "SELECT SUM(mp_poin) AS TOTAL FROM _member_poin WHERE member_id = '".$recData['memberId']."'";
            if ($recData['interval']){
                if ($recData['interval'] == 'daily'){
                    $sql .= " AND cast(mp_create_date as Date) = cast(now() as Date)";
                } elseif ($recData['interval'] == 'monthly'){
                    $sql .= " AND MONTH(mp_create_date) = MONTH(CURRENT_DATE)";
                } elseif ($recData['interval'] == 'yearly'){
                    $sql .= " AND YEAR(mp_create_date) = YEAR(CURRENT_DATE)";
                }
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL']?$data[0]['TOTAL']:0;
        }
        elseif($opt=="bySection"){
            $sql = "SELECT * FROM _member_poin WHERE member_id = '".$recData['memberId']."' 
                    AND mp_section = '".$recData['mpSection']."'";
            if ($recData['interval']){
                if ($recData['interval'] == 'daily'){
                    $sql .= " AND cast(mp_create_date as Date) = cast(now() as Date)";
                } elseif ($recData['interval'] == 'monthly'){
                    $sql .= " AND MONTH(mp_create_date) = MONTH(CURRENT_DATE)";
                } elseif ($recData['interval'] == 'yearly'){
                    $sql .= " AND YEAR(mp_create_date) = YEAR(CURRENT_DATE)";
                }
            }
            $sql .= " ORDER BY mp_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit ; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="sumBySection"){
            $sql = "SELECT SUM(mp_poin) AS TOTAL FROM _member_poin WHERE member_id = '".$recData['memberId']."' 
                    AND mp_section = '".$recData['mpSection']."'";
            if ($recData['interval']){
                if ($recData['interval'] == 'daily'){
                    $sql .= " AND cast(mp_create_date as Date) = cast(now() as Date)";
                } elseif ($recData['interval'] == 'monthly'){
                    $sql .= " AND MONTH(mp_create_date) = MONTH(CURRENT_DATE)";
                } elseif ($recData['interval'] == 'yearly'){
                    $sql .= " AND YEAR(mp_create_date) = YEAR(CURRENT_DATE)";
                }
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL']?$data[0]['TOTAL']:0;
        }
    }
    // MEMBER POIN END //

    // MEMBER RESET //
    function select_member_reset($email, $token=''){
        $sql = "SELECT * FROM _member_reset WHERE member_email='".$email."' AND reset_status='0'";
        if ($token){
            $sql .= " AND reset_token='".$token."'";
        }
        $sql .= " ORDER BY reset_id DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function insert_member_reset($member_id, $member_email, $reset_link, $reset_token){
        $data = [
            'member_id'     => $member_id,
            'member_email'  => $member_email,
            'reset_link'    => $reset_link,
            'reset_token'   => $reset_token,
            'reset_status'  => '0',
            'reset_create_date' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('_member_reset', $data);
        return $this->db->insert_id();
    }

    function update_member_reset($reset_id, $status){
        $sql = "UPDATE _member_reset SET reset_status='".$status."' WHERE reset_id='".$reset_id."'";
        $result = $this->db->query($sql);
        return $result;
    }
    // END OF MEMBER RESET //


    // RANK
    function select_rank($opt="",$recData=array(),$limit){
        if($opt==""){
            $sql = "SELECT b.* FROM (SELECT a. *,
                   @rank := @rank + 1 member_rank
                 FROM (SELECT *, Max(member_poin) poin
                       FROM _member WHERE member_status = 'active' AND member_name NOT LIKE '%admin%'
                       GROUP  BY member_id
                       ORDER  BY poin DESC)a,
                   (SELECT @rank := 0)r)b";
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        if($opt=="byGroup"){
            $sql = "SELECT b.* FROM (SELECT a. *,
                   @rank := @rank + 1 member_rank
                 FROM (SELECT *, Max(member_poin) poin
                       FROM _member
                       WHERE group_id='".$recData['groupId']."'
                       AND member_status = 'active' AND member_name NOT LIKE '%admin%'
                       GROUP  BY member_id
                       ORDER  BY poin DESC)a,
                   (SELECT @rank := 0)r)b";
            if ($recData['memberId']!=''){
                $sql .= " WHERE member_id='".$recData['memberId']."'";
            }
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byUserId"){
            $sql = "SELECT b.* FROM (SELECT a. *,
                   @rank := @rank + 1 member_rank
                 FROM (SELECT *, Max(member_poin) poin
                       FROM _member WHERE member_status = 'active' AND member_name NOT LIKE '%admin%' 
                       GROUP  BY member_id
                       ORDER  BY poin DESC)a,
                   (SELECT @rank := 0)r)b
                    WHERE member_id='".$recData['memberId']."'";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result?$result[0]['member_rank']:null;
        }
        elseif($opt=="thisMonth"){
            $sql = "SELECT b.* FROM (SELECT a.*,
                   @rank := @rank + 1 member_rank
                 FROM (SELECT
						  m1.*,
						  SUM(mp_poin) poin
						FROM _member_poin
						  LEFT JOIN `_member` m1 ON m1.member_id = `_member_poin`.member_id
						WHERE member_status = 'active' AND member_name NOT LIKE '%admin%'
						GROUP BY `_member_poin`.member_id
						ORDER BY poin DESC) a, (SELECT @rank := 0) r) b";
            if ($recData['memberId']!=''){
                $sql .= " WHERE b.member_id='".$recData['memberId']."'";
            }
            if(intval($limit)==0){ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec; }
            else { $sql .= " LIMIT 0,".$limit; }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    // MEMBER SALDO START //
    function select_member_saldo($opt="",$recData=array(),$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _member_saldo ORDER BY mp_create_date DESC ";
            if(intval($limit)>0){
                $sql .= " LIMIT 0,".$limit ;
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byMemberId"){
            $sql = "SELECT * FROM _member_saldo WHERE member_id = '".$recData['memberId']."' ORDER BY ms_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit ; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="sumByMemberId"){
            $sql = "SELECT SUM(ms_saldo) AS TOTAL FROM _member_saldo WHERE member_id = '".$recData['memberId']."'";
            if ($recData['type']){
                $sql .= " AND ms_type = '".$recData['type']."'";
            }
            if ($recData['interval']){
                if ($recData['interval'] == 'daily'){
                    $sql .= " AND cast(ms_create_date as Date) = cast(now() as Date)";
                } elseif ($recData['interval'] == 'monthly'){
                    $sql .= " AND MONTH(ms_create_date) = MONTH(CURRENT_DATE)";
                } elseif ($recData['interval'] == 'yearly'){
                    $sql .= " AND YEAR(ms_create_date) = YEAR(CURRENT_DATE)";
                } elseif ($recData['interval'] == 'previousMonth'){
                    $sql .= " AND YEAR(ms_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) 
                            AND MONTH(ms_create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
                }
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL']?$data[0]['TOTAL']:0;
        }
    }

    public function get_member_saldo($member_id = 0){
        $this->db->select('member_saldo');
        $this->db->where('member_id', $member_id);
        $data = $this->db->get('_member', 1)->row_array();

        if($data){
            return $data['member_saldo'];
        }else{
            return 0;
        }
    }

    public function add_purchase_to_member_saldo($member_id = 0, $name = '', $saldo = 0, $cr_id = 0){
        $sv = [
            'member_id' => $member_id,
            'ms_source' => 'CR',
            'ms_name' => $name,
            'ms_saldo' => $saldo,
            'cr_id' => $cr_id,
            'ms_type' => 'OUT',
            'ms_create_date' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('_member_saldo', $sv);
        $this->recalculate_saldo($member_id);
    }

    // saldo recalculate after transaction.
    // saldo resetted yearly.
    
    public function recalculate_saldo($member_id = 0){
        $this->db->select('SUM(ms_saldo) as saldo_in');
        $this->db->where('member_id', $member_id);
        $this->db->where('ms_type', 'IN');
        $this->db->where('YEAR(ms_create_date)', date('Y'));
        $saldo = $this->db->get('_member_saldo')->row_array();
        $saldo_in = $saldo['saldo_in'];

        $this->db->select('SUM(ms_saldo) as saldo_out');
        $this->db->where('member_id', $member_id);
        $this->db->where('ms_type', 'OUT');
        $this->db->where('YEAR(ms_create_date)', date('Y'));
        $saldo = $this->db->get('_member_saldo')->row_array();
        $saldo_out = $saldo['saldo_out'];

        $member_saldo = $saldo_in - $saldo_out;

        $this->db->where('member_id', $member_id);
        $this->db->update('_member', array('member_saldo' => $member_saldo));
    }

    // Member Category //
    public function insert_member_category($memberId, $categoryId){
        $sql = "INSERT INTO _member_category VALUES('','".$memberId."','".$categoryId."')";
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    public function select_member_category($opt="", $recData=array(), $limit=""){
        if ($opt == "byMemberId") {
            $sql = "SELECT c.* FROM _member_category mc LEFT JOIN _category c ON mc.category_id = c.cat_id
                    WHERE mc.member_id='" . $recData['memberId'] . "' LIMIT " . $limit;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    public function get_jabatan_all($group_id = 0){
        $this->db->where('group_id', $group_id);
        return $this->db->get('_jabatan')->result_array();
    }

    public function get_jabatan_name($jabatan_id = 0){
        $this->db->select('jabatan_name');
        $this->db->where('jabatan_id', $jabatan_id);
        $data = $this->db->get('_jabatan')->row_array();
        return isset($data['jabatan_name']) ? $data['jabatan_name'] : '';
    }

    // Member Device Token
    public function insert_member_device_token($memberId, $token){
        $sql = "INSERT INTO _member_device_token VALUES ('', '".$memberId."', '".$token."','Y')";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    public function update_member_device_token($memberId, $token, $is_active){
        $sql = "UPDATE _member_device_token SET is_active='".$is_active."' WHERE member_id='".$memberId."' AND device_token='".$token."'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function select_member_device_token($opt="",$recData=array(),$is_active=''){
        if ($is_active){
            $sql_is_active = " AND is_active='".$is_active."'";
        } else {
            $sql_is_active = "";
        }
        if ($opt == "byMemberId"){
            $sql = "SELECT device_token FROM _member_device_token WHERE member_id='".$recData['memberId']."'";
            $sql .= $sql_is_active;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        } elseif ($opt == "byToken"){
            $sql = "SELECT * FROM _member_device_token WHERE device_token='".$recData['token']."' AND member_id='".$recData['memberId']."'";
            $sql .= $sql_is_active;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:NULL;
        }
    }
}
