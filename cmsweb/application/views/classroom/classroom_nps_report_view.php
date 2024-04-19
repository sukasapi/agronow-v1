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
            <div class="col-md-12 col-xs-12">
                <div class="alert alert-warning" role="alert">
                <p>  Fitur ini masih dalam tahap uji coba</p>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12 col-xs-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter</h4>
                        </div>
                        <div class="card-body">
                            <?php 
                                $dstart=$start!=""?$start:"";
                                $dend=$end!=""?$end:"";
                            ?>
                            <form id="filter" action="<?=base_url('classroom/npsreport')?>" method="POST" enctype="multipart/form-data">
                                <div class="row mb-2">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="start">Tanggal Mulai</label>
                                            <input type="date"class="form-control" id="startDate" name="startDate" value="<?=$start?>"> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="end">Tanggal Selesai</label>
                                            <input type="date"class="form-control" id="endDate" name="endDate" value="<?=$end?>"> 
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" name="filter" value="Filter" class="btn btn-success btn-rounded btn-block">
                            </form>
                        </div> 
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12 my-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8 col-xs-12 align-middle my-auto" >
                                <h5>NPS Report</h5>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div id="menu" class="float-right mb-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="tbnps">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Penyelenggaraan</th>
                                        <th>Sarana</th>
                                        <th>Narasumber/Pengajar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $no=1;
                                        $str="";
                                        $idx=0;
                                        $kelas="";
                                        foreach($result as $kelas=>$data){
                                                $dpenyelenggaraan=isset($data['penyelenggaraan']['nilai'])?$data['penyelenggaraan']['nilai']:"0";
                                                $dsarana=isset($data['sarana']['nilai'])?$data['sarana']['nilai']:"0";
                                                $dnarasumber=isset($data['narasumber']['nilai'])?$data['narasumber']['nilai']:"0";
                                                $dexternal=isset($data['external']['nilai'])?$data['external']['nilai']:"0";
                                                $str.="<tr>";
                                                $str.="<td>".$no."</td>";
                                                $str.="<td>".$kelas."</td>";
                                                $str.="<td>".$dpenyelenggaraan."</td>";
                                                $str.="<td>".$dsarana."</td>";
                                                $str.="<td> <button class='btn btn-info btn-sm'>".$dnarasumber."</button></td>";
                                                $str.="</tr>";
                                                $no++;   
                                        }
                                        echo $str;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align:right">Total:</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

</div>

<script>
     $( document ).ready(function() {
        $("#startDate").on("change", function(){
            $("#endDate").attr("min", $(this).val());
        });

    

        $("#tbnps").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        //titleAttr: 'Excel',
                        title:'LAPORAN NPS - EXCEL - LPPAN',
                        className: 'btn btn-success btn-md mr-2 btn-excel',
                        footer: true
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        //titleAttr: 'PDF',
                        title:'LAPORAN NPS - PDF - LPPAN',
                        className: 'btn btn-danger btn-md mr-2 btn-pdf',
                        footer: true
                    },
            ]
            ,
            footerCallback :function(row, data, start, end, display){
                let api = this.api();
                
                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
                };

                //jdata
                jtotal =api.rows().count();
                jpage =api.rows({page:'current'}).count();

                // Total over all pages
             totalpenyelenggaraan = api
            .column(2)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);

            tpPage=api
            .column(2,{page:'current'})
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);

           

            avgpenyelenggaraan = totalpenyelenggaraan / jtotal;
            avgpenyelenggaraan_page = tpPage / jpage;

            totalsarana = api
            .column(3)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            tsPage = api
            .column(3,{page:'current'})
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
           
            avgs_total = totalsarana/jtotal;
            avgs_page  = tsPage/jpage;


            totalnarsum = api
            .column(4)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);

            tnPage = api
            .column(4,{page:'current'})
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);

            avgn_total = totalnarsum/jtotal;
            avgn_page  = tnPage/jpage;

            // Update footer
            api.column(2).footer().innerHTML = avgpenyelenggaraan_page.toFixed(2)+"<br>(rata total :" + avgpenyelenggaraan.toFixed(2) + ")";
            api.column(3).footer().innerHTML = avgs_page.toFixed(2) +"<br>(rata total :" + avgn_page.toFixed(2) + ")";
            api.column(4).footer().innerHTML = avgn_page.toFixed(2) +"<br>(rata total :" + avgn_total.toFixed(2) + ")";
            },
        }).buttons().container().appendTo("#menu");
    })
</script>