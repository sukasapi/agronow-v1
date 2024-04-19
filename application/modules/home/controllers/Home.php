<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Function_api function_api
 * @property Content_model content_model
 * @property Media_model media_model
 * @property Member_model member_model
 * @property Category_model category_model
 * @property Content_type_model content_type_model
 * @property Classroom_model classroom_model
 * @property Group_model group_model
 * @property CI_Session session
 * @property Kompetensi_model kompetensi_model
 * @property Ads_model ads_model
 */
class Home extends MX_Controller {
    function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model([
            'content_model',
            'scrapper_model',
            'media_model',
            'member_model',
            'content_type_model',
            'category_model',
            'classroom_model',
            'culture_model',
            'group_model',
            'jabatan_model',
            'kompetensi_model',
            'ads_model',
			'learning_wallet_model'
        ]);
        $this->load->library(['function_api']);

        $this->member_id = $this->session->userdata('member_id');
        $this->member_nip = $this->session->userdata('member_nip');
        fetchAndUpdateMemberAghris();
    }

    function index(){
		/* if(isset($_COOKIE)) { // cookie nip_sap hanya bisa di-create dari app aghris
			// cek dl groupnya, ada kode aghrisnya apa ga,,
			if(!empty($_COOKIE['nik_sap']) && empty($this->session->userdata('aghris_company_code'))) {
				$ui = 
					'<html lang="en">
					  <head>
						<meta charset="UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
					  </head>
					  <body>
						Kode entitas karyawan dg NIK '.$this->member_nip.' tidak ditemukan. Silahkan login melalui '.base_url().' menggunakan browser.
					  </body>
					</html>';
				echo $ui;
				exit;
			}
		} */
		
		// cek dl kategori kliennya
		$kategori_klien = $this->session->userdata('kategori_klien');
		if($kategori_klien=="classroom_only") {
			redirect('learning/class_room');
			exit;
		}
		
		// update session level karyawan
		$this->member_model->recData['memberId'] = $this->member_id;
		$arrLK = $this->member_model->select_member("levelKaryawanById");
		$this->data['nama_level_karyawan'] = $arrLK['nama_level_karyawan'];
		$this->session->set_userdata('id_level_karyawan', $arrLK['id_level_karyawan']);
		
        $b = time();
        $hour = date("G",$b);
        if ($hour>=0 && $hour<=11){     $salam =  "Selamat Pagi,";}
        elseif ($hour >=12 && $hour<=14){ $salam =  "Selamat Siang,"; }
        elseif ($hour >=15 && $hour<=18){ $salam =  "Selamat Sore,"; }
        else{ $salam =  "Selamat Malam,";}

        // begin of poin //
        // daily login
        $this->data['show_reward'] = false;
        $recData['memberId'] = $this->member_id;
        $recData['mpSection'] = 'Daily';
        $recData['interval'] = 'daily';
        $daily_poin = $this->member_model->select_member_poin('bySection', $recData, 1);
        if (!$daily_poin){
            $poin_setting = $this->member_model->select_member_poin_setting();
            if ($poin_setting){
                $recData['mpContentId'] = '';
                $recData['mpName']      = 'Daily Login';
                $recData['mpPoin']      = $poin_setting[0]['mps_daily'];
                $this->member_model->insert_member_poin($recData);

                $recData['interval'] = 'yearly';
                $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
                $this->member_model->recData['memberId'] = $this->member_id;
                $this->member_model->update_member('byField','','member_poin',$total_poin);

                // show reward pop-up
                $this->data['show_reward'] = true;
                $this->data['reward'] = [
                    'poin' => $poin_setting[0]['mps_daily'],
                    'cause' => 'Daily Login'
                ];
            }
        };
        // monthly login
        $recData['mpSection'] = 'Monthly';
        $recData['interval'] = 'monthly';
        $monthly_poin = $this->member_model->select_member_poin('bySection', $recData, 1);
        if (!$monthly_poin){
            $recData['interval']= 'yearly';
            $recData['type']    = 'IN';
            $saldo_in = $this->member_model->select_member_saldo('sumByMemberId', $recData);
            $recData['interval']= 'previousMonth';
            $recData['type']    = 'OUT';
            $saldo_out = $this->member_model->select_member_saldo('sumByMemberId', $recData);
            $saldo_avg = round($saldo_in/12);
            if ($saldo_avg > 0){
                $recData['percentage'] = round($saldo_out/$saldo_avg)*100;
            } else {
                $recData['percentage'] = 0;
            }
            $poin = $this->member_model->select_member_poin_setting_monthly('byPercentage', $recData);
            if ($poin > 0){
                $recData['mpContentId'] = '';
                $recData['mpName']      = 'Monthly Login';
                $recData['mpPoin']      = $poin;
                $this->member_model->insert_member_poin($recData);
            }

            $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
            $this->member_model->recData['memberId'] = $this->member_id;
            $this->member_model->update_member('byField','','member_poin',$total_poin);

            if ($poin > 0){
                // show reward pop-up
                $this->data['show_reward'] = true;
                $this->data['reward'] = [
                    'poin' => $poin,
                    'cause' => 'Monthly Login'
                ];
            }
        };
        // end of poin

        // Member
        $this->member_model->recData['memberId'] = $this->member_id;
        $dataMember = $this->member_model->select_member("byId");
        $bidang = $this->member_model->get_member_bidang($this->member_id);
        $mlevelId = $this->member_model->get_member_mlevelId($this->member_id);

        $recData['interval']    = 'yearly';
        $recData['mpSection']   = 'CR';
        // $cr_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mpSection']   = 'CC';
        // $cc_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mpSection']   = 'KS';
        // $ks_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mPoin']       = $dataMember['member_poin'];
        // $level_poin = $this->member_model->select_member_poin_level('byPoin', $recData);

        $this->group_model->recData['groupId'] = $dataMember['group_id'];
        $group = $this->group_model->select_group('byId');

        $recData['userId'] = $this->member_id;
        // $rank_global  = $this->member_model->select_rank("byUserId", $recData, 1);
        // $lb_global    = $this->member_model->select_rank("", $recData, 3);
        $recData['memberId'] = '';
        // $lb_this_month= $this->member_model->select_rank("thisMonth", $recData, 3);
        $recData['groupId'] = $dataMember['group_id'];
        // $lb_group     = $this->member_model->select_rank("byGroup", $recData, 3);

        $name = $this->_split_name($dataMember['member_name']);
        $this->data['data']['salam'] = $salam.' '.$name[0];
        $this->data['data']['member']=[
            'name'                      => $dataMember['member_name'],
            'nik'                       => $dataMember['member_nip'],
            'bidang'                    => $dataMember['member_desc'],
            'group'                     => $group['group_name'],
            'rank_global'               => $rank_global?$rank_global:'-',
            'total_point'               => $dataMember['member_poin'],
            'total_saldo'               => $dataMember['member_saldo'],
            'classroom_point'           => $cr_poin,
            'knowledge_point'           => $ks_poin,
            'corporate_culture_point'   => $cc_poin,
            'badge_level'               => $level_poin
        ];

        $this->data['data']['member']['member_image'] = validate_member_image($dataMember['member_image']);

        // Leaderboard
        $this->data['data']['leaderboard'] = [
            'all_time'      => $lb_global,
            'this_month'    => $lb_this_month,
            'group'         => $lb_group
        ];

        // News
        $this->content_model->recData['sectionId'] = 12;
        $result = $this->content_model->select_content("publish","",10,$mlevelId,$bidang);
        if(count($result)>0) {
            foreach($result as $k=>$r){
                $tmp['id'] = $result[$k]['content_id'];
                $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[$k]['content_id']);
                $tmp['image'] = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $title = $result[$k]['content_name'];
                $date = $this->function_api->date_indo($result[$k]['content_publish_date'],"dd FF YYYY");
                $tmp['title'] = $title;
                $tmp['date'] = $date;
                $tmp['detail_url'] = site_url('whatsnew/news/detail/'.$result[$k]['content_id']);
                $this->data['data']['news'][] = $tmp;
            }
        }
        // Article
        $this->content_model->recData['sectionId'] = 13;
        $result = $this->content_model->select_content("publish","",5,$mlevelId,$bidang);
        if(count($result)>0) {
            foreach($result as $k=>$r){
                $tmp['id'] = $result[$k]['content_id'];
                $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[$k]['content_id']);
                $tmp['image'] = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $title = $result[$k]['content_name'];
                $date = $this->function_api->date_indo($result[$k]['content_publish_date'],"dd FF YYYY");
                $tmp['title'] = $title;
                $tmp['date'] = $date;
                $tmp['detail_url'] = site_url('whatsnew/article/detail/'.$result[$k]['content_id']);
                $this->data['data']['article'][] = $tmp;
            }
        }
        // Ceo Note
        $this->content_model->recData['sectionId'] = 34;
        $result = $this->content_model->select_content("publish","",1,$mlevelId,$bidang);
        if ($result) {
            $this->data['data']['ceo_note']['id'] = $result[0]['content_id'];
            $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[0]['content_id']);
            $this->data['data']['ceo_note']['image'] = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
            $title = $result[0]['content_name'];
            $date = $this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['ceo_note']['title'] = $title;
            $this->data['data']['ceo_note']['date'] = $date;
            $this->data['data']['ceo_note']['detail_url'] = site_url('whatsnew/ceo_note/detail/'.$result[0]['content_id']);
        }
        // BOD Share
        $this->content_model->recData['sectionId'] = 42;
        $result = $this->content_model->select_content("publish","",1,$mlevelId,$bidang);
        if ($result) {
            $this->data['data']['bod_share']['id'] = $result[0]['content_id'];
            $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[0]['content_id']);
            $this->data['data']['bod_share']['image'] = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
            $title = $result[0]['content_name'];
            $date = $this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
            $this->data['data']['bod_share']['title'] = $title;
            $this->data['data']['bod_share']['date'] = $date;
            $this->data['data']['bod_share']['detail_url'] = site_url('whatsnew/bod_share/detail/'.$result[0]['content_id']);
        }
        // Announcement
        $this->content_model->recData['sectionId'] = 22;
        $result = $this->content_model->select_content("publish","",1,$mlevelId,$bidang);
        if ($result) {
            $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[0]['content_id']);
            $this->data['data']['announcement']['image']    = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
            $this->data['data']['announcement']['title']    = $result[0]['content_name'];
            $tmp['isi']             = str_replace("&quot;",'"',$result[0]['content_desc']);
            $this->data['data']['announcement']['isi']      = trim(preg_replace('/ +/', ' ', urldecode(html_entity_decode(strip_tags($tmp['isi'])))));
            $this->data['data']['announcement']['id']       = $result[0]['content_id'];
        }

        // Knowledge Sharing
//        $this->content_model->recData['sectionId'] = 31;
//        $result = $this->content_model->select_content("publish","",1,$mlevelId,$bidang);
//        if ($result) {
//            $this->data['data']['knowledge_sharing']['id']          = $result[0]['content_id'];
//            $this->data['data']['knowledge_sharing']['title']       = $result[0]['content_name'];
//            $this->data['data']['knowledge_sharing']['isi']         = $result[0]['content_desc'];
//            $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$result[0]['content_id']);
//            $this->data['data']['knowledge_sharing']['image']       = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
//            $date = $this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
//            $this->data['data']['knowledge_sharing']['date']        = $date;
//            $this->data['data']['knowledge_sharing']['time']        = date('H:i', strtotime($result[0]['content_publish_date']));
//            $this->data['data']['knowledge_sharing']['author']      = $result[0]['content_author'];
//            $this->data['data']['knowledge_sharing']['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
//            $this->data['data']['knowledge_sharing']['detail_url']  = site_url('learning/knowledge_sharing/detail/'.$result[0]['content_id']);
//        }

        // Digital Library
        $this->content_model->recData['sectionId'] = 35;
        $this->data['data']['digital_library'] = array();
        foreach ($this->content_type_model->get_all() as $i => $ct) {
            $content_type_id = $ct['content_type_id'];
            $this->content_model->recData['contentTypeID'] = $content_type_id;
            $result = $this->content_model->select_content("publish","",1,$mlevelId,$bidang);
            $this->data['data']['digital_library'][$content_type_id] = [];
            if ($result){
                $this->data['data']['digital_library'][$content_type_id]                = $result[0];
                $this->data['data']['digital_library'][$content_type_id]['cat_name']    = $this->category_model->get_cat_name($result[0]['cat_id']);
                if($content_type_id != 4){
                    $image = $this->media_model->get_primary_image(35, $result[0]['content_id']);
                    $thumbnail = $image?URL_MEDIA_IMAGE.$image['media_value']:'';
                    $this->data['data']['digital_library'][$content_type_id]['image']   = $image;
                }else{
                    $video = $this->media_model->get_media('video', 35, $result[0]['content_id']);
                    $video = $video?$video[0]:null;
                    $this->data['data']['digital_library'][$content_type_id]['image']   = $video;
                    $thumbnail = ($video?$this->get_image_from_youtube_embed_link($video['media_value']):'');
                    if (!$thumbnail) $thumbnail=PATH_ASSETS.'img/sample/photo/d6.jpg';
                }
                $this->data['data']['digital_library'][$content_type_id]['thumbnail']   = $thumbnail;
                $this->data['data']['digital_library'][$content_type_id]['detail_url']  = site_url('learning/digital_library/detail/'.$result[0]['content_alias']);
            }
        }

        // Commodity
        $result = $this->scrapper_model->get_latest_data('commodity');
        if ($result){
            $i=0;
            $com = '';

            foreach ($result as $key=>$val){
                $i++;
                $com .= $key.' - '.(isset($val[0])?$val[0]:'').' - '.(isset($val[1])?$val[1]:'').' - '.(isset($val[2])?$val[2]:'');
                if ($i>=10) break;
                $com .= ' | ';
            }
        }
        // Exchange rate
        $result = $this->scrapper_model->get_latest_data('datakurs');
        $er = $com = '';
        if ($result){
            $i=0;
            $er = '';
            foreach ($result as $key=>$val){
                $i++;
                $er .= $key.' - '.(isset($val['buy'])?$val['buy']:'').' - '.(isset($val['sell'])?$val['sell']:'').' - '.(isset($val['value'])?$val['value']:'');
                if ($i>=10) break;
                $er .= ' | ';
            }
        }
        $this->data['data']['cne'] = $er.' | '.$com;

        // Ads
        $this->data['ads'] = $this->ads_model->select_ads('active');
		
		// learning wallet
		$wallet_tahun = date("Y");
		$wallet_member_id = $this->session->userdata('member_id');
		$wallet_id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$wallet_group_id = $this->session->userdata('group_id');
		/*
		if($wallet_member_id=="8684") { // Tim Developer IT
			$wallet_member_id = '817';
			$wallet_id_level_karyawan = '3';
			$wallet_group_id = '15'; // holding
		}
		*/
		$saldo_awal = $this->learning_wallet_model->getSaldoAwal($wallet_tahun,$wallet_member_id,$wallet_group_id,$wallet_id_level_karyawan);
		$saldo_terpakai = $this->learning_wallet_model->getSaldoTerpakai($wallet_tahun,$wallet_member_id);
		$wallet_saldo = $this->learning_wallet_model->reformatHarga($saldo_awal-$saldo_terpakai);
		$this->data['wallet_tahun'] = $wallet_tahun;
		$this->data['wallet_saldo'] = $wallet_saldo;

        // get latest classroom home
        $this->data['classroom_list'] = $this->classroom_model->get_latest_classroom_home($this->member_id);
        $this->data['culture_list'] = $this->culture_model->get_latest_culture_home($this->member_id);
        $this->data['popup_list'] = $this->content_model->get_popup_list($this->member_id);

        // popup kompetensi harian
        $this->kompetensi_model->recData['memberId'] = $this->member_id;
        $komps = $this->kompetensi_model->select_kompetensi_member('daily');
        $ret = [];
        $today = date('Y-m-d');
        foreach ($komps as $kom){
            $kom_data = json_decode($kom['crm_step'], true);

            if (isset($kom_data['is_done_all']) && $kom_data['is_done_all'] == 1) continue;
            if (isset($kom_data['latest_work']) && $kom_data['latest_work'] >= $today) continue;

            $ret[] = [
                'head'  => 'Kompetensi Harian',
                'name'  => $kom['cr_name'],
                'url'   => site_url('learning/kompetensi/evaluasi?cr_id=').$kom['cr_id'],
                'image' => ''
            ];
        }
        $this->data['popup_kompetensi'] = $ret;

        $this->classroom_model->recData['memberId'] = $this->member_id;
        $this->data['classroom_offering'] = $this->classroom_model->select_classroom("listSell");

        $this->kompetensi_model->recData['memberId'] = $this->member_id;
        $this->data['competencies'] = $this->kompetensi_model->select_kompetensi("listByMemberId");
		
		$this->data['title'] = 'Home';
        $this->page = 'home';
        $this->menu = 'home';

        $this->customjs = array('reward', 'popup', 'header_notification', 'live_datetime', 'popup-kompetensi');

        $this->generate_layout();
    }

    private function _split_name($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }

    private function get_image_from_youtube_embed_link($link = ''){
        if(strpos($link, 'youtube') !== false && strpos($link, 'embed') !== false){
            $u = explode('embed', $link);
            if(isset($u[1])){
                $video_id = @str_replace('/', '', $u[1]);
                $link = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';
            }
        }
        return $link;
    }
}
