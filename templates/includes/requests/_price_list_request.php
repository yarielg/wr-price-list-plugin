<?php
if(isset($_POST['wrpl_new_price_list'])){
    $name = sanitize_text_field($_POST['wrpl_pl_name']);
    $price_list = sanitize_text_field(intval($_POST['price_list']));
    $factor = isset($_POST['wrpl_pl_factor']) ? sanitize_text_field(floatval($_POST['wrpl_pl_factor'])) : '';
    $was_inserted = $price_list_controller->wrpl_add_price_list($name,$price_list,$factor,$signature->is_valid());
    if($was_inserted['status'] == 'success'){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>'.__('Price list inserted! ','wr_price_list').'</strong> '.__('The price list was successfully inserted.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $plists = $price_list_controller->wrpl_get_price_lists();
    }else{
        if($was_inserted['type']==2){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>'.__('Price list name duplicated! ','wr_price_list').'</strong> '.__('The price list already exist, please choose other name.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>'.__('Please upgrade plan! ','wr_price_list').'</strong> '.__('You have reached the maximum price list number for this free version.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        }
    }
}

if(isset($_POST['wrpl_remove_pl_action'])){
    $id = sanitize_text_field(intval($_POST['wrpl_pl_id']));
    $price_list_controller->wrpl_remove_price_list($id);
    $plists = $price_list_controller->wrpl_get_price_lists();
}
if(isset($_POST['wrpl_edit_pl_action'])){
    $name = sanitize_text_field($_POST['wrpl_pl_name']);
    $id = sanitize_text_field(intval($_POST['wrpl_pl_id']));
    $factor = isset($_POST['wrpl_pl_factor']) ? sanitize_text_field(floatval($_POST['wrpl_pl_factor'])) : '';
    $was_inserted  = $price_list_controller->wrpl_edit_price_list($name,$id,$factor);
    if($was_inserted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>'.__('Price list updated! ','wr_price_list').'</strong> '.__('The price list was successfully updated.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $plists = $price_list_controller->wrpl_get_price_lists();
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>'.__('Price list name duplicated! ','wr_price_list').'</strong> '.__('The price list already exist, please choose other name.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    }
}

?>