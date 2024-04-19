<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 13/11/20
 * Time: 16:23
 * @property Member_model member_model
 * @property Fcm fcm
 */

class Notify extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model([
            'member_model'
        ]);
        $this->load->library(['fcm']);

        //$this->member_id = $this->session->userdata('member_id');
        $this->member_id = '6005';
    }

    function topic(){
        $this->fcm->setTitle('Message Topic alerts');
        $this->fcm->setBody('Noif yang dikirim untuk subscriber topic alerts');
        $payload = array(
            'page' => 'https://agronowpwa.creacle.co.id/notification/whatsnew'
        );
        $this->fcm->setData($payload);
        $result = $this->fcm->sendToTopic('alerts');
        print_r($result);
    }

    public function push()
    {
        $token = 'cPTgroEbRQyem-RCoR63n0:APA91bGvcxWS8LW6AvO4W-RU_jsYyhMr0QZYY3s5bNU3degETMpLGZFmcVL3mjo-wn2KVNqLDv1B5NJze-GCy9BOyDNg7N1fCsJXI3C2IADqfipcaAOACGq0Om5AmwO6Ozpx3WxbQnX3'; // push token

//        $this->load->library('fcm');
        $this->fcm->setTitle('Notif dari CMS');
        $this->fcm->setBody('Ini adalah message yang dikirim dari CMS');

        $payload = array(
            'page' => 'https://creacle.co.id'
        );
        $this->fcm->setData($payload);

        /**
         * Send images in the notification
         */
        $this->fcm->setImage('https://www.bantennews.co.id/wp-content/uploads/2018/11/Screenshot_2018-11-09-23-45-18-937_com.android.chrome.jpg');

        $p = $this->fcm->send($token);

        print_r($p);
    }

    public function pushmulti()
    {
        $recData = ['memberId'=>$this->member_id];
        $dtoken = $this->member_model->select_member_device_token('byMemberId', $recData);
        $tokens = [];
        foreach ($dtoken as $t){
            array_push($tokens, $t['device_token']);
        }
        $token = $tokens;

        $this->fcm->setTitle('Notif Dari CMS');
        $this->fcm->setBody('Ini adalah notifikasi yang dikirim dari CMS');

        $this->fcm->setImage('https://firebase.google.com/downloads/brand-guidelines/PNG/logo-vertical.png');

        $result = $this->fcm->sendMultiple($token);
        print_r($result);
    }

}