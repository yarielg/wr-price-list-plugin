(function($){

    jQuery(document).ready(function(){

        var table = $('#products_dt');
        var tbody = $('#products_dt tbody');
        var datatable_container = $('#datatable_container');
        //populating price list select
        var select_list = $('#price_list');
        var price_list = select_list.val();
        select_list.change(function(){
            var price_list = select_list.val();
            console.log(price_list);
            table.DataTable().destroy();
            if(!isNaN(price_list)){

                createDatatable(price_list);
            }else{

                createDatatable('default');

            }
        });

        createDatatable('default');

      function createDatatable(price_list){

          //editor feature to update prices
          editor = new $.fn.dataTable.Editor( {
              table: "#products_dt",
              idSrc:  'ID',
              fields: [ {
                  label: "Price:",
                  name: "price",
                  attr:  {
                      type: 'number',
                      maxlength: 10,
                      required: true,
                      min: 1 ,
                      step : 0.01,
                      max: 100000000,
                      placeholder: 'Regular Price'
                  }
              },{
                  label: "Sale Price:",
                  name: "sale_price",
                  attr:  {
                      type: 'number',
                      min: 0 ,
                      step : 0.01,
                      required: true,
                      max: 100000000,
                      placeholder: 'Sale Price'
                  }
              }]
          });


          // Activate an inline edit on click of a table cell
          table.on( 'click', 'tbody td.price', function (e) {
              editor.inline( this );
          } );

          table.on( 'click', 'tbody td.sale_price', function (e) {
              editor.inline( this );
          } );

          //edit price ajax call
          function editPriceAjaxRequest(price, sale_price,id){
              $.ajax( {
                  type: 'POST',
                  url:  parameters.ajax_url,
                  data:{
                      'id': id,
                      'action':'edit_price',
                      'price':price,
                      'sale_price':sale_price,
                      'price_list' : price_list
                  },
                  dataType: "json",
                  beforeSend: function () {
                      $(".wrpl_loader").css("display", "block");
                      $("#modal-overlay").show();
                  },
                  complete: function () {
                      $(".wrpl_loader").css("display", "none");
                      $("#modal-overlay").hide();
                  },
                  success: function (json) {
                      console.log(json);
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
          }

          //this is for get the old data,so when something fails we can set the old data again
          var oldata = null;
          editor.on( 'initEdit', function ( e, node, data, items, type ) {
              oldata = data;
          } );
          //set old data
          function setOldValue(id){
              setTimeout(function(){
                  var cell_price = $('#'+id+' .price');
                  var cell_sale_price = $('#'+id+' .sale_price');
                  cell_price.html(oldata['price']);
                  cell_sale_price.html(oldata['sale_price']);
                  datatable.row('#'+id).data(oldata).draw(false);
              }, 1000);
          }

          editor.on( 'preEdit', function ( e, json, data, id ) {

              var price = parseFloat('' + data['price']);
              var sale_price = parseFloat('' + data['sale_price']);
              if(!isNaN(price) && !isNaN(sale_price) && price > 0 && sale_price >= 0 && price !== '' && sale_price !== ''){
                  if(price > sale_price){
                      editPriceAjaxRequest(price, sale_price, data['ID']);

                  }else{
                      setOldValue(id);
                      alert('Sales price have to be lower than regular price');
                  }
              }else{
                  setOldValue(id);
                  alert('You must have to enter a valid number except 0');
              }
          } );

          //Datatable
          var datatable = table.DataTable( {
              "stateSave": true,
              "deferRender": true,
              "rowId": 'ID',
              "ajax": {
                  "url": parameters.ajax_url,
                  "type": "POST",
                  "data":{
                      "action" : 'get_products',
                      'price_list': price_list
                  },
                  "dataSrc": function(data){
                      for(var i = 0; i < data.length; i++ ){
                          data[i]['guid'] = "<a class='btn btn-info btn-sm' href='" + data[i]['guid'] + "'>View</a>";
                          data[i]['image'] = data[i]['image'] ? "<img src='"+ data[i]['image'] +"' width='50' height='50'>" : "<img src='https://imgplaceholder.com/120x120?text=Not+Found&font-size=25' width='50' height='50'>";
                          data[i]['post_type'] = data[i]['post_type'];
                          //data[i]['sale_price'] = data[i]['sale_price'] == 0 ? 'NOT DEFINED' : data[i]['sale_price'];
                      }
                      return data;
                  },
              },
              "columns": [

                  { "data" : "ID", "name": 'ID' },
                  { "data" : "image", "name": 'image' },
                  { "data" : "post_title", "name": 'post_title' },
                  { "data" : "sku", "name": 'sku' },
                  { "data" : "post_type", "name": 'post_type' },
                  { "data" : "price", "name": 'price' ,"render": function ( data, type, row ) { return '$'+ data;},"class" : 'price'},
                  { "data" : "sale_price", "name": 'sale_price' ,"render": function ( data, type, row ) { return '$'+ data;},"class" : 'sale_price'},
                  { "data" : "guid", "name": 'guid'}
              ],
              "createdRow": function( row, data, dataIndex ) {
                  if(data['post_type'] == 'product_variation'){
                      $(row).addClass('wrpl-variation-row');
                  }
              }
          });
      }

      //helper for get the parameters from current url
        function getUrlParam(key) {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            if(vars[key]){
                return vars[key];
            }
            return -1;
        }
    });

})(jQuery);