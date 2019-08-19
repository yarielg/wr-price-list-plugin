<?php
//ROLE REQUESTS
include 'requests/_role_request.php';

// PRICE LIST REQUESTS
include 'requests/_price_list_request.php';


?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <h5>Roles</h5>
            <ul class="list-group">
                <?php
                    foreach ($roles as $role){
                        $btns = get_option('wrpl_role-'. wrpl_valid_name($role['name'])) == $role['name'] ?
                                '<button class="btn btn-info btn-sm wrpl_edit_role wrpl-edit" name="wrpl_edit_role" data-toggle="modal" data-target="#wrpl_edit_role_modal"  data-role-name="' .$role['name']. ' " ></button>
                                <button class="btn btn-danger btn-sm wrpl_remove_role wrpl-trash" name="wrpl_remove_role" data-toggle="modal" data-target="#wrpl_remove_role_modal" data-role-name="' .$role['name']. '"></button>'
                                : '';
                        echo '
                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center pepe">'.
                            $role['name'] .
                            '<div class="wrpl_actions">
                            ' . $btns . '    
                            </div>
                            </li>';
                    }
                ?>
            </ul>
            <br>
            <form action="" method="post" class="p-1 ml-3">
                <div class="form-group row">
                    <input type="text" name="role_name" class="col-sm-10 form-control-plaintext" placeholder="New Role"  required min="3" max="100">

                    <div class="col-sm-2">
                        <input type="submit" name="wrpl_new_role" value="Add" class="btn btn-info btn-sm">
                    </div>
                </div>
            </form>
        </div>

        <!-- PRICE LISTS -->
        <div class="col-md-6">
            <h5>Price Lists</h5>
            <ul class="list-group">
                <?php
                foreach ($plists as $plist){
                    if($plist['id'] == 1){
                        continue;
                    }
                    $name_parent = $plist['id_parent'] > 0 ? ' ( based on ' . $price_list_controller->wrpl_get_price_list_name_by_id($plist['id_parent']) . ')'  : '';
                    echo '
                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">'.
                            $plist['description'] . $name_parent .
                            '<div class="wrpl_actions">
                                <button class="btn btn-info btn-sm wrpl_edit_price_list wrpl-edit" name="wrpl_edit_pl" data-toggle="modal" data-target="#wrpl_edit_pl_modal" data-pl-name="' .$plist['description']. '" data-pl-id=' . $plist['id'] .' data-pl-factor="' .$plist['factor']. '" data-pl-price_list="'.$plist['id_parent'].'" ></button>
                                <button class="btn btn-danger btn-sm wrpl_remove_price_list wrpl-trash" name="wrpl_remove_pl" data-toggle="modal" data-target="#wrpl_remove_pl_modal" data-pl-id="' .$plist['id']. '"></button>
                            </div>
                            </li> ';
                }
                ?>

            </ul>
            <br>

            <!-- Adding price Lists -->
            <button data-toggle="modal" data-target="#wrpl_add_pl_modal" class="btn btn-info btn-sm float-right">Add New Price List</button>
        </div>
    </div>
</div>

<?php include 'modals/__add_price_list_modal.php' ?>
<?php include 'modals/__remove_price_list_modal.php' ?>
<?php include 'modals/__remove_role_modal.php' ?>
<?php include 'modals/__edit_price_list_modal.php' ?>
<?php include 'modals/__edit_role_modal.php' ?>

