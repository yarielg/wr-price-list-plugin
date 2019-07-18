(function($){

    jQuery(document).ready(function(){

        var tbody = $('#products_dt tbody');

        var table = $('#products_dt').DataTable( {
            "stateSave": true,
            "ajax": {
                "url": parameters.ajax_url,
                "type": "POST",
                "data":{
                    "action" : 'get_products'
                },
                "dataSrc": function(data){
                    for(var i = 0; i < data.length; i++ ){
                        data[i]['guid'] = "<a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a>";
                    }
                    return data;
                },
            },
            "columns": [

                { "data" : "ID", "name": 'ID' },
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":    null,
                    "defaultContent": '',
                    "name" : 'collapse'
                },
                { "data" : "post_title", "name": 'post_title' },
                { "data" : "post_parent", "name": 'post_parent' },
                { "data" : "post_type", "name": 'post_type' },
                { "data" : "post_status", "name": 'post_status' },
                { "data" : "guid", "name": 'guid'}
            ],
           // "order": [[1, 'asc']]
        });

        tbody.on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        } );



        function format ( rowData ) {

            var div = $('<div/>')
                .text( 'Loading...');

            $.ajax( {
                "url": parameters.ajax_url,
                "type" : 'POST',
                "data": {
                    "action" : 'get_variations',
                    "id" : rowData['ID']
                },
                "dataType": 'json',
                "success": function ( data ) {
                    var variation_tr = '';
                    for(var i=0;i<data.length;i++){
                           variation_tr += "<tr>" +
                                   "<td>"+ data[i]['ID'] +"</td>"+
                                   "<td>asdasd</td>"+
                                   "<td>"+ data[i]['post_title'] +"</td>"+
                                   "<td>"+ data[i]['post_parent'] +"</td>"+
                                   "<td>"+ data[i]['post_type'] +"</td>"+
                                   "<td>"+ data[i]['post_status'] +"</td>"+
                                   "<td><a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a></td>"+
                               "</tr>";
                    }
                    div.html( variation_tr );
                }
            } );

            return div;
        }


       /* $('#products_dt tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
        } );*/


       /* $('#products_dt tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            alert(data['ID']);
        } );*/

    });




})(jQuery);