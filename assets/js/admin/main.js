(function($){

    jQuery(document).ready(function(){

        var tbody = $('#products_dt tbody');

        editor = new $.fn.dataTable.Editor( {
            ajax: parameters.ajax_url,
            table: "#products_dt",
            idSrc:  'ID',
            fields: [ {
                label: "Price:",
                name: "price"
            }]
        });

        // Activate an inline edit on click of a table cell
        $('#products_dt').on( 'click', 'tbody td.price', function (e) {
            editor.inline( this );
        } );

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
                { //for row details
                    "className":      'details-control',
                    "orderable":      false,
                    "data":    null,
                    "defaultContent": '',
                },

                { "data" : "ID", "name": 'ID' },
                { "data" : "image", "name": 'image' },
                { "data" : "post_title", "name": 'post_title' },
                { "data" : "post_type", "name": 'post_type' },
                { "data" : "price", "name": 'price' ,"render": $.fn.dataTable.render.number( ',', '.', 0, '$' ),"class" : 'price'},
                { "data" : "guid", "name": 'guid'}
            ],
            "createdRow": function( row, data, dataIndex ) {
                // Set the data-status attribute, and add a class
                if(data['variations'].length == 0){
                    $( row ).addClass('no-variations');

                }            }
           // "order": [[1, 'asc']]
        });


        //show icon for row details and aldo add and remove variations depending on which product you choose.
        tbody.on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var rowsChilds = format(row.data(), row); //get a raw html belong to all tr variations
            var html = $.parseHTML( rowsChilds); //parsing raw html to jquery elements
            if (tr.hasClass('shown')) {
                
                tr.removeClass('shown'); //Add class shown for change to open icon
                $("#products_dt").find("tr.tb-variations").remove(); //Deleting all tr variations
            }
            else {
                if(row.data().variations.length>0){

                    tr.after( html );
                    tr.addClass('shown'); //Add class shown for change to close icon
                }

            }
        } );

        //format for the row details
        //format for the row details
        function format ( rowData,row ) {

            var table =  ('Loading...');

            $.ajax( {
                "url": parameters.ajax_url,
                "async": false,
                "type" : 'POST',
                "data": {
                    "action" : 'get_variations',
                    "id" : rowData['ID']
                },
                "dataType": 'json',
                "success": function ( data ) {
                    if(data.length > 0 ){
                       table = "";
                        for(var i=0;i<data.length;i++){
                            variation_img = data[i]['image'] != -1 ? "<img src='"+ data[i]['image'] +"' width='50' height='50'>" : "<img src='https://imgplaceholder.com/120x120?text=Not+Found&font-size=25' width='50' height='50'>";
                            table += "<tr role='row' class='no-variations tb-variations' id='tr-variation-"+i+"'>" +
                                "<td></td>"+
                                "<td class='id' data-dt-row="+row.index()+" data-dt-column='1'>"+ data[i]['ID'] +"</td>"+
                                "<td class='imgage' data-dt-row="+row.index()+" data-dt-column='2'>"+ variation_img  +"</td>"+
                                "<td class='title' data-dt-row="+row.index()+" data-dt-column='3'>"+ data[i]['post_title'] +"</td>"+
                                "<td class='type' data-dt-row="+row.index()+" data-dt-column='4'>"+ data[i]['post_type'] +"</td>"+
                                "<td class='price' data-dt-row="+row.index()+" data-dt-column='5'>"+ data[i]['price'] +"</td>"+
                                "<td class='' data-dt-row="+row.index()+" data-dt-column='6'><a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a></td>"+
                                "</tr>";
                        }

                    }else{
                        table = "<p style='color: red;'>This is product do not have variations.</p>";
                    }
                },
                "error" : function(jqXHR, textStatus, errorThrown){
                    table = "<p style='color: red;'>The server is experimented some issues. Refresh and try again</p>";

                }
            } );
            return table;
        }

    });

})(jQuery);