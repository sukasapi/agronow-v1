<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>


    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>
			
            <div class="col-xl-12">
                
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="16px">No</th>
                                        <th class="text-center">NIP</th>
                                        <th class="text-center">Nama</th>
										<th class="text-center">Entitas</th>
										<th class="text-center">Saldo</th>
										<th class="text-center">Order</th>
                                        <th class="text-center">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1;
                                        $str="";
                                        foreach($saldo as $s){
                                         
                                            
                                            $sisa="Rp " . number_format($s['modal'] - $s['pengajuan'], 2, ",", ".");
                                            $modal="Rp " . number_format($s['modal'], 2, ",", ".");
                                            $pengajuan ="Rp " . number_format($s['pengajuan'], 2, ",", ".");

                                            $str.="<tr>";
                                            $str.="<td>".$i."</td>";
                                            $str.="<td>".$s['nip']."</td>";
                                            $str.="<td>".$s['nama']."</td>";
                                            $str.="<td>".$s['entitas']."</td>";
                                            $str.="<td>".$modal."</td>";
                                            $str.="<td>".$pengajuan."</td>";
                                            $str.="<td>".$sisa."</td>";
                                            $str.="</tr>";
                                            $i++;
                                        }
                                        echo $str;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--end: Datatable -->
                    </div>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>

        </div>
    </div>
    <!-- end:: Content -->


</div>


<script>
   $( document ).ready(function() {
        $("#kt_table").DataTable({
         dom: 'Bfrtip',
         buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
        });

   })
</script>