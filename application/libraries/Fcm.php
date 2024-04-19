<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 17/11/20
 * Time: 11:43
 */

class Fcm
{

    /**
     * @var string $title Title
     */
    private $title;
    /**
     * @var string $body Body
     */
    private $body;
    /**
     * @var string $image Image
     */
    private $image;
    /**
     * @var array $data Data payload
     */
    private $data;

    /**
     * Function to set the title
     *
     * @param string    $title  The title of the push message
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Function to set the body
     *
     * @param string    $body    Body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Function to set the image (optional)
     *
     * @param string    $imageUrl    URI string of image
     */
    public function setImage($imageUrl)
    {
        $this->image = $imageUrl;
    }

    /**
     * Function to set the custom payload (optional)
     *
     * eg:
     *      $data = array('user' => 'user1');
     *
     * @param array    $data    Custom data array
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    /**
     * Function to send notification to a single device
     *
     * @param   string $to registration id of device (device token)
     * @return string array of notification data and to address
     */
    public function send($to)
    {
        $json = $this->getPush();
        $fields = array(
            'to' => $to,
            'notification' => $json,
        );
        if ($this->data){
            $fields['data'] = $this->data;
        }
        return $this->sendPushNotification($fields);
    }

    /**
     * Function to send notification to a topic by topic name
     *
     * @param   string $to topic
     * @return string
     */
    public function sendToTopic($to)
    {
        $json = $this->getPush();
        $fields = array(
            'to' => '/topics/' . $to,
            'notification' => $json,
        );
        if ($this->data){
            $fields['data'] = $this->data;
        }
        return $this->sendPushNotification($fields);
    }

    /**
     * Function to send notification to multiple users by firebase registration ids
     *
     * @param $registration_ids
     * @return string array of notification data and to addresses
     */
    public function sendMultiple($registration_ids)
    {
        $json = $this->getPush();
        $fields = array(
            'registration_ids' => $registration_ids,
            'notification' => $json,
        );
        if ($this->data){
            $fields['data'] = $this->data;
        }
        return $this->sendPushNotification($fields);
    }

    /**
     * Generating the push message array
     *
     * @return array  array of the push notification data to be send
     */
    private function getPush()
    {
        $res = array();
        $res['title'] = $this->title;
        $res['body'] = $this->body;
        $res['image'] = $this->image;
        $res['event_time'] = date('Y-m-d G:i:s');
        return $res;
    }

    /**
     * Function makes curl request to firebase servers
     *
     * @param   array   $fields    array of registration ids of devices (device tokens)
     *
     * @return  string   returns result from FCM server as json
     */
    private function sendPushNotification($fields)
    {

        $CI = &get_instance();
        $CI->load->config('fcmconfig'); //loading of config file

        // Set POST variables
        $url = $CI->config->item('fcm_url');

        $headers = array(
            'Authorization: key=' . $CI->config->item('key'),
            'Content-Type: application/json',
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }

}