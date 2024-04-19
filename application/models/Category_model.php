<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 07/08/20
 * Time: 22:14
 * @property CI_DB_query_builder db
 */

class Category_model extends CI_Model
{
    var $catId,$sectionId,$catName,$catAlias,$catDesc,$catImage,$catHits,$catParent,$catLevel,$catStatus,$catRoot,$catOrder;
    var $lastInsertId;

    function insert_category(){
        $sql = "INSERT INTO _category 
				VALUES('', '".$this->sectionId."', '".$this->catName."', '".$this->catAlias."', '".$this->catDesc."', 
							'".$this->catImage."', '".$this->catHits."', '".$this->catParent."', 
							'".$this->catLevel."', '1', '".$this->catRoot."','".$this->catOrder."'
						)";
        $this->db->query($sql);
        $this->catId = $this->lastInsertId = $this->db->insert_id();
        $this->catRoot = ($this->catLevel=="1") ? $this->catId : $this->get_cat_root($this->catParent) . ",".$this->catId;
        $this->update_category("byField","cat_root",$this->catRoot);

        return $this->lastInsertId;
    }

    function is_parent($id=""){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _category WHERE cat_parent = '".intval($id)."'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0)	$result = true;
        return $result;
    }

    function update_category($opt="",$field="",$value=""){
        if($opt==""){
            $sql = "UPDATE _category 
					SET cat_name 	= '".$this->catName."',
						cat_alias 	= '".$this->catAlias."',
						cat_desc 	= '".$this->catDesc."',
						cat_image	= '".$this->catImage."', 
						cat_parent 	= '".$this->catParent."',
						cat_status	= '".$this->catStatus."', 
						cat_level 	= '".$this->catLevel."',
						cat_root 	= '".$this->catRoot."'
					WHERE cat_id = '".$this->catId."' 
					";
        }
        elseif($opt=="byField"){
            $sql = "UPDATE _category SET ".$field." = '".$value."' WHERE cat_id = '".$this->catId."' ";
        }
        $query = $this->db->query($sql);
        return $query;
    }

    function get_cat_name($catId=""){
        $sqlCatRoot = "SELECT cat_root FROM _category WHERE cat_id = '".$catId."'";
        $query = $this->db->query($sqlCatRoot);
        $dataCatRoot = $query->result_array();
        if(count($dataCatRoot)>0){
            $sql = "SELECT cat_name FROM _category 
					WHERE cat_id IN ('".$dataCatRoot[0]['cat_root']."') ORDER BY cat_id";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $catName = "";
            if(count($data)>0){
                for($i=0;$i<count($data);$i++){
                    $catName .= $data[$i]['cat_name'];
                    if($i<count($data)-1){
                        $catName .=", ";
                    }
                }
            }
        }
        else{
            $catName = "&mdash;";
        }
        return $catName;
    }

    function cat_name_link($catId="",$area=""){
        $dataCatRoot = $this->get_cat_root($catId);
        $catRoot = str_replace(",","','",$dataCatRoot);

        $sql = "SELECT a.cat_id,a.cat_name, a.cat_alias, b.section_alias_front 
				FROM _category a, _section b 
				WHERE a.section_id = b.section_id AND a.cat_id IN ('".$catRoot."') ORDER BY a.cat_id";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $result = array();
        if(count($data)>0){
            for($i=0;$i<count($data);$i++){
                $recData['cat_name'] = $data[$i]['cat_name'];
                $recData['cat_link'] = SITE_HOST."/".$data[$i]['section_alias_front']."/category/".$data[$i]['cat_id']."-".$data[$i]['cat_alias'];
                array_push($result,$recData);
            }
        }
        return $result;
    }

    function get_cat_root($catId){
        $sql = "SELECT cat_root FROM _category WHERE cat_id = '".$catId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['cat_root'];
    }

    function get_id_sub($id){
        $sql = "SELECT cat_root FROM _category WHERE CONCAT(',',cat_root,',') LIKE '%,".$id.",%'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $dataCat = $id;
        for($i=0;$i<count($data);$i++){
            $dataCat .= ",".$data[$i]['cat_root'];
        }
        return $dataCat;
    }

    function is_category($sectionId="",$catAlias=""){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _category 
				WHERE section_id = '".$sectionId."' AND cat_alias = '".$catAlias."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0) $result = true;
        return $result;
    }

    function is_cat_exists(){
        $result = false;
        $sql = "SELECT COUNT(*) AS TOTAL FROM _category 
				WHERE section_id = '".$this->sectionId."' AND cat_name = '".$this->catName."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if($data[0]['TOTAL']>0){
            $result = true;
        }
        return $result;
    }

    function get_max_level(){
        $sql = "SELECT MAX(cat_level) AS TOTAL FROM _category 
				WHERE section_id = '".$this->sectionId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['TOTAL'];
    }

    function get_max_order(){
        $sql = "SELECT MAX(cat_order) AS MAX_ORDER FROM _category WHERE section_id = '".$this->sectionId."' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['MAX_ORDER'];
    }

    function get_new_order($parentId){
        $sql = "SELECT MAX(cat_order) AS MAX_ORDER FROM _category WHERE CONCAT(',',cat_root,',') LIKE '%,".$parentId.",%' ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data[0]['MAX_ORDER'];
    }

    function reorder($lastOrder){
        $sql = "UPDATE _category SET cat_order = cat_order+1 
				WHERE cat_id IN (SELECT cat_id FROM 
									(SELECT cat_id FROM _category WHERE section_id = '".$this->sectionId."' AND cat_order > '".$lastOrder."') AS cats
								) ";
        $result = $this->db->query($sql);
        return $result;
    }

    function delete_category(){
        $sql = "DELETE FROM _category WHERE CONCAT(',',cat_root,',') LIKE '%,".$this->catId.",%' ";
        $result = $this->db->query($sql);
        return $result;
    }

    function select_category($opt="",$sectionId="",$desc=""){
        if($opt==""){
            $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_status = '1' ";
            if($desc!=""){
                $sql .= " AND cat_desc = '".$desc."' ";
            }
            $sql .= " ORDER BY cat_order ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="count"){
            $sql = "SELECT COUNT(*) AS TOTAL FROM _category WHERE section_id = '".$sectionId."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]['TOTAL'];
        }
        elseif($opt=="byId"){
            if ($sectionId){
                $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_id = '".$this->catId."'";
            } else {
                $sql = "SELECT * FROM _category WHERE cat_id = '".$this->catId."'";
            }
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data?$data[0]:NULL;
        }
        elseif($opt=="byName"){
            $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND LOWER(cat_name) = '".strtolower($this->catName)."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="byAlias"){
            $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_alias = '".$this->catAlias."'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0];
        }
        elseif($opt=="parent0"){
            $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_parent = '0' AND cat_status='1'";
            if($desc!=""){
                $sql .= " AND cat_desc = '".$desc."' ";
            }
            $sql .= " ORDER BY cat_order ";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byParent"){
            $sql = "SELECT * FROM _category 
					WHERE section_id = '".$sectionId."' AND cat_parent = '".$this->catParent."' AND cat_status = '1'";
            if($desc!=""){
                $sql .= " AND cat_desc = '".$desc."'";
            }
            $sql .= " ORDER BY CAST(`cat_root` AS SIGNED)";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="byRoot"){
            $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_parent = '".$this->catRoot."' AND cat_status = '1'";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
        elseif($opt=="nameByRoot"){
            $catName = "";
            if($this->catRoot!=""){
                $sql = "SELECT cat_name FROM _category WHERE cat_id IN (".$this->catRoot.")";
                $query = $this->db->query($sql);
                $data = $query->result_array();
                for($i=0;$i<count($data);$i++){
                    $catName .= $data[$i]['cat_name'];
                    if($i<count($data)-1) $catName .= ", ";
                }
            }
            return $catName;
        }
        elseif($opt=="allByCatParent"){
            $sql = "SELECT b.* 
                    FROM _category b 
                    WHERE b.cat_parent IN (
                      SELECT a.cat_id 
                      FROM _category a 
                      WHERE a.section_id ='".$sectionId."' AND a.cat_parent='".$this->catParent."')";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        }
    }


    function get_menu_tree($sectionId,$parentId=0,$groupId=""){
        $sql = "SELECT * FROM _category WHERE section_id = '".$sectionId."' AND cat_parent = '".$parentId."' ";
        if(intval($groupId)>0){
            $sql .= " AND cat_desc = '".$groupId."' ";
        }
        $sql .= " ORDER BY cat_order "; //echo $sql;exit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(count($data)>0){
            echo '<ol class="dd-list">';
            foreach($data as $dataCat){
                echo '<li class="dd-item dd3-item" data-id="'.$dataCat['cat_id'].'">';
                echo '	<div class="dd-handle dd3-handle tooltips" data-toggle="tooltip" data-original-title="Drag untuk merubah urutan"></div>';
                echo '	<div class="dd3-content">';
                if($dataCat['cat_image']!=""){
                    echo'<img src="'.MEDIA_IMAGE_HOST.'/'.$dataCat['cat_image'].'" width="16" style="margin:-3px 10px 0 0;">';
                }
                echo '<span class="name">'.ucwords($dataCat['cat_name'])."</span>";
                echo '<span class="pull-right">
								<span class="label-status" style="margin-right:10px;">'.label_status(status_name($dataCat['cat_status'])).'</span>
								<a href="#editCat-'.$dataCat['cat_id'].'" data-toggle="modal" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp; 
								<a href="#delCat-'.$dataCat['cat_id'].'" data-toggle="modal" title="Hapus"><i class="fa fa-trash-o"></i></a>
								<!-- Modal -->
								<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editCat-'.$dataCat['cat_id'].'"  class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
										<form name="editCat" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
											<div class="modal-header">
												<button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
												<h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Kategori</h4>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label class="col-sm-3 control-label"><span class="asterik">*</span> Nama Kategori</label>
													<div class="col-sm-9">
														<input type="text" name="catName" class="form-control" value="'.set_value("catName",$dataCat['cat_name']).'" required>
													</div>
												</div>
												
												<div class="form-group">
										
													<div class="col-md-12">
														<label class="col-sm-3 control-label">Gambar</label>
														<div class="col-md-9">';

                if($dataCat['cat_image']!="" && file_exists(MEDIA_IMAGE_PATH.'/'.$dataCat['cat_image'])){
                    echo'<img src="'.MEDIA_IMAGE_HOST.'/'.$dataCat['cat_image'].'" style="width:60px; height:60px;" />';
                }else{
                    echo'<img src="'.TEMPLATE_HOST.'/images/noimage.gif" style="width:60px; height:60px;" />';
                }
                echo '
														<input type="file" name="catImage" class="form-control filestyle" data-buttonbefore="true" >
														<span class="help-block"><small><i class="md-info"></i> Format gambar: .jpg atau .png</small></span>
														</div>
													</div>
												</div>
												
												<div class="form-group ">
											<label class="col-md-3 control-label"><span class="text-danger">*</span> Status</label>
											<div class="col-md-2">
												<div class="radio radio-custom">
													<input name="catStatus" id="radio1" type="radio" value="1" '.set_radio("catStatus","1",$dataCat['cat_status']).'>
													<label for="radio1"> Publish </label>
												</div>
											</div>
											
											<div class="col-md-9 col-md-push-3">
												<div class="radio radio-custom">
													<input name="catStatus" id="radio2" type="radio" value="0" <'.set_radio("catStatus","0",$dataCat['cat_status']).'>
													<label for="radio2"> Draft </label>
												</div>
											</div>
										</div>
												
												
											</div>											
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-rotate-left"></i> Batal</button>
												<button type="submit" name="editCat" class="btn btn-success"><i class="fa fa-check"></i> Simpan</button>
											</div>
											<input type="hidden" name="catIdEdit" value="'.$dataCat['cat_id'].'" />
											<input type="hidden" name="catNameEdit" value="'.$dataCat['cat_name'].'" />
										</form>
										</div>
									</div>
								</div>
								
								<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="delCat-'.$dataCat['cat_id'].'" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
												<h4 class="modal-title"><i class="fa fa-warning"></i> Konfirmasi</h4>
											</div>
											<div class="modal-body">
												<h4>Anda yakin menghapus Kategori :</h4><br />
												<h3><strong>'.$dataCat['cat_name'].'</strong> ?</h3>
											</div>
											<form name="delCat" action="" method="post">
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-rotate-left"></i> Batal</button>
												<button type="submit" name="delCat" class="btn btn-warning"><i class="fa fa-trash-o"></i> Hapus Data</button>
											</div>
											<input type="hidden" name="catIdDel" value="'.$dataCat['cat_id'].'" />
											<input type="hidden" name="catNameDel" value="'.$dataCat['cat_name'].'" />
											</form>
										</div>
									</div>
								</div>
								<!-- modal -->
							</span>';
                echo '</div>';
                echo $this->get_menu_tree($sectionId,$dataCat['cat_id']);
                echo '</li>';
            }
            echo '</ol>'."\r\n";
        }
    }

    function update_cat_order($dataOrder,$catParent=0,&$catOrder=1){
        foreach($dataOrder as $data){
            $catId = $data->id;
            if($catParent==0){
                $catLevel = 1;
                $catRoot = $catId;
                $lastParent = 0;
            }
            else{
                $this->catId = $catParent;
                $dataParent = $this->select_category("byId",$this->sectionId);
                $catLevel = $dataParent['cat_level']+1;
                $catRoot = $dataParent['cat_root'].",".$catId;
                $lastParent = $catParent;
            }
            $sql = "UPDATE _category 
					SET cat_level = '".$catLevel."', 
						cat_parent = '".$catParent."', 
						cat_root = '".$catRoot."', 
						cat_order = '".$catOrder."'  
					WHERE cat_id = '".$catId."' ";
            $this->execute($sql);
            if(isset($data->children)){
                $catParent = $catId;
                $catOrder++;
                $this->update_cat_order($data->children,$catId,$catOrder);
                $catParent = $lastParent;
                $catOrder--;
            }
            $catOrder++;
        }
    }

    function form_add_category(){
        if(isset($_POST['addCat'])){
            global $asParent;
            $_SESSION['formType']="add";
            $this->catName = ucwords(security($_POST['catName']));
            $this->catAlias = generate_alias($this->catName);
            $this->catDesc = $_SESSION['Admine']['GroupId'];
            $asParent = intval($_POST['asParent']);
            if($asParent=="1"){
                $this->catParent = "0";
                $this->catLevel = "1";
                $this->catRoot = "";
                $this->catOrder = intval($this->get_max_order())+1;
            }
            if($asParent=="0"){
                if(isset($_POST['catParent'])){
                    $arrParent = explode("-",security($_POST['catParent']));
                    $this->catParent = $arrParent[0];
                    $this->catLevel = $arrParent[1]+1;
                    $this->catRoot = $arrParent[2];
                    $this->catOrder = intval($this->get_new_order($this->catParent))+1;
                }
            }

            if($asParent==0 && !isset($_POST['catParent'])){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Silahkan pilih sub kategori.");
                $_SESSION['errotCat'] = "subcat";
            }
            elseif($this->catName==""){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Silahkan isi nama kategori.");
            }
            /*elseif($this->is_cat_exists()===true){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Duplikat kategori <strong>".$this->catName."</strong>.");
            }*/
            else{
                if(isset($_FILES['catImage']) && $_FILES["catImage"]["name"]!=""){
                    $upload_directory 	= "media/image/";
                    $allowedExt 		= array("png","jpg","jpeg","gif");
                    $mimeType			= array("image/jpg","image/png","image/gif");
                    $arrExt 			= explode(".", $_FILES["catImage"]["name"]);
                    $imgExt 			= strtolower(end($arrExt));
                    $this->catImage	= "cat-".time().".".$imgExt;

                    if($_FILES['catImage']['size'] > 2000000){
                        $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Ukuran gambar maksimal 2 MB.");
                    }
                    elseif(!in_array($_FILES["catImage"]["type"],$mimeType) && !in_array($imgExt,$allowedExt)){
                        $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Format gambar yang diijinkan : .jpg, .png dan .gif");
                    }
                    else{
                        move_uploaded_file($_FILES['catImage']['tmp_name'], $upload_directory.$this->catImage);
                    }
                }

                if($_SESSION['TxtMsg']['status']!="0"){
                    if($asParent=="0")
                        $this->reorder(($this->catOrder-1));
                    $this->insert_category();
                    $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Penambahan kategori <strong>".$this->catName."</strong> berhasil.");

                }
            }
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }

    function form_edit_category(){
        if(isset($_POST['editCat'])){
            $_SESSION['formType']="edit";
            $this->catId = intval($_POST['catIdEdit']);
            $detailCat = $this->select_category("byId",$this->sectionId);
            $catName = security($_POST['catNameEdit']);
            $this->catName = ucwords(security($_POST['catName']));
            $this->catAlias = generate_alias($this->catName);
            $this->catDesc = $_SESSION['Admine']['GroupId'];
            $this->catParent = $detailCat['cat_parent'];
            $this->catLevel = $detailCat['cat_level'];
            $this->catRoot = $detailCat['cat_root'];
            $this->catStatus = $_POST['catStatus'];
            $this->catImage = $detailCat['cat_image'];
            if($this->catName==""){
                $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Nama kategori tidak boleh kosong.");
            }
            if($_FILES["catImage"]["name"]!=""){
                $upload_directory 	= "media/image/";
                $allowedExt 		= array("png","jpg","jpeg","gif");
                $mimeType			= array("image/jpg","image/png","image/gif");
                $arrExt 			= explode(".", $_FILES["catImage"]["name"]);
                $imgExt 			= strtolower(end($arrExt));
                $this->catImage	= "cat-".time().".".$imgExt;

                if($_FILES['catImage']['size'] > 2000000){
                    $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Ukuran gambar maksimal 2 MB.");
                }
                elseif(!in_array($_FILES["catImage"]["type"],$mimeType) && !in_array($imgExt,$allowedExt)){
                    $_SESSION['TxtMsg'] = array("status"=>"0","text"=>"Format gambar yang diijinkan .jpg, .png dan .gif");
                }
                else{
                    move_uploaded_file($_FILES['catImage']['tmp_name'], $upload_directory.$this->catImage);
                }
            }

            if($_SESSION['TxtMsg']['status']!="0"){
                $this->update_category();
                $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Kategori <strong>".$catName."</strong> berhasil diperbarui.");
            }
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }

    function form_update_order_category(){
        if(isset($_POST['updateCatOrder'])){
            $_SESSION['formType']="edit";
            $catOrder = json_decode(stripslashes($_POST['catOrder']));
            if(count($catOrder)>0){
                $this->update_cat_order($catOrder);
                $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Urutan kategori berhasil diperbarui.");
                header("Location:".$_SERVER['HTTP_REFERER']);exit;
            }
        }
    }

    function form_delete_category(){
        if(isset($_POST['delCat'])){
            $_SESSION['formType']="delete";
            $this->catId = security($_POST['catIdDel']);
            $catName = security($_POST['catNameDel']);
            $this->delete_category();
            $_SESSION['TxtMsg'] = array("status"=>"1","text"=>"Kategori <strong>".$catName."</strong> telah dihapus.");
            header("Location:".$_SERVER['HTTP_REFERER']);exit;
        }
    }

}