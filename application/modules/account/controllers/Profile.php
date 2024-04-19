<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Function_api function_api
 * @property Member_model member_model
 * @property ForumGroup_model forumGroup_model
 * @property Media_model media_model
 * @property Forum_model forum_model
 * @property Group_model group_model
 * @property CI_Input input
 */
class Profile extends MX_Controller {
    protected $upload_path;
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['member_model', 'auth_model', 'group_model', 'media_model', 'forum_model', 'forumGroup_model']);
        $this->upload_path = date('Y/m/d');
    }

	function index(){
		// akun helper ga boleh diganti datanya
		$arr_helper = array();
		$arr_helper['12220'] = '12220';
		$arr_helper['12222'] = '12222';
		$arr_helper['12223'] = '12223';
		$arr_helper['12224'] = '12224';
		$arr_helper['12225'] = '12225';
		$arr_helper['12226'] = '12226';
		$arr_helper['12227'] = '12227';
		$arr_helper['12228'] = '12228';
		$arr_helper['12229'] = '12229';
		$arr_helper['12230'] = '12230';
		$arr_helper['12231'] = '12231';
		$arr_helper['12232'] = '12232';
		$arr_helper['12233'] = '12233';
		$arr_helper['12234'] = '12234';
		$arr_helper['12235'] = '12235';
		if(in_array($this->session->userdata('member_id'),$arr_helper)) {
			$this->member_model->recData['memberId'] = $this->session->userdata('member_id');
			$dataMember = $this->member_model->select_member("byId");
			echo 'Profile akun '.$dataMember['member_name'].' dikunci.';
			exit;
		}
		
		$this->data['title'] = 'Edit Profile';
		$this->page = 'edit_profile';
		$this->menu = 'account';

		$this->member_model->recData['memberId'] = $this->session->userdata('member_id');
        $dataMember = $this->member_model->select_member("byId");
        if($dataMember['member_image'] && file_exists(MEDIA_IMAGE_PATH."/".$dataMember['member_image'])){
            if(file_exists(MEDIA_IMAGE_PATH."/".$dataMember['member_image'])){
                $member_image = base_url(MEDIA_IMAGE_PATH."/".$dataMember['member_image']);
            }else{
                $member_image = URL_MEDIA_IMAGE."/".$dataMember['member_image'];
            }
        }
        else{
            $member_image = base_url('assets/img/avatar.png');
        }
        $this->media_model->recData['memberId'] = $this->session->userdata('member_id');
        $countDl = $this->media_model->select_media_download("countByMember");
        $this->forum_model->recData['memberId'] = $this->session->userdata('member_id');
        $countForum = $this->forum_model->select_forum("countByMemberId");
        $countForumChat = $this->forum_model->select_forum_chat("countByMemberId");
        $this->forumGroup_model->recData['groupId'] = $dataMember['group_id'];
        $this->forumGroup_model->recData['memberId'] = $this->session->userdata('member_id');
        $countForumGroup = $this->forumGroup_model->select_forum("countByMemberId");
        $countForumGroupChat = $this->forumGroup_model->select_forum_chat("countByMemberId");
        $countForumPost = $countForum+$countForumChat+$countForumGroup+$countForumGroupChat;
        $data = [
            'member_id'     => $dataMember['member_id'],
            'member_name'   => html_entity_decode($dataMember['member_name']),
            'member_nip'    => $dataMember['member_nip'],
            'member_email'  => $dataMember['member_email'],
            'member_phone'  => $dataMember['member_phone'],
            'member_level'  => intval($dataMember['mlevel_id']),
            'member_group'  => $this->group_model->get_group_name($dataMember['group_id']),
            'group_id'      => $dataMember['group_id'],
            'member_image'  => $member_image,
            'member_bidang' => $dataMember['member_desc'],
            'count_download'=> $this->function_api->get_size_number($countDl),
            'count_forum_post' => strval($this->function_api->get_size_number($countForumPost)),
            'portal_group'  => ($this->group_model->is_group_portal_active($dataMember['group_id'])===true)?1:0,
            'ceo_notes_allow' => (intval($dataMember['member_ceo'])==1) ? 1 : 0,
            'bod_share_allow' => (intval($dataMember['member_ceo'])==2) ? 1 : 0,
            'widget_member' => [
                'rank_global'   => 0,
                'rank_group'    => 0,
                'total_point'   => 0,
                'class_room_point'  => 0,
                'knowledge_point'   => 0,
                'corporate_culture_point' => 0,
                'badge_level'   => "SILVER"
            ],
            'jabatan_id' => $dataMember['jabatan_id'],
            'jabatan_name' => $this->member_model->get_jabatan_name($dataMember['jabatan_id']),
		];
		$this->data['data'] = $data;
        $this->data['list_jabatan'] = $this->member_model->get_jabatan_all($dataMember['group_id']);

        $this->customjs = array('croppie');

		$this->generate_layout();
    }
    
    function edit(){
		$name = $this->input->post('name', TRUE);
        $email = $this->input->post('email', TRUE);
        $image_blob = $this->input->post('image', false);
        $phone = $this->input->post('phone', TRUE);
        $this->member_model->recData['memberId'] = $this->session->userdata('member_id');
        $dataMember = $this->member_model->select_member("byId");

        $this->member_model->recData['memberId'] 		= $this->session->userdata('member_id');
        $this->member_model->recData['groupId'] 		= $dataMember['group_id'];
        $this->member_model->recData['mlevelId'] 		= intval($dataMember['mlevel_id']);
        $this->member_model->recData['memberName']		= $name;
        $this->member_model->recData['memberNip']		= $dataMember['member_nip'];
        $this->member_model->recData['memberType']		= $dataMember['member_type'];
        $this->member_model->recData['memberEmail']		= $email;
        $this->member_model->recData['memberPassword']	= $dataMember['member_password'];
        $this->member_model->recData['memberLoginWeb']	= $dataMember['member_login_web'];
        $this->member_model->recData['memberLoginApk']	= $dataMember['member_login_apk'];
        $this->member_model->recData['memberLoginIpa']	= $dataMember['member_login_ipa'];
        $this->member_model->recData['memberRegId']		= $dataMember['member_reg_id'];
        $this->member_model->recData['memberRegChannel']= $dataMember['member_reg_channel'];
        $this->member_model->recData['memberDevice']	= $dataMember['member_device'];
        $this->member_model->recData['memberDesc']		= $dataMember['member_desc'];
        if($image_blob!=""){
            $arrImage = explode(",",$image_blob);
            if(isset($arrImage[1])){
                $mime = str_replace(";base64","",$arrImage[0]);
                $mime = str_replace("data:image/","",$mime);
                $postImage = $arrImage[1];
                if (!is_dir(MEDIA_IMAGE_PATH . $this->upload_path)){
                    mkdir(MEDIA_IMAGE_PATH . $this->upload_path, 0755, true);
                }
                $filename = $this->upload_path."/"."member_".uniqid().'.'.$mime;
                $success = file_put_contents(MEDIA_IMAGE_PATH . $filename, base64_decode($postImage));
                if ($success){
                    $this->member_model->recData['memberImage'] = $filename;
                } else {
                    $this->member_model->recData['memberImage']	= $dataMember['member_image'];
                }
            }
        } else {
            $this->member_model->recData['memberImage']	= $dataMember['member_image'];
        }

        $this->member_model->recData['memberGender']	= $dataMember['member_gender'];
        $this->member_model->recData['memberBirthPlace']= $dataMember['member_birth_place'];
        $this->member_model->recData['memberBirthDate']	= $dataMember['member_birth_date'];
        $this->member_model->recData['memberPhone']		= $phone;
        $this->member_model->recData['memberAddress']	= $dataMember['member_address'];
        $this->member_model->recData['memberCity']		= $dataMember['member_city'];
        $this->member_model->recData['memberProvince']	= $dataMember['member_province'];
        $this->member_model->recData['memberPostcode']	= $dataMember['member_postcode'];
        $this->member_model->recData['memberStatus']	= $dataMember['member_status'];
        $this->member_model->update_member("",$this->member_model->recData);

        $this->session->set_flashdata('item', array('color' => 'success', 'message' => $this->function_api->msg['00']));
        redirect('account/profile');
    }

    function changePass(){
        $old_pass = md5($this->input->post('old_password'));
        $new_pass = md5($this->input->post('new_password'));
        $confirm_new_pass = md5($this->input->post('confirm_new_pass'));
        if($new_pass != $confirm_new_pass){
            $this->session->set_flashdata('item', array('color' => 'danger', 'message' => 'Confirm New Password not match'));
        }else{
            $this->member_model->recData['memberId'] = $this->session->userdata('member_id');
            $dataMember = $this->member_model->select_member("byId");

            if($dataMember['member_password']!=$old_pass){
                $this->session->set_flashdata('item', array('color' => 'danger', 'message' => $this->function_api->msg['21']));
            } else {
                $this->member_model->update_member("byField","","member_password",$new_pass);
                $this->session->set_flashdata('item', array('color' => 'success', 'message' => $this->function_api->msg['00']));
            }
        }
        redirect('account/profile');
    }

    public function changeJabatan(){
        $this->load->model('kompetensi_jabatan_model');
        $this->load->model('kompetensi_member_model');
        $post = $this->input->post();
        $this->db->trans_start();
        if ($post['jabatan_id']){
            $kompetensi = $this->kompetensi_jabatan_model->get_by_jabatan($post['jabatan_id']);
            if ($kompetensi){
                foreach ($kompetensi as $k => $v){

                    $kompetensi_id = $v['cr_id'];
                    $member_id = $this->session->userdata('member_id');
                    $get_member = $this->kompetensi_member_model->get_by_kompetensi_member($kompetensi_id,$member_id);
                    if ($get_member){
                        // Member Exist Then Skip

                    }else{
                        $data_kompetensi_member = array(
                            'cr_id'     => $kompetensi_id,
                            'member_id' => $member_id,
                        );
                        $this->kompetensi_member_model->insert($data_kompetensi_member);
                    }

                }
            }

            $this->member_model->recData['memberId'] = $this->session->userdata('member_id');
            $this->member_model->update_member('byField', array('memberId' => $this->session->userdata('member_id')), 'jabatan_id', $post['jabatan_id']);

            if($this->db->trans_complete() !== false){
                $this->session->set_flashdata('item', array('color' => 'success', 'message' => 'Ubah jabatan berhasil.'));
            }else{
                $this->session->set_flashdata('item', array('color' => 'danger', 'message' => 'Ubah jabatan gagal.'));
            }
        }

        redirect('account/profile');
    }
}
