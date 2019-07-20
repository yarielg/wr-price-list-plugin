(function($){

    jQuery(document).ready(function(){
        var table = $('#products_dt');
        var tbody = $('#products_dt tbody');
        var select_list = $('#price_list');

        select_list.change(function(){
            alert(select_list.val());
        });



        editor = new $.fn.dataTable.Editor( {
            table: "#products_dt",
            idSrc:  'ID',
            fields: [ {
                label: "Price:",
                name: "price",
                attr:  {
                    type: 'decimal',
                    maxlength: 10,
                    placeholder: 'Price'
                }
            }]
        });

        //when the price change this event is called, so here we change the prices
        editor.on( 'preEdit', function ( e, json, data, id ) {
                if(data['price']>0 && data['price'] < 100000){ //validating the price is correct
                    $.ajax( {
                        type: 'POST',
                        url:  parameters.ajax_url,
                        data:{
                            'id': data['ID'],
                            'action':'edit_price',
                            'price':data['price']
                        },
                        dataType: "json",
                        success: function (json) {

                        },
                        error : function(jqXHR, exception){
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            alert(msg);
                        }

                    } );
                }else{
                    alert('You type a wrong price, Price must be a valid number!');
                }


        } );

        /**/

        // Activate an inline edit on click of a table cell
        table.on( 'click', 'tbody td.price', function (e) {
            editor.inline( this );
        } );

        table.DataTable( {
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
                        data[i]['post_type'] = data[i]['post_status'] == 'trash' ? data[i]['post_type'] + ' (trash)': data[i]['post_type'];
                    }
                    return data;
                },
            },
            "columns": [
               /* { //this is for row details, if you include this you have to include a th as well in thead table
                    "className":      'details-control',
                    "orderable":      false,
                    "data":    null,
                    "defaultContent": '',
                },*/

                { "data" : "ID", "name": 'ID' },
                { "data" : "image", "name": 'image' },
                { "data" : "post_title", "name": 'post_title' },
                { "data" : "post_type", "name": 'post_type' },
                { "data" : "price", "name": 'price' ,"render": function ( data, type, row ) { return '$'+ data;},"class" : 'price'},
                { "data" : "guid", "name": 'guid'}
            ],
            "createdRow": function( row, data, dataIndex ) {
                if(data['post_type'] == 'product_variation'){
                    $(row).addClass('wrpl-variation-row');
                }

                /*if(data['variations'].length == 0){
                    $( row ).addClass('has-no-variations');
                }*/

               /* if( data['variations'].length === 0){
                    $( row ).addClass('wrpl-simple-product');
                }else{
                    $( row ).addClass('wrpl-variation-product');
                }*/
            }
        });


        //show icon for row details and aldo add and remove variations depending on which product you choose.
     /*   tbody.on('click', 'td.details-control', function () {
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
        } );*/

        //format for the row details
       /* function format ( rowData,row ) {
            console.log(row.index());
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
                                "<td class='image' data-dt-row="+row.index()+" data-dt-column='2'>"+ variation_img  +"</td>"+
                                "<td class='title' data-dt-row="+row.index()+" data-dt-column='3'>"+ data[i]['post_title'] +"</td>"+
                                "<td class='type' data-dt-row="+row.index()+" data-dt-column='4'>"+ data[i]['post_type'] +"</td>"+
                                "<td class='price' data-dt-row="+row.index()+" data-dt-column='5'>"+ data[i]['price'] +"</td>"+
                                "<td class='view' data-dt-row="+row.index()+" data-dt-column='6'><a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a></td>"+
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
        }*/

    });

})(jQuery);