<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_mapping extends CI_Controller {

	// look content_type on table _content_type
	// mapping data content by media_type
	// media_type   content_type
	// document     ?? (Ebook (1) or Document (2))
	// audio        Audio (3)
	// video        Video (4)
	public function index(){
		$update = [];
		$update['document'] = [];
		$update['ebook'] = [];
		$update['video'] = [];
		$update['audio'] = [];

		$this->db->select('_content.content_id, media_type');
		$this->db->join('_media', '_content.content_id = _media.data_id AND _media.section_id = _content.section_id');
		
		foreach ($this->db->get('_content')->result_array() as $data) {
			if($data['media_type'] == 'video'){
				$update['video'][] = $data['content_id'];
			}elseif($data['media_type'] == 'audio'){
				$update['audio'][] = $data['content_id'];
			}
		}

		foreach ($update as $type => $data) {
			if(count($data) > 0){
				$sv = [];
				if($type == 'audio'){
					$sv['content_type_id'] = 3;
				}elseif($type == 'video'){
					$sv['content_type_id'] = 4;
				}
				$this->db->where_in('content_id', $data);
				$this->db->update('_content', $sv);

				var_dump('Change '.$type.' data success!');
			}
		}
	}
}
