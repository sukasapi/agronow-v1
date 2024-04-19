<link href="<?=PATH_ASSETS?>plugins/surveyjs/defaultV2.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?=PATH_ASSETS?>plugins/surveyjs/survey.jquery.min.js"></script>
<?php $this->load->view('learning/app_header'); ?>
<style type="text/css">
    div.result{
        background-color: #079992;
    }
    div.result h2{
        color: #FFFFFF;
    }
    div.result h5{
        color: #FFFFFF;
        font-weight: normal;
    }
    table.que h4{
        font-weight: normal;
    }
</style>  
<div id="appCapsule">
    <div class="section full mb-5">
       <div class="text-center py-4 my-2 px-2 result">
          <h2>Selamat datang di Survey Kelas Agronow</h2>
          <h5>Survey ini menggunakan metode NPS (Net Promoter Survey).</h5>
       </div>

       <div class=" py-4 my-2 px-2">
            <div class="card">
                <h5 class="card-header bg-warning ">
                    <a data-toggle="collapse" class="text-white" href="#collapse-example" aria-expanded="true" aria-controls="collapse-example" id="heading-example" class="d-block">
                        <i class="fa fa-chevron-down pull-right"></i>
                            Baca panduan penilaian
                    </a>
                </h5>
                <div id="collapse-example" class="collapse" aria-labelledby="heading-example">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Skor</th>
                                        <th>Pengertian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1-6</td>
                                        <td><strong>Detractor :</strong> peserta kelas memberikan penilaian negatif (buruk) terhadap kelas</td>
                                    </tr>
                                    <tr>
                                        <td>7-8</td>
                                        <td><strong>Passive :</strong> peserta kelas memberikan respon tidak positif maupun negatif terhadap kelas</td>
                                    </tr>
                                    <tr>
                                        <td>9-10</td>
                                        <td><strong>Passive :</strong> peserta kelas memberikan positif (baik) terhadap kelas</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>  
                    </div>
                </div>
            </div>
       </div>

       <div class=" py-4 my-2 px-2">
        <div class="card">
            <div class="card-header bg-primary">
            <h3 class="text-center text-white m-2">EVALUASI <?=strtoupper($setsoal[0]->jenis)?> <br>KELAS <?=strtoupper($kelas[0]->cr_name)?></h3>
            </div>
            <div class="card-body">
                <input type='hidden' name='evaluasi' id='evaluasi' value='<?=$setsoal[0]->id?>'>
                <input type='hidden' name='kelas' id='kelas' value='<?=$kelas[0]->cr_id?>'>
                <input type='hidden' name='jenis' id='jenis' value='<?=$setsoal[0]->jenis?>'>
                <input type='hidden' name='tipe' id='tipe' value='<?=$setsoal[0]->tipe?>'>
                <form>
                    <div class="card m-2">
                        <div class="card-body">
                            <div id="surveyContainer"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
       
       </div>
</div>
<script>
   $( document ).ready(function() {
    var url='<?=base_url('learning/evaluasi/')?>';
    var urlback='<?=base_url('learning/evaluasi/nps_list')?>/' + $('#kelas').val();
    var dgrab= grab($('#evaluasi').val());

    function grab(evaluasi){
        var datagrab="";
        var datagrab="";

        $.ajax({
            url: url+'grabquiz',
            type: 'GET',
            data:{'evaluasi':evaluasi},
            async:false,
            success: function(response) {
                var respon=JSON.parse(response);
                var dataeval=respon.data;
                datagrab=dataeval
            }
         });
      return(datagrab);
    }
    
    Survey.StylesManager.applyTheme("defaultV2");
    const surveyJson = {
            widthMode: "responsive",
            pageNextText: "Selanjutnya",
            pagePrevText: "kembali",
            completeText: "Simpan",
            showProgressBar: "bottom",
            pages: dgrab,
            completedHtml: "Terima kasih atas Feedback Anda.<br> <a class='btn btn-rounded btn-primary' href='"+urlback+"'>Keluar</a>",
    }

    const survey = new Survey.Model(surveyJson);

    $("#surveyContainer").Survey({ model: survey });

        
    function sendResult(sender){
        const results = JSON.stringify(sender.data);
       var data = new FormData();
        var evaluasi =$('#evaluasi').val();
        var kelas =$('#kelas').val();
        var jenis =$('#jenis').val();
        var tipe =$('#tipe').val();
        data.append("soal",evaluasi);
        data.append("kelas",kelas);
        data.append("data",results);
        data.append("jenis",jenis);
        data.append("tipe",tipe);
      $.ajax({
            url: url+'savequiz',
            type: "POST",
            enctype: 'multipart/form-data',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (response) {
                console.log(response);
            }
        })
        
    }
        

    survey.currentPage = "myCurrentPage";
    survey.onComplete.add(sendResult);
    });
</script>