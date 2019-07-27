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

        <div class="col-md-6">
            <h5>Price Lists</h5>
            <ul class="list-group">
                <?php
                foreach ($plists as $plist){
                    echo '
                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">'.
                            $plist['description'] .
                            '<div class="wrpl_actions">
                                <button class="btn btn-info btn-sm wrpl_edit_price_list wrpl-edit" name="wrpl_edit_pl" data-toggle="modal" data-target="#wrpl_edit_pl_modal" data-pl-name="' .$plist['description']. '" data-pl-id=' . $plist['id'] .'></button>
                                <button class="btn btn-danger btn-sm wrpl_remove_price_list wrpl-trash" name="wrpl_remove_pl" data-toggle="modal" data-target="#wrpl_remove_pl_modal" data-pl-id="' .$plist['id']. '"></button>
                            </div>
                            </li>
                          ';
                }
                ?>

            </ul>
            <br>
            <form id="form_new_pl" action="" method="post" class="p-1 ml-3">
                <div class="form-group row">
                    <input type="text" id="wrpl_new_price_list_text" name="new_price_list" class="col-sm-10 form-control-plaintext" placeholder="New Price List" required min="3" max="100">

                    <div class="col-sm-2">
                        <input id="wrpl_new_price_list_btn" name="wrpl_new_price_list" type="submit"  value="Add" class="btn btn-info btn-sm" form="form_new_pl">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'modals/__remove_price_list_modal.php' ?>
<?php include 'modals/__remove_role_modal.php' ?>
<?php include 'modals/__edit_price_list_modal.php' ?>
<?php include 'modals/__edit_role_modal.php' ?>

