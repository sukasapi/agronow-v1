<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Inbox_model inbox_model
 * @property CI_Session session
 * @property Content_model content_model
 * @property Function_api function_api
 * @property Expert_model expert_model
 */
class notification extends MX_Controller {

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['inbox_model','content_model', 'media_model', 'expert_model']);
        $this->member_id = $this->session->userdata('member_id');
        $this->mlevel_id = $this->session->userdata('member_level');
        $this->group_id = $this->session->userdata('group_id');
        $this->bidang = $this->session->userdata('member_bidang');
    }

    function index(){
        $recData['memberId'] = $this->member_id;
        $inbox_count    = $this->inbox_model->select_inbox("countUnread",$recData);
        $this->content_model->recData['memberId'] = $this->member_id;
        $this->content_model->recData['sectionId'] = [22,13,42,34,12];
        $whatsnew_count  = $this->content_model->select_content("countUnread", "", "", $this->mlevel_id, $this->bidang);
        $this->content_model->recData['sectionId'] = 35;
        $digital_count  = $this->content_model->select_content("countUnread", "", "", $this->mlevel_id, $this->bidang);
        $this->data['data'] = [
            'inbox_count'       => $inbox_count,
            'whatsnew_count'    => $whatsnew_count,
            'learning_count'    => $digital_count,
        ];

        // expert directory count
        $this->expert_model->recData['memberId'] = $this->member_id;
        $e_m = $this->expert_model->select_expert_member("byMemberId");
        if ($e_m){
            $recData = ['emId'=>$e_m['em_id']];
            $latest_messages = $this->expert_model->get_latest_chat_another("expertMember", $recData, 10, 0);
        } else {
            $recData = ['memberId'=>$this->member_id];
            $latest_messages = $this->expert_model->get_latest_chat_another("member", $recData, 10, 0);
        }
        $total_unread = 0;
        foreach ($latest_messages as $lm){
            // set variable
            $this->expert_model->recData['expertId'] = $lm['expert_id'];
            $this->expert_model->recData['memberId'] = $this->member_id;
            // dapatkan id chat terakhir dibaca
            $latest_read_id = $this->expert_model->get_latest_read_chat_id();
            // dapatkan jumlah pesan belum dibaca
            $unread = $this->expert_model->get_count_unread_chat($latest_read_id);
            $total_unread += $unread;
        }
        $this->data['data']['expert_directory_count'] = $total_unread;
        // end
        $this->data['title'] = 'Notification';
        $this->page = 'notification';
        $this->generate_layout();
    }

    function whatsnew($page=1){
        $limit      = 10;
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $this->content_model->recData['memberId'] = $this->member_id;
        $this->content_model->recData['sectionId'] = [22,13,42,34,12];
        $whatsnew = $this->content_model->select_content("unread", "", "", $this->mlevel_id, $this->bidang);
        $w_count  = $this->content_model->select_content("countUnread", "", "", $this->mlevel_id, $this->bidang);
        $content = [];
        foreach ($whatsnew as $w){
            $tmp['id']              = $w['content_id'];
            $tmp['title']           = $w['content_name'];
            $primaryImage 	= $this->media_model->get_primary_image($w['section_id'],$w['content_id']);
            $tmp['image']           = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
            $tmp['date']            = $this->function_api->date_indo($w['content_publish_date'],"dd FF YYYY");
            $tmp['viewed']          = $this->function_api->number($w['content_hits']);
            $tmp['like_count']      = $this->content_model->select_content_comment("countLikeByContentId");
            $tmp['comment_count']   = $this->content_model->select_content_comment("countCommentByContentId");
            $tmp['detail_url']      = site_url(getSectionPage($w['section_id']).'/detail/'.$w['content_id']);
            array_push($content,$tmp);
        }
        $data   = [
            'content'   => $content,
            'next_page' => $w_count > $limit*$page ? $page+1:0,
            'prev_page' => $page > 1 ? $page-1:0
        ];
        $this->data['data']    = $data;
        $this->data['title'] = 'Notification';
        $this->page = 'whatsnew';
        $this->generate_layout();
    }

    function expert_directory(){
        // check is member part of expert member
        $this->expert_model->recData['memberId'] = $this->member_id;
        $e_m = $this->expert_model->select_expert_member("byMemberId");
        if ($e_m){
            $recData = ['emId'=>$e_m['em_id']];
            $latest_messages = $this->expert_model->get_latest_chat_another("expertMember", $recData, 10, 0);
        } else {
            $recData = ['memberId'=>$this->member_id];
            $latest_messages = $this->expert_model->get_latest_chat_another("member", $recData, 10, 0);
        }
        $notifications = [];
        $tmp_unread = [];
        foreach ($latest_messages as $key => $lm){
            // set variable
            $this->expert_model->recData['expertId'] = $lm['expert_id'];
            $this->expert_model->recData['memberId'] = $this->member_id;
            // dapatkan id chat terakhir dibaca
            $latest_read_id = $this->expert_model->get_latest_read_chat_id();
            // dapatkan jumlah pesan belum dibaca
            if ($e_m){
                $unread = $this->expert_model->get_count_unread_chat($latest_read_id, true);
            } else {
                $unread = $this->expert_model->get_count_unread_chat($latest_read_id);
            }
            $lm['unread'] = $unread;
            $notifications[] = $lm;
            $tmp_unread[$key] = $lm['unread'];
        }

        // sort by highest unread
        array_multisort($tmp_unread, SORT_DESC, $notifications);

        $this->data['data'] = [
            'notifications'       => $notifications,
        ];
        $this->data['title'] = 'Notification';
        $this->page = 'expert_directory';
        $this->menu = 'learning';
        $this->generate_layout();
    }

    function ajax_notification_count(){
        $recData['memberId'] = $this->member_id;
        $inbox_count    = $this->inbox_model->select_inbox("countUnread",$recData);
        $this->content_model->recData['memberId'] = $this->member_id;
        $this->content_model->recData['sectionId'] = [22,13,42,34,12];
        $whatsnew_count  = $this->content_model->select_content("countUnread", "", "", $this->mlevel_id, $this->bidang);
        $this->content_model->recData['sectionId'] = 35;
        $digital_count  = $this->content_model->select_content("countUnread", "", "", $this->mlevel_id, $this->bidang);
        $total = $inbox_count+$whatsnew_count+$digital_count;
        $data = [
            'inbox_count'       => $inbox_count,
            'whatsnew_count'    => $whatsnew_count,
            'learning_count'    => $digital_count,
            'total'             => $total
        ];
        echo json_encode($data);
    }

}
