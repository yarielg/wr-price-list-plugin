<?php

if(isset($_POST['wrpl_new_role'])){

    $role_name = $_POST['role_name'];
    $was_created = $price_list_controller->wrpl_add_role($role_name);
    if($was_created){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>'.__('Role created! ','wr_price_list').'</strong> '.__('The role was successfully created.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $plists = $price_list_controller->wrpl_get_price_lists();
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong> '.__('Role name duplicated !','wr_price_list').' </strong>'.__('The role already exist, please choose other name.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="'.__('Close','wr_price_list').'">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    }
    $roles = stdToArray(wrpl_roles());
}
if(isset($_POST['wrpl_remove_role_action'])){
    $role_name = $_POST['wrpl_role_name'];
    $price_list_controller->wrpl_remove_role($role_name);
    $roles = stdToArray(wrpl_roles());
}
if(isset($_POST['wrpl_edit_role_action'])){
    $role_name = $_POST['wrpl_role_name'];
    $role_name_old = $_POST['wrpl_role_old_name'];
    $was_updated = $price_list_controller->wrpl_edit_role($role_name,$role_name_old);
    if($was_updated){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>'.__('Role updated! ','wr_price_list').'</strong> '.__('The role was successfully updated.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
        $roles = $was_updated;
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>'.__('Error updating role! ','wr_price_list').'</strong>'.__('The role was not updated.','wr_price_list').'
                    <button type="button" class="close" data-dismiss="alert" aria-label="'.__('Close','wr_price_list').'">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    }

}
?>