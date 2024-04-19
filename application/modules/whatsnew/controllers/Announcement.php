<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Content_model content_model
 */
class Announcement extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['member_model', 'ads_model', 'content_model', 'media_model', 'survey_model']);
        
        $this->section_id = 22;
        $this->member_id = $this->session->userdata('member_id');
        $this->content_model->recData['sectionId'] = $this->section_id;
        $this->mlevel_id = $this->session->userdata('member_level');
        $this->group_id = $this->session->userdata('group_id');
        $this->bidang = $this->session->userdata('member_bidang');
    }

	function index($page=1){
        $page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $this->content_model->recData['sectionId'] = $this->section_id;
        $dataContent = $this->content_model->select_content("publish","", "", $this->mlevel_id, $this->bidang);
        $data = [];
        $result = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $data['id'] 	= $dataContent[$i]['content_id'];
                $data['title'] 	= $dataContent[$i]['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                $data['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $data['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"datetime");
                $data['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $data['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $data['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($result,$data);
            }
        }


        $this->data['title'] = 'Announcement';
        $this->data['page'] = $page;
        $this->page = 'announcement';
        $this->menu = 'whatsnew';
        $this->data['data'] = $result;
        $this->generate_layout();
    }

    function detail($content_id=NULL){
        $this->content_model->recData['contentId'] = $content_id;
        $content = $this->content_model->select_content("byId");
        if (!$content){
            show_404();
        }
        if ($content['section_id']!=$this->section_id){
            show_404();
        }

        $data_hits = [
            'contentId' => $content_id,
            'memberId'  => $this->member_id,
            'contentHitsChannel'    => 'android'
        ];
        $this->content_model->insert_content_hits($data_hits);
        $this->content_model->update_content('hits', $data_hits);

        $content['image']           = $this->media_model->get_primary_image($content['section_id'],$content['content_id']);
        $count_like                 = $this->content_model->select_content_comment("countLikeByContentId");
        $content['like_count']      = $this->function_api->get_size_number($count_like);
        $count_comment              = $this->content_model->select_content_comment("countCommentByContentId");
        $content['comment_count']   = $this->function_api->get_size_number($count_comment);
        $content['is_liked']        = $this->content_model->is_member_like($content_id, $this->session->userdata('member_id'));
        $content['is_bookmarked']   = $this->member_model->in_bookmark($this->member_id, $content_id);
        $this->data['content']  = $content;
        $this->data['title'] = 'Announcement';
        $this->page = 'announcement_detail';
        $this->menu = 'whatsnew';

        $dataContent = $this->content_model->select_content("publish","",5);
        $tmp = [];
        $info = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $tmp['id'] 	= $dataContent[$i]['content_id'];
                $tmp['title'] 	= $dataContent[$i]['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->section_id,$dataContent[$i]['content_id']);
                $tmp['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"datetime");
                $tmp['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($info,$tmp);
            }
        }
        $this->data['info'] = $info;
        $this->customjs = array('content','sharelink','comments', 'bookmark');
        $this->generate_layout();
	}

    function search($keyword='',$page=1){
        if (!$keyword){
            $keyword = $this->input->post('keyword');
        }
        $page = $page>=1?$page:1;
        $limit = 10;
        $this->content_model->beginRec = ($page - 1) * $limit;
        $this->content_model->endRec = $limit;

        $groupId = $this->member_model->get_group_byid($this->member_id);

        $isLogin = $this->member_id?1:0;
        $sectionName = "Pengumuman";

        $dataContent = $this->content_model->search_content_new($keyword,"",$sectionName, $isLogin, "",$groupId);
        $countContent = $this->content_model->count_search_content($keyword,"",$sectionName,$isLogin,"",$groupId);
        if($countContent==0){
            $result = [];
        }
        else{
            $countPage = ceil($countContent/$limit);
            $result['current_page'] = $page;
            $result['count_page'] = $countPage;
            $result['data'] = array();
            $data = [];
            $result = [];
            for($i=0;$i<count($dataContent);$i++){
                $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
                if(isset($dataContent[$i])){
                    $data['id'] 	= $dataContent[$i]['content_id'];
                    $data['title'] 	= $dataContent[$i]['content_name'];
                    $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                    $data['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : "";
                    $data['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"datetime");
                    $data['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                    $data['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                    $data['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                    array_push($result,$data);
                }
            }
        }
        $this->data['title'] = 'Announcement';
        $this->data['page'] = $page;
        $this->page = 'announcement';
        $this->menu = 'whatsnew';
        $this->data['data'] = $result;

        $this->data['keyword'] = $keyword;
        $this->generate_layout();
    }

    function post_comment($content_id=NULL){
        $member_id = $this->session->userdata('member_id');
        $comment_text = $this->input->post("comment_text");
        if (!$content_id) show_404();
        $referred_from = $this->input->post('current_url');
        if (!$comment_text){
            redirect($referred_from, 'refresh');
        }
        $this->content_model->recData['contentId'] = $content_id;
        $this->content_model->recData['memberId'] = $member_id;
        $this->content_model->recData['commentType'] = 'comment';
        $this->content_model->recData['commentStatus'] = '1';
        $this->content_model->recData['commentText'] = $comment_text;
        $this->content_model->insert_content_comment($this->content_model->recData);
        redirect($referred_from, 'refresh');
    }

    function toggle_bookmark($content_id){
        $inBookmark = $this->member_model->in_bookmark($this->member_id,$content_id);
        if ($inBookmark){
            $res = $this->member_model->delete_bookmark($this->member_id, $content_id);
            if($res){
                $msg = 'Berhasil menghapus dari bookmark';
            }
        } else {
            $recData['memberId'] = $this->member_id;
            $recData['contentId'] = $content_id;
            $res = $this->member_model->insert_bookmark($recData);
            if($res){
                $msg = 'Berhasil menambahkan ke bookmark';
            }
        }
        $res = array('status'=>$res,'msg'=>$msg);
        exit(json_encode($res));
    }
}
