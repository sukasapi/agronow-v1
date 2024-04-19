<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 26/02/21
 * Time: 10:02
 */
use chriskacerguis\RestServer\RestController;

/**
 * @property Content_model content_model
 * @property Member_model member_model
 * @property Media_model media_model
 * @property Function_api function_api
 */
class Content extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('function_api');
        $this->load->model(['content_model', 'member_model', 'media_model']);
    }

    function index_post(){
        return 'nothing';
    }

    function getNewsList_post(){
        $nip = $this->post('nik_sap');
        $limit = $this->post('limit')?$this->post('limit'):10;
        $page = $this->post('page')?$this->post('page'):1;
        if (!$nip){
            $response = [
                'success'   => false,
                'message'   => 'Nip diperlukan'
            ];
            return $this->response($response, 404);
        }

        // set section untuk news
        $this->content_model->recData['sectionId'] = 12;

        $this->member_model->recData['memberNip']=$nip;
        $member = $this->member_model->select_member('byNip');

        $mlevel_id = $member['mlevel_id'];
        $bidang = $member['member_desc'];

        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $dataContent = $this->content_model->select_content("publish","", "", $mlevel_id, $bidang);
        $data = [];
        $result = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $data['id'] 	= $dataContent[$i]['content_id'];
                $data['title'] 	= $dataContent[$i]['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                $data['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $data['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"date");
                $data['view_count'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $data['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $data['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                $data['detail_url']    = base_url("whatsnew/news/detail/".$dataContent[$i]['content_id']);
                array_push($result,$data);
            }
        }
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data'    => $result
        ];
        return $this->response($response, 200);
    }

    function getArticleList_post(){
        $nip = $this->post('nik_sap');
        $limit = $this->post('limit')?$this->post('limit'):10;
        $page = $this->post('page')?$this->post('page'):1;
        if (!$nip){
            $response = [
                'success'   => false,
                'message'   => 'Nip diperlukan'
            ];
            return $this->response($response, 404);
        }

        // set section untuk article
        $this->content_model->recData['sectionId'] = 13;

        $this->member_model->recData['memberNip']=$nip;
        $member = $this->member_model->select_member('byNip');

        $mlevel_id = $member['mlevel_id'];
        $bidang = $member['member_desc'];

        $this->content_model->beginRec = $limit*$page-$limit;
        $this->content_model->endRec = $limit;
        $dataContent = $this->content_model->select_content("publish","", "", $mlevel_id, $bidang);
        $data = [];
        $result = [];
        for($i=0;$i<count($dataContent);$i++){
            $this->content_model->recData['contentId'] = $dataContent[$i]['content_id'];
            if(isset($dataContent[$i])){
                $data['id'] 	= $dataContent[$i]['content_id'];
                $data['title'] 	= $dataContent[$i]['content_name'];
                $primaryImage 	= $this->media_model->get_primary_image($this->content_model->recData['sectionId'],$dataContent[$i]['content_id']);
                $data['image'] 	= (isset($primaryImage['media_image_link'])) ? $primaryImage['media_image_link'] : ((isset($primaryImage['media_value'])) ? URL_MEDIA_IMAGE.$primaryImage['media_value'] : "");
                $data['date'] 	= $this->function_api->date_indo($dataContent[$i]['content_publish_date'],"date");
                $data['view_count'] = $this->function_api->number($dataContent[$i]['content_hits']);
                $data['like_count']  = $this->content_model->select_content_comment("countLikeByContentId");
                $data['comment_count'] = $this->content_model->select_content_comment("countCommentByContentId");
                $data['detail_url']    = base_url("whatsnew/article/detail/".$dataContent[$i]['content_id']);
                array_push($result,$data);
            }
        }
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data'    => $result
        ];
        return $this->response($response, 200);
    }

}