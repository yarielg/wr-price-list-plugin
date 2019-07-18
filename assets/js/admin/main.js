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
                        data[i]['image'] = data[i]['image'] ? "<img src='"+ data[i]['image'] +"' width='50' height='50'>" : "<img src='https://imgplaceholder.com/120x120?text=Not+Found&font-size=25' width='50' height='50'>";
                    }
                    return data;
                },
            },
            "columns": [

                { "data" : "ID", "name": 'ID' },
                { "data" : "image", "name": 'image' },
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":    null,
                    "defaultContent": '',
                },
                { "data" : "post_title", "name": 'post_title' },
                { "data" : "post_parent", "name": 'post_parent' },
                { "data" : "post_type", "name": 'post_type' },
                { "data" : "post_status", "name": 'post_status' },
                { "data" : "guid", "name": 'guid'}
            ],
            "createdRow": function( row, data, dataIndex ) {
                // Set the data-status attribute, and add a class
                if(data['variations'].length == 0){
                    $( row ).addClass('no-variations');

                }            }
           // "order": [[1, 'asc']]
        });


        //show icon for row details
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

        //format for the row details
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
                    if(data.length > 0 ){
                        variation_tr = "<table class='tb-variations' style='width: 100%;'>" +
                            "<thead><th>ID</th><th></th><th>Title</th><th>parent ID</th><th>Type</th><th>Status</th><th>View</th></thead>" +
                            "<tbody>";
                        for(var i=0;i<data.length;i++){
                            variation_img = data[i]['image'] ? "<img src='"+ data[i]['image'] +"' width='50' height='50'>" : "<img src='https://imgplaceholder.com/120x120?text=Not+Found&font-size=25' width='50' height='50'>";
                            variation_tr += "<tr>" +
                                "<td>"+ data[i]['ID'] +"</td>"+
                                "<td>"+ variation_img  +"</td>"+
                                "<td>"+ data[i]['post_title'] +"</td>"+
                                "<td>"+ data[i]['post_parent'] +"</td>"+
                                "<td>"+ data[i]['post_type'] +"</td>"+
                                "<td>"+ data[i]['post_status'] +"</td>"+
                                "<td><a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a></td>"+
                                "</tr>";
                        }
                        variation_tr += "<tbody></tbody></table>";
                    }else{
                        variation_tr = "<p style='color: red;'>This is product do not have variations.</p>";
                    }
                    div.html( variation_tr );
                }
            } );
            return div;
        }

    });

})(jQuery);