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

            ?>



            <!-- Navigation -->

            <?php

                $submenu_data = $classroom;

                $this->load->view(@$submenu,$submenu_data);

            ?>



            <div class="col-lg-9">

                <!-- START PORTLET MEMBER -->

                <div class="kt-portlet">

                    <div class="kt-portlet__head">

                        <div class="kt-portlet__head-label">

                            <h3 class="kt-portlet__head-title text-primary">

                                Progress Peserta (<?= isset($member_count['total'])?$member_count['total']:0 ?>)

                            </h3>

                        </div>

                        <div class="kt-portlet__head-toolbar">

                            <div class="kt-portlet__head-actions">

                                <a href="<?= site_url("classroom/progress_member_excel/").$classroom['cr_id']; ?>" class="btn btn-outline-info btn-sm mr-2">Export Excel</a>

                            </div>

                        </div>

                    </div>



                    <div class="kt-portlet__body">

                        <div class="table-responsive">



                            <table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">

                                <thead>

                                    <tr>

                                        <th class="text-center" rowspan="2">No</th>

                                        <th class="text-center" rowspan="2">Nama</th>

                                        <th class="text-center" rowspan="2">NIP</th>

                                        <th class="text-center" rowspan="2">Group</th>

                                        <th class="text-center" colspan="4">Pre Test</th>



                                        <?php

                                            $module = array();

                                            if($classroom['cr_module']){



                                                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);

                                                $cr_module = json_decode($json_cr_module,TRUE);



                                                if (isset($cr_module['Module'])){

                                                    $module = $cr_module['Module'];

                                                }

                                            }

                                            $no_module = 0;

                                            foreach ($module as $k => $v):

                                        ?>

                                        <th class="text-center" rowspan="2">Materi Modul <?= $no_module+1 ?></th>
                                        <th class="text-center" rowspan="2">Modul Assignment <?= $no_module+1 ?></th>

                                        <th class="text-center" colspan="2">Learning Point Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" colspan="4">Evaluasi Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" colspan="3">Feedback Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" rowspan="2">Nilai Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" rowspan="2">Progress Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" rowspan="2">Tgl. Mulai Modul <?= $no_module+1 ?></th>

                                        <th class="text-center" rowspan="2">Tgl. Selesai Modul <?= $no_module+1 ?></th>

                                        <?php $no_module++; endforeach; ?>



                                        <th class="text-center" colspan="4">Competency Test</th>

                                        <th class="text-center" rowspan="2">Grade</th>

                                        <th class="text-center" rowspan="2">Nilai Akhir</th>

                                        <th class="text-center" rowspan="2">Knowledge Management</th>

                                        <!--<th class="text-center" rowspan="2">Modul Belum Selesai</th>

                                        <th class="text-center" rowspan="2">Feedback Belum Selesai</th>-->

                                    </tr>

                                    <tr>

                                        <th class="text-center">Tanggal</th>

                                        <th class="text-center">Jml Soal</th>

                                        <th class="text-center">Benar</th>

                                        <th class="text-center">Salah</th>



                                        <?php

                                            $module = array();

                                            if($classroom['cr_module']){



                                                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);

                                                $cr_module = json_decode($json_cr_module,TRUE);



                                                if (isset($cr_module['Module'])){

                                                    $module = $cr_module['Module'];

                                                }

                                            }

                                            foreach ($module as $k => $v):

                                        ?>

                                            <th class="text-center">Tanggal</th>

                                            <th class="text-center">Isi</th>



                                            <th class="text-center">Tanggal</th>

                                            <th class="text-center">Jml Soal</th>

                                            <th class="text-center">Benar</th>

                                            <th class="text-center">Salah</th>





                                            <th class="text-center">Tanggal</th>

                                            <th class="text-center">Nilai</th>

                                            <th class="text-center">Komentar</th>

                                        <?php endforeach; ?>



                                        <th class="text-center">Tanggal</th>

                                        <th class="text-center">Jml Soal</th>

                                        <th class="text-center">Benar</th>

                                        <th class="text-center">Salah</th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php if ($member): ?>

                                <?php

                                    $no=0; foreach ($member as $v):

                                    $crm_step_json = $v['crm_step'];

                                    $result = json_decode($v['crm_step'],TRUE);



                                    if (isset($result['PT']['ptScore']) AND $result['PT']['ptScore']){

                                        $ptScore = explode('-',$result['PT']['ptScore']);

                                    }else{

                                        $ptScore = array('','','','');

                                    }



                                    if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){

                                        $ctScore = explode('-',$result['CT']['ctScore']);

                                    }else{

                                        $ctScore = array('','','','');

                                    }



                                    $content = getContentNoRedirect($v['content_id'],31);



                                ?>

                                    <tr>

                                        <td><?php $no++; echo $no; ?></td>

                                        <td><?= $v['member_name'] ?></td>

                                        <td><?= $v['member_nip'] ?></td>

                                        <td><?= $v['group_name'] ?></td>



                                        <td><?= isset($result['PT']['ptDate']) ? ($result['PT']['ptDate'] ? date('d/m/Y H:i',strtotime($result['PT']['ptDate'])) : '' ) :'' ?></td>

                                        <td class="text-center"><?= $ptScore[1] ?></td>

                                        <td class="text-center"><?= $ptScore[2] ?></td>

                                        <td class="text-center"><?= $ptScore[3] ?></td>



                                        <?php

                                        $module = array();

                                        if($classroom['cr_module']){



                                            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);

                                            $cr_module = json_decode($json_cr_module,TRUE);



                                            if (isset($cr_module['Module'])){

                                                $module = $cr_module['Module'];

                                            }

                                        }



                                        $EvaScoreArr = array();

                                        if($module):
                                        /// tambahan module assignment
                                        $im=0;
                                        foreach ($module as $k => $v):

                                        ?>



                                        <!-- Materi Modul -->

                                        <td>

                                            <?php

                                            $materi_modul = classroomProgressModuleMateri($crm_step_json, $classroom['cr_id'], $k);

                                            if ($materi_modul){

                                                foreach ($materi_modul as $i => $j){

                                                    echo "Materi ".$j['MateriNo']." : ".$j['MateriName']."<br>(".$j['MateriRead'].")<br><br>";

                                                }

                                            }

                                            //print_r($materi_modul);

                                            ?>

                                        </td>
                                       <!---- Module Assignment --->
                                       <td class="text-center"> 
                                            <?php
                                            
                                             
                                                if(isset($v['Assignment']) && $v['Assignment']=="ya"){
                                                   if(isset($module_assignment[$im])){
                                                    
                                                       if($module_assignment[$im]->status_ma!="final"){
                                                        echo "<span class='badge badge-warning'>belum</span>";
                                                       }else{
                                                        echo "<span class='badge badge-success'>ok</span>";
                                                       }
                                                       
                                                   }else{
                                                    echo "<span class='badge badge-warning'>belum</span>";
                                                      
                                                   }
                                                   $im++;
                                                  
                                                }else{
                                                   
                                                   // echo "<span class='badge badge-secondary'>none</span>";
                                                   echo "<span class='badge badge-secondary'>none</span>";
                                                   
                                                  
                                                }
                                              
                                            ?>
                                          
                                        </td>
                                        
                                        <!---- End Module Assignment --->
                                        <!-- Learning Point -->

                                        <td class="text-center"><?= isset($result['MP'][$k]['LearningPoint']['tanggal']) ? ($result['MP'][$k]['LearningPoint']['tanggal'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['LearningPoint']['tanggal'])) : '' ) :'' ?></td>

                                        <td class="text-center"><?= isset($result['MP'][$k]['LearningPoint']['isi']) ? $result['MP'][$k]['LearningPoint']['isi'] : '' ?></td>



                                        <!-- Evaluasi -->

                                        <td><?= isset($result['MP'][$k]['EvaDate']) ? ($result['MP'][$k]['EvaDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['EvaDate'])) : '' ) :'' ?></td>

                                        <?php

                                            if (isset($result['MP'][$k]['EvaScore']) AND $result['MP'][$k]['EvaScore']){

                                                $EvaScore = explode('-',$result['MP'][$k]['EvaScore']);

                                            }else{

                                                $EvaScore = array('','','','');

                                            }

                                        ?>

                                        <td class="text-center"><?= $EvaScore[1] ?></td>

                                        <td class="text-center"><?= $EvaScore[2] ?></td>

                                        <td class="text-center"><?= $EvaScore[3] ?></td>



                                        <!-- Feedback -->

                                        <td class="text-center"><?= isset($result['MP'][$k]['FbDate']) ? ($result['MP'][$k]['FbDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['FbDate'])) : '' ) :'' ?></td>

                                        <td class="text-center"><?= isset($result['MP'][$k]['FbDesc']) ? $result['MP'][$k]['FbDesc'] : '' ?></td>

                                        <td class="text-center"></td>



                                        <!-- Nilai Modul -->

                                        <td class="text-center">

                                            <?php

                                                $EvaScorePercent = ($EvaScore[2] AND $EvaScore[1]) ? ($EvaScore[2]/$EvaScore[1]*100) : '';

                                                if($v['Evaluasi']['Status']=="active") {

                                                    $EvaScoreArr[$k] = $EvaScorePercent ? $EvaScorePercent : 0;

                                                }

                                            ?>

                                            <?= $EvaScorePercent ?>

                                        </td>

                                        <td class="text-center">

                                            <?php

                                            $progress_percent = classroomProgressModulePercent($crm_step_json, $k,$classroom['cr_has_learning_point']);

                                            echo $progress_percent!=0 ? parseThousand($progress_percent).'%' : '';

                                            ?>

                                        </td>

                                        <td class="text-center"><?= isset($result['MP'][$k]['date_access_start']) ? ($result['MP'][$k]['date_access_start'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['date_access_start'])) : '' ) :'' ?></td>

                                        <td class="text-center"><?= isset($result['MP'][$k]['date_access_end']) ? ($result['MP'][$k]['date_access_end'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['date_access_end'])) : '' ) :'' ?></td>





                                        <?php  endforeach; endif; ?>





                                        <td><?= isset($result['CT']['ctDate']) ? ($result['CT']['ctDate'] ? date('d/m/Y H:i',strtotime($result['CT']['ctDate'])) : '' ) :'' ?></td>

                                        <td class="text-center"><?= $ctScore[1] ?></td> 

                                        <td class="text-center"><?= $ctScore[2] ?></td>

                                        <td class="text-center"><?= $ctScore[3] ?></td>



                                        <td class="text-center"><?= isset($result['RESULT']) ? $result['RESULT'] : '' ?></td>

                                        <td class="text-center">

                                            <?php

                                                if ($classroom['cr_has_kompetensi_test']){

                                                    $endScore = $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '';

                                                }else{

                                                    $endScore = $EvaScoreArr ? number_format((array_sum($EvaScoreArr)/count($EvaScoreArr)),1,',','.') : '';

                                                }

                                            ?>

                                            <?= $endScore!='0,0' ? $endScore : '' ?>

                                        </td>

                                        <td class="">

                                            <?php

                                            if($content):

                                            ?>



                                            <a target="_blank" href="<?= site_url('knowledge_sharing/detail/').$content['content_id'] ?>"><?= $content['content_name'] ?></a>



                                            <?php endif; ?>

                                        </td>



                                        <!--<td></td>

                                        <td></td>-->

                                    </tr>

                                <?php endforeach; ?>

                                <?php endif; ?>

                                </tbody>

                            </table>



                        </div>

                    </div>

                </div>
                <!---- Tombol unduh  -->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                                <div class="row">
                                    <div class="col-lg-12 col-xs-12 text-center">
                                        <h4>Unduh Assignment Modul Kelas Ini ?</h4>
                                        <div class="text-center">
                                           
                                            <a href="<?=base_url("classroom/download_module_assignment?cr=".$classroom['cr_id']);?>" class="btn btn-primary">unduh</a>
                                        </div>
                                    </div>
                                </div>
                             
                    </div>
                    
                </div>
                <!-- END PORTLET MEMBER -->

            </div>





        </div>

    </div>



</div>









<script>

    "use strict";

    var KTDatatablesDataSourceAjaxServer = function() {



        var initTable1 = function() {

            var table = $('#kt_table');



            // begin first table

            table.DataTable({

                scrollY: '50vh',

                scrollX: true,

                scrollCollapse: true,

                responsive: false,

                searchDelay: 500,

                processing: true,

                serverSide: false,

                stateSave: true,



                language: {

                    "infoFiltered": ""

                },

                order: [[ 0, "asc" ]],

            });

        };



        return {

            //main function to initiate the module

            init: function() {

                initTable1();

            },

        };



    }();



    jQuery(document).ready(function() {

        KTDatatablesDataSourceAjaxServer.init();

    });

</script>

 





<script>

    function addSelectedItem(selectedItems)

    {

        // console.log(selectedItems);

        var ids = [];

        $.each(selectedItems, function( index, value ) {

            ids.push(value.member_id);

        });



        $.post("<?= site_url('classroom/member_add_picker/'.$classroom['cr_id']); ?>", {member_ids: ids}, function(result){

            var r = $.parseJSON(result);

            alert('Berhasil menambahkan '+r.succ+' member.');

            location.reload();

        });

    }

</script>



