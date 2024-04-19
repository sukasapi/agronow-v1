<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getContent')){
    function getContent($content_id,$section_id){
        $ci = &get_instance();
        $ci->load->model('content_model');

        $get_content = $ci->content_model->get($content_id,$section_id);
        if ($get_content==FALSE){
            redirect(404);
        }else{
            return $get_content;
        }
    }
}


if ( !function_exists('getBidang')){
    function getBidang(){
        $data = array(
            'Tanaman'           => 'Tanaman',
            'Teknik/Pengolahan' => 'Teknik/Pengolahan',
            'Akuntansi/Keuangan'=> 'Akuntansi/Keuangan',
            'SDM dan UMUM'      => 'SDM dan UMUM',
        );
        return $data;
    }
}

if ( !function_exists('getSectionPage')){
    function getSectionPage($section_id){
        $data = array(
            '1' =>	'Section',
            '2' =>	'Setting',
            '3'	=> 'User',
            '4' =>	'Category',
            '5'	=> 'Media',
            '6'	=> 'Content',
            '7'	=> 'Pages',
            '8'	=> 'Contact',
            '9'	=> 'Member',
            '10' => 'Notif',
            '11' =>	'Report',
            '12' =>	'whatsnew/news',
            '13' =>	'whatsnew/article',
            '14' => 'Knowledge',
            '16' => 'Group',
            '17' =>	'Berita PTPN',
            '18' => 'E-learning',
            '19' => 'Forum',
            '20' => 'Quotes',
            '21' => 'Ads',
            '22' => 'whatsnew/announcement',
            '23' => 'Berita RNI',
            '24' => 'Content Group',
            '25' => 'Forum Group',
            '26' => 'Pelatihan',
            '27' => 'QRContent',
            '28' => 'Reading Room',
            '29' => 'Learning Room',
            '30' => 'Class Room',
            '31' => 'Knowledge Sharing',
            '32' => 'CR',
            '33' => 'Culture',
            '34' => 'whatsnew/ceo_note',
            '35' => 'Digital Library',
            '36' => 'Digital SOP',
            '37' => 'Expert',
            '38' => 'Kamus',
            '39' => 'Survey',
            '40' => 'Popup',
            '41' =>	'Inbox',
            '42' =>	'whatsnew/bod_share'
        );
        return $data[$section_id];
    }
}

function reformatText4Js($a) {
	if (is_null($a)) return 'null';
	if ($a === false) return 'false';
	if ($a === true) return 'true';
	if (is_scalar($a))
	{
	  if (is_float($a))
	  {
		// Always use "." for floats.
		return floatval(str_replace(",", ".", strval($a)));
	  }

	  if (is_string($a))
	  {
		static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"',"'"), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"', "\'"));
		return str_replace($jsonReplaces[0], $jsonReplaces[1], $a);
	  }
	  else
		return $a;
	}
}