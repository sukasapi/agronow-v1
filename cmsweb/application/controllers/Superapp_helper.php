<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Superapp_helper extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'group_model',
        ));
		
		$this->section_id = 44;
    }
	
	function ajax_search_manpro(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
		$data_response = array();
		
		$get = $this->input->get();
        $keyword = isset($get['q'])?$get['q']:'';
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => URL_SUPERAPP_PROJECT,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'app' => 'agronow',
				'key' => 'Ca5L7M8ePpHD',
				'keyword' => $keyword
			)
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		
		$arrD = json_decode($resp,true);
		if($arrD['status']=="1") {
			$arrHasil = json_decode($arrD['data'],true);
			$i = 0;
			foreach($arrHasil as $k => $v) {
				$data_response['results'][$i]['id']    = $v['id'];
				$data_response['results'][$i]['text']  = '['.$v['kode'].'] '.$v['nama'];
				$data_response['results'][$i]['kode']  = $v['kode'];
				$data_response['results'][$i]['nama']  = $v['nama'];
				$data_response['results'][$i]['nama_produk']  = $v['nama_produk'];
				$i++;
			}
			
			$response_json = json_encode($data_response);
		} else {
			$response_json = NULL;
		}
		
        echo $response_json;
    }
    
    function ajax_post_dataclassroom(){
        
    }
}