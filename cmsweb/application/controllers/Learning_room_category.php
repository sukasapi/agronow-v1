<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Learning_room_category extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model('category_model');

        $this->section_id = 29;
    }

    function ajax_search(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get = $this->input->get();

        $query = isset($get['q'])?$get['q']:NULL;
        $search_learning_room_category = $this->category_model->search($query,75);

        if ($search_learning_room_category!=FALSE) {
            $data_response = array();
            foreach ($search_learning_room_category as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['id'];
                $data_response['results'][$k]['text']  = $v['name'];
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }


    function index(){
        $data['list_menu'] = $this->category_model->get_category_tree($this->section_id);

        $data['page_name']          = 'Kategori Learning Room';
        $data['page_sub_name']      = 'List Kategori';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'learning_room_category/learning_room_category_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('learning_room_category');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cat_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_learning_room_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_learning_room_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_learning_room_category!=FALSE){
                foreach ($get_learning_room_category['data'] as $k => $v) {
                    $data['form_opt_learning_room_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']          = 'Kategori';
            $data['page_sub_name']      = 'Tambah Kategori';

            $data['form_action']    = site_url('learning_room_category/create').'?url_return='.$url_return;
            $data['page']           = 'learning_room_category/learning_room_category_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();
            $created_by = NULL;
            $data = array(
                'cat_name'  => $post['cat_name'],
                'cat_parent'  => $post['cat_parent']?$post['cat_parent']:'0',
                'section_id'  => $this->section_id,
                'cat_status'  => $post['cat_status']?$post['cat_status']:'0',
            );

            $insert = $this->category_model->insert($data);
            if ($insert==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    function edit($learning_room_category_id=NULL){
        $learning_room_category = getCategory($learning_room_category_id,$this->section_id);
        if ($learning_room_category['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('learning_room_category');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');
        $this->form_validation->set_rules('cat_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_learning_room_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_learning_room_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_learning_room_category!=FALSE){
                foreach ($get_learning_room_category['data'] as $k => $v) {
                    $data['form_opt_learning_room_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            $data['learning_room_category']      = $learning_room_category;
            $data['request']            = $learning_room_category;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('learning_room_category/edit').'/'.$learning_room_category_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kategori Learning Room";
            $data['page_sub_name']      = 'Edit Kategori';
            $data['page']               = 'learning_room_category/learning_room_category_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'cat_id'  => $post['cat_id']==NULL?NULL:$post['cat_id'],
                'cat_name'  => $post['cat_name'],
                'cat_parent'  => $post['cat_parent']?$post['cat_parent']:'0',
                'cat_status'        => $post['cat_status'],
            );


            $edit = $this->category_model->update($data);
            if ($edit==TRUE) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function delete($learning_room_category_id=NULL){
        $learning_room_category = getCategory($learning_room_category_id,$this->section_id);
        if ($learning_room_category['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('learning_room_category');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['learning_room_category']         = $learning_room_category;
            $data['request']            = $learning_room_category;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('learning_room_category/delete').'/'.$learning_room_category_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kategori Learning Room";
            $data['page_sub_name']      = 'Hapus Kategori';
            $data['page']               = 'learning_room_category/learning_room_category_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->category_model->delete($learning_room_category_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                $msg        = $learning_room_category['cat_name']." telah dihapus.";
                $url_return = site_url('learning_room_category');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('learning_room_category/delete/'.$learning_room_category_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }

    function update_tree(){
        $json = $this->input->post('json_menu');
        $url_return = $this->input->post('url_return');

        /*$json = '[{"id":14},{"id":4},{"id":2,"children":[{"id":13},{"id":20}]},{"id":1,"children":[{"id":5,"children":[{"id":8,"children":[{"id":11},{"id":12}]},{"id":9},{"id":10}]},{"id":6},{"id":7},{"id":19}]},{"id":3,"children":[{"id":15},{"id":16}]}]';*/

        $nestable_array = json_decode($json,TRUE);

        $flat_nestable = $this->flat_nestable_recursive($nestable_array);
         //print_r($flat_nestable);
        $update_menu = $this->category_model->set_category_batch($flat_nestable);
        if ($update_menu==FALSE) {
            flash_notif_failed(NULL,urldecode($url_return));
        }else{
            flash_notif_success(NULL,urldecode($url_return));
        }

    }

    private $array_flat_nestable = array();
    private function flat_nestable_recursive($array,$parent=NULL){
        if ($parent==NULL) {
            $parent = NULL;
        }
        $sort = 0;
        foreach ($array as $item) {
            $flat_array = array(
                'cat_id'       => $item['id'],
                'cat_parent'   => $parent,
                'cat_order'     => $sort
            );
            array_push($this->array_flat_nestable, $flat_array);
            if (isset($item['children'])) {
                $this->flat_nestable_recursive($item['children'],$item['id']);
            }

            $sort++;
        }
        return $this->array_flat_nestable;
    }


}