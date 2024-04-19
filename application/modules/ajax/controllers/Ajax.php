<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Content_model content_model
 * @property Function_api function_api
 * @property CI_Session session
 * @property Member_model member_model
 * @property Media_model media_model
 * @property CI_Input input
 * @property ForumGroup_model ForumGroup_model
 */
class Ajax extends MX_Controller {
    protected $member_id, $mlevel_id, $group_id, $bidang;
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
		$this->load->model(['member_model', 'content_model', 'media_model', 'group_model', 'ForumGroup_model']);

        $this->member_id = $this->session->userdata('member_id');
        $this->mlevel_id = $this->session->userdata('member_level');
        $this->group_id = $this->session->userdata('group_id');
        $this->bidang = $this->session->userdata('member_bidang');
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

	function post_like(){
        $content_id = $this->input->post('content_id');
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
        echo json_encode($res);
    }


    function toggle_bookmark($content_id){
        $inBookmark = $this->member_model->in_bookmark($this->member_id,$content_id);
        if ($inBookmark){
            $res = $this->member_model->delete_bookmark($this->member_id, $content_id);
            if($res){
                $msg = 'Berhasil menghapus dari bookmark';
                $is_bookmarked = false;
            }
        } else {
            $recData['memberId'] = $this->member_id;
            $recData['contentId'] = $content_id;
            $res = $this->member_model->insert_bookmark($recData);
            if($res){
                $msg = 'Berhasil menambahkan ke bookmark';
                $is_bookmarked = true;
            }
        }
        $res = array('status'=>$res,'msg'=>$msg, 'is_bookmarked'=>$is_bookmarked);
        exit(json_encode($res));
    }

    function post_comment(){
        $content_id = $this->input->post('content_id');
        $comment_text = $this->input->post("comment_text");
        if (!$content_id) show_404();
        $this->content_model->recData['contentId'] = $content_id;
        $this->content_model->recData['memberId'] = $this->member_id;
        $this->content_model->recData['commentType'] = 'comment';
        $this->content_model->recData['commentStatus'] = '1';
        $this->content_model->recData['commentText'] = $comment_text;
        $res = $this->content_model->insert_content_comment($this->content_model->recData);
        echo json_encode($res);
    }

    function get_comment(){
	    $content_id = $this->input->get('content_id');
	    $page       = $this->input->get('page');
        $limit      = 5;
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit+1;
        $this->content_model->recData['contentId'] = $content_id;
        $res_comments   = $this->content_model->select_content_comment("commentByContentId");
        $comments       = [];
        $c_count        = count($res_comments);
        $n = 0;
        foreach ($res_comments as $c){
            $n++;
            $dc = [
                'comment_id'    => $c['comment_id'],
                'comment_text'  => $c['comment_text'],
                'comment_create_dat'    => $c['comment_create_date'],
                'member_id'     => $c['user_id'],
                'member_name'   => $c['member_name'],
            ];
            if($c['member_image']!="" && strpos($c['member_image'],'member_')){
                $dc['member_image'] = $c['member_image'];
            } else {
                $dc['member_image'] = base_url('assets/img/avatar.png');
            }
            $dc['comment_time']  = $this->function_api->waktu_lalu($c['comment_create_date']);
            array_push($comments, $dc);
            if ($n == $limit) break;
        }
        $data   = [
            'comments'  => $comments,
            'next_page' => $c_count > $limit?$page+1:0,
            'prev_page' => $page>1?$page-1:0
        ];
        echo json_encode($data);
    }

    function get_forum_comment(){
        $forum_id = $this->input->get('forum_id');
        $page       = $this->input->get('page');
        $limit      = 5;

        $this->ForumGroup_model->recData['forumId'] = $forum_id;
        $this->ForumGroup_model->recData['groupId'] = $this->group_id;
        $this->ForumGroup_model->beginRec = $limit*$page-$limit;
        $this->ForumGroup_model->endRec = $limit+1;
        $res_comments   = $this->ForumGroup_model->select_forum_chat("all");
        $comments       = [];
        $c_count        = count($res_comments);
        $n = 0;
        foreach ($res_comments as $c){
            $n++;
            $dc = [
                'fc_id'    => $c['fc_id'],
                'fc_text'  => $c['fc_desc'],
                'fc_create_date'=> $c['fc_create_date'],
                'member_id'     => $c['user_id'],
                'member_name'   => $c['member_name'],
            ];
            if($c['member_image']!="" && strpos($c['member_image'],'member_')){
                $dc['member_image'] = $c['member_image'];
            } else {
                $dc['member_image'] = base_url('assets/img/avatar.png');
            }
            $dc['comment_time']  = $this->function_api->waktu_lalu($c['fc_create_date']);
            array_push($comments, $dc);
            if ($n == $limit) break;
        }
        $data   = [
            'comments'  => $comments,
            'next_page' => $c_count > $limit?$page+1:0,
            'prev_page' => $page>1?$page-1:0
        ];
        echo json_encode($data);
    }

    function post_forum_comment(){
        $forum_id = $this->input->post('forum_id');
        $comment_text = $this->input->post("comment_text");
        if (!$forum_id) return false;
        $recData['forumId'] = $forum_id;
        $recData['userId'] = '0';
        $recData['memberId'] = $this->member_id;
        $recData['groupId'] = $this->group_id;
        $recData['fcDesc'] = $comment_text;
        $recData['fcImage'] = '';
        $recData['fcStatus'] = 'active';
        $res = $this->ForumGroup_model->insert_forum_chat($recData);
        echo json_encode($res);
    }
}
