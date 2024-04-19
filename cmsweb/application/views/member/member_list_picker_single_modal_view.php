<!--begin: Datatable -->
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-sm nowraps" id="dt_member_selector">
        <thead>
        <tr>
            <th class="text-center" width="16px"></th>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama</th>
            <th class="text-center" width="250px">Group</th>
            <th class="text-center">NIP</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th class="text-center" width="16px"></th>
            <th class="text-center" width="16px">No</th>
            <th class="text-center">Nama</th>
            <th class="text-center" width="250px">Group</th>
            <th class="text-center">NIP</th>
        </tr>
        </tfoot>

    </table>

</div>
<!--end: Datatable -->

<style type="text/css">
    #dt_member_selector tbody tr{
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
            $('#dt_member_selector tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
            } );

            var table = $('#dt_member_selector');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
                ajax: {
                    url  : '<?php echo site_url('member/json'); ?>',
                },
                language: {
                    "infoFiltered": ""
                },
                order: [[ 1, "desc" ]],
                columns: [
                    {data: '', responsivePriority: -1},
                    {data: 'member_id'},
                    {data: 'member_name'},
                    {data: 'group_name'},
                    {data: 'member_nip'},
                ],

                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, full, meta) {
                            return data;

                        },
                    },

                    {
                        targets: 0,
                        title: '',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<button type="button" class="btn btn-outline-primary btn-sm picker-member" data-id="'+full['member_id']+'" data-name="'+full['member_name']+'"  title="Select">' +
                                'Pilih' +
                                '</button>';

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

