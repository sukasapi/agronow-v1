<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 01/12/21
 * Time: 10:51
 */

class Default_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db->query("SET time_zone='".DB_TIME_ZONE."'");
    }

}