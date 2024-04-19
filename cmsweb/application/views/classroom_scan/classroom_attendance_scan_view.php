<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>

    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>


            <div class="col-lg-12">
                <div class="alert alert-info fade show" role="alert">
                    <div class="alert-icon">
                        <i class="fa fa-info-circle"></i>
                    </div>
                    <div class="alert-text">QR Code hanya muncul pada classroom berjenis <b>in class training</b></div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>


            <div class="col-lg-12">

                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Scan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <form id="formBarcode" action="<?= $form_action ?>" method="post">
                            <div class="form-group">
                                <input type="text" name="barcode" class="form-control" id="barcode" onmouseover="this.focus();" autocomplete="false">
                            </div>
                        </form>

                    </div>
                </div>
                <!-- END PORTLET MEMBER -->

                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Data Absensi Hari Ini
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
						<div class="alert alert-primary">
							rekomendasi keyword untuk pencarian:<br/>
							<ul>
								<li><b>NIK/Nama</b>: untuk mencari data dari NIK/Nama tertentu</li>
								<li><b>[ID_CLASSRROM]</b>: untuk mencari kelas spesifik</li>
								<li><b>masuk [ID_CLASSRROM]</b>: untuk mencari presensi masuk dari kelas spesifik</li>
							</ul>
						</div>
						
                        <div class="table-responsive">

                            <table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">
                                <thead>
                                    <tr>
                                        <th>Waktu Absensi</th>
										<th>Presensi</th>
                                        <th>Nama</th>
                                        <th>Group</th>
                                        <th>Class Room</th>
                                    </tr>
                                </thead>

                            </table>

                        </div>
                    </div>
                </div>
                <!-- END PORTLET MEMBER -->

            </div>


        </div>
    </div>

</div>


<!--MODAL DIALOG-->
<div id="modal-attendance" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <dl class="row">
                    <dt class="col-sm-3">Nama</dt>
                    <dd class="col-sm-9">: <span id="ma-name"></span></dd>

                    <dt class="col-sm-3">NIP</dt>
                    <dd class="col-sm-9">: <span id="ma-nip"></span></dd>

                    <dt class="col-sm-3">Group</dt>
                    <dd class="col-sm-9">: <span id="ma-group"></span></dd>

                    <dt class="col-sm-3">Classroom</dt>
                    <dd class="col-sm-9">: <span id="ma-classroom"></span></dd>

                    <dt class="col-sm-3">Waktu Absensi</dt>
                    <dd class="col-sm-9">: <span id="ma-date"></span></dd>

                </dl>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>

    // begin first table
    var table = $('#kt_table').DataTable({
        // scrollY: '50vh',
        scrollX: true,
        scrollCollapse: true,
        responsive: false,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        stateSave: false,

        ajax: {
            url  : '<?php echo site_url('classroom_scan/attendance_json'); ?>',
        },
        language: {
            "infoFiltered": ""
        },
        order: [[ 0, "desc" ]],
        columns: [
            {data: 'cra_create_date'},
			{data: 'kategori'},
            {data: 'member_name'},
            {data: 'group_name'},
            {data: 'cr_name'},
        ],

        columnDefs: [
            {
                targets: 2,
                render: function(data, type, full, meta) {
					return data+'<br><small>'+full['member_nip']+'</small>';
                },
            },

        ],

    });


    $(function(){
        document.getElementById('barcode').focus();
    });

    // Bind to the submit event of our form
    $("#formBarcode").submit(function(event){
        event.preventDefault();
    });

    $('#barcode').on('input', function() {
        sendBarcode();
    });



    var delayTimer;
    function sendBarcode(){
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            // Do the ajax stuff

            $.ajax({
                type: 'post',
                url: '<?= $form_action ?>',
                data: $('form').serialize(),
                success: function (json) {
                    res = $.parseJSON(json);
                    if (res.status==200){
						toastr.options = {
                            "positionClass": "toast-top-center",
                        };
                        toastr.success(res.data.member_name, res.data.cr_name);
                        table.ajax.reload();
                        //modalAttendance(res);
						$("#barcode").val("");
                    } else{
                        toastr.options = {
                            "positionClass": "toast-top-center",
                        };
                        toastr.error(res.message);
                    }

                }
            });

            table.ajax.reload();
            $("#formBarcode").resetForm();


        }, 250); 

    }


    function modalAttendance(res) {
        data = res.data;
        console.log(data);
        $('#ma-name').html('');
        $('#ma-nip').html('');
        $('#ma-group').html('');
        $('#ma-classroom').html('');
        $('#ma-date').html('');

        $('#ma-name').html(data.member_name);
        $('#ma-nip').html(data.member_nip);
        $('#ma-group').html(data.group_name);
        $('#ma-classroom').html(data.cr_name);
        $('#ma-date').html(data.cra_create_date);

        $('#modal-attendance').modal('show');
    }

</script>

