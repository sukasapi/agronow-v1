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


if ( !function_exists('getContentNoRedirect')){
    function getContentNoRedirect($content_id,$section_id){
        $ci = &get_instance();
        $ci->load->model('content_model');

        $get_content = $ci->content_model->get($content_id,$section_id);
        if ($get_content==FALSE){
            return FALSE;
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


if ( !function_exists('sendNotifByContent')){
    function sendNotifByContent($content_id,$section_id){
        $ci = &get_instance();
        $ci->load->model('content_model');
        $ci->load->model('member_model');
        $ci->load->library(['fcm']);

        $get_content = $ci->content_model->get($content_id,$section_id);
        if ($get_content==FALSE){
            return FALSE;
        }else{
            $content = $get_content;

            $member_ids = array();

            // Get Member by Group
            $group_id = $content['group_id'];
            if ($group_id=='all'){
                $member_all = $ci->member_model->get_all()['data'];
                foreach ($member_all as $v){
                    $member_ids[] = $v['member_id'];
                }
            }else{
                $group_ids = explode(',',$group_id);
                if ($group_ids){
                    foreach ($group_ids as $v){
                        $member_in_group = $ci->member_model->get_by_group($v);
                        if ($member_in_group){
                            foreach ($member_in_group as $j){
                                $member_ids[] = $j['member_id'];
                            }
                        }
                    }
                }
            }


            // Get Member by Mlevel
            $mlevel_id = $content['mlevel_id'];
            if ($mlevel_id=='all'){
                $member_all = $ci->member_model->get_all()['data'];
                foreach ($member_all as $v){
                    $member_ids[] = $v['member_id'];
                }
            }else{
                $mlevel_ids = explode(',',$mlevel_id);
                if ($mlevel_ids){
                    foreach ($mlevel_ids as $v){
                        $member_in_mlevel = $ci->member_model->get_by_mlevel($v);
                        if ($member_in_mlevel){
                            foreach ($member_in_mlevel as $j){
                                $member_ids[] = $j['member_id'];
                            }
                        }
                    }
                }
            }


            $member_ids_unique = array_unique($member_ids);

            if ($member_ids_unique){

                foreach ($member_ids_unique as $v){

                    // NOTIFIKASI
                    $recData    = ['memberId' => $v];
                    $dtoken     = $ci->member_model->select_member_device_token('byMemberId', $recData);
                    $tokens     = [];
                    foreach ($dtoken as $t){
                        array_push($tokens, $t['device_token']);
                    }
                    $token = $tokens;
                    if ($token){

                        $ci->fcm->setTitle($content['content_name']);
                        //$ci->fcm->setBody($content['content_desc']);
                        //$ci->fcm->setImage('');

                        $ci->fcm->sendMultiple($token);

                        //print_r($tokens);
                    }

                }

            }


        }
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

function url2image($teks) {
	// get all url
	preg_match_all('/(https?|ssh|ftp):\/\/[^\s"]+/', $teks, $url);
	
	// yg disupport hanya hcmdss aj
	// preg_match_all('/(https:\/\/app.hcmdss.com)\/[^\s"]+/', $teks, $url);
	
	$all_url = $url[0]; // Returns Array Of all Found URL's
	$one_url = $url[0][0]; // Gives the First URL in Array of URL's
	
	$dimg = '<img src="'.$one_url.'" class="img-fluid" alt="'.$one_url.'">';
	$pertanyaan = str_replace($one_url,$dimg,$teks);
	
	return $pertanyaan;
}