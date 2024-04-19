<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 09/08/20
 * Time: 16:37
 * @property CI_DB_query_builder db
 */

class Media_model extends CI_Model
{
    var $mediaId,$sectionId,$dataId,$mediaName,$mediaAlias,$mediaDesc,$mediaType,$mediaValue,$mediaSize,$mediaPrimary,$mediaStatus;
    var $recData = ["mdId"=>"","mediaId"=>"","memberId"=>"","mdDate"=>"","mdChannel"=>""];
    var $lastInsertId;


    function insert_media(){
        if(!isset($this->mediaCreateDate)) $this->mediaCreateDate = date('Y-m-d H:i:s');
        $sql = "INSERT INTO _media 
				VALUES('','".$this->sectionId."','".$this->dataId."','".$this->mediaName."','".$this->mediaAlias."','".$this->mediaDesc."',
						'".$this->mediaType."','".$this->mediaValue."','".$this->mediaSize."','".$this->mediaPrimary."','1','".$this->mediaCreateDate."'
						)";
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    function update_media($opt="",$field="",$value=""){
        if($opt=="byField"){
            $sql = "UPDATE _media SET ".$field."='".$value."' WHERE media_id = '".$this->mediaId."' ";
        }
        elseif($opt=="flushPrimary"){
            $sql = "UPDATE _media SET media_primary = '0' 
					WHERE section_id = '".$this->sectionId."' AND data_id = '".$this->dataId."' ";
        }
        else{
            $sql = "UPDATE _media 
					SET section_id 	= '".$this->sectionId."', 
						data_id		= '".$this->dataId."', 
						media_name 	= '".$this->mediaName."',
						media_alias	= '".$this->mediaAlias."',
						media_desc	= '".$this->mediaDesc."',
						media_value	= '".$this->mediaValue."',
						media_size	= '".$this->mediaSize."', 
						media_type	= '".$this->mediaType."',
						media_primary = '".$this->mediaPrimary."',
						media_status = '".$this->mediaStatus."'
					WHERE media_id = '".$this->mediaId."'";
        }
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_media(){
        $dataMedia = $this->db->query("SELECT * FROM _media WHERE media_id = '".$this->mediaId."'")->row_array();
        $sql = "DELETE FROM _media WHERE media_id = '".$this->mediaId."'";
        $this->db->query($sql);
        if ($dataMedia){
            if ($dataMedia['media_type']){
                if (file_exists(MEDIA_IMAGE_PATH.$dataMedia['media_value'])){
                    unlink(MEDIA_IMAGE_PATH.$dataMedia['media_value']);
                }
            }
        }
    }

    function get_media($type="",$sectionId="",$dataId=""){
        $sql = "SELECT * FROM _media WHERE section_id = '".$sectionId."' AND data_id = '".$dataId."' ";
        if($type!="")
            $sql .= " AND media_type = '".$type."' ";
        $sql .= "ORDER BY media_primary DESC ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    function get_primary_image($sectionId="",$dataId="",$class=""){
        $sql = "SELECT * FROM _media 
				WHERE section_id = '".$sectionId."' AND data_id = '".$dataId."' 
					AND media_type = 'image' AND media_primary = '1' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)==0) {$data[0] = array();}
        $imageValue = "noimage.png";
        if(count($data[0])>0 && $data[0]['media_value']!="" && file_exists(MEDIA_IMAGE_PATH.$data[0]['media_value'])){
            $imageValue = $data[0]['media_value'];
        }
        $value = "";
        // todo: tidak valid karena pathnya
        if(file_exists(MEDIA_IMAGE_PATH.$imageValue)){
            $style = ($class!="") ? " class=\"".$class."\"" : "";
            $value = "<img src=\"".URL_MEDIA_IMAGE.$imageValue."\" alt=\"".$data[0]['media_name']."\" ".$style." />";
            $data[0]['media_image'] = $value;
            $data[0]['media_image_link'] = URL_MEDIA_IMAGE.$imageValue;
        }

        return $data[0];
    }

    function get_qrcode($sectionId="",$dataId="",$class=""){
        $sql = "SELECT * FROM _media 
				WHERE section_id = '".$sectionId."' AND data_id = '".$dataId."' 
					AND media_type = 'image' AND media_value LIKE 'qrcontent%' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)==0){ $data[0] = array();}
        $imageValue = "noimage.png";
        if(count($data[0])>0 && count($data)>0 && $data[0]['media_value']!="" && file_exists(MEDIA_IMAGE_PATH."/".$data[0]['media_value'])){
            $imageValue = $data[0]['media_value'];
        }
        if(file_exists(MEDIA_IMAGE_PATH."/".$imageValue)){
            $style = ($class!="") ? " class=\"".$class."\"" : "";
            $value = "<img src=\"".MEDIA_IMAGE_HOST."/".$imageValue."\" alt=\"".$data[0]['media_name']."\" ".$style." />";
            $data[0]['media_qrcode'] = $value;
            $data[0]['media_qrcode_link'] = MEDIA_IMAGE_HOST."/".$imageValue;
        }

        return $data[0];
    }

    function select_media($opt="",$type=""){
        if($opt==""){
            $sql = "SELECT * FROM _media 
					WHERE media_type = '".$type."' AND section_id = '".$this->sectionId."' AND data_id = '".$this->dataId."' 
					ORDER BY media_primary DESC ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _media WHERE media_type = '".$type."' AND section_id = '".$this->sectionId."' AND data_id = '".$this->dataId."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _media WHERE media_id = '".$this->mediaId."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byValue"){
            $sql = "SELECT * FROM _media WHERE media_value = '".$this->mediaValue."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byDataId"){
            $sql = "SELECT * FROM _media WHERE data_id = '".$this->dataId."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }

    function get_last_image(){
        $sql = "SELECT * FROM _media 
				WHERE media_type = 'image' AND section_id = '".$this->sectionId ."' 
					AND data_id = '".$this->dataId."' LIMIT 1 ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }


    // MEDIA DOWNLOAD START //

    function insert_media_download($recData){
        $sql = "INSERT INTO _media_download 
				VALUES('','".$recData['mediaId']."','".$recData['memberId']."',NOW(),'".$recData['cdChannel']."')";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_media_download($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT COUNT(*) AS TOTAL 
					FROM (SELECT * FROM `_media_download` GROUP BY media_id,member_id) AS data 
					WHERE media_id = '".$this->recData['mediaId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byMember"){
            $sql = "SELECT * FROM _media_download a, _media b 
					WHERE a.media_id = b.media_id AND a.member_id = '".$this->recData['memberId']."' 
					GROUP BY a.media_id";
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
        elseif($opt=="countByMember"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _media_download a, _media b 
					WHERE a.media_id = b.media_id AND a.member_id = '".$this->recData['memberId']."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="countByChannel"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _media_download 
					WHERE cd_channel = '".$this->recData['cdChannel']."' 
					GROUP BY media_id, member_id ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="groupByChannel"){
            $sql = "SELECT cd_channel, COUNT(*) AS TOTAL FROM _media_download 
					GROUP BY cd_channel";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="countAll"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM (SELECT * FROM _media_download GROUP BY media_id,member_id) AS data";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
    }

    function update_media_download(){
        $sql = "SELECT * FROM _media_download ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        for($i=0;$i<count($data);$i++){
            $dataId = $data[$i]['media_id'];
            $mediaId = "SELECT media_id FROM _media WHERE data_id = '".$dataId."' ";
            $query = $this->db->query($mediaId);
            $dataMedia = $query->result_array();
            $mId = $dataMedia[0]['media_id'];
            $sqlUpdate = "UPDATE _media_download SET media_id = '".$mId."' WHERE media_id = '".$dataId."' ";
            $this->db->query($sqlUpdate);
        }
    }

    function statistic_download(){
        $sql = "SELECT a.content_id, d.section_alias_back, a.content_name, b.*, c.*, COUNT(*) AS TOTAL  
				FROM _content a, _media_download b, _media c, _section d  
				WHERE a.content_id = c.data_id AND b.media_id = c.media_id AND a.section_id = d.section_id 
				GROUP BY b.media_id  
				ORDER BY TOTAL DESC 
				LIMIT 20";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // MEDIA DOWNLOAD END //
}