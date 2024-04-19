<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("survey"); ?>" class="btn kt-subheader__btn-primary">
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
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $survey;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">


                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Pertanyaan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('survey/question_add/'.$survey['survey_id']); ?>" class="btn btn-outline-info btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">


                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50%">Pertanyaan</th>
                                <th>Model</th>
                                <th>Tipe Multiple Choice</th>
                                <th width="120px"></th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                            <?php foreach ($request as $k => $v): ?>
                            <tr id="<?= $k ?>">
                                <td><?= $v['Question'] ?></td>
                                <td><?= $v['Model']=='multiple-choice'?'MULTIPLE CHOICE':'ESSAY' ?></td>
                                <td>
                                    <?php
                                    if ($v['Type']=='text'){echo 'TEXT';}
                                    if ($v['Type']=='image'){echo 'IMAGE';}
                                    if ($v['Type']=='text-image'){echo 'TEXT IMAGE';}
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('survey/question_edit/'.$survey['survey_id']).'/'.$k; ?>" class="btn btn-outline-info btn-sm ">
                                        Edit
                                    </a>

                                    <a href="<?php echo site_url('survey/question_delete/'.$survey['survey_id']).'/'.$k; ?>" class="btn btn-outline-danger btn-sm btn-icon ml-2" onclick="return confirm('Anda yakin menghapus Pertanyaan?')" title="Hapus">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>

                        </table>


                        <?php if (isset($request)): ?>
                            <small>Drag & Drop baris untuk mengubah urutan</small>
                            <br><br>
                            <form action="<?php echo site_url('survey/question_update_tree/'.$survey['survey_id']); ?>" method="post" accept-charset="utf-8">
                                <div class="form-group">
                                    <input type="hidden" id="inputOrder" name="order" />
                                    <button type="submit" id="saveMenu" class="btn btn-brand btn-sm"><i class="fa fa-network-wired"></i> Simpan Struktur</button>
                                </div>
                            </form>
                        <?php endif; ?>


                    </div>
                </div>
                <!-- END PORTLET QUESTION -->


            </div>


        </div>
    </div>

</div>





<link href="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<style>
    .ui-sortable-helper {
        display: table;
    }
</style>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script>

    var arr = $.map($('#sortable').find('tr'), function(el) {
        return $(el).attr('id') ;
    });
    var arrJson = JSON.stringify(arr);
    $('#inputOrder').val(arrJson);
    console.log(arrJson);
    $( "#sortable" ).sortable({
        stop: function(e, ui) {
            arr = $.map($(this).find('tr'), function(el) {
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
