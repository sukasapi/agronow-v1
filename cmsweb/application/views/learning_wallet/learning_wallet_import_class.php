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
        <div class="row">
            
            <?php
                $this->load->view('flash_notif_view');
            ?>
             <div class="col-md-12 col-xs-12">
                <div class="kt-portlet">                
                    <div class="kt-portlet__body">
                         <div class="row mb-4">
                            <div class="col-md-12 col-xs-12 text-center mb-4">
                                <h4>Import Kelas</h4>
                            </div>
                            <div class="col-md-12 col-xs-12 text-center">
                                <div class="alert alert-warning alert-dismissible fade show">
                                   Pastikan bahwa file yang diimport sesuai dengan format yang ditentukan 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                
                            </div>
                            <div class="col-md-12 col-xs-12 text-center">
                                    <div class="form-group">
                                        <label for="filenya">File Excel Import</label>
                                        <input type="file" name="filex" id="filex" class="form-control">
                                        <div id="pesan"><small><i>* Pastikan file bertipe .xlsx</i></small></div>
                                    </div>
                                    <button class="btn btn-rounded btn-primary" id="goimport">Import file ini</button>
                            </div>
                         </div>
                    </div>
                </div>
             </div>
        </div>
    </div>



<?php ?>


<script>
$(document).ready(function() {
    var baseUrl='<?=base_url()?>';
    $("#goimport").hide();
    $("#filex").on("change",function(e){
        e.preventDefault();
        var file = this.files[0];
            var fileType = file.type;
            var match = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv'];

           if(!((fileType == match[0]) || (fileType == match[1]))){
            Swal.fire({
                            type: 'error',
                            title: 'Upss',
                            text: 'Pastikan tipe file yang akan diimport berupa XLSX',
                            });

            }else{
                $("#goimport").show();
            }
    })

    $("#goimport").on("click",function(e){
        e.preventDefault();
        var filex=$("#filex")[0].files[0];
        var urlimport=baseUrl+'learning_wallet/uploadexcel_lw';
                var data = new FormData();
                data.append("filex",filex);
                $.ajax({
                    type: "POST",
                     enctype: 'multipart/form-data',
                    url: urlimport,
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                    }
                })
    })
})


</script>