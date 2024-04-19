<!--begin: Datatable -->
<div class="table-responsive">
    <center>
        <p>Silahkan klik kolom untuk memilih group, kemudian klik tombol 'Tambahkan'</p>
    </center><br><br>
    <table class="table table-bordered table-striped table-hover table-sm nowraps" id="dt_group_selector">
        <thead>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama</th>
        </tr>
        </tfoot>

    </table>

    <button type="button" class="btn btn-success mt-5" id="picker-group" title="Tambahkan">Tambahkan <i class="fa fa-chevron-right"></i> </button>

</div>
<!--end: Datatable -->

<style type="text/css">
    #dt_group_selector tbody tr{
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
            $('#dt_group_selector tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
            } );

            var table = $('#dt_group_selector');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('group/json'); ?>',
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "desc" ]],
                columns: [
                    {data: 'group_id'},
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
        $('#dt_group_selector tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
        } );

        $('#picker-group').click( function () {
            $('#picker-group').prop('disabled', false);
            var data = $('#dt_group_selector').DataTable().rows('.selected').data();

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
