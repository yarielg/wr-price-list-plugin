<?php
if(isset($_POST['import_price_list'])){

    $file_name = sanitize_file_name($_FILES['file_import']['name']);
    $file_tmp = sanitize_text_field($_FILES['file_import']['tmp_name']);
    move_uploaded_file($file_tmp, WRPL_PLUGIN_PATH . 'uploads/' . $file_name );

    $products_imported =  array();

    if (($handle = fopen(WRPL_PLUGIN_PATH . 'uploads/' . $file_name, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if('' != trim($data[0]) || '' != trim($data[1])){
                if( trim($data[2]) == '' ){ //si sale price esta vacio
                    $data[2] = 0;
                }
                array_push($products_imported,$data);
            }
        }
        fclose($handle);
    }

    if(isset($_POST['import_new_price_list'])){ //if the user choose create a new list
        $new_price_list_name = sanitize_text_field($_POST['import_new_price_list']);
        if($price_list_controller->wrpl_exist_price_list_name($new_price_list_name)){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>'.__('Price list name duplicated! ','wr_price_list').'</strong> '.__('The price list already exist, please choose other name.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';

        }else{
            $price_list = $price_list_controller->wrpl_add_price_list($new_price_list_name,0,'',$signature->is_valid());
            $imported_msgs = $product_controller->wrpl_import_products($products_imported,$price_list['id']);


        }
    }else{
        $imported_msgs = $product_controller->wrpl_import_products($products_imported,intval(sanitize_text_field($_POST['price_list_id'])));
    }
}

?>