<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 11/18/2019
 * Time: 11:35 AM
 * @property CI_DB_mysql_driver|CI_DB_query_builder db
 */

class Auth_model extends CI_Model
{
    var $token_table = '_api_token';

    function deactivate_token($token){
        $this->db->update($this->token_table, ['is_active'=>0], ['token'=>$token]);
    }

    function create_token($member_id) {
        $date = new DateTime();

        // ***** Generate Token *****
        $char = "bcdfghjkmnpqrstvzBCDFGHJKLMNPQRSTVWXZaeiouyAEIOUY!@#%";
        $token = '';
        for ($i = 0; $i < 47; $i++) $token .= $char[(rand() % strlen($char))];

        // ***** Insert into Database *****
        $exp = $date->getTimestamp()+60*60*24*30;
        $exp = date('Y-m-d H:i:s',$exp);
        $this->db->insert($this->token_table, ['token'=>$token, 'member_id'=>$member_id, 'expired_at'=>$exp]);
        $num_inserts = $this->db->affected_rows();
        return ($num_inserts > 0 ? $token : False);
    }

    function update_token($data, $token){
        if(!array_key_exists('updated_at', $data)){
            $data['updated_at'] = date("Y-m-d H:i:s");
        }
        $this->db->update($this->token_table, $data, array('token'=>$token));
        return $this->db->affected_rows();
    }

}