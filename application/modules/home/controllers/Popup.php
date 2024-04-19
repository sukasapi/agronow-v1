<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup extends MX_Controller {
    public $title = 'Popup';
    public $menu = 'home';
    private $section_id = 40;

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->member_id = $this->session->userdata('member_id');

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['content_model', 'media_model', 'member_model']);
    }

    public function index(){
        redirect(base_url('home'));
    }

    public function detail($content_id=NULL){
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
        $content['is_liked'] = $this->content_model->is_member_like($content_id, $this->session->userdata('member_id'));
        $content['is_bookmarked']   = $this->member_model->in_bookmark($this->member_id, $content_id);
        $this->data['content']  = $content;
        $this->page = 'popup_detail';

        $dataContent = $this->content_model->select_content("publish","",5);
        $tmp = [];
        $info = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $tmp['id']  = $dataContent[$i]['content_id'];
                $tmp['title']   = $dataContent[$i]['content_name'];
                $primaryImage   = $this->media_model->get_primary_image($this->section_id,$dataContent[$i]['content_id']);
                $tmp['image']   = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date']    = $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"dd FF YYYY");
                $tmp['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($info,$tmp);
            }
        }
        $this->data['info'] = $info;
        $this->customjs = array('content','sharelink','comments');
        $this->data['back_url'] = base_url('home');

        $this->generate_layout();
    }

    public function post_comment($content_id=NULL){
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

    public function add_like($content_id=NULL){
        $this->content_model->recData['contentId'] = $content_id;
        $content = $this->content_model->select_content('byId');
        if (!$content) show_404('Content not found');

        // begin of poin
        if ($content['section_id']==31) { // hanya untuk knowledge sharing
            $poin_setting = $this->member_model->select_member_poin_setting();
            $recData['memberId'] = $content['content_create_by'];
            $recData['mpSection'] = 'KS';
            $recData['mpContentId'] = $content_id;
            $recData['mpName'] = $content['content_name'] . ' (Like)';
            $recData['mpPoin'] = $poin_setting[0]['mps_ks_liked'];
            $this->member_model->insert_member_poin($recData);
            $total_poin = $this->member_model->select_member_poin('sumByMemberId', $recData);
            $this->member_model->recData['memberId'] = $content['content_create_by'];
            $this->member_model->update_member('byField', '', 'member_poin', $total_poin);
        }
        // end of poin

        $this->content_model->recData['sectionId'] = $content['section_id'];
        $this->content_model->recData['contentId'] = $content_id;
        $this->content_model->recData['memberId'] = $this->session->userdata('member_id');
        $this->content_model->recData['commentType'] = 'comment';
        $this->content_model->recData['commentStatus'] = '1';
        $this->content_model->recData['commentLike'] = 'like';
        $member_liked = $this->content_model->is_member_like($content_id, $this->session->userdata('member_id'));
        $res = 0;
        if (!$member_liked){
            $res = $this->content_model->insert_content_comment($this->content_model->recData);
        }
        return $res;
    }

    public function toggle_bookmark($content_id){
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