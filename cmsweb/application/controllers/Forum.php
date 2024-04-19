<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'forum_model',
            'forum_chat_model',
            'category_model',
            'media_model',
            'group_model',
            'member_level_model',
            'klien_model',
        ));
        $select_tree = [];
        $this->section_id = 19;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->forum_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['forum_id']              = $item->forum_id;
            $row['forum_create_date']     = $item->forum_create_date?parseDateShortReadable($item->forum_create_date):NULL;
            $row['forum_create_time']     = $item->forum_create_date?parseTimeReadable($item->forum_create_date):NULL;
            $row['forum_update_date']    = $item->forum_update_date?parseDateShortReadable($item->forum_update_date):NULL;
            $row['forum_update_time']    = $item->forum_update_date?parseTimeReadable($item->forum_update_date):NULL;
            $row['forum_name']            = $item->forum_name;
            $row['member_name']            = $item->member_name;
            $row['group_name']            = $item->group_name;

            $user_count  = $this->forum_chat_model->count_member_by_forum($item->forum_id);
            $row['user_count']       = $user_count?$user_count['total']:0;

            $comment_count  = $this->forum_chat_model->count_by_forum($item->forum_id);
            $row['comment_count']    = $comment_count?$comment_count['total']:0;

            $row['nama_klien'] = getKlienByMember($item->member_id, 'render_nama_klien');

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->forum_model->count_all(),
            "recordsFiltered" => $this->forum_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_forum($forum_id){
        $get_forum = $this->forum_model->get($forum_id);
        if ($get_forum==FALSE){
            redirect(404);
        }else{
            return $get_forum;
        }
    }

    function get_forum_chat($forum_chat_id){
        $get_forum_chat = $this->forum_chat_model->get($forum_chat_id);
        if ($get_forum_chat==FALSE){
            redirect(404);
        }else{
            return $get_forum_chat;
        }
    }

    private function printTree($tree, $r = 0, $p = null, $cat_selected) {
        foreach ($tree as $i => $t) {
            if ($cat_selected==$t['cat_id']){
                $is_selected = 'selected';
            }else{
                $is_selected = '';
            }
            $dash = ($t['cat_parent'] == 0) ? '' : str_repeat('- ', $r) .' ';

            $this->select_tree[] = '<option value="'.$t['cat_id'].'" '.$is_selected.'>'.$dash.$t['cat_name'].'</option>';
            //printf("\t<option value='%d' %s>%s%s</option>\n", $t['cat_id'],$is_selected, $dash, $t['cat_name']);
            if ($t['cat_parent'] == $p) {
                // reset $r
                $r = 0;
            }
            if (isset($t['child'])) {
                $this->printTree($t['child'], $r+1, $t['cat_parent'], $cat_selected);
            }
        }
    }

    function index(){
        has_access('forum.view');

        $data['form_opt_klien'][NULL] = NULL;

        $param_query = NULL;
        if (my_klien()){
            $param_query['filter_id'] = my_klien();
        }
        $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_klien!=FALSE){
            foreach ($get_klien['data'] as $k => $v) {
                $data['form_opt_klien'][$v['id']] = $v['nama'];
            }
        }


        $data['page_name']          = 'Forum';
        $data['page_sub_name']      = 'List Forum';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'forum/forum_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('forum.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('forum');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('forum_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc');
            $get_cat_parent = $this->input->get('parent');
            if ($this->input->get('parent')){
                $cat_selected = $get_cat_parent;
            }else{
                $cat_selected = NULL;
            }

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Forum';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('forum/create');
            $data['page']           = 'forum/forum_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'user_id'       => 0,
                'member_id'     => 0,
                'forum_name'    => $post['forum_name'],
                'forum_alias'   => slugify($post['forum_name']),
                'forum_desc'    => $post['forum_desc'],
                'forum_sticky'  => $post['forum_sticky'],
                'cat_id'        => $post['cat_id'],
                'forum_status'  => "open",
                'forum_create_date'   => date("Y-m-d H:i:s"),
            );

            //print_r($data);

            $insert = $this->forum_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('forum/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($forum_id){
        has_access('forum.edit');

        $forum = $this->get_forum($forum_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('forum/detail/'.$forum_id);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('forum_name', 'Judul', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($forum['cat_id']);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id,'nama_asc');
            $cat_selected = $forum['cat_id'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['request']            = $forum;
            $data['forum']            = $forum;

            $data['url_return']     = $url_return;
            $data['editable']       = TRUE;

            $data['page_name']      = 'Forum';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('forum/edit/'.$forum_id);
            $data['page']           = 'forum/forum_form_edit_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'forum_id'    => $post['forum_id'],
                'forum_name'    => $post['forum_name'],
                'forum_alias'   => slugify($post['forum_name']),
                'forum_desc'    => $post['forum_desc'],
                'forum_sticky'  => $post['forum_sticky'],
                'cat_id'        => $post['cat_id'],
                'forum_update_date'   => date("Y-m-d H:i:s"),
            );

            //print_r($data);

            $update = $this->forum_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$forum_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function detail($forum_id=NULL){
        has_access('forum.view');

        $forum = $this->get_forum($forum_id);

        $data['member']         = $this->forum_chat_model->get_member_by_forum($forum_id);
        $data['member_count']   = $this->forum_chat_model->count_member_by_forum($forum_id);

        $data['comment']         = $this->forum_chat_model->get_by_forum($forum_id);
        $data['comment_count']   = $this->forum_chat_model->count_by_forum($forum_id);

        $data['forum']          = $forum;
        $data['page_name']      = 'Forum';
        $data['page_sub_name']  = $forum['forum_name'];
        $data['page'] = 'forum/forum_detail_view';
        $this->load->view('main_view',$data);
    }

    function delete($forum_id=NULL){
        has_access('forum.delete');

        $forum = $this->get_forum($forum_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('forum/detail/').$forum_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('forum_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['forum']         = $forum;
            $data['request']            = $forum;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('forum/delete').'/'.$forum_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Forum";
            $data['page_sub_name']      = 'Hapus Forum';
            $data['page']               = 'forum/forum_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->forum_model->delete($forum_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$forum_id,'Hapus',NULL);
                $msg        = $forum['forum_name']." telah dihapus.";
                $url_return = site_url('forum');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('forum/delete/'.$forum_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }


    function remove_comment($forum_chat_id){
        has_access('forum.delete');

        $forum_chat = $this->get_forum_chat($forum_chat_id);
        $forum_id = $forum_chat['forum_id'];

        $delete = $this->forum_chat_model->delete($forum_chat_id,TRUE);
        if ($delete==TRUE) {
            create_log($this->section_id,$forum_id,'Hapus','Komentar');
            $msg        = "1 komentar telah dihapus.";
            $url_return = site_url('forum/detail/'.$forum_id);
            flash_notif_warning($msg,$url_return);
        }else{
            $msg        = "Komentar gagal dihapus.";
            $url_return = site_url('forum/detail/'.$forum_id);
            flash_notif_failed($msg,$url_return);
        }
    }



}