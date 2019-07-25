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
                    <strong>Price list name duplicated! </strong>The price list allready exist, please choose other name.
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

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <h5>Roles</h5>
            <ul class="list-group">
                <?php
                    foreach ($roles as $role){
                        echo '
                            <li class="list-group-item p-1 d-flex justify-content-between align-items-center">'.
                            $role['name'] .
                            ' <input type="text" name="role_description" value="' . $role['name'] . '" hidden><button class="btn btn-danger btn-sm wrpl_remove_role wrpl-trash" name="wrpl_remove_role" data-toggle="modal" data-target="" data-role-id=""></button>
                            </li>
                          ';
                    }
                ?>
            </ul>
            <br>
            <form action="" method="post" class="p-1 ml-3">
                <div class="form-group row">
                    <input type="text" class="col-sm-10 form-control-plaintext" placeholder="New Role"  required min="3" max="100">

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
                            ' <input type="text" name="price_list_description" value="' . $plist['id'] . '" hidden><button class="btn btn-danger btn-sm wrpl_remove_price_list wrpl-trash" name="wrpl_remove_pl" data-toggle="modal" data-target="#wrpl_remove_pl_modal" data-pl-id="' .$plist['id']. '"></button>
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

<?php include '_remove_price_list_modal.php' ?>

