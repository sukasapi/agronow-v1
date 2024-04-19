<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Modul
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label>Keterangan</label>
                                <textarea id="content" name="Desc" style="min-height: 400px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('Desc');}else{echo isset($request) ? $request['Desc'] : '';} ?></textarea>

                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('classroom/module/').$classroom['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->

                <!-- START PORTLET MODUL -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Modul
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('classroom/module_add/'.$classroom['cr_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah Modul
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET MODUL -->

                <div id="sortable">

                <?php if ($request['Module']): ?>
                <?php $no=0; foreach ($request['Module'] as $k => $v): ?>
                <!-- START PORTLET MODUL -->
                <div id="<?= $k ?>" class="kt-portlet kt-portlet--border-bottom-primary kt-portlet--collapse">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <p class="kt-portlet__head-titles text-primary pt-3">
                                Modul <?= $no+1 ?> : <?= $v['ModuleName'] ?>
                                <br>
                                <small><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?= isset($v['ModuleStart'])?parseDateShortReadable($v['ModuleStart']):'' ?> s/d <?= isset($v['ModuleEnd'])?parseDateShortReadable($v['ModuleEnd']):'' ?></small>
                                <br>
                                <small><i class="fa fa-video"></i>&nbsp;&nbsp;<a class="text-success" target="_blank" href="<?= isset($v['ModuleLinkZoom'])?$v['ModuleLinkZoom']:'' ?>"><?= isset($v['ModuleLinkZoom'])?$v['ModuleLinkZoom']:'' ?></a> </small>
                            </p>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('classroom/module_edit/'.$classroom['cr_id']).'/'.$k; ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-edit"></i> Edit Modul
                                </a>

                                <a href="<?php echo site_url('classroom/module_delete/'.$classroom['cr_id']).'/'.$k; ?>" class="btn btn-clean btn-sm  btn-icon-md d-none" onclick="return confirm('Anda yakin menghapus Modul?')">
                                    <i class="flaticon2-trash"></i> Hapus
                                </a>

                                <!--<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-default btn-pill btn-icon-md" aria-describedby="tooltip_1kn8v1cdf1"><i class="la la-angle-down"></i></a>
                                -->
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <table class="table table-striped table-bordered table-hover nowrap" id="kt_table">
                            <tbody>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url('classroom/module_materi/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                        <i class="fa fa-chevron-right"></i> MATERI MODUL <?= $no+1 ?>
                                    </a>
                                </td>
                                <td></td>
                            </tr>
                            <?php if ($classroom['cr_has_learning_point']!=1): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url('classroom/module_evaluasi/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                        <i class="fa fa-chevron-right"></i> EVALUASI MODUL <?= $no+1 ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-<?= isset($v['Evaluasi']['Status'])&& $v['Evaluasi']['Status'] =='active'?'success':'warning' ?> badge-pill">
                                        <?= isset($v['Evaluasi']['Status'])?trim($v['Evaluasi']['Status']):'' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php if ($classroom['cr_has_learning_point']==1): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo site_url('classroom/module_learningpoint/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                            <i class="fa fa-chevron-right"></i> LEARNING POINT MODUL <?= $no+1 ?>
                                        </a>
                                    </td>
                                    <td>
                                    <span class="badge badge-<?= isset($v['LearningPoint']['Status'])&& $v['LearningPoint']['Status'] =='active'?'success':'warning' ?> badge-pill">
                                        <?= isset($v['LearningPoint']['Status'])?trim($v['LearningPoint']['Status']):'' ?>
                                    </span>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td>
                                    <a href="<?php echo site_url('classroom/module_feedback/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                        <i class="fa fa-chevron-right"></i> FEEDBACK MODUL <?= $no+1 ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $v['Feedback']['Status']=='active'?'success':'warning' ?> badge-pill">
                                        <?= trim($v['Feedback']['Status']) ?>
                                    </span>
                                </td>
                            </tr>
                                <?php 
                                    if(isset($v['Assignment'])){
                                        if($v['Assignment']=="ya"){
                                            $stat="active";
                               
                                            ?>
                                          <tr>
                                              <td>
                                                  <a href="<?php echo site_url('classroom/module_edit/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                                      <i class="fa fa-chevron-right"></i> ASSIGNMENT MODUL <?= $no+1 ?>
                                                  </a>
                                              </td>
                                              <td>
                                                  <span class="badge badge-<?= $v['Assignment']==Null?'warning':'success' ?> badge-pill">
                                                      <?= trim($stat) ?>
                                                  </span>
                                              </td>
                                          </tr>
                                            <?php
                                        }else{
                                            $stat="non-active"; 
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo site_url('classroom/module_edit/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                                        <i class="fa fa-chevron-right"></i> ASSIGNMENT MODUL <?= $no+1 ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?= $v['Assignment']==Null?'warning':'success' ?> badge-pill">
                                                        <?= trim($stat) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                              <?php
                                        }
                                    }else{
                                        $stat="non-active"; 
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo site_url('classroom/module_edit/'.$classroom['cr_id']).'/'.$k; ?>" class="">
                                            <i class="fa fa-chevron-right"></i> ASSIGNMENT MODUL <?= $no+1 ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning badge-pill">
                                            <?= trim($stat) ?>
                                        </span>
                                    </td>
                                </tr>
                                  <?php
                                    }
                                ?>
                        
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- END PORTLET MODUL -->
                <?php $no++; endforeach; ?>
                <?php endif; ?>

                </div>

                <?php /* if (isset($request['Module'])): ?>
                    <small>Drag & Drop modul untuk mengubah urutan</small>
                    <br><br>
                    <form class=" <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" action="<?php echo site_url('classroom/module_update_tree/'.$classroom['cr_id']); ?>" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <input type="hidden" id="inputOrder" name="order" />
                            <button type="submit" id="saveMenu" class="btn btn-brand btn-sm"><i class="fa fa-network-wired"></i> Simpan Struktur</button>
                        </div>
                    </form>
                <?php endif; */ ?>

            </div>


        </div>
    </div>

</div>




<script>
    $(document).ready(function(){

        // Delete
        $('.remove-module').click(function(){
            var el = this;

            // Classroom id
            var classroomId = $(this).data('classroom-id');
            var moduleId = $(this).data('module-id');

            var confirmalert = confirm("Are you sure?");

            if (confirmalert == true) {
                // AJAX Request
                $.ajax({
                    url: 'remove.php',
                    type: 'POST',
                    data: { cr_id:classroomId , crs_id:moduleId },
                    success: function(response){

                        if(response == 1){
                            // Remove row from HTML Table
                            $('#module-'+moduleId).css('background','tomato');
                            $('#module-'+moduleId).fadeOut(400,function(){
                                $(this).remove();
                            });
                        }else{
                            alert('Invalid ID.');
                        }

                    },
                    error:function(){
                        alert("Network error");
                    }
                });
            }

        });

    });
</script>



<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>




<link href="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<style>
    /*.ui-sortable-helper {
        display: table;
    }*/
</style>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script>

    var arr = $.map($('#sortable').find('div'), function(el) {
        return $(el).attr('id') ;
    });
    var arrJson = JSON.stringify(arr);
    $('#inputOrder').val(arrJson);
    console.log(arrJson);
    $( "#sortable" ).sortable({
        stop: function(e, ui) {
            arr = $.map($(this).find('div'), function(el) {
                return $(el).attr('id') ;
            });
            arrJson = JSON.stringify(arr);
            $('#inputOrder').val(arrJson);
            console.log(arrJson);
            /*console.log($.map($(this).find('tr'), function(el) {
                return $(el).index() + ' = ' +$(el).attr('id') ;
            }));*/
        }
    });
    $( "#sortable" ).disableSelection();




</script>


