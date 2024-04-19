<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 27/08/20
 * Time: 20:05
 * @property CI_DB_query_builder db
 */

class Ads_model extends CI_Model
{
    var $recData = array("adsId"=>"","adsPosition"=>"","adsSponsor"=>"","adsLink"=>"",
        "adsImage"=>"","adsStart"=>"","adsEnd"=>"","adsStatus"=>"","adsOrder"=>"",

        "akId"=>"","akChannel"=>"","akCreateDate"=>""
    );

    var $lastInsertId;
    var $beginRec,$endRec;

    function insert_ads($recData){
        $sql = "INSERT INTO _ads 
				VALUES('','".$recData['adsPosition']."','".$recData['adsSponsor']."','".$recData['adsLink']."',
						'".$recData['adsImage']."','".$recData['adsStart']."','".$recData['adsEnd']."',
						'".$recData['adsStatus']."','".$recData['adsOrder']."',NOW())";
        $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_ads($recData){
        $sql = "UPDATE _ads 
				SET ads_position 	= '".$recData['adsPosition']."', 
					ads_sponsor 	= '".$recData['adsSponsor']."', 
					ads_link		= '".$recData['adsLink']."', 
					ads_image 		= '".$recData['adsImage']."', 
					ads_start 		= '".$recData['adsStart']."', 
					ads_end 		= '".$recData['adsEnd']."', 
					ads_status 		= '".$recData['adsStatus']."', 
					ads_order 		= '".$recData['adsOrder']."' 
				WHERE ads_id = '".$recData['adsId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function update_ads_order(){
        $sql = "UPDATE _ads SET ads_order = '".$this->recData['adsOrder']."' WHERE ads_id = '".$this->recData['adsId']."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function update_ads_expired(){
        $sql = "UPDATE _ads SET ads_status = 'expired' WHERE DATE_FORMAT(ads_end,'%Y-%m-%d') < '".date('Y-m-d')."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function delete_ads($id){
        $sql = "DELETE FROM _ads WHERE ads_id IN(".$id.")";
        $result = $this->execute($sql);
        return $result;
    }

    function select_ads($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _ads ORDER BY ads_order ";
            $data = $this->db->query($sql);
            return $data->result();
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _ads ";
            $data = $this->db->query($sql);
            return $data->row()->TOTAL;
        }
        elseif($opt=="active"){
            $sql = "SELECT * FROM _ads WHERE ads_status = 'active' AND ads_start <= CURDATE() AND ads_end >= CURDATE() ORDER BY ads_order";
            $data = $this->db->query($sql);
            return $data->result();
        }
        elseif($opt=="countActive"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _ads WHERE ads_status = 'active' AND ads_start <= CURDATE() AND ads_end >= CURDATE()";
            $data = $this->db->query($sql);
            return $data->row()->TOTAL;
        }
        elseif($opt=="byId"){
            $sql = "SELECT * FROM _ads WHERE ads_id = '".$this->recData['adsId']."' ";
            $data = $this->db->query($sql);
            return $data->result();
        }
    }

    function report_ads($opt="",$dateStart,$dateEnd){
        if($opt=="filterDate"){
            $sql = "SELECT * FROM _ads 
					WHERE (DATE(ads_start) >= '".date('Y-m-d',strtotime($dateStart))."' 
						AND DATE(ads_end) <= '".date('Y-m-d',strtotime($dateEnd))."') 
					ORDER BY ads_order ";
            $data = $this->doQuery($sql); echo $sql;exit;
            return $data;
        }
        elseif($opt=="countFilterDates"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _ads 
					WHERE (ads_start >= '".date('Y-m-d',strtotime($dateStart))."' 
						AND ads_end <= '".date('Y-m-d',strtotime($dateEnd))."') ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
    }



    function get_id_inrange($date=""){
        $sql = "SELECT ads_id FROM _ads WHERE ads_start <= '".$date."' AND ads_end >= '".$date."' 
				ORDER BY RAND() LIMIT 1";
        $data = $this->doQuery($sql);
        return $data[0]['ads_id'];
    }

    // ADS KLIK

    function insert_ads_klik($recData){
        $sql = "INSERT INTO _ads_klik 
				VALUES ('','".$recData['adsId']."','".$recData['akChannel']."',NOW())";
        $result = $this->execute($sql);
        return $result;
    }

    function select_ads_klik($opt="",$limit=""){
        if($opt==""){
            $sql = "SELECT * FROM _ads_klik a, _ads b 
					WHERE a.ads_id = b.ads_id ORDER BY a.ak_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _ads_klik a, _ads b 
					WHERE a.ads_id = b.ads_id";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byAdsId"){
            $sql = "SELECT * FROM _ads_klik a, _ads b 
					WHERE a.ads_id = b.ads_id AND a.ads_id = '".$this->recData['adsId']."' 
					ORDER BY a.ak_create_date DESC ";
            if(intval($limit)>0){ $sql .= " LIMIT 0,".$limit; }
            else{ $sql .= " LIMIT ".$this->beginRec.",".$this->endRec;}
            $data = $this->doQuery($sql);
            return $data;
        }
        elseif($opt=="countByAdsId"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _ads_klik a, _ads b 
					WHERE a.ads_id = b.ads_id AND a.ads_id = '".$this->recData['adsId']."' ";
            $data = $this->doQuery($sql);
            return $data[0]['TOTAL'];
        }
        elseif($opt=="all"){
            $sql = "SELECT *, DATE_FORMAT(ak_create_date,'%Y-%m-%d') AS DATEKLIK FROM _ads_klik ";
            $data = $this->doQuery($sql);
            return $data;
        }
    }

    function update_ads_klik_id($akId,$adsId){
        $sql = "UPDATE _ads_klik SET ads_id = '".$adsId."' WHERE ak_id = '".$akId."' ";
        $result = $this->execute($sql);
        return $result;
    }

    function select_count_ads_klik(){
        $sql = "SELECT ads_id, ak_channel as CHANNEL, COUNT(*) AS TOTAL FROM _ads_klik GROUP BY ads_id, ak_channel";
        $data = $this->doQuery($sql);
        return $data;
    }

    function report_ads_klik($dateStart,$dateEnd){
        $sql = "SELECT ads_id, ak_channel as CHANNEL, COUNT(*) AS TOTAL 
				FROM _ads_klik 
				WHERE DATE(ak_create_date) >='".$dateStart."' AND DATE(ak_create_date) <= '".$dateEnd."' 
				GROUP BY ads_id, ak_channel";
        $data = $this->doQuery($sql);
        return $data;
    }
}