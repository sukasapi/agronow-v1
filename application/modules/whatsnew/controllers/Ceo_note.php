<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Function_api function_api
 * @property Content_model content_model
 * @property Media_model media_model
 */
class Ceo_note extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['member_model', 'ads_model', 'content_model', 'media_model', 'survey_model', 'category_model', 'group_model']);

        $this->section_id = 34;
        $this->member_id = $this->session->userdata('member_id');
        $this->content_model->recData['sectionId'] = $this->section_id;
        $this->mlevel_id = $this->session->userdata('member_level');
        $this->group_id = $this->session->userdata('group_id');
        $this->bidang = $this->session->userdata('member_bidang');
    }

	function index(){
        $this->member_model->recData['memberId'] = $this->member_id;
        $member = $this->member_model->select_member('byId');

        $this->data['title'] = 'CEO Note';
        $this->page = 'ceo_note';
        $this->menu = 'whatsnew';
        $this->customjs = array('ceo_note');

        $this->data['ceo_note_status'] = false;
        if(@$member['member_ceo'] == '1'){
            $this->data['ceo_note_status'] = true;
        }

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
        $this->data['title'] = 'CEO Note';
        $this->page = 'ceo_note_detail';
        $this->menu = 'whatsnew';
        $this->customjs = array('content','sharelink','comments','bookmark');
        $this->generate_layout();
	}

	function edit($content_id){
        $this->content_model->recData['contentId'] = $content_id;
        $content = $this->content_model->select_content("byId");
        if (!$content || $content['member_id'] != $this->member_id){
            show_404();
        }
        if ($content['section_id']!=$this->section_id){
            show_404();
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_name', 'Judul', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $content['image']           = $this->media_model->get_primary_image($content['section_id'],$content['content_id']);
            $this->data['content']  = $content;
            $this->data['url_return'] = site_url('whatsnew/ceo_note/detail/'.$content_id);
            $this->data['form_action'] = site_url('whatsnew/ceo_note/edit');
            $this->data['title'] = 'CEO Note';
            $this->page = 'ceo_note_edit';
            $this->menu = 'whatsnew';
            $this->customjs = array('ckeditor');
            $this->generate_layout();
        } else {
            $content_name = $this->input->post('content_name');
            $content_desc = $this->input->post('content_desc');
            $content_status = $this->input->post('content_status');

            //insert
            $recData['contentId']       = $content_id;
            $recData['sectionId']       = $content['section_id'];
            $recData['catId']           = $content['cat_id'];
            $recData['memberId']        = $content['member_id'];
            $recData['groupId']         = $content['group_id'];
            $recData['mlevelId']        = $content['mlevel_id'];
            $recData['contentName']     = $content_name;
            $recData['contentAlias']    = $this->function_api->generate_alias($content_name);
            $recData['contentDesc']     = $content_desc;
            $recData['contentTags']     = $content['content_tags'];
            $recData['contentHits']     = $content['content_hits'];
            $recData['contentSource']   = $content['content_source'];
            $recData['contentBidang']   = $content['content_bidang'];
            $recData['contentAuthor']   = $content['content_author'];
            $recData['contentPublishDate']  = $content['content_publish_date'];
            $recData['contentSeoTitle'] = $content_name;
            $recData['contentSeoKeyword']= $content_name;
            $recData['contentSeoDesc']  = $content_desc;
            $recData['contentStatus']   = $content_status;
            $recData['contentCreateBy'] = $content['content_create_by'];
            $recData['contentNotif']    = $content['content_notif'];
            if($content_status=="publish"){
                $recData['contentNotif']    = '1';
            }

            $this->content_model->update_content('',$recData);

            if(isset($_FILES["content_image1"]) && $_FILES["content_image1"]["name"]){
                $media           = $this->media_model->get_primary_image($content['section_id'],$content['content_id']);
                if ($media){
                    $this->media_model->mediaId = $media['media_id'];
                    $this->media_model->delete_media();
                }
                //image 1
                $this->media_model->dataId = $content_id;
                $this->media_model->sectionId = $this->section_id;
                $this->media_model->mediaType = "image";
                $this->media_model->mediaStatus = "1";
                $this->media_model->mediaName   = $content_name;
                $this->media_model->mediaAlias  = $this->function_api->generate_alias($this->media_model->mediaName);
                $this->media_model->mediaSize   = $this->function_api->get_size($_FILES['content_image1']['size']);

                $name = $_FILES["content_image1"]["name"];

                $arrName = explode(".",$name);
                $ext = end($arrName);

                $this->media_model->mediaValue  = "ceo_note_image1_".uniqid().'.'.$ext;
                $this->media_model->mediaDesc = "";
                $this->media_model->mediaPrimary= "1";
                $this->media_model->insert_media();

                $config['upload_path']          = MEDIA_IMAGE_PATH;
                $config['allowed_types']        = 'gif|jpg|png';
                $config['file_name']            = $this->media_model->mediaValue;
                $config['overwrite']            = true;
                $config['max_size']             = 1024; // 1MB
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;
                $this->load->library('upload', $config);
                $this->upload->do_upload('content_image1');
            }

            redirect(site_url('whatsnew/ceo_note/detail/').$content_id);
        }
    }

    function add(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('whatsnew/ceo_note');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('content_name', 'Judul', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $this->data['url_return']     = $url_return;
            $this->data['form_action']    = site_url('whatsnew/ceo_note/add');
            $this->data['title'] = 'CEO Note';
            $this->page = 'ceo_note_add';
            $this->menu = 'whatsnew';
            $this->customjs = array('ckeditor');
            $this->generate_layout();

        }else{
            $content_name = $this->input->post('content_name');
            $content_desc = $this->input->post('content_desc');
            $content_status = $this->input->post('content_status');
            $cat_id = $this->input->post('cat_id');

            //insert
            $recData['sectionId']       = $this->section_id;
            $recData['catId']           = 0;
            $recData['userId']          = 0;
            $recData['memberId']        = $this->member_id;
            $recData['groupId']         = "all";
            $recData['mlevelId']        = "all";
            $recData['contentName']     = $content_name;
            $recData['contentAlias']    = $this->function_api->generate_alias($content_name);
            $recData['contentDesc']     = $content_desc;
            $recData['contentTypeID']   = null;
            $recData['contentTags']     = $this->category_model->get_cat_name($cat_id);
            $recData['contentHits']     = '0';
            $recData['contentSource']   = '';
            $recData['contentBidang']   = 'all';
            $this->member_model->recData['memberId']= $this->member_id;
            $detailMember   = $this->member_model->select_member("byId");
            $groupName = $this->group_model->get_group_name($detailMember['group_id']);
            $recData['contentAuthor']   = $detailMember['member_name'];
            if($groupName!=""){
                $recData['contentAuthor']= $detailMember['member_name']." (".$groupName.")";
            }

            $recData['contentPublishDate']  = date('Y-m-d H:i:s');
            $recData['contentSeoTitle'] = $content_name;
            $recData['contentSeoKeyword']= $content_name;
            $recData['contentSeoDesc']  = $content_desc;
            $recData['contentStatus']   = $content_status;
            $recData['contentCreateBy'] = $this->member_id;
            $recData['contentNotif']    = '0';
            if($content_status=="publish"){
                $recData['contentNotif']    = '1';
            }

            $this->content_model->insert_content($recData);
            $result['ContentId'] = $this->content_model->lastInsertId;

            if(isset($_FILES["content_image1"]) && $_FILES["content_image1"]['name']!=""){
                //image 1
                $this->media_model->dataId = $this->content_model->lastInsertId;
                $this->media_model->sectionId = $this->section_id;
                $this->media_model->mediaType = "image";
                $this->media_model->mediaStatus = "1";
                $this->media_model->mediaName   = $content_name;
                $this->media_model->mediaAlias  = $this->function_api->generate_alias($this->media_model->mediaName);
                $this->media_model->mediaSize   = $this->function_api->get_size($_FILES['content_image1']['size']);

                $name = $_FILES["content_image1"]["name"];

                $arrName = explode(".",$name);
                $ext = end($arrName);

                $this->media_model->mediaValue  = "ceo_note_image1_".uniqid().'.'.$ext;
                $this->media_model->mediaDesc = "";
                $this->media_model->mediaPrimary= "1";
                $this->media_model->insert_media();

                $config['upload_path']          = MEDIA_IMAGE_PATH;
                $config['allowed_types']        = 'gif|jpg|png';
                $config['file_name']            = $this->media_model->mediaValue;
                $config['overwrite']            = true;
                $config['max_size']             = 1024; // 1MB
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;
                $this->load->library('upload', $config);
                $this->upload->do_upload('content_image1');
            }

            if ($this->content_model->lastInsertId){
                redirect(site_url('whatsnew/ceo_note/detail/').$this->content_model->lastInsertId);
            }

        }
    }

    function ajax_get_ceo_note(){
        $page       = $this->input->get('page');
        $limit      = 5;
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit+1;
        $dataContent = $this->content_model->select_content("publish","", "", $this->mlevel_id, $this->bidang);
        $d_count     = count($dataContent);
        $tmp = [];
        $result = [];
        for($i=0;$i<$d_count;$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $this->member_model->recData['memberId'] = $dataContent[$i]['member_id'];
                $author_data            = $this->member_model->select_member('byId');
                $tmp['id']              = $dataContent[$i]['content_id'];
                $tmp['title']           = $dataContent[$i]['content_name'];
                $tmp['author']          = $dataContent[$i]['content_author'];
                $tmp['author_image']    = validate_member_image($author_data['member_image']);
                $tmp['isi']             = str_replace("&quot;",'"',$dataContent[$i]['content_desc']);
                $tmp['isi']             = trim(preg_replace('/ +/', ' ', urldecode(html_entity_decode(strip_tags($tmp['isi'])))));
                $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                $tmp['image']           = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date']            = $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"datetime");
                $tmp['viewed']          = $this->function_api->number($dataContent[$i]['content_hits']);
                $tmp['like_count']      = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count']   = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($result,$tmp);
            }
        }
        $data['contents']   = $result;
        $data['next_page']  = $d_count > $limit?$page+1:0;
        $data['prev_page']  = $page>1?$page-1:0;

        echo json_encode($data);
    }

    function ajax_get_my_ceo_note(){
        $page       = $this->input->get('page');
        $limit      = 5;
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit+1;
        $this->member_model->recData['memberId'] = $this->member_id;
        $member = $this->member_model->select_member('byId');

        $this->content_model->recData['memberId'] = $this->member_id;
        $dataContent = $this->content_model->select_content("CeoNotesMyList","", "", $this->mlevel_id, $this->bidang);
        $d_count     = count($dataContent);
        $tmp = [];
        $my_result = [];
        for($i=0;$i<$d_count;$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $tmp['id']     = $dataContent[$i]['content_id'];
                $tmp['title']  = $dataContent[$i]['content_name'];
                $tmp['author'] = $dataContent[$i]['content_author'];
                $tmp['status'] = $dataContent[$i]['content_status'];
                if($member['member_image']!=""){
                    $tmp['author_image'] = $member['member_image'];
                } else {
                    $tmp['author_image'] = base_url('assets/img/avatar.png');
                }
                $tmp['isi']    = str_replace("&quot;",'"',$dataContent[$i]['content_desc']);
                $tmp['isi']     = trim(preg_replace('/ +/', ' ', urldecode(html_entity_decode(strip_tags($tmp['isi'])))));
                $primaryImage   = $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                $tmp['image']  = (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date']   = $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"datetime");
                $tmp['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($my_result,$tmp);
            }
        }
        $data['contents']   = $my_result;
        $data['next_page']  = $d_count > $limit?$page+1:0;
        $data['prev_page']  = $page>1?$page-1:0;

        echo json_encode($data);
    }
}
