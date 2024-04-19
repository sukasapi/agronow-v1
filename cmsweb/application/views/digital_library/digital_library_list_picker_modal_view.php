<div class="col-xl-12">
    <!--begin: Datatable -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="kt_table_digital">
            <thead>
            <tr>
                <th class="text-center" width="16px"></th>
                <th class="text-center" width="16px">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Publish</th>
                <th class="text-center">Gambar</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Level</th>
                <th class="text-center">Dilihat</th>
               <!-- <th class="text-center">Document</th>-->
                <th class="text-center">Jenis Konten</th>
                <th class="text-center">Status</th>

            </tr>
            </thead>
        </table>
    </div>
    <!--end: Datatable -->
</div>




<script>
    "use strict";
    var table = $('#kt_table_digital');

    // begin first table
    table.DataTable({
        scrollY: '50vh',
        scrollX: true,
        scrollCollapse: true,
        responsive: false,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        stateSave: false,
        ajax: {
            url  : '<?php echo site_url('digital_library/l_ajax').'?section_id=35&withcategorytype=yes&'.$_SERVER['QUERY_STRING']; ?>',
            type : "POST"
        },
        language: {
            "infoFiltered": ""
        },
        order: [[ 0, "desc" ]],

        columns: [
            {data: '', responsivePriority: -1},
            {data: 'content_id'},
            {data: 'content_create_date'},
            {data: 'content_publish_date'},
            {data: 'media_value'},
            {data: 'cat_name'},
            {data: 'content_name'},
            {data: 'mlevel_id'},
            {data: 'content_hits'},
           /* {data: 'document_value'},*/
            {data: 'content_type_name'},
            {data: 'content_status'},

        ],
        columnDefs: [
            {
                targets: 2,
                render: function(data, type, full, meta) {
                    return '<small>'+data+'<br>'+full['content_create_time']+'</small>';

                },
            },
            {
                targets: 3,
                render: function(data, type, full, meta) {
                    return '<small>'+data+'<br>'+full['content_publish_time']+'</small>';

                },
            },
            {
                targets: 4,
                orderable: false,
                render: function(data, type, full, meta) {
                    if (typeof(data) !== 'undefined' && full['media_type']=='image'){
                        return '<img src="<?php echo URL_MEDIA_IMAGE; ?>'+data+'" width="96px" />'
                    }else{
                        return '';
                    }

                },
            },
            {
                targets: 6,
                render: function(data, type, full, meta) {
                    return '<a target="_blank" href="<?php echo site_url("digital_library/detail/"); ?>'+full["content_id"]+'" title="View">'+data+'</a>'+
                        '<br><small>Author : '+full['content_author']+'</small>';

                },
            },
            {
                targets: 0,
                title: '',
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<button type="button" class="btn btn-outline-primary btn-sm picker-digital-library" data-id="'+full['content_id']+'" title="Select">' +
                        'Pilih' +
                        '</button>';

                },
            },
            {
                targets: -1,
                render: function(data, type, full, meta) {
                    var status = {
                        'publish': {'title': 'Publish', 'state': 'success'},
                        'pending': {'title': 'Pending', 'state': 'warning'},
                        'draft': {'title': 'Draft', 'state': 'dark'},
                        '': {'title': '-', 'state': 'warning'},
                    };
                    if (typeof status[data] === 'undefined') {
                        return data;
                    }
                    return '<span class="badge badge-' + status[data].state + ' badge-pill">'+status[data].title+'</span>';
                },
            },
            /*{
                targets: -3,
                orderable: false,
                class: "text-center",
                render: function(data, type, full, meta) {
                    if (data){
                        return '<a target="_blank" href="'+data+'"><i class="la la-file-pdf-o la-3x"></i></a>'
                    }else{
                        return '';
                    }

                },
            },*/

        ],
    });

</script>
