<?php
$rules = $price_list_controller->wrpl_get_rules();
var_dump($rules);
if(isset($_POST['assign_price_list_category'])){
    $result = $price_list_controller->wrpl_assign_pl_to_category($_POST['wrpl_cat_id'],$_POST['price_list_categories']);
        echo '<div class="alert alert-'.$result['type'].' alert-dismissible fade show" role="alert">
                    '. $result['msg'] .'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
               </div>';
    $rules = $price_list_controller->wrpl_get_rules();

}
?>

<div class="row p-3">
    <p>Assign a price list to product categories, so that all products that correspond to the category is going to have only the price that corresponds to the assigned price list.</p>
    <p><strong>Note:</strong>  Please note that if for a certain category you do not choose a list, all products belonging to this category will see the prices of the list that is by default.</p>
</div>

<div class="container-fluid pb-3">
    <div class="row pb-3">
        <div class="col-6">
            <h5>Categories</h5>
            <div id="default-tree"></div>
        </div>
        <div class="col-6">
            <br><br>
            <form action="" method="post">
                <div class="form-group row">
                    <label for="price_list_categories" id="price_list_categories_label" class=" form-control-sm col-sm-3 disabled">Choose Price List:</label>
                    <select name="price_list_categories" class="form-control form-control-sm col-sm-8 ml-3" id="price_list_categories" disabled>
                        <?php
                        $plists = $price_list_controller->wrpl_get_price_lists();
                        foreach ($plists as $plist) {
                            echo "<option value='{$plist['id']}' >{$plist['description']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <input type="text" name="wrpl_cat_id" id="wrpl_cat_id" hidden>
                <div class="form-group">
                    <input type="submit" class="btn btn-info btn-sm float-right mr-3" name="assign_price_list_category" id="btn_price_list_cat" value="Create Rule" disabled>
                </div>


            </form>
        </div>
    </div>
    <hr>
    <h5 class="text-center">Rules</h5>
    <div class="row">

        <br>
        <br>
        <div class="col-2"></div>
        <div class="col-8">
            <ul id="wrpl_rules_categories" class="list-group">
                <?php
                if(count($rules) > 0){
                    foreach ($rules as $rule){
                        echo '<li class="list-group-item d-flex justify-content-between align-items-center p-2">
                            ' . get_term( $rule['id_category'], 'product_cat')->name . ' => ' . $rule['description']  . '
                            <span class="wrpl-sortable"></span>
                        </li>';
                    }
                }else{
                    echo '<p>No rules at this moment.</p>';
                }
                ?>
            </ul>
        </div>
        <div class="col-2"></div>
    </div>
</div>



