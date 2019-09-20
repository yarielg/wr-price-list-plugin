(function($){

    jQuery(document).ready(function(){

        var table = $('#products_dt');
        var tbody = $('#products_dt tbody');

        //populating price list select
        var select_list = $('#price_list');
        var select_category = $('#categories_select_search');
        var price_list = select_list.val();
        var category_id = select_category.val();
        datatable = null;
        select_list.change(function(){
            var id_parent = $('#wrpl-option-' + select_list.val()).attr('pl-parent');
            var factor = $('#wrpl-option-' + select_list.val()).attr('pl-factor');
            var category_id = select_category.val();
            table.DataTable().destroy();
            price_list = $(this).val();
            datatable = createDatatable(price_list,id_parent,factor,category_id);
        });

        select_category.change(function(){
            var id_parent = $('#wrpl-option-' + select_list.val()).attr('pl-parent');
            var factor = $('#wrpl-option-' + select_list.val()).attr('pl-factor');
            category_id = $(this).val();
            table.DataTable().destroy();
            datatable = createDatatable(price_list,id_parent,factor,category_id);
            datatable.page( 'first' ).draw( 'page' );
        });

        //creando la tabla por defecto
        datatable = createDatatable(1,0,0,0);
        // crea una tabla y carga los valores en dependencia de la lista de precio
        function createDatatable(price_list,id_parent,factor,category_id){
            price_list = id_parent == 0 ? price_list : id_parent; //if la lista no tiene padre devuelve el id de la lista, sino si la lista si tiene padre devuelve el id del padre
            //Datatable
            var datatable = table.DataTable( {
                "stateSave": true,
                "deferRender": true,
                "fixedHeader": true,
                "rowId": 'ID',
                "serverSide": true,
                "bServerSide":true,
                "bPaginate":'paging',
                "processing": true,
                "language":{
                    "processing": 'loading...'
                },
                "ajax": {
                    "url": parameters.ajax_url,
                    "type": "POST",
                    "data":{
                        "action" : 'wrpl_get_products',
                        'price_list': price_list,
                        'category_id': category_id
                    },
                    "dataSrc": function(data){
                        data = data.data;
                        for(var i = 0; i < data.length; i++ ){
                            data[i]['post_title'] = data[i]['post_title'].substring(0, 30) + (data[i]['post_title'].length>30 ? '...' : '');
                            data[i]['guid'] = "<a class='btn btn-info btn-sm py-0 wrpl-view mr-1 mt-1' href='" + data[i]['guid'] + "'></a>" + "<a class='btn btn-info btn-sm py-0 wrpl-edit mt-1' href='" + data[i]['edit_url'] + "'></a>";
                            data[i]['image'] = data[i]['image'] ? "<img src='"+ data[i]['image'] +"' width='25' height='25'>" : "<div class='square' style='height: 25px;width: 25px;background-color: #555;'></div>";
                            data[i]['post_type'] = data[i]['post_type'] == 'product_variation' ? 'variation' : 'product';
                            if(id_parent != 0 ){
                                if(factor < 1 ){
                                    data[i]['sale_price'] =  (data[i]['price'] * parseFloat(factor)).toFixed(2);// + '  ' + toFixed(parseFloat(factor*100));
                                }else{
                                    data[i]['price'] = (data[i]['price']  *  parseFloat(factor)).toFixed(2);//+ '  ' + toFixed(parseFloat(factor*100));
                                    data[i]['sale_price'] = 0;
                                }

                            }

                        }
                        return data;
                    },
                },
                "columns": [

                    { "data" : "ID", "name": 'ID' },
                    { "data" : "image", "name": 'image' },
                    { "data" : "post_title", "name": 'post_title' },
                    { "data" : "sku", "name": 'sku' },
                    { "data" : "categories", "name": 'categories' },
                    { "data" : "post_type", "name": 'post_type' },
                    { "data" : "price", "name": 'price' ,
                        "render": function ( data, type, row ) {
                            if(id_parent > 0){
                                if(factor>=1){
                                    return  '$'+data + '<span class="wrpl-increase"> (↑' + (factor*100-100).toFixed() + '%)</span>' ;
                                }else{
                                    return '$' + data;
                                }
                            }else
                                return '$' + data;
                        },
                        "class" : 'price'},
                    { "data" : "sale_price", "name": 'sale_price' ,
                        "render": function ( data, type, row ) {
                            if(id_parent > 0 ){
                                if(factor<1){
                                    return  '$'+data + '<span class="wrpl-decrease"> (↓' + ((1-factor)*100).toFixed() + '%)</span>' ;
                                }else{
                                    return 'N/A';
                                }
                            }else
                                return '$' + data;
                        },
                        "class" : 'sale_price'},
                    { "data" : "guid", "name": 'guid'}
                ],
                "createdRow": function( row, data, dataIndex ) {
                    if(data['post_type'] == 'variation'){
                        $(row).addClass('wrpl-variation-row');
                    }
                    if(id_parent != 0){
                        $(row).addClass('wrpl-nobase-pl');
                    }
                }
            });

            return datatable;
        }

        //editing sale price
        $.fn.makeEditable = function(id,type) { //type 1 for regular 2 for sa;e
            var price_list = $('#price_list').val();
            function editPriceAjaxRequest(price,id,content,cell){

                $.ajax( {
                    type: 'POST',
                    url:  parameters.ajax_url,
                    data:{
                        'id': id,
                        'action':'wrpl_edit_price',
                        'price': (type === 1) ? parseFloat(content) : parseFloat(price),
                        'sale_price': (type === 1) ? parseFloat(price) : parseFloat(content),
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
                        cell.html( '$' + parseFloat(content));
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

            $(this).on('click',function(){
                if($(this).find('input').is(':focus')) return this;
                var cell = $(this);
                var content = $(this).html();
                var old_content = $(this).html();
                $(this).html('<input style="width: 100%;height: 22px;" step="0.01"  required  min=0 type="number" value="' + $(this).html().replace('$','') + '" />')
                    .find('input')
                    .trigger('focus')
                    .on({
                        'blur': function(){
                            $('#wrpl_price_editing').remove();
                            cell.html(old_content);
                        },
                        'keyup':function(e){
                            if(e.which == '13'){ // enter
                                $(this).trigger('saveEditable');
                            } else if(e.which == '27'){ // escape
                                $(this).trigger('closeEditable');
                            }
                        },
                        'closeEditable':function(e){

                            var price = (type === 1) ? $('#'+id).find('td:eq(7)').text() : $('#'+id).find('td:eq(6)').text();
                            price = parseFloat(price.replace('$',''));
                            if(isNaN(price)){
                                price = 0;
                            }
                            content += '';
                            content = parseFloat(content.replace('$',''));
                            if(isNaN(content)){
                                content = 0;
                            }

                            if(type == 2){
                                if(!isNaN(content)  && content >= 0 && content !== '' ){
                                    if(price > content && price > 0){

                                        editPriceAjaxRequest(price,id,content,cell);
                                    }else{
                                        alert('Sales price have to be lower than regular price');
                                        cell.html(old_content);
                                    }
                                }else{
                                    alert('You must have to enter a valid number except 0');
                                    cell.html(old_content);
                                }

                            }else{
                                if(!isNaN(price) && !isNaN(content) && content > 0 && price >= 0 && price !== '' && content !== ''){
                                    if(content > price){
                                        editPriceAjaxRequest(price,id,content,cell);
                                    }else{
                                        alert('Sales price have to be lower than regular price');
                                        cell.html(old_content);

                                    }
                                }else{
                                    alert('You must have to enter a valid number except 0');
                                    cell.html(old_content);
                                }
                            }
                        },
                        'saveEditable':function(){

                            content = $(this).val();
                            $(this).trigger('closeEditable');
                        }
                    });
            });
            return this;
        };

        //edito siempre y cuando sea una lista sin padre
        tbody.on('click','.sale_price',function(e){
            var id_parent = $('#wrpl-option-' + $('#price_list').val()).attr('pl-parent') || $('#wrpl-option-0').attr('pl-parent');
            if(id_parent == 0) {
                var row = $(this).closest('tr');
                var td_sale_price = row.find('td:eq(7)');
                var id = row.find('td:eq(0)').text();

                td_sale_price.makeEditable(id,2);
            }
        });

        tbody.on('click','.price',function(e){
            var id_parent = $('#wrpl-option-' + $('#price_list').val()).attr('pl-parent') || $('#wrpl-option-0').attr('pl-parent');
            if(id_parent == 0) {
                var row = $(this).closest('tr');
                var td_regular_price = row.find('td:eq(6)');
                var id = row.find('td:eq(0)').text();

                td_regular_price.makeEditable(id,1);
            }
        });

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

        //modal remove price list
        //data-* attributes to scan when populating modal values
        $('[data-toggle="modal"].wrpl_remove_price_list').on('click', function (e) {
            // convert target (e.g. the button) to jquery object
            var $target = $(e.target);
            // modal targeted by the button
            var modalSelector = $target.data('target');
            var $modalAttribute = $(modalSelector + ' #wrpl-pl');
            var dataValue = $target.data('pl-id');

            $modalAttribute.val(dataValue || '');
        });
        //edit price list
        $('[data-toggle="modal"].wrpl_edit_price_list').on('click', function (e) {
            var $target = $(e.target);
            var modalSelector = $target.data('target');

            var text_price_list_name = $(modalSelector + ' #wrpl-edit-pl-name');
            var id_price_list = $(modalSelector + ' #wrpl-edit-pl-id');
            var $input_factor = $(modalSelector + ' #wrpl-edit-pl-factor');
            var select_price_list = $(modalSelector + ' #price_list_edit');

            var dataValue3 = $target.data('pl-price_list');
            select_price_list.val(dataValue3);
            var price_list = select_price_list.val();

            var dataValue = $target.data('pl-name');
            var dataValue1 = $target.data('pl-id');
            var dataValue2 = $target.data('pl-factor');

            if( parseInt(price_list) > 0 ){
                $input_factor.prop('disabled',false);
                $('#wrpl-add_pl_factor_label').removeClass('disabled');
            }else{

                $input_factor.prop('disabled',true);
                $('#wrpl-add_pl_factor_label').addClass('disabled');
            }

            text_price_list_name.val(dataValue || '');
            id_price_list.val(dataValue1 || '');
            $input_factor.val(parseFloat(dataValue2) || '');
        });

        //edit role name
        $('[data-toggle="modal"].wrpl_edit_role').on('click', function (e) {
            var $target = $(e.target);
            var modalSelector = $target.data('target');

            var $modalAttribute = $(modalSelector + ' #wrpl-edit-role');
            var $modalAttribute1 = $(modalSelector + ' #wrpl-edit-old_role');
            var dataValue = $target.data('role-name');

            $modalAttribute.val(dataValue || '');
            $modalAttribute1.val(dataValue || '');
        });
        //remove role
        $('[data-toggle="modal"].wrpl_remove_role').on('click', function (e) {
            var $target = $(e.target);
            var modalSelector = $target.data('target');

            var $modalAttribute = $(modalSelector + ' #wrpl-edit-role');

            var dataValue = $target.data('role-name');

            $modalAttribute.val(dataValue || '');

        });
    });

    //fileupload validation
    $('input[type=file]').change(function(){
        var ext = $('#file_import').val().split('.').pop().toLowerCase();
//Allowed file types
        if($.inArray(ext, ['csv']) == -1) {
            alert('The file type is invalid!');
            $('#import_price_list').prop('disabled',true);
            $('#file_import').val("");
        }else{
            if(this.files[0].size > 8388608){
                alert('File greater than 8 mb');
            }else{
                $('#import_price_list').prop('disabled',false);
            }

        }
    });

    //import/ export feature
    $('#check_price_list').click(function(){
        if($(this).prop("checked") == true){
            $('#import_new_price_list').prop('disabled',false);
            $('#import_select_price_list').prop('disabled',true);
            $('#import_new_price_list_label').removeClass('disabled');
            $('#import_select_price_list_label').addClass('disabled');
        }
        else{
            $('#import_new_price_list').prop('disabled',true);
            $('#import_select_price_list').prop('disabled',false);
            $('#import_new_price_list_label').addClass('disabled');
            $('#import_select_price_list_label').removeClass('disabled');
        }
    });

    //hide price setting tab
    $('#hide_price').click(function(){
        if($(this).prop("checked") == true){
            $('#custom_msg_not_login_user').prop('disabled',false);
            $('#custom_msg_not_login_user_label').removeClass('disabled');
        }
        else{
            $('#custom_msg_not_login_user').prop('disabled',true);
            $('#custom_msg_not_login_user_label').addClass('disabled');
        }
    });

    $('#price_list').change(function () {
        if($(this).val() == 0){
            $('#wrpl-add_pl_factor').prop('disabled',true);
            $('#wrpl-add_pl_factor_label').addClass('disabled');
        }else{
            $('#wrpl-add_pl_factor').prop('disabled',false);
            $('#wrpl-add_pl_factor_label').removeClass('disabled');
        }
    });

    //import checbox
    $('#check_price_list').prop('indeterminate', true);

    //blofeando
    /*var $by_category = $('#rbtn_by_category');
    if($by_category.prop('disabled')){
        $by_category.remove();
        $('#rbtn_by_category_label').remove();
    }*/

    var $link_import = $('#li_link_import');
    var attr_name_link = $link_import.attr('name');
    if(attr_name_link == 'blofe'){
        $link_import.remove();
    }

})(jQuery);