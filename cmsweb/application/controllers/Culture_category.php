<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Culture_category extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model('category_model');
        $select_tree = [];
        $this->section_id = 33;
    }

    function ajax_search(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $get = $this->input->get();

        $query = isset($get['q'])?$get['q']:NULL;
        $search_culture_category = $this->category_model->search($query,$this->section_id,75);

        if ($search_culture_category!=FALSE) {
            $data_response = array();
            foreach ($search_culture_category as $k => $v ) {
                $data_response['results'][$k]['id']    = $v['cat_id'];
                $data_response['results'][$k]['text']  = $v['cat_name'];
            }

            $response_json = json_encode($data_response);
        }else{
            $response_json = NULL;
        }

        echo $response_json;
    }


    private function printTree($tree, $r = 0, $p = null, $cat_selected) {
        if($tree){
            foreach ($tree as $i => $t) {
                if ($cat_selected==$t['cat_id']){
                    $is_selected = 'selected';
                }else{
                    $is_selected = '';
                }
                $dash = ($t['cat_parent'] == 0) ? '' : str_repeat('- ', $r) .' ';

                $depth = 1;
                $depth = ($t['cat_parent'] == 0) ? 1 : $depth + $r;;

                $max_depth = 2;
                if ($depth >= $max_depth){
                    $is_disable = 'disabled';
                }else{
                    $is_disable = '';
                }

                $this->select_tree[] = '<option '.$is_disable.' value="'.$t['cat_id'].'" '.$is_selected.'>'.$dash.$t['cat_name'].'</option>';
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
    }

    function index(){
        has_access('culturecat.view');

        $data['list_menu'] = $this->category_model->get_category_tree($this->section_id);

        $data['page_name']          = 'Kategori Culture';
        $data['page_sub_name']      = 'List Kategori';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'culture_category/culture_category_list_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('culturecat.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture_category');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cat_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_culture_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_culture_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_culture_category!=FALSE){
                foreach ($get_culture_category['data'] as $k => $v) {
                    $data['form_opt_culture_category'][$v['cat_id']] = $v['cat_name'];
                }
            }

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
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

            $data['page_name']          = 'Kategori';
            $data['page_sub_name']      = 'Tambah Kategori';

            $data['form_action']    = site_url('culture_category/create').'?url_return='.$url_return;
            $data['page']           = 'culture_category/culture_category_form_create_view';

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
                create_log($this->section_id,$insert,'Tambah','Kategori');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }


    function edit($culture_category_id=NULL){
        has_access('culturecat.edit');

        $culture_category = getCategory($culture_category_id,$this->section_id);
        if ($culture_category['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture_category');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');
        $this->form_validation->set_rules('cat_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_culture_category'][''] = '-';
            $param_query['filter_active'] = '';
            $param_query['filter_section'] = $this->section_id;
            $get_culture_category = $this->category_model->get_all(NULL,NULL,NULL,$param_query);
            if ($get_culture_category!=FALSE){
                foreach ($get_culture_category['data'] as $k => $v) {
                    $data['form_opt_culture_category'][$v['cat_id']] = $v['cat_name'];
                }
            }


            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
            $cat_selected = $culture_category['cat_parent'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['culture_category']      = $culture_category;
            $data['request']            = $culture_category;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('culture_category/edit').'/'.$culture_category_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kategori Culture";
            $data['page_sub_name']      = 'Edit Kategori';
            $data['page']               = 'culture_category/culture_category_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'cat_id'  => $post['cat_id']==NULL?NULL:$post['cat_id'],
                'cat_name'  => $post['cat_name'],
                'cat_parent'  => $post['cat_parent']?$post['cat_parent']:'0',
                'cat_status'        => $post['cat_status'],
                'cat_update_date'   => date('Y-m-d H:i:s')
            );


            $edit = $this->category_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$culture_category_id,'Edit','Kategori');
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function delete($culture_category_id=NULL){
        has_access('culturecat.delete');

        $culture_category = getCategory($culture_category_id,$this->section_id);
        if ($culture_category['section_id']!=$this->section_id){
            redirect(404);
        }

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture_category');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cat_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['culture_category']         = $culture_category;
            $data['request']            = $culture_category;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('culture_category/delete').'/'.$culture_category_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Kategori Culture";
            $data['page_sub_name']      = 'Hapus Kategori';
            $data['page']               = 'culture_category/culture_category_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->category_model->delete($culture_category_id,FALSE);   //FALSE = Hard Delete
            if ($delete==TRUE) {
                create_log($this->section_id,$culture_category_id,'Hapus','Kategori');
                $msg        = $culture_category['cat_name']." telah dihapus.";
                $url_return = site_url('culture_category');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('culture_category/delete/'.$culture_category_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }

    function update_tree(){
        has_access('culturecat.edit');

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
            create_log($this->section_id,'','Edit Struktur','Kategori');
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