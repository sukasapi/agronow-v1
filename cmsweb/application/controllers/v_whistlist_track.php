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
    </div>
<!-- end:: Subheader -->
<!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row my-4">
            <div class="col-md-12 col-xs-12">
                <div class="card shadow" style="border-radius:20px;">
                    <div class="card-header">
                        <h1 class="card-title">Summary Report</h1>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="tbsummary">
                                <thead style="background-color:#40739e;color:white">
                                    <tr>
                                         <th style="color:white">Entitas</th>
                                         <th style="color:white">Peserta Agrowallet</th>
                                         <th style="color:white">Jumlah pemilih agrowallet</th>
                                         <th style="color:white">Whislist</th>
                                         <th style="color:white">Pelatihan diajukan</th>
                                         <th style="color:white">Pelatihan Disetujui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $str="";
                                        foreach($summary as $key=>$s){
                                            $str.="<tr>";
                                            $str.="<td>".$key."</td>";
                                            $str.="<td>".$s['peserta']."</td>";
                                            $str.="<td>".$s['pengajuan']."</td>";
                                            $str.="<td>".$s['whislist']."</td>";
                                            $str.="<td>".$s['approve']."</td>";
                                            $str.="<td>".$s['cancel']."</td>";
                                            $str.="</tr>";
                                        }
                                        echo $str;
                                    ?>
                                </tbody>
                                <tfoot style="background-color:#40739e;color:white">
                                    <tr>
                                        <th style="text-align:right;color:white">Jumlah Keseluruhan</th>
                                        <th style="color:white"></th>
                                        <th style="color:white"></th>
                                        <th style="color:white"></th>
                                        <th style="color:white"></th>
                                        <th style="color:white"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="divider py-1"><hr></div>
        <div class="row justify-content-center mt-4">
            <h1>Report Result</h1>
            <div class="col-md-12 col-xs-12">
                <div class="card shadow" style="border-radius:20px;">
                    <div class="card-header" style="color:#f5f6fa;background-color:#273c75;border-top-left-radius:20px;border-top-right-radius:20px">
                        <h3 class="card-title"> Filter Pencarian </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?=base_url('laporan_tracking_whislist')?>" enctype="multipart/form-data">
                        <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="startDate">Awal Tracking</label>
                                            <input type="date" class="form-control" name="startDate" id="startDate" value='<?=$start?>'>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="endDate">Akhir Tracking</label>
                                            <input type="date" class="form-control" name="endDate" id="endDate"  value='<?=$end?>'>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <input type="submit" name="bfilter" value="filter" class="btn btn-rounded btn-block btn-primary">
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-md-12 col-xs-12">
                <div class="card shadow" style="border-radius:20px">
                    <div class="card-header" style="background-color:#FFF;border-top-left-radius:20px;border-top-right-radius:20px">
                        <div class="row">
                            <div class="col-md-8">
                                <ul class="nav card-header-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="one-tab" data-toggle="tab" href="#Tabwhislist" role="tab" aria-controls="One" aria-selected="true"><h4 style="color:#40739e">Whislist</h4></a>
                                    </li>
                                   
                                        <li class="nav-item">
                                            <a class="nav-link" id="two-tab" data-toggle="tab" href="#Tabapproval" role="tab" aria-controls="Two" aria-selected="false"><h4 style="color:#40739e">Tracking Approval</h4></a>
                                        </li>
                                </ul>  
                            </div>
                            <div class="col-md-4">
                                <div id="menu" class="float-right"></div>
                                <div id="menu2" class="float-right" hidden></div>
                            </div>
                        </div>
                           
                    </div>
                    <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active p-3" id="Tabwhislist" role="tabpanel" aria-labelledby="one-tab">
                                        <h5 class="card-title">Daftar Whislist</h5>
                                        <div class="table-responsive">
                                            <table class="table" style="width:100%" id="tbWhistlist">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Entitas</th>
                                                        <th>NIP</th>
                                                        <th>Nama</th>
                                                        <th>Level</th>
                                                        <th>Kode_Kelas</th>
                                                        <th>Nama_kelas</th>
                                                        <th>Mulai</th>
                                                        <th>Selesai</th>
                                                        <th>Harga</th>
                                                        <th>Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $str="";
                                                        $no=1;
                                                        foreach($whislist as $w){
                                                            $mulai=date('d-m-Y',strtotime($w->mulai));
                                                            $selesai=date('d-m-Y',strtotime($w->selesai));
                                                            $str.="<tr>";
                                                            $str.="<td>".$no."</td>";
                                                            $str.="<td>".$w->entitas."</td>";
                                                            $str.="<td>".$w->NIP."</td>";
                                                            $str.="<td>".$w->Nama."</td>";
                                                            $str.="<td>".$w->level_member."</td>";
                                                            $str.="<td>".$w->kode_kelas."</td>";
                                                            $str.="<td>".$w->nama_kelas."</td>";
                                                            $str.="<td>".$mulai."</td>";
                                                            $str.="<td>".$selesai."</td>";
                                                            $str.="<td>".number_format($w->harga,0,",",".")."</td>";
                                                            $str.="<td>".$w->status."</td>";
                                                            $str.="</tr>";
                                                            $no++;
                                                        }

                                                        echo $str;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                                <div class="tab-pane fade p-3" id="Tabapproval" role="tabpanel" aria-labelledby="one-tab">
                                        <h5 class="card-title">Tracking Approval</h5>
                                        <div class="table-responsive">
                                            <table class="table" style="width:100%" id="tbTrack">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>NIP</th>
                                                        <th>Nama</th>
                                                        <th>Entitas</th>
                                                        <th>Pelatihan</th>
                                                        <th>Mulai</th>
                                                        <th>Selesai</th>
                                                        <th>Harga</th>
                                                        <th>Bulan</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $no=1;
                                                        $str="";
                                                        foreach($tracking as $t){
                                                            $mulai=date('d-m-Y',strtotime($t->mulai));
                                                            $selesai=date('d-m-Y',strtotime($t->selesai));
                                                            $str.="<tr>";
                                                            $str.="<td>".$no."</td>";
                                                            $str.="<td>".$t->nip."</td>";
                                                            $str.="<td>".$t->nama."</td>";
                                                            $str.="<td>".$t->entitas."</td>";
                                                            $str.="<td>".$t->pelatihan."</td>";
                                                            $str.="<td>".$mulai."</td>";
                                                            $str.="<td>".$selesai."</td>";
                                                            $str.="<td>".number_format($t->harga,0,",",".")."</td>";
                                                            $str.="<td>".$t->bulan."</td>"; 
                                                            $str.="<td>".$t->status."</td>";
                                                            $str.="</tr>";
                                                            $no++;
                                                        }
                                                        echo $str;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- end:: Content -->
</div>

<script>
    $(document).ready(function() {
        $("#tbWhistlist").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-rounded btn-md mr-2 btn-excel',
                        title: 'Tracking Whislist Kelas'
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-danger btn-rounded btn-md mr-2 btn-pdf',
                        title: 'Tracking Whislist Kelas'
                    },
            ]
        }).buttons().container().appendTo("#menu");

        $("#tbTrack").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-rounded btn-md mr-2 btn-excel',
                        title: 'Tracking Approval Kelas'
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-danger btn-rounded btn-md mr-2 btn-pdf',
                        title: 'Tracking Approval Kelas'
                    },
            ]
        }).buttons().container().appendTo("#menu2");

        $('#myTab li').on('click', function () {
            var menuklik=$(this).find('a').attr('id');
           
            if(menuklik=='two-tab'){
                $("#menu2").removeAttr('hidden');
                $("#menu").attr('hidden',true);
            }else{
                $("#menu").removeAttr('hidden');
                $("#menu2").attr('hidden',true);
            }
        })

        $("#tbsummary").DataTable({
            dom: 'lBfrtip',
            pageLength: 50,
            info :false,
            LengthChange:false,
            bLengthChange:false,
            buttons: [
                {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i> unduh Summary Excel',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-rounded btn-md mr-2 btn-excel',
                        title: 'Summary Report'
                    },
            ],
            footerCallback: function (row, data, start, end, display) {
                        let api = this.api();
                
                        // Remove the formatting to get integer data for summation
                        let intVal = function (i) {
                            return typeof i === 'string'
                                ? i.replace(/[\$,]/g, '') * 1
                                : typeof i === 'number'
                                ? i
                                : 0;
                        };
                
                        // Total memmber
                        total = api
                            .column(1)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total2 = api
                            .column(2)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total3 = api
                            .column(3)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total4 = api
                            .column(4)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        total5 = api
                            .column(5)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                
                       
                        // Update footer
                        api.column(1).footer().innerHTML = total;
                        api.column(2).footer().innerHTML = total2;
                        api.column(3).footer().innerHTML = total3;
                        api.column(4).footer().innerHTML = total4;
                        api.column(5).footer().innerHTML = total5;
                    }
        })
    })
</script>