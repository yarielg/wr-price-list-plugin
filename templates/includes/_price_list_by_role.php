<?php

$roles = stdToArray(wrpl_roles());
if(isset($_POST['wrpl_save_roles'])){
   $price_list_controller->wrpl_save_price_list_role($roles);
}
?>

<div class="row p-3">
    <p>Asigne a cada rol una lista de precio, así todo el usuario que corresponde con el role verá solo el precio de que corresponda a la lista de precio asignada.</p>
    <p><strong>Nota:</strong>  Tenga en cuenta que si para cierto role no elige una lista, todos los usuarios pertenecientes a este role veran los precios de la lista que esté por defecto</p>
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
                        <div class="col-3">
                            <label for="staticEmail" >' .$role['name']. ':</label>                      
                        </div>
                        <div class="col-9">
                            <select name="' . wrpl_valid_name($role['name'])  .'" class="form-control form-control-sm">
                                <option value="default">Default Woocommerce</option>
                                ' . $options_price_lists . '
                            </select>               
                        </div>
                        
                    </div>';

    }
    $rows .= '<input type="submit" name="wrpl_save_roles" class="btn btn-info btn-sm ml-3 my-3 float-right" value="Save">
             </form>';
    echo $rows;
?>




