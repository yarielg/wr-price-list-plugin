<?php
if(isset($_POST['wrpl_new_price_list'])){
    $price_list = $_POST['new_price_list'];
    $was_inserted = $price_list_controller->wrpl_add_price_list($price_list);
    if($was_inserted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Price list inserted! </strong> The price list was successfully inserted.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $plists = $price_list_controller->wrpl_get_price_lists();
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Price list name duplicated! </strong>The price list already exist, please choose other name.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    }
}

if(isset($_POST['wrpl_remove_pl_action'])){
    $id = $_POST['wrpl_pl_id'];
    $price_list_controller->wrpl_remove_price_list($id);
    $plists = $price_list_controller->wrpl_get_price_lists();
}
if(isset($_POST['wrpl_edit_pl_action'])){
    $name = $_POST['wrpl_pl_name'];
    $id = $_POST['wrpl_pl_id'];
    $was_inserted  = $price_list_controller->wrpl_edit_price_list($name,$id);
    if($was_inserted){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Price list inserted! </strong> The price list was successfully updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $plists = $price_list_controller->wrpl_get_price_lists();
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Price list name duplicated! </strong>The price list already exist, please choose other name.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    }
}

?>