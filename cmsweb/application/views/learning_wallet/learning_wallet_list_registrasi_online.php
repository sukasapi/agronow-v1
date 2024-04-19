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
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>

			<!--
            <?php if(has_access('member.create',FALSE) OR has_access_manage_all_member()): ?>
            <a href="<?php echo site_url("member/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>
			<?php endif; ?>
			-->

        </div>


    </div>
    <!-- end:: Subheader -->

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!-- FILTERS -->
        <div class="row py-4">
            <div class="col-md-12 col-xs-12">
                    <div class="card" style="background-color:#303952">
                        <div class="card-body">
                            <div class="text-center">
                            <h4 class="text-white">Registrasi Peserta Kelas</h4>
                            </div>
                            <div class="form-group">
                                <label class="text-white" for="kelas">1. Pilih Kelas dimana peserta teregistrasi</label>
                                <select name="kelas" id="kelas" class="form-control">
                                    <option selected readonly disabled>Daftar Kelas Non Reguler</option>
                                    <?php 
                                       
                                        foreach($kelas as $k){
                                            $txt = "<b>(".$k->kode.")</b> - ".$k->nama;
                                            if($k->id == $select){
                                                echo "<option selected value='".$k->id."'>".$txt."</option>";
                                            }else{
                                                echo "<option value='".$k->id."'>".$txt."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-white" for="file"> 2. Upload File bertipe <b>.csv</b> berisi daftar peserta kelas </label> <p class="text-white text-center alertinput" hidden><b>UPLOAD GAGAL</b> <i> **** Format file bukan <b>csv</b> ****</i></p>
                                <input  type="file" class="form-control" name="file" id="upfile">
                                <small class="text-white alertinfo"><i>* hanya menerima file .csv dengan pemisah titik koma(;)</i></small>
                                <small class="text-white infoupload" hidden><i>Read On Progress ...</i></small>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-warning btn-rounded" id="btrefresh"> Registrasi Baru</button>
                            </div>
                        </div>
                    </div>         
              
            </div>
        </div>
        <div class="row py-auto">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h4 >Daftar Peserta Kelas Agrowallet Non Reguler</h4>
                        </div>
                        <div class="table-responsive" id="registrasi_list">
                            <div id="resultparse"></div>
                        </div>
                        <div class="pt-4 float-right">
                            <button hidden class="btn btn-rounded btn-primary" id="btregis" >Registrasi Peserta</button>
                        </div>

                        <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){
        var base_url='<?=base_url()?>';
        $('#listpeserta').DataTable();
        
        $("#upfile").on("change",function(e){
            e.preventDefault();

            $('#resultparse').html("");
            var files = $(this)[0].files;
            var len = $(this).get(0).files.length;
            var ext = files[0].name.split('.').pop().toLowerCase();
            if(ext != "csv"){

                $('.alertinput').removeAttr('hidden');
                $('.alertinfo').attr('hidden',true);
                $('.msg').attr('hidden',true);
                $('#btadd').attr('disabled',true);

                    Swal.fire({
                    type: "error",
                    title: "Oops...",
                    text: "Format file bukan CSV", 
                    confirmButtonColor: "#3085d6",
                    });

            }else{
                    $('.alertinput').attr('hidden',true);
                    $('.alertinfo').removeAttr('hidden');
                    $('.msg').attr('hidden',true);

                    var dataparse = $('input[type=file]').parse({
                        config: {
                                    delimiter: ";",
                                    complete: function(results){
                                                var data=results;
                                                displayHTMLTable(data)
                                    },//displayHTMLTable(results),
                                    header:true,
                                    skipEmptyLines: true,
                                },
                                before: function(file, inputElem)
                                {
                                     $('#wait').removeAttr('hidden');
                                },
                                    error: function(err, file)
                                {
                                    alert("ERROR:", err, file);
                                },
                                complete: function()
                                {
                                   $('#wait').attr('hidden',true);
                                   $('#btregis').removeAttr('hidden');
                                }
                    })
            }
        })

        $("#btregis").on("click",function(e){
            var kelas =$('#kelas').val();

            if(kelas ==""){
                Swal.fire({
                    type: "error",
                    title: "Oops...",
                    text: "kelas agrowallet belum dipilih", 
                    confirmButtonColor: "#3085d6",
                    });
            }else{
                 var datapeserta =tabletoarray();
                 var registerurl=base_url + 'learning_wallet/ajax_import_regis';
                 var csrfName = $('.txt_csrfname').attr('name');
                 var csrfHash = $('.txt_csrfname').val();

                 var pesan="<ul>";
                 $.ajax({
                    url  : registerurl,
                    type : 'POST',
                    dataType: 'json',
                    data : {datapeserta, kelas : kelas, [csrfName]: csrfHash},
                    
                    success: function(result){
                        //var res=JSON.parse(result);
                        for(var i=0; i<result.length;i++){
                            if(result[i].status=="gagal"){
                                pesan +="<li>"+ "<p class='text-danger'><strong>" + result[i].status + "</strong> " + result[i].pesan +"</p></li>";
                            }else{
                                pesan +="<li>"+ "<p class='text-success'><strong>" + result[i].status + "</strong> " + result[i].pesan +"</p></li>";
                            }
                           
                        }
                        pesan +="</ul>";
                       // $('#resultparse').html("");
                        //$('#resultparse').html(pesan);
                        Swal.fire({
                            type: "success",
                            title: "Registrasi selesai",
                            html: pesan, 
                            confirmButtonColor: "#3085d6",
                        });
                       // console.log(result[0].status)
                    }    

                 })
               
            }
        })

        $("#btrefresh").on("click",function(e){
            location.reload();
        })

        


        function displayHTMLTable(results){

                $('#result').removeAttr('hidden');
                //   var table = "  <hr><h5>Data Result</h5><div class='table-responsive'><table id='tbresult' class='table table-bordered'>";

                var dtparse=results.data;

                // EXTRACT VALUE FOR HTML HEADER 
                const header = Object.keys(dtparse[0]);         

                // CREATE DYNAMIC TABLE.
                const table = document.createElement("table");
                table.setAttribute("class", "table table-bordered");
                table.setAttribute("id", "result");

                // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.
                let tr = table.insertRow(-1);                   // TABLE ROW.
                for (let i = 0; i < header.length; i++) {
                    const th = document.createElement("th");      // TABLE HEADER.
                    th.innerHTML = header[i];
                    tr.appendChild(th);
                }

               // ADD JSON DATA TO THE TABLE AS ROWS.
                for (let i = 0; i < dtparse.length; i++) {
                tr = table.insertRow(-1);
                    for (let j = 0; j < header.length; j++) {
                        let tabCell = tr.insertCell(-1); 
                        tabCell.innerHTML = dtparse[i][header[j]];
                    }
                }

                //  table +="</table></div>";
                const divContainer = document.getElementById("resultparse");
                divContainer.appendChild(table);
        }

        function readtable(){

                var convertedIntoArray = [];

                var dtlength=0;

                $("table#tbresult tr").each(function() {

                        var rowDataArray = [];

                        var actualData = $(this).find('td');

                        dtlength=actualData.length;

                        if (dtlength > 0) {

                            actualData.each(function() {

                                rowDataArray.push($(this).text());

                            });

                            convertedIntoArray.push(rowDataArray);

                        }

                });

                return dtlength;

        }

        function tabletoarray(){

            var rows = [].slice.call($('#result')[0].rows)
            var keys = [].map.call(rows.shift().cells, function(e) {
                return e.textContent.replace(/\s/g, '')
            })

            var result = rows.map(function(row) {
                return [].reduce.call(row.cells, function(o, e, i) {
                    o[keys[i]] = e.textContent
                    return o 
                }, {})

            })
            return result
        }



    
    });
    </script>