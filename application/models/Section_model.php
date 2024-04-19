<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 01/09/20
 * Time: 0:23
 * @property CI_DB_query_builder db
 */

class Section_model extends CI_Model
{
    var $sectionId,$sectionName,$sectionDesc,$sectionAliasFront,$sectionAliasBack,
        $sectionType,$sectionContent,$sectionStatus,$sectionPage,$sectionOrder;

    function select_section($opt=""){
        if($opt==""){
            $sql = "SELECT * FROM _section  
					WHERE section_status = '1'  AND (section_name!= 'Section' && section_name!= 'Setting' ) 
					ORDER BY section_type DESC, section_order ASC ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        } elseif($opt=="byId"){
            $sql = "SELECT * FROM _section a 
					LEFT JOIN _section_setting b ON a.section_id = b.section_id 
					WHERE a.section_id = '".$this->sectionId."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        } elseif($opt=="byName"){
            $sql = "SELECT * FROM _section WHERE LOWER(section_name) = '".strtolower($this->sectionName)."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:$data;
        } elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _section WHERE LOWER(section_alias_front) = '".$this->sectionAlias."' AND section_status = '1' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        } elseif($opt=="maxAppOrder"){
            $sql = "SELECT MAX(section_order) AS MAX FROM _section WHERE section_type = 'app'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['MAX'];
        } elseif($opt=="idByName"){
            $sql = "SELECT section_id FROM _section WHERE LOWER(section_name) = '".strtolower($this->sectionName)."' ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['section_id'];
        } elseif($opt=="front"){
            $sql = "SELECT * FROM _section WHERE section_page = '1' AND section_alias_front != '' AND section_type = 'app' ORDER BY section_order ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }

    }

    function current_section($area="",$alias=""){
        $sql = "SELECT * FROM _section WHERE section_alias_".$area." = '".$alias."' AND section_status = '1' AND section_page = '1' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0];
    }

    function get_section_alias($area="",$id=""){
        $sql = "SELECT section_alias_".$area." FROM _section WHERE section_id =  '".intval($id)."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['section_alias_'.$area];
    }

    function is_section($area="",$alias=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _section WHERE section_alias_".$area." = '".$alias."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return ($data[0]['TOTAL']==1) ? true : false;
    }

    function is_section_content($area="",$alias=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _section 
				WHERE section_alias_".$area." = '".$alias."' AND section_content = '1' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return ($data[0]['TOTAL']==1) ? true : false;
    }

    function is_section_active($name=""){
        $sql = "SELECT COUNT(*) AS TOTAL FROM _section 
				WHERE section_name = '".$name."' AND section_status = '1' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return ($data[0]['TOTAL']==1) ? true : false;
    }

    function insert_section(){
        $sql = "INSERT INTO _section 
				VALUES('','".$this->sectionName."','".$this->sectionDesc."','".$this->sectionType."',
						'".$this->sectionAliasFront."','".$this->sectionAliasBack."','".$this->sectionContent."',
						'".$this->sectionStatus."','".$this->sectionPage."','".$this->sectionOrder."'
						)";
        $query = $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_section($opt=""){
        $sql = "UPDATE _section SET ";

        if($opt=="alias"){
            $sql .= " 	section_alias_front = '".$this->sectionAliasFront."',
						section_alias_front = '".$this->sectionAliasFront."'
					";
        }
        elseif($opt=="status"){
            $sql .= "	section_status = '".$this->sectionStatus."'";
        }
        elseif($opt=="order"){
            $sql .= "	section_order = '".$this->sectionOrder."'";
        }
        else{
            $sql .= " 	section_name = '".$this->sectionName."',
						section_desc = '".$this->sectionDesc."',
						section_type = '".$this->sectionType."',
						section_alias_front = '".$this->sectionAliasFront."',
						section_alias_front = '".$this->sectionAliasFront."',
						section_content = '".$this->sectionContent."',
						section_status = '".$this->sectionStatus."',
						section_page = '".$this->sectionPage."',
						section_order = '".$this->sectionOrder."'
					";
        }

        $sql .= " WHERE section_id = '".$this->sectionId."'";
        $query = $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }

    function delete_section(){
        $sql = "DELETE FROM _section WHERE section_id = '".$this->sectionId."'";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_section_setting($opt=""){
        if($opt=="byId"){
            $sql = "SELECT * FROM _section_setting WHERE section_id = '".$this->sectionId."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
    }

    function insert_section_setting(){
        $sql = "INSERT INTO _section_setting VALUES('','".$this->sectionId."','1','0','','','','0','0')";
        $query = $this->db->query($sql);
        $result = $this->db->insert_id();
        return $result;
    }

    function update_section_setting($opt="",$field="",$value=""){
        if($opt=="perField"){
            $sql = "UPDATE _section_setting SET ".$field."='".$value."' WHERE section_id = '".$this->sectionId."'";
        }

        $this->db->query($sql);
        $result = $this->db->affected_rows();
        return $result;
    }



    /*
    function select_privileges($opt=""){
        if($opt=="section_allow"){
            $sql = "SELECT * FROM _privileges WHERE panel_level_id = ".$this->panelLevelId." AND section_id =
                        (SELECT section_id FROM _section WHERE section_alias_back = '".$this->page."') ";
            $data = $this->doQuery($sql);
            return $data[0];
        }
    }*/

}