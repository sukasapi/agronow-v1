<!--begin: Datatable -->
<div class="table-responsive">
    <center>
        <p>Silahkan klik kolom untuk memilih classroom, kemudian klik tombol 'Tambahkan'</p>
    </center><br><br>
    <table class="table table-bordered table-striped table-hover table-sm nowraps" id="dt_classroom_selector">
        <thead>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama Pelatihan</th>
            <th class="text-center">Type</th>
            <th class="text-center" width="250px">Kategori</th>
            <th class="text-center">Harga (Poin)</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama Pelatihan</th>
            <th class="text-center">Type</th>
            <th class="text-center" width="250px">Kategori</th>
            <th class="text-center">Harga (Poin)</th>
        </tr>
        </tfoot>

    </table>

    <button type="button" class="btn btn-success mt-5" id="picker-classroom" title="Tambahkan">Tambahkan <i class="fa fa-chevron-right"></i> </button>

</div>
<!--end: Datatable -->

<style type="text/css">
    #dt_classroom_selector tbody tr{
        cursor:pointer;
    }
    .selected td {
        background-color: #c2f2f7 !important; /* Add !important to make sure override datables base styles */
    }
</style>
<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {

            // Setup - add a text input to each footer cell
            $('#dt_classroom_selector tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
            } );

            var table = $('#dt_classroom_selector');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('classroom/json?is_price='.$is_price); ?>',
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'cr_id'},
                    {data: 'cr_name'},
                    {data: 'cr_type'},
                    {data: 'cat_name'},
                    {data: 'cr_price'},
                ],

                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, full, meta) {
                            return data;

                        },
                    },

                ],

                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;

                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                }


            });
        };

        return {
            //main function to initiate the module
            init: function() {
                initTable1();
            },
        };

    }();

    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#dt_classroom_selector tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
        } );

        $('#picker-classroom').click( function () {
            $('#picker-classroom').prop('disabled', false);
            var data = $('#dt_classroom_selector').DataTable().rows('.selected').data();

            if(data.length>0)
            {
                addSelectedItem(data);
                //console.log(data);

                $('.modal').modal('hide');
            }
            else
                alert('Anda belum memilih.');
        } );
    } );
</script>
