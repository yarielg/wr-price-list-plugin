<?php

if(isset($_POST['wrpl_save_roles'])){
   $price_list_controller->wrpl_save_price_list_role($roles);
}
?>

<div class="row p-3">
    <p><?php _e('Assign a price list to each role, so that all users who correspond to the role will see only the price that corresponds to the assigned price list.','wr_price_list') ?></p>
    <p><?php _e('Note: Please note that if for a certain role you do not choose a list, all users belonging to this role will see the prices of the list that is by default.','wr_price_list') ?></p>
</div>


<?php

    $rows = '<form action="" method="post" class="container-fluid">';
    foreach ($roles as $role){
        $options_price_lists = '';
        $price_lists = $price_list_controller->wrpl_get_price_lists();
        foreach ($price_lists as $price_list){
            $selected = get_option('wrpl-'. wrpl_valid_name($role['name'])) == $price_list['id'] ? 'selected' : '';
            $options_price_lists .= '<option value="'.$price_list['id'].'" ' . $selected . '>'. $price_list['description'] .'</option>';
        }

        $rows .= '<div class="row ml-3 mb-2">
                        <div class="col-md-3">
                            <label >' .$role['name']. ':</label>                      
                        </div>
                        <div class="col-md-9">
                            <select name="' . wrpl_valid_name($role['name'])  .'" class="form-control form-control-sm">
                                ' . $options_price_lists . '
                            </select>               
                        </div>
                        
                    </div>';
    }
    $rows .= '<input type="submit" name="wrpl_save_roles" class="btn btn-info btn-sm ml-3 my-3 float-right" value="'.__('Save','wr_price_list').'">
             </form>';
    echo $rows;
?>




