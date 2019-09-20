(function($){

       var myTree = [];

       $.ajax( {
           type: 'POST',
           url:  parameters.ajax_url,
           data:{
               'action':'wrpl_get_categories',
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
           success: function (data,status,xhr) {
               myTree = data;
               $('#default-tree').treeview({

                   // expanded to 2 levels
                   levels: 5,
                   data: myTree,
                   // custom icons
                   expandIcon: 'wrpl-expand',
                   collapseIcon: 'wrpl-collapse',
                   emptyIcon: '',
                   nodeIcon: 'wrpl-cat',
                   selectedIcon: '',
                   checkedIcon: '',//'wrpl-check',
                   uncheckedIcon: '',// 'wrpl-uncheck',
                   // colors
                   color: undefined, // '#000000',
                   backColor: undefined, // '#FFFFFF',
                   borderColor: undefined, // '#dddddd',
                   onhoverColor: '#F5F5F5',
                   selectedColor: '#FFFFFF',
                   selectedBackColor: '#428bca',
                   searchResultColor: '#D9534F',
                   searchResultBackColor: undefined, //'#FFFFFF',
                   enableLinks: false,
                   highlightSelected: true,
                   showBorder: true,
                   showIcon: true,
                   // enables multi select
                   multiSelect: false

               });

               $('#default-tree').treeview('collapseAll', { silent: true });


               $('#default-tree').on('nodeSelected', function(event, data) {
                   var select_pl_categories = $('#price_list_categories');
                   var save_btn = $('#btn_price_list_cat');
                   var text_cat_id = $('#wrpl_cat_id');
                   select_pl_categories.prop('disabled',false);
                   $('#price_list_categories_label').removeClass('disabled');
                   save_btn.prop('disabled',false);
                   text_cat_id.val(data.term_id);
                   select_pl_categories.val(data.plist);
               });

               $('#default-tree').on('nodeUnselected', function(event, data) {
                   var select_pl_categories = $('#price_list_categories');
                   var save_btn = $('#btn_price_list_cat');
                   select_pl_categories.prop('disabled',true);
                   $('#price_list_categories_label').addClass('disabled');
                   save_btn.prop('disabled',true);
               });
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

       //sortable rule list
    $( "#wrpl_rules_categories" ).sortable({
        connectWith: '#deleteAreaRule',
        update: function( event, ui ) {
            var order = $(this).sortable('serialize');
            $(this).children().each(function(index){
                if($(this).attr('data-order') != (index+1) ){
                    $(this).attr('data-order',index + 1 ).addClass('updated-position');
                }
            });
            saveNewPositions();
        },
    });

    function saveNewPositions(){
        var positions= [];
        $('.updated-position').each(function(){
            positions.push([$(this).attr('data-index'),$(this).attr('data-order')]);
            $(this).removeClass('updated-position');
        });

        $.ajax({
            url: parameters.ajax_url,
            method: 'POST',
            dataType: 'json',
            data: {
               // update: 1,
                positions: positions,
                action: 'wrpl_updated_rule_order'
            },
            beforeSend: function () {
                $(".wrpl_loader").css("display", "block");
                $("#modal-overlay").show();
            },
            complete: function () {
                $(".wrpl_loader").css("display", "none");
                $("#modal-overlay").hide();
            },
            success: function(response){
                //console.log(response);
            },
            error(){
                alert('Ooops something weird happened, try again please.')
            }
        });
    }

    $("#deleteAreaRule").droppable({
        accept: '#wrpl_rules_categories > li',
        activeClass: 'dropArea',
        hoverClass: 'dropAreaHover',
        drop: function(event, ui) {
            ui.draggable.remove();
            $.ajax({
                url: parameters.ajax_url,
                method: 'POST',
                dataType: 'json',
                data: {
                    id: ui.draggable.attr('data-index'),
                    action: 'wrpl_delete_rule'
                },beforeSend: function () {
                    $(".wrpl_loader").css("display", "block");
                    $("#modal-overlay").show();
                },
                complete: function () {
                    $(".wrpl_loader").css("display", "none");
                    $("#modal-overlay").hide();
                },
                success: function(response){
                    console.log(response);

                },
                error(){
                    alert('Ooops something weird happened, try again please.')
                }
            });
        }
    });


})(jQuery);