<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
            <input type="hidden" id="namapage" value="<?= $page_sub_name;?>">
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
                <div class="alert alert-warning" role="alert">
                * Hanya menampilkan peserta yang telah membuka fitur project assignment di agronow
                </div>
                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Project Assignment Peserta
                            </h3>
                        </div>
                        <?php
                        $fcount=0;
                        foreach($project_assignment as $pa){
                            if($pa->pa_file!=""){
                                $fcount++;
                            }else{

                            }
                        }

                        if($fcount > 0){
                        ?>
                        <div class="actions">  
                           <button class="btn btn-success btn-sm" id="btdownload" data-paclass="<?=$classroom['cr_id']?>">
                                Unduh Project Assignment
                            </button> 
                        </div>
                        <?php
                        }else{

                        }
                        ?>
                      
                    </div>
                    <div class="kt-portlet__body">
                        <div class="table-responsive">
                            <table class="table" id="tb_proAs" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>File</th>
                                        <th>Kelas</th>
                                        <th>Member</th>
                                        <th>Atasan</th>
                                        <th>Perusahaan</th>
                                        <th>Problem</th>
                                        <th>Solusi</th>
                                        <th>Time Frame</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $c=1;
                                        foreach($project_assignment as $as){
                                           $file=$as->pa_file!=""?base_url('media/project_assignment/').$as->pa_file:"-";
                                            switch($as->pa_status){
                                                case 'open':
                                                    $stat="<h4 class='badge badge-primary'>open</h4>";
                                                break;
                                                case 'draft':
                                                    $stat="<h4 class='badge badge-warning'>draft</h4>";
                                                break;

                                                case 'progress':
                                                    if($as->pa_progress >=100){
                                                        $stat="<h4 class='badge badge-success'>selesai</h4>";
                                                    }else{
                                                        $stat="<h4 class='badge badge-warning'>progres</h4>";
                                                    }
                                                break;
                                                case 'cancel':
                                                    $stat="<h4 class='badge badge-danger'>batal</h4>";
                                                break;
                                                case 'final':
                                                    $stat="<h4 class='badge badge-success'>selesai</h4>";
                                                break;
                                                case 'done':
                                                    $stat="<h4 class='badge badge-success'>selesai</h4>";
                                                break;
                                                default:
                                                    $stat="<h4 class='badge badge-secondary'>-</h4>";
                                                break;
                                            }

                                            echo "<tr>";
                                            echo "<td>".$c."</td>";
                                            if($as->pa_file==""){
                                                echo "<td><span class='badge badge-danger'>belum ada file</span></td>";
                                            }else{
                                                echo "<td><a href='".base_url('media/project_assignment/').$as->pa_file."' class='badge badge-info'>".$as->pa_file."</a></td>";
                                            }

                                            $problem=$as->pa_problem!=""?"ok":"-";
                                            $solusi=$as->pa_solution!=""?"ok":"-";
                                            $timeframe=$as->pa_timeframe!=""?"ok":"-";
                                            
                                            echo "<td>".$as->cr_name."</td>";
                                            echo "<td>".$as->nama."</td>";
                                            echo "<td>".$as->nama_atasan."</td>";
                                            echo "<td>".$as->group_name."</td>";
                                            echo "<td>".$problem."</td>";
                                            echo "<td>".$solusi."</td>";
                                            echo "<td>".$timeframe."</td>";
                                            echo "<td>".$as->pa_progress." %</td>";
                                            echo "<td>".$stat."</td>";
                                            echo "</tr>";
                                            $c++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET MEMBER -->
            </div>


        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    var baseUrl='<?=base_url()?>';
    var judul=$("#namapage").val();
    $('#tb_proAs').DataTable({
        scrollX: true,
        pageLength: 20,
        dom: 'Bfrtip',
			buttons: [
				{
                extend: 'excel',
				title: judul
            	},
				{
                extend: 'pdf',
				title: judul
            },

			]
    });
    
    $('#btdownload').on('click',function(e){
        var crid=$(this).data('paclass');
        var urlact = baseUrl+"classroom/download_project_assignment/"+crid;
        location.replace(urlact);
    });
});
</script>