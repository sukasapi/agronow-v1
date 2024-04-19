<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input input
 * @property Content_model content_model
 * @property Group_model group_model
 * @property Media_model media_model
 * @property Category_model category_model
 * @property Function_api function_api
 */
class Knowledge_sharing extends MX_Controller {

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'culture_model', 'classroom_model', 'forum_model', 'member_model', 'content_type_model', 'group_model']);

        $this->section_id = 31;
        $this->member_id = $this->session->userdata('member_id');
        $this->content_model->recData['sectionId'] = $this->section_id;
        $this->mlevel_id = $this->session->userdata('member_level');
        $this->group_id = $this->session->userdata('group_id');
        $this->bidang = $this->session->userdata('member_bidang');
    }

    function index(){
        $cat_id = $this->input->get('cat_id');
        $page = $this->input->get('page');
        if(intval($cat_id) > 0){
            $this->category_model->catParent = $cat_id;
            $categories = $this->category_model->select_category('byParent',$this->section_id);
            $this->data['sub'] = true;
        }else{
            $categories = $this->category_model->select_category('parent0',$this->section_id);
        }
        $breadcumb = [];
        foreach ($categories as $d){
            $parent = ($d['cat_parent'] && $d['cat_parent'] != '0');
            $parent_id = $d['cat_parent'];
            while ($parent){
                $this->category_model->catId = $parent_id;
                $d_parent = $this->category_model->select_category('byId');
                if ($d_parent) {
                    array_unshift($breadcumb,$d_parent);
                    $parent = ($d_parent['cat_parent'] && $d_parent['cat_parent'] != 0);
                    $parent_id = $d_parent['cat_parent'];
                } else {
                    $parent = false;
                }
            }
            break;
        }

        $content = [];
        if ($cat_id){
            $page = $page>=1?$page:1;
            $limit = 10; // content per page
            $this->content_model->beginRec = $limit*$page-$limit;
            $this->content_model->endRec = $limit;
            $dataContent = $this->content_model->select_content("publish",$cat_id, "", $this->mlevel_id, $this->bidang);
            $tmp = [];
            foreach ($dataContent as $dc){
                $this->content_model->recData['contentId'] = $dc['content_id'];
                $tmp['id'] 	= $dc['content_id'];
                $tmp['title'] 	= $dc['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dc['content_id']);
                $tmp['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $tmp['date'] 	= $this->function_api->date_indo($dc['content_publish_date'],"datetime");
                $tmp['viewed'] = $this->function_api->number($dc['content_hits']);
                $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($content,$tmp);
            }
        }


        $this->data['cat_id'] = $cat_id;
        $this->data['page'] = $page;
        $this->data['breadcumb'] = $breadcumb;
        $this->data['categories'] = $categories;
        $this->data['contents'] = $content;

        $this->data['title'] = 'Knowledge Management';
		$this->page = 'knowledge_sharing/index';
		$this->menu = 'learning';
		$this->generate_layout();
    }

	function listing($page=1){
        $cat_id = $this->input->get('cat_id');
        if(!(intval($cat_id) > 0)){
            redirect('learning/knowledge_sharing');
        }
        $this->category_model->catParent = $cat_id;
        $cat = $this->category_model->select_category('byParent',$this->section_id);
        if(count($cat)>0){
            redirect('learning/knowledge_sharing/index?cat_id='.$cat_id);
        }
		$page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $dataContent = $this->content_model->select_content("publish",$cat_id, "", $this->mlevel_id, $this->bidang);
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

        $this->data['cat_id'] = $cat_id;
		$this->data['page'] = $page;
		$this->data['title'] = 'Knowledge Management';
		$this->page = 'knowledge_sharing/listing';
		$this->menu = 'learning';
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
        $content['is_liked'] = $this->content_model->is_member_like($content_id, $this->session->userdata('member_id'));
        $content['is_bookmarked']   = $this->member_model->in_bookmark($this->member_id, $content_id);
        // get all media
        $media = $this->media_model->get_media('', $this->section_id, $content['content_id']);
        $content['media'] = $media?$media[0]:NULL;

        $this->data['content'] = $content;

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
                $tmp['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"dd FF YYYY");
                $tmp['viewed'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $tmp['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $tmp['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                array_push($info,$tmp);
            }
        }
        $this->data['info'] = $info;

		$this->data['title'] = 'Knowledge Management';
		$this->page = 'knowledge_sharing/detail';
		$this->menu = 'learning';
		$this->customjs = array('content','sharelink','comments','bookmark');
        $this->generate_layout();
    }

    public function preview($contentAlias = ''){
		$content = $this->content_model->get_content_detail($contentAlias, $this->section_id, $this->session->userdata('member_id'));
		
		if($content){
            $this->data['title'] = 'Knowledge Management';
            $this->menu = 'learning';
			$this->page = 'knowledge_sharing/preview';

			$this->data['data'] = $content;

			$this->generate_layout();
		}else{
			redirect('learning/digital_library');
		}
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
        $sectionName = "Knowledge";

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
        $this->data['title'] = 'Knowledge Management';
        $this->data['page'] = $page;
        $this->page = 'knowledge_sharing/listing';
        $this->menu = 'learning';
        $this->data['data'] = $result;
        $this->data['cat_id'] = 0;

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

    function add(){
	    $cat_id = $this->input->get('cat_id');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');

        if ($this->form_validation->run() == FALSE){
//            $category = $this->category_model->select_category('parent0',$this->section_id);
            $category = $this->category_model->select_category('',$this->section_id);
            $hierarchy = $this->function_api->convertToHierarchy($category, 'cat_id', 'cat_parent', 'child');
            $this->data['selected_cat_id'] = $cat_id?$cat_id:0;
            $this->data['category'] = $hierarchy;
            $this->data['title'] = 'Knowledge Management';
            $this->page = 'knowledge_sharing/tambah';
            $this->menu = 'whatsnew';
            $this->customcss = array('select2');
            $this->customjs = array('select2');
            $this->generate_layout();
        }else{
            $post = $this->input->post();

            $group_id = 'all';

            // Proses Bidang Member
            $bidang = 'all';
            // if (isset($post['bidang'])){
            //     if (in_array('all',$post['bidang'])){
            //         $bidang = 'all';
            //     }else{
            //         $bidang = implode(',',$post['bidang']);
            //     }
            // }

            // Proses Level Member
            $member_level = NULL;
            // if (isset($post['member_level'])){
            //     if (in_array('all',$post['member_level'])){
            //         $member_level = 'all';
            //     }else{
            //         $member_level = implode(',',$post['member_level']);
            //     }
            // }

            // Proses Tags
            $tags = $this->category_model->get_cat_name($cat_id);
            // if (isset($post['content_tags'])){
            //     $tags = implode(',',$post['content_tags']);

            //     foreach ($post['content_tags'] as $v){
            //         $get_tags = $this->content_tags_model->get_by_name($v);
            //         if ($get_tags==FALSE){
            //             $data_tags = array(
            //                 'section_id'    => $this->section_id,
            //                 'tags_name'     => $v,
            //                 'tags_alias'    => $this->function_api->slugify($v),
            //             );
            //             $this->content_tags_model->insert($data_tags);
            //         }
            //     }

            // }else{
            //     $tags = NULL;
            // }

            //insert
            $recData['sectionId']		    = $this->section_id;
            $recData['catId']			    = $post['kategori'];
            $recData['userId']			    = 0;
            $recData['memberId']		    = 0;
            $recData['groupId']			    = $group_id;
            $recData['mlevelId']		    = 'all';
            $recData['contentName']		    = $post['judul'];
            $recData['contentAlias']	    = $this->function_api->generate_alias($post['judul']);
            $recData['contentDesc']		    = $post['keterangan'];
            $recData['contentTags']		    = $tags;
            $recData['contentHits']		    = '0';
            $recData['contentSource']	    = '';
            $recData['contentBidang']	    = $bidang;

            $this->member_model->recData['memberId']= $this->member_id;
            $detailMember 	= $this->member_model->select_member("byId");
            $groupName = $this->group_model->get_group_name($detailMember['group_id']);
            $recData['contentAuthor']	    = $detailMember['member_name'];
            if($groupName!=""){
                $recData['contentAuthor']= $detailMember['member_name']." (".$groupName.")";
            }

            $recData['contentSeoTitle']	    = $post['judul'];
            $recData['contentSeoKeyword']   = $post['judul'];
            $recData['contentSeoDesc']	    = $post['keterangan'];
            $recData['contentTypeID']       = null;
            $recData['contentStatus']	    = 'draft';
            $recData['contentCreateBy']	    = $this->member_id; //TODO: di cms = user_id 1
            $recData['contentPublishDate']	= date('Y-m-d H:i:s');
            $recData['contentNotif']	    = '0';

            $this->content_model->insert_content($recData);
            $ContentId = $this->content_model->lastInsertId;

            //file
            if(isset($_FILES["content_doc"]) && $_FILES["content_doc"]['name']!="") {
                $this->media_model->dataId = $this->content_model->lastInsertId;
                $this->media_model->sectionId = $this->section_id;
                $this->media_model->mediaType = "document";
                $this->media_model->mediaStatus = "1";
                $this->media_model->mediaName = $post['judul'];
                $this->media_model->mediaAlias = $this->function_api->generate_alias($this->media_model->mediaName);
                $this->media_model->mediaSize = $this->function_api->get_size($_FILES['content_doc']['size']);

                $name = $_FILES["content_doc"]["name"];

                $arrName = explode(".", $name);
                $ext = end($arrName);
                $this->media_model->mediaValue = "knowledge_sharing_document_" . uniqid() . '.' . $ext;
                $this->media_model->mediaDesc = "";
                $this->media_model->mediaPrimary = "1";
                $this->media_model->insert_media();

                $config['upload_path'] = MEDIA_DOCUMENT_PATH;
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = $this->media_model->mediaValue;
                $config['overwrite'] = true;
                $config['max_size'] = 1024; // 1MB
                $this->load->library('upload', $config);
                $this->upload->do_upload('content_doc');
            }

            redirect('learning/knowledge_sharing/detail/'.$ContentId);
        }
	}
}
