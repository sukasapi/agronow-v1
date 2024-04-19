<!--begin: Datatable -->
<div class="table-responsive">
    <center>
        <p>Silahkan klik kolom untuk memilih jabatan, kemudian klik tombol 'Tambahkan'</p>
    </center><br><br>
    <table class="table table-bordered table-striped table-hover table-sm nowraps" id="dt_jabatan_selector">
        <thead>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center" width="250px">Group</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center" width="250px">Group</th>
        </tr>
        </tfoot>

    </table>

    <button type="button" class="btn btn-success mt-5" id="picker-jabatan" title="Tambahkan">Tambahkan <i class="fa fa-chevron-right"></i> </button>

</div>
<!--end: Datatable -->

<style type="text/css">
    #dt_jabatan_selector tbody tr{
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
            $('#dt_jabatan_selector tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
            } );

            var table = $('#dt_jabatan_selector');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('jabatan/json'); ?>',
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'jabatan_id'},
                    {data: 'jabatan_name'},
                    {data: 'group_name'},
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
        $('#dt_jabatan_selector tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
        } );

        $('#picker-jabatan').click( function () {
            $('#picker-jabatan').prop('disabled', false);
            var data = $('#dt_jabatan_selector').DataTable().rows('.selected').data();

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
