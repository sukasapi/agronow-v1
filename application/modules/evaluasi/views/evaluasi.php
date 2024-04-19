<?php $this->load->view('learning/app_header'); ?>
<div id="appCapsule">
    <div class="section full">
        <div class="p-2 mb-1">
            <div class="d-flex mt-2 justify-content-center">
                <div class="card">
                    <article class="card-body">
                        <h4 class="card-title text-center mb-4 mt-1">Masuk</h4>
                        <hr>
                        <h3 class="text-center">Selamat Datang di Evaluasi Produk</h3>
                        <p class='text-center'>Silahkan masukkan PIN produk untuk memulai evaluasi</p>
                        <?php 
                                if($this->session->flashdata('info')){
                                    echo "<p class='text-center' style='color:red'>".$this->session->flashdata('info')."</p>";
                                }else{

                                }
                        ?>
                    <br>
                        <form method="post" action="<?=base_url('evaluasi/kelas')?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-key"></i> </span>
                                    </div>
                                    <input name="pin" class="form-control" placeholder="Masukkan PIN produk" type="text">
                                </div> <!-- input-group.// -->
                            </div> <!-- form-group// -->
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-block" value="Konfirmasi">
                            </div> <!-- form-group// -->
                        </form>
                    </article>
                    </div> <!-- card.// -->
            </div>  
            
        </div>
    </div>

</div>