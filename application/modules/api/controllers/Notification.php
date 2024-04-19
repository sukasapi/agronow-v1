<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 16/03/21
 * Time: 23:04
 */

use chriskacerguis\RestServer\RestController;

class Notification extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'expert_model', 'member_model']);
    }

    function test_get(){
        $this->load->library('fcm');
        $tData = ['memberId'=>$this->session->userdata('member_id')];
        $dtoken = $this->member_model->select_member_device_token('byMemberId', $tData, 'Y');
        $tokens = [];
        foreach ($dtoken as $t){
            array_push($tokens, $t['device_token']);
        }
        $this->fcm->setTitle('Testing');
        $desc = $this->session->userdata('member_name').': Dari Agronow';
        $this->fcm->setBody($desc);
//        $payload = array(
//            'page' => site_url('learning/expert_directory/chat/').$expert_id
//        );
//        $this->fcm->setData($payload);
        $a = $this->fcm->sendMultiple($tokens);
        var_dump($a);
    }

}